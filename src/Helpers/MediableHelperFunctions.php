<?php

use Illuminate\Support\Str;

function getYoutubeVideoId($path)
{
    $possibleVideoIdPrefix = [
        'youtu.be/',
        'embed/',
        '?vi=',
        '&v=',
        '?v=',
        '/vi/',
        '/v/'
    ];

    $videoId = $path;
    foreach ($possibleVideoIdPrefix as $case) {
        if (Str::contains($videoId, $case)) {
            $videoId = explode($case, $videoId)[1];
        }
    }
    return explode('?', explode('&', $videoId)[0])[0];
}

function randomBy($num = 6)
{
    $start = (int) str_repeat(1, $num);
    $end = (int) str_repeat(9, $num);
    return rand($start, $end);
}
