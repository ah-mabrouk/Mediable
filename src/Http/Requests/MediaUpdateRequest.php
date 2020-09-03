<?php

namespace Mabrouk\Mediablel\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;

class MediaUpdateRequest extends FormRequest
{
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
        $this->type = $this->medium->type;
        $this->model = $this->medium->mediable;

        switch ($this->type) {
            case 'photo' :
                $rules['file'] = 'required|mimes:png,jpeg|max:2048';
                break;
            case 'file' :
                $rules['file'] = 'required|mimes:pdf|max:2048';
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

        if ($this->type != 'video') {
            $this->model->editMedia(
                $this->medium,
                $this->file->storeAs(
                    config($configPath) . $this->model->$mediaDirectory,
                    $fileName . '-' . random_int(1, 9999999) . '.' . $this->file->getClientOriginalExtension()
                )
            );
            return true;
        }

        if ($this->type != 'video') {
            $this->model->editMedia(
                $this->medium,
                $this->file->storeAs(
                    config($configPath) . $this->model->$mediaDirectory,
                    $fileName . '-' . randomBy() . '.' . $this->file->getClientOriginalExtension()
                )
            );
            return true;
        }
    }
}
