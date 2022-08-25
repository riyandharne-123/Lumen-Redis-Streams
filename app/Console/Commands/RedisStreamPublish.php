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
        while (true) {
            sleep(5);
            echo Redis::executeRaw(['XADD', 'test-stream', '*',
                'random_number', rand(0, 999),
                'message', 'Hello World!'
            ]) . PHP_EOL;
        }
    }
}
