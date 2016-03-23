<?php

namespace Deployer;


use Symfony\Component\Yaml\Yaml;

class ParseTwig
{


    /**
     * parseTwig constructor.
     *
     * @param            $dir
     * @param Yaml $yaml
     * @param \Parsedown $parsedown
     */
    public function __construct($dir, Yaml $yaml, \Parsedown $parsedown, \Twig_Environment $twig)
    {

        $this->render("docs", "index", $dir, $yaml, $parsedown, $twig);
        $this->render("api", "api", $dir, $yaml, $parsedown, $twig);
    }


    private function render($type, $file, $dir, Yaml $yaml, \Parsedown $parsedown, \Twig_Environment $twig)
    {
        $mainConfig = $dir . "/../../" . $type . "_config.yml";
        $mainConfig = $yaml->parse(file_get_contents($mainConfig));
        $config     = $dir . "/../../" . "main_config.yml";
        $config     = $yaml->parse(file_get_contents($config));
        $config     = array_merge_recursive($mainConfig, $config);

        $this->getIncludes($config, $parsedown, $dir, $type);
        $this->createTwig($dir, $type, $file, $config, $twig);
    }


    /**
     * @param                   $dir
     * @param                   $type
     * @param                   $file
     * @param                   $config
     * @param \Twig_Environment $twig
     */
    private function createTwig($dir, $type, $file, $config, \Twig_Environment $twig)
    {

        $dir = $dir . '/../templates/' . $type;
        if ($file == "index") {
            $outputFile       = $dir . "/../../../" . $file . ".html";
            $config["assets"] = "_deployer/assets/prod/";
        } else {
            $outputFile       = $dir . "/../../../" . $file . "/index.html";
            $config["assets"] = "../_deployer/assets/prod/";
        }

        if ($file == "api") {
            $apiList = __DIR__ . "/../api_list.json";
            if (file_exists($apiList)) {
                $api           = json_decode(file_get_contents($apiList), true);
                $config["api"] = $api;
            }
            $menu = __DIR__ . "/../api_menu.json";
            if (file_exists($apiList)) {
                $menu           = json_decode(file_get_contents($menu), true);
                $config["menu"] = $menu;
            }
        }

        $full = $twig->render($type . '/index.twig', $config);

        $dir = dirname($outputFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        if (file_exists($outputFile)) {
            unlink($outputFile);
        }
        touch($outputFile);
        file_put_contents($outputFile, $full);
    }


    /**
     * @param            $config
     * @param \Parsedown $parsedown
     * @param            $dir
     * @param            $which
     */
    private function getIncludes(&$config, \Parsedown $parsedown, $dir, $which)
    {

        $includes = array();
        foreach ($config["includes"] as $include) {
            if (pathinfo($include, PATHINFO_EXTENSION) == "md") {
                $name              = str_replace(".md", "", $include);
                $md                = $parsedown->parse(file_get_contents($dir . '/../pages/' . $which . "/" . $include));
                $outputFile        = $dir . '/../templates/' . $which . '/generated/' . $name . ".html";
                $includes[ $name ] = str_replace($dir . "/../templates/", "", $outputFile);
                $dir               = dirname($outputFile);
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }
                file_put_contents($outputFile, $md);
            }
        }
        $config["includes"] = $includes;
    }
}