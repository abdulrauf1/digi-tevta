<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Enrollment Management') }}
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
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Enrollments</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Header with Create Button -->
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                        <div>
                            <h3 class="text-2xl font-semibold">Enrollment Management</h3>
                            <p class="text-gray-500 dark:text-gray-400 mt-1">Manage all enrollments in one place</p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <button id="toggleFiltersBtn" class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 px-4 py-2 rounded-lg flex items-center">
                                <i class="fas fa-filter mr-2"></i>Filters
                            </button>
                            <button id="addSessionBtn" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg flex items-center">
                                <i class="fas fa-calendar-plus mr-2"></i>New Session
                            </button>
                            <a href="{{ route('admin-clerk.enrollments.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                                <i class="fas fa-user-plus mr-2"></i>New Enrollment
                            </a>
                        </div>
                    </div>

                    <!-- Success/Error Messages -->
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 flex items-center">
                            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
                        </div>
                    @endif

                    <!-- Search and Filters Section -->
                    <div id="filtersSection" class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg mb-6 transition-all duration-300" style="display: none;">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                                <div class="relative">
                                    <input type="text" id="searchInput" placeholder="Search by name, course..." class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-white">
                                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                                <select id="statusFilter" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-white">
                                    <option value="">All Statuses</option>
                                    <option value="pending">Pending</option>
                                    <option value="confirm">Confirmed</option>
                                    <option value="cancel">Cancelled</option>
                                    <option value="altered">Altered</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Course</label>
                                <select id="courseFilter" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-white">
                                    <option value="">All Courses</option>
                                    @foreach($courses as $course)
                                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button id="applyFiltersBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg mr-2">
                                Apply Filters
                            </button>
                            <button id="clearFiltersBtn" class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-800 dark:text-gray-200 px-4 py-2 rounded-lg">
                                Clear All
                            </button>
                        </div>
                    </div>

                    <!-- Stats Overview -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-100 dark:border-blue-800">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm text-blue-600 dark:text-blue-400">Total Enrollments</p>
                                    <p class="text-2xl font-bold text-blue-700 dark:text-blue-300">{{ $enrollments->total() }}</p>
                                </div>
                                <div class="bg-blue-100 dark:bg-blue-800 p-3 rounded-full">
                                    <i class="fas fa-users text-blue-600 dark:text-blue-400"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg border border-green-100 dark:border-green-800">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm text-green-600 dark:text-green-400">Confirmed</p>
                                    <p class="text-2xl font-bold text-green-700 dark:text-green-300">{{ $confirmedCount }}</p>
                                </div>
                                <div class="bg-green-100 dark:bg-green-800 p-3 rounded-full">
                                    <i class="fas fa-check-circle text-green-600 dark:text-green-400"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg border border-yellow-100 dark:border-yellow-800">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm text-yellow-600 dark:text-yellow-400">Pending</p>
                                    <p class="text-2xl font-bold text-yellow-700 dark:text-yellow-300">{{ $pendingCount }}</p>
                                </div>
                                <div class="bg-yellow-100 dark:bg-yellow-800 p-3 rounded-full">
                                    <i class="fas fa-clock text-yellow-600 dark:text-yellow-400"></i>
                                </div>
                            </div>
                        </div>
                        <div class="bg-red-50 dark:bg-red-900/20 p-4 rounded-lg border border-red-100 dark:border-red-800">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-sm text-red-600 dark:text-red-400">Cancelled</p>
                                    <p class="text-2xl font-bold text-red-700 dark:text-red-300">{{ $cancelledCount }}</p>
                                </div>
                                <div class="bg-red-100 dark:bg-red-800 p-3 rounded-full">
                                    <i class="fas fa-times-circle text-red-600 dark:text-red-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Enrollment Table -->
                    <div class="overflow-x-auto rounded-lg shadow">
                        <table class="min-w-full bg-white dark:bg-gray-700">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-600 text-left text-xs font-semibold uppercase tracking-wider">
                                    <th class="px-4 py-3">Trainee</th>
                                    <th class="px-4 py-3">Course</th>
                                    <th class="px-4 py-3">Session</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3">Enrolled Date</th>
                                    <th class="px-4 py-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                @forelse($enrollments as $enrollment)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                                                    {{ substr($enrollment->trainee->user->name ?? 'N/A', 0, 1) }}
                                                </div>
                                                <div class="ml-4">
                                                    <div class="font-medium text-gray-900 dark:text-white">{{ $enrollment->trainee->user->name ?? 'N/A' }}</div>
                                                    <div class="text-gray-500 dark:text-gray-400 text-sm">{{ $enrollment->trainee->user->email ?? '' }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="text-gray-900 dark:text-white">{{ $enrollment->course->name }}</div>
                                            <div class="text-gray-500 dark:text-gray-400 text-sm">{{ $enrollment->course->code ?? '' }}</div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <div class="text-gray-900 dark:text-white">{{ $enrollment->enrollmentSession->name ?? 'N/A' }}</div>
                                            <div class="text-gray-500 dark:text-gray-400 text-sm">
                                                @if($enrollment->session)
                                                    {{ \Carbon\Carbon::parse($enrollment->session->start_date)->format('M d, Y') }} - 
                                                    {{ \Carbon\Carbon::parse($enrollment->session->end_date)->format('M d, Y') }}
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap">
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($enrollment->status == 'confirm') bg-green-100 text-green-800
                                                @elseif($enrollment->status == 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($enrollment->status == 'cancel') bg-red-100 text-red-800
                                                @elseif($enrollment->status == 'altered') bg-blue-100 text-blue-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst($enrollment->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-gray-500 dark:text-gray-400">
                                            {{ $enrollment->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('admin-clerk.enrollments.show', $enrollment) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 action-btn" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin-clerk.enrollments.edit', $enrollment) }}" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 action-btn" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin-clerk.enrollments.destroy', $enrollment) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 action-btn" title="Delete" onclick="return confirm('Are you sure you want to delete this enrollment?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                            <div class="flex flex-col items-center justify-center">
                                                <i class="fas fa-user-slash text-4xl mb-2 text-gray-400"></i>
                                                <p>No enrollments found.</p>
                                                <a href="{{ route('admin-clerk.enrollments.create') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 mt-2 inline-flex items-center">
                                                    <i class="fas fa-plus mr-1"></i> Create your first enrollment
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $enrollments->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Session Modal -->
    <div id="addSessionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Add New Session</h3>
            </div>
            <form id="sessionForm" action="{{ route('admin-clerk.sessions.store') }}" method="POST">
                @csrf
                <div class="px-6 py-4 space-y-4">
                    <div>
                        <label for="session_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Session Name</label>
                        <input type="text" id="session_name" name="name" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-white" placeholder="Enter session name">
                    </div>
                    
                    <div>
                        <label for="session_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Session Description</label>
                        <input type="text" id="session_description" name="session_description" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-white" placeholder="Enter Session Description">
                    </div>

                    <div>
                        <label for="duration" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Session Duration(in Month)</label>
                        <input type="number" id="duration" name="duration" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-white" placeholder="Enter Session duration">
                    </div>


                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Start Date</label>
                            <input type="date" id="start_date" name="start_date" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-white">
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">End Date</label>
                            <input type="date" id="end_date" name="end_date" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:text-white">
                        </div>
                    </div>
                    
                </div>
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-2">
                    <button type="button" id="cancelSessionBtn" class="px-4 py-2 text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-700 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Create Session</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle filters section
            const toggleFiltersBtn = document.getElementById('toggleFiltersBtn');
            const filtersSection = document.getElementById('filtersSection');
            
            toggleFiltersBtn.addEventListener('click', function() {
                if (filtersSection.style.display === 'none') {
                    filtersSection.style.display = 'block';
                    toggleFiltersBtn.innerHTML = '<i class="fas fa-times mr-2"></i>Close Filters';
                    toggleFiltersBtn.classList.remove('bg-gray-200', 'dark:bg-gray-700');
                    toggleFiltersBtn.classList.add('bg-blue-100', 'text-blue-700', 'dark:bg-blue-800', 'dark:text-blue-200');
                } else {
                    filtersSection.style.display = 'none';
                    toggleFiltersBtn.innerHTML = '<i class="fas fa-filter mr-2"></i>Filters';
                    toggleFiltersBtn.classList.remove('bg-blue-100', 'text-blue-700', 'dark:bg-blue-800', 'dark:text-blue-200');
                    toggleFiltersBtn.classList.add('bg-gray-200', 'dark:bg-gray-700');
                }
            });

            // Session modal functionality
            const addSessionBtn = document.getElementById('addSessionBtn');
            const addSessionModal = document.getElementById('addSessionModal');
            const cancelSessionBtn = document.getElementById('cancelSessionBtn');
            
            addSessionBtn.addEventListener('click', function() {
                addSessionModal.classList.remove('hidden');
            });
            
            cancelSessionBtn.addEventListener('click', function() {
                addSessionModal.classList.add('hidden');
            });
            
            // Close modal when clicking outside
            addSessionModal.addEventListener('click', function(e) {
                if (e.target === addSessionModal) {
                    addSessionModal.classList.add('hidden');
                }
            });

            // Filter functionality
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const courseFilter = document.getElementById('courseFilter');
            const applyFiltersBtn = document.getElementById('applyFiltersBtn');
            const clearFiltersBtn = document.getElementById('clearFiltersBtn');
            
            applyFiltersBtn.addEventListener('click', function() {
                applyFilters();
            });
            
            clearFiltersBtn.addEventListener('click', function() {
                searchInput.value = '';
                statusFilter.value = '';
                courseFilter.value = '';
                applyFilters();
            });
            
            function applyFilters() {
                const params = new URLSearchParams();
                
                if (searchInput.value) params.append('search', searchInput.value);
                if (statusFilter.value) params.append('status', statusFilter.value);
                if (courseFilter.value) params.append('course', courseFilter.value);
                
                window.location.href = '{{ route('admin-clerk.enrollments.index') }}?' + params.toString();
            }
            
            // Preselect filters from URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('search')) searchInput.value = urlParams.get('search');
            if (urlParams.get('status')) statusFilter.value = urlParams.get('status');
            if (urlParams.get('course')) courseFilter.value = urlParams.get('course');
        });
    </script>
</x-app-layout>