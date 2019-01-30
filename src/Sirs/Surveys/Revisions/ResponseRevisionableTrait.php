<?php
namespace Sirs\Surveys\Revisions;

use Illuminate\Support\Facades\Auth;
use Sirs\Surveys\Revisions\Revision;
use Venturecraft\Revisionable\RevisionableTrait;

/*
 * This file is part of the Revisionable package by Venture Craft
 *
 * (c) Venture Craft <http://www.venturecraft.com.au>
 *
 */

/**
 * Class RevisionableTrait
 * @package Venturecraft\Revisionable
 */
trait ResponseRevisionableTrait
{
    use RevisionableTrait;

    /**
     * @return mixed
     */
    public function revisionHistory()
    {
        // return $this->morphMany('\Venturecraft\Revisionable\Revision', 'revisionable');
        return $this->hasMany(Revision::class)
            ->where('response_table', '=', $this->getTable());
    }

    /**
     * Generates a list of the last $limit revisions made to any objects of the class it is being called from.
     *
     * @param int $limit
     * @param string $order
     * @return mixed
     */
    public static function classRevisionHistory($table, $limit = 100, $order = 'desc')
    {
        return Revision::where('response_table', $table)
            ->orderBy('updated_at', $order)->limit($limit)->get();
    }

    /**
     * undocumented function
     *
     * @return void
     * @author
     **/
    public static function responseRevisionHistory($table, $limit = 100, $order = 'desc')
    {
        return static::classRevisionHistory($table, $limit, $order);
    }


    /**
     * Called after a model is successfully saved.
     *
     * @return void
     */
    public function postSave()
    {
        if (isset($this->historyLimit) && $this->revisionHistory()->count() >= $this->historyLimit) {
            $LimitReached = true;
        } else {
            $LimitReached = false;
        }
        if (isset($this->revisionCleanup)) {
            $RevisionCleanup=$this->revisionCleanup;
        } else {
            $RevisionCleanup=false;
        }

        // check if the model already exists
        if (((!isset($this->revisionEnabled) || $this->revisionEnabled) && $this->updating) && (!$LimitReached || $RevisionCleanup)) {
            // if it does, it means we're updating

            $changes_to_record = $this->changedRevisionableFields();

            $revisions = array();

            foreach ($changes_to_record as $key => $change) {
                $revisions[] = array(
                    'response_table' => $this->getTable(),
                    'response_id' => $this->getKey(),
                    'key' => $key,
                    'old_value' => array_get($this->originalData, $key),
                    'new_value' => $this->updatedData[$key],
                    'user_id' => Auth::user()->id,
                    'created_at' => new \DateTime(),
                    'updated_at' => new \DateTime(),
                );
            }

            if (count($revisions) > 0) {
                if ($LimitReached && $RevisionCleanup) {
                    $toDelete = $this->revisionHistory()->orderBy('id', 'asc')->limit(count($revisions))->get();
                    foreach ($toDelete as $delete) {
                        $delete->delete();
                    }
                }
                $revision = new Revision;
                \DB::table($revision->getTable())->insert($revisions);
                \Event::fire('revisionable.saved', array('model' => $this, 'revisions' => $revisions));
            }
        }
    }

    /**
    * Called after record successfully created
    */
    public function postCreate()
    {

        // Check if we should store creations in our revision history
        // Set this value to true in your model if you want to
        if (empty($this->revisionCreationsEnabled)) {
            // We should not store creations.
            return false;
        }

        if ((!isset($this->revisionEnabled) || $this->revisionEnabled)) {
            $revisions[] = array(
                'response_table' => $this->getTable(),
                'response_id' => $this->getKey(),
                'key' => self::CREATED_AT,
                'old_value' => null,
                'new_value' => $this->{self::CREATED_AT},
                'user_id' => (Auth::user()) ? Auth::user()->id : null,
                'created_at' => new \DateTime(),
                'updated_at' => new \DateTime(),
            );

            $revision = new Revision;
            \DB::table($revision->getTable())->insert($revisions);
            \Event::fire('revisionable.created', array('model' => $this, 'revisions' => $revisions));
        }
    }

    /**
     * If softdeletes are enabled, store the deleted time
     */
    public function postDelete()
    {
        if ((!isset($this->revisionEnabled) || $this->revisionEnabled)
            && $this->isSoftDelete()
            && $this->isRevisionable($this->getDeletedAtColumn())
        ) {
            $revisions[] = array(
                'response_table' => $this->getTable(),
                'response_id' => $this->getKey(),
                'key' => $this->getDeletedAtColumn(),
                'old_value' => null,
                'new_value' => $this->{$this->getDeletedAtColumn()},
                'user_id' => Auth::user()->id,
                'created_at' => new \DateTime(),
                'updated_at' => new \DateTime(),
            );
            $revision = new Revision;
            \DB::table($revision->getTable())->insert($revisions);
            \Event::fire('revisionable.deleted', array('model' => $this, 'revisions' => $revisions));
        }
    }
}
