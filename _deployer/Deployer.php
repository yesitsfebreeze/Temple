<?php

require "vendor/autoload.php";


class Deployer
{

    /** @var string $root */
    private $root;

    /** @var string $page */
    private $page;

    /** @var Smarty $smarty */
    private $smarty;

    /** @var \Symfony\Component\Yaml\Yaml $yaml */
    private $yaml;

    /** @var Less_Parser $less */
    private $less;

    /** @var array $menu */
    private $menu = array();


    public function __construct()
    {
        $this->root = realpath(__DIR__) . DIRECTORY_SEPARATOR;
        $this->page = $this->root . ".." . DIRECTORY_SEPARATOR . "_source" . DIRECTORY_SEPARATOR;

        $this->smarty = new Smarty();
        $this->smarty->addTemplateDir($this->page . "templates");

        $this->yaml = new \Symfony\Component\Yaml\Yaml();

        Less_Autoloader::register();
        $this->less = new Less_Parser();

        date_default_timezone_set("Europe/Berlin");
    }


    /**
     * deploys the pages
     * and all its styles
     */
    public function deploy()
    {
        $pages = $this->getPages();
        $this->parseLess("default");
        $this->buildMenu($pages);
        $this->fetchTemplates($pages);
    }


    private function buildMenu($pages)
    {
        $menu       = $this->buildSubMenu(array(), $pages["index"]);
        $this->menu = $menu;
    }


    private function buildSubMenu($menu = array(), $pages, $path = "")
    {

        $iteration = 0;

        foreach ($pages as $name => $page) {
            $menu[ $iteration ] = array();

            $path     = $path . "/" . $name;
            $template = "index/" . substr($path . ".tpl", 1);

            $exists                     = $this->smarty->templateExists($template);
            $menu[ $iteration ]["name"] = $name;
            if ($exists) {
                $menu[ $iteration ]["link"] = $path;
            } else {
                $menu[ $iteration ]["link"] = false;
            }
            $menu[ $iteration ]["children"] = array();

            if (is_array($page)) {
                $menu[ $iteration ]["children"] = $this->buildSubMenu($menu[ $iteration ]["children"], $page, $path);
            }

            $iteration = $iteration + 1;
        }

        return $menu;

    }


    /**
     * fetches the templates and saves them in the respective directory
     *
     * @param        $page
     * @param        $name
     * @param string $path
     */
    private function fetchTemplates($page, $name = "", $path = "")
    {
        if (is_array($page)) {
            foreach ($page as $name => $subviews) {
                $orgPath = $path;
                $path .= "/" . $name;
                $this->fetchTemplates($subviews, $name, $path);
                $path = $orgPath;
            }
        }

        if (is_string($name)) {

            try {
                $this->smarty->clearAllAssign();
                $path = substr($path, 1);

                $this->assignPageData("default");
                $this->assignPageData($path);
                $this->parseLess($path);
                $this->smarty->assign("menu", $this->menu);
                $content = $this->smarty->fetch($path . ".tpl");
                $this->saveFile($path, "", "html", $content, true, true);
            } catch (Exception $e) {
                // nothing to do here
            }
        }
    }


    /**
     * saves a file
     *
     * @param $path
     * @param $subPath
     * @param $extension
     * @param $content
     * @param $useIndex
     * @param $removeIndex
     */
    private function saveFile($path, $subPath, $extension, $content, $useIndex = true, $removeIndex = false)
    {


        if ($path == "index" || $useIndex == false) {
            $outputFile = $this->root . ".." . DIRECTORY_SEPARATOR . $subPath . $path . "." . $extension;
        } else {
            if ($removeIndex) {
                $path = preg_replace("/^index\//", "", $path);
            }
            $outputFile = $this->root . ".." . DIRECTORY_SEPARATOR . $subPath . $path . "/index." . $extension;
        }
        $outputDir = dirname($outputFile);
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0777, true);
        }
        if (!file_exists($outputFile)) {
            touch($outputFile);
        }
        file_put_contents($outputFile, $content);
    }


    /**
     * assigns yaml data to the page
     *
     * @param $path
     */
    private function assignPageData($path)
    {
        $file = $this->page . "data" . DIRECTORY_SEPARATOR . $path . ".yml";
        if ($path != "default") {
            $this->smarty->assign("pagePath", $path);
        }
        if (file_exists($file)) {
            $data = $this->yaml->parse(file_get_contents($file));
            $this->smarty->assign($data);
        }
    }


    /**
     * compiles the less files to css
     *
     * @param $path
     */
    private function parseLess($path)
    {
        $file = $this->page . "assets" . DIRECTORY_SEPARATOR . "less" . DIRECTORY_SEPARATOR . "pages" . DIRECTORY_SEPARATOR . $path . ".less";
        if (file_exists($file)) {
            $less = $this->less->parseFile($file);
            $css  = $less->getCss();

            $this->saveFile($path, "css" . DIRECTORY_SEPARATOR, "css", $css, false);
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