<?php namespace App\Jobs;

use App\Models\BinaryVersion;
use App\Models\Message;

final class OutdatedBinaryVersionMessage extends Job
{
    /**
     * Stores a reference to the binary version for which this job was triggered.
     *
     * @var \App\Models\BinaryVersion
     */
    private $binary_version;


    public function __construct(BinaryVersion $binary_version)
    {
        $this->binary_version = $binary_version;
    }

    public function handle()
    {
        $version = $this->binary_version;
        if ($version->isLatest()) {
            foreach ($version->binary->getOutdatedVersions() as $outdated) {
                if ($outdated->isInstalled()) {
                    foreach ($outdated->servers as $server) {
                        $msg = new Message;
                        $msg->title = 'Outdated binary version installed';
                        $msg->body  = "The version '{$version->identifier}' of '{$version->binary->name}' is outdated";
                        $msg->body .= " and installed on server '{$server->name}'. Make sure to upgrade soon!";
                        $msg->user_id = $version->binary->owner->id;
                        $msg->save();
                    }
                }
            }
        }
        return true;
    }
}
