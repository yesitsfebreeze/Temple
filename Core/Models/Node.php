<?php

namespace Caramel;


/**
 * all Node defaults are set here
 * Class Node
 *
 * @package Caramel
 */
class Node extends Storage
{

    /**
     * Node constructor.
     */
    public function __construct()
    {

        /**
         * the tag name
         *
         * @var string
         */
        $this->set("tag.tag", NULL);

        /**
         * if we want to display the tags
         *
         * @var string
         */
        $this->set("tag.display", NULL);

        /**
         * the tag name of the opening tag
         *
         * @var string
         */
        $this->set("tag.opening.tag", NULL);

        /**
         * if we want to sho/hide the opening tag
         *
         * @var boolean
         */
        $this->set("tag.opening.display", NULL);

        /**
         * opening prefix symbol for the node tag (in most cases <)
         *
         * @var string
         */
        $this->set("tag.opening.prefix", NULL);

        /**
         * opening postfix symbol for the node tag(in most cases >)
         *
         * @var string
         */
        $this->set("tag.opening.postfix", NULL);

        /**
         * if we want to insert some stuff into the node
         *
         * @var string
         */
        $this->set("content", NULL);

        /**
         * the tag name of the closing tag
         *
         * @var string
         */
        $this->set("tag.closing.tag", NULL);

        /**
         * if we want to sho/hide the closing tag
         *
         * @var boolean
         */
        $this->set("tag.closing.display", NULL);

        /**
         * closing prefix symbol for the node tag (in most cases <)
         *
         * @var string
         */
        $this->set("tag.closing.prefix", NULL);

        /**
         * closing postfix symbol for the node tag(in most cases >)
         *
         * @var string
         */
        $this->set("tag.closing.postfix", NULL);

        /**
         * the automatic assigned namespace of a node
         *
         * @var string
         */
        $this->set("namespace", NULL);

        /**
         * the file the node is located
         *
         * @var string
         */
        $this->set("file", NULL);

        /**
         * how often the file was extended
         *
         * @var integer
         */
        $this->set("level", NULL);

        /**
         * line number of the node
         *
         * @var integer
         */
        $this->set("line", NULL);

        /**
         * the complete line as a string
         *
         * @var string
         */
        $this->set("plain", NULL);

        /**
         * number of tabs/spaces we indented
         *
         * @var integer
         */
        $this->set("indent", NULL);

        /**
         * trimmed string without the tag
         *
         * @var string
         */
        $this->set("attributes", NULL);

        /**
         * if we want to render the node or not
         *
         * @var boolean
         */
        $this->set("display", NULL);

        /**
         * whether or not to execute plugins on the node
         *
         * @var boolean
         */
        $this->set("plugins", NULL);

        /**
         * if the node is self closing or not
         *
         * @var boolean
         */
        $this->set("selfclosing", NULL);

        /**
         * the node children
         *
         * @var array
         */
        $this->set("children", NULL);

    }
}
