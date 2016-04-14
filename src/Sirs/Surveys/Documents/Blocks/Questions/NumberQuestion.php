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
            return (ctype_digit($bound) || preg_match('/^\d*\.?\d*$/', $bound));
        }elseif(is_null($bound)){
            return true;
        }
        return false;
    }

    protected function getValidationRules()
    {   
        $validations = parent::getValidationRules();
        $validations[] = 'numeric';
        if( $this->min !== null){
            if( $this->refusable ){
                $validations[] = 'refusableIntMin:'.$this->min;
            }else{
                $validations[] = 'intMin:'.$this->min;
            }
        }
        if( $this->max !== null){
            $validations[] = 'intMax:'.$this->max;
        }

        return $validations;
    }
}
