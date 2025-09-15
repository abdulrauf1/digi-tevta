<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Take Attendance - {{ $session->name }}
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
                        <p class="text-sm text-blue-600 dark:text-blue-400 mt-1">
                            Date: {{ today()->format('F j, Y') }}
                        </p>
                    </div>

                    <!-- Updated form action and method -->
                    <form action="{{ route('trainer.attendance.create', $session) }}" method="POST">
                        @csrf
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
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
                                    @foreach($enrollments as $enrollment)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $enrollment->trainee->user->name }}
                                                    </div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                                        {{ $enrollment->trainee->cnic }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <select name="attendance[{{ $enrollment->id }}]" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                                <option value="Present" {{ isset($todayAttendance[$enrollment->id]) && $todayAttendance[$enrollment->id]->status == 'Present' ? 'selected' : '' }}>Present</option>
                                                <option value="Absent" {{ isset($todayAttendance[$enrollment->id]) && $todayAttendance[$enrollment->id]->status == 'Absent' ? 'selected' : '' }}>Absent</option>
                                                <option value="Late" {{ isset($todayAttendance[$enrollment->id]) && $todayAttendance[$enrollment->id]->status == 'Late' ? 'selected' : '' }}>Late</option>
                                                <option value="Leave" {{ isset($todayAttendance[$enrollment->id]) && $todayAttendance[$enrollment->id]->status == 'Leave' ? 'selected' : '' }}>Leave</option>
                                            </select>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <input type="text" name="remarks[{{ $enrollment->id }}]" 
                                                value="{{ isset($todayAttendance[$enrollment->id]) ? $todayAttendance[$enrollment->id]->remarks : '' }}"
                                                class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-full"
                                                placeholder="Optional remarks">
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <a href="{{ route('trainer.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded mr-3">
                                Cancel
                            </a>
                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded">
                                Save Attendance
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>