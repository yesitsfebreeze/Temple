<?php

namespace Temple\Engine\Structs\Language;


use Temple\Engine\EventManager\Event;
use Temple\Engine\Exception\Exception;


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
        throw new Exception(1,"Please implement the register function for %" . get_class($this) . "%", __FILE__);
    }
}