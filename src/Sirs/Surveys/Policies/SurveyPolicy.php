<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Sirs\Surveys\Models\Surveys;

class SurveyPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function view(User $user, Survey $survey)
    {
        return true;
    }

    public function conduct(User $user, Survey $survey)
    {
        return true;
    }

}
