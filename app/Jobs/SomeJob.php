<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SomeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $user;

    private const CRAWLER_URL_PATH = 'http://localhost:9999/spider.php?feed=1&';
    

    protected $searchRule;

    protected $from;

    protected $to;


    public function __construct(User $user, $from, $to)
    {
        $this->user = $user;
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $response = Http::get(SomeJob::CRAWLER_URL_PATH . "rule={$this->from}-{$this->to}");
        // sleep(10);
        // Log::info('Job completed');

        // http://localhost:9999/spider.php?search=1&phrase=bitola%20fudbal&iterations=1
        // $response = Http::get('http://localhost:9999/spider.php?search=1&phrase=messi&iterations=1');

        // sleep(2);
        // Log::info('Job completed with user id :' . $this->user->id);

    }
}
