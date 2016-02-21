<?php

namespace Caramel;

/**
 * Class Node
 * @package Caramel
 */
class Node extends Storage
{

    /**
     * all Node defaults are set here
     *
     * Node constructor.
     */
    public function __construct()
    {
        $this->set("namespace", NULL);
        $this->set("file", NULL);
        $this->set("level", NULL);
        $this->set("line", NULL);
        $this->set("indent", NULL);
        $this->set("tag", NULL);
        $this->set("display", NULL);
        $this->set("start/display", NULL);
        $this->set("start/prefix", NULL);
        $this->set("start/tag", NULL);
        $this->set("start/postfix", NULL);
        $this->set("end/display", NULL);
        $this->set("end/prefix", NULL);
        $this->set("end/tag", NULL);
        $this->set("end/postfix", NULL);
        $this->set("attributes", NULL);
        $this->set("process_plugins", NULL);
        $this->set("plain", NULL);
        $this->set("self_closing", NULL);
        $this->set("has_children", NULL);
        $this->set("children", NULL);
    }
}
