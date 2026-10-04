@extends('admin.layout')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Debtors List</h1>
        <div class="text-sm text-gray-500 italic">
            Showing students with outstanding balances
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-6">
        <form action="{{ route('admin.debtors.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">School</label>
                <select name="school_id" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                    <option value="">All Schools</option>
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}" {{ request('school_id') == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-1">Programme</label>
                <select name="programme_id" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none text-sm">
                    <option value="">All Programmes</option>
                    @foreach($programmes as $programme)
                        <option value="{{ $programme->id }}" {{ request('programme_id') == $programme->id ? 'selected' : '' }}>{{ $programme->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                    Filter
                </button>
                <a href="{{ route('admin.debtors.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition text-sm font-medium">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="flex justify-end mb-4">
        <form action="{{ route('admin.debtors.export') }}" method="GET" class="flex gap-2">
            <input type="hidden" name="school_id" value="{{ request('school_id') }}">
            <input type="hidden" name="programme_id" value="{{ request('programme_id') }}">
            <button type="submit" class="flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition text-sm font-medium shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export to CSV (Excel)
            </button>
        </form>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 border-b border-gray-200 bg-gray-50">
            <div class="flex justify-between items-center">
                <h3 class="text-sm font-semibold text-gray-600">Financial Overview</h3>
                <span class="text-xs text-gray-400">Last updated: {{ now()->format('d M, Y H:i') }}</span>
            </div>
        </div>

        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 text-gray-600 text-sm uppercase font-semibold">
                <tr>
                    <th class="px-6 py-3 border-b">Student Name</th>
                    <th class="px-6 py-3 border-b">Matric No</th>
                    <th class="px-6 py-3 border-b">Department</th>
                    <th class="px-6 py-3 border-b">Programme</th>
                    <th class="px-6 py-3 border-b">Total Required</th>
                    <th class="px-6 py-3 border-b">Total Paid</th>
                    <th class="px-6 py-3 border-b text-right">Outstanding Balance</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($debtors as $debtor)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-medium text-gray-800">{{ $debtor->name }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $debtor->matric_no }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $debtor->department_name }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $debtor->programme_name }}</td>
                        <td class="px-6 py-4 text-gray-600">₦{{ number_format($debtor->total_required, 2) }}</td>
                        <td class="px-6 py-4 text-green-600">₦{{ number_format($debtor->total_paid ?? 0, 2) }}</td>
                        <td class="px-6 py-4 text-right font-bold text-red-600">
                            ₦{{ number_format($debtor->outstanding_balance, 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-500">
                            No debtors found. All students are cleared!
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t border-gray-200">
            {{ $debtors->links() }}
        </div>
    </div>
@endsection
