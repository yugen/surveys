<?php

namespace Sirs\Surveys\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;
use Illuminate\Database\Eloquent\Model;
use Sirs\Surveys\Contracts\SurveyModel;
use Sirs\Surveys\Contracts\SurveyResponse;
use Sirs\Surveys\Documents\SurveyDocument;

class Survey extends Model implements SurveyModel
{
    use Sluggable;
    use SluggableScopeHelpers;

    protected $table = "surveys";
    protected $fillable = ['name', 'version', 'file_name', 'response_table'];
    protected $document = null;

    public function sluggable()
    {
        return [
            'slug' => [
                'source'=>'name'
            ]
        ];
    }

    /**
     * Scope for name
     *
     * @return Query Builder Object
     * @author SIRS
     **/
    public function scopeName($query, $name)
    {
        return $query->where('name', $name);
    }

    /**
     * scope for Version
     *
     * @return Query Builder Object
     * @author SIRS
     **/
    public function scopeVersion($query, $version)
    {
        return $this->where('version', $version);
    }

    /**
     * scope for slug
     *
     * @return Query Builder Object
     * @author SIRS
     **/
    public function scopeSlug($query, $slug)
    {
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
        if (is_null($this->document)) {
            $this->document = SurveyDocument::initFromFile(base_path($this->file_name));
        }
        return $this->document;
    }

    public function getSurveyDocumentAttribute()
    {
        return $this->getSurveyDocument();
    }

    public function getDocumentAttribute()
    {
        return $this->getSurveyDocument();
    }

    /**
     * get response object for a given survey
     *
     * @return Response object(s)
     * @author SIRS
     **/
    public function responses()
    {
        return class_response()::lookupTable($this->response_table);
    }

    /**
     * get all responses for a given survey
     *
     * @return Response object(s)
     * @author SIRS
     **/
    public function getResponsesAttribute()
    {
        $responses =  class_response()::lookupTable($this->response_table)->get();
        foreach ($responses as $response) {
            $response->setTable($this->response_table);
        }
        return $responses;
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

    public function getLatestResponse($respondent, $responseId = null)
    {
        $response = null;

        if (!is_null($responseId)) {
            $response = $this->responses()->findOrFail($responseId);
            $response->setTable($this->response_table);
        } else {
            $responseQuery = $this->responses()
                ->where('respondent_type', '=', get_class($respondent))
                ->where('respondent_id', '=', $respondent->id)
                ->orderBy('updated_at', 'DESC');
            $response = $responseQuery->get()->first();
            if ($response) {
                $response->setTable($this->response_table);
            } else {
                $response = $this->getNewResponse($respondent);
            }
            $response->survey_id = $this->id;
        }
        return $response;
    }

    public function getNewResponse($respondent)
    {
        $response = class_response()::newResponse($this->response_table);
        $response->respondent_type = get_class($respondent);
        $response->respondent_id = $respondent->id;
        $response->survey_id = $this->id;
        return $response;
    }

    public function getPagesAttribute()
    {
        return $this->document->pages;
    }

    public function getRules(Response $response)
    {
        $rulesClassName = $this->getSurveyDocument()->getRulesClass();
        return new $rulesClassName($response);
    }


    /**
     * returns an array of questions
     *
     * @return array
     * @author SIRS
     **/
    public function getQuestions()
    {
        $doc = $this->getSurveyDocument();
        return $doc->getQuestions();
    }

    /**
     * undocumented function
     *
     * @return void
     * @author
     **/
    public function getReports()
    {
        $rsp = $this->responses;
        $questions = $this->getQuestions();
        $report = array();
        foreach ($questions as $q) {
            $report[$q->variableName] = $q->getReport($rsp);
        }
        return collect($report);
    }
}
