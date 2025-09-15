<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Attendance Records - {{ $session->name }}
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
                            {{ $session->start_date }} to {{ $session->end_date }} | 
                            {{ $enrollments->count() }} trainees
                        </p>
                    </div>

                    <div class="mb-4 flex justify-between items-center">
                        <h3 class="text-lg font-semibold">Attendance History</h3>
                        <div class="flex space-x-2">
                            <a href="{{ route('trainer.attendance.monthly', $session) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">
                                Monthly View
                            </a>
                            <a href="{{ route('trainer.attendance.create', $session) }}" class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded">
                                Take Attendance Today
                            </a>
                        </div>
                    </div>

                    
                    @if($attendanceRecords->isEmpty())
                        <div class="bg-yellow-100 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-700 rounded-lg p-4 text-center">
                            <p class="text-yellow-800 dark:text-yellow-400">No attendance records found for this session.</p>
                        </div>
                    @else
                        @foreach($attendanceRecords as $date => $records)
                        <div class="mb-8">
                            <h4 class="text-md font-semibold mb-2 border-b border-gray-200 dark:border-gray-700 pb-2">
                                {{ \Carbon\Carbon::parse($date)->format('F j, Y') }}
                            </h4>
                            
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 mb-4">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Trainee
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Status
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Remarks
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($records as $record)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                            {{ $record->enrollment->trainee->user->name }}
                                                        </div>
                                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                                            {{ $record->enrollment->trainee->cnic }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 py-1 text-xs rounded-full 
                                                    @if($record->status == 'present') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                    @elseif($record->status == 'absent') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                    @elseif($record->status == 'late') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                    @else bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                    @endif">
                                                    {{ ucfirst($record->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $record->remarks ?? '-' }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endforeach
                    @endif

                    <div class="mt-6">
                        <a href="{{ route('trainer.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded">
                            Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>