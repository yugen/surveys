<?php

namespace Sirs\Surveys\Console;

use Illuminate\Console\Command;
use Sirs\Surveys\Documents\SurveyDocument;
use File;

class CreateSurveyRulesFromTemplate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'survey:rules 
                            {template : File location of survey template}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create/update rules file from survey template';

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
        
        $this->templateFile =  $this->argument('template');

        $contents = $this->getMigrationText();
        $filename =  'app/Surveys/'
            .$this->formatClassName( $this->survey->getName(), $this->survey->getVersion()) .'.php';

            $bytes_written = File::put($filename, $contents);
            if ($bytes_written === false)
            {
                die("Error writing to file");
            }
        
        
        
    }
    public function getMigrationText() 
    {
        $str = $this->getDefaultText();

        $this->survey =  SurveyDocument::initFromFile($this->templateFile.".xml");
        
        $str = str_replace('DummyClass', $this->formatClassName( $this->survey->getName(), $this->survey->getVersion() ), $str);

       $pages = $this->survey->getPages();
       
       $pageStr = '';
       foreach( $pages as $page ) {
            $pageStr .= $page->getTitle()."\n";

       }
        $str = str_replace('PAGES', $pageStr, $str);
       return $str;
    }



    public function formatClassName( $name, $version ) 
    {
        return str_replace('-', '', str_replace('.', '', $name.$version.'Rules' ));
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

   Known Page Titles:
    PAGES

   */
}
';

    }
}