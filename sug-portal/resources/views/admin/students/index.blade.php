@extends('admin.layout')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Student Management</h1>
        <a href="{{ route('admin.students.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
            + Enroll New Student
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-800">Enrolled Students</h3>
            <a href="{{ route('admin.students.import.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold">
                Bulk Import
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50">
                    <tr class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="px-6 py-4">Student</th>
                        <th class="px-6 py-4">Matric No</th>
                        <th class="px-6 py-4">Programme</th>
                        <th class="px-6 py-4">Level</th>
                        <th class="px-6 py-4">Session</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($students as $student)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900">{{ $student->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $student->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm font-mono text-gray-600">
                                {{ $student->matric_no }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $student->programme->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-indigo-100 text-indigo-700">
                                    {{ $student->level->level_number ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">
                                {{ $student->session->session_name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('admin.students.show', $student->id) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-semibold">View</a>
                                <a href="{{ route('admin.students.edit', $student->id) }}" class="text-blue-600 hover:text-blue-900 text-sm font-semibold">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                No students found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $students->links() }}
        </div>
    </div>
</div>
@endsection
