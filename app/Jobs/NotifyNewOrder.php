<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use DB;

class NotifyNewOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $type;
    protected $id;
    /**
     * IN THIS JOB WILL NOTIFY TO FIXERMEN WITH CATEGORIES MATCHED
     *
     * @return void
     */
    public function __construct($type,$id)
    {
        $this->type = $type;
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // $fixermen = DB::table('', ['John'])
    }
}
