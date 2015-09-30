@if ($showStart)
    <link rel="stylesheet" type="text/css" href="{!! config('datagrid-builder.css_url') !!}">

    <div>
        <table id="{{ $name or 'datagrid' }}" class="table table-condensed table-hover table-striped">
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
        $("#{{ $name or 'datagrid' }}").bootgrid({
            ajax: true,
            ajaxSettings: {
                method: "{!! $datagridOptions['method'] !!}"
            },
            url: "{!! $datagridOptions['url'] !!}",
            rowCount: {!! json_encode($datagridOptions['rowCount']) !!},
            requestHandler: function(request)
            {
                var query = {
                    "page": request.current,
                    "per_page": request.rowCount,
                    "sort": Object.keys(request.sort)[0],
                    "order": request.sort[Object.keys(request.sort)[0]],
                    "search": request.searchPhrase
                }

                @unless (empty($datagridOptions['ajaxParams']))
                    $.extend(true, query, {!! json_encode($datagridOptions['ajaxParams']) !!});
                @endunless

                return query;
            },
            responseHandler: function (response)
            {
                var rows = [];
                if (Array.isArray(response.data)) {
                    response.data.forEach(function(element) {
                        rows.push(JSON.flatten(element));
                    });
                } else {
                    rows = response.data;
                }

                return {
                    "current": response.meta.pagination.current_page,
                    "rowCount": response.meta.pagination.per_page,
                    "total": response.meta.pagination.total,
                    "rows": rows
                };
            },
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
        });

        JSON.unflatten=function(r){"use strict";if(Object(r)!==r||Array.isArray(r))return r;var e=/\.?([^.\[\]]+)|\[(\d+)\]/g,n={};for(var t in r){for(var a,f=n,i="";a=e.exec(t);)f=f[i]||(f[i]=a[2]?[]:{}),i=a[2]||a[1];f[i]=r[t]}return n[""]},JSON.flatten=function(r){function e(r,t){if(Object(r)!==r)n[t]=r;else if(Array.isArray(r)){for(var a=0,f=r.length;f>a;a++)e(r[a],t+"["+a+"]");0==f&&(n[t]=[])}else{var i=!0;for(var u in r)i=!1,e(r[u],t?t+"."+u:u);i&&(n[t]={})}}var n={};return e(r,""),n};
    </script>
@endif
