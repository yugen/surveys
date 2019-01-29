<?php

namespace Sirs\Surveys\Console;

use File;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Sirs\Surveys\Documents\SurveyDocument;

class CreateSurveyRules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:survey-rules 
                            {className : Name of the rules class}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new survey rules class';

    /**
     * The console command option for class name
     *
     * @var string
     */
    protected $className = null;

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->className = $this->argument('className');
        $dir = config('surveys.rulesPath');
        $filename =  $dir.'/'.$this->className .'.php';

        if (!File::exists($dir)) {
            File::makeDirectory($dir, 0775, true);
        }

        if (File::exists($filename)) {
            $this->info('Cannot create the rules class '.$this->className.'. File '.$filename.' already exists.');
        }

        $bytes_written = File::put($filename, $this->getRulesText());

        if ($bytes_written === false) {
            throw new Exception("Error writing to file");
        } else {
            $testName = 'Surveys/'.$this->className.'Test';
            Artisan::call('make:test', ['name'=>$testName]);
            $this->info('Created ' . $filename.' and test, tests/Feature/Surveys'.$testName);
        }
    }

    public function getRulesText()
    {
        $str = str_replace(
            'DummyClass',
            $this->className,
            file_get_contents(__DIR__.'/stubs/rules.stub')
        );
       
        return $str;
    }
}
