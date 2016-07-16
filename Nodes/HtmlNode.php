<?php

namespace Underware\Nodes;


class HtmlNode extends Node
{


    /** @var array $selfClosingTags */
    private $selfClosingTags = array("br", "area", "base", "col", "command", "embed", "hr", "img", "input", "keygen", "link", "meta", "param", "source", "track", "wbr");

    /** @var array $inlineTags */
    private $inlineTags = array("b", "big", "i", "small", "tt", "abbr", "acronym", "cite", "code", "dfn", "em", "kbd", "strong", "samp", "var", "a", "bdo", "br", "img", "map", "object", "q", "script", "span", "sub", "sup", "button", "input", "label", "select", "textarea");

    /** @var array $tags */
    private $tags = array("html", "head", "title", "base", "link", "meta", "style", "script", "noscript", "body", "section", "nav", "article", "aside", "h1", "h2", "h3", "h4", "h5", "h6", "header", "footer", "address", "main", "p", "hr", "pre", "blockqoute", "ol", "ul", "li", "dl", "dd", "figure", "figcaption", "div", "a", "em", "strong", "small", "s", "cite", "q", "dfn", "abbr", "data", "time", "code", "var", "samp", "kbd", "sub", "sup", "i", "b", "u", "mark", "ruby", "rt", "rp", "bdi", "bdo", "span", "br", "wbr", "ins", "del", "img", "iframe", "embed", "object", "param", "video", "audio", "source", "track", "canvas", "map", "area", "svg", "math", "table", "caption", "colgroup", "col", "tbody", "thead", "tfoot", "tr", "td", "th", "form", "fieldset", "legend", "label", "input", "button", "select", "datalist", "optgroup", "option", "textarea", "keygen", "output", "progress", "meter", "details", "summary", "command", "menu");


    /**
     * converts the line into a node
     *
     * @param $line
     *
     * @return Node
     */
    public function create($line)
    {

        return $this;
    }


    public function compile()
    {
        return "test";
    }

}