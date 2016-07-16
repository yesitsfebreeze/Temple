<?php

namespace Underware\Engine\Exception;


/**
 * Class ExceptionTemplate
 *
 * @package Underware\Exception
 */
class Template
{

    /** @var Exception $exception */
    private $exception;

    /** @var string $template */
    private $template;


    /**
     * ExceptionTemplate constructor.
     *
     * @param Exception $exception
     */
    public function __construct(Exception $exception)
    {
        $this->exception = $exception;
        $this->template  = $this->getTemplate();
        $this->displayMessage();
        $this->displayFile();
        $this->displayStackTrace();
        die($this->template);
    }


    /**
     * reads the content of the html template
     *
     * @return string
     */
    private function getTemplate()
    {
        return file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . (new \ReflectionClass($this))->getShortName() . ".html");
    }


    /**
     * displays the message
     */
    private function displayMessage()
    {
        $message        = preg_replace('/\\%(.+?)\\%/', "<span class='highlight'>$1</span>", $this->exception->getMessage());
        $this->template = str_replace("%message%", $message, $this->template);
    }


    /**
     * displays the file
     */
    private function displayFile()
    {
        $file = $this->coloredFilePath($this->exception->getUnderwareFile());
        if ($this->exception->getUnderwareLine()) {
            $file .= " on line <span class='highlight'>" . $this->exception->getUnderwareLine() . "</span>";
        }
        $this->template = str_replace("%file%", $file, $this->template);
    }


    /**
     * displays the stack trace
     */
    private function displayStackTrace()
    {
        $output = "<table>";
        $traces = $this->exception->getTrace();
        foreach ($traces as $trace) {
            $output .= "<tr>";
            $output .= "<td>";
            $output .= $this->coloredFilePath($trace["file"]);
            $output .= "</td>";
            $output .= "<td>";
            $output .= "on line <span class='highlight'>" . $trace["line"] . "</span>";
            $output .= "</td>";
            $output .= "<td>";
            $output .= "<span class='mute'>&rarr;</span> " . $trace["function"] . "()";
            $output .= "</td>";
            $output .= "<td>";
            $output .= $this->realPath($trace["file"]);
            $output .= "</td>";
            $output .= "</tr>";
        }
        $output .= "</table>";
        $this->template = str_replace("%stacktrace%", $output, $this->template);
    }


    /**
     * @param $file
     *
     * @return string
     */
    private function coloredFilePath($file)
    {
        if (!file_exists($file)) {
            return "<span class='highlight'>" . $file . "</span>";
        }

        $file     = array_reverse(explode(DIRECTORY_SEPARATOR, $file));
        $filename = array_shift($file);
        $path     = implode("/", array_reverse($file));
        $output   = "<span class='highlight'>" . $filename . "</span>";

        return $output;
    }


    /**
     * @param $file
     *
     * @return string
     */
    private function realPath($file)
    {
        if (!file_exists($file)) {
            return "";
        }
        $file     = array_reverse(explode(DIRECTORY_SEPARATOR, $file));
        array_shift($file);
        $path     = implode("/", array_reverse($file));
        $output   = "<span class='hide mute'>" . $path . "</span>";

        return $output;
    }


}