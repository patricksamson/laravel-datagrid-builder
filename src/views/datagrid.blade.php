@if ($showStart)
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
@endif