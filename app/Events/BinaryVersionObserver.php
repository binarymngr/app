<?php namespace App\Events;

use App\Models\BinaryVersion;
use App\Models\Message;

final class BinaryVersionObserver
{
    /**
     *
     */
    public function created(BinaryVersion $version)
    {
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
