<?php namespace Sirs\Surveys\Models;

use Illuminate\Database\Eloquent\Model;

class Response extends Model {

	protected $table = null;
  protected $guarded = ['id', 'finalized_at', 'survey_id'];

  protected $surveyName = null;
  protected $surveyVersion = null;

  /**
   * set up response to pull from the correct table
   *
   * @param string $surveyName Name of survey
   * @param string #surveyVersion Version of the survey
   * @return void
   */
  public function setSurveyVersion($surveyName, $versionNumber = null) 
  {
    $survey = Survey;

    
    $survey->where('name', '=', $surveyName);
    $this->surveyName = $surveyName;
    
    if ( $surveyVersion ) 
    {
      $survey->where('version', '=', $versionNumber);
      $this->surveyVersion = $versionNumber;
    }
      
    $sr = $survey->orderBy('version', 'DESC')->firstOrFail();
    $this->survey_id = $sr->id;
    $this->table = $sr->table;
  }
  /**
   * allows you to statically intialize Response
   * @param string $surveyName ame of survey
   * @param string  $versionNumber Version of the survey
   *
   * @return void
   */
  public static function surveyVersion($surveyName, $versionNumber = null) 
  {
    $instance = new static;
    $instance->setSurveyVersion($surveyName, $versionNumber);
    return $instance->newQuery();
  }
  /**
   * used when statically initiating the model
   * @param array $attributes
   * @param bool  $exists 
   *
   * @return void
   */
  // this allows Response to be called statically, i.e. Resonse::surveyVersion('Baseline', 2)->findOrFail
  
  public function newInstance($attributes = array(), $exists = false)
  {
      $model = parent::newInstance($attributes, $exists);
      $model->setSurveyVersion($this->surveyName, $this->surveyVersion);
      return $model;
  }

  /**
   * finalize survey if it has not already been finalized
   * @param string $finalizeDate date string of time to set finalized_at to
   * @param bool  $override allow setting a new finalized_at date even if one already exists
   *
   * @return void
   * @example 
   *    $responses  = Response;
   *    $responses->setSurveyVersion('Baseline')->get();
   *    
   *    $response = Response::surveyVersion('Baseline', 3)->findOrFail(4);
  */
  public function finalizeResponse( $finalizeDate = null, $override = false ) 
  {
    if( $this->finalized_at == null || $override == true ) 
    {
      if ( $finalizeDate ) 
      {
        $this->finalized_at = date( strtotime( $finalizeDate ));
      } else 
      {
        $this->finalized_at = date();
      }
      
      $this->save();
    } else if ( $override == false ) 
    {
      throw new \Sirs\Surveys\Exceptions\ResponseException("Tried to set finalized_at when it is already set");
    }
  }


  public function survey()
  {
      
    return $this->belongsTo('Sirs\Survey\Survey');
  }

  public function respondant()
  {
    return $this->morphTo();
  }


}
