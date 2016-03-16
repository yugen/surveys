<?php namespace Sirs\Surveys\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Sirs\Surveys\Exceptions\ResponsePreviouslyFinalizedException;

class Response extends Model {

	protected $table = null;
  protected $guarded = ['id', 'finalized_at', 'survey_id'];

  protected $name = null;
  protected $version = null;


 /**
   * allows you to statically intialize Response
   * @param string $surveyName ame of survey
   * @param string  $versionNumber Version of the survey
   *
   * @return void
   */
  public static function lookupTable($table) 
  {
    // $instance = new static;
    // $instance->setTable($table);
    $instance = static::newResponse($table);
    return $instance->newQuery();
  }

  public static function newResponse($table)
  {
    $instance = new static;
    $instance->setTable($table);
    return $instance;
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
  public function finalizeResponse( Carbon $finalizeDate = null, $override = false ) 
  {
    if( $this->finalized_at == null || $override == true ){
      if ( $finalizeDate )      {
        $this->finalized_at = $finalizeDate;
      }else{
        $this->finalized_at = new Carbon();
      }
      
      $this->save();
    }elseif( $override == false ){
      // throw new ResponsePreviouslyFinalizedException($this);
    }
  }

  public function finalize(Carbon $finalizedDate = null, $override = false)
  {
    return $this->finalizeResponse($finalizedDate, $override);
  }


  public function survey()
  {
    return $this->belongsTo(Survey::class);
  }

  public function respondent()
  {
    return $this->morphTo();
  }

  public function getDataAttributes()
  {
    $data = [];
    $data['id'] = $this->id;
    $data['respondent'] = $this->respondent->full_name.' - id: '.$this->respondent->id;
    foreach( $this->getAttributes() as $key => $value ){
      if( in_array($key, ['respondent_id', 'respondent_type', 'survey_id', 'last_page']) ){
        continue;
      }
      $data[$key] = $value;
    }
    return $data;

  }


}
