<?php 
namespace Sirs\Surveys\Handlers;

use Bus;
use Illuminate\Contracts\Queue\ShouldBeQueued;
use Illuminate\Queue\InteractsWithQueue;
use Sirs\Surveys\Events\SurveyResponseEvent;

/**
 * Event Listener that calls appropriate workflow strategy if it exists
 */
class RunWorkflow
{

  /**
   * Create the event handler.
   *
   * @return void
   */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Sirs\Surveys\Events\AppointmentEvent  $event
     * @return void
     */
    public function handle(SurveyResponseEvent $event)
    {
        $response = $event->surveyResponse;

        $workflowClassName = 'App\\Surveys\\Workflows\\'.ucfirst(camel_case($response->survey->slug).'WorkflowStrategy');
        if (class_exists($workflowClassName)) {
            $workflow = new $workflowClassName($response, $event);
            $workflow->run();
        }
    }
}
