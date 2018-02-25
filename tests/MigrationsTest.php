<?php 

namespace Sirs\Surveys\Test;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

/**
* Initail test to see if this even works
*/
class MigrationsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->artisan('migrate', ['--database' => 'testbench']);
    }

    /**
     * @test
     */
    public function migrations_creates_surveys_table()
    {
        $this->assertTrue(Schema::hasTable('surveys'));
    }

    /**
     * @test
     */
    public function migrations_creates_responses_table()
    {
        $this->assertTrue(Schema::hasTable('responses'));
        $this->assertTrue(Schema::hasColumn('responses', 'survey_id'));
        $this->assertTrue(Schema::hasColumn('responses', 'respondent_id'));
        $this->assertTrue(Schema::hasColumn('responses', 'respondent_type'));
        $this->assertTrue(Schema::hasColumn('responses', 'response_data'));
        $this->assertTrue(Schema::hasColumn('responses', 'started_at'));
        $this->assertTrue(Schema::hasColumn('responses', 'finalized_at'));
        $this->assertTrue(Schema::hasColumn('responses', 'last_page'));
        $this->assertTrue(Schema::hasColumn('responses', 'created_at'));
        $this->assertTrue(Schema::hasColumn('responses', 'updated_at'));
    }
}
