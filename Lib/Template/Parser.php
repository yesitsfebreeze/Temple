<?php

namespace Temple\Template;


use Temple\Dependency\DependencyInstance;
use Temple\Plugins\Plugins;

/**
 * Class Parser
 *
 * @package Temple
 */
class Parser extends DependencyInstance
{

    /** @var  Plugins Plugins */
    protected $Plugins;


    public function dependencies()
    {
        return array(
            "Plugins/Plugins" => "Plugins"
        );
    }

    /**
     * @param $dom
     * @return string
     */
    public function parse($dom)
    {
        $parsedContent = "<div>test</div>";

        return $parsedContent;
    }

}