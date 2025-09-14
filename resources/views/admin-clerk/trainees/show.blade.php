<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col space-y-2">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Trainee Details') }}
                </h2>
                <div class="flex space-x-2">
                    <a href="{{ route('admin-clerk.trainees.edit', $trainee) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-medium rounded-md transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                        Edit Trainee
                    </a>
                    <a href="{{ route('admin-clerk.trainees.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-md transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        All Trainees
                    </a>
                </div>
            </div>
            
            <!-- Breadcrumbs -->
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                            <svg class="w-3 h-3 mr-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <a href="{{ route('admin-clerk.trainees.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">Trainees</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mx-1 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">{{ $trainee->name }}</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Profile Header -->
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600 dark:text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $trainee->name }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Trainee Profile</p>
                            </div>
                        </div>
                        <div class="mt-4 md:mt-0 flex space-x-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                Active
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                Member since {{ $trainee->created_at->format('M Y') }}
                            </span>
                        </div>
                    </div>

                    <!-- Information Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Personal Information Card -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow-sm">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Personal Information
                            </h4>
                            
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Full Name</p>
                                    <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ $trainee->name }}</p>
                                </div>
                                
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Email Address</p>
                                    <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                        {{ $trainee->email }}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </p>
                                </div>
                                
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone Number</p>
                                    <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                        {{ $trainee->phone }}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information Card -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow-sm">
                            <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-8m-8 0H3m2 0h2M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                Additional Information
                            </h4>
                            
                            <div class="space-y-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Address</p>
                                    <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">{{ $trainee->address }}</p>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Account Created</p>
                                        <p class="mt-1 text-md font-semibold text-gray-900 dark:text-white">
                                            {{ $trainee->created_at->format('M d, Y') }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $trainee->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    
                                    <div>
                                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</p>
                                        <p class="mt-1 text-md font-semibold text-gray-900 dark:text-white">
                                            {{ $trainee->updated_at->format('M d, Y') }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $trainee->updated_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-between space-y-4 sm:space-y-0">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin-clerk.trainees.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-md transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                </svg>
                                Back to All Trainees
                            </a>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('admin-clerk.trainees.edit', $trainee) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-medium rounded-md transition-colors duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Edit Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>