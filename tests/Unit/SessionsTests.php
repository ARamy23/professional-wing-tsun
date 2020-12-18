<?php

namespace Tests\Unit;

use App\Exceptions\AttendingSessionAfterItHasEndedException;
use App\Exceptions\AttendingSessionBeforeItsTimeException;
use App\Exceptions\CancelSessionInSameDayException;
use App\Exceptions\NoMoreSlotsToBookException;
use App\Exceptions\SurpassedAllowedExcusesException;
use App\Exceptions\TryingToBookASessionThatStarted;
use App\Exceptions\TryingToBookASessionWithoutSessionCredit;
use App\Exceptions\TryingToCancelASessionThatStarted;
use App\Http\Controllers\Attender;
use App\Http\Controllers\Booker;
use App\Http\Controllers\SessionActivityState;
use App\Http\Controllers\SessionManager;
use App\Jobs\AttendanceCheck;
use App\Mail\VacancyAvailableMail;
use App\Mail\YouAreNowBooked;
use App\Models\Day;
use App\Models\Session;
use App\Models\User;
use App\Models\Week;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Spatie\TestTime\TestTime;
use Tests\TestCase;

class SessionsTests extends TestCase
{
    use WithFaker, RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:fresh --seed');
        TestTime::setTestNow(Carbon::today());
    }

    public function testSessionsAreSeededSoThatThereAreThreeEverydayExceptOnOffDays()
    {

        // Week is created
        $this->assertDatabaseCount('weeks', 1);
        // Week has 7 days
        $this->assertDatabaseCount('days', 7);

        $week = Week::find(1);
        $day = Day::find(2);
        $offDay = Day::where('is_off_day', '=', true)->firstOrFail();
        $session = Session::find(1);

        $this->assertTrue($week->days->count() == 7);

        $this->assertTrue($day->sessions->count() == 3);
        $this->assertTrue($offDay->sessions->isEmpty());

        $this->assertTrue($session->attendees->isEmpty());
    }

    public function testUsersCanBookAnySessionAsLongAsItDidntExceedTheInitialLimit()
    {
        // Create User
        $firstAttendee = User::factory()->create();
        // Get 1st Session
        $firstWorkingDay = Day::where('date', '!=', Carbon::today())->where('is_off_day', '!=', '1')->first();
        $firstSession = $firstWorkingDay->sessions->first();
        // Make User book a session
        Booker::book($firstSession, $firstAttendee);
        $firstSession->refresh();
        // Assert that the session is booked by the user
        self::assertTrue($firstSession->attendees->contains($firstAttendee));
        // Create 5 Users
        $batchOfAttendees = User::factory()->count(5)->create();
        // Make them Book the session
        $batchOfAttendees->each(function ($attendee) use ($firstSession) {
            Booker::book($firstSession, $attendee);
        });
        // Create another user
        $lateStudent = User::factory()->create();
        // Make the user Book the session & assert that user got an error
        $this->expectException(NoMoreSlotsToBookException::class);
        Booker::book($firstSession, $lateStudent);
    }

    public function testUserCanCancelASessionAsLongAsTheSessionIsNoTakingPlaceInTheSameDay()
    {
        // Create a User
        $attendee = User::factory()->create();
        $workingDay = Day::where('date', '!=', Carbon::tomorrow())->where('is_off_day', '!=', '1')->first();
        $secondSession = $workingDay->sessions[1];
        $attendee->refresh();
        // Book a session
        Booker::book($secondSession, $attendee);
        // Cancel this session
        Booker::cancel($secondSession, $attendee);
        // Get another session
        $sessionInToday = $workingDay->sessions->first();
        // Change the session's from_time into today
        $sessionInToday->from_time = Carbon::parse('tomorrow 6pm');
        // Book that session
        Booker::book($sessionInToday, $attendee);
        TestTime::addDay();
        // Cancel it
        $this->expectException(CancelSessionInSameDayException::class);
        Booker::cancel($sessionInToday, $attendee);
    }

    public function testUserCanCancelAsLongAsHeHasExcusesOrItsCancelledInTheSameDay()
    {
        $this->artisan('migrate:fresh --seed');
        // Create a User
        $attendee = User::factory()->create();
        $workingDay = Day::where([
            ['is_off_day', '!=', '1'],
            ['date', '=', Carbon::today()->addDays(3)]
        ])->first();
        $session = $workingDay->sessions->first();
        $attendee->refresh();
        $originalAllowedExcuses = $attendee->allowed_excuses;
        // Book a session
        Booker::book($session, $attendee);
        // Cancel the session in the same day
        Booker::cancel($session, $attendee);

        $attendee->refresh();
        // Assert That Excuses count didn't get incremented
        self::assertTrue($attendee->excuses_count > 0);
        // Assert That Allowed Excuses didn't get decremented
        self::assertFalse($attendee->allowed_excuses < $originalAllowedExcuses);
        // Book another session
        Booker::book($session, $attendee);
        // Simulate that a day has passed
        TestTime::addDay();
        // Cancel this session
        Booker::cancel($session, $attendee);
        // Update User
        $attendee->refresh();
        // assert that excuses count went up by one
        $this->assertTrue($attendee->excuses_count > 1);
        // Assert That Allowed Excuses got decremented
        self::assertTrue($attendee->allowed_excuses < $originalAllowedExcuses);
        $firstAllowedExcusesDecremental = $attendee->allowed_excuses;
        // Book another session
        Booker::book($session, $attendee);
        // Simulate that a day has passed
        TestTime::addDay();
        // Cancel this session
        Booker::cancel($session, $attendee);
        // Update User
        $attendee->refresh();
        // assert that excuses count went up by one
        $this->assertTrue($attendee->excuses_count > 2);
        // Assert That Allowed Excuses got decremented
        self::assertTrue($attendee->allowed_excuses < $firstAllowedExcusesDecremental);
        // Book another session
        Booker::book($session, $attendee);
        // Simulate that a day has passed
        TestTime::addDay();
        // Cancel this session && expect SurpassedAllowedExcusesException
        $this->expectException(SurpassedAllowedExcusesException::class);
        Booker::cancel($session, $attendee);
    }

    public function testWhenUserCancelsASessionAllUsersAreNotifiedExceptTheOneWhoCancelledAndThoseAlreadyInThisSession()
    {
        // Create 6 Users who wants to book
        Mail::fake();
        $usersWhomAreBooking = User::factory()->count(6)->create();
        $usersWhomAreBooking->each(function ($user) {
            $user->refresh();
        });
        // Create 3 normal users
        $otherUsers = User::factory()->count(3)->create();
        $otherUsers->each(function ($user) {
            $user->refresh();
        });
        // Get a user who wants to cancel
        $userWhoWantsToCancel = $usersWhomAreBooking->last();
        // Get a session
        $firstWorkingDay = Day::where('date', '!=', Carbon::tomorrow())->where('is_off_day', '!=', '1')->first();
        $session = $firstWorkingDay->sessions->first();
        // Make all 6 users book this session
        $usersWhomAreBooking->each(function (User $user) use ($session) {
            Booker::book($session, $user);
        });
        // Make the user cancel his booking & Check if event is fired
        Booker::cancel($session, $userWhoWantsToCancel);
        // Check if the 3 other users got an email
        $otherUsers->each(function ($user) {
            Mail::assertSent(VacancyAvailableMail::class, function ($mail) use ($user) {
                // Assert a message was sent to the given users...
                return $mail->hasTo($user->email);
            });
        });
        // Make sure users who are attending didn't get the email
        $session->attendees->each(function ($attendingUser) {
            Mail::assertNotSent(VacancyAvailableMail::class, function ($mail) use ($attendingUser) {
                return $mail->hasTo($attendingUser->email);
            });
        });
        // Make sure the user who cancelled didn't get the email
        Mail::assertNotSent(VacancyAvailableMail::class, function ($mail) use ($userWhoWantsToCancel) {
            return $mail->hasTo($userWhoWantsToCancel->email);
        });
    }

    public function testWhenUserScansQRCodeInDojoItRoutesHimToAttendanceEndpointAndThatMakesHimAttended()
    {
        // Make User
        $attendingUser = User::factory()->create();
        $attendingUser->refresh();
        // Get a Session
        $firstWorkingDay = Day::where('date', '!=', Carbon::tomorrow())->where('is_off_day', '!=', '1')->first();
        $session = $firstWorkingDay->sessions->first();
        // Book this session for the user
        Booker::book($session, $attendingUser);
        // Fast-Forward time to the session's from_time
        $sessionStartTime = Carbon::parse($session->from_time);
        TestTime::setTestNow($sessionStartTime);

        Attender::attend($attendingUser, $session);

        self::assertTrue($attendingUser->didAttend($session));
    }

    public function testUserCantAttendASessionBeforeItsFromDateByMoreThanFifteenMinutes()
    {
        // Make User
        $attendingUser = User::factory()->create();
        $attendingUser->refresh();
        // Get a Session
        $firstWorkingDay = Day::where('date', '!=', Carbon::tomorrow())->where('is_off_day', '!=', '1')->first();
        $session = $firstWorkingDay->sessions->first();
        // Book this session for the user
        Booker::book($session, $attendingUser);
        // Fast-Forward time to the before the session's start time by 1 hour
        $startTime = Carbon::parse($session->from_time);
        TestTime::setTestNow($startTime->subHour());

        // Attend the session && Expect that AttendingSessionBeforeItsTimeException was thrown
        $this->expectException(AttendingSessionBeforeItsTimeException::class);
        Attender::attend($attendingUser, $session);
        // Assert User was not recorded as attending
        self::assertFalse($attendingUser->didAttend($session));
    }

    public function testUserCantAttendASessionAfterItHasEndedBy15Minutes()
    {
        // Make User
        $attendingUser = User::factory()->create();
        $attendingUser->refresh();
        // Get a Session
        $firstWorkingDay = Day::where('date', '!=', Carbon::tomorrow())->where('is_off_day', '!=', '1')->first();
        $session = $firstWorkingDay->sessions->first();
        // Book this session for the user
        Booker::book($session, $attendingUser);
        // Fast-Forward time to the before the session's start time by 1 hour
        $endTime = Carbon::parse($session->to_time);
        TestTime::setTestNow($endTime->addMinutes(20));

        // Attend the session && Expect that AttendingSessionAfterItHasEndedException was thrown
        $this->expectException(AttendingSessionAfterItHasEndedException::class);
        Attender::attend($attendingUser, $session);
        // Assert User was not recorded as attending
        self::assertFalse($attendingUser->didAttend($session));
    }

    public function testThatOnEveryWorkingDayAtSevenUntilTenPMUsersWhoDidntMarkThemselvesAttendedInTheirBookedSessionsAreMarkedDidntAttend()
    {
        // Make Six Users
        $attendingUsers = User::factory()->count(6)->create();
        $attendingUsers->each(function (User $user) {
            $user->refresh();
        });
        // Get a session
        $firstWorkingDay = Day::where('date', '!=', Carbon::tomorrow())->where('is_off_day', '!=', '1')->first();
        $session = $firstWorkingDay->sessions->last();
        // Make Users Book the session
        $attendingUsers->each(function ($user) use($session) {
            Booker::book($session, $user);
        });
        // Fast-Forward time to end time
        $endTime = Carbon::parse($session->to_time);
        TestTime::setTestNow($endTime);


        AttendanceCheck::dispatch();

        // Assert that attendingUsers are marked as didnt_attend
        $attendingUsers->each(function ($user) use ($session) {
            self::assertTrue($user->didntAttend($session));
        });
    }

    public function testThatFullSessionsCanBeQueuedByUsersAndIfSomeoneCancelledThisUserIsAutomaticallyBookedToThisSessionAndAnEmailIsSentToHim() {
        // Make Six Users
        $usersWhoAreNotBooking = User::factory()->count(3)->create();
        $usersWhoAreNotBooking->each(function (User $user) {
            $user->refresh();
        });
        $attendingUsers = User::factory()->count(6)->create();
        $attendingUsers->each(function (User $user) {
            $user->refresh();
        });
        // Get a session
        $firstWorkingDay = Day::where('date', '!=', Carbon::tomorrow())->where('is_off_day', '!=', '1')->first();
        $session = $firstWorkingDay->sessions->last();
        // Make Users Book the session
        $attendingUsers->each(function ($user) use($session) {
            Booker::book($session, $user);
        });
        // Create a late user (like me 😭)
        $lateUser = User::factory()->create();
        $lateUser->refresh();
        // Queue this user
        Booker::queue($session, $lateUser);
        // Cancel first user in the session attendees
        Mail::fake();
        Booker::cancel($session, $attendingUsers->first());
        Mail::assertSent(YouAreNowBooked::class, function ($mail) use ($lateUser) {
            return $mail->hasTo($lateUser->email);
        });

        $usersWhoAreNotBooking->each(function ($user) {
            Mail::assertNotSent(VacancyAvailableMail::class, function ($mail) use ($user) {
                return $mail->hasTo($user->email);
            });
        });
    }

    public function testSessionStatusesForUsers()
    {
        // Make Six users
        $attendingUsers = User::factory()->count(6)->create();
        $attendingUsers->each(function (User $user) {
            $user->refresh();
        });
        $firstWorkingDay = Day::where('date', '!=', Carbon::tomorrow())->where('is_off_day', '!=', '1')->first();
        $session = $firstWorkingDay->sessions->last();

        // Assert that Session is bookable
        $attendingUsers->each(function (User $user) use($session) {
            $this->assertTrue(SessionManager::statusForUser($session, $user) == "bookable");
        });

        // Book users to this session

        $attendingUsers->each(function (User $user) use($session) {
            Booker::book($session, $user);
            $user->refresh();
            $session->refresh();
        });

        $attendingUsers->each(function (User $user) use($session) {
            $this->assertTrue(SessionManager::statusForUser($session, $user) == "cancellable");
        });

        $lateUser = User::factory()->create();
        $lateUser->refresh();

        $this->assertTrue(SessionManager::statusForUser($session, $lateUser) == "queueable");
        Booker::queue($session, $lateUser);

        $this->assertTrue(SessionManager::statusForUser($session, $lateUser) == "alreadyQueued");
    }

    public function testSessionAvailabilityStateAndMakeSureSessionIsNotBookableOrCancellableIfItsAnythingButNotYetStarted()
    {
        // Get a session
        $firstWorkingDay = Day::where('is_off_day', '!=', '1')->first();
        $session = $firstWorkingDay->sessions->first();

        $user = User::factory()->create();

        // Set time to 2:00 Pm
        TestTime::setTestNow(Carbon::parse('2:00 pm'));

        // Assert that session has not yet started
        $this->assertTrue(SessionManager::activityStateOf($session) == SessionActivityState::NOT_YET_STARTED);

        // Fast-forward time to starting time of the session + 10 mins
        TestTime::setTestNow(Carbon::parse($session->from_time)->addMinutes(10));

        // Assert that session is on going
        $this->assertTrue(SessionManager::activityStateOf($session) == SessionActivityState::ON_GOING);

        // Fast-Forward time to end date + 10 mins
        TestTime::setTestNow(Carbon::parse($session->to_time)->addMinutes(10));

        // Assert that session has ended
        $this->assertTrue(SessionManager::activityStateOf($session) == SessionActivityState::FINISHED);

        // Book & expect TryingToBookASessionThatStarted
        $this->expectException(TryingToBookASessionThatStarted::class);
        Booker::book($session, $user);
        // Cancel & expect TryingToCancelASessionThatStarted
        $this->expectException(TryingToCancelASessionThatStarted::class);
        Booker::cancel($session, $user);
    }

    public function testWhenUserBooksSessionWithNoSessionCreditItThrowsAnError()
    {
        // Create a user with 0 sessions credit
        $user = User::factory()->create(['sessions_credit' => 0]);
        $user->refresh();
        // Get a session
        $firstWorkingDay = Day::where('is_off_day', '!=', '1')->first();
        $session = $firstWorkingDay->sessions->first();
        // Book Session & Expect an exception
        $this->expectException(TryingToBookASessionWithoutSessionCredit::class);
        Booker::book($session, $user);
    }

    public function testWhenUserBooksSessionWithSessionCreditHeGetsBookedNormally()
    {
        // Create a user
        $user = User::factory()->create();
        $user->refresh();
        // Get a session
        $firstWorkingDay = Day::where('is_off_day', '!=', '1')->first();
        $session = $firstWorkingDay->sessions->first();
        // Make the user's session credit = 32
        $user->sessions_credit = 32;
        $user->refresh();
        // Book Session & Expect an exception
        Booker::book($session, $user);
        // Assert User was booked
        self::assertTrue($session->willBeAttendedBy($user));
    }

    public function testWhenSessionIsAttendedSessionCreditIsDecreased()
    {
        // Create a user
        $user = User::factory()->create();
        $user->refresh();
        // Get a session
        $firstWorkingDay = Day::where('is_off_day', '!=', '1')->first();
        $session = $firstWorkingDay->sessions->first();
        // Book Session & Expect an exception
        Booker::book($session, $user);
        TestTime::setTestNow($session->from_time);
        // Attend Session
        Attender::attend($user, $session);
        self::assertTrue($user->sessions_credit <= 0);
    }
}
