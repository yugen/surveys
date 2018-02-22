<?php 

namespace Sirs\Surveys;

/**
 * undocumented class
 *
 * @package default
 * @author 
 **/
class SurveyResponseService implements SurveyResponseServiceInterface
{
    protected function render($survey, $page, $pageIdx, $response, $respondent, $errors=null){
        $rules = $survey->getRules($response);
        $context = [
            'survey'=>[
                'name'=>$survey->name,
                'title'=>$survey->getSurveyDocument()->title,
                'version'=>$survey->version,
                'totalPages'=>count($survey->getSurveyDocument()->pages),
                'currentPageIdx'=>$pageIdx
            ],
            'respondent'=>$respondent,
            'response'=>$response
        ];
        if($errors){
            $context['errors'] = $errors;   
        }

        if( $ruleContext = $this->execRule($rules, $page->name, 'BeforeShow') ){
            $context = array_merge($context, $ruleContext);
        }

        return  $page->render($context); 
    }

    protected function setPreviousLocation(Request $request)
    {
        $previous = $request->session()->pull('survey_previous');
        if( !preg_match('/\/survey\//', URL::previous()) ){
            $previous = URL::previous();
        }
        $request->session()->put('survey_previous', $previous);
    }

    protected function redirect(Request $request, $rules)
    {
        // get the redirect url
        $redirectUrl = null;
        if( method_exists($rules, 'getRedirectUrl') ){
            $redirectUrl = $rules->getRedirectUrl();
        }
        if(!$redirectUrl){
            $redirectUrl = $request->session()->pull('survey_previous', '/');
            $request->session()->forget('survey_previous');
        }
        return redirect($redirectUrl);
    }
    
    protected function execRule($rulesObj, $pageName, $methodName, $params = null)
    {
       $method = $pageName . $methodName;
        if ( method_exists( $rulesObj, $method ) ) {
            if( $params ){
                return $rulesObj->$method($params);
            }
            return $rulesObj->$method();
        }else{
            return;
        }
    }

    protected function getRespondent($type, $id)
    {
        $className = str_replace(' ', '\\', ucwords(str_replace('-',' ',$type)));
        return $className::findOrFail($id);
    }

} // END class 