<?php

namespace Docs;


/**
 * Class Docs
 *
 * @package Docs
 */
class MarkDownTree
{

    /** @var string $dir */
    private $dir = "../markdown/";

    /** @var array $files */
    private $files = array();

    /** @var array $dirs */
    private $dirs = array();
    /** @var array $dirs */
    private $tree = array();


    /**
     * Docs constructor.
     */
    public function __construct()
    {
        $this->dir = __DIR__ . "/" . $this->dir;
        $files     = scandir($this->dir . "/parsed");
        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) == "md") {
                if ($file != "ApiIndex.md") {
                    $this->files[] = str_replace(".md", "", $file);
                }
            }
        }
        sort($this->files);
        $this->dir = $this->dir . "dir/";
        $this->createDirectories();
        $this->getDirs($this->dir);
        $this->buildTree();
    }


    /**
     * @return array
     */
    public function getTree()
    {
        return $this->tree;
    }


    /**
     * create directories and move markdown files
     */
    private function createDirectories()
    {
        foreach ($this->files as $file) {
            $path    = explode("-", $file);
            $name    = array_pop($path);
            $Class   = array_pop($path);
            $path    = implode("/", $path) . "/" . $Class;
            $newFile = $this->dir . $path . "/" . $name . ".md";
            $file    = $this->dir . "../parsed/" . $file . ".md";
            if (!is_dir($this->dir . $path)) {
                mkdir($this->dir . $path, 0777, true);
            }
            rename($file, $newFile);
        }
    }


    /**
     * @param $path
     */
    private function getDirs($path)
    {
        $dirs = scandir($path);
        foreach ($dirs as $dir) {
            if ($dir != "." && $dir != "..") {
                if (pathinfo($dir, PATHINFO_EXTENSION) != "md") {
                    $parent       = $path . $dir . "/";
                    $this->dirs[] = $parent;
                    $this->getDirs($parent);
                }
            }
        }
    }


    /**
     * creates the md file tree
     */
    private function buildTree()
    {
        $count = substr_count($this->dir, "/");
        foreach ($this->dirs as $dir) {
            $subCount     = substr_count($dir, "/") - $count;
            $class        = array_reverse(explode("/", rtrim($dir, "/")))[0];
            $file         = rtrim($dir, "/") . ".md";
            $this->tree[] = array("level" => $subCount, "file" => $file);
            file_put_contents($file, "## " . $class);
            $mds = scandir($dir);
            foreach ($mds as $md) {
                if (pathinfo($md, PATHINFO_EXTENSION) == "md") {
                    $this->tree[] = array("level" => $subCount, "file" => $dir . $md);

                }
            }
        }
    }
}

