<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Enrollment Details') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <nav class="mb-4 flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                            <i class="fas fa-home mr-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400"></i>
                            <a href="{{ route('enrollments.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">
                                Enrollments
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400"></i>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Details</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Enrollment Information -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4">Enrollment Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="font-medium">Status:</span>
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        @if($enrollment->status == 'confirm') bg-green-100 text-green-800
                                        @elseif($enrollment->status == 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($enrollment->status == 'cancel') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($enrollment->status) }}
                                    </span>
                                </div>
                                <div>
                                    <span class="font-medium">Enrolled Date:</span>
                                    {{ $enrollment->created_at->format('M d, Y') }}
                                </div>
                                <div>
                                    <span class="font-medium">Last Updated:</span>
                                    {{ $enrollment->updated_at->format('M d, Y') }}
                                </div>
                            </div>
                        </div>

                        <!-- Trainee Information -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4">Trainee Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="font-medium">Name:</span>
                                    {{ $enrollment->trainee->user->name ?? 'N/A' }}
                                </div>
                                <div>
                                    <span class="font-medium">CNIC:</span>
                                    {{ $enrollment->trainee->cnic }}
                                </div>
                                <div>
                                    <span class="font-medium">Contact:</span>
                                    {{ $enrollment->trainee->contact }}
                                </div>
                                <div>
                                    <span class="font-medium">Email:</span>
                                    {{ $enrollment->trainee->user->email ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Course Information -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4">Course Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="font-medium">Course Name:</span>
                                    {{ $enrollment->course->name }}
                                </div>
                                <div>
                                    <span class="font-medium">Course Code:</span>
                                    {{ $enrollment->course->code }}
                                </div>
                                <div>
                                    <span class="font-medium">Duration:</span>
                                    {{ $enrollment->course->duration }} hours
                                </div>
                                <div>
                                    <span class="font-medium">Method:</span>
                                    {{ $enrollment->course->method }}
                                </div>
                            </div>
                        </div>

                        <!-- Session Information -->
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-4">Session Information</h3>
                            <div class="space-y-3">
                                <div>
                                    <span class="font-medium">Session Name:</span>
                                    {{ $enrollment->session->name ?? 'N/A' }}
                                </div>
                                <div>
                                    <span class="font-medium">Duration:</span>
                                    {{ $enrollment->session->duration ?? 'N/A' }}
                                </div>
                                <div>
                                    <span class="font-medium">Start Date:</span>
                                    {{ $enrollment->session->start_date->format('M d, Y') ?? 'N/A' }}
                                </div>
                                <div>
                                    <span class="font-medium">End Date:</span>
                                    {{ $enrollment->session->end_date->format('M d, Y') ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-4">
                        <a href="{{ route('enrollments.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                            Back to List
                        </a>
                        <a href="{{ route('enrollments.edit', $enrollment) }}" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg">
                            Edit Enrollment
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>