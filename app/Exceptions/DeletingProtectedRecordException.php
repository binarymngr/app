<?php namespace App\Exceptions;

use Exception;

final class DeletingProtectedRecordException extends Exception
{
    /**
     *
     */
    private $record;


    /**
     *
     */
    public function __construct($record, $message = null, $code = 0, Exception $previous = null)
    {
        $this->record = $record;  # TODO: create copy
        parent::__construct($message, $code, $previous);
    }

    /**
     *
     */
    public function getRecord()
    {
        return $this->record;  # TODO: create copy
    }
}
