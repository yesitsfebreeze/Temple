<?php

namespace Underware\Engine\Structs\Language;


use Underware\Engine\EventManager\Event;
use Underware\Engine\Exception\Exception;


class Language extends Event implements LanguageInterface
{

    /** @inheritdoc */
    public function dispatch($args)
    {
        $this->register();
    }


    /** @inheritdoc */
    public function register()
    {
        throw new Exception("Please implement the register function for %" . get_class($this) . "%", __FILE__);
    }
}