<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            My Courses
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($courses as $course)
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg shadow p-6 hover:shadow-md transition-shadow">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $course->name }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $course->code }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-300 mt-2 line-clamp-2">{{ $course->description }}</p>
                            
                            <div class="flex items-center mt-4 text-sm text-gray-500 dark:text-gray-400">
                                <span class="mr-4"><i class="fas fa-users mr-1"></i> {{ $course->enrollment_count }} trainees</span>
                                <span class="px-2 py-1 text-xs rounded-full {{ $course->status == 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' }}">
                                    {{ ucfirst($course->status) }}
                                </span>
                            </div>
                            
                            <div class="mt-4 flex justify-between items-center">
                                <a href="{{ route('trainer.courses.show', $course) }}" class="text-blue-500 hover:text-blue-700 text-sm font-medium">
                                    View Details
                                </a>
                                <a href="{{ route('trainer.enrollments.course', $course) }}" class="text-green-500 hover:text-green-700 text-sm font-medium">
                                    View Trainees
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    @if($courses->isEmpty())
                    <div class="text-center py-12">
                        <i class="fas fa-book-open text-4xl text-gray-400 dark:text-gray-500 mb-4"></i>
                        <p class="text-gray-500 dark:text-gray-400">No courses assigned to you.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>