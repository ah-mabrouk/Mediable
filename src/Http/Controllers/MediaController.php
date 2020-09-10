<?php

namespace Mabrouk\Mediable\Http\Controllers;

use Mabrouk\Mediable\Models\Media;
use Mabrouk\Mediable\Http\Resources\MediaResource;
use Mabrouk\Mediable\Http\Requests\MediaUpdateRequest;

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
        if ($request->updateMadia()) {
            return response([
                'message' => __('One media file updated successfully'),
                'media' => new MediaResource($medium->refresh()),
            ]);
        }

        return response([
            'message' => __('Something went wrong'),
        ], 403);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Mabrouk\Mediable\Models\Media  $medium
     * @return \Illuminate\Http\Response
     */
    public function destroy(Media $medium)
    {
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
