<?php namespace Sirs\Surveys\Models;

use Illuminate\Database\Eloquent\Model;

class Response extends Model {

	protected $table = null;
  protected $guarded = ['id', 'finalized_at', 'survey_id'];

  protected $name = null;
  protected $version = null;

  
  /**
   * sets table given input
   *
   * @return void
   * @author SIRS
   **/
  public function setTable($table)
  {
    $this->table = $table;
  }

  /**
   * gets all responses for a given table
   *
   * @return responses
   * @author SIRS
   **/
  public function getSurveyResponses($table)
  {
    $this->setTable($table);
    $responses = $this->get();
    return $responses;
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
