<x-app-layout :backgroundImage="asset('images/' . $category->slug . '.jpg')">
    <x-slot name="header">
        <h2 class="font-['Cormorant_Garamond'] text-3xl font-light text-white text-shadow-lg shadow-white/10">
            {{ __($category->name) }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-cover bg-center bg-no-repeat">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white/10 backdrop-blur-md border border-white/10 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-8 text-white">
                        <div class="grid grid-cols-1  gap-6 mt-8 ">
                            <div class="bg-white/5 backdrop-blur-sm border border-white/5 rounded-lg p-6 ">
                                <div class="grid grid-cols-1 md:grid-cols-3 ">
                                    @foreach ($categories->where('parent_id', $category->id) as $subcategory)
                                        <h4 class="mb-5">
                                            {{ $subcategory->name }}
                                        </h4>
                                    @endforeach
                                </div>

                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
