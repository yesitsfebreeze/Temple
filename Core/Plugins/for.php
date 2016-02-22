<?php

namespace Caramel;


/**
 *
 * Class PluginFor
 *
 * @purpose: handles template extending and block overriding
 * @usage:
 *
 * for @variable as @item
 *      - {@item}
 *
 * * for @variable as @key,@item
 *      - {@key}:{@item}
 *
 * @autor: Stefan Hövelmanns - hvlmnns.de
 * @License: MIT
 * @package Caramel
 *
 */
class PluginFor extends Plugin
{

    /** @var int $position */
    protected $position = 91;

}