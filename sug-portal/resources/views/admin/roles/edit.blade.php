@extends('admin.layout')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.roles.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center text-sm font-medium mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Roles
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Edit Role</h1>
    </div>


    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 max-w-2xl">
        <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Role Name</label>
                <input type="text" name="name" value="{{ old('name', $role->name) }}" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none @error('name') border-red-500 @enderror" required>
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Permissions</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 bg-gray-50 p-4 rounded-lg border max-h-60 overflow-y-auto">
                    @foreach(\Spatie\Permission\Models\Permission::all() as $permission)
                        <label class="flex items-center space-x-3 p-2 hover:bg-gray-100 rounded cursor-pointer transition">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }} class="rounded text-blue-600 focus:ring-blue-500">
                            <span class="text-sm text-gray-600">{{ $permission->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.roles.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800 font-medium">Cancel</a>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-medium">Update Role</button>
            </div>
        </form>
    </div>
@endsection
