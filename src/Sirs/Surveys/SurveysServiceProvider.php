<?php 
namespace Sirs\Surveys;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Sirs\Surveys\Console\CreateSurveyMigrationsFromDocument;
use Sirs\Surveys\Console\CreateSurveyRulesFromDocument;
use Sirs\Surveys\Console\CreateSurveyXml;
use Sirs\Surveys\Console\NewSurveyFromDocument;
use Sirs\Surveys\Console\ValidateSurveyDefinition;
use Sirs\Surveys\Models\Response;

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
    $this->publishes([ __DIR__.'/database/migrations/' => database_path('/migrations') ], 'migrations');
    $this->publishes([ __DIR__.'/../../../assets/sass/'=> base_path('/resources/assets/sass')], 'sass');
    $this->publishes([ __DIR__.'/../../../assets/js/'=> public_path('/js')], 'js');


    // custom validators for numbers
    Validator::replacer('intMin', function($message, $attribute, $rule, $parameters) {
      if($message == 'validation.int_min'){
        $message = 'Must be at least :min';
      }
      return str_replace(':min', $parameters[0], 'Must be greater than or equal to :min');
    });
    Validator::extend('intMin', function($attribute, $value, $parameters, $validator) {
      return ((int)$value >= (int)$parameters[0]);
    });
    Validator::replacer('intMin', function($message, $attribute, $rule, $parameters) {
      if($message == 'validation.int_min'){
        $message = 'Must be at least :min';
      }
      return str_replace(':min', $parameters[0], 'Must be greater than or equal to :min');
    });
    Validator::extend('refusableIntMin', function($attribute, $value, $parameters, $validator) {
      return ((int)$value >= (int)$parameters[0] || (int)$value == -77);
    });
    Validator::replacer('refusableIntMin', function($message, $attribute, $rule, $parameters) {
      if($message == 'validation.refusable_int_min'){
        $message = 'Must be greater than or equal to :min or -77 (refused)';
      }
      return str_replace(':min', $parameters[0], 'Must be greater than or equal to :min or -77 (refused)');
    });
    Validator::extend('intMax', function($attribute, $value, $parameters, $validator) {
      return ((int)$value <= (int)$parameters[0]);
    });
    Validator::replacer('intMax', function($message, $attribute, $rule, $parameters) {
      if($message == 'validation.int_max'){
        $message = 'Must be at less than or equal to :max';
      }
      return str_replace(':max', $parameters[0], $message);
    });

    // register observers:
    Response::observe(new SurveyResponseObserver);    

    require __DIR__.'/routes.php';

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

    $this->commands([
        CreateSurveyXml::class,
    ]);
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
