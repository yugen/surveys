<?php

namespace Sirs\Surveys\Tests\Unit\Models;

use Sirs\Surveys\Models\Survey;
use Sirs\Surveys\Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SurveyTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $svy = Survey::create([
            'name' => 'test',
            'file_name' => 'tests/files_test_survey_one.xml',
            'version' => 1,
            'response_table' => 'rsp_test_1'
        ]);
    }

    /**
     * @test
     */
    public function record_created()
    {
        $this->assertEquals(DB::table('surveys')->count(), 1);
    }
}
