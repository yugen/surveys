<?php 
namespace Sirs\Surveys;

use Carbon\Carbon;
use Event;
use Sirs\Surveys\Events\SurveyResponseFinalized;
use Sirs\Surveys\Events\SurveyResponseReopened;
use Sirs\Surveys\Events\SurveyResponseSaved;
use Sirs\Surveys\Events\SurveyResponseStarted;
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
    }

    public function saved(Response $surveyResponse)
    {
        Event::dispatch(new SurveyResponseSaved($surveyResponse));

        // dispatch started at event
        if ($surveyResponse->isDirty('started_at') && !is_null($surveyResponse->started_at)) {
            Event::dispatch(new SurveyResponseStarted($surveyResponse));
        }

        // dispatch finalized event or reopened event
        if ($surveyResponse->isDirty('finalized_at')) {
            if (!is_null($surveyResponse->finalized_at) && is_null($surveyResponse->getOriginal('finalize_at'))) {
                Event::dispatch(new SurveyResponseFinalized($surveyResponse));
            } elseif (is_null($surveyResponse->finalized_at)) {
                Event::dispatch(new SurveyResponseReopened($surveyResponse));
            }
        }
    }

    public function updated(Response $surveyResponse)
    {
    }
} // END class ParticipantObserver
