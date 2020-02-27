<?php

namespace Sirs\Surveys\Models\Concerns;

use Illuminate\Support\Str;

trait UsesUuid
{
    protected static function bootUsesUuid()
    {
        static::creating(function ($model) {
            if (! $model->getKey()) {
                // Get uuid and verify it's unique
                $uuid = (string) Str::uuid();

                // Adding a unique check that is specific for the survey package
                $responses = $model->survey->responses()->get('id');
                foreach($responses as $response){
                    if( $response->id == $uuid ){
                        $uuid = (string) Str::uuid();
                    }
                }
                // end unique check for survey package

                $model->{$model->getKeyName()} = $uuid;
            }
        });
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
