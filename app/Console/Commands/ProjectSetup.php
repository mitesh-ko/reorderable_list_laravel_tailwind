<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

class ProjectSetup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:project-setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        Process::timeout(1800)->run('npm i', function (string $type, string $output) {
            $this->info($output);
        });

        Process::run('composer run-script post-root-package-install', function (string $type, string $output) {
            $this->info($output);
        });

        Process::run('composer run-script post-create-project-cmd', function (string $type, string $output) {
            $this->info($output);
        });

        $this->info(Process::run('php artisan storage:link')->output());

        $this->setupdb();

        Process::timeout(240)->run('npm run build', function (string $type, string $output) {
            $this->info($output);
        });

        Process::timeout(0)->run('php artisan serve', function (string $type, string $output) {
            $this->info($output);
        });
    }

    private function setupdb()
    {
        $envKeyValue = [
            "DB_CONNECTION=" => "",
            "DB_HOST="       => "",
            "DB_PORT="       => "",
            "DB_DATABASE="   => "",
            "DB_USERNAME="   => "",
            "DB_PASSWORD="   => "",
        ];

        $envKey = [
            "/DB_CONNECTION=.*/",
            "/DB_HOST=.*/",
            "/DB_PORT=.*/",
            "/DB_DATABASE=.*/",
            "/DB_USERNAME=.*/",
            "/DB_PASSWORD=.*/",
        ];

        $envKeyValue['DB_CONNECTION='] = $this->choice('Choose your database connection', ['mysql', 'pgsql', 'sqlite']);

        if ($envKeyValue['DB_CONNECTION='] == 'sqlite') {
            $isRunSeeder = $this->confirm("Create database.sqlite file in database folder and enter yes.");
            foreach ($envKeyValue as $key => $value) {
                $envKeyValue[$key] = '#' . $key;
                $envKeyValue['DB_CONNECTION='] = 'DB_CONNECTION=sqlite';
            }
        } else {
            $this->info('Press enter to skip optional input.');
            $envKeyValue['DB_HOST='] = $this->ask('Enter database host (optional)', '127.0.0.1');
            $envKeyValue['DB_PORT='] = $this->ask('Enter database port (optional)', $envKeyValue['DB_CONNECTION='] == 'mysql' ? '3306' : '5432');
            $envKeyValue['DB_DATABASE='] = $this->ask('Enter database name');
            $envKeyValue['DB_USERNAME='] = $this->ask('Enter database username');
            $envKeyValue['DB_PASSWORD='] = $this->ask('Enter database password');
            $isRunSeeder = $this->confirm("Create {$envKeyValue['DB_DATABASE=']} database on {$envKeyValue['DB_CONNECTION=']} server and enter yes.");
            foreach ($envKeyValue as $key => $value) {
                $envKeyValue[$key] = $key . $value;
            }
        }
        if (File::exists('.env')) {
            $envFile = File::get('.env');
            $updatedEnv = preg_replace($envKey, $envKeyValue, $envFile);
            File::put('.env', $updatedEnv);
        }

        if ($isRunSeeder) {
            Process::run('php artisan migrate --seed', function (string $type, string $output) {
                $this->info($output);
            });
        }
    }
}
