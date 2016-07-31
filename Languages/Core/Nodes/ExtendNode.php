<?php

namespace WorkingTitle\Languages\Core\Nodes;


use WorkingTitle\Engine\Structs\Dom;
use WorkingTitle\Engine\Exception\Exception;
use WorkingTitle\Engine\Structs\Node\Node;


class ExtendNode extends Node
{


    /** @inheritdoc */
    public function check()
    {
        if ($this->getTag() == "extends" || $this->getTag() == "extend") {
            return true;
        }

        return false;
    }


    /**
     * @return $this
     * @throws Exception
     */
    public function setup()
        {
        if ($this->getContent() == "") {
            throw new Exception(1, "Please specify a file", $this->getFile(), $this->getLine());
        }

        // todo: relative file
        try {
            if ($this->getContent() == $this->getNamespace()) {
                $Dom = $this->Instance->Template()->dom($this->getContent(), $this->getLevel() + 1);
            } else {
                $Dom = $this->Instance->Template()->dom($this->getContent());
            }
        } catch (Exception $e) {

            if ($e->getCustomCode() == 0) {
                throw new Exception(1, "Can't extend file %" . $this->getContent() . "% because it doesn't exist!", $this->getFile(), $this->getLine());
            }

            throw $e;
        }

        /** @var Dom $currentDom */
        $currentDom = $this->getDom();
        $currentDom->setParentDom($Dom);
        $currentDom->setExtending(true);

        return $this;
    }


    /**
     * creates the output
     *
     * @return string
     */
    public function compile()
    {
        $output = "";
        if ($this->Instance->Config()->isShowBlockComments()) {
            $output = "<!-- " . trim($this->plain) . " - " . $this->getRelativeFile() . "-->";
        }

        return $output;
    }


}


