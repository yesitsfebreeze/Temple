<?php


namespace Temple\Engine\Console\Commands;


use Temple\Engine\Console\Command;


/**
 * Class TestCommand
 *
 * @package Temple\Engine\Console\Commands
 */
class ClearCacheCommand extends Command
{


    public function define()
    {
        $this->setName("cache:clear");
        # process
        # help
    }


    /**
     * removes all cache folders
     */
    public function execute($arg = null)
    {
        $this->CliOutput->writeln("clearing caches...", "green");
        $cacheDir = $this->config["cacheDir"];
        $this->removeDir($cacheDir);
        $this->CliOutput->writeln("done.", "green");
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
