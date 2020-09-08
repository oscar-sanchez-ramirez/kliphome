<?php

namespace App\Console\Commands;
use App\ResetPassword;
use Illuminate\Console\Command;

class DeleteCodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:code';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Eliminará los codigos de verificacion por teléfono';

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
        ResetPassword::truncate();
    }
}
