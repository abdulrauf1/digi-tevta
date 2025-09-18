<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            My Courses & Modules
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            <nav class="mb-4 flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('trainer.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                            <svg class="w-3 h-3 mr-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Assessments</span>
                        </div>
                    </li>
                </ol>
            </nav>

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Course Selection -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Select Course</h3>
                    <form method="GET" action="{{ route('trainer.assessments.index',['session' => $session, 'course' => $course->id]) }}">
                        <div class="flex gap-4 items-end">
                            <div class="flex-1">
                                <label for="course_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Course</label>
                                <select name="course_id" id="course_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="">-- Select a Course --</option>
                                    
                                        <option value="{{ $course->id }}" {{ $selectedCourse && $selectedCourse->id == $course->id ? 'selected' : '' }}>
                                            {{ $course->name }}
                                        </option>
                                    
                                </select>
                            </div>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                Load Modules
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            @if($selectedCourse)
                <!-- Modules Section -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-medium mb-4">Modules for {{ $selectedCourse->name }}</h3>
                        
                        @if($selectedCourse->modules->isEmpty())
                            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative" role="alert">
                                <strong>No modules found!</strong> Please contact administrator to upload the course material completely.
                            </div>
                        @else
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Module Name</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Assessment Package</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                        @foreach($course->modules as $module)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $module->name }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                    @if($module->assesment_package_link)
                                                        <a href="{{ route('storage.file', ['filename' => $module->assesment_package_link]) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">View Assessment Package</a>
                                                    @else
                                                        <span class="text-gray-400">Not available</span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                    <button type="button" 
                                                            class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300"
                                                            onclick="openModal({{ $module->id }}, '{{ $module->name }}')">
                                                        Create Assessment Entries
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal for creating assessment entries -->
    @if($selectedCourse && !$course->modules->isEmpty())
        @foreach($course->modules as $module)
            <div id="modal-{{ $module->id }}" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
                <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 shadow-lg rounded-md bg-white dark:bg-gray-800">
                    <div class="mt-3">
                        <div class="flex justify-between items-center pb-3 border-b">
                            <h3 class="text-xl font-medium text-gray-900 dark:text-white">
                                Create Assessment Entries for {{ $module->name }}
                            </h3>
                            <button onclick="closeModal({{ $module->id }})" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        
                        
                        <form action="{{ route('trainer.assessments.create-entries') }}" method="POST">
                            @csrf
                            <input type="hidden" name="module_id" value="{{ $module->id }}">
                            <input type="hidden" name="course_id" value="{{ $selectedCourse->id }}">
                            
                            <div class="my-4">
                                <div class="flex justify-between items-center mb-2">
                                    <h4 class="text-lg font-medium text-gray-900 dark:text-white">Select Enrollments</h4>
                                    <button type="button" onclick="toggleSelectAll({{ $module->id }})" class="text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        Select All
                                    </button>
                                </div>
                                
                                <div class="max-h-60 overflow-y-auto border rounded-md p-2">
                                    @if($enrollments->isEmpty())
                                        <p class="text-gray-500 dark:text-gray-400 p-2">No enrollments found for this course.</p>
                                    @else
                                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                            <thead class="bg-gray-50 dark:bg-gray-700">
                                                <tr>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Select</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Trainee</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                                @foreach($enrollments as $enrollment)
                                                    <tr>
                                                        <td class="px-4 py-2 whitespace-nowrap">
                                                            <input type="checkbox" name="enrollment_ids[]" value="{{ $enrollment->id }}" 
                                                                   class="enrollment-checkbox-{{ $module->id }} rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                        </td>
                                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                                            {{ $enrollment->trainee->user->name ?? 'N/A' }}
                                                        </td>
                                                        <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                {{ $enrollment->status == 'confirm' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 
                                                                   ($enrollment->status == 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100' : 
                                                                   'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100') }}">
                                                                {{ ucfirst($enrollment->status) }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label for="type-{{ $module->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Assessment Type</label>
                                    <select name="type" id="type-{{ $module->id }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                        <option value="formative">Formative</option>
                                        <option value="integrated">Integrated</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="submission_date-{{ $module->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date of Submission</label>
                                    <input type="date" name="submission_date" id="submission_date-{{ $module->id }}" 
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                           required>
                                </div>
                            </div>
                            
                            <div class="flex justify-end space-x-3 pt-4 border-t">
                                <button type="button" onclick="closeModal({{ $module->id }})" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 dark:bg-gray-600 dark:text-white dark:hover:bg-gray-500">
                                    Cancel
                                </button>
                                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    Create Assessment Entries
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @endif

    <script>
        function openModal(moduleId, moduleName) {
            document.getElementById('modal-' + moduleId).classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeModal(moduleId) {
            document.getElementById('modal-' + moduleId).classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        function toggleSelectAll(moduleId) {
            const checkboxes = document.querySelectorAll('.enrollment-checkbox-' + moduleId);
            const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = !allChecked;
            });
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            document.querySelectorAll('[id^="modal-"]').forEach(modal => {
                if (event.target === modal) {
                    closeModal(modal.id.split('-')[1]);
                }
            });
        }
    </script>
</x-app-layout>