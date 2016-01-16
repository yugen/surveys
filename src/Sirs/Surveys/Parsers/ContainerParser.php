<?php

namespace Sirs\Surveys\Parsers;

use Sirs\Surveys\Container;
use Sirs\Surveys\Parsers\Parser;

class ContainerParser extends Parser
{


    public function parse(\SimpleXmlElement $element)
    {
        $container = [];
        $name = $this->getAttribute($element, 'name');
        $class = $this->getAttribute($element, 'class');
        $id = $this->getAttribute($element, 'id');
        $contents = [];
        $template = '';

        return new Container($name, $contents, $class, $id, $template);
    }

}
