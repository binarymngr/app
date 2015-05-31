<?php namespace App\Console\Commands;

use App\Models\BinaryVersion;
use App\Models\Message;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;

final class CreateEolMessages extends Command
{

    protected $name = 'binarymngr:create-eol-messages';
    protected $description = 'Creates messages for all binary versions which have reached end-of-life.';


    /**
     * Creates (and saves) the message for the given binary version.
     *
     * @param \App\Models\BinaryVersion $binary_version the binary_version that has reached EOL
     * @param \App\Models\Server        $server         the server on which the version is installed
     *
     * @return void
     */
    private function createMessage(BinaryVersion $binary_version, $server = null)
    {
        $msg = new Message;
        $msg->title = 'Unsupported binary version ';
        $msg->body  = "Version '{$binary_version->identifier}' of '{$binary_version->binary->name}' has reached end-of-life";
        if ($server === null) {
            $msg->title .= 'in system.';
            $msg->body  .= '.';
        } else {
            $msg->title .= 'installed.';
            $msg->body  .= " and is installed on server '{$server->name}'. Make sure to upgrade soon!";
        }
        $msg->user_id = $binary_version->binary->owner->id;
        $msg->save();
    }

    /**
     * @{inherit}
     */
    public function fire()
    {
        $created = 0;
        $installed_only = boolval($this->option('installed-only'));
        foreach (BinaryVersion::all() as $version) {
            $is_installed = $version->isInstalled();
            if ($version->hasReachedEol() && (!$installed_only || $is_installed)) {
                if ($is_installed) {
                    foreach ($version->servers as $server) {
                        $this->createMessage($version, $server);
                        $this->comment("Creating message for version '{$version->identifier}' of '{$version->binary->name}'.");
                        ++$created;
                    }
                } else {
                    $this->createMessage($version);
                    $this->comment("Creating message for version '{$version->identifier}' of '{$version->binary->name}'.");
                    ++$created;
                }
            }
        }

        if ($created != 0) {
            return $this->info("Sucessfully created {$created} EOL reached messages.");
        } elseif ($installed_only) {
            return $this->info('No installed binary version has reached EOL.');
        } else {
            return $this->info('No binary version has reached EOL.');
        }
    }

    /**
     * @{inherit}
     */
    protected function getOptions()
    {
        return [
            ['installed-only', 'i', InputOption::VALUE_OPTIONAL,
            'Either to check all binary versions or only those installed on at least one server.', 1]
        ];
    }
}
