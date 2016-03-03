<?php

namespace Sirs\Surveys\Console;

use Illuminate\Console\Command;
use Sirs\Surveys\Documents\SurveyDocument;
use File;

class NewSurveyFromDocument extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'survey:new 
                            {document : File location of survey document}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates new survey migration and rules document';

    /**
     * The console command option for template location
     *
     * @var string
     */
    protected $templateFile = null;

     /**
     * The survey object
     *
     * @var string
     */
    protected $survey = null;



    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
        $this->survey = $this->argument('document');

        try {

            $this->call('survey:validate', [
            'survey' => $this->survey
            ]);

            $this->call('survey:migration', [
            'document' => $this->survey
            ]);

            $this->call('survey:rules', [
                'document' => $this->survey
            ]);

        } catch (Exception $e) {
            $this->error($e->getMessage());
            return false;
        }
   
    }
}