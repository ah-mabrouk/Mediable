<?php

namespace Mabrouk\Mediable\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
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

    public function remove($removeFileWithoutObject = false)
    {
        Storage::delete($this->storagePath);
        if ($removeFileWithoutObject) return $this;
        $this->delete();
        return $this;
    }
}
