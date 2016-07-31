<?php

namespace Rite\Languages\Html\Nodes;


use Rite\Engine\Structs\Node\Node;


class HtmlNode extends Node
{


    /** @var array $selfClosingTags */
    private $selfClosingTags = array("!doctype", "br", "area", "base", "col", "command", "embed", "hr", "img", "input", "keygen", "link", "meta", "param", "source", "track", "wbr");

    /** @var array $inlineTags */
    private $inlineTags = array("b", "big", "i", "small", "tt", "abbr", "acronym", "cite", "code", "dfn", "em", "kbd", "strong", "samp", "var", "a", "bdo", "br", "img", "map", "object", "q", "script", "span", "sub", "sup", "button", "input", "label", "select", "textarea");

    /** @var array $tags */
    private $tags = array("!doctype", "doctype", "html", "head", "title", "base", "link", "meta", "style", "script", "noscript", "body", "section", "nav", "article", "aside", "h1", "h2", "h3", "h4", "h5", "h6", "header", "footer", "address", "main", "p", "hr", "pre", "blockqoute", "ol", "ul", "li", "dl", "dd", "figure", "figcaption", "div", "a", "em", "strong", "small", "s", "cite", "q", "dfn", "abbr", "data", "time", "code", "var", "samp", "kbd", "sub", "sup", "i", "b", "u", "mark", "ruby", "rt", "rp", "bdi", "bdo", "span", "br", "wbr", "ins", "del", "img", "iframe", "embed", "object", "param", "video", "audio", "source", "track", "canvas", "map", "area", "svg", "math", "table", "caption", "colgroup", "col", "tbody", "thead", "tfoot", "tr", "td", "th", "form", "fieldset", "legend", "label", "input", "button", "select", "datalist", "optgroup", "option", "textarea", "keygen", "output", "progress", "meter", "details", "summary", "command", "menu");


    /** @inheritdoc */
    public function check()
    {
        if (in_array(strtolower($this->getTag()), $this->tags)) {
            return true;
        }

        return false;
    }


    /**
     * converts the line into a node
     *
     * @return Node|bool
     */
    public function setup()
    {

        if (in_array(strtolower($this->getTag()), $this->selfClosingTags)) {
            $this->setSelfClosing(true);
        };

        return $this;
    }


    /**
     * returns html tag
     *
     * @return string
     */
    public function getTag()
    {
        preg_match("/^(.*?)(?:$|\s)/", trim($this->plain), $tag);
        $tag = trim($tag[0]);
        if (strtolower($tag) == "doctype") {
            $tag = "!DOCTYPE";
        }

        return $tag;
    }


    /**
     * removes the tag name
     *
     * @return mixed
     */
    private function getAttributes()
    {
        $attributes = preg_replace("/^(.*?)(?:$|\s)/", "", trim($this->plain));

        return $attributes;
    }


    /**
     * creates the output
     *
     * @return string
     */
    public function compile()
    {
        $output = "<" . $this->getTag() . " " . $this->getAttributes() . ">";

        /** @var Node $child */
        foreach ($this->getChildren() as $child) {
            $output .= $child->compile();
        }

        if (!$this->isSelfClosing()) {
            $output .= "</" . $this->getTag() . ">";
        }

        return $output;
    }


}