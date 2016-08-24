<?php

namespace Temple\Engine\Exception;


/**
 * Class ExceptionTemplate
 *
 * @package Temple\Exception
 */
class ExceptionTemplate
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
        $this->displayCode();
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
        $className = explode("\\", get_class($this));
        $className = array_pop($className);

        return file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . $className . ".html");
    }


    /**
     * displays the error code
     */
    private function displayCode()
    {

        $code = "<div class='mute code'>";
        $code .= "Error code: " . $this->exception->getCustomCode();
        $code .= "</div>";

        $this->template = str_replace("%code%", $code, $this->template);
    }


    /**
     * displays the message
     */
    private function displayMessage()
    {
        $message        = preg_replace('/\\%(.+?)\\%/', "<span class='highlight'>$1</span>", $this->exception->getCustomMessage());
        $this->template = str_replace("%message%", $message, $this->template);
    }


    /**
     * displays the file
     */
    private function displayFile()
    {
        $file = $this->coloredFilePath($this->exception->getCustomFile(), true);
        if ($this->exception->getCustomLine()) {
            $file .= " on line <span class='highlight'>" . $this->exception->getCustomLine() . "</span>";
        }
        $this->template = str_replace("%file%", $file, $this->template);
    }


    /**
     * displays the stack trace
     */
    private function displayStackTrace()
    {
        $output = "<div>";
        $traces = $this->exception->getTrace();
        foreach ($traces as $trace) {
            $output .= "<div class='trace'>";
            $output .= $this->realPath($trace["file"]);
            $output .= $this->coloredFilePath($trace["file"]);
            $output .= "<span class='mute'> at line </span><span class='highlight'>" . $trace["line"] . "</span>";
            $output .= "<span class='mute'> &rightarrow; </span>" . $trace["function"] . "()";
            $output .= "</div>";
        }
        $output .= "</div>";
        $this->template = str_replace("%stacktrace%", $output, $this->template);
    }


    /**
     * @param      $file
     * @param bool $full
     *
     * @return string
     */
    private function coloredFilePath($file, $full = false)
    {
        if (!file_exists($file) || $full) {
            return "<span class='highlight'>" . str_replace($_SERVER["DOCUMENT_ROOT"], "", $file) . "</span>";
        }

        $file     = array_reverse(explode(DIRECTORY_SEPARATOR, $file));
        $filename = array_shift($file);
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
        $file = array_reverse(explode(DIRECTORY_SEPARATOR, $file));
        array_shift($file);
        $path = implode("/", array_reverse($file));
        $path = str_replace($_SERVER["DOCUMENT_ROOT"], "", $path);
        $path = preg_replace("/^\//", "", $path);
        if ($path != "") {
            $path = $path . "/";
        }
        $output = "<span class='mute hide'>" . $path . "</span>";

        return $output;
    }


}