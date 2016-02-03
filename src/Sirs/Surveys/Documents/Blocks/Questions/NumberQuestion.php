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
            return (is_int($bound) || preg_match('/^\d?$/', $bound));
        }
        return false;
    }
}
