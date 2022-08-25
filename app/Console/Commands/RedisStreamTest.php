<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class RedisStreamTest extends Command
{
    protected $signature = 'redis:setup';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        //creating test stream
        Redis::executeRaw(['XADD', 'test-stream', '*', 'message', 'hello']);

        //creating test consumer group
        Redis::executeRaw(['XGROUP', 'CREATE', 'test-stream', 'test-group', '$']);
    }
}
