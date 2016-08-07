<?php

require "vendor/autoload.php";


class Page
{

    /** @var string $root */
    private $root;


    public function __construct()
    {
        $this->root = realpath(__DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }


    public function deploy()
    {
        $pages = $this->getPages();
        var_dump($_SERVER["HTTP_HOST"]);
        var_dump($_SERVER["REQUEST_URI"]);
    }


    private function getPages()
    {
        $yaml   = new \Symfony\Component\Yaml\Yaml();
        $source = $this->root . "pages.yml";
        if (file_exists($source)) {
            return $yaml->parse(file_get_contents($source));
        }

        return null;
    }

}