<?php

namespace WorkingTitle\Engine\Structs;


class Variables extends Storage
{

    /** @var  Storage $cached */
    public $cached;

    /** @var  Storage $unCached */
    public $unCached;


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
        if (!$cached) {
            return $this->unCached->set($path, $value);
        } else {
            return $this->cached->set($path, $value);
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
        if ($onlyCached) {
            return $this->cached->get();
        }
        $this->init();
        $merged = new Storage();
        if (!is_null($this->cached->get())) {
            $merged->merge($this->cached->get());
        }
        if (!is_null($this->unCached->get())) {
            $merged->merge($this->unCached->get());
        }

        return $merged->get($path);
    }


}