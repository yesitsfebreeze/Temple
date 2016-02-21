<?php

namespace Caramel;

/**
 *
 * to implement a plugin, you have to follow a name convention.
 * The File name represents the plugin class name.
 * Each Plugin class has to be prefixed with with Caramel(Type)
 *
 * Example given:
 *      filename = MyPlugin.php
 *      classname = Caramel_Plugin_MyPlugin
 *
 *
 * Class Caramel_Plugin_MyPlugin
 *
 * @purpose: explains how to use plugins
 * @usage: none
 * @autor: Stefan Hövelmanns
 * @License: MIT
 * @package Caramel
 *
 */
class PluginVar extends IdentifierPlugin
{

    public $position = 0;

    /**
     * @var Storage $node
     * @return Storage $node
     * hast to return $node
     */
    public function process($node)
    {
        if ($node->get("tag")[0] == "@") {
            # hide it if we have a variable tag
            $node->set("display", false);
            $name  = $this->getVariableName($node);
            $value = $this->parseVariable($node);
            $this->caramel->setVariable($name, $value);
        }

        return $node;
    }

    /**
     * @param $node Storage
     * @return bool|mixed
     */
    private function parseVariable($node)
    {
        $value = $node->get("attributes");
        if ($node->get("has_children")) {
            $children = $node->get("children");

            $array = array();

            /** @var $child Storage */
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
            $value    = trim($value);
        }

        if ($value[0] == "@") {
            $var = $this->getVariableName(false,$value);
            return $this->caramel->getVariables($var);
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
     * @param $node Storage
     * @param $name string
     * @return string
     */
    private function getVariableName($node,$name = "")
    {
        if ($name == "") $name = $node->get("tag");
        $name = preg_replace("/^@/", "", $name);
        $name = preg_replace("/\./", "/", $name);

        return $name;
    }

}