<?php

namespace Mabrouk\Mediable\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Mabrouk\Mediable\Traits\MediaModelsTrait;
use Mabrouk\Translatable\Traits\Translatable;
use Mabrouk\Mediable\Factories\TranslatedMediaFactory;

class TranslatedMedia extends Model
{
    use MediaModelsTrait, Translatable;

    protected $table = 'translated_media';

    public $translatedAttributes = [
        'title',        // nullable
        'description',  // nullable
    ];

    protected $fillable = [
        'path',
        'type',         // photo, video, file, voice or url
        'media_group_name', // non translated. just used to separate the multiple media groups programatically
        'size',         // file size in kb
        'priority',     // default 0
        'is_main',
    ];

    protected $casts = [
        'is_main' => 'boolean',
    ];

    ## Relations

    ## Getters & Setters

    ## Query Scope Methods

    public function scopeByTitle($query1, string $title = '')
    {
        return $title == '' ? $query1 : $query1->whereHas('translations', function ($query2) use ($title) {
            $query2->where('title', $title);
        });
    }

    ## Other Methods

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return TranslatedMediaFactory::new();
    }

    public function remove($removeFileWithoutObject = false)
    {
        Storage::delete($this->storagePath);
        if ($removeFileWithoutObject) return $this;
        $this->deleteTranslations();
        $this->delete();
        return $this;
    }
}
