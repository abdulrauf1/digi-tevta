<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Enrollment Details') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="mb-6 flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white transition-colors duration-200">
                            <i class="fas fa-home mr-2.5"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                            <a href="{{ route('admin-clerk.enrollments.index') }}" class="ml-4 text-sm font-medium text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white transition-colors duration-200">
                                Enrollments
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 text-xs"></i>
                            <span class="ml-4 text-sm font-medium text-blue-500 dark:text-blue-400">Details</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Header Card -->
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 dark:from-gray-800 dark:to-gray-900 rounded-xl shadow-lg overflow-hidden mb-8">
                <div class="p-6 flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-white">Enrollment #{{ $enrollment->id }}</h1>
                        <p class="text-blue-100 dark:text-gray-300 mt-1">Detailed enrollment information</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full 
                            @if($enrollment->status == 'confirm') bg-green-100 text-green-800
                            @elseif($enrollment->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($enrollment->status == 'cancel') bg-red-100 text-red-800
                            @elseif($enrollment->status == 'altered') bg-purple-100 text-purple-800
                            @endif">
                            <i class="fas 
                                @if($enrollment->status == 'confirm') fa-check-circle 
                                @elseif($enrollment->status == 'pending') fa-clock
                                @elseif($enrollment->status == 'cancel') fa-times-circle
                                @elseif($enrollment->status == 'altered') fa-exclamation-circle
                                @endif mr-1.5"></i>
                            {{ ucfirst($enrollment->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Enrollment Information Card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg">
                    <div class="p-5 border-b border-gray-100 dark:border-gray-700 flex items-center">
                        <div class="bg-blue-100 dark:bg-blue-900/30 p-3 rounded-lg mr-4">
                            <i class="fas fa-clipboard-list text-blue-600 dark:text-blue-400 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Enrollment Information</h3>
                    </div>
                    <div class="p-5 space-y-4">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-gray-500 dark:text-gray-400 font-medium">Enrollment ID</span>
                            <span class="text-gray-800 dark:text-white font-semibold">#{{ $enrollment->id }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <span class="text-gray-500 dark:text-gray-400 font-medium">Enrolled Date</span>
                            <span class="text-gray-800 dark:text-white">{{ $enrollment->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-500 dark:text-gray-400 font-medium">Last Updated</span>
                            <span class="text-gray-800 dark:text-white">{{ $enrollment->updated_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Trainee Information Card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg">
                    <div class="p-5 border-b border-gray-100 dark:border-gray-700 flex items-center">
                        <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-lg mr-4">
                            <i class="fas fa-user-graduate text-green-600 dark:text-green-400 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Trainee Information</h3>
                    </div>
                    <div class="p-5 space-y-4">
                        <div class="flex items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-full p-2 mr-4">
                                <i class="fas fa-user text-gray-600 dark:text-gray-300"></i>
                            </div>
                            <div>
                                <p class="text-gray-800 dark:text-white font-medium">{{ $enrollment->trainee->user->name ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Trainee Name</p>
                            </div>
                        </div>
                        <div class="flex items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-full p-2 mr-4">
                                <i class="fas fa-id-card text-gray-600 dark:text-gray-300"></i>
                            </div>
                            <div>
                                <p class="text-gray-800 dark:text-white font-medium">{{ $enrollment->trainee->cnic }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">CNIC</p>
                            </div>
                        </div>
                        <div class="flex items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-full p-2 mr-4">
                                <i class="fas fa-phone text-gray-600 dark:text-gray-300"></i>
                            </div>
                            <div>
                                <p class="text-gray-800 dark:text-white font-medium">{{ $enrollment->trainee->contact }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Contact Number</p>
                            </div>
                        </div>
                        <div class="flex items-center py-2">
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-full p-2 mr-4">
                                <i class="fas fa-envelope text-gray-600 dark:text-gray-300"></i>
                            </div>
                            <div>
                                <p class="text-gray-800 dark:text-white font-medium">{{ $enrollment->trainee->user->email ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Email Address</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Course Information Card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg">
                    <div class="p-5 border-b border-gray-100 dark:border-gray-700 flex items-center">
                        <div class="bg-purple-100 dark:bg-purple-900/30 p-3 rounded-lg mr-4">
                            <i class="fas fa-book text-purple-600 dark:text-purple-400 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Course Information</h3>
                    </div>
                    <div class="p-5 space-y-4">
                        <div class="flex items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-full p-2 mr-4">
                                <i class="fas fa-graduation-cap text-gray-600 dark:text-gray-300"></i>
                            </div>
                            <div>
                                <p class="text-gray-800 dark:text-white font-medium">{{ $enrollment->course->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Course Name</p>
                            </div>
                        </div>
                        <div class="flex items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-full p-2 mr-4">
                                <i class="fas fa-hashtag text-gray-600 dark:text-gray-300"></i>
                            </div>
                            <div>
                                <p class="text-gray-800 dark:text-white font-medium">{{ $enrollment->course->code }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Course Code</p>
                            </div>
                        </div>
                        <div class="flex items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-full p-2 mr-4">
                                <i class="fas fa-clock text-gray-600 dark:text-gray-300"></i>
                            </div>
                            <div>
                                <p class="text-gray-800 dark:text-white font-medium">{{ $enrollment->course->duration }} hours</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Duration</p>
                            </div>
                        </div>
                        <div class="flex items-center py-2">
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-full p-2 mr-4">
                                <i class="fas fa-chalkboard-teacher text-gray-600 dark:text-gray-300"></i>
                            </div>
                            <div>
                                <p class="text-gray-800 dark:text-white font-medium">{{ $enrollment->course->method }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Method</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Session Information Card -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden transition-all duration-300 hover:shadow-lg">
                    <div class="p-5 border-b border-gray-100 dark:border-gray-700 flex items-center">
                        <div class="bg-amber-100 dark:bg-amber-900/30 p-3 rounded-lg mr-4">
                            <i class="fas fa-calendar-alt text-amber-600 dark:text-amber-400 text-xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Session Information</h3>
                    </div>
                    <div class="p-5 space-y-4">
                        <div class="flex items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-full p-2 mr-4">
                                <i class="fas fa-signature text-gray-600 dark:text-gray-300"></i>
                            </div>
                            <div>
                                <p class="text-gray-800 dark:text-white font-medium">{{ $enrollment->enrollmentSession->name ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Session Name</p>
                            </div>
                        </div>
                        <div class="flex items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-full p-2 mr-4">
                                <i class="fas fa-hourglass-half text-gray-600 dark:text-gray-300"></i>
                            </div>
                            <div>
                                <p class="text-gray-800 dark:text-white font-medium">{{ $enrollment->enrollmentSession->duration ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Duration</p>
                            </div>
                        </div>
                        <div class="flex items-center py-2 border-b border-gray-100 dark:border-gray-700">
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-full p-2 mr-4">
                                <i class="fas fa-play-circle text-gray-600 dark:text-gray-300"></i>
                            </div>
                            <div>
                                <p class="text-gray-800 dark:text-white font-medium">{{ $enrollment->enrollmentSession->start_date ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Start Date</p>
                            </div>
                        </div>
                        <div class="flex items-center py-2">
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-full p-2 mr-4">
                                <i class="fas fa-flag-checkered text-gray-600 dark:text-gray-300"></i>
                            </div>
                            <div>
                                <p class="text-gray-800 dark:text-white font-medium">{{ $enrollment->enrollmentSession->end_date ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">End Date</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 mb-6">
                <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4">
                    <a href="{{ route('admin-clerk.enrollments.index') }}" class="px-5 py-2.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-2"></i> Back to List
                    </a>
                    <a href="{{ route('admin-clerk.enrollments.edit', $enrollment) }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors duration-200 flex items-center justify-center">
                        <i class="fas fa-edit mr-2"></i> Edit Enrollment
                    </a>
                </div>
            </div>
        </div>
    </div>

    <style>
        .card-hover {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
        }
    </style>
</x-app-layout>