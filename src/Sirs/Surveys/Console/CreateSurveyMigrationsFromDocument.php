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

        $questions = $this->survey->getVariables();
        
        $str = str_replace('DummyTable', $this->formatTableName( $this->survey->getName(), $this->survey->getVersion() ), $str);

        $str = str_replace('DummyClass', $this->formatClassName( $this->survey->getName(), $this->survey->getVersion() ), $str);

        $str = str_replace('DummyName', $this->survey->getName(), $str);

        $str = str_replace('DummySlug', str_replace(' ', '_', $this->survey->getName()), $str);

        $str = str_replace('DummyVersion',  $this->survey->getVersion(), $str);

        if( !preg_match('/\.xml$/', $this->documentFile) ){
            $this->documentFile .= '.xml';
        }
        $str = str_replace('DummyFileName', $this->documentFile, $str);

        $strQuestions = '';
        foreach( $questions as $question ) {
            $strQuestions .= "\n" . '$table->'.$this->setMigrationDataType($question->dataFormat)
                ."('".$question->name."')->nullable();";
        }
        $str = str_replace('INSERTSURVEY', $strQuestions, $str);
        
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
        return str_replace('-', '', str_replace('.', '', 'CreateSurveyRsp'.ucfirst($name).$version ));
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
            $table->integer(\'survey_id\')->unsigned();
            INSERTSURVEY
            $table->string(\'last_page\');
            $table->integer(\'duration\');
            $table->timestamp(\'started_at\')->nullable();
            $table->timestamp(\'finalized_at\')->nullable();
            $table->timestamps();

            $table->foreign(\'survey_id\')->references(\'id\')->on(\'surveys\')->onDelete(\'restrict\');
            //$table->index([\'respondent_type\', \'respondent_id\', \'survey_id\']);
            $table->index([\'respondent_type\', \'respondent_id\']);
            $table->index([\'respondent_type\']);
            $table->index([\'survey_id\']);
            $table->index([\'started_at\', \'finalized_at\', \'survey_id\']);
        });

        \Sirs\Surveys\Models\Survey::firstOrCreate(["name"=>"DummyName", "version"=>"DummyVersion", "slug"=>"DummySlug", "file_name"=>"DummyFileName", "response_table"=>"DummyTable"]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop(\'DummyTable\');
        $s = \Sirs\Surveys\Models\Survey::where(\'name\', \'DummyName\')->where(\'version\', \'DummyVersion\');
        $s->delete();
    }
}
';

    }
}