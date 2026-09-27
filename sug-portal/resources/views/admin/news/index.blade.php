@extends('admin.layout')
2
3	@section('content')
4	    <div class="flex justify-between items-center mb-6">
5	        <h1 class="text-2xl font-bold text-gray-800">Campus News</h1>
6	        <a href="{{ route('admin.news.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition font-medium">
7	            <i class="fas fa-plus mr-2"></i> Add News
8	        </a>
9	    </div>
10
11	    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
12	        <table class="w-full text-left border-collapse">
13	            <thead class="bg-gray-50 border-b border-gray-200">
14	                <tr>
15	                    <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase">Title</th>
16	                    <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase">Category</th>
17	                    <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase">Status</th>
18	                    <th class="px-6 py-3 text-xs font-semibold text-gray-600 uppercase text-right">Actions</th>
19	                </tr>
20	            </thead>
21	            <tbody class="divide-y divide-gray-200">
22	                @forelse($news as $item)
23	                    <tr class="hover:bg-gray-50 transition">
24	                        <td class="px-6 py-4">
25	                            <div class="font-medium text-gray-900">{{ $item->title }}</div>
26	                            <div class="text-xs text-gray-400">{{ $item->created_at->format('M d, Y') }}</div>
27	                        </td>
28	                        <td class="px-6 py-4">
29	                            <span class="px-2 py-1 bg-blue-100 text-blue-600 text-xs font-bold rounded-full uppercase">
S30	                                {{ $item->category->name ?? 'General' }}
31	                            </span>
32	                        </td>
33	                        <td class="px-6 py-4">
34	                            @if($item->is_published)
35	                                <span class="text-green-600 text-sm font-medium"><i class="fas fa-check-circle mr-1"></i> Published</span>
36	                            @else
37	                                <span class="text-gray-400 text-sm font-medium"><i class="fas fa-clock mr-1"></i> Draft</span>
38	                            @endif
39	                        </td>
40	                        <td class="px-6 py-4 text-right space-x-2">
41	                            <a href="{{ route('admin.news.edit', $item->id) }}" class="text-blue-600 hover:text-blue-800 p-2">
42	                                <i class="fas fa-edit"></i>
43	                            </a>
44	                            <form action="{{ route('admin.news.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this news post?')">
45	                                @csrf
46	                                @method('DELETE')
47	                                <button type="submit" class="text-red-600 hover:text-red-800 p-2">
48	                                    <i class="fas fa-trash"></i>
49	                                </button>
L50	                            </form>
51	                        </td>
52	                    </tr>
53	                @empty
54	                    <tr>
55	                        <td colspan="4" class="px-6 py-10 text-center text-gray-500 italic">
56	                            No news posts found.
57	                        </td>
58	                    </tr>
59	                @endforelse
60	            </tbody>
61	        </table>
62	    </div>
63
64	    <div class="mt-4">
65	        {{ $news->links() }}
66	    </div>
67	@endsection
