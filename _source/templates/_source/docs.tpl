{extends file="_source/body.tpl"}


{block name="page-wrapper:classes"}has-breadcrumbs is-docs{/block}

{block name="content"}
    {function name="docsMenu" menu=false path="index"}
        {if is_array($menu)}
            <ul class="menu-list list-unstyled">
                <li>
                    <a href="{$pathPrefix}/documentation" title="Overview" {if $item.escapedName == "documentation"}class="active"{/if}>
                        Overview
                    </a>
                </li>
                {foreach $menu as $item}
                    <li>
                        {if $item.link}
                            <a href="{$pathPrefix}{$item.link}" title="{$item.name}" {if $item.escapedName == $pageName}class="active"{/if}>
                                {$item.name}
                            </a>
                        {/if}
                    </li>
                {/foreach}
            </ul>
        {/if}
    {/function}

    {function name="docsSubMenu" menu=false path="index" htag="h3"}
        {if is_array($menu) && $menu|@count > 0}
            <div class="docs-on-this-page">
                <{$htag} class="text-upper">on this page</{$htag}>
                <ul class="menu-list list-unstyled">
                    {foreach $menu as $item}
                        <li class="doc-link">
                            <a href="#{$item.escapedName}" title="{$item.name}">{$item.name}</a>
                        </li>
                    {/foreach}
                </ul>
            </div>
        {/if}
    {/function}

    <div class="container">
        <div class="row border-left-light">
            <div class="col-xs-3 no-padding-left">
                <div class="jump-docs-sidebar docs-page-jumper no-index"></div>
                <h4 class="pl-grid">
                    Documentation
                </h4>
                <div class="sidebar">
                    {call docsMenu menu=$docsMenu}
                </div>
                <div class="breadcrumbs fade">
                    <div class="breadcrumbs-bg">
                        <div class="sidebar">
                            {call docsSubMenu menu=$docsSubMenu htag="h4"}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-9">
                <div class="docs">
                    <div class="head">
                        <h1>
                            {$docTitle}
                        </h1>
                        <p>
                            {$docText}
                        </p>
                        {call docsSubMenu menu=$docsSubMenu}
                    </div>
                    <div class="docs-sections">
                        {block name="content:docs"}

                        {/block}
                    </div>
                </div>
            </div>
        </div>
    </div>
{/block}