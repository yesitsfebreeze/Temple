{extends file="_source/body.tpl"}

{block name="content"}
    <section class="light section-1">
        <div class="vertical-center">
            <div class="logo">
                <img src="{$pathPrefix}/assets/img/lgoo-ultra-large.png" alt="Temple">
            </div>
            <div class="text-center">
                <h1>
                    Welcome to Temple
                </h1>
                <p>
                    A flexible,extensible and lightweight php template engine.
                </p>
            </div>
        </div>
    </section>
    <section class="dark section-2">
        <div class="container vertical-center">
            <div class="row fix-height">
                <div class="col-xs-7 vertical-center">
                    {include file="snippets/editor.tpl" code="index-old"}
                    <div class="parallax" data-distance="250" data-direction="top" data-lag="0.9" data-offset="350" data-fade="3.5" data-minus="false">
                        {include file="snippets/editor.tpl" code="index-preview" dark=true}
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
    <section class="gradient">
        <div class="container vertical-center">
            <div class="text-center">
                some more text
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
                        <div class="parallax" data-distance="400" data-direction="bottom" data-lag=".5" data-offset="500" data-fade="3">
                            {include file="_source/icons/brach.tpl"}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="light">
        <div class="pattern parent-height">
            <div class="vertical-center text-center">
                <h3>
                    Show me your support!
                </h3>
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                    <input type="hidden" name="cmd" value="_donations">
                    <input type="hidden" name="business" value="stefan.hoevelmanns@schmiechen.de">
                    <input type="hidden" name="lc" value="DE">
                    <input type="hidden" name="item_name" value="Temple donation">
                    <input type="hidden" name="no_note" value="0">
                    <input type="hidden" name="currency_code" value="EUR">
                    <input type="hidden" name="bn" value="PP-DonationsBF:donate-button.png:NonHostedGuest">
                    <input type="image" src="http://hvlmnns.github.io/Temple/assets/img/donate-button.png" border="0" name="submit" alt="Jetzt einfach, schnell und sicher online bezahlen – mit PayPal.">
                    <img alt="" border="0" src="https://www.paypalobjects.com/de_DE/i/scr/pixel.gif" width="1" height="1">
                </form>
            </div>
        </div>
    </section>
{/block}