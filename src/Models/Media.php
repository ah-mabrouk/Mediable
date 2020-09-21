<?php

namespace Mabrouk\Mediable\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    protected $fillable = [
        'path',
        'type',         // photo, video or file
        'title',        // nullable
        'description',  // nullable
        'priority', // default 0
        'is_main'
    ];

    protected $casts = [
        'is_main' => 'boolean'
    ];

    public function mediable()
    {
        return $this->morphTo();
    }

    public function scopeMain($query, $type)
    {
        return $query->where('is_main', 1)->where('type', $type);
    }

    protected function getPathAttribute($value)
    {
        return str_replace('public', '/storage', $value);
    }

    protected function getIsMainAttribute($value)
    {
        return (bool) $value;
    }

    protected function getVideoIdAttribute($value)
    {
        return getYoutubeVideoId($this->path);
    }

    public function deleteMedia()
    {
        $this->deleteOldMediaPath()->delete();
        return $this;
    }

    private function deleteOldMediaPath()
    {
        Storage::delete($this->path);
        return $this;
    }
}
