<?php
/**
 * Smarty plugin
 *
 * @package    Smarty
 * @subpackage PluginsFunction
 */

/**
 * Smarty Markdown function plugin
 * Type:     markdown<br>
 * Name:     lower<br>
 * Purpose:  convert string to markdown format
 *
 * @link   davidscherer.blogspot.com
 * @author David Scherer <scherer dot david dot h at gmail dot com>
 *
 * @param array $params parameters
 *
 * @return string with converted text
 */

require_once('PHP-Markdown-Lib-1.2.7/Michelf/Markdown.php');

use Michelf\Markdown;


function smarty_function_markdown($params, &$smarty)
{

    if (!in_array('file', array_keys($params))) {
        trigger_error("markdown missing 'file' parameter", E_USER_NOTICE);

        return "";
    }

    $file = $GLOBALS["templateDir"] . $params['file'] . ".md";

    if (file_exists($file)) {
        $content = file_get_contents($file);
    } else {
        trigger_error("the file " . $file . " does not exist", E_USER_NOTICE);

        return "";
    }

    if (isset($content)) {
        return Markdown::defaultTransform($content);
    }

    return "";

}

?>