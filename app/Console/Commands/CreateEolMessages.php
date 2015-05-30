<?php namespace App\Console\Commands;

use App\Models\BinaryVersion;
use App\Models\Message;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

final class CreateEolMessages extends Command
{

    protected $name = 'binarymngr:create-eol-messages';
    protected $description = 'Creates messages for all binary versions which have reached end-of-life.';


    /**
     * @{inherit}
     */
    public function fire()
    {
        $created = 0;
        foreach (BinaryVersion::all() as $version) {
            if (!$version->isLatest() && $version->isInstalled()) {
                foreach ($version->servers as $server) {
                    $msg = new Message;
                    $msg->title = 'Unsupported binary version installed';
                    $msg->body  = "Version '{$version->identifier}' of '{$version->binary->name}' has reached end-of-life";
                    $msg->body .= " and is installed on server '{$server->name}'. Make sure to upgrade soon!";
                    $msg->user_id = $version->binary->owner->id;
                    $msg->save();

                    $this->comment("Creating message for version '{$version->identifier}' of '{$version->binary->name}'.");
                    ++$created;
                }
            }
        }

        if ($created != 0) {
            return $this->info("Sucessfully created {$created} EOL reached messages.");
        } else {
            return $this->info('No installed binary version has reached EOL yet.');
        }
    }
}
