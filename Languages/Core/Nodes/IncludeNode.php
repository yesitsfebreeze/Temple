<?php

namespace Temple\Languages\Core\Nodes;


use Temple\Engine\Structs\Node\Node;


/**
 * Class ExtendNode
 *
 * @package Temple\Languages\Core\Nodes
 */
class IncludeNode extends Node
{
    /** @var  string $includeFile */
    private $includeFile;


    /**
     * check if the tag is matching this node
     *
     * @return bool
     */
    public function check()
    {
        if ($this->getTag() == "include") {
            return true;
        }

        return false;
    }


    /**
     * initial setup of the node
     *
     * @return $this
     */
    public function setup()
    {
        $this->includeFile = $this->getIncludeFile();

        return $this;
    }


    /**
     * returns the include file we passed with the node
     *
     * @return string
     */
    private function getIncludeFile()
    {
        $includeFile = trim(preg_replace("/^" . $this->getTag() . "/", "", trim($this->plain)))
        if (substr($includeFile, 1) != "/") {
            $prefix      = preg_replace("/\/([^\/]*?$)/", "/", $this->getNamespace());
            $includeFile = $prefix . $includeFile;
        }
        return $includeFile;
    }


    /**
     * turns the node into php output
     *
     * @return string
     */
    public function compile()
    {
        $includeFile = $this->Instance->Template()->fetch($this->includeFile);
        $this->Instance->Cache()->addDependency($this->getNamespace(), $this->includeFile);
        return "<?php include_once('" . $includeFile . "'); ?>";
    }
}