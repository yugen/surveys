<?php 
namespace Sirs\Surveys\Exceptions;

class ResponsePreviouslyFinalizedException extends ResponseException
{
    public function __construct($response, $message=null, $code=null)
    {
        $this->message = ($message) ? $message : 'Response previously finalized at '.$response->finalized_at.'.  To change the finalized_at date you must indicate an override.';
    }
}
