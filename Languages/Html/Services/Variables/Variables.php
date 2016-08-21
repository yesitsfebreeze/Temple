<?php

namespace Temple\Engine\Structs;


class Variables extends Storage
{

    /** @var  Storage $cached */
    public $cached;

    /** @var  Storage $unCached */
    public $unCached;

    /** @var  Storage $scoped */
    public $scoped;

    /** @var bool $isScoped */
    public $isScoped = false;


    /**
     * setup Storage Objects
     */
    private function init()
    {
        if (!$this->cached instanceof Storage) {
            $this->cached = new Storage();
        }
        if (!$this->unCached instanceof Storage) {
            $this->unCached = new Storage();
        }
        if (!$this->scoped instanceof Storage) {
            $this->scoped = new Storage();
        }
    }


    /**
     * @param      $path
     * @param      $value
     * @param bool $cached
     *
     * @return bool
     */
    public function set($path, $value, $cached = true)
    {
        $this->init();

        if ($this->isScoped) {
            return $this->scoped->set($path, $value);
        } else {
            if (!$cached) {
                return $this->unCached->set($path, $value);
            } else {
                return $this->cached->set($path, $value);
            }
        }

    }


    /**
     * @param null $path
     * @param bool $onlyCached
     * @param bool $echo
     *
     * @return mixed
     */
    public function get($path = null, $onlyCached = false, $echo = false)
    {


        if ($onlyCached && !$this->isScoped) {
            return $this->cached->get($path);
        }
        $this->init();
        $merged = new Storage();
        if (!is_null($this->cached->get())) {
            $merged->merge($this->cached->get());
        }
        if (!is_null($this->unCached->get())) {
            $merged->merge($this->unCached->get());
        }

        if ($this->isScoped) {
            $this->scoped->merge($merged->get());

            $var = $this->scoped->get($path);
            if ($echo) {
                $var = $this->convertToEchoOutput($var);
            }

            return $var;
        }

        $var = $merged->get($path);
        if ($echo) {
            $var = $this->convertToEchoOutput($var);
        }

        return $var;
    }


    /**
     * converts variable to save echo output
     *
     * @param  mixed $variable
     *
     * @return string
     */
    private function convertToEchoOutput($variable)
    {
        if (is_array($variable)) {
            $variable = implode(",", $variable);
        } else if (is_bool($variable)) {
            $variable = (!$variable) ? "false" : "true";
        }

        return (string) $variable;
    }


    /**
     * starts a new scope session
     */
    public function scope()
    {
        $this->isScoped = true;
    }


    /**
     * stops the current scope
     */
    public function unScope()
    {
        $this->scoped   = new Storage();
        $this->isScoped = false;
    }

}