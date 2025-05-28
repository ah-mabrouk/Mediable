<?php

namespace Mabrouk\Mediable\Models;

use Illuminate\Database\Eloquent\Model;

class MediaMetaTranslation extends Model
{
    protected $fillable = [
        'media_meta_id',

        'locale',

        'meta_title',
        'alternative_text',
    ];

    ## Relations

    public function mediaMeta()
    {
        return $this->belongsTo(MediaMeta::class);
    }

    ## Getters & Setters

    ## Query Scope Methods

    ## Other Methods    
}
