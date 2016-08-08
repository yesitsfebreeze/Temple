{extends file="_source/body.tpl"}

{block name="content"}
    <section class="light">
        <div class="vertical-center">
            <div class="text-center">
                <h1>
                    Welcome to Temple
                </h1>
                <p>
                    A flexible,extensible and lightweight template engine.
                </p>
            </div>
        </div>
    </section>
    <section class="dark">
        <div class="parallax" data-distance="100" data-direction="right" data-speed="0.6">
            <div class="editor">
                <div class="editor-header">
                    <div class="action-buttons"></div>
                </div>
                <div class="editor-body">
                    test
                </div>
            </div>
        </div>
    </section>
    <section class="light">
    </section>
    <section class="dark">
        <div class="container vertical-center">
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <p>
                        Temple is a engine base on a dom and nodes, just like html.
                    </p>
                    <p>
                        It's an python inspired syntax to keep your templates clean and manageable.
                    </p>
                </div>
                <div class="col-xs-12 col-md-6">
                    <div>
                        <div class="parallax" data-distance="400" data-direction="down" data-speed="0.6">
                            {include file="_source/icons/brach.tpl"}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="primary">
        <div class="pattern"></div>
    </section>
    <section class="gradient">
    </section>
    <section class="gradient">
        <div class="pattern"></div>
    </section>
    <section class="light">
        <div class="pattern">

        </div>
    </section>
    <section class="gradient-reverse">
    </section>
    <section class="primary">
        <div class="pattern"></div>
    </section>
{/block}