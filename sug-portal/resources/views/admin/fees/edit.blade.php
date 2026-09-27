@extends('admin.layout')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Edit Fee Structure</h1>
        <a href="{{ route('admin.fees.index') }}" class="text-blue-600 hover:underline">Back to List</a>
    </div>

    <div class="bg-white shadow-md rounded-lg p-8">
        <form action="{{ route('admin.fees.update', $fee->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fee Type</label>
                    <select name="fee_type_id" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" required>
                        <option value="">Select Fee Type</option>
                        @foreach($feeTypes as $type)
                            <option value="{{ $type->id }}" {{ $fee->fee_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Amount (₦)</label>
                    <input type="number" step="0.01" name="amount" value="{{ $fee->amount }}" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" placeholder="0.00" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Academic Level</label>
                    <select name="level_id" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" required>
                        <option value="">Select Level</option>
                        @foreach($levels as $level)
                            <option value="{{ $level->id }}" {{ $fee->level_id == $level->id ? 'selected' : '' }}>{{ $level->level_number }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Academic Session</label>
                    <select name="session_id" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" required>
                        <option value="">Select Session</option>
                        @foreach($sessions as $session)
                            <option value="{{ $session->id }}" {{ $fee->session_id == $session->id ? 'selected' : '' }}>{{ $session->session_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Programme (Optional - leave blank for all)</label>
                    <select name="programme_id" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="">All Programmes</option>
                        @foreach($programmes as $prog)
                            <option value="{{ $prog->id }}" {{ $fee->programme_id == $prog->id ? 'selected' : '' }}>{{ $prog->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center space-x-2">
                    <input type="checkbox" name="is_mandatory" value="1" {{ $fee->is_mandatory ? 'checked' : '' }} class="w-4 h-4 text-blue-600">
                    <label class="text-sm font-medium text-gray-700">Mandatory Payment</label>
                </div>
            </div>
            <div class="mt-8 flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700 transition">Update Fee Configuration</button>
            </div>
        </form>
    </div>
</div>
@endsection
