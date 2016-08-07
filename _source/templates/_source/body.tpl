<!doctype HTML>
<html>
    <head>
        <title>
            {$title}
        </title>

        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">

        {block name="stylesheets"}
            <link rel="stylesheet" href="{$pathPrefix}/css/all.css">
            {$cssFile ="/css/{$pagePath}.css"}
            {if file_exists($cssFile)}
                <link rel="stylesheet" href="{$pathPrefix}{$cssFile}">
            {/if}
        {/block}

        {block name="javascripts"}
            <script src="{$pathPrefix}/js/all.js" type="text/javascript"></script>
            {$jsFile ="/js/{$pagePath}.js"}
        {if file_exists($jsFile)}
            <script src="{$pathPrefix}{$jsFile}" type="text/javascript"></script>
        {/if}
        {/block}


    </head>
    <body>
        {include "_source/menu.tpl"}
        <div class="page-wrapper">
            {block name="content"}

            {/block}

            {include "_source/footer.tpl"}
        </div>
    </body>
</html>

