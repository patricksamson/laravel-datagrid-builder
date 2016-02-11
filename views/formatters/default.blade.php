<script type="text/javascript">
function DatagridBuilder() {};

DatagridBuilder.commands = function(value, row, index) {
    return '<div class="btn-toolbar">\
                <a href="/view/' + row.id + '" class="btn btn-xs btn-default"><span class="fa fa-eye"></span></a>\
                <a href="/edit/' + row.id + '" class="btn btn-xs btn-warning"><span class="fa fa-pencil"></span></a>\
                <a href="/delete/' + row.id + '" class="btn btn-xs btn-danger"><span class="fa fa-trash-o"></span></a>\
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