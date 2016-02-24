<?php

namespace Caramel;


/**
 * Class PluginImport
 *
 * @package    Caramel
 * @description: handles file imports
 * @position   : 0
 * @author     : Stefan Hövelmanns
 * @License    : MIT
 */

class PluginImport extends Plugin
{

    /** @var int $position */
    protected $position = 0;


    /**
     * @param Node $node
     * @return bool
     */
    public function check($node)
    {
        return ($node->get("tag.tag") == "import");
    }


    /**
     * @param Node $node
     * @return Node $node
     */
    public function process($node)
    {
        $node->set("tag.display", false);

        $file      = $this->getPath($node);
        $cachePath = $this->crml->template()->parse($file);

        # add the dependency
        $this->crml->cache()->dependency($node->get("file"), $file);

        $node->set("content", "<?php include '" . $cachePath . "' ?>");

        return $node;
    }


    /**
     * searches for a template file and returns the correct path
     *
     * @param Node $node
     * @return string $file
     */
    private function getPath($node)
    {
        # if the file has an absolute path
        $file      = $node->get("attributes");
        $relative  = $file[0] != "/";
        $templates = $this->crml->template()->dirs();

        if ($relative) {
            $dir  = $this->getParentPath($node, $templates);
            $file = $dir . $file;
        }

        return $file;
    }


    /**
     * returns the template path to the file which is importing
     *
     * @param Node  $node
     * @param array $templates
     * @return mixed
     */
    private function getParentPath($node, $templates)
    {
        $path = explode("/", $node->get("file"));
        array_pop($path);
        $path = implode("/", $path) . "/";
        foreach ($templates as $template) {
            $path = str_replace($template, "", $path);
        }

        return $path;
    }

}