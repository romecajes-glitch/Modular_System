<!-- Modal for Student Details -->
<div id="studentDetailsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Student Details</h3>
                <button id="closeModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <div class="bg-white rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center mb-6">
                            <div class="flex-shrink-0 h-16 w-16 bg-blue-100 rounded-full flex items-center justify-center">
                                @if($enrollment->user && $enrollment->user->photo)
                                <img src="{{ asset('storage/' . $enrollment->user->photo) }}" alt="Photo" class="w-16 h-16 rounded-full object-cover">
                                @else
                                <i class="fas fa-user text-blue-600 text-2xl"></i>
                                @endif
                            </div>
                            <div class="ml-6">
                                <h3 class="text-xl font-bold text-gray-900">{{ ucwords($enrollment->full_name) }}</h3>
                                <p class="text-gray-600">{{ $enrollment->email }}</p>
                                <p class="text-sm text-gray-500">{{ $enrollment->program->name ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Personal Information -->
                            <div>
                                <h4 class="text-lg font-semibold text-gray-800 mb-4">Personal Information</h4>
                                <div class="space-y-3">
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Full Name</label>
                                        <p class="text-gray-900">{{ ucwords($enrollment->full_name) }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Email</label>
                                        <p class="text-gray-900">{{ $enrollment->email }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Phone</label>
                                        <p class="text-gray-900">{{ $enrollment->phone ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Birthdate</label>
                                        <p class="text-gray-900">{{ $enrollment->birthdate ? \Carbon\Carbon::parse($enrollment->birthdate)->format('F d, Y') : 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Address</label>
                                                <p class="text-gray-900">{{ ucwords($enrollment->address) ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Program Information -->
                            <div>
                                <h4 class="text-lg font-semibold text-gray-800 mb-4">Program Information</h4>
                                <div class="space-y-3">
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Program</label>
                                        <p class="text-gray-900">{{ $enrollment->program->name ?? 'N/A' }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Status</label>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ ucfirst($enrollment->status) }}
                                        </span>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Paid Sessions</label>
                                        <p class="text-gray-900">{{ $enrollment->paid_sessions ?? 0 }} of {{ $enrollment->total_sessions ?? 0 }}</p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500">Enrollment Date</label>
                                        <p class="text-gray-900">{{ $enrollment->created_at->format('F d, Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Attendance History -->
                        <div class="mt-8">
                            <h4 class="text-lg font-semibold text-gray-800 mb-4">Attendance History</h4>
                            <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Session #</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">OR Number</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference #</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Marked By</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @php
                                    $sortedAttendances = $enrollment->attendances->sortBy('session_number');
                                @endphp
                                @forelse ($sortedAttendances as $attendance)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $attendance->session_number }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $attendance->session_date->format('F d, Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $attendance->created_at ? $attendance->created_at->format('g:i A') : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            {{ $attendance->status === 'present' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($attendance->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $attendance->or_number ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($attendance->or_number && !is_numeric($attendance->or_number))
                                            {{ $attendance->or_number }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($enrollment->program && $enrollment->program->price_per_session)
                                            ₱{{ number_format($enrollment->program->price_per_session, 2) }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($attendance->markedByUser)
                                            {{ $attendance->markedByUser->name }}
                                        @else
                                            System
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-4 text-center text-gray-500">No attendance records found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
