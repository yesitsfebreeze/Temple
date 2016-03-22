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
        $config = $dir . "/../../config.yml";
        $config = $yaml->parse(file_get_contents($config));

        $this->renderDocs($dir, $config, $parsedown);
        $this->renderAPI($dir, $config, $parsedown);
    }


    /**
     * @param            $dir
     * @param            $config
     * @param \Parsedown $parsedown
     */
    private function renderDocs($dir, $config, \Parsedown $parsedown)
    {
        $this->getIncludes($config, $parsedown, $dir, "docs");
        $this->createTwig($dir, "docs", "index", $config);
    }


    /**
     * @param            $dir
     * @param            $config
     * @param \Parsedown $parsedown
     */
    private function renderAPI($dir, $config, \Parsedown $parsedown)
    {
        $this->getIncludes($config, $parsedown, $dir, "api");
        $this->createTwig($dir, "docs", "api", $config);
    }


    /**
     * @param $dir
     * @param $template
     * @param $output
     * @param $config
     */
    private function createTwig($dir, $template, $output, $config)
    {
        $dir = $dir . '/../templates/' . $template;
        \Twig_Autoloader::register();
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
        foreach ($config[ $which . "_includes" ] as $include) {
            if (pathinfo($include, PATHINFO_EXTENSION) == "md") {
                $md         = $parsedown->parse(file_get_contents($dir . '/../pages/' . $which . "/" . $include));
                $outputFile = $dir . '/../templates/' . $which . '/generated/' . str_replace(".md", ".html", $include);
                $includes[] = str_replace($dir . '/../templates/' . $which, "", $outputFile);
                $dir        = dirname($outputFile);
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                }
                file_put_contents($outputFile, $md);
            }
        }
        $config[ $which . "_includes" ] = $includes;
    }
}