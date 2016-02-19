<?

namespace Caramel;

use Exception as Exception;

/**
 * Class Autoloader
 * @package Caramel
 */
class Autoloader
{

    /** @var string */
    private $pwd = __DIR__;

    /**
     * CaramelAutoloader constructor.
     */
    public function __construct()
    {
        $this->lib = $this->pwd . "/../";
        if (is_dir($this->lib)) {
            $this->load();
        } else {
            # oh man..
            throw new Exception("Gosh! you deleted the core!");
        }
    }

    /**
     * loads all classes in the lib directory
     */
    private function load()
    {
        # recursively get all files in the lib directory
        $classes = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($this->lib), \RecursiveIteratorIterator::SELF_FIRST);

        foreach ($classes as $file) {
            # get extension with revers
            $ext = strrev(substr(strrev($file), 0, 4));
            # only require if the extension is .php
            if ($ext == ".php") require_once $file;
        }

        # load the main class
        require_once  $this->lib . "Caramel.php";
    }
}