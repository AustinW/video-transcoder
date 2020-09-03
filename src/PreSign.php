<?php

namespace Austinw\VideoTranscoder;

use Aws\S3\S3Client;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class PreSign
{
    public static function make($fileName): array
    {
        $bucket = Config::get('video-transcoder.s3.bucket');

        $client = static::storageClient();

        $fileHashName = Str::uuid();

        $signedRequest = $client->createPresignedRequest(
            static::createCommand($client, $bucket, $fileName, 'private'),
            '+60 minutes'
        );

        $uri = $signedRequest->getUri();

        return [
            'path' => $fileHashName,
            'url' => 'https://'.$uri->getHost().$uri->getPath().'?'.$uri->getQuery(),
            'headers' => static::headers($signedRequest),
        ];
    }

    protected static function headers($signedRequest, $fileType = null)
    {
        return array_merge(
            $signedRequest->getHeaders(),
            [
                'Content-Type' => $fileType ?: 'application/octet-stream',
            ]
        );
    }

    protected static function createCommand(S3Client $client, $bucket, $key, $visibility)
    {
        return $client->getCommand('putObject', array_filter([
            'Bucket' => $bucket,
            'Key' => $key,
            'ACL' => $visibility,
            'ContentType' => 'application/octet-stream',
            'CacheControl' => null,
            'Expires' => null,
        ]));
    }

    protected static function storageClient()
    {
        $key = Config::get('video-transcoder.s3.key');
        $secret = Config::get('video-transcoder.s3.secret');
        $region = Config::get('video-transcoder.s3.region');

        $config = [
            'region' => $region,
            'version' => 'latest',
            'signature_version' => 'v4',
            'credentials' => [
                'key' => $key,
                'secret' => $secret,
            ],
        ];

        return S3Client::factory($config);
    }
}
