<?php

namespace Temple\Languages\Html\Plugins;


use Temple\Engine\EventManager\Event;


/**
 * Class VariablesPlugin
 *
 * @package Temple\Languages\Core\Plugins
 */
class CleanPhpTagsPlugin extends Event
{

    /** @var array $buffer */
    private $buffer = array();


    /**
     * cleans away nested php tags
     *
     * @param $content
     *
     * @return string
     */
    public function dispatch($content)
    {
        $content = $this->getTags($content);
        $content = $this->replaceHashes($content);

        return $content;
    }


    private function getTags($content)
    {
        preg_match("/<\?php(?!.*<\?php).*?\?>/", $content, $matches);
        if (sizeof($matches) > 0) {
            $hash                  = md5($matches[0]);
            $this->buffer[ $hash ] = $matches[0];
            $content               = str_replace($matches[0], $hash, $content);
            $content               = $this->getTags($content);
        }

        return $content;
    }


    /**
     * reverts the hashes and just leaves the outer php tag
     *
     * @param string $content
     *
     * @return string $content
     */
    private function replaceHashes($content)
    {
        $matching = array();

        foreach ($this->buffer as $hash => $value) {
            if (strpos($content, $hash) !== false) {
                $matching[] = $hash;
            }

        }

        foreach ($matching as $hash) {
            $old     = $this->buffer[ $hash ];
            $content = str_replace($hash, $old, $content);
            unset($this->buffer[ $hash ]);
        }


        foreach ($this->buffer as $hash => $value) {
            $value   = preg_replace("/^<\?php/", "", $value);
            $value   = preg_replace("/\?>$/", "", $value);
            $content = str_replace($hash, $value, $content);
        }

        return $content;

    }

}


?>