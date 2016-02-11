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
		return SurveyDocument::initFromFile($this->file_name);
	}

	/**
	 * get response object for a given survey
	 *
	 * @return Response object(s)
	 * @author SIRS
	 **/
	public function responses()
	{
		return Response::lookupTable($this->table_name);
	}

	/**
	 * get all responses for a given survey
	 *
	 * @return Response object(s)
	 * @author SIRS
	 **/
	public function getResponsesAttribute()
	{
		return Response::lookupTable($this->table_name)->get();
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