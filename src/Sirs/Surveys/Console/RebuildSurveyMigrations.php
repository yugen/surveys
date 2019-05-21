<?php

namespace Sirs\Surveys\Console;

use Illuminate\Console\Command;

class RebuildSurveyMigrations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'survey:migrations-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rebuild migrations for all surveys in the project.';

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
        $dir = base_path('resources/surveys');
        foreach (scandir($dir) as $filename) {
            $this->info('Create migration for '.$filename);
            if (in_array($filename, ['.', '..']) || is_dir($dir.'/'.$filename) || pathinfo($dir.'/'.$filename)['extension'] != 'xml') {
                continue;
            }
            \Artisan::call('survey:migration', ['document'=>config('surveys.surveysPath').$filename]);
        }
    }
}
