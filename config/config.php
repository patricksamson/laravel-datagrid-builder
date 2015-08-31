<?php

return [

    'defaults' => [
        'datagrid_class' => 'table table-condensed table-hover table-striped',
        'column_class'   => 'form-control',
    ],

    // Views Templates
    'datagrid' => 'datagrid-builder::datagrid',
    'column'   => 'datagrid-builder::column',

    'api' => [
        'parameters' => [
            'page'     => 'page',
            'per_page' => 'per_page',
            'sort'     => 'sort',
            'order'    => 'order',
            'search'   => 'search',
            'include'  => 'include',
        ],
    ],

];
