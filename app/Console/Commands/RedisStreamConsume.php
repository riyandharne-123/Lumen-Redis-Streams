<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class RedisStreamConsume extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redis:stream-consume';

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
        $output = new \Symfony\Component\Console\Output\ConsoleOutput();
        while (true) {
            $data = Redis::executeRaw(['XREADGROUP', 'GROUP', 'test-group', 'test-consumer', 'COUNT', '1', 'STREAMS', 'test-stream', '>']);
            if(empty($data)) {
                continue;
            }

            $id = $data[0][1][0][0];

            //acknowledge message
            Redis::executeRaw(['XACK', 'test-stream', 'test-group', $id]);

            //delete from stream
            Redis::executeRaw(['XDEL', 'test-stream', $id]);

            $output->writeln(json_encode($data) . PHP_EOL);
        }
    }
}
