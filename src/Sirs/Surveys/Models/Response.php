<?php namespace Sirs\Surveys\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
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
        $dataCols = $this->getDataAttributeNames();
        foreach ($dataCols as $column) {
            $data[$column] = $this->{$column};
        }
        return $data;

    }

    /**
     * Sets the values of the dataAttributes from an associative array
     *
     * @return void
     * @param Array $data Associative array of data field=>value
     **/
    function setDataValues($data, $page)
    {
        // $page = $this->survey->survey_document->getPage($page);
        $pageVariables = collect($page->getVariables())->keyBy('name');

        foreach ($data as $key => $value) {
            if( in_array($key, $pageVariables->keys()->all()) ){
                $this->$key = ($value == '') ? null : $value;
            }
        }

        $dataKeys = array_keys($data);
        foreach( $pageVariables as $idx => $pageVar ){
            if(!in_array($pageVar->name, $dataKeys) ){
                $this->{$pageVar->name} = null;
            }
        }
    }

    /**
     * gets the field names for data attributes
     *
     * @return void
     * @author 
     **/
    public function getDataAttributeNames()
    {
        $cols = DB::getSchemaBuilder()->getColumnListing($this->table);
        foreach ($cols as $idx => $column) {
            if( in_array($column, ['id','respondent_id', 'respondent_type', 'survey_id', 'last_page', 'created_at', 'updated_at']) ){
                unset($cols[$idx]);
            }
        }
        return $cols;
    }
}
