<?php

namespace Sirs\Surveys\Events;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Sirs\Surveys\Events\SurveyResponseEvent;

class SurveyResponseSaving extends SurveyResponseEvent
{
}
