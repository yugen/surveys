<?php 

namespace Sirs\Surveys\Test\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Sirs\Surveys\Models\Response;
use Sirs\Surveys\Models\Survey;
use Sirs\Surveys\Test\TestCase;

/**
* Test the Response model
*/
class ResponseTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->migrate();
    }
}
