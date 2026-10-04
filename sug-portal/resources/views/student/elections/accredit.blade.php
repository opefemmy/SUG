@extends('student.layout')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Voter Accreditation</h1>
        <p class="text-gray-600">You must be accredited to participate in the election. Please confirm your details to proceed.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8 max-w-2xl mx-auto">
        <div class="mb-8 space-y-4">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Your Verification Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                    <label class="block text-xs font-semibold text-gray-500 uppercase">Full Name</label>
                    <p class="text-lg font-bold text-gray-800">{{ Auth::user()->name }}</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                    <label class="block text-xs font-semibold text-gray-500 uppercase">Matric Number</label>
                    <p class="text-lg font-bold text-gray-800">{{ Auth::user()->student->matric_no }}</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                    <label class="block text-xs font-semibold text-gray-500 uppercase">Department</label>
                    <p class="text-lg font-bold text-gray-800">{{ Auth::user()->student->department->name }}</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                    <label class="block text-xs font-semibold text-gray-500 uppercase">Programme</label>
                    <p class="text-lg font-bold text-gray-800">{{ Auth::user()->student->programme->name }}</p>
                </div>
            </div>
        </div>

        <div class="text-center">
            <form action="{{ route('student.elections.accredit', ['election' => $election->id]) }}" method="POST">
                @csrf
                <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg transform hover:-translate-y-1 w-full md:w-auto">
                    Confirm & Accredit Me Now
                </button>
            </form>
            <p class="text-xs text-gray-400 mt-4">By clicking the button, you certify that you are the legitimate owner of this account and wish to participate in the voting exercise.</p>
        </div>
    </div>
</div>
@endsection