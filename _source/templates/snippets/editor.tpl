{if $code}
<div class="editor {if !isset($light)}dark{/if}">
<div class="editor-header">
    <div class="action-buttons">
        <div class="action-button btn1"></div>
        <div class="action-button btn2"></div>
        <div class="action-button btn3"></div>
    </div>
    {if isset($lang)}
    <div class="editor-language">
        {$lang}
    </div>
    {/if}
</div>
<div class="editor-body">
{include file="snippets/code.tpl" code=$code lang=$lang}
</div>
</div>
{/if}


