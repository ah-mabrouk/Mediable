<?php

namespace Mabrouk\Mediable\Models;

use Illuminate\Database\Eloquent\Model;
use Mabrouk\Translatable\Traits\Translatable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MediaMeta extends Model
{
    use HasFactory, Translatable;

    protected $table = 'media_meta';

    public $translatedAttributes = [
        'meta_title',
        'alternative_text',
    ];

    protected $fillable = [ 
        'media_id'
    ];

    ## Relations

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    ## Getters & Setters

    ## Query Scope Methods

    ## Other Methods

    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory(): Factory
    {
        return \Mabrouk\Mediable\Database\Factories\MediaMetaFactory::new();
    }

    public function remove(): bool
    {
        return $this->deleteTranslations()->delete();
    }
}
