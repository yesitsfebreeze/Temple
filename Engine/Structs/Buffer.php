<?php


namespace Temple\Engine\Structs;

use Temple\Engine\Exception\Exception;


/**
 * Class Buffer
 *
 * @package Temple\Engine\Structs
 */
class Buffer
{

    /** @var array $buffer */
    private $buffer = array();


    /**
     * @param $content
     *
     * @return bool
     * @throws Exception
     */
    public function insertAfter($content)
    {
        if (!is_string($content)) {
            throw new Exception(401, "The %Buffer->add()% method can only strings!");
        }

        $this->buffer[] = $content;

        return true;
    }

    /**
     * @param $content
     *
     * @return bool
     * @throws Exception
     */
    public function insertBefore($content)
    {
        if (!is_string($content)) {
            throw new Exception(401, "The %Buffer->add()% method can only strings!");
        }

        array_unshift($this->buffer,$content);

        return true;
    }


    /**
     * @return bool
     */
    public function clear()
    {
        $this->buffer = array();
        return true;
    }


    /**
     * @return string
     */
    public function getContent()
    {
        return implode("", $this->buffer);
    }


    /**
     * @return array
     */
    public function getBuffer()
    {
        return $this->buffer;
    }

}