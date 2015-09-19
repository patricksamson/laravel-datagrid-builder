commands: function(column, row)
{
    return '<div class="btn-toolbar">\
                <a href="/view/' + row.id + '" class="btn btn-xs btn-default"><span class="fa fa-eye"></span></a>\
                <a href="/edit/' + row.id + '" class="btn btn-xs btn-warning"><span class="fa fa-pencil"></span></a>\
                <a href="/delete/' + row.id + '" class="btn btn-xs btn-danger"><span class="fa fa-trash-o"></span></a>\
            </div>';
},
