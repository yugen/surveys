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
	protected $fillable = ['name', 'version', 'file_name', 'table'];


	/**
	 * Retrieves a Survey record given a survey and version
	 *
	 * @return Survey Object
	 * @author SIRS
	 **/
	public function getSurveyVersion($survey, $version){
		return $this->where('name', $survey)->where('version', $version)->firstOrFail();
	}

	/**
	 * retrieves the record for the latest version of a given survey
	 *
	 * @return Survey Object
	 * @author SIRS
	 **/
	public function getLatestSurvey($survey){
		return $this->where('name', $survey)->orderBy('version', 'desc')->firstOrFail();
	}

	/**
	 * retrieves survey document object for a given survey
	 *
	 * @return Survey Document Object
	 * @author SIRS
	 **/
	public function getSurveyDocument()
	{
		return SurveyDocument::initFromFile($this->file_name);
	}

	/**
	 * documented function
	 *
	 * @return Response object(s)
	 * @author SIRS
	 **/
	public function getSurveyVersionResponses()
	{
		$response = new Response;
		return $response->getSurveyResponses($this->table_name);
		
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




}
  ?>