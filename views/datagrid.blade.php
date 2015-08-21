@if ($showStart)
	<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/jquery-bootgrid/1.2.0/jquery.bootgrid.min.css">

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
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-bootgrid/1.2.0/jquery.bootgrid.min.js"></script>

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
				return {
					"current": response.meta.pagination.current_page,
					"rowCount": response.meta.pagination.per_page,
					"total": response.meta.pagination.total,
					"rows": response.data
				};
			},
			formatters: {
				"link": function(column, row)
				{
					return '<a href="/admin/promotions/show/' + row.id + '">Voir</a>';
				},
				"date": function(column, row)
				{
					return row.dateDebut.split(' ')[0];
				}
			}
		});
	</script>
@endif
