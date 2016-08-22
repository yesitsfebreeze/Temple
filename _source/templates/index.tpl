{extends file="_source/body.tpl"}

{block name="content"}
    <section class="light section-1">
        <div class="vertical-center">
            <div class="logo">
                <img src="{$pathPrefix}/assets/img/logo-ultra-large.png" alt="Temple">
            </div>
            <div class="text-center">
                <h1>
                    Temple
                </h1>
                <p>
                    a leightweight template precompiler.
                </p>
            </div>
        </div>
    </section>
    <section class="dark section-2">
        <div class="container vertical-center">
            <div class="row fix-height">
                <div class="col-xs-7 vertical-center">
                    {include file="snippets/editor.tpl" code="index-old" light=true}
                    <div class="parallax" data-distance="250" data-direction="bottom" data-lag="1.5" data-offset="350" data-fade="1" data-minus="false">
                        {include file="snippets/editor.tpl" code="index-preview"}
                    </div>
                </div>
                <div class="col-xs-5">
                    <h3>A new kind of templating.</h3>
                    <p>
                        I's a multianguage engine which provides a clean syntax.
                        <br>
                        No more closing tags and fiddling with brackets.
                    </p>
                </div>
            </div>
        </div>
    </section>
    {*<section class="bg-bamboo bg-stretch">*}
    {*</section>*}
    {*<section class="bg-shrine bg-stretch">*}
    {*</section>*}
    <section class="gradient">
        <div class="container vertical-center">
            <div class="text-center">
                <div class="row">
                    <div class="col-xs-12 col-md-6 col-md-push-3">
                        <p>Temple is super flexible and can be adjusted to your needs.</p>
                        <p>Since it's working event based, you will be able to add custom functionalities at almost any point in the code.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="bg-maple bg-stretch">
        <div class="container vertical-center text-center">
            <div class="parallax" data-distance="200" data-direction="left" data-lag=".3" data-offset="700" data-fade="1" data-minus="false">
                <h2 class="color-white">
                   Enjoy some real zen coding.
                </h2>
            </div>
        </div>
    </section>
    <section class="dark">
        <div class="container vertical-center">
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <h3>object based dom and nodes</h3>
                    <p>
                        Temple is a engine base on a dom and nodes, just like html.
                    </p>
                    <p>
                        It's an python inspired syntax to keep your templates clean and manageable.
                    </p>
                </div>
                <div class="col-xs-12 col-md-6">
                    <div>
                        <div class="parallax" data-distance="400" data-direction="top" data-lag=".5" data-offset="550" data-fade="1.5">
                            {include file="_source/icons/brach.tpl"}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
{/block}