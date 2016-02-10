<?php

namespace Sirs\Surveys\Console;

use Illuminate\Console\Command;
use Sirs\Surveys\Documents\SurveyDocument;
use File;

class CreateSurveyMigrationsFromTemplate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'survey:migration 
                            {template : File location of survey template}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create/update migrations from survey template';

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
        $filename =  'database/migrations/0000_00_00_000000_create_survey_rsp'
            .'_'.$this->survey->getName()
            .'_'.str_replace('.', '', $this->survey->getVersion()).'.php';

            $bytes_written = File::put($filename, $contents);
            if ($bytes_written === false)
            {
                die("Error writing to file");
            }
        
        
        
    }
    public function getMigrationText() 
    {
        $str = $this->getDefaultText();

        $survey =  SurveyDocument::initFromFile($this->templateFile.".xml");

        $this->survey = $survey;
        $questions = $survey->getQuestions();
        
        $str = str_replace('DummyTable', $this->formatTableName( $survey->getName(), $survey->getVersion() ), $str);

        $str = str_replace('DummyClass', $this->formatClassName( $survey->getName(), $survey->getVersion() ), $str);

        $strQuestions = '';
        foreach( $questions as $question ) {
            $strQuestions .= "\n" . '$table->'.$this->setMigrationDataType($question->dataFormat)
                ."('".$question->variableName."')->nullable();";
        }
        $str = str_replace('INSERTSURVEY', $strQuestions, $str);
        
        return $str;
    }

    public function setMigrationDataType( $dataFormat ) 
    {
        
        switch( $dataFormat ) {
            case 'time':
                $return = 'time';
                break;
            case 'date':
                $return = 'date';
                break;
            case 'number':
                $return = 'integer';
                break;
            case 'text':
                $return = 'text';
                break;
            case 'varchar':
            case 'char':
            default:
                $return = 'string';
                break;
        }
        return $return;

    }

    public function formatTableName( $name, $version ) 
    {
        return 'rsp_'.strtolower( str_replace('-', '_', $name )).'_'.$version;
    }

    public function formatClassName( $name, $version ) 
    {
        return str_replace('-', '', str_replace('.', '', 'CreateSurveyRsp'.$name.$version ));
    }

    public function getDefaultText() 
    {

        return '<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DummyClass extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create(\'DummyTable\', function (Blueprint $table) {
            $table->increments(\'id\')->unsigned();
            $table->morphs(\'respondent\');
            $table->integer(\'survey_id\');
            INSERTSURVEY
            $table->string(\'last_page\');
            $table->integer(\'duration\');
            $table->timestamp(\'started_at\')->nullable();
            $table->timestamp(\'finalized_at\')->nullable();
            $table->timestamps();

            $table->foreign(\survey_id\')->references(\'id\')->on(\'surveys\')->onDelete(\'restrict\');
            $table->index([\'respondent_type\', \'respondent_id\', \'survey_id\']);
            $table->index([\'respondent_type\', \'respondent_id\']);
            $table->index([\'respondent_type\']);
            $table->index([\'survey_id\']);
            $table->index([\'started_at\', \'finalized_at\', \'survey_id\']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop(\'DummyTable\');
    }
}
';

    }
}