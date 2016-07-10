<?php

namespace Underware\Plugins\Node;


use Underware\Exception\Exception;
use Underware\Models\Nodes\HtmlNodeModel;
use Underware\Models\Plugins\NodePlugin;
use Underware\Utilities\Storage;


/**
 * Class Import
 *
 * @package Underware\Plugins
 */
class Import extends NodePlugin
{

    /** @var array|Storage $attributes */
    protected $attributes = array(
        "file"
    );


    /**
     * @param HtmlNodeModel $args
     *
     * @return bool
     */
    public function check($args)
    {

        if ($args instanceof HtmlNodeModel) {
            return ($args->get("tag.definition") == "import");
        }

        return false;

    }


    /**
     * @param HtmlNodeModel $node
     *
     * @return HtmlNodeModel $node
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
     * @param HtmlNodeModel $node
     *
     * @return string $file
     */
    private function getPath(HtmlNodeModel $node)
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
     * @param HtmlNodeModel $node
     *
     * @return mixed
     */
    private function getParentPath(HtmlNodeModel $node)
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
     * @param HtmlNodeModel $node
     */
    private function hideImport(HtmlNodeModel $node)
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