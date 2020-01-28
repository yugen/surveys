<?php

namespace Sirs\Surveys\Console;

use Illuminate\Console\Command;

class RefreshSurveyResponseTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'survey:refresh {--only= : Comma separated list of survey slugs to refresh}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh response tables';

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
        $surveyQuery = class_survey()::query();

        if ($this->option('only')) {
            $slugs = explode(',', $this->option('only'));
            $surveyQuery->whereIn('slug', $slugs);
        }

        $surveys = $surveyQuery->get();
        $migrationNames = $surveys->pluck('response_table')
                            ->map(function ($rspTable) {
                                return '0000_00_00_000001_create_survey_'.$rspTable;
                            });

        \DB::table("migrations")
                        ->whereIn('migration', $migrationNames)
                        ->delete();

        $surveys->pluck('response_table')
            ->each(function ($tableName) {
                \Schema::dropIfExists($tableName);
            });
                         
        $this->call('migrate');
    }
}
