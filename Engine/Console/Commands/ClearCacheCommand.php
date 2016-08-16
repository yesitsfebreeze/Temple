<?php


namespace Temple\Engine\Console\Commands;

use Temple\Engine\Console\Command;


/**
 * Class TestCommand
 *
 * @package Temple\Engine\Console\Commands
 */
class ClearCacheCommand extends Command {


    public function define() {
        $this->setName("clearcache");
    }

    public function execute() {
        # todo: config/instance problem
        # i somehow have to get all current instances and its configs
        # to get the cache directory to clear it

        # could work with a config cache in which ia save the config of an instance
        # then i run over all configs an get their cache directories

        # but that would also mean that whenever i change the config, i have to update the cache for it

        # i could also just serialize the complete instance an get it that way.. performance meeehh...


    }



}
