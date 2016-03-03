<?php 
namespace Sirs\Surveys;

use Illuminate\Support\ServiceProvider;
use Sirs\Surveys\Console\CreateSurveyMigrationsFromDocument;
use Sirs\Surveys\Console\CreateSurveyRulesFromDocument;
use Sirs\Surveys\Console\ValidateSurveyDefinition;
use Sirs\Surveys\Console\NewSurveyFromDocument;

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
    $this->app->singleton('command.survey.migration', function($app) {
      return new CreateSurveyMigrationsFromDocument();
    });
    $this->commands('command.survey.migration');

    $this->app->singleton('command.survey.rules', function($app) {
      return new CreateSurveyRulesFromDocument();
    });
    $this->commands('command.survey.rules');

    $this->app->singleton('command.survey.validate', function($app) {
      return new ValidateSurveyDefinition();
    });
    $this->commands('command.survey.validate');
    $this->app->singleton('command.survey.new', function($app) {
      return new NewSurveyFromDocument();
    });
    $this->commands('command.survey.new');
  }

  /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [

            'command.survey.migration',
            'command.survey.rules'
        ];
    }


}
