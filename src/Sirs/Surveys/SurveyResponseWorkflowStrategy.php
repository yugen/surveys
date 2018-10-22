<?php

namespace Sirs\Surveys;

use Sirs\Surveys\Events\SurveyResponseEvent;
use Sirs\Surveys\Contracts\WorkflowStrategy;
use Sirs\Surveys\Models\Response;

/**
 * A strategy for handling all events for a given Survey
 *
 * @package Sirs\Surveys
 **/
class SurveyResponseWorkflowStrategy implements WorkflowStrategy
{
    protected $response;
    protected $event;


    /**
     * Constructor
     * @param SurveyResponse $response  The Survey for which the SurveyResponseEvent was fired
     * @param Events\SurveyResponseEvent $event
     */
    public function __construct(Response $response, SurveyResponseEvent $event)
    {
        $this->response = $response;
        $this->event = $event;
    }

    /**
     * Determines and executes the appropriate instance method based on SurveyResponseEvent type.
     * @return void
     */
    public function run()
    {
        $reflection = new \ReflectionClass(get_class($this->event));

        $method = lcfirst(substr($reflection->getShortName(), strlen('SurveyResponse')));
        
        if (method_exists($this, $method)) {
            $this->$method($this->response);
        }
    }

    public function finalized()
    {
    }

    public function reopened()
    {
    }

    public function saved()
    {
    }

    public function started()
    {
    }
} // END class SurveyTypeWorkflowStrategy
