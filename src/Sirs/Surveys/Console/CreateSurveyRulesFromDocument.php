<?php

namespace Sirs\Surveys\Console;

use Illuminate\Console\Command;
use Sirs\Surveys\Documents\SurveyDocument;
use File;

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
        $this->survey =  SurveyDocument::initFromFile($this->documentFile);

        $dir = config('surveys.rulesPath');
        $filename =  $dir.'/'.$this->survey->getRulesClassName() .'.php';

        if( !File::exists($dir) ){
            File::makeDirectory($dir, 0775, true);
        }
        $bytes_written = File::put($filename, $this->getRulesText());

        if ($bytes_written === false)
        {
            throw new Exception("Error writing to file");
        }else{
            $this->info('Created ' . $filename);
        }
    }

    public function getRulesText() 
    {        
        $str = str_replace(
            'DummyClass', 
            $this->survey->getRulesClassName() ,
            file_get_contents(__DIR__.'/stubs/rules.stub')
        );
       
       $pageStr = '';
       $pageTitles = [];
       foreach( $this->survey->getPages() as $page ) {
            $pageTitles[] = $page->getTitle();
       }
       $pageStr = implode("\n\t\t", $pageTitles);
        $str = str_replace('PAGES', $pageStr, $str);
       return $str;
    }

}