<script type="text/javascript">
function DatagridBuilder() {};

DatagridBuilder.actions = function(value, row, index) {
    return '<div class="btn-toolbar">\
      @if (isset($data['formatters.actions.view']))
        <a href="{!! $data['formatters.actions.view'] !!}" class="btn btn-xs btn-default"><span class="fa fa-eye"></span></a>\
      @endif
      @if (isset($data['formatters.actions.edit']))
        <a href="{!! $data['formatters.actions.edit'] !!}" class="btn btn-xs btn-warning"><span class="fa fa-pencil"></span></a>\
      @endif
      @if (isset($data['formatters.actions.delete']))
        <a href="{!! $data['formatters.actions.delete'] !!}" class="btn btn-xs btn-danger"><span class="fa fa-trash-o"></span></a>\
      @endif
    </div>';
}

DatagridBuilder.replaceSpecialChars = function(value) {
    return value.replace(/[^\w\s]/gi, '');
}

DatagridBuilder.dateFormatter = function(value) {
    return new Date(value).toISOString().split('T')[0];
}

DatagridBuilder.datetimeFormatter = function(value) {
    function pad(number) {
        return (number < 10) ? '0' + number : number;
    }

    var d = new Date(value);
    return d.getUTCFullYear() +
        '-' + pad(d.getMonth() + 1) +
        '-' + pad(d.getDate()) +
        ' ' + pad(d.getHours()) +
        ':' + pad(d.getMinutes()) +
        ':' + pad(d.getSeconds());
}

</script>
