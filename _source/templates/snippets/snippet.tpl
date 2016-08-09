{if $code}
{capture name="code" assign="code"}
{include file="snippets/codes/$code.md"}
{/capture}
{$code = $code|replace:">":"&gt;"}
{$code = $code|replace:"<":"&lt;"}
{$code}
{/if}