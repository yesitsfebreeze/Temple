<?php

require "vendor/autoload.php";


class Page
{

    /** @var string $root */
    private $root;

    /** @var string $page */
    private $page;

    /** @var Smarty $smarty */
    private $smarty;

    /** @var \Symfony\Component\Yaml\Yaml $yaml */
    private $yaml;



    public function __construct()
    {
        $this->root   = realpath(__DIR__) . DIRECTORY_SEPARATOR;
        $this->page   = $this->root . ".." . DIRECTORY_SEPARATOR . "_page" . DIRECTORY_SEPARATOR;
        $this->smarty = new Smarty();
        $this->yaml   = new \Symfony\Component\Yaml\Yaml();
        $this->smarty->addTemplateDir($this->page . "templates");
        date_default_timezone_set("Europe/Berlin");
    }


    /**
     * deploys the pages
     * and all its styles
     */
    public function deploy()
    {
        $pages = $this->getPages();
        $this->fetchTemplates($pages, "index");
    }


    /**
     * fetches the templates and saves them in the respective directory
     *
     * @param        $page
     * @param        $name
     * @param string $path
     */
    private function fetchTemplates($page, $name, $path = "")
    {
        if (is_array($page)) {
            foreach ($page as $name => $subviews) {
                $orgPath = $path;
                $path .= "/" . $name;
                $this->fetchTemplates($subviews, $name, $path);
                $path = $orgPath;
            }
        } else if (is_string($name)) {
            $this->smarty->clearAllAssign();
            $path = substr($path, 1);
            $this->assignPageData("default");
            $this->assignPageData($path);
            $content    = $this->smarty->fetch($path . ".tpl");
            $outputFile = $this->root . ".." . DIRECTORY_SEPARATOR . $path . ".html";
            $outputDir  = dirname($outputFile);
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0777, true);
            }
            if (!file_exists($outputFile)) {
                touch($outputFile);
            }
            file_put_contents($outputFile, $content);
        }
    }


    /**
     * assigns yaml data to the page
     *
     * @param $path
     */
    private function assignPageData($path)
    {
        $file = $this->page . "data" . DIRECTORY_SEPARATOR . $path . ".yml";
        if (file_exists($file)) {
            $data = $this->yaml->parse(file_get_contents($file));
            $this->smarty->assign($data);
        }
    }


    /**
     * returns deep array of all registered pages
     *
     * @return mixed|null
     */
    private function getPages()
    {
        $source = $this->root . "pages.yml";
        if (file_exists($source)) {
            return $this->yaml->parse(file_get_contents($source));
        }

        return null;
    }

}