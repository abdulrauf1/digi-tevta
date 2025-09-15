<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Mark Custom Leave Period - {{ $session->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-6 bg-blue-100 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-400">Session Information</h3>
                        <p class="text-blue-700 dark:text-blue-300">{{ $session->name }}</p>
                        <p class="text-sm text-blue-600 dark:text-blue-400">
                            {{ $session->start_date }} to {{ $session->end_date }}
                        </p>
                    </div>

                    <form action="{{ route('trainer.attendance.store-leave-day', $session) }}" method="POST">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="date_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    From Date
                                </label>
                                <input type="date" name="date_from" id="date_from" value="{{ $date_from }}" 
                                    class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full"
                                    required>
                                @error('date_from')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="date_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    To Date
                                </label>
                                <input type="date" name="date_to" id="date_to" value="{{ $date_to }}" 
                                    class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full"
                                    required>
                                @error('date_to')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Reason
                                </label>
                                <input type="text" name="reason" id="reason" 
                                    class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full"
                                    placeholder="e.g., Public Holiday, Training Event, Vacation, etc." required>
                                @error('reason')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Description (Optional)
                                </label>
                                <textarea name="description" id="description" rows="3"
                                    class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full"
                                    placeholder="Additional details about this leave period"></textarea>
                                @error('description')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <a href="{{ route('trainer.attendance.monthly', $session) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded mr-3">
                                Cancel
                            </a>
                            <button type="submit" class="bg-purple-500 hover:bg-purple-600 text-white font-medium py-2 px-4 rounded">
                                Mark as Leave Period
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-set date_to to same as date_from if not set
        document.getElementById('date_from').addEventListener('change', function() {
            const dateTo = document.getElementById('date_to');
            if (!dateTo.value || dateTo.value < this.value) {
                dateTo.value = this.value;
            }
        });
    </script>
</x-app-layout>