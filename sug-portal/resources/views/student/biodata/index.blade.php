@extends('student.layout')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Complete Your Biodata</h1>
        <a href="{{ route('student.dashboard') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors text-sm font-medium">
            Back to Dashboard
        </a>
    </div>

    <div class="bg-white shadow-sm rounded-2xl border border-gray-100 p-8 max-w-4xl mx-auto">
        <div class="mb-8 p-4 bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700 rounded-r-lg">
            <p class="font-bold">Important:</p>
            <p class="text-sm">Please fill in all your details correctly. This information is used for your official student record.</p>
        </div>

        <form action="{{ route('student.biodata.store') }}" method="POST" enctype="multipart/form-data" x-data="biodataForm()">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Personal Info -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-gray-700 border-b pb-2">Personal Information</h3>
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">First Name</label>
                                <input type="text" name="first_name" value="{{ old('first_name', $biodata->first_name ?? '') }}" class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Last Name</label>
                                <input type="text" name="last_name" value="{{ old('last_name', $biodata->last_name ?? '') }}" class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all" required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Middle Name</label>
                            <input type="text" name="middle_name" value="{{ old('middle_name', $biodata->middle_name ?? '') }}" class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', $biodata->email ?? '') }}" class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Phone Number</label>
                            <input type="text" name="phone_number" value="{{ old('phone_number', $biodata->phone_number ?? '') }}" class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">House Address</label>
                            <input type="text" name="house_address" value="{{ old('house_address', $biodata->house_address ?? '') }}" class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all" required>
                        </div>
                    </div>
                </div>

                <!-- Academic & Parent Info -->
                <div class="space-y-6">
                    <h3 class="text-lg font-bold text-gray-700 border-b pb-2">Academic & Parent Info</h3>
                    <div class="space-y-4">
                        <!-- Academic Selection -->
                        <div class="p-4 bg-indigo-50 rounded-xl border border-indigo-100 space-y-4">
                            <div class="flex items-center gap-2 text-indigo-700 font-bold text-sm mb-2">
                                <i class="fas fa-graduation-cap"></i> Academic Affiliation
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-indigo-600 mb-1">School</label>
                                <select name="school_id" x-model="schoolId" @change="fetchDepartments()" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-sm" required>
                                    <option value="">Select School</option>
                                    @foreach($schools as $school)
                                        <option value="{{ $school->id }}" {{ $student->school_id == $school->id ? 'selected' : '' }}>{{ $school->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-indigo-600 mb-1">Department</label>
                                <select name="department_id" x-model="departmentId" @change="fetchProgrammes()" :disabled="!schoolId" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-sm" required>
                                    <option value="">Select Department</option>
                                    <template x-for="dept in departments" :key="dept.id">
                                        <option :value="dept.id" x-text="dept.name"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-indigo-600 mb-1">Programme</label>
                                <select name="programme_id" x-model="programmeId" :disabled="!departmentId" class="w-full p-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none text-sm" required>
                                    <option value="">Select Programme</option>
                                    <template x-for="prog in programmes" :key="prog.id">
                                        <option :value="prog.id" x-text="prog.name"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <div class="border-t pt-4 space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Parent's Full Name</label>
                                <input type="text" name="parent_name" value="{{ old('parent_name', $biodata->parent_name ?? '') }}" class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all" required>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">Parent's Phone</label>
                                    <input type="text" name="parent_phone" value="{{ old('parent_phone', $biodata->parent_phone ?? '') }}" class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">Parent's Email</label>
                                    <input type="email" name="parent_email" value="{{ old('parent_email', $biodata->parent_email ?? '') }}" class="w-full p-3 border rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none transition-all" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Passport -->
                <div class="md:col-span-2 p-6 bg-gray-50 rounded-2xl border border-gray-200">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Passport Photograph</label>
                    <input type="file" name="passport" class="w-full p-3 border rounded-xl bg-white" accept="image/*">
                    <p class="text-xs text-gray-500 mt-2">Upload a professional passport photo (JPG, PNG, max 2MB)</p>
                </div>
            </div>

            <div class="mt-10 flex justify-end">
                <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded-xl font-bold hover:bg-indigo-700 transition shadow-lg transform hover:-translate-y-1">
                    Complete Biodata & Continue
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function biodataForm() {
    return {
        schoolId: '{{ $student->school_id ?? '' }}',
        departmentId: '{{ $student->department_id ?? '' }}',
        programmeId: '{{ $student->programme_id ?? '' }}',
        departments: [],
        programmes: [],
        async init() {
            if (this.schoolId) {
                await this.fetchDepartments();
                if (this.departmentId) {
                    await this.fetchProgrammes();
                }
            }
        },
        async fetchDepartments() {
            if (!this.schoolId) {
                this.departments = [];
                this.departmentId = '';
                this.programmeId = '';
                this.programmes = [];
                return;
            }
            try {
                const response = await fetch(`/student/api/departments?school_id=${this.schoolId}`);
                this.departments = await response.json();
                // If the current department is not in the new list, reset it
                if (this.departmentId && !this.departments.find(d => d.id == this.departmentId)) {
                    this.departmentId = '';
                    this.programmeId = '';
                    this.programmes = [];
                }
            } catch (e) {
                console.error('Error fetching departments:', e);
            }
        },
        async fetchProgrammes() {
            if (!this.departmentId) {
                this.programmes = [];
                return;
            }
            try {
                const response = await fetch(`/student/api/programmes?department_id=${this.departmentId}`);
                this.programmes = await response.json();
                // If the current programme is not in the new list, reset it
                if (this.programmeId && !this.programmes.find(p => p.id == this.programmeId)) {
                    this.programmeId = '';
                }
            } catch (e) {
                console.error('Error fetching programmes:', e);
            }
        }
    }
}
</script>
@endsection
