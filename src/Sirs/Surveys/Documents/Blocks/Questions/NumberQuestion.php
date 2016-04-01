<?php

namespace Sirs\Surveys\Documents\Blocks\Questions;

use Sirs\Surveys\Documents\Blocks\Questions\BoundedQuestion;

class NumberQuestion extends BoundedQuestion
{

    public function __construct($xml = null)
    {
        $this->defaultTemplate = 'questions/number/default.blade.php';
        $this->defaultDataFormat = 'int';
        parent::__construct($xml);
    }

    public function boundaryIsValid($bound)
    {
        if( is_scalar($bound) ){
            return (ctype_digit($bound));
        }elseif(is_null($bound)){
            return true;
        }
        dd($bound);
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
