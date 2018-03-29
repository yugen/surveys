<?php

namespace Sirs\Surveys\Console;

use Illuminate\Console\Command;
use Sirs\Surveys\Documents\SurveyDocument;
use File;

class CreateSurveyMigrationsFromDocument extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'survey:migration 
                            {document : File location of survey document}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create/update migrations from survey document';

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
        $this->survey = SurveyDocument::initFromFile($this->documentFile);

        $contents = $this->getMigrationText();
        $filename =  'database/migrations/0000_00_00_000001_create_survey_rsp'
            .'_'.$this->survey->name
            .'_'.str_replace('.', '', $this->survey->version).'.php';

        if (File::put($filename, $contents) === false){
            throw new \Exception("Error writing to file");
        }else{
            $this->info('Created ' . $filename);
        }
        
        
        
    }
    public function getMigrationText() 
    {
        $str = $this->getDefaultText();

        $questions = $this->survey->getQuestions();
        
        $str = str_replace('DummyTable', $this->formatTableName( $this->survey->getName(), $this->survey->getVersion() ), $str);

        $str = str_replace('DummyClass', $this->formatClassName( $this->survey->getName(), $this->survey->getVersion() ), $str);

        $str = str_replace('DummyName', $this->survey->getName().$this->survey->getVersion(), $str);

        $str = str_replace('DummySlug', str_replace(' ', '_', $this->survey->getName()), $str);

        $str = str_replace('DummyVersion',  $this->survey->getVersion(), $str);

        if ($this->survey->getSurveyId()) {
            $str = str_replace('DummySurveyId', $this->survey->getSurveyId(), $str);
        }else{
            $str = str_replace("            \"id\"=>DummySurveyId,\n", '', $str);
        }

        if( !preg_match('/\.xml$/', $this->documentFile) ){
            $this->documentFile .= '.xml';
        }
        $str = str_replace('DummyFileName', $this->documentFile, $str);

        $questionStrings = [];
        foreach( $questions as $question ) {
            $variables = $question->getVariables();
            print("$question->name vars: ".count($variables)."\n");

            foreach ($variables as $idx => $var) {
                print_r('var: '.$var->name."\n");
                $questionStrings[] = '$table->'.$this->setMigrationDataType($var->dataFormat)
                   ."('".$var->name."')->nullable();";
            }
        }

        $questionsStr = implode("\n\t\t\t", $questionStrings);
        $str = str_replace('INSERTSURVEY', $questionsStr, $str);
        
        return $str;
    }

    public function setMigrationDataType( $dataFormat ) 
    {
        $mysqlToLaravelTypeMap = [
            'tinyint'=>'tinyInteger',
            'smallint'=>'smallInteger',
            'mediumint'=>'mediumInteger',
            'int'=>'integer',
            'bigint'=>'bigInteger',
            'float'=>'float',
            'double'=>'double',
            'decimal'=>'decimal',
            'bit'=>'bit',
            'char'=>'char',
            'varchar'=>'string',
            'tinytext'=>'string',
            'text'=>'text',
            'mediumtext'=>'mediumText',
            'longtext'=>'longText',
            'json' => 'json',
            'binary'=>'binary',
            'varbinary'=>'binary',
            'tinyblob'=>'text',
            'blob'=>'text',
            'longblob'=>'longText',
            'enum'=>'enum',
            'set'=>'enum',
            'date'=>'date',
            'datetime'=>'datetime',
            'time'=>'time',
            'timestamp'=>'timestamp',
            'year'=>'date',
        ];        
        if( isset($mysqlToLaravelTypeMap[$dataFormat]) ){
            return $mysqlToLaravelTypeMap[$dataFormat];
        }else{
            throw new \Exception($dataFormat.' not found in available data formats');
        }
    }

    public function formatTableName( $name, $version ) 
    {
        return 'rsp_'.strtolower( str_replace('-', '_', $name )).'_'.$version;
    }

    public function formatClassName( $name, $version ) 
    {
        $string = preg_replace('/\./', '', 'CreateSurveyRsp'.ucfirst($name).$version );
        return ucfirst(camel_case($string));
    }

    public function getDefaultText() 
    {

        return file_get_contents(__DIR__.'/stubs/migration.stub');

    }
}