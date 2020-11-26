<?php

namespace Mabrouk\Mediable\Traits;

use ReflectionClass;
use Illuminate\Support\Str;
use Mabrouk\Mediable\Models\Media;
use Illuminate\Support\Facades\Storage;

Trait Mediable
{
	public function media($type = null, $title = null)
    {
        return $this->morphMany(Media::class, 'mediable')
                    ->orderBy('priority', 'asc')
                    ->when($type, function ($query) use ($type) {
                        $query->where('type', $type);
                    })
                    ->when($title, function ($query) use ($title) {
                        $query->where('title', $title);
                    })
                    ->select([
                        'id',
                        'type',
                        'mediable_type',
                        'mediable_id',
                        'path',
                        'title',
                        'description',
                        'priority',
                        'is_main'
                    ]);
    }

	public function singleMedia($type = null)
    {
        return $this->morphOne(Media::class, 'mediable')
                    ->when($type, function ($query) use ($type) {
                        $query->where('type', $type);
                    })
                    ->select([
                        'id',
                        'type',
                        'mediable_type',
                        'mediable_id',
                        'path',
                        'title',
                        'description',
                        'is_main'
                    ]);
    }

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

    public function addMedia($type, $path, $title = null, $description = null, $isMain = false)
    {
        ! $isMain ? : $this->normalizePreviousMainMedia();

        $this->media()->create([
            'path' => $path,
            'type' => $type,
            'title' => $title,
            'description' => $description,
            'is_main' => $isMain ? true : false
        ]);
        $this->touch;
        return $this;
    }

    public function editMedia(Media $singleMedia, $path = null, $title = null, $description = null, $isMain = false)
    {
        $oldPath = $path == null ?: $singleMedia->path;
        $singleMedia->is_main || (!$singleMedia->is_main && !$isMain) ? : $this->normalizePreviousMainMedia();

        $singleMedia->update([
            'path' => $path ?? $singleMedia->path,
            'title' => $title ?? $singleMedia->title,
            'description' => $description ?? $singleMedia->description,
            'is_main' => $isMain
        ]);
        ! $oldPath ?: $this->deleteOldMediaPath($oldPath);
        $this->touch;
    }

    public function replaceMedia(Media $singleMedia, $path, $title = null, $description = null, $isMain = false)
    {
        $this->editMedia($singleMedia, $path, $title, $description, $isMain);
        $this->touch;
    }

    public function deleteMedia(Media $singleMedia)
    {
        $singleMedia->delete();
        $this->deleteOldMediaPath($singleMedia->path);
        $this->touch;
    }

    public function deleteAllMedia()
    {
        $this->media->each(function ($singleMedia) {
            $this->deleteMedia($singleMedia);
        });
        $this->touch;
    }

    protected function deleteOldMediaPath($path)
    {
        Storage::delete($path);
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

    public function getVideosAttribute()
    {
        return $this->media('video')->get();
    }

    public function getVideoAttribute()
    {
        return $this->media('video')->first();
    }

    protected function getVideoIdAttribute($value)
    {
        return getYoutubeVideoId($this->path);
    }
}
