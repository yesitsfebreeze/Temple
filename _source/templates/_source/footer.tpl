<footer>
    <section class="light donate-section">
        <div class="pattern parent-height">
            <div class="vertical-center text-center">
                <form id="paypal-donate" action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
                    <input type="hidden" name="cmd" value="_donations">
                    <input type="hidden" name="business" value="stefan.hoevelmanns@schmiechen.de">
                    <input type="hidden" name="lc" value="DE">
                    <input type="hidden" name="item_name" value="Temple donation">
                    <input type="hidden" name="no_note" value="0">
                    <input type="hidden" name="currency_code" value="EUR">
                    <input type="hidden" name="bn" value="PP-DonationsBF:donate-button.png:NonHostedGuest">
                    <button type="submit" class="btn btn-link">
                        <span class="click-me">
                            <img class="img-responsive" src="{$pathPrefix}/assets/img/click-me.png" alt="Temple">
                        </span>
                        <h2 class="text-primary">
                            <i class="icon icon-static fa fa-2x fa-heart-o"></i>
                            <i class="icon icon-hover fa fa-2x fa-heart"></i>
                        </h2>
                    </button>
                    <img alt="" border="0" src="https://www.paypalobjects.com/de_DE/i/scr/pixel.gif" width="1" height="1">
                </form>

                <h3 class="no-margin">
                    Show me your support!
                </h3>
                <p>
                    Since i'm working alone on this project, a little love never hurts.
                </p>

                <small class="text-muted">
                    donation via &nbsp; <i class="fa fa-paypal"></i>
                </small>
            </div>
        </div>
    </section>


    {function name="footermenu" menu=false path="index"}
        {if is_array($menu)}
            <ul class="menu-list list-unstyled">
                {foreach $menu as $item}
                    <li>
                        {if $item.link}
                            <a href="{$pathPrefix}{$item.link}" title="{$item.name}">{$item.name}</a>
                        {/if}
                    </li>
                {/foreach}
            </ul>
        {/if}

    {/function}

    <section class="dark no-height">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-9">
                    <div class="row">
                        <div class="col-xs-12 col-md-3">
                            <a href="{$pathPrefix}/" title="temple">
                                <img src="{$pathPrefix}/assets/img/logo/logo.png" class="img-responsive" alt="Temple">
                            </a>
                        </div>
                        <div class="col-xs-12 col-md-4">{call footermenu menu=$menu}</div>
                        <div class="col-xs-12 col-md-5 text-left">
                            <ul class="social-links list-unstyled list-inline text-right">
                                <li>
                                    <a href="https://github.com/hvlmnns/Temple" title="github" target="_blank">
                                        <i class="fa fa-2x fa-github"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-3">
                    <span class="text-muted">
                        &copy; copyright by hvlmnns &mdash; 2016
                    </span>
                </div>
            </div>
        </div>
    </section>
</footer>