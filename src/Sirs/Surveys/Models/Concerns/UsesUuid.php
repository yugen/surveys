<?php

namespace Sirs\Surveys\Models\Concerns;

use Illuminate\Support\Str;

trait UsesUuid
{
    protected static function bootUsesUuid()
    {
        if( config('surveys.useUuidForResponses', false) ) {
            static::creating(function ($model) {
                if (!$model->getKey()) {
                    // Get uuid and verify it's unique
                    $uuid = (string)Str::uuid();

                    $model->{$model->getKeyName()} = $uuid;
                }
            });
        }
    }

    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }
}
