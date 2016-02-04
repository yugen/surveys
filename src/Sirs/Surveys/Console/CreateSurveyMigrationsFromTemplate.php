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
    protected $signature = 'surveys:create_migration 
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
        $filename =  'database/migrations/0000_00_00_000000_create_survey'
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
        
        $str = str_replace('DummyTable', $survey->getName(), $str);

        $str = str_replace('DummyClass', str_replace('-', '', str_replace('.', '', 'CreateSurvey'.$survey->getName().$survey->getVersion())), $str);

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
        Schema::dropIfExists(\'DummyTable\');

        Schema::create(\'DummyTable\', function (Blueprint $table) {
            $table->increments(\'id\');
            $table->morphs(\'respondent\');
            INSERTSURVEY
            $table->string(\'last_page\');
            $table->integer(\'duration_seconds\');
            $table->timestamp(\'started_at\')->nullable();
            $table->timestamp(\'finalized_at\')->nullable();
            $table->timestamps();
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