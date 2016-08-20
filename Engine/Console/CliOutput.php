<?php


namespace Temple\Engine\Console;


class CliOutput
{

    /** @var array $buffer */
    private $buffer = array();

    /** @var CliColors $outputColors */
    private $outputColors;


    public function __construct(CliColors $outputColors)
    {
        $this->outputColors = $outputColors;
    }


    /**
     * just outputs a single message to the console
     *
     * @param      $message
     * @param null $color
     * @param null $background
     */
    public function writeln($message, $color = null, $background = null)
    {
        $message        = $this->outputColors->getColoredString($message, $color, $background);
        $this->buffer[] = $message;
    }


    /**
     * clears the current buffer
     */
    public function clearBuffer() {
        $this->buffer   = array();
    }


    /**
     * echoes the output
     */
    public function outputBuffer() {
        echo implode("\n",$this->buffer) . "\n";
        $this->clearBuffer();
    }

}