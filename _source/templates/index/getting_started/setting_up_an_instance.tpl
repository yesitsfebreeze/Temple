{extends file="_source/docsSubtemplate.tpl"}

{block name="section:content"}
    <h4>
        Introduction
    </h4>
    <p>
        Temple works instance based, therefore all your settings are stored within one instance and automatically passed to another instance.
        <br>
        First of you need to require the autoloader.php if you'r not using composer.
    </p>

{include file="snippets/editor.tpl" code="getting_started/setting_up_an_instance/autoload" lang="php"}
    <h4>
        Creating an Instance
    </h4>
    <p>
        To create a new instance you would just do this.
    </p>
{include file="snippets/editor.tpl" code="getting_started/setting_up_an_instance/create" lang="php"}

    <h4>
        Configure an Instance
    </h4>
    <p>
        You can either pass a custom config into the instance creation or change the settings afterwards.
    </p>
{include file="snippets/editor.tpl" code="getting_started/setting_up_an_instance/config" lang="php"}

{/block}