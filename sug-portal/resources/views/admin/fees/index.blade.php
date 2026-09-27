@extends('admin.layout')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Fee Configurations</h1>
        <a href="{{ route('admin.fees.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-blue-700 transition">
            + Add New Fee
        </a>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-100">
                <tr class="text-gray-700">
                    <th class="p-4 font-bold border-b">Fee Type</th>
                    <th class="p-4 font-bold border-b">Level</th>
                    <th class="p-4 font-bold border-b">Programme</th>
                    <th class="p-4 font-bold border-b">Session</th>
                    <th class="p-4 font-bold border-b">Amount</th>
                    <th class="p-4 font-bold border-b">Mandatory</th>
                    <th class="p-4 font-bold border-b text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($fees as $fee)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="p-4">{{ $fee->feeType->name }}</td>
                        <td class="p-4">{{ $fee->level->level_number ?? 'N/A' }}</td>
                        <td class="p-4">{{ $fee->programme->name ?? 'All' }}</td>
                        <td class="p-4">{{ $fee->session->session_name ?? 'N/A' }}</td>
                        <td class="p-4 font-bold">₦{{ number_format($fee->amount, 2) }}</td>
                        <td class="p-4">
                            <span class="px-2 py-1 rounded-full text-xs font-bold {{ $fee->is_mandatory ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $fee->is_mandatory ? 'Yes' : 'No' }}
                            </span>
                        </td>
                        <td class="p-4 text-right space-x-2">
                            <a href="{{ route('admin.fees.edit', $fee->id) }}" class="text-blue-600 hover:underline">Edit</a>
                            <form action="{{ route('admin.fees.destroy', $fee->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-gray-500">No fee structures configured.</td>
                    </tr>
                @endforelse
            </tbody>
            <div class="p-4 border-t">
                {{ $fees->links() }}
            </div>
        </table>
    </div>
</div>
@endsection
