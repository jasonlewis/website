<?php

return array(

	/*
    |--------------------------------------------------------------------------
    | Collections
    |--------------------------------------------------------------------------
    |
    | Basset is built around collections. A collection contains assets for
    | your application. Collections can contain both stylesheets and
    | javascripts.
    |
    | A default "application" collection is ready for immediate use. It makes
    | a couple of assumptions about your directory structure.
    |
    | /public
    |    /assets
    |        /stylesheets
    |            /less
    |            /sass
    |        /javascripts
    |            /coffeescripts
    |
    | You can overwrite this collection or remove it by publishing the config.
    |
    */

    'website' => function($collection)
    {
        $asset = $collection->stylesheet('assets/stylesheets/sass/website.scss');
        $asset->apply('Sass');
        $asset->apply('CssMin')->whenProductionBuild();
        $asset->apply('UriRewriteFilter');

        $collection->javascript('assets/javascripts/tooltip.min.js')->apply('JsMin')->whenProductionBuild();
        $collection->javascript('assets/javascripts/website.js')->apply('JsMin')->whenProductionBuild();
        $collection->javascript('assets/javascripts/prettify.js')->raw();
    }

);