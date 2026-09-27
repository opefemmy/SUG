@extends('admin.layout')

@section('content')
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">SUG Administrations</h1>
            <a href="{{ route('administration.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                + New Term
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 text-gray-600 text-sm uppercase font-semibold">
                <tr>
                    <th class="px-6 py-3 border-b">Term Name</th>
                    <th class="px-6 py-3 border-b">Start Date</th>
                    <th class="px-6 py-3 border-b">End Date</th>
                    <th class="px-6 py-3 border-b">Status</th>
                    <th class="px-6 py-3 border-b text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($administrations as $admin)
                    <tr class="hover:bg-gray-50 transition {{ $admin->status === 'current' ? 'bg-blue-50' : '' }}">
                        <td class="px-6 py-4 font-medium">{{ $admin->term_name }}</td>
                        <td class="px-6 py-4">{{ $admin->start_date }}</td>
                        <td class="px-6 py-4">{{ $admin->end_date }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 {{ $admin->status === 'current' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }} rounded-full text-xs font-medium">
                                {{ ucfirst($admin->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <a href="{{ route('administration.officers', $admin->id) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">Officers</a>
                            @if($admin->status !== 'current')
                                <form action="{{ route('administration.set-active', $admin->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-800 font-medium text-sm">Set Active</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">No administrations found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
