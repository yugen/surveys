<?php 

namespace Sirs\Surveys\Test;

use Cviebrock\EloquentSluggable\ServiceProvider;
use Orchestra\Testbench\TestCase as TestBenchCase;
use Sirs\Surveys\SurveysServiceProvider;

abstract class TestCase extends TestBenchCase
{
    public function setUp()
    {
        parent::setUp();
        $this->withFactories(__DIR__.'/factories');
    }

    protected function migrate()
    {
        $this->artisan('migrate');
        $this->loadLaravelMigrations();
        $this->loadMigrationsFrom([
            '--realpath' => realpath(__DIR__.'/../vendor/venturecraft/revisionable/src/migrations'),
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [SurveysServiceProvider::class, ServiceProvider::class];
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
