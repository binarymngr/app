<?php namespace App\Providers;

use App\Models\BinaryVersion;
use Event;

final class EventServiceProvider extends AppServiceProvider
{
    /**
     * @{inherit}
     */
    public function boot()
    {
        BinaryVersion::created('App\Jobs\OutdatedBinaryVersionMessage@handle');
    }
}
