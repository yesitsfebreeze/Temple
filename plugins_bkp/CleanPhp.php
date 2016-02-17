<?php

namespace Caramel;

/**
 *
 * Class Caramel_Plugin_CleanPhp
 *
 * @purpose: replaces all nested php tags
 * @usage: automatic
 * @autor: Stefan Hövelmanns - hvlmnns.de
 * @License: MIT
 * @package Caramel
 *
 */
class Caramel_Plugin_CleanPhp extends PluginBase
{

    /** @var int $position */
    protected $position = 1000;

    /** @var array $buffer */
    private $buffer = array();

    /**
     * @param $output
     * @return bool|mixed|string
     */
    public function processOutput($output)
    {
        $output = preg_replace("/\?>\s*?<\?php/", "", $output);

        $output = $this->bufferPhp($output);
        $output = $this->replacePhp($output);

        return $output;
    }

    /**
     *
     * gets all php tags recursively and pushes it into the buffer
     *
     * @param $output
     * @return mixed
     */
    private function bufferPhp($output)
    {
        $openTag  = "<?php";
        $closeTag = "?>";
        $close    = strpos($output, "?>");
        $open     = strpos($output, "<?php");
        if ($open && $close) {
            $chunk                = strrev(substr($output, 0, $close));
            $pos                  = strpos($chunk, strrev($openTag));
            $chunk                = $openTag . strrev(substr($chunk, 0, $pos)) . $closeTag;
            $md5                  = md5($chunk);
            $output               = str_replace($chunk, $md5, $output);
            $this->buffer[ $md5 ] = $chunk;
            return $this->bufferPhp($output);
        }

        return $output;
    }


    /**
     *
     * replaces the buffer and wraps a php tag around it
     *
     * @param $output
     * @return mixed
     */
    private function replacePhp($output) {
        foreach ($this->buffer as $parnetHash => $buff) {
            foreach ($this->buffer as $hash => $temp) {
                $buff = str_replace($hash, $temp, $buff);
            }
            $buff   = str_replace("<?php", "", $buff);
            $buff   = str_replace("?>", "", $buff);
            $buff   = "<?php " . $buff . " ?>";
            $output = str_replace($parnetHash, $buff, $output);
        }
        return $output;
    }


}