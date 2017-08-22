<?php

namespace Sirs\Surveys\Console;

use File;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Sirs\Surveys\Documents\SurveyDocument;
use Sirs\Surveys\Models\Survey;
use Sirs\Surveys\Models\Response;

class CreateWorkflowStrategy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'survey:workflow 
                            {survey_slug : Survey slug or \'all\' to create workflows for all survey types}
                            {--replace : Replace an existing file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create SurveyWorkflowStrategy class for survey of type';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $surveySlug = $this->argument('survey_slug');
        $surveys = collect([]);
        if ($surveySlug == 'all') {
            $surveys = Survey::all();
        }else{
            $surveys = Survey::where('slug', $surveySlug)->get();
            if($surveys->count() < 1){
                $this->error('Could not find survey type with slug '.$this->argument('survey_slug').'.');
                $typeSlugs = Survey::all()->lists('slug')->toArray();
                $this->info("Appointment types for this project: \n\t".implode("\n\t", $typeSlugs));
                return;
            }
        }

        $surveys->each(function($type){
            $typeClassName = $this->buildClassName($type);
            $this->createClass($type);
            \Artisan::call('make:test', [
                'name' => 'Surveys/'.$typeClassName.'WorkflowStrategyTest'
            ]);
        });
    }

    protected function createClass(Survey $survey){

        $typeClassName = $this->buildClassName($survey);
        $stub = file_get_contents(__DIR__.'/stubs/WorkflowStrategy.stub');
        $classContent = preg_replace('/{DummyType}/', $typeClassName, $stub);

        if( !file_exists(app_path('Surveys/Workflows')) ){
            mkdir(app_path('Surveys/Workflows'));
        }

        $classFile = app_path('Surveys/Workflows/'.$typeClassName.'WorkflowStrategy.php');
        if( !file_exists($classFile) ){
            file_put_contents($classFile, $classContent);
            $this->info('Created class at '.$classFile);
        }elseif($this->confirm("There's already a workflow class for this survey type.\n Do you want to replace the existing file? [y|N]")){
            file_put_contents($classFile, $classContent);
            $this->info('Replaced '.$classFile);
        }else{
            $this->info('Left existing workflow in place.');
        }
    }

    protected function buildClassName(Survey $survey)
    {
        return ucfirst(camel_case($survey->slug));        
    }

}