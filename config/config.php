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
    'datagrid_defaults' => [
        'view' => 'datagrid-builder::datagrid',
        'attr' => [
            // HTML & CSS
            'data-classes' => 'table table-striped table-bordered table-hover',

            // Data
            'data-url' => null,
            'data-method' => 'GET',
            'data-cache' => true, // Cache Ajax requests.
            'data-flat' => true, // requires the "Flat JSON" extension; flattens to a single-level array.
            'data-data-field' => 'data', // Which JSON attribute contains the data array?
            'data-id-field' => 'id', // Indicate which field is an identity field.

            // Sorting
            'data-sortable' => true, // False to disable sortable of all columns.

            // Pagination
            'data-pagination' => true,
            'data-side-pagination' => 'client', // 'client' or 'server' with Ajax
            'data-page-size' => 10,
            'data-page-list' => '[5, 10, 20, 50, All]',

            // Search
            'data-search' => true,
            'data-search-time-out' => 250, // Wait for X ms after last input before firing the search.

            // UI
            'data-locale' => 'en-US',
            'data-show-refresh' => true,
            'data-show-toggle' => false, // Toggle for the card view
            'data-show-columns' => true, // Menu to show/hide columns.
            'data-show-footer' => false, // A summary footer, for totals and such.
        ],
    ],

    'column_defaults' => [
        'view' => 'datagrid-builder::column',
        'attr' => [
            'data-sortable' => true,
            'data-order' => 'asc',
            'data-visible' => true,
            'data-searchable' => true,
            'data-class' => null, // The column class name.
            'data-field' => null, // The column field name.
            'data-title' => null, // The column header title text.
        ],
    ],

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
    'css_url' => '//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.9.1/bootstrap-table.min.css',
    'js_url' => '//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.9.1/bootstrap-table.min.js',

];
