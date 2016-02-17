<?php

namespace Caramel;

/**
 * Class CaramelError
 * @package Caramel
 */
class Error
{
    /** @var \Exception $error */
    private $error;

    /** @var string $bodyBg */
    private $bodyBg = "#f1f1f1";

    /** @var string $bodyColor */
    private $bodyColor = "#58586f";

    /** @var string color */
    private $textColor = "#85859e";

    /** @var string $highlight */
    private $highlight = "#60a3db";

    /** @var string $file */
    private $file;

    /** @var integer $line */
    private $line;


    /**
     * Error constructor.
     * @param $error
     * @param bool $file
     * @param bool $line
     */
    public function __construct($error, $file = false, $line = false)
    {
        $this->file  = $file;
        $this->line  = $line;
        $this->error = $error;
        # if the error just contains of a string
        # create a new exception to handle the error display correctly
        if ("string" === gettype($error)) $this->error = new \Exception($error);

        # if we instantly want to show the error
        # putting together the error and show it
        $this->display();
    }

    /**
     * including styles and set title
     */
    private function head()
    {
        $head = "<style>";
        $head .= "html,body{background-color:{$this->bodyBg};color:{$this->bodyColor};font-family:sans-serif;padding:50px;}";
        $head .= "h3{margin-top:50px;margin-bottom:0px;}";
        $head .= "h4{margin-top:15px;margin-bottom:50px;font-weight:400;font-size:120%;}";
        $head .= ".trace{color:{$this->textColor};margin:4px 0;}";
        $head .= ".colored{color:{$this->highlight};}";
        $head .= "h1{font-weight:400;position:relative;color:{$this->highlight};margin-bottom:20px;}";
        $head .= "</style>";
        $head .= "<title>Ops!</title>";

        return $head;
    }


    /**
     * creating the error message
     */
    private function body()
    {
        # get the message from the exception and
        # escape double quotes to prevent js errors
        $text    = str_replace('"', "'", $this->error->getMessage());
        $message = "<div class='content'>";
        $message .= "<h1>Ops!</h1>";
        $message .= "<h3>{$text}</h3>";


        if ($this->file) {
            $message .= "<h4>";
            $message .= "<p class='trace'>";
            $file     = explode("/", $this->file);
            $filename = "<span class='colored'>" . array_pop($file) . "</span>";
            $message .= implode("/", $file) . "/" . $filename;
            if ($this->line) {
                $message .= " on line <b class='colored'>";
                $message .= $this->line;
                $message .= "</b>";
            }
            $message .= "</p></h4>";
        }


        # build the trace display
        foreach ($this->error->getTrace() as $trace) {
            $message .= "<p class='trace'>";
            $file     = explode("/", $trace["file"]);
            $filename = "<span class='colored'>" . array_pop($file) . "</span>";
            $message .= implode("/", $file) . "/" . $filename;
            $message .= " on line <b class='colored'>";
            $message .= $trace["line"];
            $message .= "</b></p>";
        }
        $message .= "</div>";

        return $message;
    }


    /**
     * displays the output and performs a die
     */
    private function display()
    {

        # update the status code
        http_response_code(500);

        $script = "<html>";
        $script .= "    <head></head>";
        $script .= "    <body></body>";
        $script .= "    <script type='text/javascript'>";
        # replace the head with ours
        $script .= '        window.document.head.innerHTML = "' . $this->head() . '";';
        # replace the body with ours
        $script .= '        window.document.body.innerHTML = "' . $this->body() . '";';
        $script .= "    </script>";
        $script .= "</html>";

        echo $script;

        return die();
    }

}