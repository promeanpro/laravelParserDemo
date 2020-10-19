<?php

namespace App\Console\Commands;

use App\Services\User\Import\UserImportService;
use Illuminate\Console\Command;

class importUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:import-from-csv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle()
    {
        $filename = 'test.csv';

        /** @var userImportService $userImportService */
        $userImportService = $this->laravel->make(UserImportService::class);
        $userImportService->doSync('csv', $filename);
    }
}
