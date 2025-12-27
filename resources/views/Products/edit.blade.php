<x-app-layout>
    <x-slot name="header">
        <h2 class="font-['Cormorant_Garamond'] text-3xl font-light text-white text-shadow-lg shadow-white/10">
            {{ __('Edit Product') }}
        </h2>
    </x-slot>
    @if ($errors->any())
        <div class="mb-6 bg-red-500/20 border border-red-500 text-red-300 px-4 py-3 rounded-lg">
            <h4 class="font-bold">Validation Errors:</h4>
            <ul class="list-disc list-inside mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="mb-6">
        <a href="{{ route('products.index', ['category_id' => $product->category->id]) }}"
            class="inline-flex items-center text-blue-400 hover:text-blue-300 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to {{ $product->category->name }}
        </a>
    </div>

    <div class="min-h-screen bg-cover bg-center bg-no-repeat">
        <div class="py-4">
            <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white/10 backdrop-blur-md border border-white/10 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-8 text-white">
                        <!-- Upload Field -->
                        <x-image-upload-simplified :model="Product::class" :modelId="$product->id" type="main"
                            label="Upload New Image" description="Add a new image to this product" />

                        <!-- Existing Images Gallery -->
                        <x-image-gallery :images="$product->images" title="Product Images" :showSummary="true" :showEmptyState="true"
                            emptyMessage="No images for this product yet." />
                        <form action="{{ route('products.update', $product) }}" method="POST">

                            @csrf
                            @method('put')
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium mb-2">Product Name *</label>
                                <input type="text" name="name" id="name"
                                    value="{{ old('name', $product->name) }}" required
                                    class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-md text-white placeholder-gray-400">
                            </div>

                            <div class="mb-4">
                                <label for="slug" class="block text-sm font-medium mb-2">Slug</label>
                                <input type="text" name="slug" id="slug"
                                    value="{{ old('slug', $product->slug) }}" required
                                    class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-md text-white placeholder-gray-400">

                            </div>

                            <div class="mb-4">
                                <label for="price" class="block text-sm font-medium mb-2">Price </label>
                                <input type="number" step="0.1" name="price" id="price"
                                    placeholder="{{ $product->price_range }}" disabled
                                    class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-md text-white placeholder-gray-400">
                            </div>

                            <div class="mb-4">
                                <label for="status" class="block text-sm font-medium mb-2">Status </label>
                                <select name="status" id="status" required
                                    class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-md text-white">
                                    <option
                                        value="Published"{{ old('status', $product->status) == 'Published' ? 'selected' : '' }}>
                                        Published</option>
                                    <option
                                        value="Draft"{{ old('status', $product->status) == 'Draft' ? 'selected' : '' }}>
                                        Draft</option>
                                    <option
                                        value="Archived"{{ old('status', $product->status) == 'Archived' ? 'selected' : '' }}>
                                        Archived</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="description" class="block text-sm font-medium mb-2">Description</label>
                                <textarea name="description" id="description" rows="3"
                                    class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-md text-white placeholder-gray-400 whitespace-pre-wrap">{{ old('description', $product->description ?? '') }}</textarea>
                            </div>

                            <div class="mb-4">
                                <label for="category_id" class="block text-sm font-medium mb-2">Category *</label>
                                <select name="category_id" id="category_id" required
                                    class="w-full px-3 py-2 bg-white/5 border border-white/10 rounded-md text-white">
                                    <option value="">Select Category</option>
                                    @foreach ($grandchildcategory as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}

                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex space-x-3 mb-5 mt-5">
                                <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition">
                                    Save Product
                                </button>
                                <a href="{{ route('products.index', 'category_id=' . $product->category_id) }}"
                                    class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
