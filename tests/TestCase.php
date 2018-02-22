<?php 

namespace Sirs\Surveys\Test;

use Orchestra\Testbench\TestCase as TestBenchCase;
use Sirs\Surveys\SurveysServiceProvider;

abstract class TestCase extends TestBenchCase
{
    protected function getPackageProviders($app)
    {
        return [SurveysServiceProvider::class];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }
}
