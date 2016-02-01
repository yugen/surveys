<?php 
namespace Sirs\Surveys;

use Illuminate\Support\ServiceProvider;

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
    
  }


}
