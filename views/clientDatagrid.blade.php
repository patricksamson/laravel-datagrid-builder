@if ($showStart)
    <link rel="stylesheet" type="text/css" href="{!! config('datagrid-builder.css_url') !!}">

    <div>
        <table id="{{ $HTML_id }}" class="{!! config('datagrid-builder.default_css.datagrid_class') !!}">
            <thead>
                <tr>
@endif

@if ($showColumns)
    @foreach ($columns as $column)
        @if( ! in_array($column->getName(), $exclude) )
            {!! $column->render() !!}
        @endif
    @endforeach
@endif

@if ($showEnd)
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="{!! config('datagrid-builder.js_url') !!}"></script>

    <script type="text/javascript">
        JSON.unflatten=function(r){"use strict";if(Object(r)!==r||Array.isArray(r))return r;var e=/\.?([^.\[\]]+)|\[(\d+)\]/g,n={};for(var t in r){for(var a,f=n,i="";a=e.exec(t);)f=f[i]||(f[i]=a[2]?[]:{}),i=a[2]||a[1];f[i]=r[t]}return n[""]},JSON.flatten=function(r){function e(r,t){if(Object(r)!==r)n[t]=r;else if(Array.isArray(r)){for(var a=0,f=r.length;f>a;a++)e(r[a],t+"["+a+"]");0==f&&(n[t]=[])}else{var i=!0;for(var u in r)i=!1,e(r[u],t?t+"."+u:u);i&&(n[t]={})}}var n={};return e(r,""),n};

        var deferredBootgrid = $.Deferred(),
            deferredAjax = $.Deferred();

        var rows = []
        var fetchDatagridJSON = function() {
            rows = []
            $.getJSON("{!! $datagridOptions['url'] !!}")
                .done(function( json ) {
                    if (Array.isArray(json.data)) {
                        json.data.forEach(function(element) {
                            rows.push(JSON.flatten(element))
                        })
                    } else {
                        rows = response.responseJSON.data
                    }

                    deferredAjax.resolve()
                })
        }
        fetchDatagridJSON()

        var datagrid = $("#{{ $HTML_id }}")
        .on("initialized.rs.jquery.bootgrid", function (e)
        {
            deferredBootgrid.resolve()
        })
        .bootgrid({
            caseSensitive: {{ json_encode($datagridOptions['searchSettings']['caseSensitive']) }},
            searchSettings: {
                delay: {{ $datagridOptions['searchSettings']['delay'] }},
                characters: {{ $datagridOptions['searchSettings']['characters'] }}
            },
            rowCount: {!! json_encode($datagridOptions['rowCount']) !!},
            converters: {
                @include($datagridOptions['converters']['view'],['options' => $datagridOptions['converters']['options']])
            },
            formatters: {
                @include($datagridOptions['formatters']['view'], ['options' => $datagridOptions['formatters']['options']])
            },
            labels: {
                all: "@lang('datagrid-builder::datagrid-builder.all')",
                infos: "@lang('datagrid-builder::datagrid-builder.infos')",
                loading: "@lang('datagrid-builder::datagrid-builder.loading')",
                noResults: "@lang('datagrid-builder::datagrid-builder.noResults')",
                refresh: "@lang('datagrid-builder::datagrid-builder.refresh')",
                search: "@lang('datagrid-builder::datagrid-builder.search')",
            }
        })

        $.when(deferredBootgrid,deferredAjax).done(function(){
            datagrid.bootgrid("append", rows)
            rows = []

            $("#{{ $HTML_id }}-header .actions").prepend('<div class="btn-group"><button id="{{ $HTML_id }}-refresh" class="btn btn-default"><span class="icon glyphicon glyphicon-refresh"></span></button></div>')

            $("#{{ $HTML_id }}-refresh").click(function() {
                deferredAjax = $.Deferred();
                fetchDatagridJSON()
                $.when(deferredBootgrid,deferredAjax).done(function(){
                    datagrid.bootgrid("clear").bootgrid("append", rows)
                })
            })
        });

    </script>
@endif
