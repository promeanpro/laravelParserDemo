<?php

namespace App\Console\Commands;

use Doctrine\DBAL\Driver\PDOConnection;
use Illuminate\Console\Command;

class CreateDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:create {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new pgsql database schema based on the database config file';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle() : void
    {
        $dsn = sprintf(
            'pgsql:host=%s;port=%s',
            config('database.connections.pgsql.host'),
            config('database.connections.pgsql.port')
        );
        $dbname = $this->argument('name');
        try {
            $pdo = new PDOConnection($dsn, config('database.connections.pgsql.username'), config('database.connections.pgsql.password'));
            $pdo->exec("CREATE DATABASE \"$dbname\";");
            $this->info(sprintf('Successfully created %s database', $dbname));
        } catch (\Exception $exception) {
            $this->error(sprintf('Failed to create %s database: %s', $dbname, $exception->getMessage()));
        }

    }
}
