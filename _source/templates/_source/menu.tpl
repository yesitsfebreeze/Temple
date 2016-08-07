{function name="menu" menu=false path="index"}
    {if is_array($menu)}
        <ul class="menu-list list-inline">
            {foreach $menu as $item}
                <li>
                    {if $item.link}<a href="{$item.link}" title="{$item.name}">{/if}{$item.name}{if $item.link}</a>{/if}
                    {call menu menu=$item.children path=$path}
                </li>
            {/foreach}
        </ul>
    {/if}

{/function}

<div class="menu">
    <div class="logo">
        <a href="/" title="temple">
            <img src="/_source/assets/img/logo/logo.svg" alt="temple">
        </a>
    </div>
    {call menu menu=$menu}
</div>