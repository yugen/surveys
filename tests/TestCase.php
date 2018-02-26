<?php 

namespace Sirs\Surveys\Test;

use Cviebrock\EloquentSluggable\ServiceProvider;
use Illuminate\Foundation\Exceptions\Handler;
use Orchestra\Testbench\TestCase as TestBenchCase;
use Sirs\Surveys\SurveysServiceProvider;
use Sirs\Surveys\Test\Stubs\User;

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
        $this->loadMigrationsFrom([
            '--realpath' => realpath(__DIR__.'/database/migrations'),
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
        $app['config']->set('app.debug', true);
        $app['config']->set('surveys.surveysPath', __DIR__.'/files/resources/surveys/');
        $app['config']->set('surveys.rulesNamespace', 'Sirs\\Surveys\\Test\Stubs\\Surveys\\');
        $app['config']->set('surveys.rendererConfig.cache_path', __DIR__.'/cache');
        $app['config']->set('surveys.chromeTemplate', 'surveys::layouts.app');

        $app['config']->set('auth.providers.users.model', User::class);
    }

    protected function disableExceptionHandling()
    {
        $this->app->instance(ExceptionHandler::class, new class extends Handler {
            public function __construct()
            {
            }
            public function report(\Exception $e)
            {
            }
            public function render($request, \Exception $e)
            {
                throw $e;
            }
        });
    }
}
