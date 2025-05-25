<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class RedisStreamPublish extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redis:stream-publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish data to a Redis channel';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $messages = [1,2,3,4,5,6,7,8,9,10];
        foreach($messages as $message) {
            echo 'message_id: ' . Redis::executeRaw(['XADD', 'test-stream', '*',
                'data', json_encode([
                    'message' => $message
                ])
            ]) . PHP_EOL;
        }
    }
}
