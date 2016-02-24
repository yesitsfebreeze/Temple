<?php

namespace Caramel;


/**
 * Class CaramelError
 *
 * @package Caramel
 */
class Error
{

    /**
     * Error constructor.
     *
     * @param \Exception|string $error
     * @param bool              $file
     * @param bool              $line
     */
    public function __construct($error, $file = false, $line = false)
    {
        # if the error just contains of a string
        # create a new exception to handle the error display correctly
        if ("string" === gettype($error)) $error = new \Exception($error);

        # if we instantly want to show the error
        # putting together the error and show it
        $this->display($error, $file, $line);
    }


    /**
     * displays the output and performs a die
     *
     * @param \Exception|string $error
     * @param bool              $file
     * @param bool              $line
     */
    private function display($error, $file, $line)
    {
        # update the status code
        http_response_code(500);

        $output = "<html>";
        $output .= "    <head></head>";
        $output .= "    <body></body>";
        $output .= "    <script type='text/javascript'>";
        # replace the head with ours
        $output .= '        window.document.head.innerHTML = "' . $this->head() . '";';
        # replace the body with ours
        $output .= '        window.document.body.innerHTML = "' . $this->body($error, $file, $line) . '";';
        $output .= "    </script>";
        $output .= "</html>";

        echo $output;

        return die();
    }


    /**
     * includes styles and sets the title
     */
    private function head()
    {
        # colors
        $bodyBg    = "#3b3a39";
        $bodyColor = "#e0dddc";
        $textColor = "#a6a2a0";
        $highlight = "#d6963c";

        $output = "<style>";
        $output .= "html,body{background-color:{$bodyBg};color:{$bodyColor};font-family:sans-serif;padding:50px;}";
        $output .= "h3{margin-top:50px;margin-bottom:0px;}";
        $output .= "h4{margin-top:15px;margin-bottom:50px;font-weight:400;font-size:120%;}";
        $output .= ".trace{color:{$textColor};margin:4px 0;}";
        $output .= ".colored{color:{$highlight};}";
        $output .= "h1{font-weight:400;position:relative;color:{$highlight};margin-bottom:20px;}";
        $output .= ".issues{padding: 30px 0;}";
        $output .= "a{color:{$highlight}}";
        $output .= "a:hover,a:focus,a:active{color:{$highlight}}";
        $output .= "</style>";
        $output .= "<title>Caramel found an Error!</title>";

        return $output;
    }


    /**
     * creating the error message
     *
     * @param \Exception|string $error
     * @param bool              $file
     * @param bool              $line
     * @return string
     */
    private function body($error, $file, $line)
    {
        # get the message from the exception and
        # escape double quotes to prevent js errors
        $text    = str_replace('"', "'", $error->getMessage());
        $output = "<div class='content'>";
        $output .= "<h1>Snap!</h1>";
        $output .= "<h3>{$text}</h3>";

        if ($file !== false) {
            $this->file($file, $line);
        }

        $output .= "<div class='issues'>Please report any unsolved problem to my <a href='https://github.com/hvlmnns/Caramel/issues' title='issues' target='_blank'>Github</a> page.</div>";

        $output .= $this->traces($error);

        return $output;
    }


    /**
     * creates the file display
     *
     * @param $file
     * @param $line
     * @return string
     */
    private function file($file, $line)
    {
        $output = "<h4>";
        $output .= "<p class='trace'>";
        $file     = explode("/", $file);
        $filename = "<span class='colored'>" . array_pop($file) . "</span>";
        $output .= implode("/", $file) . "/" . $filename;
        if ($line !== false) {
            $output .= " on line <b class='colored'>{$line}</b>";
        }
        $output .= "</p>";
        $output .= "</h4>";

        return $output;
    }


    /**
     * creates the trace stack
     *
     * @param \Exception $error
     * @return string
     */
    private function traces($error)
    {
        $output = "<div class='traces'>";
        foreach ($error->getTrace() as $trace) {

            $file = explode("/", $trace["file"]);
            $output .= "<p class='trace'>";
            $output .= "<span class='colored'>" . array_pop($file) . "</span>";
            $output .= " on line <b class='colored'>" . $trace["line"] . "</b>";
            $output .= "</p>";
        }
        $output .= "</div>";

        return $output;
    }

}