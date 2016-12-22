<?php

namespace Sirs\Surveys\Revisions;

use Illuminate\Support\Facades\Log;
use Sirs\Surveys\Models\Response;
use Venturecraft\Revisionable\Revision as VentercraftRevision;

/**
 * Revision.
 *
 * Base model to allow for revision history on
 * any model that extends this model
 *
 * (c) Venture Craft <http://www.venturecraft.com.au>
 */
class Revision extends VentercraftRevision
{
    /**
     * undocumented class variable
     *
     * @var string
     **/
    protected $revisionable_type = Response::class;

    /**
     * @var string
     */
    public $table = 'response_revisions';

    /**
     * Revisionable.
     *
     * Grab the revision history for the model that is calling
     *
     * @return array revision history
     */
    public function revisionable()
    {
        return Response::lookupTable($this->response_table)->where('id','=',$this->response_id);
    }


    /**
     * Returns the object we have the history of
     *
     * @return Object|false
     */
    public function historyOf()
    {
        if (class_exists($class = $this->revisionable_type)) {
            $query = $class::lookupTable();
            return $query->where('id', $this->response_id);
        }

        return false;
    }
}