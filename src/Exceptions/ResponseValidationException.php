<?php
namespace Sirs\Surveys\Exceptions;

/**
* ResponseValidationException
*/
class ResponseValidationException extends \Exception
{
    public function __construct($errors, $code = 400)
    {
        $this->message = 'There was a problem with the data submitted.';
        $this->errors = $errors;
        $this->code = $code;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
