<?php

namespace Caramel;

/**
 *
 * Class PluginCleanup
 *
 * @purpose: replaces all nested php tags
 * @usage: automatic
 * @autor: Stefan Hövelmanns - hvlmnns.de
 * @License: MIT
 * @package Caramel
 *
 */
class PluginCleanup extends Plugin
{

    /** @var int $position */
    protected $position = 9999999993;

    /** @var array $buffer */
    private $buffer = array();

    /**
     * @param $output
     * @return bool|mixed|string
     */
    public function processOutput($output)
    {
        $output = $this->prepare($output);
        $output = $this->bufferPhp($output);
        $output = $this->removeInnerPhp($output);

        debug("-------------------------");
        debug(htmlspecialchars($output));

        return $output;
    }

    /**
     * @param $output
     * @return mixed
     */
    private function prepare($output)
    {
        $output = preg_replace("/\n/", "", $output);

        return $output;
    }

    /**
     * @param string $output
     * @return string $output
     */
    private function bufferPhp($output)
    {
        if ($this->find($output, "<?php") && $this->find($output, "?>")) {
            $end     = strpos($output, "?>") + 2;
            $start   = strpos($output, "?>") - strpos(strrev(substr($output, 0, $end)), "php?<") - 3;
            $length  = $end - $start;
            $content = substr($output, $start, $length);
            $hash    = md5($content);

            $this->buffer[ $hash ] = $content;

            $output = str_replace($content, "{{cleanup::" . $hash . "::cleanup}}", $output);
            $output = $this->bufferPhp($output);
        }

        return $output;
    }

    /**
     * @param $output
     * @param bool $first
     * @return string $output
     */
    private function removeInnerPhp($output, $first = true)
    {
        preg_match_all("/\{\{cleanup::(.*?)::cleanup}}/", $output, $matches);
        if (isset($matches[1])) {
            $matches = $matches[1];
            foreach ($matches as $match) {
                $bufferItem = $this->buffer[ $match ];
                if (!$first) {
                    $bufferItem = str_replace("<?php ", "", $bufferItem);
                    $bufferItem = str_replace(" ?>", "", $bufferItem);
                }
                $output     = str_replace("{{cleanup::" . $match . "::cleanup}}", $bufferItem, $output);
                $output     = $this->removeInnerPhp($output, false);
            }
        }

        return $output;
    }

    /**
     * searches a string for the needle and returns true if found
     *
     * @param string $string
     * @param string $needle
     * @return bool
     */
    private function find($string, $needle)
    {
        return sizeof(explode($needle, $string)) > 1;
    }

}