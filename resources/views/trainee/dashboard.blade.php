<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('ucfirst(Auth::user()->roles[0]->name) Dashboard') }}
        </h2>
    </x-slot>
    
    @if($traineeEnrollment)
        <!-- route('assessments.create') -->
        <a href="#" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            <i class="fas fa-upload mr-2"></i>Upload Assessment
        </a>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 mt-4">
            <!-- My Courses Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                        <i class="fas fa-book text-blue-500 dark:text-blue-300 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">My Courses</h3>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['myCourses'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Attendance Rate Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                        <i class="fas fa-clipboard-check text-green-500 dark:text-green-300 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Attendance Rate</h3>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['attendanceRate'] }}%</p>
                    </div>
                </div>
            </div>

            <!-- Pending Assessments Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900">
                        <i class="fas fa-tasks text-yellow-500 dark:text-yellow-300 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending Assessments</h3>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['pendingAssessments'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Submitted Assessments Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900">
                        <i class="fas fa-check-circle text-purple-500 dark:text-purple-300 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Submitted Assessments</h3>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['submittedAssessments'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- My Courses -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">My Courses</h3>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($myCourses as $course)
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $course->title }}</h4>
                            <span class="px-2 py-1 text-xs rounded-full {{ $course->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($course->status) }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Trainer: {{ $course->trainer->user->name }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Duration: {{ $course->duration_hours }} hours • {{ $course->start_date ? $course->start_date->format('M d, Y') : 'N/A'}} - {{ $course->end_date ? $course->end_date->format('M d, Y') : 'N/A'}}
                            
                        </p>
                        <div class="mt-3">
                            <!-- route('courses.show', $course->id) -->
                            <a href="#" class="text-blue-500 hover:text-blue-700 text-sm">
                                <i class="fas fa-eye mr-1"></i> View Course
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                        You are not enrolled in any courses yet.
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- My Assessments -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">My Assessments</h3>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($recentAssessments as $assessment)
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">{{ $assessment->title }}</h4>
                            <span class="px-2 py-1 text-xs rounded-full {{ $assessment->status == 'submitted' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($assessment->status) }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            Course: {{ $assessment->course->title }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Due: {{ $assessment->due_date->format('M d, Y') }}
                            @if($assessment->submission_date)
                            • Submitted: {{ $assessment->submission_date->diffForHumans() }}
                            @endif
                        </p>
                        <div class="mt-3 flex space-x-2">
                            <!-- route('assessments.show', $assessment->id) -->
                            <a href="#" class="text-blue-500 hover:text-blue-700 text-sm">
                                <i class="fas fa-eye mr-1"></i> View
                            </a>
                            @if($assessment->status != 'submitted')
                            <!-- route('assessments.edit', $assessment->id)  -->
                            <a href="" class="text-green-500 hover:text-green-700 text-sm">
                                <i class="fas fa-edit mr-1"></i> Submit
                            </a>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                        No assessments found.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    @else
        <p>contact Admin to enroll u</p>
    @endif
</x-app-layout>


