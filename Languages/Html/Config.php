<?php

namespace Temple\Languages\Html;

use Temple\Engine\LanguageConfig;


/**
 * if you add setter and getter which have an add function
 * the name must be singular
 * Class Config
 *
 * @package Temple\Engine
 */
class Config extends LanguageConfig
{
    /** @var string $name */
    protected $name = "html";

}