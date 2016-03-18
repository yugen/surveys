<?php

namespace Sirs\Surveys\Events;

use Sirs\Surveys\Models\Response;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;

abstract class SurveyResponseEvent
{
    use SerializesModels;

    public $surveyResponse;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Response $surveyResponse)
    {
        $this->surveyResponse = $surveyResponse;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
