<?php

namespace Mabrouk\Mediable\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Media extends Model
{
    use HasFactory;

    protected $table = 'media';

    protected $fillable = [
        'path',
        'type',         // photo, video or file
        'size',         // file size in kb
        'title',        // nullable
        'description',  // nullable
        'priority',     // default 0
        'is_main',
    ];

    protected $casts = [
        'is_main' => 'boolean',
    ];

    ## Relations

    ## Getters & Setters

    public function getStoragePathAttribute()
    {
        $fileNamePathParts = \explode('/', $this->path);
        $fileName = $fileNamePathParts[(\count($fileNamePathParts) - 1)];
        return "/{$this->mediable?->photosDirectory}/{$fileName}";
    }

    ## Query Scope Methods

    public function scopeMain($query, bool $main = true)
    {
        return $query->where('is_main', $main);
    }

    public function scopeOfType($query, string $type = '')
    {
        $availableTypes = [
            'photo',
            'file',
            'video',
        ];
        $type = \in_array(\strtolower($type), $availableTypes) ? \strtolower($type) : '';
        return $type == '' ? $query : $query->where('type', $type);
    }

    public function scopeWithTitle($query, string $title = '')
    {
        return $title == '' ? $query : $query->where('title', $title);
    }

    ## Other Methods

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return \Mabrouk\Mediable\Database\Factories\MediaFactory::new();
    }

    public function remove($removeFileWithoutObject = false)
    {
        Storage::delete($this->storagePath);
        if ($removeFileWithoutObject) return $this;
        $this->delete();
        return $this;
    }
}
