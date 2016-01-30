@if ($showStart)
    <div>
        <table {!! $tableAttrs !!}>
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
