{if $code}
{$language = ""}
{if $lang}{$language = "language-"|cat:$lang} {/if}
<pre><code class="{$language}">{include file="snippets/snippet.tpl" code=$code}</code></pre>
{/if}