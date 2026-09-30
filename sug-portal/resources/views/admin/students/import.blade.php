@extends('admin.layout')

@section('content')
<div class="container mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Bulk Student Import</h1>
        <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:underline">Back to Dashboard</a>
    </div>

    <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-200">
        <div class="mb-8 p-4 bg-blue-50 rounded-lg border border-blue-100">
            <h3 class="text-blue-800 font-bold mb-2">CSV Import Requirements</h3>
            <p class="text-sm text-blue-600 mb-4">Please upload a CSV file with the following exact headers (case-sensitive):</p>
            <div class="flex items-center justify-between bg-white p-3 rounded border border-blue-200">
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4 text-xs font-mono">
                    <div class="p-1 bg-gray-50 rounded border">matric_no</div>
                    <div class="p-1 bg-gray-50 rounded border">last_name</div>
                    <div class="p-1 bg-gray-50 rounded border">first_name</div>
                    <div class="p-1 bg-gray-50 rounded border">middle_name</div>
                    <div class="p-1 bg-gray-50 rounded border">department</div>
                    <div class="p-1 bg-gray-50 rounded border">level</div>
                </div>
                <a href="{{ asset('templates/student_import_template.csv') }}" class="ml-4 text-xs bg-blue-600 text-white px-3 py-1 rounded hover:bg-blue-700 transition font-bold">
                    Download Template
                </a>
            </div>
            <p class="text-xs text-blue-500 mt-2 italic">Note: Matric number will be used as username and Surname as temporary password.</p>
        </div>

        <form action="{{ route('admin.students.import.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="flex flex-col items-center justify-center p-12 border-2 border-dashed border-gray-300 rounded-xl hover:border-blue-500 transition-colors cursor-pointer relative">
                <input type="file" name="csv_file" id="csv_file" class="absolute inset-0 opacity-0 cursor-pointer" onchange="updateFileName()">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.//8 0 4 4 0 01 8 0m-7 7H7m0-7l7-7 7 7M12 3v12" />
                </svg>
                <p id="upload-text" class="text-lg font-medium text-gray-700">Click or Drag CSV file here to upload</p>
                <p class="text-sm text-gray-500 mt-1">Only .csv or .txt files are accepted</p>
            </div>
            <div class="mt-6 flex justify-center">
                <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg font-bold hover:bg-blue-700 transition shadow-md">
                    Upload Students
                </button>
            </div>
        </form>
    </div>

    @if(session('error'))
        <div class="mb-6 p-4 rounded-lg bg-red-100 border border-red-400 text-red-700">
            {{ session('error') }}
        </div>
    @endif

    @if(session('import_results'))
        <div class="mt-8 p-6 bg-white rounded-xl shadow-sm border border-gray-200">
            <h3 class="text-xl font-bold mb-4">Import Results</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="p-4 bg-green-50 text-green-700 rounded-lg border border-green-200">
                    <span class="text-2xl font-bold">{{ session('import_results')['success'] }}</span> Successful
                </div>
                <div class="p-4 bg-red-50 text-red-700 rounded-lg border border-red-200">
                    <span class="text-2xl font-bold">{{ session('import_results')['failed'] }}</span> Failed
                </div>
            </div>
            @if(!empty(session('import_results')['errors']))
                <div class="space-y-2">
                    <p class="font-semibold text-gray-700">Errors:</p>
                    <div class="max-h-60 overflow-y-auto bg-gray-50 p-4 rounded border border-gray-200 text-sm text-red-600 font-mono">
                        @foreach(session('import_results')['errors'] as $error)
                            <div class="py-1 border-b border-gray-200 last:border-0">{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif
</div>

<script>
    function updateFileName() {
        const input = document.getElementById('csv_file');
        const text = document.getElementById('upload-text');
        if (input.files && input.files.length > 0) {
            text.innerText = 'Selected: ' + input.files[0].name;
            text.classList.remove('text-gray-700');
            text.classList.add('text-blue-600', 'font-bold');
        } else {
            text.innerText = 'Click or Drag CSV file here to upload';
            text.classList.remove('text-blue-600', 'font-bold');
            text.classList.add('text-gray-700');
        }
    }
</script>
@endsection
