<?php

namespace Temple\Languages\Core\Nodes;


use Temple\Engine\Exception\Exception;
use Temple\Engine\Structs\Dom;
use Temple\Engine\Structs\Node\Node;


/**
 * Class ExtendNode
 *
 * @package Temple\Languages\Core\Nodes
 */
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
        $this->setCommentNode(true);
        if ($this->getContent() == "") {
            throw new Exception(1, "Please specify a file", $this->getFile(), $this->getLine());
        }

        $fileToExtend = $this->getContent();

        if (substr($this->getContent(), 0, 1) != "/") {
            $templateFolder = preg_replace("/\/[^\/]*?$/", "", $this->getNamespace());
            $fileToExtend   = $templateFolder . "/" . $fileToExtend;
        }

        try {
            if ($fileToExtend == $this->getNamespace()) {
                $Dom = $this->Instance->Template()->dom($fileToExtend, $this->getLevel() + 1);
            } else {
                $Dom = $this->Instance->Template()->dom($fileToExtend);
            }
            $this->Instance->TemplateCache()->update($fileToExtend);
            $this->Instance->TemplateCache()->addDependency($this->getNamespace(), $fileToExtend, false);
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
        if ($this->Instance->Config()->isShowBlockComments() && $this->isShowComment()) {
            $output = "<!-- " . trim($this->plain) . " - " . $this->getRelativeFile() . "-->";
        }

        return $output;
    }


}


