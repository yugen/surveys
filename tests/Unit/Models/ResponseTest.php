<?php 

namespace Sirs\Surveys\Test\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Sirs\Surveys\Models\Response;
use Sirs\Surveys\Models\Survey;
use Sirs\Surveys\Test\Stubs\Participant;
use Sirs\Surveys\Test\Stubs\User;
use Sirs\Surveys\Test\TestCase;

/**
* Test the Response model
* @group response
* @group models
*/
class ResponseTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->migrate();
        $this->u = factory(User::class)->create();
        \Auth::loginUsingId($this->u->id);
        $this->svy = Survey::find(1);
        $this->p = factory(Participant::class)->create();
        $this->rsp = $this->svy->getNewResponse($this->p);
    }

    /**
     * @test
     */
    public function can_finalize_a_response_with_default_timestamp()
    {
        $this->rsp->finalize();

        $this->assertNotNull($this->rsp->finalized_at);
    }

    /**
     * @test
     */
    public function can_finalize_a_response_w_custom_timestamp()
    {
        $fin = Carbon::parse('1977-09-17 09:00:00');
        $this->rsp->finalize($fin);

        $this->assertEquals($fin, $this->rsp->finalized_at);
    }

    /**
     * @test
     */
    public function cannot_overwrite_finalized_at_without_forcing()
    {
        $fin = Carbon::parse('1977-09-17 09:00:00');
        $this->rsp->finalize($fin);

        $this->rsp->finalize();

        $this->assertEquals($fin, $this->rsp->finalized_at);
    }

    /**
     * @test
     */
    public function can_overwrite_finalized_at_with_force()
    {
        $fin = Carbon::parse('1977-09-17 09:00:00');
        $this->rsp->finalize($fin);

        $this->rsp->finalize(null, true);

        $this->assertEquals(Carbon::create(), $this->rsp->finalized_at);
    }
}
