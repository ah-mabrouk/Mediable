<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TranslatedMediaTranslation extends Model
{
    protected $fillable = [
        'locale',

        'title',
        'description',
    ];

    ## Relations

    public function translatedMedia()
    {
        return $this->belongsTo(TranslatedMedia::class, 'translated_media_id');
    }
}
