<?php

namespace Austinw\VideoTranscoder\Commands;

use Illuminate\Console\Command;

class VideoTranscoderCommand extends Command
{
    public $signature = 'video-transcoder';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}
