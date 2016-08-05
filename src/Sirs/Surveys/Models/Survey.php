<?php 

namespace Sirs\Surveys\Models;

use Cviebrock\EloquentSluggable\SluggableInterface;
use Cviebrock\EloquentSluggable\SluggableTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Sirs\Surveys\Documents\SurveyDocument;
use Sirs\Surveys\Models\Response;

class Survey extends Model implements SluggableInterface {

	use SluggableTrait;

	protected $table = "surveys";
	protected $fillable = ['name', 'version', 'file_name', 'response_table'];
	protected $sluggable = [
	'build_from' => 'name_version'
	];
	protected $document = null;


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
		if( is_null($this->document) ){
			if(Cache::has('survey-'.$this->slug)){
				print('<pre>');print_r('in the cache.  get it from there');print('</pre>');
				$this->document = Cache::get('survey-'.$this->slug);
			}else{
				print('<pre>');print_r('not in the cache.  load from file and cache it.');print('</pre>');
				$this->document = SurveyDocument::initFromFile( base_path($this->file_name) );
				Cache::put('survey-'.$this->slug, $this->document, 60);
			}
		}
		// dd($this->document);
		return $this->document;
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
	 * Get the title attribute from the survey document
	 *
	 * @return string
	 **/
	public function getTitleAttribute()
	{
		return $this->getSurveyDocument()->title;
	}

	/**
	 * Get the title attribute from the survey document
	 *
	 * @return string
	 **/
	public function getPagesAttribute()
	{
		return $this->getSurveyDocument()->pages;
	}

	/**
	 * Get the survey document as an attribute
	 *
	 * @return Sirs\Surveys\Documents\SurveyDocument
	 * @author 
	 **/
	public function getSurveyDocumentAttribute()
	{
		return $this->getSurveyDocument();
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

	public function getLatestResponse($respondentType, $respondentId = null, $responseId = null)
	{
		if ($respondentType instanceOf \Illuminate\Database\Eloquent\Model) {
			$respondent = $respondentType;
		}else{
			if(!$respondentId) throw new \Exception('You must provide a respondent id to get the latest response.');
			$respondentType = str_replace(' ', '\\', ucwords(str_replace('-', ' ', $respondentType)));
			$respondent = $respondentType::findOrFail($respondentId);
		}

		$response = null;
		if ( !is_null($responseId) ) {
			$response = $this->responses()->findOrFail($responseId);
			$response->setTable($this->response_table);
		}else{
			$responseQuery = $this->responses()
				->where('respondent_type', '=', get_class($respondent))
				->where('respondent_id', '=', $respondent->id)
				->orderBy('updated_at', 'DESC');
			$response = $responseQuery->get()->first();
			if( $response ){
				$response->setTable($this->response_table);
			}else{
				$response = Response::newResponse($this->response_table);
				$response->respondent_type = get_class($respondent);
				$response->respondent_id = $respondent->id;
			}
			$response->survey_id = $this->id;
		}
		return $response;
	}

	public function getRules(Response $response)
	{
		$rulesClassName = $this->getSurveyDocument()->getRulesClass();
		return new $rulesClassName($response);	
	}

}
?>