<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Users Management') }}
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
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Users</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">All Users</h3>
                        <a href="{{ route('admin.users.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            <i class="fas fa-plus mr-2"></i>Add New User
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <!-- Filter Section -->
                    <div class="mb-6 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                        <h4 class="text-md font-semibold mb-3">Filter Users</h4>
                        <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Search</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                    placeholder="Name or email" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white">
                            </div>
                            
                            <div>
                                <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Role</label>
                                <select name="role" id="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white">
                                    <option value="">All Roles</option>
                                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="admission-clerk" {{ request('role') == 'admission-clerk' ? 'selected' : '' }}>Admission-Clerk</option>                                    
                                    <option value="trainer" {{ request('role') == 'trainer' ? 'selected' : '' }}>Trainer</option>
                                    <option value="trainee" {{ request('role') == 'trainee' ? 'selected' : '' }}>Trainee</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="sort" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sort By</label>
                                <select name="sort" id="sort" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-600 dark:text-white">
                                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name (A-Z)</option>
                                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Name (Z-A)</option>
                                </select>
                            </div>
                            
                            <div class="flex items-end">
                                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                                    <i class="fas fa-filter mr-2"></i>Apply Filters
                                </button>
                                <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                    <i class="fas fa-times mr-2"></i>Clear
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Results Count -->
                    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                        Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Role</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($users as $user)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">ID: {{ $user->id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-white">{{ $user->email }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">Joined: {{ $user->created_at->format('M d, Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @foreach($user->roles as $role)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $role->name == 'admin' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : '' }}
                                                {{ $role->name == 'admission-clerk' ? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200' : '' }}
                                                {{ $role->name == 'trainer' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}
                                                {{ $role->name == 'trainee' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}">
                                                {{ ucfirst($role->name) }}
                                            </span>
                                        @endforeach
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <a href="{{ route('admin.users.show', $user) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            @if(optional($user->roles->first())->name == 'admin' || optional($user->roles->first())->name == 'admission-clerk')
                                                <a href="{{ route('admin.users.edit', $user) }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" 
                                                    onclick="return confirm('Are you sure you want to delete this user?')" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        No users found matching your criteria.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>