<?php

namespace Austinw\VideoTranscoder\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;

class VideoController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required',
        ]);

        $video = config('video-transcoder.model')->create([
            'title' => '',
            'description' => '',
        ]);

        $video
            ->addMediaFromDisk($request->input('file.name'), 's3')
            ->toMediaCollection();
    }
}
