<?php

namespace App\Http\Controllers;

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

        $gatherers = [];
        foreach ($app->tagged('binary_version_gatherers') as $gatherer) {
            $gatherers[] = $gatherer->getName();
        }
        return Collection::make($gatherers);
    }
}
