<?php

namespace Temple\Template;


use Temple\Dependency\DependencyInstance;
use Temple\Exception\TempleException;
use Temple\Models\Dom\Dom;
use Temple\Models\Nodes\BaseNode;
use Temple\Repositories\StorageRepository;
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

    /** @var  array $templateFiles */
    private $templateFiles;


    /**
     * returns the file as a Dom Object
     *
     * @param array    $templateFiles
     * @param string   $file
     * @param int|bool $level
     * @return array
     */
    public function lex($templateFiles, $file, $level = false)
    {

        $this->templateFiles = $templateFiles;
        $this->prepareDom($file, $level);
        $this->process();

        return $this->dom;
    }


    /**
     * # create a new dom model
     *
     * @param $filename
     * @param $level
     * @return Dom
     * @throws TempleException
     */
    public function prepareDom($filename, $level)
    {

        $this->dom = new Dom();
        $filename  = str_replace("." . $this->Config->get("template.extension"), "", $filename);
        $this->dom->set("info.namespace", $filename);
        $this->dom->set("info.line", 1);
        $this->dom->set("info.indent.amount", 0);
        $this->dom->set("info.indent.char", "");
        $this->dom->set("info.templates", $this->templateFiles);
        $this->dom->set("info.level", $level);
        $this->dom->set("info.file", $this->getFile($level, $filename));
        $this->dom->set("nodes", array());

        # and then process the file
        $this->process();

        return $this->dom;
    }


    /**
     * returns the matching file
     *
     * @param int|null $level
     * @param string   $filename
     * @return StorageRepository
     * @throws TempleException
     */
    private function getFile($level, $filename)
    {

        $templates = $this->dom->get("info.templates");
        if (is_array($templates) && !empty($templates)) {
            if (is_null($level)) {
                return reset($templates);
            }

            if (isset($templates[ $level ])) {
                return $templates[ $level ];
            }
        }

        throw new TempleException("Can't find template file for '" . $filename . "' on template level " . $level);

    }


    /**
     * creates a dom for the current file
     *
     * @return mixed
     */
    private function process()
    {

        $handle = fopen($this->dom->get("info.file"), "r");
        while (($line = fgets($handle)) !== false) {
            if (trim($line) != '') {
                $node = $this->createNode($line);
                $this->addNode($node);
                $this->dom->set("tmp.prev", $node);
            }
            $this->dom->set("info.line", $this->dom->get("info.line") + 1);
        }
        fclose($handle);
        $this->dom->delete("tmp");
    }


    /**
     * creates a node matching to the criteria
     *
     * @param $line
     * @return BaseNode
     * @throws TempleException
     */
    private function createNode($line)
    {

        $this->nodeFactory->addConfig($this->Config);

        $node = $this->nodeFactory->create($line);
        $node->createNode($line);

        $node->set("info.namespace", $this->dom->get("info.namespace"));
        $node->set("info.level", $this->dom->get("info.level"));
        $node->set("info.line", $this->dom->get("info.line"));
        $node->set("info.file", $this->dom->get("info.file"));
        $node->set("info.parent", "test");

        if (!$node->has("tag.tag")) {
            throw new TempleException("Node models must have a tag!", $this->dom->get("info.file"), $this->dom->get("info.line"));
        }

        return $node;

    }


    /**
     * adds the node to the dom
     * getParentNode/child logic is handled here
     *
     * @param BaseNode $node
     */
    private function addNode($node)
    {

        # root nodes
        if ($node->get("info.indent") == 0 || $node->get("info.line") == 1) {
            $node->set("info.parent", false);
            $this->dom->set("nodes." . $this->dom->get("info.line"), $node);
        } else {

            $indent     = $node->get("info.indent");
            $prevIndent = $this->dom->get("tmp.prev")->get("info.indent");

            # node position
            if ($indent > $prevIndent) {
                $this->addNodeOnDeeperLevel($node);
            }

            if ($indent < $prevIndent) {
                $this->addNodeOnHigherLevel($node);
            }

            if ($indent == $prevIndent) {
                $this->addNodeOnSameLevel($node);
            }

        }

    }


    /**
     * adds a node to the dom if has a addNodeOnDeeperLevel level
     * than the previous node
     *
     * @param BaseNode $node
     * @throws TempleException
     */
    private function addNodeOnDeeperLevel($node)
    {

        $node->set("info.parent", $this->dom->get("tmp.prev"));

        if ($node->get("info.parent")->get("info.selfclosing")) {
            $tag = $node->get("info.parent")->get("tag.tag");
            throw new TempleException("You can't have children in an $tag!", $this->dom->get("info.file"), $this->dom->get("info.line"));
        } else {
            $this->getChildNodes($this->dom->get("tmp.prev"), $node);
        }

    }


    /**
     * adds a node to the dom if has a addNodeOnHigherLevel level
     * than the previous node
     *
     * @param BaseNode $node
     */
    private function addNodeOnHigherLevel($node)
    {

        $parent = $this->getParentNode($node);
        $node->set("info.parent", $parent);
        $this->getChildNodes($parent, $node);

    }


    /**
     * adds a node to the dom if has the addNodeOnSameLevel  level
     * than the previous node
     *
     * @param BaseNode $node
     */
    private function addNodeOnSameLevel($node)
    {

        $parent = $this->dom->get("tmp.prev")->get("info.parent");
        $node->set("info.parent", $parent);
        $this->getChildNodes($parent, $node);

    }


    /**
     * adds the passed node to children
     *
     * @param BaseNode $target
     * @param BaseNode $node
     * @throws \Exception
     */
    private function getChildNodes($target, $node)
    {

        $children   = $target->get("children");
        $children[] = $node;
        $target->set("children", $children);

    }


    /**
     * returns the getParentNode of the passed node
     *
     * @param BaseNode      $node
     * @param bool|BaseNode $parent
     * @return BaseNode
     */
    private function getParentNode($node, $parent = false)
    {

        if (!$parent) {
            $temp = $this->dom->get("tmp.prev")->get("info.parent");
        } else {
            $temp = $parent->get("info.parent");
        }
        if ($temp->get("info.indent") == $node->get("info.indent")) {
            $temp = $temp->get("info.parent");

            return $temp;
        } else {
            return $this->getParentNode($node, $temp);
        }

    }

}