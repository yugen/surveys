<?php

namespace Sirs\Surveys\Documents\Blocks\Questions;

use Sirs\Surveys\Documents\Blocks\Questions\BoundedQuestion;

class NumberQuestion extends BoundedQuestion
{

    public function __construct($xml = null)
    {
        parent::__construct($xml);
        $this->defaultTemplate = 'questions.number.number';
        $this->defaultDataFormat = 'int';
    }

    public function boundaryIsValid($bound)
    {
        if( is_scalar($bound) ){
            return (ctype_digit($bound) || preg_match('/^\d*\.?\d*$/'));
        }elseif(is_null($bound)){
            return true;
        }
        return false;
    }

    protected function getValidationRules()
    {   
        $validations = parent::getValidationRules();
        if( $this->min !== null){
            $validations[] = 'min:'.$this->min;
        }
        if( $this->max !== null){
            $validations[] = 'max:'.$this->max;
        }

        return $validations;
    }
}
