<?php

use App\Models\BinaryVersion;

/**
 *
 */
final class OutdatedBinaryVersionMessageCreator
{
    /**
     *
     */
    public function handle(BinaryVersion $binary_version)
    {
        dd($binary_version);
    }
}
