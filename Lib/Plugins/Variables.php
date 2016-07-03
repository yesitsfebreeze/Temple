<?php


namespace Underware\Plugins;


use Underware\Models\HtmlNode;
use Underware\Models\Plugin;


class Variables extends Plugin
{

    /** @var  string $symbol */
    private $symbol;



    # no array assign since we have deep assign
    # if is numeric then check if . or . and parse the respective value
    # if is true or false assign true or false
    # else wrap with string
    # if its another variable just assign it
    # if its an object assign the object

    # i also need to implement an variable getter for inline stuff

    # serialize the object and save it in the cache



    /**
     * @param HtmlNode $args
     *
     * @return bool
     */
    public function check($args)
    {

//        if ($args instanceof HtmlNode) {
//
//            $this->symbol = $this->Instance->Config()->get("template.symbols.variable");
//            $tag          = $args->get("tag.definition");
//
//            return $tag[0] == $this->symbol;
//        }
//
        return false;
    }


    /**
     * @var HtmlNode $node
     * @return HtmlNode $node
     * hast to return $node
     */
    public function process($node)
    {
//        # hide it if we have a variable tag
//        $node->set("tag.display", false);
//        $name  = $this->getVariableName($node);
//        $value = $this->parseVariable($node);
//        var_dump($name);
//        var_dump($value);
//        $this->Instance->Variables()->set($name, $value);

        return $node;
    }

//
//    /**
//     * @param HtmlNode $node
//     *
//     * @return bool|mixed
//     */
//    private function parseVariable(HtmlNode $node)
//    {
//        $value = preg_replace("/^\s*?=\s*?/", "", $node->get("attributes"));
//        if ($node->has("children") && sizeof($node->get("children")) > 0) {
//            $children = $node->get("children");
//
//            $array = array();
//
//            /** @var $child HtmlNode */
//            foreach ($children as $child) {
//                $name  = $this->getVariableName($child);
//                $name  = $this->parseValue($name);
//                $value = $this->parseVariable($child);
//                $child->set("display", false);
//                if ($value) {
//                    $array[ $name ] = $value;
//                } else {
//                    array_push($array, $name);
//                }
//
//            }
//
//
//            return $array;
//        } else {
//            return $this->parseValue($value);
//        }
//    }

//
//    /**
//     * @param $value
//     *
//     * @return float|int|mixed|string
//     */
//    private function parseValue($value)
//    {
//
//        if (is_string($value)) {
//            $value = trim($value);
//        }
//
//        if ($value[0] == "@") {
//            $var = $this->getVariableName(false, $value);
//
//            return $this->Instance->Variables()->get($var);
//        } else {
//            # test if we have a forced string
//            $isString = false;
//            if ($value[0] == "'" || $value[0] == '"') {
//                $end = $value[ strlen($value) - 1 ];
//                if ($end == $value[0]) {
//                    $isString = true;
//                }
//            }
//            if ($isString) {
//                $value = substr($value, 1, strlen($value) - 2);
//
//                return $value;
//            } else {
//                $int = preg_replace("/[0-9]/", "", $value);
//                if (strlen($int) == 0) {
//                    return intval($value);
//                } else if ($int == "," || $int == ".") {
//                    $value = str_replace(",", ".", $value);
//
//                    return floatval($value);
//                } else {
//                    return $value;
//                }
//            }
//        }
//    }

//
//    /**
//     * @param HtmlNode|bool $node
//     * @param string        $name
//     *
//     * @return string
//     */
//    private function getVariableName(HtmlNode $node, $name = "")
//    {
//        if ($name == "") $name = $node->get("tag.definition");
//        $symbol = preg_quote($this->symbol);
//        $name   = preg_replace("/^" . $symbol . "/", "", $name);
//        $name   = preg_replace("/\./", "/", $name);
//
//        return $name;
//    }


//    private function getMatchPattern()
//    {
//        $matchPattern = "/";
//        $matchPattern .= preg_quote($this->configService->get("variable_symbol"));
//        $matchPattern .= preg_quote($this->configService->get("left_delimiter"));
//        $matchPattern .= "(.*?)";
//        $matchPattern .= preg_quote($this->configService->get("right_delimiter"));
//        $matchPattern .= "/";
//
//        return $matchPattern;
//    }
//
//
//    public function processOutput($output)
//    {
//
//        $matchPattern = $this->getMatchPattern();
//        $output       = preg_replace_callback(
//            $matchPattern,
//            function ($hits) {
//                $var = $this->Instance->Variables()->get($hits[1]);
//                if (is_array($var)) {
//                    $array = "[";
//                    foreach ($var as $key => $value) {
//                        $array .= "'" . $key . "' => '" . $value . "',";
//                    }
//                    $array = trim($array, ",");
//                    $array .= "]";
//
//                    return $array;
//                }
//                if (is_string($var)) {
//
//                    if ($var == "!false" || $var == "true" || $var == "!!") {
//                        return "true";
//                    }
//                    if ($var == "!true" || $var == "false" || $var == "!") {
//                        return "false";
//                    }
//
//                    return $var;
//                }
//
//                if (is_double($var) || is_integer($var)) {
//                    return $var;
//                }
//
//                return "false";
//            },
//            $output
//        );
//
//        return $output;
//    }

}