<!doctype HTML>
<html>
    <head>
        <title>
            {$title}
        </title>
        <link rel="stylesheet" href="/css/default.css">
        {$cssFile ="/css/{$pagePath}.css"}
        {if file_exists($cssFile)}
            <link rel="stylesheet" href="{$cssFile}">
        {/if}

    </head>
    <body>
        <div>
            {include "_source/menu.tpl"}
        </div>
    </body>
</html>

