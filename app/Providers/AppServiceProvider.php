<?php

namespace App\Providers;

use BinaryMngr\Contract\Gatherers\BinaryVersionsGatherer as Gatherer;
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
        $this->app->bind('GithubTagsGatherer', function ($app) {
            return new GithubTagsGatherer;
        });

        # tag all binary version gatherers so we can easily access them elsewhere
        $this->app->tag([
            'GithubTagsGatherer'
        ], 'binary_version_gatherers');
    }
}

class GithubTagsGatherer implements Gatherer
{
    public function gather() {}
    public function getDescription() {return 'Gatherer to get versions from Github tags page.';}
    public function getLatestVersion() {
        return [
            Gatherer::KEY_IDENTIFIER => '1.0.0',
            Gatherer::KEY_NOTE => 'Final release.',
            Gatherer::KEY_EOL => '2016-06-30'
        ];
    }
    public function getName() {return 'GithubTagsGatherer';}
    public function getVersions() {
        return [
            [
                Gatherer::KEY_IDENTIFIER => '1.0.0',
                Gatherer::KEY_NOTE => 'Final release.',
                Gatherer::KEY_EOL => '2016-06-30'
            ],
            [
                Gatherer::KEY_IDENTIFIER => '0.1.0',
                Gatherer::KEY_NOTE => 'Another release.',
                Gatherer::KEY_EOL => '2015-01-01'
            ]
        ];
    }
    public function setBinary(array $binary){return $this;}
    public function setMeta($meta) {return $this;}
}
