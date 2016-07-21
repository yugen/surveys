<?php

namespace Sirs\Surveys\Console;

use File;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Sirs\Surveys\Documents\SurveyDocument;

class CreateSurveyRulesFromDocument extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'survey:rules 
                            {document : File location of survey document}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create/update rules file from survey document';

    /**
     * The console command option for document location
     *
     * @var string
     */
    protected $documentFile = null;

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
        $this->documentFile =  $this->argument('document');
        $this->surveyDoc =  SurveyDocument::initFromFile($this->documentFile);

        $dir = config('surveys.rulesPath');
        $filename =  $dir.'/'.$this->surveyDoc->getRulesClassName() .'.php';

        if( !File::exists($dir) ){
            File::makeDirectory($dir, 0775, true);
        }
        if (File::exists($filename)) {
            $this->info('Cannot create the rules class '.$this->surveyDoc->getRulesClassName().'. File '.$filename.' already exists.');
        }
        $bytes_written = File::put($filename, $this->getRulesText());

        if ($bytes_written === false)
        {
            throw new Exception("Error writing to file");
        }else{
            $testName = 'Surveys/'.$this->surveyDoc->getRulesClassName().'Test';
            Artisan::call('make:test', ['name'=>$testName]);
            $this->info('Created ' . $filename);
            $this->info(' and test, tests/'.$testName);
        }
    }

    public function getRulesText() 
    {        
        $str = str_replace(
            'DummyClass', 
            $this->surveyDoc->getRulesClassName() ,
            file_get_contents(__DIR__.'/stubs/rules.stub')
        );
       
       $pageStr = '';
       $pageTitles = [];
       foreach( $this->surveyDoc->getPages() as $page ) {
            $pageTitles[] = $page->getTitle();
       }
       $pageStr = implode("\n\t\t", $pageTitles);
        $str = str_replace('PAGES', $pageStr, $str);
       return $str;
    }

}