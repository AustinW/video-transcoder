<?php

namespace Austinw\VideoTranscoder\Http\Controllers;

use Austinw\VideoTranscoder\PreSign;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;

class PreSignController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file_name' => 'required'
        ]);

        return response()->json(['data' => [
            'request' => PreSign::make($request->input('file_name')),
            'bucket' => Config::get('video-transcoder.s3.bucket'),
            'path' => Config::get('video-transcoder.s3.upload_target'),
            'region' => Config::get('video-transcoder.s3.region'),
        ]]);
    }
}
