<?php

namespace Temple\Plugins\Core;


use Temple\Exception\TempleException;
use Temple\Models\NodeModel;
use Temple\Models\PluginModel;


/**
 * to implement a plugin, you have to follow a name convention.
 * The File name represents the plugin class name.
 * Each Plugins class has to be prefixed with with Temple(Type)
 * Example given:
 *      filename = MyPlugin.php
 *      classname = Temple_Plugin_MyPlugin
 * Class Temple_Plugin_MyPlugin
 *
 * @purpose : explains how to use plugins
 * @usage   : none
 * @author   : Stefan Hövelmanns
 * @License : MIT
 * @package Temple
 */
class Variables extends PluginModel
{

    /** @var  string $sign */
    private $sign;


    /**
     * @return int;
     */
    public function position()
    {
        return 9999999991;
    }


    /**
     * @param NodeModel $node
     * @return bool
     * @throws TempleException
     */
    public function check(NodeModel $node)
    {
        $this->sign = $this->configService->get("variable_symbol");
        $tag        = $node->get("tag.tag");

        return $tag[0] == $this->sign;
    }

    /**
     * @var NodeModel $node
     * @return NodeModel $node
     * hast to return $node
     */
    public function process(NodeModel $node)
    {
        # hide it if we have a variable tag
        $node->set("display", false);
        $name  = $this->getVariableName($node);
        $value = $this->parseVariable($node);
        $this->vars->set($name, $value);

        return $node;
    }


    /**
     * @param NodeModel $node
     * @return bool|mixed
     */
    private function parseVariable(NodeModel $node)
    {
        $value = preg_replace("/^\s*?=\s*?/", "", $node->get("attributes"));
        if ($node->has("children")) {
            $children = $node->get("children");

            $array = array();

            /** @var $child NodeModel */
            foreach ($children as $child) {
                $name  = $this->getVariableName($child);
                $name  = $this->parseValue($name);
                $value = $this->parseVariable($child);
                $child->set("display", false);
                if ($value) {
                    $array[ $name ] = $value;
                } else {
                    array_push($array, $name);
                }

            }

            return $array;
        } else {
            return $this->parseValue($value);
        }
    }


    /**
     * @param $value
     * @return float|int|mixed|string
     */
    private function parseValue($value)
    {

        if (is_string($value)) {
            $value = trim($value);
        }

        if ($value[0] == "@") {
            $var = $this->getVariableName(false, $value);

            return $this->vars->get($var);
        } else {
            # test if we have a forced string
            $isString = false;
            if ($value[0] == "'" || $value[0] == '"') {
                $end = $value[ strlen($value) - 1 ];
                if ($end == $value[0]) {
                    $isString = true;
                }
            }
            if ($isString) {
                $value = substr($value, 1, strlen($value) - 2);

                return $value;
            } else {
                $int = preg_replace("/[0-9]/", "", $value);
                if (strlen($int) == 0) {
                    return intval($value);
                } else if ($int == "," || $int == ".") {
                    $value = str_replace(",", ".", $value);

                    return floatval($value);
                } else {
                    return $value;
                }
            }
        }
    }


    /**
     * @param NodeModel|bool $node
     * @param string         $name
     * @return string
     */
    private function getVariableName(NodeModel $node, $name = "")
    {
        if ($name == "") $name = $node->get("tag.tag");
        $symbol = preg_quote($this->sign);
        $name   = preg_replace("/^" . $symbol . "/", "", $name);
        $name   = preg_replace("/\./", "/", $name);

        return $name;
    }

    private function getMatchPattern()
    {
        $matchPattern = "/";
        $matchPattern .= preg_quote($this->configService->get("variable_symbol"));
        $matchPattern .= preg_quote($this->configService->get("left_delimiter"));
        $matchPattern .= "(.*?)";
        $matchPattern .= preg_quote($this->configService->get("right_delimiter"));
        $matchPattern .= "/";
        return $matchPattern;
    }
    
    
    public function processOutput($output)
    {

        $matchPattern = $this->getMatchPattern();
        $output       = preg_replace_callback(
            $matchPattern,
            function ($hits) {
                $var = $this->vars->get($hits[1]);
                if (is_array($var)) {
                    $array = "[";
                    foreach ($var as $key => $value) {
                        $array .= "'" . $key . "' => '" . $value . "',";
                    }
                    $array = trim($array, ",");
                    $array .= "]";

                    return $array;
                }
                if (is_string($var)) {

                    if ($var == "!false" || $var == "true" || $var == "!!") {
                        return "true";
                    }
                    if ($var == "!true" || $var == "false" || $var == "!") {
                        return "false";
                    }

                    return $var;
                }

                if (is_double($var) || is_integer($var)) {
                    return $var;
                }

                return "false";
            },
            $output
        );

        return $output;
    }

}