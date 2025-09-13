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
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-semibold">Enrollment List</h3>
                        <a href="{{ route('enrollments.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                            <i class="fas fa-plus mr-2"></i>New Enrollment
                        </a>
                    </div>

                    <!-- Success Message -->
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Enrollment Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-700 rounded-lg">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-600">
                                    <th class="px-4 py-2 text-left">Trainee</th>
                                    <th class="px-4 py-2 text-left">Course</th>
                                    <th class="px-4 py-2 text-left">Session</th>
                                    <th class="px-4 py-2 text-left">Status</th>
                                    <th class="px-4 py-2 text-left">Enrolled Date</th>
                                    <th class="px-4 py-2 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($enrollments as $enrollment)
                                    <tr class="border-b border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <td class="px-4 py-3">
                                            {{ $enrollment->trainee->user->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-4 py-3">{{ $enrollment->course->name }}</td>
                                        <td class="px-4 py-3">{{ $enrollment->session->name ?? 'N/A' }}</td>
                                        <td class="px-4 py-3">
                                            <span class="px-2 py-1 text-xs rounded-full 
                                                @if($enrollment->status == 'confirm') bg-green-100 text-green-800
                                                @elseif($enrollment->status == 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($enrollment->status == 'cancel') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst($enrollment->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-3">{{ $enrollment->created_at->format('M d, Y') }}</td>
                                        <td class="px-4 py-3">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('enrollments.show', $enrollment) }}" class="text-blue-600 hover:text-blue-800">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('enrollments.edit', $enrollment) }}" class="text-green-600 hover:text-green-800">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('enrollments.destroy', $enrollment) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Are you sure?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-4 py-4 text-center text-gray-500">
                                            No enrollments found.
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
</x-app-layout>