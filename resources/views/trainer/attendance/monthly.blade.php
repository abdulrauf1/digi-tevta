<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Monthly Attendance - {{ $session->name }} - {{ $selectedDate->format('F Y') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Print Button -->
            <div class="mb-4 flex justify-end print:hidden">
                <button onclick="window.print()" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded flex items-center">
                    <i class="fas fa-print mr-2"></i> Print/Export PDF
                </button>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg print:shadow-none print:bg-white">
                <div class="p-6 text-gray-900 dark:text-gray-100 print:p-2">
                    <!-- Compact Header Section for Printing -->
                    <div class="print:flex print:justify-between print:items-center print:mb-2 print:border-b print:border-gray-300 print:pb-1">
                        <div class="print:text-left">
                            <h1 class="text-2xl font-bold print:text-lg">Monthly Attendance Report</h1>
                            <div class="print:text-xs print:mt-1">
                                <p><strong>Course:</strong> {{ $session->enrollment->first()->course->name ?? 'N/A' }} ({{ $session->enrollment->first()->course->code ?? 'N/A' }})</p>
                                <p><strong>Session:</strong> {{ $session->name }} | {{ $session->start_date }} to {{ $session->end_date }}</p>
                            </div>
                        </div>
                        <div class="print:text-right">
                            <p class="print:text-sm"><strong>Month:</strong> {{ $selectedDate->format('F Y') }}</p>
                            <p class="print:text-sm"><strong>Trainees:</strong> {{ $enrollments->count() }}</p>
                            <p class="print:text-xs print:mt-1">Generated on: {{ now()->format('M j, Y') }}</p>
                        </div>
                    </div>

                    <!-- Session Info and Month Navigation (Hidden when printing) -->
                    <div class="mb-6 bg-blue-100 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-700 rounded-lg p-4 print:hidden">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-400">Session Information</h3>
                                <p class="text-blue-700 dark:text-blue-400">{{ $session->name }}</p>
                                <p class="text-sm text-blue-600 dark:text-blue-400">
                                    {{ $session->start_date }} to {{ $session->end_date }} | 
                                    {{ $enrollments->count() }} trainees
                                </p>
                            </div>
                            <div class="flex items-center space-x-4">
                                <a href="{{ route('trainer.attendance.monthly', ['session' => $session, 'month' => $prevMonth->month, 'year' => $prevMonth->year]) }}" 
                                   class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded">
                                    &larr; {{ $prevMonth->format('M Y') }}
                                </a>
                                <span class="text-lg font-semibold text-blue-800 dark:text-blue-400">{{ $selectedDate->format('F Y') }}</span>
                                <a href="{{ route('trainer.attendance.monthly', ['session' => $session, 'month' => $nextMonth->month, 'year' => $nextMonth->year]) }}" 
                                   class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded">
                                    {{ $nextMonth->format('M Y') }} &rarr;
                                </a>
                                <a href="{{ route('trainer.attendance.create-leave-day', ['session' => $session, 'date_from' => $selectedDate->format('Y-m-d')]) }}" 
                                   class="bg-purple-500 hover:bg-purple-600 text-white font-medium py-2 px-4 rounded">
                                    Mark Leave Period
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Attendance Matrix -->
                    <div class="overflow-x-auto print:overflow-visible">
                        <table class="min-w-full border-collapse border border-gray-300 dark:border-gray-600 print:border-gray-500 print:text-xs">
                            <thead>
                                <tr>
                                    <th class="border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 p-2 text-left sticky left-0 z-10 print:bg-gray-200 print:border-gray-500 print:p-1">
                                        Trainee Name / Date
                                    </th>
                                    @foreach($datesInMonth as $date)
                                        <th class="border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 p-1 text-center min-w-8 {{ $date->isWeekend() ? 'bg-yellow-100 dark:bg-yellow-900/30' : '' }} print:bg-gray-200 print:border-gray-500 print:p-0 print:text-xs">
                                            <div class="print:flex print:flex-col print:items-center print:justify-center print:h-8">
                                                <span>{{ $date->format('d') }}</span>
                                                <span class="text-xs print:text-2xs">{{ $date->format('D') }}</span>
                                            </div>
                                        </th>
                                    @endforeach
                                    <th class="border border-gray-300 dark:border-gray-600 bg-gray-100 dark:bg-gray-700 p-2 text-center sticky right-0 z-10 print:bg-gray-200 print:border-gray-500 print:p-1 print:text-xs">
                                        Total
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($enrollments as $enrollment)
                                <tr class="{{ $loop->even ? 'bg-gray-50 dark:bg-gray-700/50' : '' }} print:break-inside-avoid">
                                    <td class="border border-gray-300 dark:border-gray-600 p-2 sticky left-0 bg-inherit z-10 print:border-gray-500 print:p-1 print:text-xs print:max-w-20 print:truncate">
                                        <div class="font-medium text-gray-900 dark:text-white print:text-black">
                                            {{ $enrollment->trainee->user->name }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 print:text-gray-600">
                                            {{ $enrollment->trainee->cnic }}
                                        </div>
                                    </td>
                                    
                                    @php
                                        $presentCount = 0;
                                        $totalDays = 0;
                                    @endphp
                                    
                                    @foreach($datesInMonth as $date)
                                        @php
                                            $dateString = $date->format('Y-m-d');
                                            $attendance = $attendanceRecords[$enrollment->id][$dateString][0] ?? null;
                                            $status = $attendance ? $attendance->status : '';
                                            $isWeekend = $date->isWeekend();
                                            $isLeaveDay = $leaveDates->has($dateString);
                                            $leaveDay = $isLeaveDay ? $leaveDates[$dateString] : null;
                                            
                                            if ($attendance && $attendance->status === 'Present') {
                                                $presentCount++;
                                            }
                                            // Don't count leave days or weekends in total days
                                            if (!$isWeekend && !$isLeaveDay) {
                                                $totalDays++;
                                            }
                                        @endphp
                                        <td class="border border-gray-300 dark:border-gray-600 p-1 text-center print:border-gray-500 print:p-0 print:text-xs
                                            {{ $isWeekend ? 'bg-gray-100 dark:bg-gray-800' : '' }}
                                            {{ $isLeaveDay ? 'bg-purple-100 dark:bg-purple-900/30' : '' }}
                                            {{ $status === 'Present' ? 'bg-green-100 dark:bg-green-900/30' : '' }}
                                            {{ $status === 'Absent' ? 'bg-red-100 dark:bg-red-900/30' : '' }}
                                            {{ $status === 'Late' ? 'bg-yellow-100 dark:bg-yellow-900/30' : '' }}
                                            {{ $status === 'Leave' ? 'bg-blue-100 dark:bg-blue-900/30' : '' }}">
                                            
                                            @if($isLeaveDay)
                                                <span class="text-purple-600 dark:text-purple-400 print:text-black" title="{{ $leaveDay->reason }} ({{ $leaveDay->date_from->format('M j') }} - {{ $leaveDay->date_to->format('M j') }})">H</span>
                                                @if($leaveDay->created_by == Auth::id())
                                                    <form action="{{ route('trainer.attendance.delete-leave-day', ['session' => $session, 'leaveDay' => $leaveDay]) }}" method="POST" class="inline print:hidden">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-500 hover:text-red-700 text-xs" onclick="return confirm('Remove this leave period?')">×</button>
                                                    </form>
                                                @endif
                                            @elseif($attendance)
                                                @if($status === 'Present')
                                                    <span class="text-green-600 dark:text-green-400 print:text-black" title="Present">P</span>
                                                @elseif($status === 'Absent')
                                                    <span class="text-red-600 dark:text-red-400 print:text-black" title="Absent">A</span>
                                                @elseif($status === 'Late')
                                                    <span class="text-yellow-600 dark:text-yellow-400 print:text-black" title="Late">L</span>
                                                @elseif($status === 'Leave')
                                                    <span class="text-blue-600 dark:text-blue-400 print:text-black" title="Leave">LV</span>
                                                @endif
                                            @elseif($isWeekend)
                                                <span class="text-gray-400 dark:text-gray-500 print:text-black">-</span>
                                            @else
                                                <span class="text-gray-400 dark:text-gray-500 print:text-black">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                    
                                    <td class="border border-gray-300 dark:border-gray-600 p-2 text-center sticky right-0 bg-inherit z-10 print:border-gray-500 print:p-1 print:text-xs">
                                        <span class="font-medium">{{ $presentCount }}/{{ $totalDays }}</span>
                                        <br>
                                        <span class="text-xs">
                                            @if($totalDays > 0)
                                                {{ round(($presentCount / $totalDays) * 100) }}%
                                            @else
                                                0%
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Compact Legend for Printing -->
                    <div class="mt-4 p-3 bg-gray-100 dark:bg-gray-700 rounded-lg print:bg-white print:border print:border-gray-300 print:p-2 print:text-xs">
                        <h4 class="font-semibold mb-2 print:text-black print:text-sm">Legend:</h4>
                        <div class="flex flex-wrap gap-3 print:gap-2">
                            <div class="flex items-center">
                                <span class="inline-block w-5 h-5 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 text-center mr-1 print:border print:border-gray-400 print:bg-white print:text-black print:text-xs">P</span>
                                <span class="print:text-black">Present</span>
                            </div>
                            <div class="flex items-center">
                                <span class="inline-block w-5 h-5 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-center mr-1 print:border print:border-gray-400 print:bg-white print:text-black print:text-xs">A</span>
                                <span class="print:text-black">Absent</span>
                            </div>
                            <div class="flex items-center">
                                <span class="inline-block w-5 h-5 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 text-center mr-1 print:border print:border-gray-400 print:bg-white print:text-black print:text-xs">L</span>
                                <span class="print:text-black">Late</span>
                            </div>
                            <div class="flex items-center">
                                <span class="inline-block w-5 h-5 bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 text-center mr-1 print:border print:border-gray-400 print:bg-white print:text-black print:text-xs">LV</span>
                                <span class="print:text-black">Leave</span>
                            </div>
                            <div class="flex items-center">
                                <span class="inline-block w-5 h-5 bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 text-center mr-1 print:border print:border-gray-400 print:bg-white print:text-black print:text-xs">H</span>
                                <span class="print:text-black">Holiday</span>
                            </div>
                            <div class="flex items-center">
                                <span class="inline-block w-5 h-5 bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-500 text-center mr-1 print:border print:border-gray-400 print:bg-white print:text-black print:text-xs">-</span>
                                <span class="print:text-black">Weekend/No Data</span>
                            </div>
                        </div>
                    </div>

                    <!-- Compact Signatures Section -->
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-8 print:mt-4 print:gap-4 print:text-xs">
                        <div class="border-t border-gray-300 dark:border-gray-600 pt-4 print:border-gray-400 print:pt-2">
                            <p class="text-center font-semibold print:text-black print:text-sm">Trainer's Signature</p>
                            <p class="text-center text-sm text-gray-500 print:text-black print:text-xs">Name: {{ Auth::user()->name }}</p>
                            <p class="text-center text-sm text-gray-500 print:text-black print:text-xs">Date: ________________</p>
                        </div>
                        <div class="border-t border-gray-300 dark:border-gray-600 pt-4 print:border-gray-400 print:pt-2">
                            <p class="text-center font-semibold print:text-black print:text-sm">Principal's Signature</p>
                            <p class="text-center text-sm text-gray-500 print:text-black print:text-xs">Name: ________________</p>
                            <p class="text-center text-sm text-gray-500 print:text-black print:text-xs">Date: ________________</p>
                        </div>
                    </div>

                    <!-- Action Buttons (Hidden when printing) -->
                    <div class="mt-6 flex justify-between print:hidden">
                        <div>
                            <a href="{{ route('trainer.attendance.session', $session) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded mr-3">
                                Back to Daily View
                            </a>
                            <a href="{{ route('trainer.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded">
                                Back to Dashboard
                            </a>
                        </div>
                        <div>
                            <a href="{{ route('trainer.attendance.create', $session) }}" class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded">
                                Take Attendance Today
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .sticky {
            position: sticky;
            backdrop-filter: blur(8px);
        }
        .sticky.left-0 {
            left: 0;
            box-shadow: 2px 0 4px rgba(0,0,0,0.1);
        }
        .sticky.right-0 {
            right: 0;
            box-shadow: -2px 0 4px rgba(0,0,0,0.1);
        }

        /* Print Styles */
        @media print {
            @page {
                size: landscape;
                margin: 0.25in;
            }
            
            body * {
                visibility: hidden;
            }
            
            .print\:shadow-none,
            .print\:shadow-none * {
                visibility: visible;
            }
            
            .print\:shadow-none {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            
            .print\:break-inside-avoid {
                break-inside: avoid;
            }
            
            .print\:text-2xs {
                font-size: 0.6rem;
            }
            
            /* Reduce font sizes for printing */
            .print\:text-xs {
                font-size: 0.7rem;
            }
            
            .print\:text-sm {
                font-size: 0.8rem;
            }
            
            .print\:text-lg {
                font-size: 1.1rem;
            }
            
            /* Reduce padding for table cells */
            .print\:p-0 {
                padding: 0;
            }
            
            .print\:p-1 {
                padding: 0.1rem;
            }
            
            .print\:p-2 {
                padding: 0.2rem;
            }
            
            /* Make table cells compact */
            .print\:h-8 {
                height: 1.5rem;
            }
            
            .print\:max-w-20 {
                max-width: 5rem;
            }
            
            .print\:truncate {
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
        }
    </style>
</x-app-layout>