<?php

namespace Temple\Models\Nodes;


// TODO: add find method, maybe even to storage

use Temple\Exception\TempleException;
use Temple\Utilities\Config;
use Temple\Utilities\Storage;


/**
 * all BaseNode defaults are set here
 * Class BaseNode
 *
 * @package Temple
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
     * @return float|int
     * @throws TempleException
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
        throw new TempleException("Indent isn't matching!", $this->dom->get("file"), $this->dom->get("line"));

    }


    /**
     * returns the tag for the current line
     *
     * @param string $line
     * @return string
     */
    protected function tag($line)
    {
        $tag = array();

        # match all characters until a word boundary or space or end of the string
        preg_match("/^(.*?)(?:$| )/", trim($line), $tagname);
        $tagname = trim($tagname[0]);

        $tag["name"]    = $tagname;
        $tag["display"] = true;
        $tag["opening"] = array();

        $tag["opening"]["display"] = true;
        $tag["opening"]["before"]  = $this->Config->get("template.tag.opening.before");
        $tag["opening"]["name"]    = $tagname;
        $tag["opening"]["after"]   = $this->Config->get("template.tag.opening.after");

        $tag["closing"]["display"] = true;
        $tag["closing"]["before"]  = $this->Config->get("template.tag.closing.before");
        $tag["closing"]["name"]    = $tagname;
        $tag["closing"]["after"]   = $this->Config->get("template.tag.closing.after");

        return $tag;
    }


    /**
     * returns the attributes for the current line
     *
     * @param string $line
     * @return string
     */
    protected function attributes($line)
    {
        # replace the tag from the beginning of the line and then trim the string
        $tag        = preg_quote($this->get("tag.name"));
        $attributes = trim(preg_replace("/^" . $tag . "/", "", trim($line)));
        $attributes = explode(">", $attributes);
        $attributes = explode(" ", $attributes[0]);
        $attributes = array_filter($attributes);
        foreach ($attributes as &$attribute) {
            $arr   = array();
            $attrs = explode("=", $attribute);
            if (isset($attrs[0])) {
                $arr["name"] = $attrs[0];
                if (isset($attrs[1])) {
                    $arr["value"] = $attrs[1];
                }
            }
            $attribute = $arr;
        }

        return $attributes;
    }


    /**
     * returns if the current line has a self closing tag
     *
     * @return string
     */
    private function selfclosing()
    {
        # check if our tag is in the self closing array set in the config
        if (in_array($this->get("tag.name"), $this->Config->get("parser.self closing"))) return true;

        return false;
    }
}
