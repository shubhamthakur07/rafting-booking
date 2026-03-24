<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image_path',
        'category',
        'video_url',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected $appends = [
        'image_url',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function scopePhotos($query)
    {
        return $query->where('category', 'photos')->orWhereNull('category');
    }

    public function scopeVideos($query)
    {
        return $query->where('category', 'videos');
    }

    public function isVideo(): bool
    {
        return $this->category === 'videos' && !empty($this->video_url);
    }

    public function getImageUrlAttribute(): string
    {
        if (empty($this->image_path)) {
            return '';
        }

        // If it's already a full URL, return it as-is
        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }

        // Otherwise, use our custom route for local files
        return route('gallery.image', ['filename' => $this->image_path]);
    }
}
