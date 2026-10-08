@extends('admin.layout')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Payment History</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:underline">Back to Dashboard</a>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-8">
        <form action="{{ route('admin.payments.history') }}" method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Student Email</label>
                <input type="text" name="email" value="{{ request('email') }}" placeholder="Search student by email..." class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">School</label>
                <select name="school_id" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">All Schools</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                <select name="department_id" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                    <option value="">All Departments</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ request('department_id') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700 transition">
                    Search
                </button>
                <a href="{{ route('admin.payments.history') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-bold hover:bg-gray-300 transition text-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="flex justify-end mb-4">
        <form action="{{ route('admin.payments.history.export') }}" method="GET" class="flex gap-2">
            <input type="hidden" name="email" value="{{ request('email') }}">
            <input type="hidden" name="school_id" value="{{ request('school_id') }}">
            <input type="hidden" name="department_id" value="{{ request('department_id') }}">
            <button type="submit" class="flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition text-sm font-medium shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export to CSV (Excel)
            </button>
        </form>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-100">
                <tr class="text-gray-700">
                    <th class="p-4 font-bold border-b">Receipt No</th>
                    <th class="p-4 font-bold border-b">Student</th>
                    <th class="p-4 font-bold border-b">Department</th>
                    <th class="p-4 font-bold border-b">Programme</th>
                    <th class="p-4 font-bold border-b">Amount</th>
                    <th class="p-4 font-bold border-b">Status</th>
                    <th class="p-4 font-bold border-b">Date</th>
                    <th class="p-4 font-bold border-b text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-4 font-mono text-sm">{{ $payment->receipt->receipt_no ?? 'N/A' }}</td>
                        <td class="p-4">
                            <div class="font-medium">{{ $payment->student->user->name ?? 'Unknown' }}</div>
                            <div class="text-xs text-gray-500">{{ $payment->student->user->email ?? 'N/A' }}</div>
                        </td>
                        <td class="p-4 text-sm text-gray-600">{{ $payment->student->department->name ?? 'N/A' }}</td>
                        <td class="p-4 text-sm text-gray-600">{{ $payment->student->programme->name ?? 'N/A' }}</td>
                        <td class="p-4 font-bold">₦{{ number_format($payment->amount, 2) }}</td>
                        <td class="p-4">
                            <span class="px-2 py-1 rounded-full text-xs font-bold {{ $payment->status === 'success' ? 'bg-green-100 text-green-700' : ($payment->status === 'initiated' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td class="p-4 text-sm text-gray-500">{{ $payment->created_at->format('M d, Y H:i') }}</td>
                        <td class="p-4 text-center">
                            <div class="flex flex-col gap-2 items-center">
                                <form action="{{ route('admin.payments.history.confirm', $payment->id) }}" method="POST" onsubmit="return confirm('Confirm this payment as successful? This will update the status and refresh the manual receipt.');">
                                    @csrf
                                    <button type="submit" class="bg-green-100 text-green-600 px-3 py-1 rounded text-xs font-bold hover:bg-green-200 transition border border-green-200">
                                        Confirm Payment
                                    </button>
                                </form>

                                @if(str_contains($payment->receipt->receipt_no ?? '', 'MANUAL'))
                                    <form action="{{ route('admin.payments.history.mark_unpaid', $payment->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this manual payment? This will mark the student as unpaid.');">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="bg-red-100 text-red-600 px-3 py-1 rounded text-xs font-bold hover:bg-red-200 transition border border-red-200">
                                            Mark Unpaid
                                        </button>
                                    </form>
                                @endif
                            </div>
                            @if($payment->status === 'success' && !str_contains($payment->receipt->receipt_no ?? '', 'MANUAL'))
                                <span class="text-gray-400 text-xs italic">Portal Payment</span>
                            @elseif($payment->status === 'success')
                                <span class="text-green-600 text-xs italic">Confirmed</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="p-8 text-center text-gray-500">No payments found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t">
            {{ $payments->links() }}
        </div>
    </div>
</div>
@endsection
