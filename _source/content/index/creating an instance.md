``` php
<?php


namespace Pagen;


/**
 * Class Content
 *
 * @package Pagen
 */
class Content
{
    /** @var  array $routes */
    private $routes;

    /** @var Config $config */
    private $config;

    /** @var PathHelper $pathHelper */
    private $pathHelper;

    /** @var string $contentFolder */
    private $contentFolder;

    /** @var \Parsedown $parsedown */
    private $parsedown;


    /**
     * Content constructor.
     *
     * @param Config     $config
     * @param PathHelper $pathHelper
     * @param \Parsedown $parsedown
     */
    public function __construct($config, PathHelper $pathHelper, \Parsedown $parsedown)
    {
        $this->config     = $config;
        $this->pathHelper = $pathHelper;
        $this->parsedown  = $parsedown;
    }


    /**
     * adds all md files to the routing array
     */
    public function addContentToRoutes()
    {
        $this->contentFolder = $this->pathHelper->getPath($this->config->getSourceFolder() . DIRECTORY_SEPARATOR . "content");

        $this->gatherMdFiles();
        return $this->routes;
    }


    /**
     * gathers and returns the parsed content of all md files
     * within the respective content folder
     */
    private function gatherMdFiles()
    {
        foreach ($this->routes as $name => &$route) {
            $path = $this->contentFolder . $name . DIRECTORY_SEPARATOR;

            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }

            $files = scandir($path);
            foreach ($files as $key => $file) {
                if (strpos($file, ".md") == false) {
                    unset($files[ $key ]);
                }
            }
            $route["mdFiles"] = $files;
            $route["content"] = array();
            $this->parseMdFiles($path, $route);
        }
    }


    /**
     * @param $path
     * @param $route
     */
    private function parseMdFiles($path, &$route)
    {
        foreach ($route["mdFiles"] as $mdFile) {
            $name                      = str_replace(".md", "", $mdFile);
            $content                   = file_get_contents($path . $mdFile);
            $content                   = $this->parsedown->parse($content);
            $route["content"][ $name ] = $content;
        }
    }


    /**
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }


    /**
     * @param array $routes
     */
    public function setRoutes($routes)
    {
        $this->routes = $routes;
    }


}
```