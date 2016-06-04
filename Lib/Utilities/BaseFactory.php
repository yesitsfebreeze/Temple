<?php

namespace Temple\Utilities;


use Temple\Exception\TempleException;


/**
 * Class configFactory
 *
 * @package Contentmanager\Services
 */
abstract class BaseFactory implements FactoryInterface
{

    /**
     * @param string $class
     * @return mixed
     * @throws \Exception
     */
    public function create($class)
    {
        $class = $this->check($class);

        if (!$class) {
            return null;
        }

        return new $class();
    }


    /**
     * @param string $class
     * @return string|null
     * @throws \Exception
     */
    abstract public function check($class);


    /**
     * @param $class
     * @return string
     * @throws \Exception
     */
    protected function getClassName($class)
    {
        if (is_null($class)) {
            return $class;
        }

        if (!gettype($class) == "string") {
            throw new TempleException("Class name must be a string");
        }

        $class = $this->cleanClassName($class, ' ');
        $class = $this->cleanClassName($class, '_');
        $class = ucfirst($class);

        return $class;

    }


    /**
     * replaces all "$char" characters and the following character with the according uppercase character
     *
     * @param string $class
     * @param string $char
     * @return string $class
     */
    protected function cleanClassName($class, $char)
    {
        $char  = preg_quote($char);
        $class = preg_replace_callback('@' . $char . '+(\w)@',
            function ($matches) {
                return strtoupper($matches[1]);
            },
            $class
        );

        return $class;
    }

}