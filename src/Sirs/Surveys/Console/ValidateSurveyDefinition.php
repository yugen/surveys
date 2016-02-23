<?php

namespace Sirs\Surveys\Console;

use Illuminate\Console\Command;
use Sirs\Surveys\Documents\SurveyDocument;

class ValidateSurveyDefinition extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'survey:validate {survey : Path to survey xml}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Validate a survey against the schema';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $doc = SurveyDocument::initFromFile($this->argument('survey'));
        try{
            if($doc->validate()){
                $this->info('Survey at '.$this->argument('survey').' is valid');
                return true;
            }else{
                $this->error('Survey at '.$this->argument('survey').' is not valid');
                return false;
            }
        }catch(\Exception $e){
            $this->error($e->getMessage());
            return false;
        }
    }
}