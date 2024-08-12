<?php

namespace Mabrouk\Mediable\Traits;

use Carbon\Carbon;
use Mabrouk\Mediable\Traits\Mediable;
use Mabrouk\Mediable\Models\TranslatedMedia;

Trait HasTranslatedMedia
{
    use Mediable;

    public $isHasTranslatedMedia = true;

    ## Relations

	public function media($type = null, $title = null, $relatedObjectMediaGroupName = null)
    {
        return $this->morphMany(TranslatedMedia::class, 'mediable')
            ->orderBy('translated_media.priority', 'asc')
            ->when($type, function ($query) use ($type) {
                $query->ofType('type', $type);
            })->when($relatedObjectMediaGroupName, function ($query) use ($relatedObjectMediaGroupName) {
                $query->ofGroup('media_group_name', $relatedObjectMediaGroupName);
            })->when($title, function ($query1) use ($title) {
                $query1->byTitle($title);
            })->select([
                'id',
                'mediable_type',
                'mediable_id',
                'type',
                'extension',
                'path',
                // 'media_group_name',
                'title',
                'description',
                'priority',
                'size',
                'is_main',
                'created_at',
                'updated_at',
            ]);
    }

	public function singleMedia($type = null)
    {
        return $this->morphOne(TranslatedMedia::class, 'mediable')
            ->when($type, function ($query) use ($type) {
                $query->where('type', $type);
            })->select([
                'id',
                'mediable_type',
                'mediable_id',
                'type',
                'extension',
                'path',
                // 'media_group_name',
                'title',
                'description',
                'priority',
                'size',
                'is_main',
                'created_at',
                'updated_at',
            ]);
    }

    ## Getters & Setters

    ## Query Scope Methods

    ## Other Methods

    public function addMedia(string $type, string $path, string $title = null, string $description = null, bool $isMain = false, string $extension = '', string $mediaGroupName = '', int $priority = 9999, int $fileSize = null)
    {
        ! $isMain ? : $this->normalizePreviousMainMedia();

        $media = $this->media()->create([
            'path' => $path,
            'type' => $type,
            'is_main' => $isMain ? true : false,
            'extension' => $extension,
            // 'media_group_name' => $mediaGroupName,
            'priority' => $priority,
            'size' => $fileSize,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        request()->dontTranslate = true;
        $media->translate([
            'title' => $title,
            'description' => $description,
        ], (config('translatable.fallback_locale') ?? config('app.fallback_locale')));
        $this->touch;
        request()->dontTranslate = false;
        return $this;
    }

    // ! should handle translation here
    public function editMedia(TranslatedMedia $singleMedia, string $path = null, string $title = null, string $description = null, bool $isMain = false, string $extension = '', string $mediaGroupName = '', int $priority = 9999, int $fileSize = null)
    {
        $oldPath = $path == null ?: $singleMedia->path;
        $singleMedia->is_main || (!$singleMedia->is_main && !$isMain) ? : $this->normalizePreviousMainMedia();

        ! $oldPath ?: $singleMedia->remove(true);
        $singleMedia->update([
            'path' => $path ?? $singleMedia->path,
            'title' => $title ?? $singleMedia->title,
            'description' => $description ?? $singleMedia->description,
            'is_main' => $isMain,
            'extension' => $extension,
            // 'media_group_name' => $mediaGroupName ?? $singleMedia->media_group_name,
            'priority' => $priority != $singleMedia->priority ? $priority : $singleMedia->priority,
            'size' => $fileSize,
            'updated_at' => Carbon::now(),
        ]);

        $this->touch;
    }

    // public function replaceMedia(TranslatedMedia $singleMedia, string $path, string $title = null, string $description = null, bool $isMain = false, string $mediaGroupName = '', int $priority = 9999, int $fileSize = null)
    // {
    //     $this->editMedia(singleMedia: $singleMedia, path: $path, title: $title, description: $description, isMain: $isMain, mediaGroupName: $mediaGroupName,  priority: $priority, fileSize: $fileSize);
    //     $this->touch;
    // }
}
