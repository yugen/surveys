<?php


namespace Sirs\Surveys;

use Illuminate\Http\Request;

/**
 * Base Survey Rules Class
 *
 * @package sirs/surveys
 * @author
 **/
class SurveyRules
{
    public $pretext;
    protected $response;

    /**
     * constructs the survey
     *
     * @return void
     * @author
     **/
    public function __construct($response)
    {
        $this->response = $response;
    }

    /**
     * Overrides get to provide attribute style access
     *
     * @return void
     * @author
     **/
    public function __get($attr)
    {
        switch ($attr) {
            case 'surveyDocument':
            case 'surveyDoc':
                return $this->response->survey->getSurveyDocument();
                break;
            case 'survey':
                return $this->response->survey;
                break;
            case 'respondent':
                return $this->response->respondent;
                break;
            default:
                return $this->{$attr};
                break;
        }
    }

    public function setPretext($requestData)
    {
        $pretext = session()->get('pretext', []);

        $this->pretext = (isset($pretext[$this->response->survey->slug][$this->response->respondent_id]))
                            ? $pretext[$this->response->survey->slug][$this->response->respondent_id]
                            : new RulesPretext([]);

        // set the pretext->page to the response->last_page in case it's not coming in on the request
        $this->pretext->page = $this->response->last_page;

        foreach ($requestData as $key => $value) {
            if (in_array($key, ['_token'])) {
                continue;
            }
            if (in_array($key, array_keys($this->response->getDataAttributes()))) {
                continue;
            }
            if (in_array(preg_replace('/(_refused)|(_field)/', '', $key), array_keys($this->response->getDataAttributes()))) {
                continue;
            }

            $this->pretext->{$key} = $value;
        }
        if (!isset($pretext[$this->response->survey->slug])) {
            $pretext[$this->response->survey->slug] = [
                $this->response->respondent_id => $this->pretext
            ];
        } else {
            $pretext[$this->response->survey->slug][$this->response->respondent_id] = $this->pretext;
        }

        session()->put('pretext', $pretext);
    }

    public function forgetPretext()
    {
        $sessionPretext = session()->get('pretext');
        if (isset($sessionPretext[$this->response->survey->slug][$this->response->respondent_id])) {
            unset($sessionPretext[$this->response->survey->slug][$this->response->respondent_id]);
            session()->put('pretext', $sessionPretext);
        }
    }
} // END class SurveyRules
