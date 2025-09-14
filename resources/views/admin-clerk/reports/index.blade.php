<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Reports Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-blue-100 dark:bg-blue-900 p-6 rounded-lg shadow-md transition-all hover:scale-105">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-500 text-white mr-4">
                            <i class="fas fa-book text-xl"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold">{{ $stats['total_courses'] }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Total Courses</p>
                        </div>
                    </div>
                </div>

                <div class="bg-green-100 dark:bg-green-900 p-6 rounded-lg shadow-md transition-all hover:scale-105">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-500 text-white mr-4">
                            <i class="fas fa-chalkboard-teacher text-xl"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold">{{ $stats['total_trainers'] }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Total Trainers</p>
                        </div>
                    </div>
                </div>

                <div class="bg-purple-100 dark:bg-purple-900 p-6 rounded-lg shadow-md transition-all hover:scale-105">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-500 text-white mr-4">
                            <i class="fas fa-user-graduate text-xl"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold">{{ $stats['total_trainees'] }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Total Trainees</p>
                        </div>
                    </div>
                </div>

                <div class="bg-yellow-100 dark:bg-yellow-900 p-6 rounded-lg shadow-md transition-all hover:scale-105">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-500 text-white mr-4">
                            <i class="fas fa-clipboard-list text-xl"></i>
                        </div>
                        <div>
                            <p class="text-2xl font-bold">{{ $stats['total_enrollments'] }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300">Total Enrollments</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts and Data Visualization -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Enrollment Trends Chart -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                    <h4 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Enrollment Trends (Last 6 Months)</h4>
                    <canvas id="enrollmentTrendChart" height="250"></canvas>
                </div>

                <!-- Popular Courses Chart -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                    <h4 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Most Popular Courses</h4>
                    <div class="space-y-4">
                        @foreach($popularCourses as $course)
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700 dark:text-gray-300 truncate">{{ $course->title }}</span>
                            <div class="flex items-center">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 mr-2">{{ $course->enrollments_count }} enrollments</span>
                                <div class="w-20 bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                    <div class="bg-blue-600 h-2.5 rounded-full" 
                                         style="width: {{ ($course->enrollments_count / max($popularCourses->max('enrollments_count'), 1)) * 100 }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Recent Activities and Quick Actions -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Enrollments -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                    <h4 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Recent Enrollments</h4>
                    <div class="space-y-4">
                        @foreach($recentEnrollments as $enrollment)
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $enrollment->trainee->name }} enrolled in {{ $enrollment->course->title }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $enrollment->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
                    <h4 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Quick Actions</h4>
                    <div class="grid grid-cols-1 gap-4">
                        <a href="{{ route('admin-clerk.courses.create') }}" class="flex items-center p-3 bg-blue-50 dark:bg-blue-900 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-800 transition">
                            <div class="p-2 rounded-full bg-blue-500 text-white mr-3">
                                <i class="fas fa-plus"></i>
                            </div>
                            <span class="text-gray-900 dark:text-white">Add New Course</span>
                        </a>
                        <a href="{{ route('admin.users.create', ['role' => 'trainer']) }}" class="flex items-center p-3 bg-green-50 dark:bg-green-900 rounded-lg hover:bg-green-100 dark:hover:bg-green-800 transition">
                            <div class="p-2 rounded-full bg-green-500 text-white mr-3">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <span class="text-gray-900 dark:text-white">Add New Trainer</span>
                        </a>
                        <a href="{{ route('admin.users.create', ['role' => 'trainee']) }}" class="flex items-center p-3 bg-purple-50 dark:bg-purple-900 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-800 transition">
                            <div class="p-2 rounded-full bg-purple-500 text-white mr-3">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <span class="text-gray-900 dark:text-white">Add New Trainee</span>
                        </a>
                        <!-- route('enrollments.create') -->
                        <a href="{{ route('admin-clerk.enrollments.create') }}" class="flex items-center p-3 bg-yellow-50 dark:bg-yellow-900 rounded-lg hover:bg-yellow-100 dark:hover:bg-yellow-800 transition">
                            <div class="p-2 rounded-full bg-yellow-500 text-white mr-3">
                                <i class="fas fa-clipboard-check"></i>
                            </div>
                            <span class="text-gray-900 dark:text-white">Create New Enrollment</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Enrollment Trends Chart
            const trendCtx = document.getElementById('enrollmentTrendChart').getContext('2d');
            const trendChart = new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: @json(array_column($enrollmentTrends, 'month')),
                    datasets: [{
                        label: 'Enrollments',
                        data: @json(array_column($enrollmentTrends, 'count')),
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2,
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-app-layout>