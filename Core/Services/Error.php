<?php

namespace Caramel\Services;


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
     * @param bool $file
     * @param bool $line
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
     * @param bool $file
     * @param bool $line
     */
    private function display($error, $file, $line)
    {
        # update the status code
        if (function_exists("http_response_code")) {
            http_response_code(500);
        }

        $output = "    <script type='text/javascript'>";
        # replace the head with ours
        $output .= '        window.document.head.innerHTML = "' . $this->head() . '";';
        # replace the body with ours
        $output .= '        window.document.body.innerHTML = "' . $this->body($error, $file, $line) . '";';
        $output .= "    </script>";

        echo $output;

        return die();
    }


    /**
     * includes styles and sets the title
     */
    private function head()
    {
        # colors
        $bodyBg    = "#2B2B3B";
        $bodyColor = "#E1E0DE";
        $textColor = "#6E7A89";
        $highlight = "#E59137";

        $output = "<style>";
        $output .= "html,body{background-color:{$bodyBg};color:{$bodyColor};letter-spacing:0.04em;font-weight:400;font-family:'Catamaran',sans-serif;padding:50px;font-size: 16px}";
        $output .= "h1{font-weight:100;position:relative;margin-bottom:20px}";
        $output .= "h3{margin-top:50px;margin-bottom:0px;font-weight:400}";
        $output .= "h4{margin-top:15px;margin-bottom:0px;font-weight:400;font-size:120%;}";
        $output .= ".traces{color:{$textColor};font-weight:400;margin-top:50px}";
        $output .= ".trace{color:{$textColor};margin:4px 0;}";
        $output .= ".colored{color:{$highlight};}";
        $output .= ".issues{padding: 30px 0;}";
        $output .= "a{color:{$highlight}}";
        $output .= "a:hover,a:focus,a:active{color:{$highlight}}";
        $output .= ".issues{color:{$textColor};font-size: 87%}";
        $output .= "</style>";
        $output .= "<link href='https://fonts.googleapis.com/css?family=Catamaran:400,500,300' rel='stylesheet' type='text/css'>";
        $output .= "<title>Caramel found an Error!</title>";

        $output = $this->escape($output);
        return $output;
    }


    /**
     * creating the error message
     *
     * @param \Exception|string $error
     * @param bool $file
     * @param bool $line
     * @return string
     */
    private function body($error, $file, $line)
    {
        # get the message from the exception and
        # escape double quotes to prevent js errors
        $text   = str_replace('"', "'", $error->getMessage());
        $output = "<div class='content'>";
        $output .= "<h1><span class='colored'>c</span>aramel</h1>";
        $output .= "<h3>{$text}</h3>";

        if ($file !== false) {
            $output .= $this->file($file, $line);
        }

        $output .= $this->traces($error);

        $output .= "<div class='issues'>Please report any unsolved problem to my <a href='https://github.com/hvlmnns/Caramel/issues' title='issues' target='_blank'>Github</a> page.</div>";

        $output = $this->escape($output);
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
            $output .= " on line <span class='lineno colored'>{$line}</span>";
        }
        $output .= "</p>";
        $output .= "</h4>";

        $output = $this->escape($output);
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
            $filename = "<span class='colored'>" . array_pop($file) . "</span>";
            $output .= implode("/", $file) . "/" . $filename;
            $output .= " on line <span class='lineno colored'>" . $trace["line"] . "</span>";
            $output .= "</p>";
        }
        $output .= "</div>";

        $output = $this->escape($output);
        return $output;
    }

    /**
     * escapes the " character to keep the js string valid
     * @param $output
     * @return mixed
     */
    private function escape($output)
    {
        $output = preg_replace('/\"/',"'",$output);
        return $output;
    }

}