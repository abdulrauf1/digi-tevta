<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('ucfirst(Auth::user()->roles[0]->name) Dashboard') }}
        </h2>
    </x-slot>

    <a href="#" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        <i class="fas fa-plus mr-2"></i>New Course
    </a>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 mt-4">
    <!-- Total Users Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                <i class="fas fa-users text-blue-500 dark:text-blue-300 text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Users</h3>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['totalUsers'] }}</p>
            </div>
        </div>
    </div>

    <!-- Total Courses Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                <i class="fas fa-book text-green-500 dark:text-green-300 text-xl"></i>
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
            <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900">
                <i class="fas fa-user-graduate text-purple-500 dark:text-purple-300 text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Trainees</h3>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['totalTrainees'] }}</p>
            </div>
        </div>
    </div>

    <!-- Pending Assessments Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900">
                <i class="fas fa-tasks text-yellow-500 dark:text-yellow-300 text-xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending Assessments</h3>
                <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['pendingAssessments'] }}</p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Courses -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Recent Courses</h3>
        </div>
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($recentCourses as $course)
            <div class="p-6 flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-book-open text-blue-500 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $course->title }}</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Trainer: {{ $course->trainer->name }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Duration: {{ $course->duration_hours }} hours</p>
                </div>
                <div class="ml-auto">
                    <span class="px-2 py-1 text-xs rounded-full {{ $course->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($course->status) }}
                    </span>
                </div>
            </div>
            @empty
            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                No courses found.
            </div>
            @endforelse
        </div>
    </div>

    <!-- Recent Users -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Recent Users</h3>
        </div>
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($recentUsers as $user)
            <div class="p-6 flex items-center">
                <div class="flex-shrink-0">
                    <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=4f46e5&color=fff" alt="{{ $user->name }}">
                </div>
                <div class="ml-4">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</h4>
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 capitalize">
                        {{ $user->roles->first()->name ?? 'No role assigned' }}
                    </p>
                </div>
                <div class="ml-auto">
                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                        {{ $user->created_at->diffForHumans() }}
                    </span>
                </div>
            </div>
            @empty
            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                No users found.
            </div>
            @endforelse
        </div>
    </div>
</div>

</x-app-layout>



