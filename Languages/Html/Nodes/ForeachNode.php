<?php

namespace Rite\Languages\Html\Nodes;


use Rite\Engine\Exception\Exception;
use Rite\Engine\Structs\Node\Node;


class ForeachNode extends Node
{

    /** @var array $tags */
    private $tags = array("each", "foreach", "for");

    /** @var  string $key */
    private $key;

    /** @var  string $itemName */
    private $itemName;

    /** @var  string $iterableName */
    private $iterableName;


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
        $this->key          = $this->getKey();
        $this->itemName     = $this->getItemName();
        $this->iterableName = $this->getIterableName();

        return $this;
    }


    /**
     * returns the two parts of the foreach definition
     *
     * @return array
     * @throws Exception
     */
    private function getParts()
    {
        $clean = str_replace($this->getTag(), "", $this->plain);
        $parts = explode(" in ", $clean);
        if (sizeof($parts) < 2) {
            throw new Exception(1,"Syntax error in iteration! It should be: %for key,item in array%");
        }

        return $parts;
    }


    /**
     * returns the item key if defined
     *
     * @return string
     */
    private function getKey()
    {
        $parts = $this->getParts();
        $part  = $parts[0];
        if (!strpos($part, ",")) {
            return "";
        }

        return trim(explode(",", $part)[0]);
    }


    /**
     * returns the item name
     *
     * @return string
     */
    private function getItemName()
    {
        $parts = $this->getParts();
        $part  = $parts[0];
        if (!strpos($part, ",")) {
            return $part;
        }

        return trim(explode(",", $part)[1]);
    }


    /**
     * returns the array which we want to iterate over
     *
     * @return string
     */
    private function getIterableName()
    {
        $parts = $this->getParts();
        $part  = trim($parts[1]);

        return $part;
    }


    /**
     * creates the output
     *
     * @return string
     */
    public function compile()
    {
        $output = '<?php foreach($this->Variables->get("' . $this->iterableName . '") as $' . ($this->key != "" ? $this->key . ' => $' : "") . $this->itemName . ') { ?>';
        if (is_string($this->key)) {
            $output .= '<?php $this->Variables->set("' . $this->key . '",$' . $this->key . "); ?>";
        }

        if (is_string($this->itemName)) {
            $output .= '<?php $this->Variables->set("' . $this->itemName . '",$' . $this->itemName . "); ?>";
        }

        /** @var Node $child */
        foreach ($this->getChildren() as $child) {
            $output .= $child->compile();
        }

        if (!$this->isSelfClosing()) {
            $output .= "<?php } ?>";
        }

        return $output;
    }


}