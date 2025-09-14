<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Course') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            <nav class="mb-4 flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                            <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <a href="{{ route('admin-clerk.courses.index') }}" class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">Courses</a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <a href="{{ route('admin-clerk.courses.show', $course) }}" class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">{{ $course->name }}</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Edit</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('admin-clerk.courses.update', $course) }}" enctype="multipart/form-data" id="course-edit-form">
                        @csrf
                        @method('PUT')

                        <div class="space-y-8 divide-y divide-gray-200 dark:divide-gray-700">
                            <!-- Course Information Section -->
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-white">Course Information</h3>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Update the basic details of your course.</p>
                                </div>

                                <div class="grid grid-cols-1 gap-6 mt-6">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Course Name *</label>
                                        <input type="text" name="name" id="name" value="{{ old('name', $course->name) }}" required 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 p-2 border">
                                        @error('name')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description *</label>
                                        <textarea name="description" id="description" rows="4" required 
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 p-2 border">{{ old('description', $course->description) }}</textarea>
                                        @error('description')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="duration" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Duration (hours) *</label>
                                            <input type="number" name="duration" id="duration" value="{{ old('duration', $course->duration) }}" required 
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 p-2 border">
                                            @error('duration')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="method" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Teaching Method *</label>
                                            <select name="method" id="method" required 
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 p-2 border">
                                                <option value="">Select Method</option>
                                                <option value="CBT" {{ old('method', $course->method) == 'CBT' ? 'selected' : '' }}>CBT</option>
                                                <option value="Traditional" {{ old('method', $course->method) == 'Traditional' ? 'selected' : '' }}>Traditional</option>
                                            </select>
                                            @error('method')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label for="field" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Field *</label>
                                            <input type="text" name="field" id="field" value="{{ old('field', $course->field) }}" required 
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 p-2 border">
                                            @error('field')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="trainer_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Trainer *</label>
                                            <select name="trainer_id" id="trainer_id" required 
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 p-2 border">
                                                <option value="">Select Trainer</option>
                                                @foreach($trainers as $trainer)
                                                    <option value="{{ $trainer->id }}" {{ old('trainer_id', $course->trainer_id) == $trainer->id ? 'selected' : '' }}>
                                                        {{ $trainer->user->name }} ({{ $trainer->user->email }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('trainer_id')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                        </div>

                        <div class="pt-5">
                            <div class="flex justify-end">
                                <a href="{{ route('admin-clerk.courses.show', $course) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-3 transition duration-150 ease-in-out">Cancel</a>
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">Update Course</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
</x-app-layout>