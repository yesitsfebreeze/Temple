<?php

namespace Deploy;


use Symfony\Component\Yaml\Yaml;

class parseTwig
{
    /**
     * parseTwig constructor.
     *
     * @param            $dir
     * @param Yaml       $yaml
     * @param \Parsedown $parsedown
     */
    public function __construct($dir, Yaml $yaml, \Parsedown $parsedown)
    {

        $this->render("docs", "index", $dir, $yaml, $parsedown);
        $this->render("api", "api", $dir, $yaml, $parsedown);
    }


    private function render($type, $file, $dir, Yaml $yaml, \Parsedown $parsedown)
    {
        $config = $dir . "/../../" . $type . "_config.yml";
        $config = $yaml->parse(file_get_contents($config));
        $this->getIncludes($config, $parsedown, $dir, $type);
        $this->createTwig($dir, $type, $file, $config);
    }


    /**
     * @param $dir
     * @param $template
     * @param $output
     * @param $config
     */
    private function createTwig($dir, $template, $output, $config)
    {
        $dir    = $dir . '/../templates/' . $template;
        $loader = new \Twig_Autoloader();
        $loader->register($dir);
        $loader = new \Twig_Loader_Filesystem($dir);
        $twig   = new \Twig_Environment($loader, array('cache' => $dir . "/../../cache",));
        $twig->clearCacheFiles();
        $full       = $twig->render('index.twig', $config);
        $outputFile = $dir . "/../../../" . $output . ".html";
        if (file_exists($outputFile)) {
            unlink($outputFile);
        }
        touch($outputFile);
        file_put_contents($outputFile, $full);
    }


    /**
     * @param $config
     * @return mixed
     */
    private function getIncludes(&$config, \Parsedown $parsedown, $dir, $which)
    {

        $includes = array();
        foreach ($config["includes"] as $include) {
            if (pathinfo($include, PATHINFO_EXTENSION) == "md") {
                $md         = $parsedown->parse(file_get_contents($dir . '/../pages/' . $which . "/" . $include));
                $outputFile = $dir . '/../templates/' . $which . '/generated/' . str_replace(".md", ".html", $include);
                $includes[] = str_replace($dir . '/../templates/' . $which . "/", "", $outputFile);
                $dir        = dirname($outputFile);
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }
                file_put_contents($outputFile, $md);
            }
        }
        $config["includes"] = $includes;
    }
}