<?php

return [
    'model' => \Austinw\VideoTranscoder\Video::class,

    'path' => 'video-transcoder',

    's3' => [
        'key' => env('VIDEO_TRANSCODER_S3_KEY', env('AWS_ACCESS_KEY_ID')),

        'secret' => env('VIDEO_TRANSCODER_S3_SECRET', env('AWS_SECRET_ACCESS_KEY')),

        'region' => env('VIDEO_TRANSCODER_S3_REGION', env('AWS_DEFAULT_REGION')),

        'bucket' => env('VIDEO_TRANSCODER_S3_BUCKET', env('AWS_BUCKET')),

        'version' => env('VIDEO_TRANSCODER_S3_VERSION', 'latest'),

        'upload_target' => env('VIDEO_TRANSCODER_S3_UPLOAD_TARGET', 'uploads'),
    ]
];
