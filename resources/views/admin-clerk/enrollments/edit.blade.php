<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Enrollment') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
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
                            <span class="ml-4 text-sm font-medium text-blue-500 dark:text-blue-400">Edit Enrollment</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Header Card -->
            <div class="bg-gradient-to-r from-blue-500 to-indigo-600 dark:from-gray-800 dark:to-gray-900 rounded-xl shadow-lg overflow-hidden mb-8">
                <div class="p-6 flex flex-col md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-white">Edit Enrollment #{{ $enrollment->id }}</h1>
                        <p class="text-blue-100 dark:text-gray-300 mt-1">Update enrollment details</p>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-white/20 text-white">
                            <i class="fas fa-edit mr-1.5"></i>
                            Edit Mode
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('admin-clerk.enrollments.update', $enrollment) }}" method="POST" id="enrollmentForm">
                        @csrf
                        @method('PUT')

                        <!-- Trainee Information Card -->
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-xl p-5 mb-6">
                            <div class="flex items-center mb-4">
                                <div class="bg-blue-100 dark:bg-blue-900/30 p-3 rounded-lg mr-4">
                                    <i class="fas fa-user-graduate text-blue-600 dark:text-blue-400 text-xl"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Trainee Information</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Trainee Name
                                    </label>
                                    <div class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-700/50 text-gray-700 dark:text-gray-300">
                                        {{ $enrollment->trainee->user->name }}
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        CNIC
                                    </label>
                                    <div class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-700/50 text-gray-700 dark:text-gray-300">
                                        {{ $enrollment->trainee->cnic }}
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="trainee_id" value="{{ $enrollment->trainee_id }}">
                        </div>

                        <!-- Current Enrollment Information -->
                        <div class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5 mb-6">
                            <div class="flex items-center mb-4">
                                <div class="bg-indigo-100 dark:bg-indigo-900/30 p-3 rounded-lg mr-4">
                                    <i class="fas fa-info-circle text-indigo-600 dark:text-indigo-400 text-xl"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Current Enrollment</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Current Course
                                    </label>
                                    <div class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                        {{ $enrollment->course->name }} ({{ $enrollment->course->code }})
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Current Session
                                    </label>
                                    <div class="w-full px-4 py-3 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                        {{ $enrollment->enrollmentSession->name ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Course Selection Card -->
                            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5 shadow-sm">
                                <div class="flex items-center mb-4">
                                    <div class="bg-purple-100 dark:bg-purple-900/30 p-3 rounded-lg mr-4">
                                        <i class="fas fa-book text-purple-600 dark:text-purple-400 text-xl"></i>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Change Course</h3>
                                </div>
                                <div>
                                    <label for="course_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Select New Course *
                                    </label>
                                    <select name="course_id" id="course_id" required
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors duration-200">
                                        <option value="">Select Course</option>
                                        @foreach($courses as $course)
                                            <option value="{{ $course->id }}" {{ old('course_id', $enrollment->course_id) == $course->id ? 'selected' : '' }} class="py-2">
                                                {{ $course->name }} ({{ $course->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('course_id')
                                        <p class="mt-2 text-sm text-red-600 flex items-center"><i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Session Selection Card -->
                            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-5 shadow-sm">
                                <div class="flex items-center mb-4">
                                    <div class="bg-amber-100 dark:bg-amber-900/30 p-3 rounded-lg mr-4">
                                        <i class="fas fa-calendar-alt text-amber-600 dark:text-amber-400 text-xl"></i>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Session Selection</h3>
                                </div>
                                <div>
                                    <div class="flex items-center mb-2">
                                        <label for="session_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                            Select Session *
                                        </label>
                                        <span class="ml-2 text-xs text-blue-500 bg-blue-100 dark:bg-blue-900/30 dark:text-blue-300 px-2 py-1 rounded-full">Required</span>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <div class="flex items-center">
                                            <input type="radio" id="keep_current_session" name="session_option" value="keep" checked class="mr-2 focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                            <label for="keep_current_session" class="text-sm text-gray-700 dark:text-gray-300">Keep current session</label>
                                        </div>
                                        <div class="flex items-center mt-2">
                                            <input type="radio" id="change_session" name="session_option" value="change" class="mr-2 focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                            <label for="change_session" class="text-sm text-gray-700 dark:text-gray-300">Change to different session</label>
                                        </div>
                                    </div>
                                    
                                    <select name="session_id" id="session_id" 
                                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors duration-200"
                                        disabled>
                                        <option value="">Select Session</option>
                                        @foreach($sessions as $session)
                                            <option value="{{ $session->enrollmentSession->id }}" {{ old('session_id', $enrollment->session_id) == $session->enrollmentSession->id ? 'selected' : '' }} class="py-2">
                                                {{ $session->enrollmentSession->name }} ({{ $session->enrollmentSession->start_date }} - {{ $session->enrollmentSession->end_date }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('session_id')
                                        <p class="mt-2 text-sm text-red-600 flex items-center"><i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                      
                        <!-- Validation Alert -->
                        <div id="validation-alert" class="hidden bg-red-100 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 px-4 py-3 rounded-lg mb-6 transition-opacity duration-300">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-triangle mr-3"></i>
                                <span>This trainee is already enrolled in the selected session.</span>
                            </div>
                        </div>

                        <!-- Success Alert -->
                        <div id="success-alert" class="hidden bg-green-100 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-700 dark:text-green-400 px-4 py-3 rounded-lg mb-6 transition-opacity duration-300">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle mr-3"></i>
                                <span>You can change the course for the current session without creating a duplicate enrollment.</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('admin-clerk.enrollments.index') }}" class="px-5 py-2.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center">
                                <i class="fas fa-times mr-2"></i> Cancel
                            </a>
                            <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors duration-200 flex items-center justify-center shadow-md">
                                <i class="fas fa-save mr-2"></i> Update Enrollment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const courseSelect = document.getElementById('course_id');
            const sessionSelect = document.getElementById('session_id');
            const keepSessionRadio = document.getElementById('keep_current_session');
            const changeSessionRadio = document.getElementById('change_session');
            const validationAlert = document.getElementById('validation-alert');
            const successAlert = document.getElementById('success-alert');
            const traineeId = "{{ $enrollment->trainee_id }}";
            const currentSessionId = "{{ $enrollment->session_id }}";
            const form = document.getElementById('enrollmentForm');

            // Toggle session select based on radio button selection
            function toggleSessionSelect() {
                if (changeSessionRadio.checked) {
                    sessionSelect.disabled = false;
                    sessionSelect.required = true;
                    sessionSelect.classList.remove('bg-gray-100', 'dark:bg-gray-800');
                    sessionSelect.classList.add('dark:bg-gray-700');
                } else {
                    sessionSelect.disabled = true;
                    sessionSelect.required = false;
                    sessionSelect.classList.add('bg-gray-100', 'dark:bg-gray-800');
                    sessionSelect.classList.remove('dark:bg-gray-700');
                    // Set value to current session when keeping it
                    sessionSelect.value = currentSessionId;
                    validationAlert.classList.add('hidden');
                    successAlert.classList.remove('hidden');
                }
            }

            // Initialize
            toggleSessionSelect();
            
            // Add event listeners
            keepSessionRadio.addEventListener('change', toggleSessionSelect);
            changeSessionRadio.addEventListener('change', toggleSessionSelect);

            // Check for existing enrollment when session changes
            sessionSelect.addEventListener('change', function() {
                const sessionId = this.value;

                if (sessionId && sessionId != currentSessionId) {
                    fetch(`/check-enrollment?trainee_id=${traineeId}&session_id=${sessionId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.exists) {
                                validationAlert.classList.remove('hidden');
                                successAlert.classList.add('hidden');
                            } else {
                                validationAlert.classList.add('hidden');
                                successAlert.classList.remove('hidden');
                            }
                        });
                } else if (sessionId === currentSessionId) {
                    validationAlert.classList.add('hidden');
                    successAlert.classList.remove('hidden');
                } else {
                    validationAlert.classList.add('hidden');
                    successAlert.classList.add('hidden');
                }
            });

            // Also check when course changes to show appropriate message
            courseSelect.addEventListener('change', function() {
                if (keepSessionRadio.checked) {
                    successAlert.classList.remove('hidden');
                }
            });

            // Form submission validation
            form.addEventListener('submit', function(e) {
                if (validationAlert.classList.contains('hidden') === false) {
                    e.preventDefault();
                    alert('Please resolve the duplicate enrollment issue before submitting.');
                }
            });
        });
    </script>
    @endpush

    <style>
        select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1rem center;
            background-size: 1.5em 1.5em;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        .dark select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23d1d5db'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        }
        
        select:disabled {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%239ca3af'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            cursor: not-allowed;
        }
        
        .dark select:disabled {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
        }
    </style>
</x-app-layout>