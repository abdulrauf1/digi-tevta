<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User Details') }}
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
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400"></i>
                            <a href="{{ route('admin.users.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">
                                Users
                            </a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400"></i>
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">User Details</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">User Information</h3>
                        <div>
                            <a href="{{ route('admin.users.edit', $user) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded mr-2">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                <i class="fas fa-arrow-left mr-1"></i>Back to List
                            </a>
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <div class="flex items-center space-x-4 mb-4">
                                    <div class="flex-shrink-0">
                                        <div class="h-16 w-16 rounded-full bg-blue-500 flex items-center justify-center text-white text-2xl font-bold">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    </div>
                                    <div>
                                        <h2 class="text-2xl font-bold">{{ $user->name }}</h2>
                                        <p class="text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">User ID</h4>
                                <p class="mt-1 text-lg font-semibold">#{{ $user->id }}</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</h4>
                                <p class="mt-1">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        Active
                                    </span>
                                </p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</h4>
                                <p class="mt-1 text-lg font-semibold">{{ $user->email }}</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Email Verified</h4>
                                <p class="mt-1 text-lg font-semibold">
                                    @if($user->email_verified_at)
                                        <span class="text-green-600 dark:text-green-400">Yes</span>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">({{ $user->email_verified_at->format('M d, Y') }})</span>
                                    @else
                                        <span class="text-red-600 dark:text-red-400">No</span>
                                    @endif
                                </p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Roles</h4>
                                <div class="mt-1">
                                    @foreach($user->roles as $role)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $role->name == 'admin' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200' : '' }}
                                            {{ $role->name == 'trainer' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}
                                            {{ $role->name == 'trainee' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                            {{ !in_array($role->name, ['admin', 'trainer', 'trainee']) ? 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200' : '' }}">
                                            {{ ucfirst($role->name) }}
                                        </span>
                                    @endforeach
                                    @if($user->roles->count() == 0)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200">
                                            No roles assigned
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Permissions</h4>
                                <div class="mt-1">
                                    @if($user->permissions->count() > 0)
                                        @foreach($user->permissions as $permission)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200 mb-1">
                                                {{ $permission->name }}
                                            </span>
                                        @endforeach
                                    @else
                                        <span class="text-sm text-gray-500 dark:text-gray-400">No direct permissions assigned</span>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Account Created</h4>
                                <p class="mt-1 text-lg font-semibold">{{ $user->created_at->format('M d, Y') }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->created_at->diffForHumans() }}</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Updated</h4>
                                <p class="mt-1 text-lg font-semibold">{{ $user->updated_at->format('M d, Y') }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Additional User Information Section -->
                    <div class="mt-6 bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                        <h4 class="text-md font-semibold mb-4">Additional Information</h4>

                        <!-- Login Sessions Section -->
                        <div class="mt-6 bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                            <h4 class="text-md font-semibold mb-4">Login Sessions</h4>
                            
                            @if($lastLogin)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Login</h4>
                                        <p class="mt-1 text-lg font-semibold">
                                            {{ $lastLogin['last_activity']->format('M d, Y H:i') }}
                                            <span class="text-sm text-gray-500 dark:text-gray-400">({{ $lastLogin['last_activity']->diffForHumans() }})</span>
                                        </p>
                                    </div>

                                    <div>
                                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Login IP</h4>
                                        <p class="mt-1 text-lg font-semibold">
                                            {{ $lastLogin['ip_address'] }}
                                        </p>
                                    </div>
                                    
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Device</h4>
                                        <p class="mt-1 text-lg font-semibold">
                                            {{ $lastLogin['device'] }}
                                        </p>
                                    </div>
                                    
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Browser</h4>
                                        <p class="mt-1 text-lg font-semibold">
                                            {{ $lastLogin['browser'] }}
                                        </p>
                                    </div>
                                </div>
                            @else
                                <p class="text-gray-500 dark:text-gray-400">No login sessions found.</p>
                            @endif
                            
                            @if($sessions->count() > 1)
                                <div class="mt-4">
                                    <h5 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Recent Sessions ({{ $sessions->count() }})</h5>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                            <thead class="bg-gray-100 dark:bg-gray-600">
                                                <tr>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Date</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">IP Address</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Device</th>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Browser</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                                @foreach($sessions->take(5) as $session)
                                                    <tr>
                                                        <td class="px-4 py-2 text-sm">{{ $session['last_activity']->format('M d, Y H:i') }}</td>
                                                        <td class="px-4 py-2 text-sm">{{ $session['ip_address'] }}</td>
                                                        <td class="px-4 py-2 text-sm">{{ $session['device'] }}</td>
                                                        <td class="px-4 py-2 text-sm">{{ $session['browser'] }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>