<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Datagrid Styling
    |--------------------------------------------------------------------------
    |
    | Specify the CSS classes that will be added by default to the various
    | HTML elements that will be generated.
    |
     */
    'default_css' => [
        // Will be appended to the <table> element
        'datagrid_class' => 'table table-condensed table-hover table-striped',

        // Will be appended to all the <th> elements
        'column_class' => 'table-column',
    ],

    /*
    |--------------------------------------------------------------------------
    | Available Views
    |--------------------------------------------------------------------------
    |
    | This array contains all the views that can be used to properly
    | generate a datagrid.
    |
     */
    'views' => [
        // For client-side processing
        'client_datagrid' => 'datagrid-builder::clientDatagrid',

        // For server-side processing
        'server_datagrid' => 'datagrid-builder::serverDatagrid',

        // For the table headers
        'column' => 'datagrid-builder::column',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Datagrid View
    |--------------------------------------------------------------------------
    |
    | Set the default view that will be used when generating a datagrid.
    | Use one of the keys from the 'views' array above.
    |
     */
    'default_datagrid_view' => 'client_datagrid',

    /**
     * jQuery Bootgrid assets location
     */
    /*
    |--------------------------------------------------------------------------
    | Assets Location
    |--------------------------------------------------------------------------
    |
    | Set the URLs of the assets used by jQuery Bootgrid. By default, a CDN
    | is used, but you can also host the files yourself.
    |
    | WARNING : jQuery Bootgrid also requires jQuery. It is expected
    | that you already included it.
    |
     */
    'css_url' => '//cdnjs.cloudflare.com/ajax/libs/jquery-bootgrid/1.3.1/jquery.bootgrid.min.css',
    'js_url'  => '//cdnjs.cloudflare.com/ajax/libs/jquery-bootgrid/1.3.1/jquery.bootgrid.min.js',

];
