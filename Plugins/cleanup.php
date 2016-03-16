<?php

namespace Caramel;


/**
 * Class PluginCleanup
 *
 * @purpose : replaces all nested php tags
 * @usage   : automatic
 * @autor   : Stefan Hövelmanns - hvlmnns.de
 * @License : MIT
 * @package Caramel
 */
class PluginCleanup extends Models\Plugin
{


    /**
     * @return int;
     */
    public function position()
    {
        return 9999999993;
    }


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
        $output = $this->cleanup($output);

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
        if ($this->caramel->helpers()->str_find($output, "<?php") && $this->caramel->helpers()->str_find($output, "?>")) {
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
     * @param      $output
     * @param bool $topLevel
     * @return string $output
     */
    private function cleanup($output, $topLevel = true)
    {
        preg_match_all("/\{\{cleanup::(.*?)::cleanup}}/", $output, $matches);
        if (isset($matches[1])) {
            $matches = $matches[1];
            foreach ($matches as $match) {

                # clean the buffer item
                $bufferItem = $this->buffer[ $match ];
                $bufferItem = preg_replace("/\<\?php( |)/", "", $bufferItem);
                $bufferItem = preg_replace("/( |)\?\>/", "", $bufferItem);
                $bufferItem = $this->cleanup($bufferItem, false);

                # if we are on the first iteration, wrap a php tag around it,
                # else do nothing
                if ($topLevel) {
                    $output = str_replace("{{cleanup::" . $match . "::cleanup}}", '<?php ' . $bufferItem . ' ?>', $output);
                } else {
                    $output = str_replace("{{cleanup::" . $match . "::cleanup}}", $bufferItem, $output);
                }
                unset($this->buffer[ $match ]);
            }
        }

        return $output;
    }

}