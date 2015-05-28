<?php

use App\Events\BinaryVersionCreated;

/**
 *
 */
final class OutdatedBinaryVersionInstalledMessage
{
    /**
     *
     */
    public function handle(BinaryVersionCreated $event)
    {
        dd($event);
    }
}
