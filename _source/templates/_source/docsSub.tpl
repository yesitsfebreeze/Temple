{extends file="index.tpl"}

{block name="content:docs"}
    {foreach $docsPages as $page}
        <h2>{$page.name}</h2>
        <div class="jump-{$page.escapedName} docs-page-jumper"></div>
        {include file=$page.file}
    {/foreach}
{/block}