<?php

namespace Caramel;

/**
 *
 * Class Caramel_Plugin_If
 *
 * @purpose: creates an if condition
 * @usage: if @myvar == false
 * @autor: Stefan Hövelmanns - hvlmnns.de
 * @License: MIT
 * @package Caramel
 *
 */
class Caramel_Plugin_If extends PluginBase
{

    /** @var int $position */
    public $position = 0;

    public function process($node)
    {

        if ($node->get("tag") == "if") {
            $node = $this->createCondition($node);

            $node->set("start/prefix", "<?php if (");
            $node->set("start/tag", "");
            $node->set("start/postfix", " ) { ?>");
            $node->set("end/prefix", "<?php ");
            $node->set("end/tag", "");
            $node->set("end/postfix", "} ?>");
        }

        return $node;
    }

    private function createCondition($node)
    {
        $condition = $node->get("attributes");

        $condition = $this->normalizeOperator($condition, "=");
        $condition = $this->normalizeOperator($condition, ">", true);
        $condition = $this->normalizeOperator($condition, "<", true);
        $condition = $this->normalizeOperator($condition, "|");
        $condition = $this->normalizeOperator($condition, "&");
        $condition = $this->normalizeOperator($condition, "!");

        $condition = $this->cleanOperators($condition);
        $condition = $this->fixSpecialCases($condition);
        $condition = $this->cleanOperators($condition);

        $node->set("attributes", $condition);

        return $node;
    }

    private function normalizeOperator($condition, $symbol, $single = false)
    {
        $condition = str_replace("$symbol", " $symbol ", $condition);
        $condition = str_replace(" $symbol  $symbol", " $symbol$symbol", $condition);
        if (!$single) {
            $condition = str_replace(" $symbol ", " $symbol$symbol ", $condition);
        } else {
            $condition = preg_replace("/\s*?$symbol\s*?==\s*?/", " $symbol= ", $condition);
        }
        $condition = str_replace("$symbol$symbol  $symbol$symbol", "$symbol$symbol$symbol", $condition);

        return $condition;
    }

    private function cleanOperators($condition)
    {
        $operators      = array("\&\&", "\|\|", "\=\=\=", "\=\=", "\>\=", "\<\=", "\<", "\>", "\!\=");
        $cleanOperators = array("&&", "||", "===", "==", ">=", "<=", "<", ">", "!=");
        foreach ($operators as $number => $operator) {
            preg_match("/\s*?$operator\s+/", $condition, $matches);
            $match     = $matches[0];
            $condition = str_replace($match, ' ' . $cleanOperators[ $number ] . ' ', $condition);
        }

        return $condition;
    }

    private function fixSpecialCases($condition)
    {
        $condition = str_replace("= ==", "===", $condition);
        $condition = str_replace("! ==", "!=", $condition);
        $condition = str_replace("!!=", "!=", $condition);
        $condition = str_replace("!!", "!=", $condition);
        $condition = str_replace("!===", "!==", $condition);
        $condition = str_replace("!= @", "!@", $condition);
        $condition = str_replace("!= (", "!(", $condition);


        return $condition;

    }


}