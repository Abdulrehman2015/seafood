<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $table = 'media';

    protected $fillable = [
        'filename',
        'original_name',
        'path',
        'mime_type',
        'size',
        'width',
        'height',
        'folder',
        'alt_text',
    ];

    protected $appends = [
        'url',
        'size_formatted',
        'dimensions',
    ];

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }

    public function getSizeFormattedAttribute(): string
    {
        $bytes = $this->size;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 1) . ' KB';
        }
        return $bytes . ' B';
    }

    public function getDimensionsAttribute(): string
    {
        if ($this->width && $this->height) {
            return "{$this->width} × {$this->height} px";
        }
        return 'Unknown';
    }

    protected static function booted(): void
    {
        static::deleting(function (Media $media) {
            if ($media->path && Storage::disk('public')->exists($media->path)) {
                Storage::disk('public')->delete($media->path);
            }
        });
    }
}
