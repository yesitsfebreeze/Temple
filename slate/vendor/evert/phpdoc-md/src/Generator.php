<?php

namespace PHPDocMD;


use Twig_Environment;
use Twig_Loader_Filesystem;
use Twig_SimpleFilter;

/**
 * This class takes the output from 'parser', and generate the markdown
 * templates.
 *
 * @copyright Copyright (C) Evert Pot. All rights reserved.
 * @author    Evert Pot (https://evertpot.coom/)
 * @license   MIT
 */
class Generator
{
    /**
     * Output directory.
     *
     * @var string
     */
    protected $outputDir;

    /**
     * The list of classes and interfaces.
     *
     * @var array
     */
    protected $classDefinitions;

    /**
     * Directory containing the twig templates.
     *
     * @var string
     */
    protected $templateDir;

    /**
     * A simple template for generating links.
     *
     * @var string
     */
    protected $linkTemplate;

    /**
     * Filename for API Index.
     *
     * @var string
     */
    protected $apiIndexFile;

    /**
     * @var array $nameSpaces
     */
    protected $nameSpaces = array();
    /**
     * @var array $registeredClasses
     */
    protected $registeredClasses = array();
    private $paths = array();


    /**
     * @param array  $classDefinitions
     * @param string $outputDir
     * @param string $templateDir
     * @param string $linkTemplate
     * @param string $apiIndexFile
     */
    function __construct(array $classDefinitions, $outputDir, $templateDir, $linkTemplate = '%c.md', $apiIndexFile = 'ApiIndex.md')
    {
        $this->classDefinitions = $classDefinitions;
        $this->outputDir        = $outputDir;
        $this->templateDir      = $templateDir;
        $this->linkTemplate     = $linkTemplate;
        $this->apiIndexFile     = $apiIndexFile;
    }


    /**
     * Starts the generator.
     */
    function run()
    {

        $twig = $this->getTwig();
        $this->getNameSpaces();
        $GLOBALS['PHPDocMD_registeredClasses'] = $this->registeredClasses;
        $this->createClasses($twig);
        $this->createIncludeFile();
        $this->mergeOutoutFile();
    }


    /**
     * @return Twig_Environment
     */
    private function getTwig()
    {
        $loader = new Twig_Loader_Filesystem($this->templateDir, [
            'cache' => false,
            'debug' => true,
        ]);

        $twig = new Twig_Environment($loader);

        $GLOBALS['PHPDocMD_classDefinitions'] = $this->classDefinitions;
        $GLOBALS['PHPDocMD_linkTemplate']     = $this->linkTemplate;

        $filter = new Twig_SimpleFilter('classLink', ['PHPDocMd\\Generator', 'classLink']);
        $twig->addFilter($filter);

        return $twig;
    }


    /**
     *
     */
    private function getNameSpaces()
    {
        foreach ($this->classDefinitions as $name => $data) {
            $class                             = array_reverse(explode("\\", $name))[0];
            $space                             = str_replace("\\", "/", $name);
            $space                             = preg_replace("/\/" . $class . "$/", "", $space);
            $this->registeredClasses[ $class ] = $name;
            $dir                               = $this->outputDir . '/' . $space;
            if (!isset($this->nameSpaces[ $space ])) {
                $this->nameSpaces[ $space ] = $dir;

            }
        }
    }


    /**
     * @param Twig_Environment $twig
     */
    private function createClasses($twig)
    {
        $keys = array_map('strlen', array_keys($this->nameSpaces));
        array_multisort($keys, SORT_DESC, $this->nameSpaces);
        $this->nameSpaces = array_reverse($this->nameSpaces);
        foreach ($this->classDefinitions as $className => $data) {
            $data["id"] = strtolower(str_replace("\\", "-", $className));
            $dir        = false;
            foreach ($this->nameSpaces as $nameSpace => $path) {
                if (strpos($className, str_replace("/", "\\", $nameSpace)) !== false) {
                    $data["nameSpaceid"] = strtolower(str_replace("\\", "-", $nameSpace));
                    $dir                 = $this->nameSpaces[ $nameSpace ];
                }
            }
            $data["level"] = sizeof(explode("-", $data["id"])) - 1;
            if (!is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
            if (!file_exists($dir . ".md")) {
                $name            = array_reverse(explode("/", $dir))[0];
                $data["section"] = $name;
                $nameSpaceOutput = $twig->render('namespace.twig', $data);
                file_put_contents($dir . ".md", $nameSpaceOutput);
            }

            $data["level"] = $data["level"] + 1;
            $output        = $twig->render('class.twig', $data);
            file_put_contents($dir . "/" . $data['shortClass'] . ".md", $output);
        }
    }


    /**
     * creates the include file for slate
     */
    private function createIncludeFile()
    {
        $outputFile = $this->outputDir . "/../../phpdoc.md";
        $file       = "";
        $namespaces = array();
        $paths      = $this->sortPaths();

        foreach ($paths as $class) {
            $class     = str_replace("\\", "/", $class);
            $namespace = strrev(preg_replace("!^.*?\/!", "", strrev($class)));
            if (!isset($namespaces[ $namespace ]) && strpos($namespace, '/') !== false) {
                $namespaces[ $namespace ] = "set";
                $file .= "    - phpdoc/" . $namespace . ".md\n";
            }
            $file .= "    - phpdoc/" . $class . ".md\n";
        }
        unlink($outputFile);
        file_put_contents($outputFile, $file);
    }


    private function sortPaths()
    {
        $paths = $this->registeredClasses;
        sort($paths);
        return $paths;
    }


    private function subsort($paths)
    {
        
    }


    /**
     *
     */
    private function mergeOutoutFile()
    {
        $outputPath = $this->outputDir . "/../../";
        $outputFile = $outputPath . "index.html.md";

        $output      = file_get_contents($outputPath . "index.md");
        $handwritten = file_get_contents($outputPath . "handwritten.md");
        $phpdoc      = file_get_contents($outputPath . "phpdoc.md");
        $content     = $handwritten . "\n" . $phpdoc;
        $output      = preg_replace("!%%content%%!m", $content, $output);
        touch($outputFile);
        var_dump($output);
        file_put_contents($outputFile, $output);
    }


    /**
     * This is a twig template function.
     * This function allows us to easily link classes to their existing pages.
     * Due to the unfortunate way twig works, this must be static, and we must use a global to
     * achieve our goal.
     *
     * @param string $className
     * @return string
     */
    static function classLink($className)
    {
        $class = $GLOBALS['PHPDocMD_registeredClasses'][ $className ];
        if (isset($class)) {
            $name   = array_reverse(explode("\\", $className))[0];
            $link   = strtolower(str_replace("\\", "-", $class));
            $output = "<a href='#{$link}' title='{$name}'>{$name}</a>";

            return $output;
        } else {
            return $className;
        }
    }
}
