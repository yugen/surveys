<?php

namespace Sirs\Surveys\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use InvalidArgumentException;
use Sirs\Surveys\Contracts\SurveyResponse;
use Sirs\Surveys\Exceptions\ResponsePreviouslyFinalizedException;
use Sirs\Surveys\Revisions\ResponseRevisionableTrait;

class Response extends Model implements SurveyResponse
{
    use SoftDeletes;
    use ResponseRevisionableTrait;

    protected $table = null;
    protected $guarded = ['id', 'finalized_at', 'survey_id'];
    protected $dates = ['created_at', 'updated_at', 'started_at', 'finalized_at', 'deleted_at'];

    protected $name = null;
    protected $version = null;

    protected $dontKeepRevisionOf = [
        'last_page',
    ];
    protected $revisionCreationsEnabled = true;

    /**
     * allows you to statically intialize Response.
     *
     * @return void
     */
    public static function lookupTable($table)
    {
        // $instance = new static;
        // $instance->setTable($table);
        $instance = static::newResponse($table);

        return $instance->newQuery();
    }

    public static function newResponse($table)
    {
        $instance = new static();
        $instance->setTable($table);

        return $instance;
    }

    /**
     * override getTable to return correct table if not set.
     *
     * @return string
     **/
    public function getTable()
    {
        if (isset($this->table)) {
            return $this->table;
        }

        return $this->survey->response_table;
    }

    /**
     * finalize survey if it has not already been finalized.
     *
     * @param string $finalizeDate date string of time to set finalized_at to
     * @param bool   $override     allow setting a new finalized_at date even if one already exists
     *
     * @return void
     *
     * @example
     *    $responses  = Response;
     *    $responses->setSurveyVersion('Baseline')->get();
     *
     *    $response = Response::surveyVersion('Baseline', 3)->findOrFail(4);
     */
    public function finalizeResponse(Carbon $finalizeDate = null, $override = false)
    {
        if ($this->finalized_at == null || $override == true) {
            if ($finalizeDate) {
                $this->finalized_at = $finalizeDate;
            } else {
                $this->finalized_at = new Carbon();
            }

            $this->save();
        } elseif ($override == false) {
            // throw new ResponsePreviouslyFinalizedException($this);
        }
    }

    public function finalize(Carbon $finalizedDate = null, $override = false)
    {
        return $this->finalizeResponse($finalizedDate, $override);
    }

    public function survey()
    {
        return $this->belongsTo(class_survey());
    }

    public function respondent()
    {
        return $this->morphTo();
    }

    public function getDataAttributes()
    {
        $data = [];
        $data['id'] = $this->id;
        $data['respondent'] = $this->respondent->full_name.' - id: '.$this->respondent->id;
        $dataCols = $this->getDataAttributeNames();
        foreach ($dataCols as $column) {
            $data[$column] = $this->{$column};
        }

        return $data;
    }

    /**
     * Sets the values of the dataAttributes from an associative array.
     *
     * @return void
     *
     * @param array $data Associative array of data field=>value
     **/
    public function setDataValues($data, $page)
    {
        // $page = $this->survey->survey_document->getPage($page);
        $pageVariables = collect($page->getVariables())->keyBy('name');

        foreach ($data as $key => $value) {
            if (in_array($key, $pageVariables->keys()->all())) {
                $this->$key = ($value == '') ? null : $value;
            }
        }

        $dataKeys = array_keys($data);
        foreach ($pageVariables as $idx => $pageVar) {
            if (!in_array($pageVar->name, $dataKeys)) {
                $this->{$pageVar->name} = null;
            }
        }
    }

    /**
     * Gets the field names for data attributes.
     *
     * @return void
     *
     * @author
     **/
    public function getDataAttributeNames()
    {
        $cols = [];
        foreach ($cols as $idx => $column) {
            if (in_array($column, ['id', 'respondent_id', 'respondent_type', 'survey_id', 'last_page', 'created_at', 'updated_at'])) {
                unset($cols[$idx]);
            }
        }

        return $cols;
    }

    public function mutateDataValues()
    {
        $this->transformDataValues('json', function ($value, $key) {
            if ($this->isDirty($key)) {
                return $this->decodeJsonField($key, $value);
            }

            return $value;
        });
    }

    public function accessDataValues()
    {
        $errors = [];
        $this->transformDataValues('json', function ($value, $key) use (&$errors) {
            return $this->decodeJsonField($key, $value);
        });
    }

    protected function transformDataValues($dataFormat, $callback)
    {
        $vars = collect($this->survey->document->variables)->pluck('dataFormat', 'name');
        foreach ($this->getAttributes() as $key => $value) {
            if (!$vars->keys()->contains($key)) {
                continue;
            }

            if ($vars->get($key) != $dataFormat) {
                continue;
            }

            $this->attributes[$key] = $callback($value, $key);
        }
    }

    private function decodeJsonField($key, $value)
    {
        try {
            return decodeJson($value, true);
        } catch (InvalidArgumentException $e) {
            if (is_null($value)) {
                return null;
            }
            if (is_array($value) || is_object($value)) {
                return $value;
            }
            \Log::error('Unexpected input for json_decode. Tried to decode a '.gettype($value).' for field '.$key.'. String expected', $this->toArray());
        }
        return $value;
    }
}
