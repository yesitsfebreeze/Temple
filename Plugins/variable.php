<?php

namespace Caramel;


use Caramel\Models\Node;


/**
 * to implement a plugin, you have to follow a name convention.
 * The File name represents the plugin class name.
 * Each Plugin class has to be prefixed with with Caramel(Type)
 * Example given:
 *      filename = MyPlugin.php
 *      classname = Caramel_Plugin_MyPlugin
 * Class Caramel_Plugin_MyPlugin
 *
 * @purpose : explains how to use plugins
 * @usage   : none
 * @autor   : Stefan Hövelmanns
 * @License : MIT
 * @package Caramel
 */
class PluginVariable extends Models\Plugin
{

    public $position = 9999999991;


    public function check($node)
    {
        $tag = $node->get("tag.tag");

        return $tag[0] == "@";
    }

    /**
     * @var Node $node
     * @return Node $node
     * hast to return $node
     */
    public function process($node)
    {
        # hide it if we have a variable tag
        $node->set("display", false);
        $name  = $this->getVariableName($node);
        $value = $this->parseVariable($node);
        $this->caramel->vars()->set($name, $value);

        return $node;
    }


    /**
     * @param Node $node
     * @return bool|mixed
     */
    private function parseVariable($node)
    {
        $value = $node->get("attributes");
        if ($node->has("children")) {
            $children = $node->get("children");

            $array = array();

            /** @var $child Node */
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

        if (gettype($value) == "string") {
            $value = trim($value);
        }

        if ($value[0] == "@") {
            $var = $this->getVariableName(false, $value);

            return $this->caramel->vars()->get($var);
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
     * @param Node|bool $node
     * @param string $name
     * @return string
     */
    private function getVariableName($node, $name = "")
    {
        if ($name == "") $name = $node->get("tag.tag");
        $name = preg_replace("/^@/", "", $name);
        $name = preg_replace("/\./", "/", $name);

        return $name;
    }


    public function processOutput($output)
    {

        $matchPattern = "/" . preg_quote($this->caramel->config()->get("variable_match_pattern_start")) . "(.*?)" . preg_quote($this->caramel->config()->get("variable_match_pattern_end")) . "/";
        $output       = preg_replace_callback(
            $matchPattern,
            function ($hits) {
                $var = $this->caramel->vars()->get($hits[1]);
                if (gettype($var) == "array") {
                    $array = "[";
                    foreach ($var as $key => $value) {
                        $array .= "'" . $key . "' => '" . $value . "',";
                    }
                    $array = trim($array, ",");
                    $array .= "]";

                    return $array;
                }

                if (gettype($var) == "string") {
                    if ($var == "!false" || $var == "true" || $var == "!!") {
                        return "true";
                    }
                    if ($var == "!true" || $var == "false" || $var == "!") {
                        return "false";
                    }
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