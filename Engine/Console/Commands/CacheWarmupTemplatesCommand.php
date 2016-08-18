<?php


namespace Temple\Engine\Console\Commands;


use Temple\Engine\Config;
use Temple\Engine\Console\Command;
use Temple\Instance;


/**
 * Class TestCommand
 *
 * @package Temple\Engine\Console\Commands
 */
class CacheWarmupTemplatesCommand extends Command
{


    public function define()
    {
        $this->setName("cache:warmup:templates");
        $this->setUseProgress(true);
        $this->setProgressTitle("generating template cache...");
        $this->setProgressTitleColor("green");
    }


    /**
     * removes all cache folders
     */
    public function execute($arg = null)
    {
        if (!is_null($this->config["processedTemplates"])) {
            foreach ($this->config["processedTemplates"] as $template) {
                $Instance = new Instance();
                $Instance = $this->createConfig($Instance, $this->config);
                $Instance->Template()->fetch($template);
            }
        }

    }


    private function createConfig(Instance $Instance, $cachedConfig)
    {
        /** @var Config $config */
        $config  = $Instance->Config();
        $methods = array_flip(get_class_methods($config));
        foreach ($cachedConfig as $method => $value) {
            $setMethodName = "set" . ucfirst($method);
            $addMethodName = "add" . preg_replace("/s$/", "", ucfirst($method));

            if (isset($methods[ $setMethodName ])) {
                $config->$setMethodName($value);
            } else if (isset($methods[ $addMethodName ])) {
                foreach ($value as $v) {
                    $config->$addMethodName($v);
                }
            }
        }

//        $Instance->Config()

        return $Instance;
    }


}
