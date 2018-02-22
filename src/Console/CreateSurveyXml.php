<?php

namespace Sirs\Surveys\Console;

use Illuminate\Console\Command;

class CreateSurveyXml extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:survey 
                            {name : name of the survey}
                            {--title= : title of survey}
                            {--survey_version=1 : version}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a stub survey file';

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
        $name = ucfirst(camel_case($this->argument('name')));
        $title = ($this->option('title')) 
                    ? $this->option('title') 
                    : ucwords(str_replace('_', ' ', snake_case($name)));
        $version = $this->option('survey_version');

        $stub = file_get_contents(__DIR__.'/stubs/survey_xml.stub');
        $contents = str_replace('DUMMY_NAME', $name, $stub);
        $contents = str_replace('DUMMY_TITLE', $title, $contents);
        $contents = str_replace('DUMMY_VERSION', $version, $contents);

        $filename =  config('surveys.surveysPath').'/'.snake_case($name).'.xml';

        if (\File::put($filename, $contents) === false){
            throw new \Exception("Error writing to file");
        }else{
            $this->info('Created ' . $filename);
        }


    }
}
