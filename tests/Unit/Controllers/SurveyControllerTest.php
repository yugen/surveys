<?php

namespace Sirs\Surveys\Test\Unit;

use Sirs\Surveys\Test\TestCase;

/**
* Test for the SurveyControlService
*/
class SurveyControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $survey = factory(\Sirs\Surveys\Survey::class)->create([
            'name' => 'Survey A',
        ]);
    }
}
