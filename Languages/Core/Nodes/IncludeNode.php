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


    public function check()
    {
        if ($this->getTag() == "include") {
            return true;
        }

        return false;
    }


    public function setup()
    {
        $this->includeFile = $this->getIncludeFile();

        return $this;
    }


    private function getIncludeFile()
    {
        return trim(preg_replace("/^" . $this->getTag() . "/", "", trim($this->plain)));
    }


    public function compile()
    {
        if (substr($this->includeFile, 1) != "/") {
            $prefix            = preg_replace("/\/([^\/]*?$)/", "/", $this->getNamespace());
            $this->includeFile = $prefix . $this->includeFile;
        }
        $includeFile = $this->Instance->Template()->fetch($this->includeFile);

        $this->Instance->Cache()->addDependency($this->getNamespace(),$this->includeFile);
        return "<?php include_once('" . $includeFile . "'); ?>";
    }
}