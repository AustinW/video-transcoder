<?php

namespace Austinw\VideoTranscoder;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Austinw\Video Transcoder\VideoTranscoder
 */
class VideoTranscoderFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'video-transcoder';
    }
}
