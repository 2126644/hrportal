@extends('layouts.master')

@section('content')
<div class="p-6 space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Leave Management</h2>
            <p class="text-gray-600">Manage your leave requests and view your balance.</p>
        </div>
        <button 
            onclick="document.getElementById('leaveForm').classList.remove('hidden')"
            class="flex items-center space-x-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>Apply for Leave</span>
        </button>
    </div>

    {{-- Leave Balance Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="cursor-pointer hover:shadow-md transition-shadow bg-white p-4 rounded-lg border" onclick="filterLeaves('all')">
            <div class="flex items-center justify-between pb-2">
                <p class="text-sm font-medium">Total Requests</p>
                <i data-feather="calendar"></i>
            </div>
            <div class="text-2xl font-bold">{{ $leaveStats['total'] }}</div>
            <p class="text-xs text-gray-600">Click to view all</p>
        </div>

        <div class="cursor-pointer hover:shadow-md transition-shadow bg-white p-4 rounded-lg border" onclick="filterLeaves('approved')">
            <div class="flex items-center justify-between pb-2">
                <p class="text-sm font-medium">Approved</p>
                <i data-feather="check-circle"></i>
            </div>
            <div class="text-2xl font-bold text-green-600">{{ $leaveStats['approved'] }}</div>
            <p class="text-xs text-gray-600">Click to filter</p>
        </div>

        <div class="cursor-pointer hover:shadow-md transition-shadow bg-white p-4 rounded-lg border" onclick="filterLeaves('pending')">
            <div class="flex items-center justify-between pb-2">
                <p class="text-sm font-medium">Pending</p>
                <i data-feather="clock"></i>
            </div>
            <div class="text-2xl font-bold text-yellow-600">{{ $leaveStats['pending'] }}</div>
            <p class="text-xs text-gray-600">Click to filter</p>
        </div>
    </div>

    {{-- Leave Balances --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <x-leave-balance-card title="Annual Leave Balance" color="blue" :balance="$leaveBalance['annual']"/>
        <x-leave-balance-card title="Sick Leave Balance" color="red" :balance="$leaveBalance['sick']"/>
        <x-leave-balance-card title="Personal Leave Balance" color="green" :balance="$leaveBalance['personal']"/>
    </div>

    {{-- Apply Leave Form --}}
    <div id="leaveForm" class="hidden bg-white border rounded-lg p-6">
        <h3 class="text-lg font-semibold mb-4">Apply for Leave</h3>
        <form action="{{ route('employee.leaves.store') }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Leave Type</label>
                    <select name="type" class="w-full p-2 border rounded-md">
                        <option value="annual">Annual Leave</option>
                        <option value="sick">Sick Leave</option>
                        <option value="personal">Personal Leave</option>
                        <option value="emergency">Emergency Leave</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Start Date</label>
                    <input type="date" name="start_date" class="w-full p-2 border rounded-md" required>
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">End Date</label>
                    <input type="date" name="end_date" class="w-full p-2 border rounded-md" required>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Reason</label>
                <textarea name="reason" class="w-full p-2 border rounded-md h-24 resize-none" placeholder="Enter reason..." required></textarea>
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">Submit</button>
                <button type="button" onclick="document.getElementById('leaveForm').classList.add('hidden')" class="border px-4 py-2 rounded-md">Cancel</button>
            </div>
        </form>
    </div>

    {{-- Leave Requests History --}}
    <div class="bg-white border rounded-lg">
        <div class="p-4 border-b">
            <h3 class="text-lg font-semibold">Leave Requests</h3>
        </div>
        <div class="p-4 overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-2 px-2">Type</th>
                        <th class="text-left py-2 px-2">Start Date</th>
                        <th class="text-left py-2 px-2">End Date</th>
                        <th class="text-left py-2 px-2">Reason</th>
                        <th class="text-left py-2 px-2">Status</th>
                        <th class="text-left py-2 px-2">Applied</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($leaveRequests as $request)
                    <tr class="border-b">
                        <td class="py-2 px-2">{{ $request->type }}</td>
                        <td class="py-2 px-2 text-gray-600">{{ \Carbon\Carbon::parse($request->start_date)->toFormattedDateString() }}</td>
                        <td class="py-2 px-2 text-gray-600">{{ \Carbon\Carbon::parse($request->end_date)->toFormattedDateString() }}</td>
                        <td class="py-2 px-2 text-gray-600 max-w-xs truncate">{{ $request->reason }}</td>
                        <td class="py-2 px-2">
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                @if($request->status == 'approved') bg-green-100 text-green-800
                                @elseif($request->status == 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($request->status) }}
                            </span>
                        </td>
                        <td class="py-2 px-2 text-gray-600">{{ \Carbon\Carbon::parse($request->applied_date)->toFormattedDateString() }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-6 text-gray-500">No leave requests found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Simple JS filter simulation --}}
<script>
    function filterLeaves(status) {
        // optional: reload via AJAX or redirect with query ?status=approved
        window.location.href = "{{ route('employee.leaves.index') }}?status=" + status;
    }
</script>
@endsection
