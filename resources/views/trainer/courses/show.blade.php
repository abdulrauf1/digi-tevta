<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Course: {{ $course->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Course Overview -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="lg:col-span-2">
                            <h3 class="text-lg font-semibold">{{ $course->name }} ({{ $course->code }})</h3>
                            <p class="text-gray-600 dark:text-gray-400 mt-2">{{ $course->description }}</p>
                            
                            <div class="grid grid-cols-2 gap-4 mt-6">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Duration</p>
                                    <p class="font-medium">{{ $course->duration }} hours</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Training Method</p>
                                    <p class="font-medium">{{ $course->method }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Enrolled Trainees</p>
                                    <p class="font-medium">{{ $course->enrollments_count }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                                    <span class="px-2 py-1 text-xs rounded-full {{ $course->status == 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                        {{ ucfirst($course->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                            <h4 class="font-medium mb-3">Attendance Overview</h4>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Sessions</p>
                                    <p class="font-medium">{{ $attendanceStats['total_sessions'] }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Attendance Rate</p>
                                    <p class="font-medium">{{ $attendanceStats['attendance_rate'] }}%</p>
                                </div>
                                <div class="grid grid-cols-3 gap-2 mt-4">
                                    <div class="text-center p-2 bg-green-100 dark:bg-green-900/30 rounded">
                                        <p class="text-xl font-bold text-green-600 dark:text-green-400">{{ $attendanceStats['present_count'] }}</p>
                                        <p class="text-xs text-green-700 dark:text-green-300">Present</p>
                                    </div>
                                    <div class="text-center p-2 bg-yellow-100 dark:bg-yellow-900/30 rounded">
                                        <p class="text-xl font-bold text-yellow-600 dark:text-yellow-400">{{ $attendanceStats['late_count'] }}</p>
                                        <p class="text-xs text-yellow-700 dark:text-yellow-300">Late</p>
                                    </div>
                                    <div class="text-center p-2 bg-red-100 dark:bg-red-900/30 rounded">
                                        <p class="text-xl font-bold text-red-600 dark:text-red-400">{{ $attendanceStats['absent_count'] }}</p>
                                        <p class="text-xs text-red-700 dark:text-red-300">Absent</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex space-x-3">
                        <a href="{{ route('trainer.enrollments.course', $course) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-users mr-1"></i> View Trainees
                        </a>
                        <a href="#" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-chart-bar mr-1"></i> View Reports
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Recent Assessments -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Recent Assessments</h3>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($recentAssessments as $assessment)
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $assessment->title ?? 'Assessment #'.$assessment->id }}
                            </h4>
                            <span class="px-2 py-1 text-xs rounded-full {{ $assessment->status == 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                {{ ucfirst($assessment->status) }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            By: {{ $assessment->enrollment->trainee->user->name ?? 'Unknown Trainee' }}
                        </p>
                        @if($assessment->submission_date)
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            Submitted: {{ $assessment->submission_date->diffForHumans() }}
                        </p>
                        @endif
                    </div>
                    @empty
                    <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                        No assessments found for this course.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>