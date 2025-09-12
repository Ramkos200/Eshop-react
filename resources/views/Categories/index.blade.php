<x-app-layout>
    <x-slot name="header">
        <h2 class="font-['Cormorant_Garamond'] text-3xl font-light text-white text-shadow-lg shadow-white/10">
            {{ __('Categories') }} <span class="text-lg ">{{ $categories->where('parent_id', null)->count() }}
                main categories</span>
        </h2>
    </x-slot>

    <div class="min-h-screen bg-cover bg-center bg-no-repeat">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white/10 backdrop-blur-md border border-white/10 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-8 text-white">
                        <x-link-button href="{{ route('categories.create') }}"> + New Category</x-link-button>
                        <x-link-button href="{{ route('products.index') }}"> show all Products</x-link-button>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8 ">
                            @foreach ($categories->where('parent_id', null) as $category)
                                <div class="bg-white/5 backdrop-blur-sm border border-white/5 rounded-lg p-6 relative ">
                                    <!-- Edit and Delete Icons -->
                                    <div class="flex justify-end mr-2">
                                        <!-- Edit Icon -->
                                        <a href="{{ route('categories.edit', $category->id) }}"
                                            class="text-blue-400 hover:text-blue-300 transition-colors mr-2"
                                            title="Edit Category">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        <!-- Delete Icon with Form -->
                                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-400 hover:text-red-300 transition-colors"
                                                onclick="return confirm('Are you sure you want to delete this category?')"
                                                title="Delete Category">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                    <a href="{{ route('categories.show', $category->slug) }}"
                                        class="text-xl font-semibold hover:underline block mb-3">
                                        {{ $category->name }}
                                    </a>
                                    <div class="text-sm text-gray-400 mb-3">
                                        Subcategories :
                                        {{ $category->children->count() }}
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 ">
                                        @foreach ($category->children as $subcategory)
                                            <a href="{{ route('products.index', ['category_id' => $subcategory->id]) }}"
                                                class="mb-5 hover:underline">
                                                {{ $subcategory->name }} ({{ $subcategory->products->count() }})
                                            </a>
                                        @endforeach
                                    </div>

                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
