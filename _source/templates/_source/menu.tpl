{function name="menu" menu=false path="index"}
    {if is_array($menu)}
        <ul class="menu-list">
            {foreach $menu as $item}
                <li>
                    {if $item.link}
                    <a href="{$item.link}" title="{$item.name}">
                        {/if}
                        {$item.name}
                        {if $item.link}
                    </a>
                    {/if}

                    {call menu menu=$item.children path=$path}
                </li>
            {/foreach}
        </ul>
    {/if}

{/function}

<div class="menu">
    {call menu menu=$menu}
</div>