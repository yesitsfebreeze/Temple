<!doctype HTML>
<html>
    <head>
        {include file="_source/header.tpl"}
    </head>
    <body>
        {include "_source/menu.tpl"}
        <div class="page-wrapper {block name="page-wrapper:classes"}{/block}">
            {block name="content"}

            {/block}

            {include "_source/footer.tpl"}
        </div>
    </body>
</html>

