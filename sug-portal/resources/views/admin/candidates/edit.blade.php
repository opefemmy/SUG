@extends('admin.layout')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Edit Candidate</h1>
        <a href="{{ route('admin.candidates.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-colors text-sm font-medium">
            Cancel
        </a>
    </div>

    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-8 max-w-4xl mx-auto">
        <form action="{{ route('admin.candidates.update', $candidate->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-gray-700 border-b pb-2">Election & Position</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Election</label>
                            <select name="election_id" class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none" required>
                                <option value="">Select Election</option>
                                @foreach($elections as $election)
                                    <option value="{{ $election->id }}" {{ $candidate->election_id == $election->id ? 'selected' : '' }}>
                                        {{ $election->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Position</label>
                            <select name="position_id" class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none" required>
                                <option value="">Select Position</option>
                                @foreach($positions as $position)
                                    <option value="{{ $position->id }}" {{ $candidate->position_id == $position->id ? 'selected' : '' }}>
                                        {{ $position->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Student Candidate</label>
                            <select name="student_id" class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none" required>
                                <option value="">Select Student</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ $candidate->student_id == $student->id ? 'selected' : '' }}>
                                        {{ $student->user->name }} ({{ $student->matric_no }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-gray-700 border-b pb-2">Candidate Details</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Manifesto</label>
                            <textarea name="manifesto" rows="4" class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">{{ $candidate->manifesto }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Biography</label>
                            <textarea name="biography" rows="4" class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Enter candidate's short bio...">{{ $candidate->biography }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Passport Photograph</label>
                            <input type="file" name="photo_path" class="w-full p-3 border rounded-xl bg-white" accept="image/*">
                            <div class="mt-2">
                                <img src="{{ $candidate->photo_path ? asset('storage/'.$candidate->photo_path) : asset('images/default-user.png') }}" class="h-16 w-16 rounded-full object-cover border">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Initial Approval Status</label>
                            <select name="approval_status" class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none">
                                <option value="pending" {{ $candidate->approval_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="approved" {{ $candidate->approval_status == 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ $candidate->approval_status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-10 flex justify-end">
                <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg transform hover:-translate-y-1">
                    Update Candidate
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
