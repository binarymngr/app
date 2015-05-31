<?php namespace App\Providers;

use App\Jobs\OutdatedBinaryVersionsMessages;
use App\Models\BinaryVersion;
use Queue;

final class EventServiceProvider extends AppServiceProvider
{
    /**
     * @{inherit}
     */
    public function boot()
    {
        # directly passing the job as a callback doesn't work...therefor the workaround
        BinaryVersion::created(function(BinaryVersion $binary_version) {
            Queue::push(new OutdatedBinaryVersionsMessages($binary_version));
        });
    }
}
