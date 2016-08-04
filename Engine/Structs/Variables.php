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

    /** @var int $currentScope */
    public $currentScope = 0;

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
            $path = $this->currentScope . "." . $path;

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
     *
     * @return mixed
     */
    public function get($path = null, $onlyCached = false)
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
            $mergedVars = $merged->get();
            $this->scoped->merge($mergedVars);
            $path = $this->currentScope . "." . $path;

            return $this->scoped->get($path);
        }

        return $merged->get($path);
    }


    /**
     * starts a new scope session
     */
    public function scope()
    {
        $this->currentScope = $this->currentScope + 1;
        $this->isScoped     = true;
    }


    /**
     * stops the current scope
     */
    public function unscope()
    {
        $this->isScoped = false;
    }

}