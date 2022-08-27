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
    protected $signature = 'redis:stream-publish {--seconds=}';

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
            if (!empty($this->option('seconds'))) {
                sleep((float)$this->option('seconds'));
            }
            echo 'message_id: ' . Redis::executeRaw(['XADD', 'test-stream', '*',
                'data', json_encode([
                    'random_number' => rand(0, 999),
                    'message' => 'Hello World!'
                ])
            ]) . PHP_EOL;
        }
    }
}
