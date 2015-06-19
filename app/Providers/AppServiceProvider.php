<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        # register all the BinaryMngr\Contract\Gatherers\BinaryVersionGatherer implementations
        // $this->app->bind('GithubTagsGatherer', function ($app) {
        //     return new BinaryMngr\Lib\Gatherers\GithubTagsGatherer;
        // });

        # tag all binary version gatherers so we can easily access them elsewhere
        $this->app->tag([
            // 'GithubTagsGatherer'
        ], 'binary_version_gatherers');
    }
}
