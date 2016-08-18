<?php


namespace Temple\Engine\Console\Commands;


use Temple\Engine\Console\Command;
use Temple\Engine\Exception\Exception;


/**
 * Class TestCommand
 *
 * @package Temple\Engine\Console\Commands
 */
class CacheClearCompleteCommand extends Command
{


    public function define()
    {
        $this->setName("cache:clear:complete");
        $this->setUseProgress(true);
        $this->setProgressTitle("completely clearing cache...");
        $this->setProgressTitleColor("red");
    }


    /**
     * removes all cache folders
     */
    public function execute($arg = null)
    {
        $this->CliOutput->writeln("clearing caches...", "green");
        $cacheDir = $this->config["cacheDir"];

        if (!$this->Storage->has("paths." . $cacheDir)) {
            $this->removeDir($cacheDir);
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
                        unlink($dir . "/" . $object);
                    }
                }
            }
            rmdir($dir);
        }
    }

}
