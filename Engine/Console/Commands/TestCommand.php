<?php


namespace Temple\Engine\Console\Commands;

use Temple\Engine\Console\Command;


/**
 * Class TestCommand
 *
 * @package Temple\Engine\Console\Commands
 */
class TestCommand extends Command {


    public function define() {
        $this->setName("test");
        $this->setUseConfigs(false);
    }


    /**
     * removes all cache folders
     */
    public function execute($test = null) {
        sleep(1);
    }



}
