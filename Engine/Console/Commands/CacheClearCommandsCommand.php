<?php


namespace Temple\Engine\Console\Commands;


use Temple\Engine\Console\Command;
use Temple\Engine\Exception\Exception;


/**
 * Class TestCommand
 *
 * @package Temple\Engine\Console\Commands
 */
class CacheClearCommandsCommand extends Command
{


    public function define()
    {
        $this->setName("cache:clear:commands");
        $this->setUseProgress(true);
        $this->setProgressTitle("clearing command cache...");
        $this->setProgressTitleColor("red");
    }


    public function execute($arg = null)
    {
        $this->CliOutput->writeln("clearing caches...", "green");
        $cacheDir = $this->config["cacheDir"];

        if (!$this->Storage->has("paths." . $cacheDir)) {

            $last_error_reporting = error_reporting();
            error_reporting(E_ALL & ~E_WARNING);
            $this->removeDir($cacheDir);
            error_reporting($last_error_reporting);

            $this->Storage->set("paths." . $cacheDir, true);
        }
        $this->CliOutput->writeln("done.", "green");
    }


    /**
     * echo if there were no caches to clear
     */
    public function after()
    {
        try {
            $this->Storage->get("paths");
        } catch (Exception $e) {
            if ($e->getMessage() == "Sorry, 'paths' is undefined!") {
                $this->CliOutput->writeln("Nothing to clear.", "green");
            }
        }
    }


    /**
     * recursively removes a directory
     *
     * @param $dir
     */
    private function removeDir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir . "/" . $object)) {
                        $this->removeDir($dir . "/" . $object);
                    } else {
                        if ($object == "cache.commands.php") {
                            unlink($dir . "/" . $object);
                        }
                    }
                }
            }

            rmdir($dir);
        }
    }


}
