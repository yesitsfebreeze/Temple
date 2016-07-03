<?php

namespace Underware\Plugins;


use Underware\Exception\Exception;
use Underware\Models\HtmlNode;
use Underware\Models\Plugin;
use Underware\Utilities\Storage;


/**
 * Class Import
 *
 * @package Underware\Plugins
 */
class Import extends Plugin
{

    /** @var array|Storage $attributes */
    protected $attributes = array(
        "file"
    );


    /**
     * @param HtmlNode $args
     *
     * @return bool
     */
    public function check($args)
    {

        if ($args instanceof HtmlNode) {
            return ($args->get("tag.definition") == "import");
        }

        return false;

    }


    /**
     * @param HtmlNode $node
     *
     * @return HtmlNode $node
     * @throws Exception
     */
    public function process($node)
    {

        $this->Instance->Config()->extend("selfClosing", "import");
        $this->hideImport($node);

        $file = $this->getPath($node);
        if ($file == $node->get("info.namespace")) {
            throw new Exception("Recursive imports are not allowed!", $node->get("file"), $node->get("line"));
        }
        $cachePath = $this->Instance->Template()->fetch($file);

        # add the dependency
        $this->Instance->Cache()->addDependency($node->get("info.file"), $file);

        $node->set("content", "<?php include '" . $cachePath . "' ?>");

        return $node;
    }


    /**
     * searches for a template file and returns the correct path
     *
     * @param HtmlNode $node
     *
     * @return string $file
     */
    private function getPath(HtmlNode $node)
    {

        # if the file has an absolute path
        $path     = $this->attributes->get("file");
        $relative = $path[0] != "/";

        if ($relative) {
            $path = $this->getParentPath($node) . $path;
        }

        return $path;
    }


    /**
     * returns the template path to the file which is importing
     *
     * @param HtmlNode $node
     *
     * @return mixed
     */
    private function getParentPath(HtmlNode $node)
    {
        $templates = $this->Instance->Template()->getDirectories();
        $path      = explode("/", $node->get("info.file"));
        array_pop($path);
        $path = implode("/", $path) . "/";

        foreach ($templates as $template) {
            $path = str_replace($template, "", $path);
        }

        return $path;
    }


    /**
     * @param HtmlNode $node
     */
    private function hideImport(HtmlNode $node)
    {

        if ($this->Instance->Config()->get("template.comments.bricks")) {
            $node->set("tag.opening.before", "<!-- ");
            $node->set("tag.opening.after", $node->get("info.relativeFile") . ":" . $node->get("info.line") . " --!>");

            $node->set("tag.closing.before", "");
            $node->set("tag.closing.after", "");
            $node->set("tag.closing.definition", "");
        } else {
            $node->set("tag.display", false);
        }
    }

}