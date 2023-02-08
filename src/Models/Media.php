<?php

namespace Mabrouk\Mediable\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $fillable = [
        'path',
        'type',         // photo, video or file
        'size',         // file size in kb
        'title',        // nullable
        'description',  // nullable
        'priority',     // default 0
        'is_main'
    ];

    protected $casts = [
        'is_main' => 'boolean'
    ];

    ## Relations

    public function mediable()
    {
        return $this->morphTo();
    }

    ## Getters & Setters

    protected function getPathAttribute($value)
    {
        return str_replace('public/', '/storage//', $value);
    }

    protected function getIsMainAttribute($value)
    {
        return (bool) $value;
    }

    protected function getVideoIdAttribute($value)
    {
        return getYoutubeVideoId($this->path);
    }

    public function getStoragePathAttribute()
    {
        $fileNamePathParts = \explode('/', $this->path);
        $fileName = $fileNamePathParts[(\count($fileNamePathParts) - 1)];
        return "/{$this->mediable?->photosDirectory}/{$fileName}";
    }

    ## Query Scope Methods

    public function scopeMain($query, $type = 'photo')
    {
        return $query->where('is_main', 1)->where('type', $type);
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
