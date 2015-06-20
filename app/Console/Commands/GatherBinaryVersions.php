<?php

namespace App\Console\Commands;

use App\Models\Binary;
use App\Models\BinaryVersion;
use BinaryMngr\Contract\Gatherers\BinaryVersionsGatherer as Gatherer;
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
                $latestIdentifier = $binary->hasVersions() ? $binary->getLatestVersion()->identifier : null;
                $versions = null;
                try {
                    $gatherer->gather();
                    $versions = $gatherer->getVersions();
                } catch (BinaryVersionsGathererException $e) {
                    $this->error("Gathering versions for binary '{$binary->name}' failed.");
                }
                if (is_array($versions) && count($versions) !== 0
                    && $latestIdentifier !== $gatherer->getLatestVersion()[Gatherer::KEY_IDENTIFIER]) {  # TODO: >=
                    foreach ($versions as $version) {
                        if (($identifier = array_get($version, Gatherer::KEY_IDENTIFIER, null)) !== null
                            && BinaryVersion::where('binary_id', '=', $binary->id)->where('identifier', '=', $identifier)->first() === null) {
                            $new = new BinaryVersion;
                            $new->identifier = $identifier;
                            $new->note = array_get($version, Gatherer::KEY_NOTE, null);
                            $new->eol = array_get($version, Gatherer::KEY_EOL, null);
                            $new->binary_id = $binary->id;
                            if ($new->validate() && $new->save()) {
                                ++$gathered;
                            } else {
                                $this->error($new->errors()->all());
                            }
                        }
                    }
                }
            }
        }

        return $this->info("Gathered {$gathered} binary versions.");
    }
}
