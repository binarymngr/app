<?php namespace App\Providers;

use App\Models\BinaryVersion;

/**
 *
 */
final class EventServiceProvider extends AppServiceProvider
{
    /**
     * @{inherit}
     */
    public function boot()
    {
        BinaryVersion::created('App\Events\OutdatedBinaryVersionMessageCreator@handle');
    }
}
