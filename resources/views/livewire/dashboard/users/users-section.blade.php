<div>
    <div class="p-6 bg-gray-200 bg-opacity-25">

        <div class="flex items-center">
            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                 viewBox="0 0 24 24" class="w-8 h-8 text-gray-400">
                <path
                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
            </svg>
            <div class="ml-4 text-lg text-gray-600 leading-7 font-semibold"><h1>Users</h1></div>
        </div>

        <div>
            <div class="my-2 text-sm text-gray-500">
                You can monitor everyone on the system from here.
            </div>
        </div>
    </div>

    <div class="p-3 bg-gray-200 bg-opacity-25">
        <input type="text" wire:model="search" placeholder="Search for users..."
               class="px-3 py-3 placeholder-gray-400 text-gray-700 relative rounded text-sm shadow outline-none focus:outline-none focus:shadow-outline w-full"/>
    </div>

    <div class="flex flex-col">
        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Role
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Sessions Credit
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Grade
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Certified Grade
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Branch
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Excuses Count
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Allowed Excuses
                            </th>

                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">

                        @if ($users == null || $users->isEmpty())
                            <tr>
                                <td class="content-center">
                                    <h2 class="p-4 place-self-center">No Users Found</h2>
                                </td>
                            </tr>
                        @else
                            @foreach ($users as $user)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                                    <a
                                                        href="{{route('edit-user', $user->id)}}"
                                                        class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition duration-150 ease-in-out">
                                                        <img class="h-8 w-8 rounded-full object-cover"
                                                             src="{{ $user->profile_photo_url }}"
                                                             alt="{{ $user->name }}"/>
                                                    </a>
                                                @else
                                                    <a
                                                        href="{{route('edit.user', $user->id)}}"
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
                                                    </a>
                                                @endif
                                            </div>
                                            <div class="ml-4">
                                                @if ($user->id == auth()->id())
                                                    <div class="text-sm font-medium text-green-500">
                                                        You
                                                    </div>
                                                @else
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $user->name }}
                                                    </div>
                                                @endif
                                                <div class="text-sm text-gray-500">
                                                    {{ $user->email }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                  Active
                </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $user->roles->first()->title }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($user->hasRole('student'))
                                            {{ $user->sessions_credit }}
                                        @else
                                            N/A
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $user->grade->english_number }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $user->certifiedGrade->english_number }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $user->branch->name }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $user->excuses_count }}
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $user->allowed_excuses }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
