<?php

namespace App\Console\Commands;

use App\Models\Binary;
use App\Models\BinaryVersion;
use BinaryMngr\Contract\Gatherers\BinaryVersionsGathererException;
use Illuminate\Console\Command;

final class GatherBinaryVersions extends Command
{
    protected $name = 'binarymngr:gather-binary-versions';
    protected $description = 'Gathers binary versions from external sources.';


    /**
     * @{inherit}
     */
    public function fire()
    {
        $gathered = 0;
        foreach (Binary::all() as $binary) {  # TODO: where versions_gatherer is set
            if ($binary->hasVersionsGatherer()) {
                $gatherer = $binary->getVersionsGatherer();
                $versions = null;
                try {
                    $gatherer->gather();
                    $versions = $gatherer->getVersions();
                } catch (BinaryVersionsGathererException $e) {
                    $this->error("Gathering versions for binary '{$binary->name}' failed.");
                }
                if (is_array($versions) && count($versions) !== 0
                    && $gatherer->getLatestVersion()->identifier != $latestVersion) {  # TODO: >=
                    foreach ($versions as $version) {
                        if (($identifier = array_get($version, 'identifier', null)) !== null
                            && Binary::where('identifier', '=', $identifier)->isEmpty()) {
                            $version = new BinaryVersion;
                            $version->identifier = $identifier;
                            $version->note = array_get($version, 'note', null);
                            $version->eol = array_get($version, 'eol', null);
                            if ($version->validate() && $version->save()) {
                                ++$gathered;
                            } else {
                                $this->error($version->errors()->all());
                            }
                        }
                    }
                }
            }
        }

        return $this->info("Gathered {$gathered} binary versions.");
    }
}
