<?php

namespace Mabrouk\Mediable\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaMetaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'meta_title' => $this->meta_title,
            'alternative_text' => $this->alternative_text,
        ];
    }
}
