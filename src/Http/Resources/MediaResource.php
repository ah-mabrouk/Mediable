<?php

namespace Mabrouk\Mediable\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
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
            'type' => $this->type,
            'extension' => $this->extension,
            'size' => $this->size,
            'path' => $this->type != 'video' ? url($this->path) : $this->path,
            'title' => $this->title,
            'description' => $this->description,
            'priority' => $this->priority,
            'main' => $this->is_main,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'media_meta' => new MediaMetaResource($this->mediaMeta)
        ];
    }
}
