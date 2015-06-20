<?php

namespace App\Http\Controllers;

use Cache;
use Illuminate\Database\Eloquent\Collection;

final class BinaryVersionGathererController extends Controller
{
    /**
     * Returns a collection of all configured binary version gatherers.
     *
     * @return
     */
    public function getAll()
    {
        global $app;

        $gatherers = null;
        if (($cached = Cache::get('binary_versions_gatherers', false))) {
            $gatherers = $cached;
        } else {
            $gatherers = [];
            foreach ($app->tagged('binary_versions_gatherers') as $gatherer) {
                $gatherers[] = [
                    'name' => $gatherer->getName(),
                    'description' => $gatherer->getDescription()
                ];
            }
            $gatherers = Collection::make($gatherers);
            Cache::put('binary_versions_gatherers', $gatherers, \
                       config('binarymngr.gatherers_cache_lifetime'));
        }
        return $gatherers;
    }
}
