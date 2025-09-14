<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ ucfirst(Auth::user()->roles[0]->name) }} Dashboard
        </h2>
    </x-slot>

    <!-- Current Session Section -->
    @if($currentSession)
    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-6 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-200 flex items-center">
                    <i class="fas fa-play-circle mr-2"></i> Current Session
                </h3>
                <p class="text-blue-600 dark:text-blue-300 mt-1">{{ $currentSession->name }}</p>
                <div class="flex items-center mt-2 text-sm text-blue-500 dark:text-blue-400">
                    <span class="mr-4"><i class="far fa-calendar-alt mr-1"></i> {{ $currentSession->start_date->format('M d, Y') }} - {{ $currentSession->end_date->format('M d, Y') }}</span>
                    <span><i class="fas fa-users mr-1"></i> {{ $currentSession->enrollments_count }} trainees</span>
                </div>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('trainer.attendance.create', $currentSession) }}" class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded flex items-center">
                    <i class="fas fa-clipboard-check mr-2"></i>Take Attendance
                </a>
                <a href="{{ route('trainer.assessments.create', $currentSession) }}" class="bg-purple-500 hover:bg-purple-600 text-white font-medium py-2 px-4 rounded flex items-center">
                    <i class="fas fa-plus-circle mr-2"></i>Create Assessment
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
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

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                    <i class="fas fa-user-graduate text-green-500 dark:text-green-300 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">My Trainees</h3>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['myTrainees'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900">
                    <i class="fas fa-clipboard-check text-purple-500 dark:text-purple-300 text-xl"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Today's Attendance</h3>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['todayAttendance'] }}</p>
                </div>
            </div>
        </div>

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
    </div>

    <!-- My Courses Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden mb-8">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                <i class="fas fa-book mr-2"></i> My Courses
            </h3>
        </div>
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($myCourses as $course)
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-md font-medium text-gray-900 dark:text-white">
                            {{ $course->name }} ({{ $course->code }})
                        </h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            {{ $course->description }}
                        </p>
                        <div class="flex items-center mt-2 text-sm text-gray-500 dark:text-gray-400">
                            <span class="mr-4"><i class="fas fa-clock mr-1"></i> {{ $course->duration }} hours</span>
                            <span class="mr-4"><i class="fas fa-chalkboard-teacher mr-1"></i> {{ $course->method }}</span>
                            <span class="mr-4"><i class="fas fa-users mr-1"></i> {{ $course->enrollments_count }} trainees</span>
                            <span class="px-2 py-1 text-xs rounded-full {{ $course->status == 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                {{ ucfirst($course->status) }}
                            </span>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <!-- route('trainer.courses.show', $course) -->
                        <a href="" class="text-blue-500 hover:text-blue-700 text-sm font-medium">
                            <i class="fas fa-eye mr-1"></i> View Details
                        </a>
                        <!-- route('trainer.enrollments.course', $course) -->
                        <a href="" class="text-green-500 hover:text-green-700 text-sm font-medium">
                            <i class="fas fa-users mr-1"></i> Trainees
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                No courses assigned to you.
            </div>
            @endforelse
        </div>
    </div>

    <!-- Enrollment Sessions Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden mb-8">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                <i class="fas fa-list-alt mr-2"></i> All Enrollment Sessions
            </h3>
        </div>
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($enrollmentSessions as $session)
            <div class="p-6 {{ $session->id === optional($currentSession)->id ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-md font-medium text-gray-900 dark:text-white">
                            {{ $session->name }}
                            @if($session->id === optional($currentSession)->id)
                            <span class="ml-2 bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-200">Current</span>
                            @endif
                        </h4>
                        <div class="flex items-center mt-1 text-sm text-gray-500 dark:text-gray-400">
                            <span class="mr-4"><i class="far fa-calendar-alt mr-1"></i> {{ $session->start_date }} - {{ $session->end_date }}</span>
                            <span><i class="fas fa-users mr-1"></i> {{ $session->enrollments_count }} trainees</span>
                        </div>
                    </div>
                    <div class="flex space-x-2">
                        <!-- route('trainer.enrollments.session', $session) -->
                        <a href="#" class="text-blue-500 hover:text-blue-700 text-sm font-medium">
                            <i class="fas fa-eye mr-1"></i> View Enrollments
                        </a>
                        <!-- route('trainer.attendance.session', $session) -->
                        <a href="#" class="text-green-500 hover:text-green-700 text-sm font-medium">
                            <i class="fas fa-clipboard-check mr-1"></i> Attendance
                        </a>
                        <!-- route('trainer.assessments.session', $session) -->
                        <a href="#" class="text-purple-500 hover:text-purple-700 text-sm font-medium">
                            <i class="fas fa-tasks mr-1"></i> Assessments
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                No enrollment sessions found.
            </div>
            @endforelse
        </div>
    </div>

    <!-- Recent Assessments -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Recent Assessments</h3>
        </div>
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($recentAssessments as $assessment)
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                        @if(isset($assessment->title))
                            {{ $assessment->title }}
                        @else
                            Assessment #{{ $assessment->id }}
                        @endif
                    </h4>
                    <span class="px-2 py-1 text-xs rounded-full {{ $assessment->status == 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                        {{ ucfirst($assessment->status) }}
                    </span>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    By: {{ $assessment->enrollment->trainee->user->name ?? 'Unknown Trainee' }}
                </p>
                @if($assessment->submission_date)
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Submitted: {{ $assessment->submission_date->diffForHumans() }}
                </p>
                @endif
                <div class="mt-3">
                    <a href="{{ route('trainer.assessments.show', $assessment) }}" class="text-blue-500 hover:text-blue-700 text-sm font-medium">
                        <i class="fas fa-eye mr-1"></i> View Details
                    </a>
                </div>
            </div>
            @empty
            <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                No assessments found.
            </div>
            @endforelse
        </div>
    </div>
</x-app-layout>