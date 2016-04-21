<?php 
namespace Sirs\Surveys;
use Carbon\Carbon;
use Event;
use Sirs\Surveys\Events\SurveyResponseFinalized;
use Sirs\Surveys\Events\SurveyResponseReopened;
use Sirs\Surveys\Events\SurveyResponseSaved;
use Sirs\Surveys\Events\SurveyResponseStarted;
use Sirs\Surveys\Models\Response;

/**
 * participant model observer
 *
 * @package Change
 * @author 
 **/
class SurveyResponseObserver
{
  use \Illuminate\Foundation\Bus\DispatchesJobs;

  var $eventsDispatcher;

  public function __construct(){
  }

  public function saving(Response $surveyResponse){
    // set the started_at
    if( is_null($surveyResponse->started_at) ){
      $surveyResponse->started_at = new Carbon();
    }
    
  }

  public function saved(Response $surveyResponse){
    Event::fire(new SurveyResponseSaved($surveyResponse));

    // fire started at event
    if( $surveyResponse->isDirty('started_at') && !is_null($surveyResponse->started_at) ){
      Event::fire(new SurveyResponseStarted($surveyResponse));
    }

    // fire finalized event or reopened event
    if( $surveyResponse->isDirty('finalized_at')){
      if( !is_null($surveyResponse->finalized_at) ){
        Event::fire(new SurveyResponseFinalized($surveyResponse));
      }else{
        Event::fire(new SurveyResponseReopened($surveyResponse));
      }
    }
  }

  public function updated(Response $surveyResponse){
  }
} // END class ParticipantObserver

?>