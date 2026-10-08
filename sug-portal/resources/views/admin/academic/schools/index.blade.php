@extends('admin.layout')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Manage Schools</h1>
        <div class="flex gap-2">
            <a href="{{ route('admin.academic.schools.template') }}" class="bg-gray-100 text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-200 transition-colors text-xs font-bold border border-gray-300 flex items-center gap-1">
                <i class="fas fa-download"></i> Download Template
            </a>
            <form action="{{ route('admin.academic.schools.import.store') }}" method="POST" enctype="multipart/form-data" class="flex gap-2">
                @csrf
                <input type="file" name="csv_file" class="text-xs p-1 border rounded-lg bg-white outline-none">
                <button type="submit" class="bg-gray-100 text-gray-700 px-3 py-2 rounded-lg hover:bg-gray-200 transition-colors text-xs font-bold border border-gray-300">
                    Import CSV
                </button>
            </form>
            <a href="{{ route('admin.academic.schools.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors flex items-center gap-2">
                <i class="fas fa-plus"></i> Add New School
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">
            {{ session('error') }}
        </div>
    @endif

<div class="bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr class="text-gray-600 text-sm font-semibold">
                <th class="px-6 py-4">School Name</th>
                <th class="px-6 py-4 text-right">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($schools as $school)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-medium text-gray-800">{{ $school->name }}</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end items-center gap-2">
                            <a href="{{ route('admin.academic.schools.edit', $school->id) }}" class="text-indigo-600 hover:text-indigo-900 p-2" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.academic.schools.destroy', $school->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 p-2" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="px-6 py-12 text-center text-gray-500 italic">No schools found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
</div>
@endsection
