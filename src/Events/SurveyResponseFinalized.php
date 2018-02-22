<?php

namespace Sirs\Surveys\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Sirs\Surveys\Events\SurveyResponseEvent;
use Sirs\Surveys\Models\Response;

class SurveyResponseFinalized extends SurveyResponseEvent
{
}
