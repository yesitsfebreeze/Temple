<?php

namespace Caramel\Services;


use Caramel\Exceptions\CaramelException;
use Caramel\Exceptions\ExceptionHandler;
use Caramel\Models\Storage;
use Symfony\Component\Yaml\Yaml;

/**
 * Class CaramelConfig
 *
 * @package Caramel
 */
class Config extends Storage
{

    /** @var  Yaml $yaml */
    private $yaml;


    /**
     * merges a new config file into our current config
     *
     * @param $file
     * @throws CaramelException
     */
    public function addConfigFile($file)
    {
        if (file_exists($file)) {
            $content = file_get_contents($file);
            $config  = $this->yaml->parse($content);
            if (sizeof($config) > 0) {
                $this->merge($config);
            }
        } else {
            throw new CaramelException("Can't find the config file!", $file);
        }
    }


    /**
     * initially sets the required settings
     *
     * @param string $root
     */
    public function setDefaults($root)
    {
        $this->set("templates.dirs", array());
        $this->set("plugins.dirs", array());
        $this->set("framework_dir", $root . "/Core/");
        $this->set("cache_dir", $this->get("cache_dir"));

        # set default self closing items
        $selfclosing = array("br", "area", "base", "col", "command", "embed", "hr", "img", "input", "keygen", "link", "meta", "param", "source", "track", "wbr");
        $this->extend("self_closing", $selfclosing);

        # set default inline items
        $inline = array("b", "big", "i", "small", "tt", "abbr", "acronym", "cite", "code", "dfn", "em", "kbd", "strong", "samp", "var", "a", "bdo", "br", "img", "map", "object", "q", "script", "span", "sub", "sup", "button", "input", "label", "select", "textarea");
        $this->extend("inline_elements", $inline);

        if ($this->get("use_exception_handler")) {
            $this->set("exception_handler", new ExceptionHandler());
        }

    }


    public function setYaml(Yaml $Yaml)
    {
        $this->yaml = $Yaml;
    }

}