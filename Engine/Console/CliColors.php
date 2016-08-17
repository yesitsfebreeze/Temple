<?php

namespace Temple\Engine\Console;


/**
 * Class OutputColors
 *
 * @package Temple\Engine\Console
 */
class CliColors
{

    private $colors = array();
    private $background = array();


    public function __construct()
    {
        $this->colors['black']        = '0;30';
        $this->colors['dark_gray']    = '1;30';
        $this->colors['blue']         = '0;34';
        $this->colors['light_blue']   = '1;34';
        $this->colors['green']        = '0;32';
        $this->colors['light_green']  = '1;32';
        $this->colors['cyan']         = '0;36';
        $this->colors['light_cyan']   = '1;36';
        $this->colors['red']          = '0;31';
        $this->colors['light_red']    = '1;31';
        $this->colors['purple']       = '0;35';
        $this->colors['light_purple'] = '1;35';
        $this->colors['brown']        = '0;33';
        $this->colors['yellow']       = '1;33';
        $this->colors['light_gray']   = '0;37';
        $this->colors['white']        = '1;37';

        $this->background['black']      = '40';
        $this->background['red']        = '41';
        $this->background['green']      = '42';
        $this->background['yellow']     = '43';
        $this->background['blue']       = '44';
        $this->background['magenta']    = '45';
        $this->background['cyan']       = '46';
        $this->background['light_gray'] = '47';
    }


    /**
     * @param      $message
     * @param null $color
     * @param null $background
     *
     * @return string
     */
    public function getColoredString($message, $color = null, $background = null)
    {

        $colored = "";

        if (isset($this->colors[ $color ])) {
            $colored .= "\033[" . $this->colors[ $color ] . "m";
        }

        if (isset($this->background[ $background ])) {
            $colored .= "\033[" . $this->background[ $background ] . "m";
        }

        $colored .= $message . "\033[0m";

        return $colored;
    }


    /**
     * @return array
     */
    public function getForegroundColors()
    {
        return array_keys($this->colors);
    }


    /**
     * @return array
     */
    public function getBackgroundColors()
    {
        return array_keys($this->background);
    }
}


?>