<title>
    {$title}
</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">

{block name="stylesheets"}
    <link rel="stylesheet" href="{$pathPrefix}/css/all.css">
    {$cssFile ="/css/{$pagePath}.css"}
    <link rel="stylesheet" href="{$pathPrefix}{$cssFile}">
{/block}

{block name="javascripts"}
    <script src="{$pathPrefix}/js/all.js" type="text/javascript"></script>
    {$jsFile ="/js/{$pagePath}.js"}
    <script src="{$pathPrefix}{$jsFile}" type="text/javascript"></script>
{/block}
