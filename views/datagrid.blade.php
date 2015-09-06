@if ($showStart)
	<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/jquery-bootgrid/1.3.0/jquery.bootgrid.min.css">

	<div>
		<table id="grid-data" class="table table-condensed table-hover table-striped">
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
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-bootgrid/1.3.0/jquery.bootgrid.min.js"></script>

	<script type="text/javascript">
		$("#grid-data").bootgrid({
			ajax: true,
			ajaxSettings: {
				method: "{!! $datagridOptions['method'] !!}"
			},
			url: "{!! $datagridOptions['url'] !!}",
			requestHandler: function(request)
			{
				return {
					"page": request.current,
					"per_page": request.rowCount,
					"sort": Object.keys(request.sort)[0],
					"order": request.sort[Object.keys(request.sort)[0]],
					"search": request.searchPhrase
				}
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
		        date: {
		            from: function (value) { return Date.parse(value); },
		            to: function (value) { return new Date(value).toISOString().split('T')[0]; }
		        },
                datetime: {
                    from: function (value) { return Date.parse(value); },
                    to: function (value) {
                        function pad(number) {
                          if (number < 10) {
                            return '0' + number;
                          }
                          return number;
                        }

                        var d = new Date(value);
                        return d.getUTCFullYear() +
                            '-' + pad(d.getUTCMonth() + 1) +
                            '-' + pad(d.getUTCDate()) +
                            ' ' + pad(d.getUTCHours()) +
                            ':' + pad(d.getUTCMinutes()) +
                            ':' + pad(d.getUTCSeconds());
                    }
                },
		    },
			formatters: {
                "commands": function(column, row)
                {
                    return '<div class="btn-toolbar">\
                                <a href="/view/' + row.id + '" class="btn btn-xs btn-default"><span class="fa fa-eye"></span></a>\
                                <a href="/edit/' + row.id + '" class="btn btn-xs btn-warning"><span class="fa fa-pencil"></span></a>\
                                <a href="/delete/' + row.id + '" class="btn btn-xs btn-danger"><span class="fa fa-trash-o"></span></a>\
                            </div>';
                },
			}
		});

        JSON.unflatten = function(data) {
            "use strict";
            if (Object(data) !== data || Array.isArray(data))
                return data;
            var regex = /\.?([^.\[\]]+)|\[(\d+)\]/g,
                resultholder = {};
            for (var p in data) {
                var cur = resultholder,
                    prop = "",
                    m;
                while (m = regex.exec(p)) {
                    cur = cur[prop] || (cur[prop] = (m[2] ? [] : {}));
                    prop = m[2] || m[1];
                }
                cur[prop] = data[p];
            }
            return resultholder[""];
        };

        JSON.flatten = function(data) {
            var result = {};
            function recurse (cur, prop) {
                if (Object(cur) !== cur) {
                    result[prop] = cur;
                } else if (Array.isArray(cur)) {
                     for(var i=0, l=cur.length; i<l; i++)
                         recurse(cur[i], prop + "[" + i + "]");
                    if (l == 0)
                        result[prop] = [];
                } else {
                    var isEmpty = true;
                    for (var p in cur) {
                        isEmpty = false;
                        recurse(cur[p], prop ? prop+"."+p : p);
                    }
                    if (isEmpty)
                        result[prop] = {};
                }
            }
            recurse(data, "");
            return result;
        }
	</script>
@endif
