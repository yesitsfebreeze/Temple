<?php

namespace Temple\Engine;


use Temple\Engine\EventManager\EventManager;
use Temple\Engine\Exception\Exception;
use Temple\Engine\Filesystem\DirectoryHandler;
use Temple\Engine\InjectionManager\Injection;
use Temple\Engine\Structs\Dom;
use Temple\Engine\Structs\LanguageLoader;
use Temple\Engine\Structs\Node\DefaultNode;
use Temple\Engine\Structs\Node\Node;


/**
 * Class Lexer
 *
 * @package Project
 */
class Lexer extends Injection
{

    /** @var  Config $Config */
    protected $Config;

    /** @var  Languages $Languages */
    protected $Languages;

    /** @var  DirectoryHandler $DirectoryHandler */
    protected $DirectoryHandler;

    /** @var  EventManager $EventManager */
    protected $EventManager;

    /** @var int $level */
    private $level;


    /** @inheritdoc */
    public function dependencies()
    {
        return array(
            "Engine/Config"                      => "Config",
            "Engine/Languages"                   => "Languages",
            "Engine/Filesystem/DirectoryHandler" => "DirectoryHandler",
            "Engine/EventManager/EventManager"   => "EventManager"
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


        // todo: cache problem
        // $fileContent = file_get_contents($file);
        // $fileContent = $this->EventManager->dispatch("plugins.file.process", $fileContent);
        // file_put_contents($file, $fileContent);


        /** @var LanguageLoader $language */
        $language = $this->Languages->getLanguageFromFile($file);

        $Dom = new Dom($namespace, $file, $files, $this->level, $language);
        $this->process($file, $Dom);


        return $Dom;

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
            throw new Exception(0, $file . " not found!", $file);
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
     * @param     $file
     * @param Dom $Dom
     *
     * @throws Exception
     * @return Dom
     */
    private function process($file, Dom $Dom)
    {
        $handle      = fopen($file, "r");
        $nodeCounter = 0;

        while (($line = fgets($handle)) !== false) {
            if (trim($line) != '') {
                $node = $this->createNode($line, $Dom);
                $this->addNode($node, $Dom);
                $Dom->setPreviousNode($node);
                $nodeCounter++;
            }

            $Dom->setCurrentLine($Dom->getCurrentLine() + 1);
        }
        fclose($handle);

        return $Dom;
    }


    /**
     * creates a node matching to the criteria
     *
     * @param     $line
     * @param Dom $Dom
     *
     * @return Node
     * @throws Exception
     */
    private function createNode($line, Dom $Dom)
    {
        /** @var LanguageLoader $language */
        $language = $Dom->getLanguage()->getConfig()->getName();
        $line = $this->EventManager->dispatch($language,"plugin.line", $line);

        $arguments = array($line, $Dom);

        /** @var Node $node */
        $node = $this->EventManager->dispatch($language,"node", $arguments);

        if (!($node instanceof Node)) {
            $node = new DefaultNode();
            $node->setEngine($this->EventManager->getEngine());
            $node->dispatch(...$arguments);
        }

        $node->setup();

        return $node;
    }


    /**
     * adds the node to the dom
     * getParentNode/child logic is handled here
     *
     * @param Node $node
     * @param Dom  $Dom
     *
     * @return bool
     */
    private function addNode($node, Dom $Dom)
    {
        # root nodes
        if ($node->getIndent() == 0 || $node->getLine() == 1) {
            $node->setParent(false);
            $rootNodes   = $Dom->getNodes();
            $rootNodes[] = $node;
            $Dom->setNodes($rootNodes);

        } else {
            $indent     = $node->getIndent();
            $prevIndent = $Dom->getPreviousNode()->getIndent();

            # node position
            if ($indent > $prevIndent) {
                $this->addNodeOnDeeperLevel($node, $Dom);
            }

            if ($indent < $prevIndent) {
                $this->addNodeOnHigherLevel($node, $Dom);
            }

            if ($indent == $prevIndent) {
                $this->addNodeOnSameLevel($node, $Dom);
            }

        }

        return true;
    }


    /**
     * adds a node to the dom if has a addNodeOnDeeperLevel level
     * than the previous node
     *
     * @param Node $node
     * @param Dom  $Dom
     *
     * @throws Exception
     * @return mixed
     */
    private function addNodeOnDeeperLevel($node, Dom $Dom)
    {
        $node->setParent($Dom->getPreviousNode());
        if ($node->getParent()->isSelfClosing()) {
            throw new Exception(1, "Current node cant have children!", $Dom->getFile(), $Dom->getCurrentLine());
        }

        return $this->addNodeAsChild($Dom->getPreviousNode(), $node);

    }


    /**
     * adds a node to the dom if has a addNodeOnHigherLevel level
     * than the previous node
     *
     * @param Node $node
     * @param Dom  $Dom
     *
     * @return mixed
     */
    private function addNodeOnHigherLevel($node, Dom $Dom)
    {
        $parent = $this->getParentNode($node, $Dom);
        $node->setParent($parent);

        return $this->addNodeAsChild($parent, $node);
    }


    /**
     * adds a node to the dom if has the addNodeOnSameLevel  level
     * than the previous node
     *
     * @param Node $node
     * @param Dom  $Dom
     *
     * @return mixed
     */
    private function addNodeOnSameLevel($node, Dom $Dom)
    {
        $parent = $Dom->getPreviousNode()->getParent();
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
     * @param Dom       $Dom
     * @param bool|Node $parent
     *
     * @throws Exception
     * @return Node
     */
    private function getParentNode($node, Dom $Dom, $parent = false)
    {

        if (!$parent) {
            $temp = $Dom->getPreviousNode()->getParent();
        } else {
            $temp = $parent->getParent();
        }

        if ($temp) {
            if ($temp->getIndent() == $node->getIndent()) {
                $temp = $temp->getParent();

                return $temp;
            } else {
                return $this->getParentNode($node, $Dom, $temp);
            }
        }

        throw new Exception(4, "Mismatching Nodes detected", $node->getFile(), $node->getLine());
    }

}