@extends('admin.layout')

@section('content')
    <div class="mb-6">
        <a href="{{ route('users.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center text-sm font-medium mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Back to Users
        </a>
        <h1 class="text-2xl font-bold text-gray-800">Add New User</h1>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 max-w-4xl">
        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none @error('name') border-red-500 @enderror" required>
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none @error('email') border-red-500 @enderror" required>
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none @error('password') border-red-500 @enderror" required>
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" required>
                </div>
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                        <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Roles</label>
                    <div class="grid grid-cols-2 gap-2 p-3 bg-gray-50 rounded-lg border border-gray-200">
                        @foreach(\Spatie\Permission\Models\Role::all() as $role)
                            <label class="flex items-center text-sm text-gray-600 cursor-pointer hover:text-gray-900">
                                <input type="checkbox" name="roles[]" value="{{ $role->name }}" class="rounded text-blue-600 mr-2" {{ is_array(old('roles')) && in_array($role->name, old('roles')) ? 'checked' : '' }}>
                                {{ $role->name }}
                            </label>
                        @endforeach
                    </div>
                    @error('roles') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Granular Permissions Section -->
                <div class="col-span-2 mt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 pb-2 border-b">Granular Permissions</h3>
                    <p class="text-sm text-gray-500 mb-4">Assign specific capabilities to this user. These will override or supplement the base role.</p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @php
                            $permissionGroups = [
                                'User Management' => ['manage users', 'view users', 'edit users', 'delete users'],
                                'Student Management' => ['view students', 'edit students', 'import students', 'promote students'],
                                'Academic Management' => ['manage schools', 'manage departments', 'manage programmes', 'manage sessions', 'manage levels'],
                                'Fee Management' => ['view fees', 'edit fees', 'view payments', 'mark unpaid'],
                                'CMS/Settings' => ['edit settings', 'manage news', 'manage events', 'manage pages'],
                                'Support/Complaints' => ['view complaints', 'verify complaints', 'manage support tickets'],
                                'System' => ['impersonate users', 'view system logs'],
                            ];
                        @endphp

                        @foreach($permissionGroups as $group => $permissions)
                            <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <h4 class="font-bold text-sm text-gray-700 mb-3">{{ $group }}</h4>
                                <div class="space-y-2">
                                    @foreach($permissions as $permission)
                                        <label class="flex items-center text-sm text-gray-600 cursor-pointer hover:text-gray-900">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission }}" class="rounded text-blue-600 mr-2" {{ is_array(old('permissions')) && in_array($permission, old('permissions')) ? 'checked' : '' }}>
                                            {{ ucwords(str_replace(' ', '_', $permission)) }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('users.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800 font-medium">Cancel</a>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-medium">Create User</button>
            </div>
        </form>
    </div>
@endsection
