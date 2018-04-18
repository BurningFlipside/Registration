function render_logo(data, type, row)
{
    return '<img src="'+data+'" style="max-width:100px; max-height:100px;"/>';
}

var renderers = [
    {'logo': render_logo}
];

var hidden = [
    '_id'
];

function changeDLType()
{
    var format = $('#dlFormat').val();
    var links = $('.dl_link').each(function(){
        this.href = this.href.replace(/(fmt=)[^\&]+/, '$1'+format);
    });
}

function get_id_for_event(trigger)
{
    var tr = $(trigger).closest('tr');
    var table = tr.closest('table');
    var api = table.DataTable();
    var row = api.row(tr).data();
    if(row._id !== undefined)
    {
        if(row._id.$id !== undefined)
        {
            return row._id.$id;
        }
        return row._id;
    }
    return row[1];
}

function edit_obj()
{
    location.href='/register/art_reg.php?_id='+get_id_for_event(this)+'&is_admin=true';
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
            url: '/register/api/v1/art/'+_id,
            success: done_del
        });
    }
    _id = null;
}

function del_obj()
{
    _id = get_id_for_event(this);
    bootbox.confirm("Are you sure you want to delete this art project?", really_del);
}

function data_obtained(data)
{
    if(data.length === 0) return;
    var columns = [{
        'data': null,
        'defaultContent': '<button name="edit"><span class="fa fa-pencil"></span></button> <button name="del"><span class="fa fa-remove"></span></button>'
    }];
    var toggles = $('#coltoggle');
    for(var_name in data[0])
    {
        var col = {};
        col.title = var_name;
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
        var len = columns.push(col);
        len = len-1;
        toggles.append('<a class="toggle-vis" data-column="'+len+'">'+var_name+'</a> | ');
    }
    $.fn.dataTable.ext.errMode = 'none';
    $('#art').dataTable({
        'data': [],
        'columns': columns
    });
    var table = $('#art').DataTable();
    for(i = 0; i < data.length; i++)
    {
        var obj = data[i];
        var arr = [];
        for(j = 0; j < columns.length; j++)
        {
            if(obj[columns[j].title] !== undefined)
            {
                arr.push(obj[columns[j].title]);
            }
            else
            {
                arr.push(null);
            }
        }
        table.row.add(arr);
    }
    table.draw(false);
    $('a.toggle-vis').on('click', function (e){
        e.preventDefault();
 
        // Get the column API object
        var column = table.column( $(this).attr('data-column') );
                  
        // Toggle the visibility
        column.visible( ! column.visible() );
    } );
}

function art_page_loaded()
{
    var finished = getParameterByName('finished');
    var filter = '';
    if(finished !== null)
    {
        filter = '&$filter=final eq true and year eq current';
    }
    $.ajax({
        url: '/register/api/v1/art?no_logo=1&fmt=json-ss'+filter,
        success: data_obtained
    });
    $('#art tbody').on('click', 'button[name="edit"]', edit_obj);
    $('#art tbody').on('click', 'button[name="del"]', del_obj);
}

$(art_page_loaded);
