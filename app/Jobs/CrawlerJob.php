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

class CrawlerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    
    private const CRAWLER_URL_PATH = 'http://localhost:9999/spider.php?';
    
    protected $user;

    protected $searchRule;

    protected $from;

    protected $to;

    protected $type;


    
    public function __construct(User $user, $from, $to, $type)
    {
        $this->user = $user;
        $this->from = $from;
        $this->to = $to;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {        
        $response = Http::get($this::CRAWLER_URL_PATH . "{$this->type}=1&rule={$this->from}-{$this->to}");
        sleep(2);
        Log::info('Job completed');
    }
}
