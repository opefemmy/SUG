@extends('admin.layout')
2
3	@section('content')
4	    <div class="mb-6">
5	        <h1 class="text-2xl font-bold text-gray-800">Edit News Post</h1>
6	        <p class="text-gray-600">Update the details of your news post.</p>
7	    </div>
8
9	    <form action="{{ route('admin.news.update', $news->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
10	        @csrf
11	        @method('PUT')
12	        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
13	            <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
14	                <div class="flex flex-col col-span-2">
15	                    <label class="text-sm font-medium text-gray-600 mb-1">News Title</label>
16	                    <input type="text" name="title" value="{{ $news->title }}" required class="px-3 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
17	                </div>
18	                <div class="flex flex-col">
19	                    <label class="text-sm font-medium text-gray-600 mb-1">Category</label>
20	                    <select name="category_id" required class="px-3 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
21	                        @foreach($categories as $category)
22	                            <option value="{{ $category->id }}" {{ $news->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
23	                        @endforeach
24	                    </select>
25	                </div>
26	                <div class="flex flex-col">
27	                    <label class="text-sm font-medium text-gray-600 mb-1">Publication Status</label>
28	                    <select name="is_published" class="px-3 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">
29	                        <option value="1" {{ $news->is_published ? 'selected' : '' }}>Published</option>
30	                        <option value="0" {{ !$news->is_published ? 'selected' : '' }}>Draft</option>
31	                    </select>
32	                </div>
33	                <div class="flex flex-col col-span-2">
34	                    <label class="text-sm font-medium text-gray-600 mb-1">Content</label>
35	                    <textarea name="content" required rows="6" class="px-3 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500">{{ $news->content }}</textarea>
36	                </div>
37	            </div>
38	        </div>
39
40	        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
41	            <div class="p-6">
42	                <h3 class="text-lg font-semibold text-gray-700 mb-4">Images & Captions</h3>
43	                <p class="text-sm text-gray-500 mb-6">Manage existing images and add new ones.</p>
44
45	                <div id="image-upload-container" class="space-y-4">
46	                    @foreach($news->images as $image)
47	                        <div class="flex flex-col sm:flex-row gap-4 p-4 bg-gray-50 rounded-lg border border-gray-200 relative" data-image-id="{{ $image->id }}">
48	                            <div class="flex-1">
49	                                <label class="text-xs font-semibold text-gray-500 uppercase mb-1 block">Existing Image</label>
50	                                <div class="flex items-center gap-4">
51	                                    <img src="{{ asset('storage/' . $image->image_path) }}" class="h-12 w-12 rounded object-cover border border-gray-300">
52	                                    <span class="text-xs text-gray-400 truncate">{{ $image->image_path }}</span>
53	                                </div>
54	                            </div>
L55	                            <div class="flex-1">
L56	                                <label class="text-xs font-semibold text-gray-500 uppercase mb-1 block">Caption</label>
L57	                                <input type="text" name="existing_captions[{{ $image->id }}]" value="{{ $image->caption }}" class="w-full px-3 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500 text-sm">
L58	                            </div>
L59	                            <div class="flex items-end">
L60	                                <input type="checkbox" name="remove_images[]" value="{{ $image->id }}" class="w-4 h-4 text-blue-600 border-gray-300 rounded">
L61	                                <label class="ml-2 text-xs text-red-500 font-medium cursor-pointer">Remove</label>
L62	                            </div>
L63	                        </div>
L64	                    @endforeach
L65	                </div>
L66
L67	                <button type="button" onclick="addImageRow()" class="mt-6 bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition text-sm font-medium border border-gray-300">
L68	                    <i class="fas fa-plus mr-2"></i> Add Another Image
L69	                </button>
L70	            </div>
L71	        </div>
L72
L73	        <div class="flex justify-end gap-4">
L74	            <a href="{{ route('admin.news.index') }}" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition font-medium">Cancel</a>
L75	            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-medium">Update News Post</button>
L76	        </div>
L77	    </form>
L78
L79	    <script>
L80	        function addImageRow() {
L81	            const container = document.getElementById('image-upload-container');
L82	            const row = document.createElement('div');
L83	            row.className = 'flex flex-col sm:flex-row gap-4 p-4 bg-gray-50 rounded-lg border border-gray-200 relative';
L84	            row.innerHTML = `
L85	                <div class="flex-1">
L86	                    <label class="text-xs font-semibold text-gray-500 uppercase mb-1 block">New Image</label>
L87	                    <input type="file" name="images[]" required class="w-full text-sm text-gray-500 file:mr-4 file:py-1 file:px-4 file:rounded-full file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
L88	                </div>
L89	                <div class="flex-1">
L90	                    <label class="text-xs font-semibold text-gray-500 uppercase mb-1 block">Caption</label>
L91	                    <input type="text" name="captions[]" class="w-full px-3 py-2 border rounded-lg outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Image description...">
L92	                </div>
L93	                <div class="flex items-end">
L94	                    <button type="button" onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 p-2">
L95	                        <i class="fas fa-trash"></i>
L96	                    </button>
L97	                </div>
L98	            `;
L99	            container.appendChild(row);
L100	        }
L101	    </script>
L102	@endsection
