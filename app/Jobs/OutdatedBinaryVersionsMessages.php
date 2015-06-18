<?php namespace App\Jobs;

use App\Models\BinaryVersion;
use App\Models\Message;

/**
 * The OutdatedBinaryVersionsMessages job is best used as a queued callback
 * for BinaryVersion created events. It will check if the newly created version
 * is newer than the existing ones and create 'outdated binary version' messages
 * for all older binary versions that are installed.
 */
final class OutdatedBinaryVersionsMessages extends Job
{
    /**
     * Stores a reference to the binary version for which this job was triggered.
     *
     * @var \App\Models\BinaryVersion
     */
    private $binary_version;


    /**
     * Constructor to initialize a new OutdatedBinaryVersionsMessages instance.
     *
     * @param \App\Models\BinaryVersion $binary_version the newly created binary version
     */
    public function __construct(BinaryVersion $binary_version)
    {
        $this->binary_version = $binary_version;
    }

    /**
     * @{inherit}
     */
    public function handle()
    {
        $version = $this->binary_version;
        if ($version->isLatest()) {
            foreach ($version->binary->getOutdatedVersions() as $outdated) {
                if ($outdated->isInstalled()) {
                    foreach ($outdated->servers as $server) {
                        $msg = new Message;
                        $msg->title = 'Outdated binary version installed';
                        $msg->body  = "Version '{$outdated->identifier}' of '{$outdated->binary->name}' is outdated";
                        $msg->body .= " and installed on server '{$server->name}'. Make sure to upgrade soon!";
                        $msg->binary_version_id = $outdated->id;
                        $msg->user_id = $outdated->binary->owner->id;
                        $msg->save();
                    }
                }
            }
        }
        return true;
    }
}
