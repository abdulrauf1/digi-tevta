<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Reports Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-6">System Overview</h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <div class="bg-blue-100 dark:bg-blue-800 p-6 rounded-lg shadow-md">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-blue-500 text-white mr-4">
                                    <i class="fas fa-book text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold">{{ $stats['total_courses'] }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">Total Courses</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-green-100 dark:bg-green-800 p-6 rounded-lg shadow-md">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-green-500 text-white mr-4">
                                    <i class="fas fa-chalkboard-teacher text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold">{{ $stats['total_trainers'] }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">Total Trainers</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-purple-100 dark:bg-purple-800 p-6 rounded-lg shadow-md">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-purple-500 text-white mr-4">
                                    <i class="fas fa-user-graduate text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold">{{ $stats['total_trainees'] }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">Total Trainees</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-yellow-100 dark:bg-yellow-800 p-6 rounded-lg shadow-md">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-yellow-500 text-white mr-4">
                                    <i class="fas fa-clipboard-list text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold">{{ $stats['total_enrollments'] }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">Total Enrollments</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow-md">
                            <h4 class="text-lg font-semibold mb-4">Recent Activities</h4>
                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white">
                                            <i class="fas fa-user-plus"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium">New trainee registered</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">2 hours ago</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-green-500 flex items-center justify-center text-white">
                                            <i class="fas fa-book"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium">New course added</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">5 hours ago</p>
                                    </div>
                                </div>
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-purple-500 flex items-center justify-center text-white">
                                            <i class="fas fa-clipboard-check"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-sm font-medium">New enrollment completed</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">1 day ago</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow-md">
                            <h4 class="text-lg font-semibold mb-4">Quick Actions</h4>
                            <div class="grid grid-cols-1 gap-4">
                                <a href="{{ route('admin.courses.create') }}" class="flex items-center p-3 bg-blue-50 dark:bg-blue-900 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-800 transition">
                                    <div class="p-2 rounded-full bg-blue-500 text-white mr-3">
                                        <i class="fas fa-plus"></i>
                                    </div>
                                    <span>Add New Course</span>
                                </a>
                                <a href="{{ route('admin.trainers.create') }}" class="flex items-center p-3 bg-green-50 dark:bg-green-900 rounded-lg hover:bg-green-100 dark:hover:bg-green-800 transition">
                                    <div class="p-2 rounded-full bg-green-500 text-white mr-3">
                                        <i class="fas fa-user-plus"></i>
                                    </div>
                                    <span>Add New Trainer</span>
                                </a>
                                <a href="{{ route('admin.trainees.create') }}" class="flex items-center p-3 bg-purple-50 dark:bg-purple-900 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-800 transition">
                                    <div class="p-2 rounded-full bg-purple-500 text-white mr-3">
                                        <i class="fas fa-user-graduate"></i>
                                    </div>
                                    <span>Add New Trainee</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>