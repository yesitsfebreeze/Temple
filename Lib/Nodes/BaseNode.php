<?php

namespace Temple\Nodes;


// todoo: split attributes
// todoo: add find method, maybe even to storage
use Temple\Exception\TempleException;
use Temple\Repositories\StorageRepository;
use Temple\Services\ConfigService;

/**
 * all NodeModel defaults are set here
 * Class NodeModel
 *
 * @package Temple
 */
class BaseNode extends StorageRepository
{


    /** @var ConfigService $configService */
    private $configService;


    public function __construct(ConfigService $configService)
    {
        $this->configService = $configService;
    }


    /**
     * returns the node name
     *
     * @return string
     */
    public function getName()
    {
        return strtolower(str_replace("Node", "", array_reverse(explode("\\", get_class($this)))[0]));
    }


    /**
     * check for different node types
     * and create a model
     *
     * @param $line
     * @return NodeModel
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

        $this->set("info.plain", $line);
        $this->set("info.display", true);
        $this->set("info.plugins", true);

        # clean the node
        unset($this->config);

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
        $indent = substr_count($whitespace, $this->configService->get("template.indent.character"));
        $indent = $indent / $this->configService->get("template.indent.amount");
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
    private function tag($line)
    {
        $tag = array();

        # match all characters until a word boundary or space or end of the string
        preg_match("/^(.*?)(?:$| )/", trim($line), $tagname);
        $tagname = trim($tagname[0]);

        $tag["tag"] = $tagname;
        $tag["display"] = true;
        $tag["opening"] = array();

        $tag["opening"]["display"] = true;
        $tag["opening"]["before"] = $this->configService->get("template.tag.opening.before");
        $tag["opening"]["tag"] = $tagname;
        $tag["opening"]["after"] = $this->configService->get("template.tag.opening.after");

        $tag["closing"]["display"] = true;
        $tag["closing"]["before"] = $this->configService->get("template.tag.closing.before");
        $tag["closing"]["tag"] = $tagname;
        $tag["closing"]["after"] = $this->configService->get("template.tag.closing.after");

        return $tag;
    }


    /**
     * returns the attributes for the current line
     *
     * @param string $line
     * @return string
     */
    private function attributes($line)
    {
        # replace the tag from the beginning of the line and then trim the string
        $tag = preg_quote($this->get("tag.tag"));
        $attributes = trim(preg_replace("/^" . $tag . "/", "", trim($line)));
        $attributes = explode(">", $attributes);
        $attributes = explode(" ", $attributes[0]);
        $attributes = array_filter($attributes);
        foreach ($attributes as &$attribute) {
            $arr = array();
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
        if (in_array($this->get("tag.tag"), $this->configService->get("parser.self closing"))) return true;

        return false;
    }
}
