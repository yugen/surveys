<?php

namespace Sirs\Surveys\Test\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Sirs\Surveys\Models\Survey;
use Sirs\Surveys\Test\Stubs\Participant;
use Sirs\Surveys\Test\Stubs\User;
use Sirs\Surveys\Test\TestCase;

/**
* Test for the SurveyControlService
* @group survey-controller
* @group controllers
*/
class SurveyControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();
        $this->migrate();
        $this->p = factory(Participant::class)->create();
        $this->u = factory(User::class)->create();
        $this->svy = Survey::find(1);
        $this->startUri = '/sirs-surveys-test-stubs-participant/'.$this->p->id.'/survey/'.$this->svy->slug;
    }

    private function getPage()
    {
        return $this->withSession([])
                    ->call('GET', $this->startUri);
    }

    /**
     * @test
     */
    public function Asurvey_response_migration_ran()
    {
        $this->assertTrue(Schema::hasTable('rsp_a_1'));
        $this->assertDatabasehas('surveys', ['name'=>'A1']);
    }

    /**
     * @test
     */
    public function user_can_view_a_survey_page_for_a_participant()
    {
        $this->disableExceptionHandling();

        $this->getPage()->assertSuccessful();
    }

    /**
     * @test
     */
    public function shows_one_page_content_at_a_time()
    {
        $this->getPage()
            ->assertSeeText('THIS IS INTRO TEXT')
            ->assertSeeText('Page 1 Question 1')
            ->assertSeeText('Page 1 Question 2')
            ->assertDontSee('Page 2 Question 1')
            ->assertDontSee('Page 2 Question 2');
    }

    /**
     * @test
     */
    public function saves_response_data_for_page_when_nav_is_save()
    {
        $this->actingAs($this->u)
            ->call(
                'POST',
                $this->startUri,
                [
                    'p1q1' => 1,
                    'p1q2' => 'text answer',
                    'nav' => 'save'
                ]
            )->assertRedirect($this->startUri.'/1?page=page-1');

        $response = $this->svy->getLatestResponse($this->p);
        $this->assertNotNull($response->id);
        $this->assertEquals($response->p1q1, 1);
        $this->assertEquals($response->p1q2, 'text answer');
    }

    /**
     * @test
     */
    public function saves_response_data_and_advances_when_nav_is_next()
    {
        $this->actingAs($this->u)
            ->call(
                'POST',
                $this->startUri,
                [
                    'p1q1' => 2,
                    'p1q2' => 'text answer 2',
                    'nav' => 'next'
                ]
            )
            ->assertRedirect($this->startUri.'/1?page=page-2');

        $response = $this->svy->getLatestResponse($this->p);
        $this->assertNotNull($response->id);
        $this->assertEquals($response->p1q1, 2);
        $this->assertEquals($response->p1q2, 'text answer 2');
    }

    /**
     * @test
     */
    public function saves_response_data_and_goes_back_when_nav_is_prev()
    {
        $this->actingAs($this->u)
            ->call(
                'POST',
                $this->startUri.'/?page=page-2',
                [
                    'p2q1' => 2,
                    'p2q2' => 'text answer 2',
                    'nav' => 'prev'
                ]
            )
            ->assertRedirect($this->startUri.'/1?page=page-1');

        $response = $this->svy->getLatestResponse($this->p);
        $this->assertNotNull($response->id);
        $this->assertEquals($response->p2q1, 2);
        $this->assertEquals($response->p2q2, 'text answer 2');
    }

    /**
     * @test
     */
    public function saves_and_finalizes_and_redirects_response_when_nav_is_finalize()
    {
        $this->actingAs($this->u)
            ->withSession([])
            ->call(
                'POST',
                $this->startUri.'/?page=page-2',
                [
                    'p2q1' => 1,
                    'p2q2' => 9,
                    'nav' => 'finalize'
                ]
            )
            ->assertRedirect('http://localhost');

        $response = $this->svy->getLatestResponse($this->p);
        $this->assertNotNull($response->finalized_at);
        $this->assertEquals(1, $response->p2q1);
        $this->assertEquals(9, $response->p2q2);
    }

    /**
     * @test
     */
    public function saves_and_redirects_when_nav_is_save_exit()
    {
        $this->actingAs($this->u)
            ->withSession([])
            ->call(
                'POST',
                $this->startUri.'/?page=page-2',
                [
                    'p2q1' => 1,
                    'p2q2' => 9,
                    'nav' => 'save_exit'
                ]
            )
            ->assertRedirect('http://localhost');

        $response = $this->svy->getLatestResponse($this->p);
        $this->assertNotNull($response->id);
        $this->assertNull($response->finalized_at);
        $this->assertEquals(1, $response->p2q1);
        $this->assertEquals(9, $response->p2q2);
    }
    
    

    /**
     * @test
     */
    public function validates_response_before_saving_when_nav_is_next()
    {
        $this->actingAs($this->u)
            ->call(
                'POST',
                $this->startUri,
                [
                    'p1q1' => 'test',
                    'p1q2' => null,
                    'nav' => 'next'
                ]
            )
            ->assertSee('The p1q2 field is required.')
            ->assertSee('The p1q1 must be a number.');

        $response = $this->svy->getLatestResponse($this->p);
        $this->assertNull($response->id);
        $this->assertEquals($response->p1q1, null);
        $this->assertEquals($response->p1q2, null);
    }

    /**
     * @test
     */
    public function validates_response_before_saving_when_nav_is_finalize()
    {
        $this->actingAs($this->u)
            ->call(
                'POST',
                $this->startUri,
                [
                    'p1q1' => 'test',
                    'p1q2' => null,
                    'nav' => 'finalize'
                ]
            )
            ->assertSee('The p1q2 field is required.')
            ->assertSee('The p1q1 must be a number.');

        $response = $this->svy->getLatestResponse($this->p);
        $this->assertNull($response->id);
        $this->assertEquals($response->p1q1, null);
        $this->assertEquals($response->p1q2, null);
    }

    /**
     * @test
     */
    public function does_not_validate_response_before_saving_when_nav_is_save()
    {
        $this->actingAs($this->u)
            ->call(
                'POST',
                $this->startUri,
                [
                    'p1q1' => 2,
                    'p1q2' => null,
                    'nav' => 'save'
                ]
            )
            ->assertDontSee('The p1q2 field is required.');

        $response = $this->svy->getLatestResponse($this->p);
        $this->assertNotNull($response->id);
        $this->assertEquals($response->p1q1, 2);
        $this->assertEquals($response->p1q2, null);
    }


    /**
     * @test
     */
    public function does_not_validate_response_before_saving_when_nav_is_prev()
    {
        $this->actingAs($this->u)
            ->call(
                'POST',
                $this->startUri.'/?page=page-2',
                [
                    'p2q1' => null,
                    'p2q2' => 11,
                    'nav' => 'prev'
                ]
            )
            ->assertDontSee('The p2q1 field is required.');

        $response = $this->svy->getLatestResponse($this->p);
        $this->assertNotNull($response->id);
        $this->assertEquals($response->p2q1, null);
        $this->assertEquals($response->p2q2, 11);
    }
}
