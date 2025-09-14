<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Trainers Management') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Breadcrumbs -->
            <nav class="mb-4 flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                            <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 极l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 极 1 1v4a1 1 0 0 0 1 1极3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                            <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Trainers</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Header Section -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Trainer Management</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage all trainers in the system</p>
                    </div>
                    <a href="{{ route('admin-clerk.trainers.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Add New Trainer
                    </a>
                </div>

                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6 flex items-center" role="alert">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    @if($trainers->count() > 0)
                        <!-- Stats Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                            <div class="bg-indigo-50 dark:bg-indigo-900/20 p-4 rounded-lg border border-indigo-100 dark:border-indigo-800">
                                <div class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">{{ $trainers->count() }}</div>
                                <div class="text-sm text-indigo-700 dark:text-indigo-300">Total Trainers</div>
                            </div>
                            <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-100 dark:border-blue-800">
                                <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">
                                    {{ $trainers->where('experience', '>=', 5)->count() }}
                                </div>
                                <div class="text-sm text-blue-700 dark:text-blue-300">Experienced (5+ years)</div>
                            </div>
                            <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg border border-green-100 dark:border-green-800">
                                <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                                    {{ $trainers->unique('specialization')->count() }}
                                </div>
                                <div class="text-sm text-green-700 dark:text-green-300">Specializations</div>
                            </div>
                            <div class="bg-purple-50 dark:bg-purple-900/20 p-4 rounded-lg border border-purple-100 dark:border-purple-800">
                                <div class="text-2xl font-bold text-purple-600 dark:text-purple-400">
                                    {{ $trainers->avg('experience') ? round($trainers->avg('experience'), 1) : 0 }}
                                </div>
                                <div class="text-sm text-purple-700 dark:text-purple-300">Avg. Experience (years)</div>
                            </div>
                        </div>

                        <!-- Trainers Table -->
                        <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-100 dark:bg-gray-800">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Trainer</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Specialization</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Experience</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($trainers as $trainer)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <div class="h-10 w-10 rounded-full bg-indigo-100 dark:bg-indigo-900 flex items-center justify-center">
                                                            <span class="font-medium text-indigo-800 dark:text-indigo-200">
                                                                {{ substr($trainer->user->name, 0, 1) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $trainer->user->name }}</div>
                                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $trainer->user->email }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900 dark:text-white">{{ $trainer->specialization }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900 dark:text-white">{{ $trainer->experience }} years</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    @if($trainer->experience >= 5)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Expert</span>
                                                    @elseif($trainer->experience >= 3)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">Experienced</span>
                                                    @else
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Junior</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    <svg class="-ml-0.5 mr-1.5 h-2 w-2 text-green-400" fill="currentColor" viewBox="0 0 8 8">
                                                        <circle cx="4" cy="4" r="3" />
                                                    </svg>
                                                    Active
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex items-center justify-end space-x-2">
                                                    <a href="{{ route('admin-clerk.trainers.show', $trainer->user) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-150" title="View Details">
                                                        <i class="fas fa-eye mr-1"></i>
                                                    </a>
                                                    <a href="{{ route('admin-clerk.trainers.edit', $trainer->user) }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300 transition-colors duration-150" title="Edit">
                                                        <i class="fas fa-edit mr-1"></i>
                                                    </a>
                                                    <form action="{{ route('admin-clerk.trainers.destroy', $trainer->user) }}" method="POST" class="inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 transition-colors duration-150" title="Delete" onclick="return confirm('Are you sure you want to delete this trainer?')">
                                                            <i class="fas fa-trash mr-1"></i> 
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $trainers->links() }}
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-12">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 极 2 2 0 014 0z" />
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No trainers found</h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-6">Get started by adding your first trainer to the system.</p>
                            <a href="{{ route('admin-clerk.trainers.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>
                                Add New Trainer
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>