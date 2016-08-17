<?php


namespace Temple\Engine\Console\Commands;

use Temple\Engine\Console\CliProgress;
use Temple\Engine\Console\Command;


/**
 * Class TestCommand
 *
 * @package Temple\Engine\Console\Commands
 */
class TestCommand extends Command {


    public function define() {
        $this->setName("test");
        $this->setUseProgress(true);
    }


    /**
     * removes all cache folders
     */
    public function execute($test = null,$ba = null) {
        usleep(1000000 / 10);
    }


    public function after()
    {

    }


}
