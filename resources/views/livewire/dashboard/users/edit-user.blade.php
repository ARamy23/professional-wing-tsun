<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User') }}
        </h2>
    </x-slot>

    <div class="py-12 md:px-24">
        <div>
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <div class="px-4 sm:px-0">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Profile</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Some Information about this user.
                        </p>
                    </div>
                </div>

                <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="shadow sm:rounded-md sm:overflow-hidden">
                        <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button
                                    class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition duration-150 ease-in-out">
                                    <img class="h-16 w-17 rounded-full object-cover"
                                         src="{{ $user->profile_photo_url }}"
                                         alt="{{ $user->name }}"/>
                                </button>
                            @else
                                <button
                                    class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                    <div>{{ $user->name }}</div>

                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4"
                                             xmlns="http://www.w3.org/2000/svg"
                                             viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </button>
                            @endif
                            <h2>
                                <span class="font-bold">Name:</span> {{ $user->name }}
                            </h2>

                            <h2>
                                <span class="font-bold">Email:</span> {{ $user->email }}
                            </h2>
                        </div>
                    </div>
                </div>


                <div class="md:col-span-1">
                    <div class="px-4 sm:px-0">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">Edit Profile</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            You can edit some of the user's data in here.
                        </p>
                    </div>
                </div>
                <div class="mt-5 md:mt-0 md:col-span-2">

                    @error('grade') @livewire('utils.alert', ['message' => $message]) @enderror
                    @error('certifiedGrade') @livewire('utils.alert', ['message' => $message])  @enderror
                    @error('excusesAllowance') @livewire('utils.alert', ['message' => $message]) @enderror
                    @error('role') @livewire('utils.alert', ['message' => $message]) @enderror
                    @error('branch') @livewire('utils.alert', ['message' => $message]) @enderror

                    @if(session('message'))
                        @livewire('utils.success', ['message' => session('message')])
                    @endif

                    <form wire:submit.prevent="editProfile">
                        <div class="shadow sm:rounded-md sm:overflow-hidden">
                            <div class="px-4 py-5 bg-white space-y-6 sm:p-6">
                                <div class="grid grid-cols-3 gap-6">
                                    <div class="col-span-3 sm:col-span-2">
                                        <label for="company_website" class="block text-sm font-medium text-gray-700">
                                            Grade
                                        </label>
                                        <div class="mt-1 flex rounded-md shadow-sm">
                                            <input type="number" wire:model="grade" placeholder="Grade (Can't be empty)"
                                                   class="px-3 py-3 placeholder-gray-400 text-gray-700 relative rounded text-sm shadow outline-none focus:outline-none focus:shadow-outline w-full"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-6">
                                    <div class="col-span-3 sm:col-span-2">
                                        <label for="company_website" class="block text-sm font-medium text-gray-700">
                                            Certified Grade
                                        </label>
                                        <div class="mt-1 flex rounded-md shadow-sm">
                                            <input type="number" wire:model="certifiedGrade"
                                                   placeholder="Certified Grade (Can't be empty)"
                                                   class="px-3 py-3 placeholder-gray-400 text-gray-700 relative rounded text-sm shadow outline-none focus:outline-none focus:shadow-outline w-full"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-6">
                                    <div class="col-span-3 sm:col-span-2">
                                        <label for="company_website" class="block text-sm font-medium text-gray-700">
                                            Excuses Allowance
                                        </label>
                                        <div class="mt-1 flex rounded-md shadow-sm">
                                            <input type="number" wire:model="excusesAllowance"
                                                   placeholder="Certified Grade (Can't be empty)"
                                                   class="px-3 py-3 placeholder-gray-400 text-gray-700 relative rounded text-sm shadow outline-none focus:outline-none focus:shadow-outline w-full"/>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-6">
                                    <div class="col-span-3 sm:col-span-2">
                                        <label for="company_website" class="block text-sm font-medium text-gray-700">
                                            Role
                                        </label>
                                        <x-jet-dropdown align="right" width="48">
                                            <x-slot name="trigger">
                                                <input wire:model="role"
                                                       placeholder="Select a role..."
                                                       class="px-3 py-3 placeholder-gray-400 text-gray-700 relative rounded text-sm shadow outline-none focus:outline-none focus:shadow-outline w-full"
                                                       type="text"
                                                       autocomplete="off">
                                            </x-slot>

                                            <x-slot name="content">
                                                @foreach($roles as $role)
                                                    <span wire:click="selectRole({{ $role->id }})"
                                                          class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                                    {{ $role->title }}
                                            </span>
                                                @endforeach
                                            </x-slot>
                                        </x-jet-dropdown>
                                    </div>
                                </div>

                                <div class="grid grid-cols-3 gap-6">
                                    <div class="col-span-3 sm:col-span-2">
                                        <label for="company_website" class="block text-sm font-medium text-gray-700">
                                            Branch
                                        </label>
                                        <x-jet-dropdown align="right" width="48">
                                            <x-slot name="trigger">
                                                <input for="branch" id="branch" name="branch" wire:model="branch"
                                                       placeholder="Select a branch..."
                                                       class="px-3 py-3 placeholder-gray-400 text-gray-700 relative rounded text-sm shadow outline-none focus:outline-none focus:shadow-outline w-full"
                                                       type="text"
                                                       autocomplete="off">
                                            </x-slot>

                                            <x-slot name="content">
                                                @foreach($branches as $branch)
                                                    <span wire:click="select({{ $branch->id }})"
                                                          class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                                    {{ $branch->name }}
                                            </span>
                                                @endforeach
                                            </x-slot>
                                        </x-jet-dropdown>
                                    </div>
                                </div>
                            </div>
                            <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                                <button type="submit"
                                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Save
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
