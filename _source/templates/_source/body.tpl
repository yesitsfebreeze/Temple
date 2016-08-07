<!doctype HTML>
<html>
    <head>
        <title>
            {$title}
        </title>
        <link rel="stylesheet" href="/css/all.css">
        {$cssFile ="/css/{$pagePath}.css"}
        {if file_exists($cssFile)}
            <link rel="stylesheet" href="{$cssFile}">
        {/if}

    </head>
    <body>
        <div class="container">
            {include "_source/menu.tpl"}
        </div>
        {block name="content"}

        {/block}
    </body>
</html>

