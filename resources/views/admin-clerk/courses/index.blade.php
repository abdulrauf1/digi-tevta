<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Courses Management') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            <nav class="mb-4 flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                            <i class="fas fa-home mr-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400"></i>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Courses</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Header Section -->
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Course Catalog</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage all your training courses in one place</p>
                        </div>
                        <a href="{{ route('admin-clerk.courses.create') }}" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold py-3 px-6 rounded-lg shadow-md transition-all duration-200 transform hover:-translate-y-1 flex items-center">
                            <i class="fas fa-plus-circle mr-2"></i>
                            Create New Course
                        </a>
                    </div>

                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-lg shadow-sm dark:bg-green-900 dark:border-green-600 dark:text-green-200" role="alert">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle mr-3"></i>
                                <span>{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif

                    <!-- Stats Overview -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                        <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg shadow-sm border-l-4 border-blue-500">
                            <div class="flex items-center">
                                <div class="p-2 rounded-full bg-blue-100 dark:bg-blue-700 text-blue-600 dark:text-blue-200 mr-3">
                                    <i class="fas fa-book"></i>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold">{{ $courses->total() }}</p>
                                    <p class="text-sm text-blue-600 dark:text-blue-200">Total Courses</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-green-50 dark:bg-green-900 p-4 rounded-lg shadow-sm border-l-4 border-green-500">
                            <div class="flex items-center">
                                <div class="p-2 rounded-full bg-green-100 dark:bg-green-700 text-green-600 dark:text-green-200 mr-3">
                                    <i class="fas fa-play-circle"></i>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold">{{ $courses->where('status', 'active')->count() }}</p>
                                    <p class="text-sm text-green-600 dark:text-green-200">Active Courses</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-purple-50 dark:bg-purple-900 p-4 rounded-lg shadow-sm border-l-4 border-purple-500">
                            <div class="flex items-center">
                                <div class="p-2 rounded-full bg-purple-100 dark:bg-purple-700 text-purple-600 dark:text-purple-200 mr-3">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold">0</p>
                                    <p class="text-sm text-purple-600 dark:text-purple-200">Enrollments</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-orange-50 dark:bg-orange-900 p-4 rounded-lg shadow-sm border-l-4 border-orange-500">
                            <div class="flex items-center">
                                <div class="p-2 rounded-full bg-orange-100 dark:bg-orange-700 text-orange-600 dark:text-orange-200 mr-3">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold">0</p>
                                    <p class="text-sm text-orange-600 dark:text-orange-200">Avg. Rating</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Courses Grid -->
                    @if($courses->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($courses as $course)
                            <div class="bg-white dark:bg-gray-700 rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300 border border-gray-200 dark:border-gray-600">
                                <!-- Course Image Placeholder -->
                                <div class="h-40 bg-gradient-to-r from-blue-400 to-blue-600 flex items-center justify-center">
                                    <i class="fas fa-book-open text-white text-5xl opacity-80"></i>
                                </div>
                                
                                <div class="p-5">
                                    <!-- Course Title -->
                                    <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2 line-clamp-1">{{ $course->name }}</h4>
                                    
                                    <!-- Course Description -->
                                    <p class="text-gray-600 dark:text-gray-300 mb-4 line-clamp-2 text-sm">{{ $course->description }}</p>
                                    
                                    <!-- Trainer Name -->
                                    <span class="text-large bg-gray-100 px-2 py-1 text-gray-800 dark:bg-gray-900 dark:text-gray-200 rounded-full">
                                            <i class="fas fa-chalkboard-teacher mr-1">‌</i>{{ $course->trainer->user->name }}
                                    </span>
                                    <!-- Course Meta -->
                                    <div class="flex justify-between items-center mb-4 mt-3">
                                        <span class="text-xs font-medium px-2 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 rounded-full">
                                            <i class="fas fa-clock mr-1"></i>{{ $course->duration }}
                                        </span>
                                        <span class="text-xs font-bold px-2 py-1 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 rounded-full">
                                            <i class="fas fa-list-alt mr-1 ml-1"></i>{{ $course->modules->count() }}Modules
                                        </span>
                                    </div>
                                    
                                    <!-- Action Buttons -->
                                    <div class="flex justify-between items-center pt-3 border-t border-gray-100 dark:border-gray-600">
                                        <a href="{{ route('admin-clerk.courses.show', $course) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-200 flex items-center">
                                            <i class="fas fa-eye mr-1"></i> View
                                        </a>
                                        <a href="{{ route('admin-clerk.courses.edit', $course) }}" class="text-yellow-600 hover:text-yellow-800 dark:text-yellow-400 dark:hover:text-yellow-300 transition-colors duration-200 flex items-center">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </a>
                                        <form action="{{ route('admin-clerk.courses.destroy', $course) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-200 flex items-center" onclick="return confirm('Are you sure you want to delete this course?')">
                                                <i class="fas fa-trash mr-1"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-12">
                            <div class="mx-auto w-24 h-24 mb-4 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                <i class="fas fa-book-open text-gray-400 text-3xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No courses yet</h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-6">Get started by creating your first course</p>
                            <a href="{{ route('admin-clerk.courses.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition ease-in-out duration-150">
                                <i class="fas fa-plus mr-2"></i> Create First Course
                            </a>
                        </div>
                    @endif
                    
                    <!-- Pagination -->
                    @if($courses->hasPages())
                        <div class="mt-8">
                            {{ $courses->links() }}
                        </div>
                    @endif
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
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .shadow-hover {
            transition: all 0.3s ease;
        }
        .shadow-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</x-app-layout>