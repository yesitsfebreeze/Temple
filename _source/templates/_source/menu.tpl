{function name="menu" menu=false path="index"}
    {if is_array($menu)}
        <ul class="menu-list list-unstyled list-inline">
            {foreach $menu as $item}
                <li>
                    {if $item.link}<a href="{$pathPrefix}{$item.link}" title="{$item.name}">{/if}{$item.name}{if $item.link}</a>{/if}
                    {call menu menu=$item.children path=$path}
                </li>
            {/foreach}
        </ul>
    {/if}

{/function}

<div class="menu">
    <div class="container">
        <div class="logo">
            <a href="{$pathPrefix}/" title="temple">
                <img src="{$pathPrefix}/assets/img/logo/logo.png" class="img-responsive" alt="Temple">
                <span class="text">Temple</span>
            </a>
        </div>
        {call menu menu=$menu}


        <ul class="social-links list-unstyled list-inline pull-right">
            <li>
                <a href="#donate" class="jump-to-donate" title="donate me" >
                    <i class="fa fa-heart-o"></i>&nbsp;&nbsp;show some love
                </a>
            </li>
            <li>
                <a href="https://github.com/hvlmnns" title="fork me" target="_blank">
                    <i class="fa fa-code-fork"></i>&nbsp;&nbsp;fork me
                </a>
            </li>
            <li>
                <a href="https://github.com/hvlmnns/Temple" title="github" target="_blank">
                    <i class="fa fa-2x fa-github"></i>
                </a>
            </li>
        </ul>

    </div>
</div>