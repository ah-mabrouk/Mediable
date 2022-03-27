<?php

namespace Mabrouk\Mediable\Http\Controllers;

use Mabrouk\Mediable\Models\Media;
use Mabrouk\Mediable\Http\Resources\MediaResource;
use Mabrouk\Mediable\Http\Requests\MediaUpdateRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MediaController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  Mabrouk\Mediable\Http\Requests\MediaUpdateRequest  $request
     * @param  Mabrouk\Mediable\Models\Media  $medium
     * @return \Illuminate\Http\Response
     */
    public function update(MediaUpdateRequest $request, Media $medium)
    {
        $medium = $request->updateMadia();
        return response([
            'message' => __('One media file updated successfully'),
            'media' => new MediaResource($medium->refresh()),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Mabrouk\Mediable\Models\Media  $medium
     * @return \Illuminate\Http\Response
     */
    public function destroy($medium)
    {
        $medium = Media::findOrFail($medium);
        if (! $medium->mediable) {
            optional($medium)->remove();
            throw new ModelNotFoundException;
        }
        if ((bool) $medium->is_main) {
            return response([
                'message' => __('You can\'t delete a main media file'),
            ], 422);
        }
        $medium->mediable->deleteMedia($medium);
        return response([
            'message' => __('One media file deleted successfully'),
        ]);
    }
}
