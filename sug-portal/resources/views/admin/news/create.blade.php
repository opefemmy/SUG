@extends('admin.layout')
2
3	@section('content')
4	    <div class="mb-6">
5	        <h1 class="text-2xl font-bold text-gray-800">Create News Post</h1>
6	        <p class="text-gray-600">Share the latest campus updates and announcements.</p>
7	    </div>
8
9	    <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
10	        @csrf
11	        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
12	            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
13	                <div class="flex flex-col col-span-2">
14	                    <label class="text-sm font-medium text-gray-600 mb-1">News Title</label>
15	                    <input type="text" name="title" required class="px-3 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter a catchy headline...">
16	                </div>
17	                <div class="flex flex-col">
18	                    <label class="text-sm font-medium text-gray-600 mb-1">Category</label>
19	                    <select name="category_id" required class="px-3 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
20	                        <option value="">Select Category</option>
21	                        @foreach($categories as $category)
22	                            <option value="{{ $category->id }}">{{ $category->name }}</option>
23	                        @endforeach
24	                    </select>
25	                </div>
26	                <div class="flex flex-col">
27	                    <label class="text-sm font-medium text-gray-600 mb-1">Publication Status</label>
28	                    <select name="is_published" class="px-3 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
29	                        <option value="1">Published</option>
30	                        <option value="0">Draft</option>
31	                    </select>
32	                </div>
33	                <div class="flex flex-col col-span-2">
34	                    <label class="text-sm font-medium text-gray-600 mb-1">Content</label>
35	                    <textarea name="content" required rows="6" class="px-3 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500" placeholder="Write the full story here..."></textarea>
36	                </div>
37	            </div>
38	        </div>
39
40	        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
41	            <div class="p-6">
42	                <h3 class="text-lg font-semibold text-gray-700 mb-4">Images & Captions</h3>
43	                <p class="text-sm text-gray-500 mb-6">Upload one or more images. Each image can have its own caption.</p>
44
45	                <div id="image-upload-container" class="space-y-4">
46	                    <!-- Image rows will be added here via JS -->
47	                </div>
48
49	                <button type="button" onclick="addImageRow()" class="mt-6 bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition text-sm font-medium border border-gray-300">
48	                    <i class="fas fa-plus mr-2"></i> Add Another Image
49	                </button>
L50	            </div>
L51	        </div>
L52
L53	        <div class="flex justify-end gap-4">
L54	            <a href="{{ route('admin.news.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition font-medium">Cancel</a>
L55	            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-medium">Create News Post</button>
L56	        </div>
L57	    </form>
L58
L59	    <script>
L60	        let imageCount = 0;
L61	        function addImageRow() {
L62	            const container = document.getElementById('image-upload-container');
L63	            const row = document.createElement('div');
L64	            row.className = 'flex flex-col sm:flex-row gap-4 p-4 bg-gray-50 rounded-lg border border-gray-200 relative';
L65	            row.innerHTML = `
L66	                <div class="flex-1">
L67	                    <label class="text-xs font-semibold text-gray-500 uppercase mb-1 block">Image</label>
L68	                    <input type="file" name="images[]" required class="w-full text-sm text-gray-500 file:mr-4 file:py-1 file:px-4 file:rounded-full file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
L69	                </div>
L70	                <div class="flex-1">
L71	                    <label class="text-xs font-semibold text-gray-500 uppercase mb-1 block">Caption</label>
L72	                    <input type="text" name="captions[]" class="w-full px-3 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Image description...">
L73	                </div>
L74	                <div class="flex items-end">
L75	                    <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 p-2">
L76	                        <i class="fas fa-trash"></i>
L77	                    </button>
L78	                </div>
L79	            `;
L80	            container.appendChild(row);
L81	        }
L82
L83	        // Add first row by default
L84	        window.onload = () => { addImageRow(); };
L85	    </script>
L86	@endsection
