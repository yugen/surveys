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
    protected $response;
    public $pretext;

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

    public function setPretext(Request $request){
        $pretext = $request->session()->get('pretext', []);

        $this->pretext = (isset($pretext[$this->response->survey->slug][$this->response->respondent_id])) 
                            ? $pretext[$this->response->survey->slug][$this->response->respondent_id]
                            : new RulesPretext([]);

        foreach ($request->all() as $key => $value) {
            if(in_array($key, ['_token'])) continue;
            if(in_array($key, array_keys($this->response->getDataAttributes()))) continue;
            if(in_array(preg_replace('/(_refused)|(_field)/', '', $key), array_keys($this->response->getDataAttributes()))) continue;

            $this->pretext->{$key} = $value;
        }
        if (!isset($pretext[$this->response->survey->slug])) {
            $pretext[$this->response->survey->slug] = [
                $this->response->respondent_id => $this->pretext
            ];
        }else{
            $pretext[$this->response->survey->slug][$this->response->respondent_id] = $this->pretext;
        }

        $request->session()->put('pretext', $pretext);
    }

    public function forgetPretext()
    {
        $sessionPretext = session()->get('pretext');
        if(isset($sessionPretext[$this->response->survey->slug][$this->response->respondent_id])){
            unset($sessionPretext[$this->response->survey->slug][$this->response->respondent_id]);
            session()->put('pretext', $sessionPretext);
        }
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
} // END class SurveyRules