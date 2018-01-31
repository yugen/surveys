<?php 
namespace App\Surveys;

use Sirs\Surveys\SurveyRules;

class DummyClass extends SurveyRules
{
    /**
     * lifecycle hook called before any page in the survey is shown.
     * @return array Context associative array to be passed to view.
     */
    public function beforeShow()
    {
        $context = [];
        $context['dayOfWeek'] = today()->dayOfWeek();
        return $context;
    }

    /**
     * lifecycle hood called before save when any page is submitted
     * @return void
     */
    public function beforeSave()
    {
    }

    /**
     * lifecycle hood called after save when any page is submitted
     * @return void
     */
    public function afterSave()
    {
        // update the respondent's updated_at timestamp
        $this->respondent->touch();
    }

    /**
     * do custom navigation
     * @param  array $params ['page'=>'pageName', 'nav'=>'navType']
     * @return mixed int page number to navigate to or null for derfault navigation.
     */
    public function navigate($params)
    {
        if ($this->response->p2_q1 == 1 && $this->response->p2_q2 == 0) {
            return 1;
        }
        return null;
    }
}
