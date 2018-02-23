<?php

namespace Sirs\Surveys\Test\Stubs;

use Illuminate\Foundation\Auth\User as Eloquent;

class User extends Eloquent
{
    /**
     * Get the name of the unique identifier for the user.
     *
     * @return array
     */
    public function getAuthIdentifiersName()
    {
        return ['email', 'username'];
    }

    public function getNameAttribute()
    {
        return $this->username;
    }

    public function getName()
    {
        return $this->getNameAttribute();
    }
}
