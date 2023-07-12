<?php

namespace Mabrouk\Mediable\Traits;

Trait MediaModelsTrait
{

    ## Relations

    public function mediable()
    {
        return $this->morphTo();
    }

    ## Getters & Setters

    protected function getPathAttribute($value)
    {
        return str_replace('public/', '/storage/', $value);
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
            'voice',
            'url',
        ];
        $type = \in_array(\strtolower($type), $availableTypes) ? \strtolower($type) : '';
        return $type == '' ? $query : $query->where('type', $type);
    }

    public function scopeOfGroup($query, $group = '')
    {
        return $group != '' ? $query->where('media_group_name', $group) : $query;
    }
}
