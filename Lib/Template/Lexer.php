<?php

namespace Temple\Template;


use Temple\Dependency\DependencyInstance;
use Temple\Exception\TempleException;
use Temple\Models\Dom\Dom;
use Temple\Models\Nodes\BaseNode;
use Temple\Utilities\Config;
use Temple\Utilities\Directories;


/**
 * Class Lexer
 *
 * @package Temple
 */
class Lexer extends DependencyInstance
{

    /** @var  Config $Config */
    protected $Config;

    /** @var  Directories $Directories */
    protected $Directories;


    public function dependencies()
    {
        return array(
            "Utilities/Config"      => "Config",
            "Utilities/Directories" => "Directories"
        );
    }


    /** @var Dom $dom */
    private $dom;

    /** @var int $level */
    private $level;

    /** @var  NodeFactory $NodeFactory */
    private $NodeFactory;


    public function __construct(NodeFactory $NodeFactory)
    {
        $this->NodeFactory = $NodeFactory;
    }


    /**
     * returns the file as a Dom Object
     *
     * @param string $file
     * @param int    $level
     * @return array
     */
    public function lex($file, $level = 0)
    {

        $this->level = $level;
        $namespace   = str_replace("." . $this->Config->get("template.extension"), "", $file);
        $files       = $this->getTemplateFiles($file);
        $file        = $this->getTemplateFile($file, $level);
        $this->createNewDom($namespace, $file, $files);
        return $this->process($file);

    }


    /**
     * returns the next template file in line for the given level
     * also counts up the member variable "level"
     *
     * @param string $file
     * @param int    $level
     * @return string
     * @throws TempleException
     */
    private function getTemplateFile($file, $level = 0)
    {
        $this->level = $level;

        $dirs        = $this->Directories->get("template");
        if (!isset($dirs[ $level ])) {
            throw new TempleException("No template file found!", "on level " . $level);
        }

        $dir          = $dirs[ $level ];
        $templateFile = $dir . $file;

        if (!file_exists($templateFile)) {
            return $this->getTemplateFile($file, $level + 1);
        }

        return $templateFile;

    }


    /**
     * returns all found template files
     *
     * @param $file
     * @return array
     */
    private function getTemplateFiles($file)
    {

        $dirs  = $this->Directories->get("template");
        $files = array();

        foreach ($dirs as $dir) {
            $templateFile = $dir . $file;
            if (file_exists($templateFile)) {
                $files[] = $templateFile;
            }
        }


        return $files;
    }


    /**
     * creates a new dom
     *
     * @param $namespace
     * @param $file
     * @param $files
     */
    private function createNewDom($namespace, $file, $files)
    {
        $this->dom = new Dom();
        $this->dom->set("info.namespace", $namespace);
        $this->dom->set("info.line", 1);
        $this->dom->set("info.indent.amount", 0);
        $this->dom->set("info.indent.char", "");
        $this->dom->set("info.templates", $files);
        $this->dom->set("info.level", $this->level);
        $this->dom->set("info.file", $file);
        $this->dom->set("nodes", array());
    }


    /**
     * processes the current file and adds its node to the dom
     *
     * @param $file
     * @throws TempleException
     * @return Dom
     */
    private function process($file)
    {
        $handle = fopen($file, "r");
        while (($line = fgets($handle)) !== false) {
            if (trim($line) != '') {
                $node = $this->createNode($line);
                $this->addNode($node);
                $this->dom->set("tmp.prev", $node);
            }
            $this->dom->set("info.line", $this->dom->get("info.line") + 1);
        }
        fclose($handle);

        # remove the temporary dom helper
        $this->dom->delete("tmp");

        return $this->dom;
    }


    /**
     * creates a node with the node factory
     *
     * @param $line
     * @return BaseNode
     * @throws TempleException
     */
    private function createNode($line)
    {

        $this->NodeFactory->addConfig($this->Config);
        $node = $this->NodeFactory->create($line);

        $node->createNode($line);

        if (!$node->has("tag.tag")) {
            throw new TempleException("Node models must have a tag!", $this->dom->get("info.file"), $this->dom->get("info.line"));
        }

        $node->set("info.namespace", $this->dom->get("info.namespace"));
        $node->set("info.level", $this->dom->get("info.level"));
        $node->set("info.line", $this->dom->get("info.line"));
        $node->set("info.file", $this->dom->get("info.file"));
        $node->set("info.getParentNode", "test");

        return $node;
    }


    /**
     * adds the node to the dom
     * parent/child logic is handled here
     *
     * @param BaseNode $node
     */
    private function addNode($node)
    {
        # root nodes
        if ($node->get("info.indent") == 0 || $node->get("info.line") == 1) {

            $node->set("info.parent", false);

            $rootNodes   = $this->dom->get("nodes");
            $rootNodes[] = $node;
            $this->dom->set("nodes", $rootNodes);

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
     * adds a node to the dom if it has a deeper level
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
            throw new TempleException("You can't have children in an $tag tag!", $this->dom->get("info.file"), $this->dom->get("info.line"));
        } else {
            $this->addNodeAsChild($this->dom->get("tmp.prev"), $node);
        }
    }


    /**
     * adds a node to the dom if it has a higher
     * than the previous node
     *
     * @param BaseNode $node
     */
    private function addNodeOnHigherLevel($node)
    {
        $parent = $this->getParentNode($node);
        $node->set("info.parent", $parent);
        $this->addNodeAsChild($parent, $node);
    }


    /**
     * adds a node to the dom if it has the same level
     * than the previous node
     *
     * @param BaseNode $node
     */
    private function addNodeOnSameLevel($node)
    {
        $parent = $this->dom->get("tmp.prev")->get("info.parent");
        $node->set("info.parent", $parent);
        $this->addNodeAsChild($parent, $node);
    }


    /**
     * adds the passed node as a child
     *
     * @param BaseNode $target
     * @param BaseNode $node
     * @throws \Exception
     */
    private function addNodeAsChild($target, $node)
    {
        $children   = $target->get("children");
        $children[] = $node;
        $target->set("children", $children);
    }


    /**
     * returns the parent of the passed node
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