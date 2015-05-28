<?php namespace App\Providers;

use App\Events\BinaryVersionObserver;
use App\Models\BinaryVersion;

final class EventServiceProvider extends AppServiceProvider
{
    /**
     * @{inherit}
     */
    public function boot()
    {
        BinaryVersion::observe(new BinaryVersionObserver());
    }
}
