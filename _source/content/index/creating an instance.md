``` php
<?php


namespace Pagen;


use Symfony\Component\Yaml\Yaml;


class Routing
{

    /** @var Config $config */
    private $config;

    /** @var PathHelper $pathHelper */
    private $pathHelper;

    /** @var Yaml $yaml */
    private $yaml;


    /**
     * Routing constructor.
     *
     * @param Config     $config
     * @param PathHelper $pathHelper
     * @param Yaml       $yaml
     */
    public function __construct($config, PathHelper $pathHelper, Yaml $yaml)
    {
        $this->config     = $config;
        $this->pathHelper = $pathHelper;
        $this->yaml       = $yaml;
    }


    /**
     * returns an array of routs
     *
     * @returns array
     * @throws \Exception
     */
    public function getRoutes()
    {
        $source      = $this->pathHelper->getPath($this->config->getSourceFolder());
        $routingFile = $source . "routing.yml";

        if (!file_exists($routingFile)) {
            throw new \Exception("routing.yml file doesn't exists!");
        }

        $content = file_get_contents($routingFile);
        $content = $this->yaml->parse($content);

        $requiredKeys = array("name", "folder", "parent");

        foreach ($content as $key => $route) {
            $content[$key]["page"] = $key;
            foreach ($requiredKeys as $required) {
                if ($required == "parent" && $key == "index") {
                    continue;
                }
                if (!array_key_exists($required, $route)) {
                    throw new \Exception("$required: has to exist withing the $key routing!");
                }
            }
        }

        return $content;
    }

}
```