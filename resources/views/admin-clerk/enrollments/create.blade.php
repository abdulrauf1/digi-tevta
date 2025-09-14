<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create New Enrollments') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                            <a href="{{ route('admin-clerk.enrollments.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">
                                Enrollments
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400"></i>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Create</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Session Selection Section -->
                    <div id="session-section" class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Step 1: Select a Session</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label for="session_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Session *
                                </label>
                                <select name="session_id" id="session_id" required
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors">
                                    <option value="">Select Session</option>
                                    @foreach($sessions as $session)
                                        @if($session->status === 'upcoming' || $session->status === 'ongoing')
                                            <option value="{{ $session->id }}">
                                                {{ $session->name }} ({{ $session->start_date }} - {{ $session->end_date }}) - {{ ucfirst($session->status) }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="flex items-end">
                                <button type="button" id="load-trainees-btn" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors flex items-center disabled:bg-gray-400 disabled:cursor-not-allowed" disabled>
                                    <i class="fas fa-users mr-2"></i> Load Unenrolled Trainees
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Trainees Selection Section -->
                    <div id="trainees-section" class="hidden mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Step 2: Select Trainees and Courses</h3>
                        
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
                            <div class="flex justify-between items-center mb-4">
                                <div>
                                    <span id="selected-count" class="text-sm text-gray-500 dark:text-gray-400">0 trainees selected</span>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <button type="button" id="select-all" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        Select All
                                    </button>
                                    <button type="button" id="select-none" class="text-sm text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-300">
                                        Deselect All
                                    </button>
                                </div>
                            </div>

                            <div class="mb-4">
                                <input type="text" id="trainee-search" placeholder="Search trainees..." 
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-white">
                            </div>

                            <div id="trainees-container" class="max-h-96 overflow-y-auto space-y-3">
                                <!-- Trainees will be loaded here dynamically -->
                            </div>

                            <div id="no-trainees-message" class="hidden text-center py-8 text-gray-500 dark:text-gray-400">
                                <i class="fas fa-user-slash text-3xl mb-2"></i>
                                <p>No unenrolled trainees found for this session.</p>
                            </div>
                            
                            <div id="loading-trainees" class="hidden text-center py-8 text-gray-500 dark:text-gray-400">
                                <i class="fas fa-spinner fa-spin text-3xl mb-2"></i>
                                <p>Loading unenrolled trainees...</p>
                            </div>

                            <div id="error-message" class="hidden text-center py-8 text-red-500 dark:text-red-400">
                                <i class="fas fa-exclamation-triangle text-3xl mb-2"></i>
                                <p id="error-text">Failed to load trainees. Please try again.</p>
                            </div>
                        </div>
                        
                        <!-- Status Selection -->
                        <div class="mb-6">
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Default Enrollment Status *
                            </label>
                            <select name="status" id="status" required
                                class="w-full md:w-1/2 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white transition-colors">
                                <option value="confirm">Confirm</option>
                                <option value="cancel">Cancel</option>
                                
                            </select>
                        </div>
                    </div>

                    <!-- Validation Alert -->
                    <div id="validation-alert" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span id="validation-message"></span>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('admin-clerk.enrollments.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg transition-colors flex items-center">
                            <i class="fas fa-times mr-2"></i> Cancel
                        </a>
                        <button type="button" id="submit-btn" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors flex items-center hidden">
                            <i class="fas fa-user-plus mr-2"></i> Create Enrollments
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing enrollment creation...');
            
            // Safe element retrieval with fallbacks
            function getElement(id) {
                const element = document.getElementById(id);
                if (!element) {
                    console.warn(`Element with id '${id}' not found`);
                }
                return element;
            }

            // Initialize variables with safe access
            const sessionSelect = getElement('session_id');
            const loadTraineesBtn = getElement('load-trainees-btn');
            const traineesSection = getElement('trainees-section');
            const traineesContainer = getElement('trainees-container');
            const noTraineesMessage = getElement('no-trainees-message');
            const loadingTrainees = getElement('loading-trainees');
            const errorMessage = getElement('error-message');
            const errorText = getElement('error-text');
            const traineeSearch = getElement('trainee-search');
            const selectAllBtn = getElement('select-all');
            const selectNoneBtn = getElement('select-none');
            const selectedCount = getElement('selected-count');
            const submitBtn = getElement('submit-btn');
            const validationAlert = getElement('validation-alert');
            const validationMessage = getElement('validation-message');
            const statusSelect = getElement('status');
            
            let allTrainees = [];
            let selectedTrainees = new Set();

            // Enable load button when session is selected
            if (sessionSelect && loadTraineesBtn) {
                sessionSelect.addEventListener('change', function() {
                    loadTraineesBtn.disabled = !this.value;
                });
            }

            // Load trainees when button is clicked - using XMLHttpRequest as fallback
            if (loadTraineesBtn) {
                loadTraineesBtn.addEventListener('click', function() {
                    const sessionId = sessionSelect.value;
                    
                    if (!sessionId) {
                        showError('Please select a session first.');
                        return;
                    }

                    // Show loading state
                    hideElement(errorMessage);
                    hideElement(noTraineesMessage);
                    showElement(loadingTrainees);
                    if (traineesContainer) traineesContainer.innerHTML = '';
                    
                    // Use XMLHttpRequest as a fallback to avoid fetch issues
                    const xhr = new XMLHttpRequest();
                    xhr.open('GET', `/admin-clerk/get-available-trainees/${sessionId}`, true);
                    xhr.setRequestHeader('Accept', 'application/json');
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                    
                    xhr.onload = function() {
                        hideElement(loadingTrainees);
                        
                        if (xhr.status === 200) {
                            try {
                                const trainees = JSON.parse(xhr.responseText);
                                allTrainees = trainees;
                                renderTrainees(trainees);
                                
                                if (traineesSection && trainees.length > 0) {
                                    showElement(traineesSection);
                                    hideElement(noTraineesMessage);
                                    if (submitBtn) showElement(submitBtn);
                                } else {
                                    showElement(traineesSection);
                                    showElement(noTraineesMessage);
                                    if (submitBtn) hideElement(submitBtn);
                                }
                                
                                updateSelectedCount();
                            } catch (e) {
                                console.error('Error parsing JSON:', e);
                                showErrorMessage('Failed to parse response data.');
                            }
                        } else {
                            showErrorMessage(`Failed to load trainees. Server returned ${xhr.status}`);
                        }
                    };
                    
                    xhr.onerror = function() {
                        hideElement(loadingTrainees);
                        showErrorMessage('Network error occurred. Please check your connection.');
                    };
                    
                    xhr.ontimeout = function() {
                        hideElement(loadingTrainees);
                        showErrorMessage('Request timed out. Please try again.');
                    };
                    
                    xhr.send();
                });
            }

            // Helper functions for element visibility
            function showElement(element) {
                if (element) element.classList.remove('hidden');
            }
            
            function hideElement(element) {
                if (element) element.classList.add('hidden');
            }
            
            function showErrorMessage(message) {
                if (errorMessage && errorText) {
                    errorText.textContent = message;
                    showElement(errorMessage);
                }
            }

            // Search trainees
            if (traineeSearch) {
                traineeSearch.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const filteredTrainees = allTrainees.filter(trainee => 
                        trainee.user.name.toLowerCase().includes(searchTerm) || 
                        (trainee.user.email && trainee.user.email.toLowerCase().includes(searchTerm)) ||
                        (trainee.cnic && trainee.cnic.toLowerCase().includes(searchTerm))
                    );
                    renderTrainees(filteredTrainees);
                });
            }

            // Select all trainees
            if (selectAllBtn) {
                selectAllBtn.addEventListener('click', function() {
                    document.querySelectorAll('.trainee-checkbox').forEach(checkbox => {
                        checkbox.checked = true;
                        selectedTrainees.add(checkbox.value);
                    });
                    updateSelectedCount();
                });
            }

            // Deselect all trainees
            if (selectNoneBtn) {
                selectNoneBtn.addEventListener('click', function() {
                    document.querySelectorAll('.trainee-checkbox').forEach(checkbox => {
                        checkbox.checked = false;
                        selectedTrainees.delete(checkbox.value);
                    });
                    updateSelectedCount();
                });
            }

            // Render trainees list
            function renderTrainees(trainees) {
                if (!traineesContainer) return;
                
                traineesContainer.innerHTML = '';
                
                if (trainees.length === 0) {
                    traineesContainer.innerHTML = '<div class="text-center py-4 text-gray-500">No trainees match your search</div>';
                    return;
                }
                
                trainees.forEach(trainee => {
                    const traineeDiv = document.createElement('div');
                    traineeDiv.className = 'flex flex-col md:flex-row items-start md:items-center p-4 bg-white dark:bg-gray-600 rounded-lg border border-gray-200 dark:border-gray-500 hover:bg-gray-50 dark:hover:bg-gray-500 transition-colors gap-4';
                    
                    traineeDiv.innerHTML = `
                        <div class="flex items-center flex-grow">
                            <input type="checkbox" name="trainee_ids[]" value="${trainee.id}" 
                                class="trainee-checkbox h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                ${selectedTrainees.has(trainee.id.toString()) ? 'checked' : ''}>
                            <div class="ml-3 flex-grow">
                                <div class="font-medium text-gray-900 dark:text-white">${trainee.user.name}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">${trainee.user.email || 'No email'} | ${trainee.cnic || 'No CNIC'}</div>
                            </div>
                        </div>
                        <div class="w-full md:w-64">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Course</label>
                            <select name="course_ids[${trainee.id}]" class="trainee-course w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                                <option value="">Select Course</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->name }} ({{ $course->code }})</option>
                                @endforeach
                            </select>
                        </div>
                    `;
                    
                    traineesContainer.appendChild(traineeDiv);
                    
                    // Add event listener to the checkbox
                    const checkbox = traineeDiv.querySelector('.trainee-checkbox');
                    if (checkbox) {
                        checkbox.addEventListener('change', function() {
                            if (this.checked) {
                                selectedTrainees.add(this.value);
                            } else {
                                selectedTrainees.delete(this.value);
                            }
                            updateSelectedCount();
                        });
                    }
                });
            }

            // Update selected count
            function updateSelectedCount() {
                if (selectedCount) {
                    const count = selectedTrainees.size;
                    selectedCount.textContent = `${count} trainee${count !== 1 ? 's' : ''} selected`;
                }
            }

            // Show error message
            function showError(message) {
                if (validationAlert && validationMessage) {
                    validationMessage.textContent = message;
                    showElement(validationAlert);
                    
                    // Hide after 5 seconds
                    setTimeout(() => {
                        hideElement(validationAlert);
                    }, 5000);
                }
            }

            // Form submission
            if (submitBtn) {
                submitBtn.addEventListener('click', function() {
                    // Validate form
                    const selectedTraineeIds = Array.from(selectedTrainees);
                    let isValid = true;
                    let errorMessage = '';
                    
                    if (selectedTraineeIds.length === 0) {
                        isValid = false;
                        errorMessage = 'Please select at least one trainee to enroll.';
                    }
                    
                    // Check if all selected trainees have a course selected
                    selectedTraineeIds.forEach(traineeId => {
                        const courseSelect = document.querySelector(`select[name="course_ids[${traineeId}]"]`);
                        if (courseSelect && !courseSelect.value) {
                            isValid = false;
                            errorMessage = 'Please select a course for all selected trainees.';
                        }
                    });
                    
                    if (!isValid) {
                        showError(errorMessage);
                        return;
                    }
                    
                    // Create form and submit
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route('admin-clerk.enrollments.bulk-store') }}';
                    
                    // Add CSRF token
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);
                    
                    // Add session ID
                    const sessionInput = document.createElement('input');
                    sessionInput.type = 'hidden';
                    sessionInput.name = 'session_id';
                    sessionInput.value = sessionSelect.value;
                    form.appendChild(sessionInput);
                    
                    // Add status
                    const statusInput = document.createElement('input');
                    statusInput.type = 'hidden';
                    statusInput.name = 'status';
                    statusInput.value = statusSelect.value;
                    form.appendChild(statusInput);
                    
                    // Add trainee IDs and course IDs
                    selectedTraineeIds.forEach(traineeId => {
                        const traineeInput = document.createElement('input');
                        traineeInput.type = 'hidden';
                        traineeInput.name = 'trainee_ids[]';
                        traineeInput.value = traineeId;
                        form.appendChild(traineeInput);
                        
                        const courseSelect = document.querySelector(`select[name="course_ids[${traineeId}]"]`);
                        if (courseSelect) {
                            const courseInput = document.createElement('input');
                            courseInput.type = 'hidden';
                            courseInput.name = 'course_ids[]';
                            courseInput.value = courseSelect.value;
                            form.appendChild(courseInput);
                        }
                    });
                    
                    document.body.appendChild(form);
                    form.submit();
                });
            }

            console.log('Enrollment creation initialized successfully');
        });
    </script>
    @endpush
</x-app-layout> 