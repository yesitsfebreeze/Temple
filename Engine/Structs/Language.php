<?php

namespace Underware\Engine\Structs;


use Underware\Engine\Exception\Exception;
use Underware\Engine\Injection\Injection;


class Language extends Injection implements LanguageInterface
{

    /** @inheritdoc */
    public function register()
    {
        throw new Exception("Please implement the register function for %" . get_class($this) . "%", __FILE__);
    }
}