<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    protected $fillable = ['name', 'category', 'price', 'stock', 'image_path'];

    /**
     * URL gambar produk. Kembalikan URL placeholder jika tidak ada gambar.
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image_path && Storage::disk('public')->exists($this->image_path)) {
            return Storage::url($this->image_path);
        }

        // Placeholder berbasis nama produk (via DiceBear / UI Avatars - tidak perlu internet,
        // cukup gradient berdasarkan inisial)
        return '';
    }

    public function hasImage(): bool
    {
        return $this->image_path && Storage::disk('public')->exists($this->image_path);
    }
}
