<?php 
namespace Sirs\Surveys;

use Illuminate\Support\ServiceProvider;
use Sirs\Surveys\Console\CreateSurveyMigrationsFromTemplate;

class SurveysServiceProvider extends ServiceProvider {

  /**
   * Bootstrap the application services.
   *
   * @return void
   */
  public function boot()
  {
    
    $this->loadViewsFrom(__DIR__.'/Views', 'surveys');

    $this->publishes([ __DIR__.'/Config/config.php' => config_path('surveys.php') ], 'config');
    $this->publishes([ __DIR__.'/database/migrations/' => database_path('/migrations') ], 'migrations');

    // include __DIR__.'/routes.php';

  }

  /**
   * Register the application services.
   *
   * @return void
   */
  public function register()
  {
    $this->app->singleton('command.surveys.create_migration', function($app) {
            
            return new CreateSurveyMigrationsFromTemplate();
        });
    $this->commands('command.surveys.create_migration');
  }

  /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [

            'command.surveys.create_migration'
        ];
    }


}
