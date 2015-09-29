<?php

return [

    'defaults' => [
        'datagrid_class' => 'table table-condensed table-hover table-striped',
        'column_class'   => 'form-control',
    ],

    // Views Templates
    'datagrid' => 'datagrid-builder::datagrid',
    'column'   => 'datagrid-builder::column',

    'css_url' => '//cdnjs.cloudflare.com/ajax/libs/jquery-bootgrid/1.3.1/jquery.bootgrid.min.css',
    'js_url'  => '//cdnjs.cloudflare.com/ajax/libs/jquery-bootgrid/1.3.1/jquery.bootgrid.min.js',

];
