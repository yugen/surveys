<?php 

namespace Sirs\Surveys\Contracts;

/**
 * Defines interface for task workflow strategies.
 *
 * @package Sirs\Surveys
 **/
interface WorkflowStrategy
{
    /*
     * Constructor
     * @param Sirs\Surveys\Survey $response
     * @param Sirs\Surveys\Events\SurveyEvent $event
     */
    public function __construct(\Sirs\Surveys\Models\Response $response, \Sirs\Surveys\Events\SurveyResponseEvent $event);

    /*
     * runs the appropriate method based on $this->task and $this->event
     */
    public function run();


    public function started();

    public function saved();

    public function finalized();

    public function reopened();
} // END interface SurveyWorkflowStrategyInterface
