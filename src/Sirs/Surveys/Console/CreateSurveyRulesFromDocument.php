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
            $this->getDefaultText()
        );
       
       $pageStr = '';
       foreach( $this->survey->getPages() as $page ) {
            $pageStr .= $page->getTitle()."\n";

       }
        $str = str_replace('PAGES', $pageStr, $str);
       return $str;
    }

    public function getDefaultText() 
    {

        return '<?php 
namespace App\Surveys;

class DummyClass 
{
   /*
   Rules stub.  Will add more as we flesh this out

   format:
    public function PAGETITLEBeforeShow() {}
    public function PAGETITLEBeforeSave() {}
    public function PAGETITLEAfterSave() {}
    public function PAGETITLESkip() {}
    public function PAGAETITLEGetValidator() {}
    public function getRedirectUrl() {}

   Known Page Titles:
    PAGES

   */

    public function __construct($survey, $response){
        $this->survey = $survey;
        $this->response = $response;
    }

}
';

    }
}