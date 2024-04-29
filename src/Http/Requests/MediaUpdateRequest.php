<?php

namespace Mabrouk\Mediable\Http\Requests;

use Illuminate\Support\Str;
use Mabrouk\Mediable\Models\Media;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MediaUpdateRequest extends FormRequest
{
    public $model;
    public $medium;
    public $type;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->medium = \gettype($this->medium) == 'object' ? $this->medium : Media::findOrFail($this->medium);
        $this->type = $this->medium->type;
        $this->model = $this->medium->mediable;
        if (! $this->medium->mediable) {
            throw new ModelNotFoundException;
        }

        switch ($this->type) {
            case 'photo' :
                $rules['file'] = 'required|mimes:png,jpeg|max:2048';
                break;
            case 'file' :
                $rules['file'] = 'required|mimes:pdf,doc,docx|max:2048';
                break;
            case 'video' :
                $rules['file'] = 'required|active_url';
                break;
        }

        return $rules;
    }

    public function updateMadia()
    {
        $configPath = 'media.' . Str::plural($this->type) . '.path';
        $mediaDirectory = Str::plural($this->type) . 'Directory';
        $fileName = $this->model->name != null ? Str::slug($this->model->name) : $this->model->slug;

        switch (true) {
            case $this->type == 'video' :
                $this->model->editMedia(singleMedia: $this->medium, path: $this->file);
                break;
            case $this->type != 'video' :
                $this->model->editMedia(
                    singleMedia: $this->medium,
                    path: $this->file->storeAs(
                        config($configPath) . $this->model->$mediaDirectory,
                        $fileName . '-' . random_int(1, 9999999) . '.' . $this->file->getClientOriginalExtension()
                    ),
                    fileSize: $this->file('file')->getSize() / 1024,
                    extension: $this->file->getClientOriginalExtension()
                );
                break;
        }
        return $this->medium->refresh();
    }
}
