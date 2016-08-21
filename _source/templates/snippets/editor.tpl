{if $code}
<div class="editor {if isset($dark)}dark{/if}">
<div class="editor-header">
    <div class="action-buttons"></div>
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


