<?php

namespace Sirs\Console;

use App\User;
use App\DripEmailer;
use Illuminate\Console\Command;
use Sirs\SurveyDocument;

class CreateSurveyMigrationsFromTemplate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'surveys:create_migration {template}
                            {template: File location of survey template}';

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
     * Create a new command instance.
     *
     * @param  DripEmailer  $drip
     * @return void
     */
    public function __construct( $template)
    {
        parent::__construct();

        $this->templateFile = $template;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
        $contents = $this->getMigrationText();

        $bytes_written = File::put($file, $contents);
        if ($bytes_written === false)
        {
            die("Error writing to file");
        }
        
    }
    public function getMigrationText() 
    {
        $str = $this->getDefaultText();
        $survey =  \SurveyDocument::initFromFile('directory/'.$this->templateFile.".xml");
        $questions = $survey->getQuestions();
        dd($questions);
        $str = str_replace('DummyTable', $survey->getName(), $str);

        $strQuestions = ''
        foreach( $questions as $question ) {
            $strQuestion .= '\n' . '$table->'.$this->setMigrationDataType($question->type)
                ."('".$question->name."');";
        }
        $str = str_replace('INSERTSURVEY', $strQuestion, $str);


    }

    public function setMigrationDataType( $questionType ) 
    {
        
        switch( $questionType ) {
            case: 'time':
                $return = 'time'
                break;
            case 'date':
                $return = 'date'
                break;
            case 'number':
                $return = 'integer'
                break;
            case 'text':
            default:
                $return = 'string'
                break;
        }
        return $return;

    }

    public function getDefaultText() 
    {

        return '
        <?php

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