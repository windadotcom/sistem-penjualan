@csrf

{{-- Nama --}}
<div>
    <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">
        Nama Barang <span class="text-red-500">*</span>
    </label>
    <input id="name" name="name" type="text"
           value="{{ old('name', $product->name ?? '') }}"
           required autofocus placeholder="Contoh: Beras Premium 5kg"
           class="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 focus:outline-none transition">
    <x-input-error :messages="$errors->get('name')" class="mt-1.5" />
</div>

{{-- Kategori --}}
<div>
    <label for="category" class="block text-sm font-semibold text-gray-700 mb-1.5">Kategori</label>
    <input id="category" name="category" type="text"
           value="{{ old('category', $product->category ?? '') }}"
           placeholder="Contoh: Sembako, Minuman, Snack..."
           class="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 focus:outline-none transition">
    <x-input-error :messages="$errors->get('category')" class="mt-1.5" />
</div>

{{-- Harga & Stok --}}
<div class="grid grid-cols-2 gap-4">
    <div>
        <label for="price" class="block text-sm font-semibold text-gray-700 mb-1.5">
            Harga Jual (Rp) <span class="text-red-500">*</span>
        </label>
        <div class="relative">
            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-gray-400 font-medium">Rp</span>
            <input id="price" name="price" type="number" min="0"
                   value="{{ old('price', $product->price ?? '') }}"
                   required placeholder="0"
                   class="w-full pl-10 pr-3.5 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 focus:outline-none transition">
        </div>
        <x-input-error :messages="$errors->get('price')" class="mt-1.5" />
    </div>
    <div>
        <label for="stock" class="block text-sm font-semibold text-gray-700 mb-1.5">
            Stok <span class="text-red-500">*</span>
        </label>
        <div class="relative">
            <input id="stock" name="stock" type="number" min="0"
                   value="{{ old('stock', $product->stock ?? 0) }}"
                   required placeholder="0"
                   class="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 focus:outline-none transition">
            <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-xs text-gray-400">unit</span>
        </div>
        <x-input-error :messages="$errors->get('stock')" class="mt-1.5" />
    </div>
</div>

{{-- Upload Gambar --}}
<div x-data="imageUpload()" class="space-y-3">
    <label class="block text-sm font-semibold text-gray-700">
        Foto Produk
        <span class="text-xs text-gray-400 font-normal ml-1">(JPG/PNG/WebP, maks. 2MB)</span>
    </label>

    {{-- Gambar saat ini (edit mode) --}}
    @if (!empty($product->image_path) && $product->hasImage())
        <div class="flex items-center gap-4 p-3 bg-gray-50 rounded-xl border border-gray-200" id="current-image-box">
            <img src="{{ $product->image_url }}"
                 alt="{{ $product->name }}"
                 class="w-20 h-20 object-cover rounded-lg border border-gray-200">
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-700">Gambar saat ini</p>
                <p class="text-xs text-gray-400 mt-0.5">Upload baru untuk mengganti</p>
                <label class="flex items-center gap-2 mt-2 cursor-pointer">
                    <input type="checkbox" name="remove_image" value="1"
                           class="rounded border-gray-300 text-red-500 focus:ring-red-400"
                           x-model="removeImage">
                    <span class="text-xs text-red-600 font-semibold">Hapus gambar ini</span>
                </label>
            </div>
        </div>
    @endif

    {{-- Drop zone --}}
    <div
        x-show="!preview"
        @dragover.prevent="dragging = true"
        @dragleave.prevent="dragging = false"
        @drop.prevent="handleDrop($event)"
        :class="dragging ? 'border-emerald-400 bg-emerald-50' : 'border-gray-300 bg-gray-50 hover:bg-emerald-50 hover:border-emerald-300'"
        class="relative border-2 border-dashed rounded-xl p-6 text-center transition-all cursor-pointer"
        @click="$refs.fileInput.click()">

        <div class="flex flex-col items-center gap-2 pointer-events-none">
            <div class="w-12 h-12 rounded-xl bg-white border border-gray-200 flex items-center justify-center shadow-sm">
                <svg class="w-6 h-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <p class="text-sm font-semibold text-gray-600">Klik atau drag & drop gambar</p>
            <p class="text-xs text-gray-400">JPG, PNG, WebP — maksimal 2MB</p>
        </div>

        <input
            x-ref="fileInput"
            type="file" name="image"
            accept="image/jpeg,image/png,image/webp"
            class="hidden"
            @change="handleFile($event)">
    </div>

    {{-- Preview gambar baru --}}
    <div x-show="preview" class="relative">
        <img :src="preview" alt="Preview"
             class="w-full max-h-52 object-contain rounded-xl border border-gray-200 bg-gray-50">
        <button type="button" @click="clearPreview()"
                class="absolute top-2 right-2 w-7 h-7 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center shadow transition">
            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        <p class="text-xs text-gray-500 mt-1.5 text-center" x-text="fileName"></p>
    </div>

    <x-input-error :messages="$errors->get('image')" class="mt-1.5" />
</div>

{{-- Tombol --}}
<div class="flex items-center gap-3 pt-2 border-t border-gray-100">
    <button type="submit" class="btn btn-primary">
        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
        Simpan Barang
    </button>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
</div>

<script>
function imageUpload() {
    return {
        preview: null,
        fileName: '',
        dragging: false,
        removeImage: false,

        handleFile(event) {
            const file = event.target.files[0];
            if (file) this.loadPreview(file);
        },

        handleDrop(event) {
            this.dragging = false;
            const file = event.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) {
                // Inject ke input file supaya ikut submit
                const dt = new DataTransfer();
                dt.items.add(file);
                this.$refs.fileInput.files = dt.files;
                this.loadPreview(file);
            }
        },

        loadPreview(file) {
            this.fileName = file.name;
            const reader = new FileReader();
            reader.onload = (e) => { this.preview = e.target.result; };
            reader.readAsDataURL(file);
        },

        clearPreview() {
            this.preview = null;
            this.fileName = '';
            this.$refs.fileInput.value = '';
        }
    };
}
</script>
