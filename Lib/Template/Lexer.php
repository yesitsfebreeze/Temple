<?php

namespace Temple\Template;


use Temple\Dependency\DependencyInstance;
use Temple\Models\Dom\Dom;
use Temple\Utilities\Config;


/**
 * Class Lexer
 *
 * @package Temple
 */
class Lexer extends DependencyInstance
{

    /** @var  Config $Config */
    protected $Config;


    public function dependencies()
    {

        return array(
            "Utilities/Config" => "Config"
        );
    }


    /** @var Dom $dom */
    private $dom;


    /**
     * returns the file as a Dom Object
     *
     * @param string   $file
     * @param int|bool $level
     * @return array
     */
    public function lex($file, $level = false)
    {


        $this->dom = new Dom();

        return $this->dom;
    }


}