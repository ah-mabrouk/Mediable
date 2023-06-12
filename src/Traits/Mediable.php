<?php

namespace Mabrouk\Mediable\Traits;

use ReflectionClass;
use Illuminate\Support\Str;
use Mabrouk\Mediable\Models\Media;

Trait Mediable
{
    ## Relations

	public function media($type = null, $title = null)
    {
        return $this->morphMany(Media::class, 'mediable')
            ->orderBy('priority', 'asc')
            ->when($type, function ($query) use ($type) {
                $query->ofType($type);
            })->when($title, function ($query) use ($title) {
                $query->byTitle($title);
            })->select([
                'id',
                'mediable_type',
                'mediable_id',
                'type',
                'path',
                'title',
                'description',
                'priority',
                'size',
                'is_main',
            ]);
    }

	public function singleMedia($type = null)
    {
        return $this->morphOne(Media::class, 'mediable')
            ->when($type, function ($query) use ($type) {
                $query->ofType($type);
            })->select([
                'id',
                'mediable_type',
                'mediable_id',
                'type',
                'path',
                'title',
                'description',
                'size',
                'is_main',
            ]);
    }

    ## Getters & Setters

    public function getMediaDirectoryAttribute($value)
    {
        $className = new ReflectionClass($this);
        return Str::plural(strtolower($className->getShortName()));
    }

    public function getPhotosDirectoryAttribute($value)
    {
        return config('mediable.base_path') . config('mediable.photos.path') . "{$this->mediaDirectory}";
    }

    public function getFilesDirectoryAttribute($value)
    {
        return config('mediable.base_path') . config('mediable.files.path') . "{$this->mediaDirectory}";
    }

    public function getVideosDirectoryAttribute($value)
    {
        return config('mediable.base_path') . config('mediable.videos.path') . "{$this->mediaDirectory}";
    }

    public function getMainMediaAttribute()
    {
        $main = $this->media()->where('is_main', true)->first();
        return $main ? $main : $this->media()->first();
    }

    public function getIsMainMediaAttribute()
    {
        return (bool) $this->singleMedia->is_main;
    }

    public function getPhotosAttribute()
    {
        return $this->media('photo')->get();
    }

    public function getPhotoAttribute()
    {
        return $this->media('photo')->first();
    }

    public function getFilesAttribute()
    {
        return $this->media('file')->get();
    }

    public function getFileAttribute()
    {
        return $this->media('file')->first();
    }

    public function getVoicesAttribute()
    {
        return $this->media('voice')->get();
    }

    public function getVoiceAttribute()
    {
        return $this->media('voice')->first();
    }

    public function getVideosAttribute()
    {
        return $this->media('video')->get();
    }

    public function getVideoAttribute()
    {
        return $this->media('video')->first();
    }

    public function getUrlsAttribute()
    {
        return $this->media('url')->get();
    }

    public function getUrlAttribute()
    {
        return $this->media('url')->first();
    }

    protected function getVideoIdAttribute($value)
    {
        return getYoutubeVideoId($this->path);
    }

    ## Query Scope Methods

    ## Other Methods

    public function addMedia(string $type, string $path, string $title = null, string $description = null, bool $isMain = false, int $priority = 9999, int $fileSize = null)
    {
        ! $isMain ? : $this->normalizePreviousMainMedia();

        $this->media()->create([
            'path' => $path,
            'type' => $type,
            'title' => $title,
            'description' => $description,
            'is_main' => $isMain ? true : false,
            'priority' => $priority,
            'size' => $fileSize,
        ]);
        $this->touch;
        return $this;
    }

    public function editMedia(Media $singleMedia, string $path = null, string $title = null, string $description = null, bool $isMain = false, int $priority = 9999, int $fileSize = null)
    {
        $oldPath = $path == null ?: $singleMedia->path;
        $singleMedia->is_main || (!$singleMedia->is_main && !$isMain) ? : $this->normalizePreviousMainMedia();

        ! $oldPath ?: $singleMedia->remove(true);
        $singleMedia->update([
            'path' => $path ?? $singleMedia->path,
            'title' => $title ?? $singleMedia->title,
            'description' => $description ?? $singleMedia->description,
            'is_main' => $isMain,
            'priority' => $priority != $singleMedia->priority && $priority != 9999 ? $priority : $singleMedia->priority,
            'size' => $fileSize,
        ]);

        $this->touch;
    }

    public function replaceMedia(Media $singleMedia, string $path, string $title = null, string $description = null, bool $isMain = false, int $fileSize = null)
    {
        $this->editMedia($singleMedia, $path, $title, $description, $isMain, $fileSize);
        $this->touch;
    }

    public function deleteMedia(Media $singleMedia)
    {
        $singleMedia->remove();
        $this->touch;
    }

    public function deleteAllMedia()
    {
        $this->media->each(function ($singleMedia) {
            $this->deleteMedia($singleMedia);
        });
    }

    protected function normalizePreviousMainMedia()
    {
        if ((bool) optional($this->mainMedia)->is_main) {
            $this->mainMedia->update([
                'is_main' => false
            ]);
        }
        return;
    }
}
