<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Enrollment') }}
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
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Edit</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('enrollments.update', $enrollment) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Trainee (Read-only) -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Trainee
                                </label>
                                <div class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-100 dark:bg-gray-700">
                                    {{ $enrollment->trainee->user->name }} - {{ $enrollment->trainee->cnic }}
                                </div>
                                <input type="hidden" name="trainee_id" value="{{ $enrollment->trainee_id }}">
                            </div>

                            <!-- Course Selection -->
                            <div>
                                <label for="course_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Course *
                                </label>
                                <select name="course_id" id="course_id" required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                    <option value="">Select Course</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}" {{ old('course_id', $enrollment->course_id) == $course->id ? 'selected' : '' }}>
                                            {{ $course->name }} ({{ $course->code }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('course_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Session Selection -->
                        <div class="mb-6">
                            <label for="session_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Session *
                            </label>
                            <select name="session_id" id="session_id" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Select Session</option>
                                @foreach($sessions as $session)
                                    <option value="{{ $session->id }}" {{ old('session_id', $enrollment->session_id) == $session->id ? 'selected' : '' }}>
                                        {{ $session->name }} ({{ $session->start_date->format('M d, Y') }} - {{ $session->end_date->format('M d, Y') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('session_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status Selection -->
                        <div class="mb-6">
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status *
                            </label>
                            <select name="status" id="status" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="confirm" {{ old('status', $enrollment->status) == 'confirm' ? 'selected' : '' }}>Confirm</option>
                                <option value="pending" {{ old('status', $enrollment->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="cancel" {{ old('status', $enrollment->status) == 'cancel' ? 'selected' : '' }}>Cancel</option>
                                <option value="altered" {{ old('status', $enrollment->status) == 'altered' ? 'selected' : '' }}>Altered</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Validation Alert -->
                        <div id="validation-alert" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            This trainee is already enrolled in the selected session.
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('enrollments.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                                Cancel
                            </a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">
                                Update Enrollment
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
            const validationAlert = document.getElementById('validation-alert');
            const traineeId = "{{ $enrollment->trainee_id }}";

            // Load sessions when course changes
            courseSelect.addEventListener('change', function() {
                const courseId = this.value;
                
                if (courseId) {
                    fetch(`/get-sessions/${courseId}`)
                        .then(response => response.json())
                        .then(sessions => {
                            sessionSelect.innerHTML = '<option value="">Select Session</option>';
                            sessions.forEach(session => {
                                const startDate = new Date(session.start_date).toLocaleDateString();
                                const endDate = new Date(session.end_date).toLocaleDateString();
                                sessionSelect.innerHTML += `
                                    <option value="${session.id}">
                                        ${session.name} (${startDate} - ${endDate})
                                    </option>
                                `;
                            });
                        });
                } else {
                    sessionSelect.innerHTML = '<option value="">Select Session</option>';
                }
            });

            // Check for existing enrollment when session changes
            sessionSelect.addEventListener('change', function() {
                const sessionId = this.value;

                if (sessionId && sessionId != "{{ $enrollment->session_id }}") {
                    fetch(`/check-enrollment?trainee_id=${traineeId}&session_id=${sessionId}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.exists) {
                                validationAlert.classList.remove('hidden');
                            } else {
                                validationAlert.classList.add('hidden');
                            }
                        });
                } else {
                    validationAlert.classList.add('hidden');
                }
            });
        });
    </script>
    @endpush
</x-app-layout>