<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('products.index') }}" class="w-8 h-8 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-500 transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <div>
                <h1 class="page-heading">Edit Barang</h1>
                <p class="text-xs text-gray-500 mt-0.5">{{ $product->name }}</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="card">
            <form method="POST" action="{{ route('products.update', $product) }}"
                  enctype="multipart/form-data" class="space-y-5">
                @method('PUT')
                @include('products._form')
            </form>
        </div>
    </div>
</x-app-layout>
