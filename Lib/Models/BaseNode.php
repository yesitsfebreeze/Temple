<?php

namespace Shift\Models;


// TODO: add find method, maybe even to storage

use Shift\Exception\ShiftException;
use Shift\Utilities\Config;
use Shift\Utilities\Storage;


/**
 * all BaseNode defaults are set here
 * Class BaseNode
 *
 * @package Shift
 */
class BaseNode extends Storage
{


    /** @var Config $Config */
    protected $Config;


    public function __construct(Config $configService)
    {
        $this->Config = $configService;
    }


    public function isFunction()
    {
        return false;
    }


    /**
     * check for different node types
     * and create a model
     *
     * @param $line
     *
     * @return BaseNode
     */
    public function createNode($line)
    {

        # add everything we need to our node
        $this->set("info.type", "node");

        $this->set("tag", $this->tag($line));
        $this->set("attributes", $this->attributes($line));
        $this->set("children", array());
        $this->set("info.indent", $this->indent($line));
        $this->set("info.selfclosing", $this->selfclosing());

        $this->set("info.plain", str_replace("\n", "", $line));
        $this->set("info.display", true);
        $this->set("info.isPlain", false);
        $this->set("info.plugins", true);

        # clean the node
        unset($this->Config);

        return $this;
    }


    /**
     * returns the indent of the current line
     * also initially sets the indent character and amount
     *
     * @param     $line
     *
     * @return float|int
     * @throws ShiftException
     */
    private function indent($line)
    {
        # get tab or space whitespace form the line start
        $whitespace = substr($line, 0, strlen($line) - strlen(ltrim($line)));

        # divide our counted characters trough the amount
        # we used to indent in the first line
        # this should be a non decimal number
        $indent = substr_count($whitespace, $this->Config->get("template.indent.character"));
        $indent = $indent / $this->Config->get("template.indent.amount");
        # if we have a non decimal number return how many times we indented
        if (is_int($indent)) return $indent;

        # else throw an error since the amount of characters doesn't match
        throw new ShiftException("Indent isn't matching!", $this->get("info.file"), $this->get("info.line"));

    }


    /**
     * returns the tag for the current line
     *
     * @param string $line
     *
     * @return string
     */
    protected function tag($line)
    {
        $tag = array();

        # match all characters until a word boundary or space or end of the string
        preg_match("/^(.*?)(?:$| )/", trim($line), $tagName);
        $tagName = trim($tagName[0]);

        $tag["definition"] = $tagName;
        $tag["display"]    = true;
        $tag["opening"]    = array();

        $tag["opening"]["display"]    = true;
        $tag["opening"]["before"]     = $this->Config->get("template.tag.opening.before");
        $tag["opening"]["definition"] = $tagName;
        $tag["opening"]["after"]      = $this->Config->get("template.tag.opening.after");

        $tag["closing"]["display"]    = true;
        $tag["closing"]["before"]     = $this->Config->get("template.tag.closing.before");
        $tag["closing"]["definition"] = $tagName;
        $tag["closing"]["after"]      = $this->Config->get("template.tag.closing.after");

        return $tag;
    }


    /**
     * returns the attributes for the current line
     *
     * @param string $line
     *
     * @return string
     */
    protected function attributes($line)
    {
        $attrs = array();
        $tag   = preg_quote($this->get("tag.definition"));

        # replace the tag from the beginning of the line and then trim the string
        $attributes = trim(preg_replace("/^" . $tag . "/", "", trim($line)));
        $attributes = $this->escapeSpaces($attributes, "'");
        $attributes = $this->escapeSpaces($attributes, '"');
        $attributes = explode(" ", $attributes);
        $attributes = array_filter($attributes);

        foreach ($attributes as &$attribute) {
            $attribute = explode("=", $attribute);
            $name      = $attribute[0];
            if (isset($attribute[1])) {
                $value = $attribute[1];
                $value = preg_replace("/^\'/", '', $value);
                $value = preg_replace('/^\"/', '', $value);
                $value = preg_replace("/\'$/", '', $value);
                $value = preg_replace('/\"$/', '', $value);
            }
            if (!isset($value)) {
                $value = "";
            }
            # revert the space escape
            $value = str_replace("~~~", ' ', $value);
            $attrs[ $name ] = $value;
        }

        return $attrs;
    }


    /**
     * escapes all spaces within quotes
     *
     * @param string $text
     * @param string $quote
     *
     * @return string
     */
    private function escapeSpaces($text, $quote)
    {
        $quote = preg_quote($quote);
        preg_match_all('/(' . $quote . '[^' . $quote . ']*' . $quote . ')|[^' . $quote . ']*/', $text, $matches);

        if (isset($matches[1])) {
            $matches = array_filter($matches[1]);
            foreach ($matches as $match) {
                $newMatch = preg_replace('/\s/', "~~~", $match);
                $text     = str_replace($match, $newMatch, $text);
            }
        }

        return $text;
    }


    /**
     * returns if the current line has a selfClosing tag
     *
     * @return string
     */
    private function selfclosing()
    {
        # check if our tag is in the selfClosing array set in the config
        if (in_array($this->get("tag.definition"), $this->Config->get("parser.selfClosing"))) return true;

        return false;
    }
}
