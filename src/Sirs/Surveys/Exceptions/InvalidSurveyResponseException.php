<?php
namespace Sirs\Surveys\Exceptions;

class InvalidSurveyResponseException extends \Exception
{
    public function __construct($errors, $message = null, $code=null)
    {
        $this->message = $this->makeMessage($errors);
        $this->code = $code;
    }

    protected function makeMessage($errors)
    {
        $message = 'Invalid Survey Response: There were problems with the response: '."\n";
        foreach ($errors->getMessages() as $key => $value) {
            foreach ($value as $idx => $message) {
                $message .= "\n\t".$message;
            }
        }
        return $message;
    }
}
