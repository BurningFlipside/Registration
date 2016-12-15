function render_logo(data, type, row)
{
    return '<img src="'+data+'" style="max-width:100px; max-height:100px;"/>';
}

var renderers = [
    {'logo': render_logo}
];

var hidden = [
    '_id',
    'final'
];


function get_id_for_event(trigger)
{
    var tr = $(trigger).closest('tr');
    var table = tr.closest('table');
    var api = table.DataTable();
    var row = api.row(tr).data();
    return row._id.$id;
}

function edit_obj()
{
    location.href='/register/event_reg.php?_id='+get_id_for_event(this)+'&is_admin=true';
}

function done_del(data)
{
    location.reload();
}

var _id = null;

function really_del(result)
{
    if(result)
    {
        $.ajax({
            type: 'DELETE',
            url: '/register/api/v1/event/'+_id,
            success: done_del
        });
    }
    _id = null;
}

function del_obj()
{
    _id = get_id_for_event(this);
    bootbox.confirm("Are you sure you want to delete this event?", really_del);
}

function data_obtained(data)
{
    if(data.length === 0) return;
    var columns = [{
        'data': null,
        'defaultContent': '<button name="edit"><span class="glyphicon glyphicon-pencil"></span></button> <button name="del"><span class="glyphicon glyphicon-remove"></span></button>'
    }];
    for(var_name in data[0])
    {
        var col = {};
        col.title = var_name;
        col.data = var_name;
        for(i = 0; i < renderers.length; i++)
        {
            if(renderers[i][var_name] !== undefined)
            {
                col.render = renderers[i][var_name];
            }
        }
        if($.inArray(var_name, hidden) !== -1)
        {
            col.visible=false;
        }
        columns.push(col);
    }
    for(i = 0; i < data.length; i++)
    {
        if(data[i]['final'] === undefined)
        {
            data[i]['final'] = false;
        }
    }
    $('#evt').dataTable({
        'data': data,
        'columns': columns
    });
}

function art_page_loaded()
{
    $.ajax({
        url: '/register/api/v1/event?$filter=year eq 2017&no_logo=1',
        success: data_obtained
    });
    $('#evt tbody').on('click', 'button[name="edit"]', edit_obj);
    $('#evt tbody').on('click', 'button[name="del"]', del_obj);
}

$(art_page_loaded);
