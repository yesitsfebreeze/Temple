<?php


namespace Temple\Engine\Console\Commands;


use Temple\Engine;
use Temple\Engine\Console\Command;


/**
 * Class TestCommand
 *
 * @package Temple\Engine\Console\Commands
 */
class CacheBuildTemplatesCommand extends Command
{

    /**
     * defines the command
     */
    public function define()
    {
        $this->setName("cache:build:templates");
        $this->setUseProgress(true);
        $this->setProgressTitle("generating template cache...");
        $this->setProgressTitleColor("green");
    }


    /**
     * @param null $arg
     */
    public function execute($arg = null)
    {
        if (isset($this->config["dynamic"]["processedTemplates"])) {

            $Engine = new Engine();
            $Engine->Config()->setUpdate(false);
            $Engine->Config()->setConfigCacheEnabled(false);
            $Engine->Config()->createFromArray($this->config);

            foreach ($this->config["dynamic"]["processedTemplates"] as $template) {
                $Engine->Template()->process($template);
            }

        }
    }


}
