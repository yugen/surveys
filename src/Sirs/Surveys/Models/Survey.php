<?php 

namespace Sirs\Surveys\Models;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Sirs\Surveys\Documents\SurveyDocument;
use Sirs\Surveys\Models\Response;

class Survey extends Model implements SluggableInterface {

	use SluggableTrait;

	protected $table = "surveys";
	protected $fillable = ['name', 'version', 'file_name', 'response_table'];
	protected $sluggable = [
		'build_from' => 'name_version'
	];


	/**
	 * Scope for name
	 *
	 * @return Query Builder Object
	 * @author SIRS
	 **/
	public function scopeName($query, $name){
		return $query->where('name', $name);
	}

	/**
	 * scope for Version
	 *
	 * @return Query Builder Object
	 * @author SIRS
	 **/
	public function scopeVersion($query, $version){
		return $this->where('version', $version);
	}

	/**
	 * scope for slug
	 *
	 * @return Query Builder Object
	 * @author SIRS
	 **/
	public function scopeSlug($query, $slug){
		return $query->where('slug', $slug);
	}
	/**
	 * retrieves survey document object for a given survey
	 *
	 * @return Survey Document Object
	 * @author SIRS
	 **/
	public function getSurveyDocument()
	{
		return SurveyDocument::initFromFile( base_path($this->file_name) );
	}

	/**
	 * get response object for a given survey
	 *
	 * @return Response object(s)
	 * @author SIRS
	 **/
	public function responses()
	{
		return Response::lookupTable($this->response_table);
	}

	/**
	 * get all responses for a given survey
	 *
	 * @return Response object(s)
	 * @author SIRS
	 **/
	public function getResponsesAttribute()
	{
		return Response::lookupTable($this->response_table)->get();
	}

	public function getNameVersionAttribute()
	{
		return $this->name.$this->version;
	}

	/**
	 * Gets the validation rules for a given survey in a format acceptable for laravel validation
	 *
	 * @return array
	 * @author SIRS
	 **/
	public function getValidationRules()
	{
		
	}

	public function getLatestResponse($respondentType, $respondentId, $responseId)
	{
      $response = null;
      if ( !is_null($responseId) ) {
          $response = $this->responses()->findOrFail($responseId);
        	$response->setTable($this->response_table);
      }elseif($this->responses->count() > 0){
					$response = $this->responses()
						->where('respondent_type', '=', $respondentType)
						->where('respondent_id', '=', $respondentId)
						->orderBy('updated_at', 'DESC')->first();
        	$response->setTable($this->response_table);
      }else{
      	$response = null;
	    }
      return $response;
	}

	public function getRules(Response $response){
	  $rulesClassName = 'App\\Surveys\\'.$this->slug."Rules";
    return new $rulesClassName($this, $response);	
 	}

}
  ?>