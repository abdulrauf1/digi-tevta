<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ ucfirst(Auth::user()->roles[0]->name) }} Dashboard
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Welcome Card & Quick Actions -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Welcome Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 col-span-1">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-indigo-100 dark:bg-indigo-900">
                            <i class="fas fa-user-circle text-indigo-500 dark:text-indigo-300 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Welcome, {{ Auth::user()->name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Here's your training overview for today</p>
                        </div>
                    </div>
                    <div class="mt-4 grid grid-cols-2 gap-2">
                        <div class="text-center p-3 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
                            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $upcomingSessions ?? 0 }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Upcoming Sessions</p>
                        </div>
                        <div class="text-center p-3 bg-green-50 dark:bg-green-900/30 rounded-lg">
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $pendingTasks ?? 0 }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Pending Tasks</p>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 col-span-1 lg:col-span-2">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                        <i class="fas fa-bolt mr-2"></i> Quick Actions
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        <!-- Take Attendance with Course Selection -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="w-full bg-blue-100 dark:bg-blue-900 hover:bg-blue-200 dark:hover:bg-blue-800 p-3 rounded-lg text-center">
                                <i class="fas fa-clipboard-check text-blue-500 dark:text-blue-300 text-xl mb-2"></i>
                                <p class="text-sm font-medium text-blue-700 dark:text-blue-300">Take Attendance</p>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute z-10 mt-2 w-64 bg-white dark:bg-gray-800 rounded-lg shadow-lg py-2">
                                <p class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">Select a course:</p>
                                @foreach($myCourses as $course)
                                <a href="{{ route('trainer.attendance.create', ['session' => $currentSession, 'course' => $course->id]) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-100 dark:hover:bg-blue-900">
                                    {{ $course->name }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Create Assessment with Course Selection -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="w-full bg-purple-100 dark:bg-purple-900 hover:bg-purple-200 dark:hover:bg-purple-800 p-3 rounded-lg text-center">
                                <i class="fas fa-plus-circle text-purple-500 dark:text-purple-300 text-xl mb-2"></i>
                                <p class="text-sm font-medium text-purple-700 dark:text-purple-300">Create Assessment</p>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute z-10 mt-2 w-64 bg-white dark:bg-gray-800 rounded-lg shadow-lg py-2">
                                <p class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 border-b border-gray-200 dark:border-gray-700">Select a course:</p>
                                @foreach($myCourses as $course)
                                <a href="{{ route('trainer.assessments.create', ['session' => $currentSession, 'course' => $course->id]) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-purple-100 dark:hover:bg-purple-900">
                                    {{ $course->name }}
                                </a>
                                @endforeach
                            </div>
                        </div>
                        
                        <a href="{{ route('trainer.courses.index') }}" class="bg-green-100 dark:bg-green-900 hover:bg-green-200 dark:hover:bg-green-800 p-3 rounded-lg text-center">
                            <i class="fas fa-book text-green-500 dark:text-green-300 text-xl mb-2"></i>
                            <p class="text-sm font-medium text-green-700 dark:text-green-300">My Courses</p>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Current Session Section -->
            @if($currentSession)
                <div class="bg-blue-100 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-6 mb-8">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
                        <div class="mb-4 md:mb-0">
                            <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-400 flex items-center">
                                <i class="fas fa-play-circle mr-2"></i> Current Session
                            </h3>
                            <p class="text-blue-600 dark:text-blue-400 mt-1 font-medium">{{ $currentSession->name }}</p>
                            <div class="flex flex-wrap items-center mt-2 text-sm text-blue-500 dark:text-bl ue-400">
                                <span class="mr-4 mb-1"><i class="far fa-calendar-alt mr-1"></i> {{ $currentSession->start_date }} - {{ $currentSession->end_date }}</span>
                                <span class="mr-4 mb-1"><i class="fas fa-users mr-1"></i> {{ $currentSession->enrollment_count }} trainees</span>
                                <span class="mb-1"><i class="fas fa-clock mr-1"></i> 
                                    @php
                                        $daysLeft = \Carbon\Carbon::parse($currentSession->end_date)->diffInDays(now());
                                    @endphp
                                    {{ $daysLeft }} {{ Str::plural('day', $daysLeft) }} left
                                </span>
                            </div>
                        </div>  
                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('trainer.attendance.create', $currentSession) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded flex items-center">
                                <i class="fas fa-clipboard-check mr-2"></i>Take Attendance
                            </a>
                            <a href="{{ route('trainer.assessments.create', $currentSession) }}" class="bg-purple-500 hover:bg-purple-600 text-white font-medium py-2 px-4 rounded flex items-center">
                                <i class="fas fa-plus-circle mr-2"></i>Create Assessment
                            </a>
                            <a href="{{ route('trainer.enrollments.session', $currentSession) }}" class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded flex items-center">
                                <i class="fas fa-users mr-2"></i>View Trainees
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- My Courses Section -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                            <i class="fas fa-book mr-2"></i> My Courses
                        </h3>
                        <a href="{{ route('trainer.courses.index') }}" class="text-sm text-blue-500 hover:text-blue-700">View All</a>
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700 max-h-96 overflow-y-auto">
                        @forelse($myCourses as $course)
                        <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h4 class="text-md font-medium text-gray-900 dark:text-white">
                                        {{ $course->name }} ({{ $course->code }})
                                    </h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 line-clamp-1">
                                        {{ $course->description }}
                                    </p>
                                    <div class="flex flex-wrap items-center mt-2 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="mr-4"><i class="fas fa-clock mr-1"></i> {{ $course->duration }} hours</span>
                                        <span class="mr-4"><i class="fas fa-chalkboard-teacher mr-1"></i> {{ $course->method }}</span>
                                        <span class="mr-4"><i class="fas fa-users mr-1"></i> {{ $course->enrollment_count }} trainees</span>
                                        <span class="px-2 py-1 text-xs rounded-full {{ $course->status == 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                            {{ ucfirst($course->status) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex space-x-2 ml-4">
                                    <a href="{{ route('trainer.courses.show', $course) }}" class="text-blue-500 hover:text-blue-700 p-2 rounded-full hover:bg-blue-50 dark:hover:bg-blue-900/30" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('trainer.enrollments.course', $course) }}" class="text-green-500 hover:text-green-700 p-2 rounded-full hover:bg-green-50 dark:hover:bg-green-900/30" title="Trainees">
                                        <i class="fas fa-users"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                            <i class="fas fa-book-open text-3xl mb-3 opacity-50"></i>
                            <p>No courses assigned to you.</p>
                        </div>
                        @endforelse
                    </div>
                </div>

                <!-- Recent Assessments -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Recent Assessments</h3>
                        <a href="{{ route('trainer.assessments.index') }}" class="text-sm text-blue-500 hover:text-blue-700">View All</a>
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700 max-h-96 overflow-y-auto">
                        @forelse($recentAssessments as $assessment)
                        <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
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
                            <div class="flex justify-between items-center mt-3">
                                @if($assessment->submission_date)
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Submitted: {{ $assessment->submission_date->diffForHumans() }}
                                </p>
                                @endif
                                <a href="{{ route('trainer.assessments.show', $assessment) }}" class="text-blue-500 hover:text-blue-700 text-sm font-medium inline-flex items-center">
                                    <i class="fas fa-eye mr-1"></i> View Details
                                </a>
                            </div>
                        </div>
                        @empty
                        <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                            <i class="fas fa-tasks text-3xl mb-3 opacity-50"></i>
                            <p>No assessments found.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Enrollment Sessions Section -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden mt-8">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white flex items-center">
                        <i class="fas fa-list-alt mr-2"></i> All Enrollment Sessions
                    </h3>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($enrollmentSessions as $session)
                    <div class="p-6 {{ $session->id === optional($currentSession)->id ? 'bg-blue-50 dark:bg-blue-900/20' : '' }} hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
                            <div class="mb-3 md:mb-0">
                                <h4 class="text-md font-medium text-gray-900 dark:text-white flex items-center">
                                    {{ $session->name }}
                                    @if($session->id === optional($currentSession)->id)
                                    <span class="ml-2 bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-200">Current</span>
                                    @endif
                                </h4>
                                <div class="flex flex-wrap items-center mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    <span class="mr-4"><i class="far fa-calendar-alt mr-1"></i> {{ $session->start_date }} - {{ $session->end_date }}</span>
                                    <span><i class="fas fa-users mr-1"></i> {{ $session->enrollment_count }} trainees</span>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('trainer.enrollments.session', $session) }}" class="text-blue-500 hover:text-blue-700 text-sm font-medium inline-flex items-center bg-blue-50 dark:bg-blue-900/30 hover:bg-blue-100 dark:hover:bg-blue-900/50 px-3 py-1 rounded">
                                    <i class="fas fa-eye mr-1"></i> Enrollments
                                </a>
                                <a href="{{ route('trainer.attendance.session', $session) }}" class="text-green-500 hover:text-green-700 text-sm font-medium inline-flex items-center bg-green-50 dark:bg-green-900/30 hover:bg-green-100 dark:hover:bg-green-900/50 px-3 py-1 rounded">
                                    <i class="fas fa-clipboard-check mr-1"></i> Attendance
                                </a>
                                <a href="{{ route('trainer.assessments.session', $session) }}" class="text-purple-500 hover:text-purple-700 text-sm font-medium inline-flex items-center bg-purple-50 dark:bg-purple-900/30 hover:bg-purple-100 dark:hover:bg-purple-900/50 px-3 py-1 rounded">
                                    <i class="fas fa-tasks mr-1"></i> Assessments
                                </a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                        <i class="fas fa-calendar-times text-3xl mb-3 opacity-50"></i>
                        <p>No enrollment sessions found.</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <style>
        .line-clamp-1 {
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
    
    <!-- AlpineJS for dropdown functionality -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</x-app-layout>