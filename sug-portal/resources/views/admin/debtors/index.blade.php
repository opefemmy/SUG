@extends('admin.layout')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Debtors List</h1>
        <div class="text-sm text-gray-500 italic">
            Showing students with outstanding balances
        </div>
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
                        <td class="px-6 py-4 text-gray-600">₦{{ number_format($debtor->total_required, 2) }}</td>
                        <td class="px-6 py-4 text-green-600">₦{{ number_format($debtor->total_paid ?? 0, 2) }}</td>
                        <td class="px-6 py-4 text-right font-bold text-red-600">
                            ₦{{ number_format($debtor->outstanding_balance, 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">
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
