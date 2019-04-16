<?php
namespace Sirs\Surveys;

use Event;
use Carbon\Carbon;
use Sirs\Surveys\Events\SurveyResponseSaved;
use Sirs\Surveys\Events\SurveyResponseSaving;
use Sirs\Surveys\Events\SurveyResponseStarted;
use Sirs\Surveys\Events\SurveyResponseReopened;
use Sirs\Surveys\Events\SurveyResponseFinalized;
use Sirs\Surveys\Contracts\SurveyResponse as Response;

/**
 * participant model observer
 *
 * @package Change
 * @author
 **/
class SurveyResponseObserver
{
    use \Illuminate\Foundation\Bus\DispatchesJobs;

    public $eventsDispatcher;

    public function __construct()
    {
    }

    public function saving(Response $surveyResponse)
    {
        Event::disptach(new SurveyResponseSaving($surveyResponse));
    }

    public function saved(Response $surveyResponse)
    {
        Event::disptach(new SurveyResponseSaved($surveyResponse));

        // disptach started at event
        if ($surveyResponse->isDirty('started_at') && !is_null($surveyResponse->started_at)) {
            Event::disptach(new SurveyResponseStarted($surveyResponse));
        }

        // disptach finalized event or reopened event
        if ($surveyResponse->isDirty('finalized_at')) {
            if (!is_null($surveyResponse->finalized_at) && is_null($surveyResponse->getOriginal('finalize_at'))) {
                Event::disptach(new SurveyResponseFinalized($surveyResponse));
            } elseif (is_null($surveyResponse->finalized_at)) {
                Event::disptach(new SurveyResponseReopened($surveyResponse));
            }
        }
    }

    public function updated(Response $surveyResponse)
    {
    }
} // END class ParticipantObserver
