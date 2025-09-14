<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Trainer') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            <nav class="mb-6 flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                            <svg class="w-4 h-4 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0极-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a极 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-4 h-4 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <a href="{{ route('admin-clerk.trainers.index') }}" class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">Trainers</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-4 h-4 text极ray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <a href="{{ route('admin-clerk.trainers.show', $trainer) }}" class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">{{ $trainer->name }}</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-4 h-4 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Edit</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Header Section -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center mr-4">
                            <span class="font-medium text-indigo-800 dark:text-indigo-200">{{ substr($trainer->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Edit Trainer Profile</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Update the trainer's information</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('admin-clerk.trainers.update', $trainer) }}">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <!-- Personal Information Section -->
                            <div>
                                <h4 class="text-md font-medium text-gray-800 dark:text-white mb-4 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Personal Information
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Full Name *</label>
                                        <input type="text" name="name" id="name" value="{{ old('name', $trainer->name) }}" required 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600">
                                        @error('name')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email Address *</label>
                                        <input type="email" name="email" id="email" value="{{ old('email', $trainer->email) }}" required 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600">
                                        @error('email')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Password Section -->
                            <div>
                                <h4 class="text-md font-medium text-gray-800 dark:text-white mb-4 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    Password Update
                                </h4>
                                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg mb-4">
                                    <p class="text-sm text-blue-700 dark:text-blue-300">Leave password fields blank to keep the current password</p>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">New Password</label>
                                        <input type="password" name="password" id="password" 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                                            placeholder="Enter new password">
                                        @error('password')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Confirm Password</label>
                                        <input type="password" name="password_confirmation" id="password_confirmation" 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                                            placeholder="Confirm new password">
                                    </div>
                                </div>
                            </div>

                            <!-- Professional Information Section -->
                            <div>
                                <h4 class="text-md font-medium text-gray-800 dark:text-white mb-4 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 极 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    Professional Information
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="specialization" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Specialization *</label>
                                        <input type="text" name="specialization" id="specialization" value="{{ old('specialization', $trainerData->specialization) }}" required 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                                            placeholder="e.g., Web Development, Data Science">
                                        @error('specialization')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="experience" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Experience (years) *</label>
                                        <input type="number" name="experience" id="experience" value="{{ old('experience', $trainerData->experience_years) }}" required 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                                            min="0" max="50" step="1">
                                        @error('experience')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Fields (if any) -->
                            
                            <div>
                                <h4 class="text-md font-medium text-gray-800 dark:text-white mb-4 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    Contact Information
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Phone Number</label>
                                        <input type="tel" name="phone" id="phone" value="{{ old('phone', $trainerData->contact) }}" 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                                            placeholder="+1 (555) 123-4567">
                                        @error('phone')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="qualification" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Qualification *</label>
                                        <input type="text" name="qualification" id="qualification" value="{{ old('qualification', $trainerData->qualification) }}" required 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                                            placeholder="e.g., Web Development, Data Science">
                                        @error('qualification')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                </div>
                            </div>
                            
                        </div>

                        <!-- Form Actions -->
                        <div class="flex justify-end mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('admin-clerk.trainers.show', $trainer) }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-md shadow-sm transition duration-150 ease-in-out mr-3">
                                Cancel
                            </a>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md shadow-sm transition duration-150 ease-in-out flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Update Trainer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Add some interactive features
        document.addEventListener('DOMContentLoaded', function() {
            // Add focus styles to form inputs
            const inputs = document.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('ring-2', 'ring-indigo-500', 'ring-opacity-50');
                });
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('ring-2', 'ring-indigo-500', 'ring-opacity-50');
                });
            });
        });
    </script>

    <style>
        input:focus, select:focus, textarea:focus {
            outline: none;
        }
        .form-group:focus-within {
            transform: translateY(-1px);
            transition: transform 0.1s ease-in-out;
        }
    </style>
</x-app-layout>