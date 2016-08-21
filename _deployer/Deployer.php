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

    /** @var \Patchwork\JSqueeze $jsSqueezer */
    private $jsSqueezer;

    /** @var string $jsContent */
    private $jsContent;

    /** @var string $environment */
    private $environment;

    /** @var array $pages */
    private $pages = array();

    /** @var array $menu */
    private $menu = array();

    /** @var array $docsMenuName */
    private $docsMenuName = "documentation";

    /** @var array $docsMenu */
    private $docsMenu = array();

    /** @var array $docsSubMenu */
    private $docsSubMenu = array();


    /**
     * Deployer constructor.
     *
     * @param $environment
     * @param $pathPrefix
     */
    public function __construct($environment, $pathPrefix)
    {
        $this->root = realpath(__DIR__) . DIRECTORY_SEPARATOR;
        $this->page = $this->root . ".." . DIRECTORY_SEPARATOR . "_source" . DIRECTORY_SEPARATOR;

        $this->smarty = new Smarty();
        $this->smarty->addTemplateDir($this->page . "templates");

        $this->yaml = new \Symfony\Component\Yaml\Yaml();

        $this->jsSqueezer = new \Patchwork\JSqueeze();

        $this->environment = $environment;
        $this->pathPrefix  = $pathPrefix;

        Less_Autoloader::register();

        date_default_timezone_set("Europe/Berlin");
    }


    /**
     * deploys the pages
     * and all its styles
     */
    public function deploy()
    {
        $this->pages = $this->getPages();
        $this->buildMenu($this->pages);
        $this->buildDocsMenu($this->pages);
        $this->parseLess("../source/all");
        $this->processJs("../source/all");
        $this->copyAssets();
        error_reporting(E_ERROR | E_WARNING | E_PARSE);
        $this->fetchTemplates($this->pages);
    }


    /**
     * @param $pages
     */
    private function buildMenu($pages)
    {
        $menu       = $this->buildSubMenu(array(), $pages["index"]);
        $this->menu = $menu;

    }


    /**
     * @param $pages
     */
    private function buildDocsMenu($pages)
    {
        $docsMenu       = $this->buildSubMenu(array(), $pages["index"][ $this->docsMenuName ], "/" . $this->docsMenuName);
        $this->docsMenu = $docsMenu;
    }


    /**
     * @param $pages
     * @param $name
     */
    private function buildDocsSubMenu($pages, $name)
    {
        $name              = str_replace("_", " ", $name);
        $docsSubMenu       = $this->buildSubMenu(array(), $pages["index"][ $this->docsMenuName ][ $name ], "/" . $this->docsMenuName . "/" . $name);
        $this->docsSubMenu = $docsSubMenu;
    }


    /**
     * @param array  $menu
     * @param        $pages
     * @param string $path
     *
     * @return array
     */
    private function buildSubMenu($menu = array(), $pages, $path = "")
    {

        $iteration = 0;
        if (sizeof($pages) > 0) {
            foreach ($pages as $name => $page) {
                $menu[ $iteration ]                = array();
                $orgPath                           = $path;
                $path                              = $path . "/" . $name;
                $escaped                           = str_replace(" ", "_", $path);
                $template                          = "index/" . substr($escaped . ".tpl", 1);
                $exists                            = $this->smarty->templateExists($template);
                $menu[ $iteration ]["name"]        = $name;
                $menu[ $iteration ]["escapedName"] = str_replace(" ", "_", $name);
                if ($exists) {
                    $menu[ $iteration ]["link"] = $escaped;
                } else {
                    $menu[ $iteration ]["link"] = false;
                }
                $menu[ $iteration ]["children"] = array();

                if (is_array($page)) {
                    $menu[ $iteration ]["children"] = $this->buildSubMenu($menu[ $iteration ]["children"], $page, $path);
                }

                $iteration = $iteration + 1;
                $path      = $orgPath;
            }
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
                $path            = substr($path, 1);
                $path            = str_replace(" ", "_", $path);
                $this->jsContent = "";
                $this->smarty->assign("pathPrefix", $this->pathPrefix);
                $this->assignPageData("default");
                $this->assignPageData($path);
                $this->parseLess($path);
                $this->processJs($path);
                $this->smarty->assign("menu", $this->menu);
                $this->smarty->assign("docsMenu", $this->docsMenu);
                $content = $this->smarty->fetch($path . ".tpl");
                $this->saveFile($path, "", "html", $content, true, true);
            } catch (SmartyException $e) {
                // just catch stuff so smarty wont try to render non existent templates
                // and log the message in the console
                $message = $e->getMessage();
                if ($message != "Unable to load template 'file:.tpl'") {
                    echo "\n";
                    echo $e->getMessage();
                    echo "\n";
                }

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
            chown($outputDir, "hvlmnns");
        }
        if (!file_exists($outputFile)) {
            touch($outputFile);
        }
        file_put_contents($outputFile, $content);
        chmod($outputFile, 0777);
        chown($outputFile, "hvlmnns");
    }


    /**
     * assigns yaml data to the page
     *
     * @param $path
     */
    private function assignPageData($path)
    {
        if ($path != "default") {
            $this->smarty->assign("pagePath", $path);
        }
        $file = $this->page . "data" . DIRECTORY_SEPARATOR . $path . ".yml";
        if ($path != "default") {
            $oldPath   = $path;
            $path      = explode("/", $path);
            $preParent = $path[ sizeof($path) - 2 ];
            $parent    = $path[ sizeof($path) - 1 ];
            $path      = end($path);
            $name      = str_replace("_", " ", $path);

            $this->smarty->assign("pageName", $path);

            if ($parent == $this->docsMenuName) {
                $breadcrumbs = array(
                    "Documentation" => preg_replace("/^index/", "/Temple/", "index/" . $this->docsMenuName)
                );
            }

            if ($preParent == $this->docsMenuName) {
                $this->buildDocsSubMenu($this->pages, $parent);
                $this->smarty->assign("docsSubMenu", $this->docsSubMenu);
                $pages       = array();
                $breadcrumbs = array(
                    "Documentation" => preg_replace("/^index/", "/Temple/", "index/" . $this->docsMenuName),
                    "$name"    => preg_replace("/^index/", "/Temple/", $oldPath)
                );
                foreach ($this->docsSubMenu as $page) {
                    $docPage                = array();
                    $docPage["name"]        = $page["name"];
                    $docPage["escapedName"] = str_replace(" ", "_", $page["name"]);
                    $docPage["file"]        = "index" . $page["link"] . ".tpl";
                    $pages[]                = $docPage;
                }
                $this->smarty->assign("docsPages", $pages);
            }

            $this->smarty->assign("breadcrumbs", $breadcrumbs);

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
        $this->less = new Less_Parser();
        $file       = $this->page . "assets" . DIRECTORY_SEPARATOR . "less" . DIRECTORY_SEPARATOR . "pages" . DIRECTORY_SEPARATOR . $path . ".less";


        if (file_exists($file)) {
            $less = $this->less->parseFile($file);
            $css  = $less->getCss();

            if ($path == "../source/all") {
                $path = "all";
            }


            $this->saveFile($path, "css" . DIRECTORY_SEPARATOR, "css", $css, false);
        }
    }


    /**
     * compiles the less files to css
     *
     * @param $path
     *
     * @throws Exception
     */
    private function processJs($path)
    {

        $prePath = $this->page . "assets" . DIRECTORY_SEPARATOR . "js" . DIRECTORY_SEPARATOR . "pages" . DIRECTORY_SEPARATOR;
        $jsPath  = $prePath . preg_replace("/\/[^\/]*?$/", "", $path) . DIRECTORY_SEPARATOR;
        $file    = $prePath . $path . ".loader.php";

        if ($path == "../source/all") {
            $path = "all";
        }

        if (file_exists($file)) {
            /** @noinspection PhpIncludeInspection */
            include $file;
            if (isset($scripts)) {
                if (is_array($scripts)) {
                    foreach ($scripts as $script) {
                        $script = $jsPath . $script;
                        if (file_exists($script)) {
                            $content         = file_get_contents($script);
                            $content         = $this->jsSqueezer->squeeze($content);
                            $this->jsContent = $this->jsContent . ";" . $content;
                        } else {
                            throw new Exception("could not include js file: " . $script);
                        }
                    }
                    $this->saveFile($path, "js" . DIRECTORY_SEPARATOR, "js", $this->jsContent, false);
                }
            }
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


    /**
     * copy all assets to the root folder
     */
    private function copyAssets()
    {
        $this->copy(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "_source" . DIRECTORY_SEPARATOR . "assets", __DIR__ . DIRECTORY_SEPARATOR . "../assets");
    }


    /**
     * copied files from a to b
     *
     * @param     $source
     * @param     $dest
     * @param int $permissions
     *
     * @return bool
     */
    private function copy($source, $dest, $permissions = 0755)
    {
        if (is_link($source)) {
            return symlink(readlink($source), $dest);
        }

        if (is_file($source)) {
            return copy($source, $dest);
        }

        if (!is_dir($dest)) {
            mkdir($dest, $permissions);
        }

        $dir = dir($source);
        while (false !== $entry = $dir->read()) {
            if ($entry == '.' || $entry == '..') {
                continue;
            }
            $this->copy("$source/$entry", "$dest/$entry", $permissions);
        }

        $dir->close();

        return true;
    }

}