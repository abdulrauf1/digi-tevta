<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('ucfirst(Auth::user()->roles[0]->name) Dashboard') }}
        </h2>
    </x-slot>

    <div class="flex space-x-2 mb-8 ">
        <a href="#" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-user-plus mr-2"></i>Add Trainee
        </a>
        <a href="#" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-chalkboard-teacher mr-2"></i>Add Trainer
        </a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Courses Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                    <i class="fas fa-book text-blue-500 dark:text-blue-300 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Courses</h3>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['totalCourses'] }}</p>
                </div>
            </div>
        </div>

        <!-- Total Trainees Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                    <i class="fas fa-user-graduate text-green-500 dark:text-green-300 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Trainees</h3>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['totalTrainees'] }}</p>
                </div>
            </div>
        </div>

        <!-- Total Trainers Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900">
                    <i class="fas fa-chalkboard-teacher text-purple-500 dark:text-purple-300 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Trainers</h3>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['totalTrainers'] }}</p>
                </div>
            </div>
        </div>

        <!-- Pending Applications Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900">
                    <i class="fas fa-clock text-yellow-500 dark:text-yellow-300 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending Applications</h3>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['pendingApplications'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Trainees -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Recent Trainees</h3>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($recentTrainees as $trainee)
                <div class="p-6 flex items-center">
                    <div class="flex-shrink-0">
                        <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($trainee->user->name) }}&background=4f46e5&color=fff" alt="{{ $trainee->user->name }}">
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $trainee->user->name }}</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $trainee->user->email }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $trainee->education_level }}
                        </p>
                    </div>
                    <div class="ml-auto">
                        <span class="px-2 py-1 text-xs rounded-full {{ $trainee->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($trainee->status) }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                    No trainees found.
                </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Trainers -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Recent Trainers</h3>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($recentTrainers as $trainer)
                <div class="p-6 flex items-center">
                    <div class="flex-shrink-0">
                        <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($trainer->user->name) }}&background=4f46e5&color=fff" alt="{{ $trainer->user->name }}">
                    </div>
                    <div class="ml-4">
                        <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $trainer->user->name }}</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $trainer->user->email }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ $trainer->specialization }}
                        </p>
                    </div>
                    <div class="ml-auto">
                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                            {{ $trainer->experience_years }} yrs exp
                        </span>
                    </div>
                </div>
                @empty
                <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                    No trainers found.
                </div>
                @endforelse
            </div>
        </div>
    </div>

</x-app-layout>


    