<?php

namespace Underware\Engine;


use Underware\Engine\Events\EventManager;
use Underware\Engine\Exception\Exception;
use Underware\Engine\Filesystem\DirectoryHandler;
use Underware\Engine\Injection\Injection;
use Underware\Engine\Structs\Dom;
use Underware\Nodes\Node;


/**
 * Class Lexer
 *
 * @package Project
 */
class Lexer extends Injection
{

    /** @var  Config $Config */
    protected $Config;

    /** @var  DirectoryHandler $DirectoryHandler */
    protected $DirectoryHandler;

    /** @var  EventManager $EventManager */
    protected $EventManager;

    /** @var Dom $dom */
    private $dom;

    /** @var int $level */
    private $level;


    /** @inheritdoc */
    public function dependencies()
    {
        return array(
            "Engine/Config"                      => "Config",
            "Engine/Filesystem/DirectoryHandler" => "DirectoryHandler",
            "Engine/Events/EventManager"         => "EventManager"
        );
    }


    /**
     * returns the file as a Dom Object
     *
     * @param string $file
     * @param int    $level
     *
     * @return Dom
     */
    public function lex($file, $level = 0)
    {

        $this->level = $level;
        $namespace   = str_replace("." . $this->Config->getExtension(), "", $file);
        $files       = $this->getTemplateFiles($file);
        $file        = $this->getTemplateFile($file, $level);

        $fileContent = file_get_contents($file);
        $fileContent = $this->EventManager->notify("plugins.file.process", $fileContent);
        file_put_contents($file, $fileContent);

        $this->dom = new Dom($namespace, $file, $files, $this->level);
        $this->process($file);

        $this->dom = $this->EventManager->notify("plugin.dom", $this->dom);

        return $this->dom;

    }


    /**
     * returns the next template file in line for the given level
     * also counts up the member variable "level"
     *
     * @param string $file
     * @param int    $level
     *
     * @return string
     * @throws Exception
     */
    private function getTemplateFile($file, $level = 0)
    {
        $this->level = $level;

        $dirs = $this->DirectoryHandler->getTemplateDirs();

        if (!isset($dirs[ $level ])) {
            throw new Exception($file . " not found!");
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
     *
     * @return array
     */
    private function getTemplateFiles($file)
    {
        $dirs  = $this->DirectoryHandler->getTemplateDirs();
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
     * processes the current file and adds its node to the dom
     *
     * @param $file
     *
     * @throws Exception
     * @return Dom
     */
    private function process($file)
    {
        $handle = fopen($file, "r");
        while (($line = fgets($handle)) !== false) {
            if (trim($line) != '') {
                $node = $this->createNode($line);
                $this->addNode($node);
                $this->dom->setLastNode($node);
            }
            $this->dom->setCurrentLine($this->dom->getCurrentLine() + 1);
        }
        fclose($handle);
        $this->dom->setLastNode(null);

        return $this->dom;
    }


    /**
     * creates a node matching to the criteria
     *
     * @param $line
     *
     * @return Node
     * @throws Exception
     */
    private function createNode($line)
    {
        $line = $this->EventManager->notify("plugin.line", $line);
        /** @var Node $node */
        $node = $this->EventManager->notify("lexer.node", array($line));

        $node->setNamespace($this->dom->getNamespace());
        $node->setLevel($this->dom->getLevel());
        $node->setLine($this->dom->getCurrentLine());
        $node->setFile($this->dom->getFile());
        $node->setRelativeFile(str_replace($_SERVER['DOCUMENT_ROOT'], "", $this->dom->getFile()));

        if (!$node instanceof Node) {
            throw new Exception("The Node EventManager has to return an instance of a %Node%!");
        }

        return $node;
    }


    /**
     * adds the node to the dom
     * getParentNode/child logic is handled here
     *
     * @param Node $node
     *
     * @return bool
     */
    private function addNode($node)
    {
        # root nodes
        if ($node->getIndent() == 0 || $node->getLine() == 1) {
            $node->setParent(false);
            $rootNodes   = $this->dom->getNodes();
            $rootNodes[] = $node;
            $this->dom->setNodes($rootNodes);

        } else {
            $indent     = $node->getIndent();
            $prevIndent = $this->dom->getLastNode()->getIndent();

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

        return true;
    }


    /**
     * adds a node to the dom if has a addNodeOnDeeperLevel level
     * than the previous node
     *
     * @param Node $node
     *
     * @throws Exception
     * @return mixed
     */
    private function addNodeOnDeeperLevel($node)
    {
        $node->setParent($this->dom->getLastNode());
        if ($node->getParent()->isSelfClosing()) {
            throw new Exception("Current node cant have children!", $this->dom->getFile(), $this->dom->getCurrentLine());
        }

        return $this->addNodeAsChild($this->dom->getLastNode(), $node);

    }


    /**
     * adds a node to the dom if has a addNodeOnHigherLevel level
     * than the previous node
     *
     * @param Node $node
     *
     * @return mixed
     */
    private function addNodeOnHigherLevel($node)
    {
        $parent = $this->getParentNode($node);
        $node->setParent($parent);

        return $this->addNodeAsChild($parent, $node);
    }


    /**
     * adds a node to the dom if has the addNodeOnSameLevel  level
     * than the previous node
     *
     * @param Node $node
     *
     * @return mixed
     */
    private function addNodeOnSameLevel($node)
    {
        $parent = $this->dom->getLastNode()->getParent();
        $node->setParent($parent);

        return $this->addNodeAsChild($parent, $node);
    }


    /**
     * adds the passed node as a child
     *
     * @param Node $target
     * @param Node $node
     *
     * @throws \Exception
     * @return mixed
     */
    private function addNodeAsChild($target, $node)
    {
        $children   = $target->getChildren();
        $children[] = $node;

        return $target->setChildren($children);
    }


    /**
     * returns the getParentNode of the passed node
     *
     * @param Node      $node
     * @param bool|Node $parent
     *
     * @return Node
     */
    private function getParentNode($node, $parent = false)
    {

        if (!$parent) {
            $temp = $this->dom->getLastNode()->getParent();
        } else {
            $temp = $parent->getParent();
        }
        if ($temp->getIndent() == $node->getIndent()) {
            $temp = $temp->getParent();

            return $temp;
        } else {
            return $this->getParentNode($node, $temp);
        }
    }

}