<?php

namespace Underware\Nodes\Base;


use Underware\Engine\Exception\Exception;
use Underware\Engine\Structs\Language;
use Underware\Engine\Structs\Node;


class UseNode extends Node
{


    /** @inheritdoc */
    public function check()
    {

        $matches = (strtolower($this->getTag()) == "use");

        if ($matches && $this->getLine() == 0) {
            return true;
        } elseif ($matches && $this->getLine() != 0) {
            throw new Exception("%use% statement must be at first line!", $this->getFile(), $this->getLine());
        }

        return false;
    }


    /**
     * converts the line into a node
     *
     * @return $this
     * @throws Exception
     */
    public function setup()
    {
        $lang           = $this->getLanguage();
        $injectionClass = "Languages/" . $lang . "Language";

        if (!$this->InjectionManager->checkInstance($injectionClass)) {
            $langClass = str_replace("/", "\\", "/Underware/" . $injectionClass);
            /** @var Language $lang */
            if (!class_exists($langClass)) {
                throw new Exception("The language class %" . $langClass . "% does not exist!");
            }
            $lang = new $langClass();
            $this->InjectionManager->registerDependency($lang);
            $lang->register();
        } else {
            /** @var Language $lang */
            $lang = $this->InjectionManager->getInstance($injectionClass);
            $lang->register();
        }


        return $this;
    }


    /**
     * returns the language class
     *
     * @return string
     */
    public function getLanguage()
    {
        return ucfirst(strtolower(trim(str_replace($this->getTag(), "", $this->plain))));
    }


    /**
     * creates the output
     *
     * @return string
     */
    public function compile()
    {
        return $this->plain;
    }


}