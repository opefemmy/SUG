@extends('admin.layout')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-4">
            <h1 class="text-2xl font-bold text-gray-800">Administration Officers</h1>
            <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full uppercase">
                {{ $administration->term_name }}
            </span>
        </div>
        <button type="button" onclick="document.getElementById('assign-modal').classList.remove('hidden')" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition font-medium">
            <i class="fas fa-plus mr-2"></i> Assign Officer
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr class="text-xs font-semibold text-gray-600 uppercase">
                    <th class="px-6 py-3">Officer</th>
                    <th class="px-6 py-3">Position</th>
                    <th class="px-6 py-3">Portfolio</th>
                    <th class="px-6 py-3 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($officers as $officer)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img src="{{ $officer->image_path ? asset('storage/' . $officer->image_path) : asset('images/default-avatar.png') }}" class="w-10 h-10 rounded-full object-cover border border-gray-200">
                                <span class="font-medium text-gray-900">{{ $officer->user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $officer->position }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 truncate max-w-xs">
                            {{ Str::limit($officer->portfolio, 50) }}
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button onclick="openEditModal({{ json_encode($officer) }})" class="text-blue-600 hover:text-blue-800 p-2">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('admin.administration.remove_officer', $officer->id) }}" method="POST" class="inline" onsubmit="return confirm('Remove this officer from administration?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 p-2">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr class="text-center">
                        <td colspan="4" class="px-6 py-10 text-gray-500 italic">
                            No officers assigned to this administration.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- ASSIGN MODAL -->
    <div id="assign-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">Assign New Officer</h3>
                <button onclick="document.getElementById('assign-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('admin.administration.assign_officer', $administration->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <div class="flex flex-col">
                    <label class="text-sm font-medium text-gray-600 mb-1">Select User</label>
                    <select name="user_id" required class="px-3 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select User</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex flex-col">
                    <label class="text-sm font-medium text-gray-600 mb-1">Position</label>
                    <input type="text" name="position" required class="px-3 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g. President">
                </div>
                <div class="flex flex-col">
                    <label class="text-sm font-medium text-gray-600 mb-1">Portfolio / Biography</label>
                    <textarea name="portfolio" rows="3" class="px-3 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500" placeholder="Brief description of duties..."></textarea>
                </div>
                <div class="flex flex-col">
                    <label class="text-sm font-medium text-gray-600 mb-1">Portrait Image</label>
                    <input type="file" name="image" class="text-sm text-gray-500 file:mr-4 file:py-1 file:px-4 file:rounded-full file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
                <div class="flex flex-col">
                    <label class="text-sm font-medium text-gray-600 mb-1">Appointment Date</label>
                    <input type="date" name="appointment_date" required class="px-3 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('assign-modal').classList.add('hidden')" class="px-4 py-2 text-gray-600 hover:text-gray-700 font-medium">Cancel</button>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-medium">Assign Officer</button>
                </div>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT MODAL -->
    <div id="edit-modal" class="hidden fixed inset-0 z-50 overflow-y-auto bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">Edit Officer Profile</h3>
                <button onclick="document.getElementById('edit-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('admin.administration.update_officer', ':id') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                @method('PUT')
                <div class="flex flex-col">
                    <label class="text-sm font-medium text-gray-600 mb-1">Position</label>
                    <input type="text" name="position" id="edit-position" required class="px-3 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex flex-col">
                    <label class="text-sm font-medium text-gray-600 mb-1">Portfolio / Biography</label>
                    <textarea name="portfolio" id="edit-portfolio" rows="3" class="px-3 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                <div class="flex flex-col">
                    <label class="text-sm font-medium text-gray-600 mb-1">Portrait Image</label>
                    <input type="file" name="image" class="text-sm text-gray-500 file:mr-4 file:py-1 file:px-4 file:rounded-full file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="document.getElementById('edit-modal').classList.add('hidden')" class="px-4 py-2 text-gray-600 hover:text-gray-700 font-medium">Cancel</button>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-medium">Update Profile</button>
                </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(officer) {
            document.getElementById('edit-modal').classList.remove('hidden');
            const form = document.querySelector('#edit-modal form');
            form.action = `/admin/administration/officers/${officer.id}/update`;
            document.getElementById('edit-position').value = officer.position;
            document.getElementById('edit-portfolio').value = officer.portfolio || '';
        }
    </script>
@endsection
