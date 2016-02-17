<?php

namespace Caramel;

/**
 *
 * Class Caramel_Plugin_Var
 *
 * @purpose: converts variable setter and getters
 * @usage:
 *
 *      assign:
 * @my.variable = "test"
 *      get:
 *          default:
 * @my.variable
 *          escaped
 *              @(my.variable)
 *
 *
 * @autor: Stefan Hövelmanns - hvlmnns.de
 * @License: MIT
 * @package Caramel
 *
 */
class Caramel_Plugin_Var extends PluginBase
{

    /** @var int $position */
    public $position = 0;


    public function process($node)
    {

        if ($node->get("tag") == "var") {
            $node->set("display", false);
            $assign = $this->getAssign($node);
            $this->milk->data($assign["path"], $assign["value"]);
        }

        return $node;
    }

    /**
     * creates an array with the variable path and its value
     *
     * @param Storage $node
     * @return array
     */
    private function getAssign($node)
    {
        $assign = array();
        $attr   = explode(" ", trim($node->get("attributes")));
        $path   = array_shift($attr);
        $val    = trim(implode(" ", $attr));
        $val    = $this->convertValue($val);

        if ($node->get("has_children")) {
            $children = $node->get("children");
            $val      = $this->createArray(array(), $children);
        }

        $assign["path"]  = preg_replace("/\./", "/", $path);
        $assign["value"] = $val;

        return $assign;
    }

    /**
     * creates an multidimensional array from the children
     *
     * @param array $array
     * @param array $nodes
     * @return array
     */
    private function createArray($array, $nodes)
    {
        /** @var Storage $node */
        /** @var string $key */
        foreach ($nodes as $node) {
            $node->set("display", false);

            $key = $node->get("tag");

            if ($node->get("has_children")) {
                $children = $node->get("children");
                $val      = $this->createArray(array(), $children);
            } else {
                $val = trim($node->get("attributes"));
                if ($val != "") $val = $this->convertValue($val);
            }

            if ($val !== "") {
                $array[ $key ] = $val;
            } else {
                $array[] = $key;
            }

        }

        return $array;
    }

    /**
     * @param string $val
     * @return mixed
     */
    private function convertValue($val)
    {
        if (gettype($val) === "string") {

            # see if the string contains characters
            preg_match_all("/([a-zA-Z]|\"|\')/", $val, $words);
            $words = sizeof($words[0]) != 0;

            # see if the string contains numbers
            preg_match_all("/[0-9]/", $val, $numbers);
            $numbers = sizeof($numbers[0]) != 0;

            # see if the string contains dots or commas
            preg_match_all("/\.|\,/", $val, $seperator);
            $seperator = sizeof($seperator[0]) != 0;

            # see if the string is a boolean
            $bool = ($val == "false" || $val == "true");

            if (!$words && !$bool && $numbers) {
                if ($seperator) {
                    $val = floatval($val);
                } else {
                    $val = '{"value": ' . $val . '}';
                    $val = json_decode($val, true)["value"];
                }
            } elseif ($bool) {
                if ($val == "false") $val = false;
                if ($val == "true") $val = true;
            } else {
                $val = preg_replace("/^(\"|\')/", "", $val);
                $val = preg_replace("/(\"|\')$/", "", $val);
            }
        }

        return $val;
    }

//    private function getValue($node)
//    {
//        /** @var Storage $node */
//        $value = trim(str_replace('=', '', $node->get("attributes")));
//        if ($value[0] == '"' && $value[ strlen($value) ] == '"') $value = str_replace('"', '', $value);
//        if ($value[0] == "'" && $value[ strlen($value) ] == "'") $value = str_replace("'", "", $value);
//        if ($value == "true") $value = true;
//        if ($value == "false") $value = false;
//
//        return $value;
//    }
//
//    private function getPath($node)
//    {
//        /** @var Storage $node */
//        return str_replace(".", "/", str_replace('@', '', $node->get("tag")));
//    }
//
//    private function assign($path, $value)
//    {
//        $this->milk->data($path, $value);
//    }
//
//
//    public function postProcess($dom)
//    {
//        # override all variables with php getters
//        $this->replaceVariables($dom);
//
//        return $dom;
//    }
//
//    private function replaceVariables(&$nodes)
//    {
//        foreach ($nodes as $key => $node) {
//            if ($node->get("tag")[0] != "@") {
//                $php = $this->checkForPhp($node);
//                if ($php) {
//                    $node = $this->pregReplace($node, "/@\((.*?)\)/", false, "@(", ")");
//                    $node = $this->pregReplace($node, "/(@.*?)($| |[\"')])/", false);
//                } else {
//                    $node = $this->pregReplace($node, "/@\((.*?)\)/", true, "@(", ")");
//                    $node = $this->pregReplace($node, "/(@.*?)($| |[\"')])/", true);
//                }
//                if ($node->get("has_children")) {
//                    $this->replaceVariables($node->get("children"));
//                }
//            }
//        }
//
//        return $nodes;
//    }
//
//    private function getReplaces($node)
//    {
//        $replaces = array(
//            "tag" => $node->get("tag"),
//            "start/prefix" => $node->get("start/prefix"),
//            "start/tag" => $node->get("start/tag"),
//            "start/postfix" => $node->get("start/postfix"),
//            "end/prefix" => $node->get("end/prefix"),
//            "end/tag" => $node->get("end/tag"),
//            "end/postfix" => $node->get("end/postfix"),
//            "attributes" => $node->get("attributes"),
//        );
//
//        return $replaces;
//    }
//
//    private function checkForPhp($node)
//    {
//        $replaces = $this->getReplaces($node);
//        $inTag    = false;
//        foreach ($replaces as $replace) {
//            if (strpos($replace, "?php")) {
//                $inTag = true;
//            }
//        }
//
//        $plain = strpos($node->get("plain"), "?php");
//        if ($inTag || $plain) {
//            return true;
//        }
//
//        return false;
//    }
}