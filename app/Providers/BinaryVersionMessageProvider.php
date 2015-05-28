<?php namespace App\Providers;

use Event;

final class BinaryVersionMessageProvider extends AppServiceProvider
{
    /**
     * @{inherit}
     */
    public function register()
    {
        Event::listen(
            'App\Events\BinaryVersionCreated',
            'App\Events\OutdatedBinaryVersionInstalledMessage@handle'
        );
    }
}
