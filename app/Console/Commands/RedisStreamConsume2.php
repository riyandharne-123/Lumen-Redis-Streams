<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class RedisStreamConsume2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redis:stream-consume-2';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read data from redis stream';

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
            $data = Redis::executeRaw(['XREADGROUP', 'GROUP', 'test-group', 'test-consumer-2', 'COUNT', '1', 'STREAMS', 'test-stream', '>']);
            if(empty($data) || empty($data[0][1])) {
                continue;
            }

            $id = $data[0][1][0][0];
            $message = json_decode($data[0][1][0][1][1]);

            //acknowledge message
            Redis::executeRaw(['XACK', 'test-stream', 'test-group', $id]);

            //delete from stream
            Redis::executeRaw(['XDEL', 'test-stream', $id]);

            echo('data: ' . json_encode([
                'message_id' => $id,
                'message' => $message->message
            ]) . PHP_EOL);
        }
    }
}
