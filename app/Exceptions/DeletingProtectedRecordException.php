<?php namespace App\Exceptions;

use Exception;

/**
 * The DeletingProtectedRecordException is meant to be thrown when deleting
 * a model record is not possible/allowed because it is protected (e.g. the admin role).
 */
final class DeletingProtectedRecordException extends Exception
{
    /**
     * Stores a reference to the protected record.
     *
     * @var mixed
     */
    private $record;


    /**
     * @{inherit}
     *
     * @param mixed $record the protected record prevented from deleting
     */
    public function __construct($record, $message = null, $code = 0, Exception $previous = null)
    {
        $this->record = clone $record;
        parent::__construct($message, $code, $previous);
    }

    /**
     * Returns the record for which this exception was thrown.
     *
     * @return mixed
     */
    public function getRecord()
    {
        return clone $this->record;
    }
}
