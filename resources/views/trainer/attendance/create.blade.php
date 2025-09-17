<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Take Attendance - {{ $session->name }}
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
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <a href="{{ route('trainer.courses.show', $course) }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">{{ $course->name }}</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Take Attendance</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Session Info Card -->
                    <div class="mb-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-400 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Session Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-2">
                            <div>
                                <p class="text-sm text-blue-600 dark:text-blue-300 font-medium">Session Name</p>
                                <p class="text-blue-800 dark:text-blue-100">{{ $session->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-blue-600 dark:text-blue-300 font-medium">Date Range</p>
                                <p class="text-blue-800 dark:text-blue-100">{{ $session->start_date }} to {{ $session->end_date }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-blue-600 dark:text-blue-300 font-medium">Trainees</p>
                                <p class="text-blue-800 dark:text-blue-100">{{ $enrollments->count() }} enrolled</p>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-blue-100 dark:border-blue-800">
                            <p class="text-sm text-blue-600 dark:text-blue-300 font-medium">Today's Date</p>
                            <p class="text-blue-800 dark:text-blue-100 font-semibold">{{ today()->format('l, F j, Y') }}</p>
                        </div>
                    </div>

                    @if($todayAttendance->count() > 0)
                    <div class="mb-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-100 dark:border-yellow-800 rounded-lg p-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <span class="text-yellow-700 dark:text-yellow-300 font-medium">Note: Attendance has already been recorded for today.</span>
                        </div>
                        <p class="text-sm text-yellow-600 dark:text-yellow-400 mt-1">You can update attendance status below if needed.</p>
                    </div>
                    @endif

                    <!-- Attendance Form -->
                    <form action="{{ route('trainer.attendance.store', $session) }}" method="POST">
                        @csrf
                        
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-4">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Mark Attendance</h3>
                                <div class="flex space-x-2">
                                    <button type="button" id="mark-all-present" class="px-3 py-1 text-xs bg-green-100 hover:bg-green-200 text-green-700 dark:bg-green-900 dark:hover:bg-green-800 dark:text-green-300 rounded">
                                        Mark All Present
                                    </button>
                                    <button type="button" id="mark-all-absent" class="px-3 py-1 text-xs bg-red-100 hover:bg-red-200 text-red-700 dark:bg-red-900 dark:hover:bg-red-800 dark:text-red-300 rounded">
                                        Mark All Absent
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="overflow-x-auto shadow-md sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-100 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                            Trainee
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                            Remarks
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($enrollments as $enrollment)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center">
                                                    <span class="text-indigo-800 dark:text-indigo-200 font-medium">
                                                        {{ substr($enrollment->trainee->user->name, 0, 1) }}
                                                    </span>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $enrollment->trainee->user->name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $enrollment->trainee->cnic }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="relative">
                                                <select name="attendance[{{ $enrollment->id }}]" class="attendance-select rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 py-2 pl-3 pr-10">
                                                    <option value="Present" {{ isset($todayAttendance[$enrollment->id]) && $todayAttendance[$enrollment->id]->status == 'Present' ? 'selected' : '' }} class="text-green-600">Present</option>
                                                    <option value="Absent" {{ isset($todayAttendance[$enrollment->id]) && $todayAttendance[$enrollment->id]->status == 'Absent' ? 'selected' : '' }} class="text-red-600">Absent</option>
                                                    <option value="Late" {{ isset($todayAttendance[$enrollment->id]) && $todayAttendance[$enrollment->id]->status == 'Late' ? 'selected' : '' }} class="text-yellow-600">Late</option>
                                                    <option value="Leave" {{ isset($todayAttendance[$enrollment->id]) && $todayAttendance[$enrollment->id]->status == 'Leave' ? 'selected' : '' }} class="text-blue-600">Leave</option>
                                                </select>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="text" name="remarks[{{ $enrollment->id }}]" 
                                                value="{{ isset($todayAttendance[$enrollment->id]) ? $todayAttendance[$enrollment->id]->remarks : '' }}"
                                                class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full px-3 py-2"
                                                placeholder="Optional remarks (e.g., reason for absence)">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6 flex justify-between items-center">
                            <!-- route('trainer.attendance.select-course') -->
                            <a href="" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Back to Courses
                            </a>
                            <div class="flex space-x-3">
                                <a href="{{ route('trainer.dashboard') }}" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-white font-medium rounded-md">
                                    Cancel
                                </a>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-md">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Save Attendance
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mark all present
            document.getElementById('mark-all-present').addEventListener('click', function() {
                document.querySelectorAll('.attendance-select').forEach(select => {
                    select.value = 'Present';
                });
            });
            
            // Mark all absent
            document.getElementById('mark-all-absent').addEventListener('click', function() {
                document.querySelectorAll('.attendance-select').forEach(select => {
                    select.value = 'Absent';
                });
            });
            
            // Color code the status dropdowns based on selection
            document.querySelectorAll('.attendance-select').forEach(select => {
                // Set initial color
                updateSelectColor(select);
                
                // Update color on change
                select.addEventListener('change', function() {
                    updateSelectColor(this);
                });
            });
            
            function updateSelectColor(select) {
                switch(select.value) {
                    case 'Present':
                        select.classList.add('text-green-600');
                        select.classList.remove('text-red-600', 'text-yellow-600', 'text-blue-600');
                        break;
                    case 'Absent':
                        select.classList.add('text-red-600');
                        select.classList.remove('text-green-600', 'text-yellow-600', 'text-blue-600');
                        break;
                    case 'Late':
                        select.classList.add('text-yellow-600');
                        select.classList.remove('text-green-600', 'text-red-600', 'text-blue-600');
                        break;
                    case 'Leave':
                        select.classList.add('text-blue-600');
                        select.classList.remove('text-green-600', 'text-red-600', 'text-yellow-600');
                        break;
                }
            }
        });
    </script>
    @endpush
</x-app-layout>