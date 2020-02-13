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

function get_id_for_event(trigger)
{
    var tr = $(trigger).closest('tr');
    var table = tr.closest('table');
    var api = table.DataTable();
    var row = api.row(tr).data();
    if(row._id.$id !== undefined)
    {
        return row._id.$id;
    }
    return row._id;
}

function edit_obj()
{
    location.href='/register/tc_reg.php?_id='+get_id_for_event(this)+'&is_admin=true';
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
            url: '/register/api/v1/camps/'+_id,
            success: done_del
        });
    }
    _id = null;
}

function del_obj()
{
    _id = get_id_for_event(this);
    bootbox.confirm("Are you sure you want to delete this camp?", really_del);
}

function unlockDone(data)
{
    if(data === true)
    {
        alert('Unlocked');
    }
    else
    {
        alert('Error!');
    }
}

function unlockObject()
{
     _id = get_id_for_event(this);
     $.post('../api/v1/camps/'+_id+'/Actions/Unlock', '', unlockDone);
}

function getStructs()
{
     _id = get_id_for_event(this);
     location = '../api/v1/camps/'+_id+'/structs?fmt=xlsx';
}

function calculateSquareFootage(data, type, row) {
  console.log(data);
  return '';
}

function data_obtained(data)
{
    if(data.length === 0) return;
    var columns = [{
        'data': null,
        'defaultContent': '<button name="edit"><span class="fa fa-pencil"></span></button> <button name="del"><span class="fa fa-remove"></span></button> <button name="unlock"><span class="fa fa-unlock"></span></button> <button name="structs"><span class="fa fa-home"></span></button>'
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
    $.fn.dataTable.ext.errMode = 'none';
    $('#tc').dataTable({
        'data': data,
        'columns': columns
    });
    $('#listTable tbody').on('click', 'button[name="edit"]', edit_obj);
    $('#listTable tbody').on('click', 'button[name="del"]', del_obj);
    $('#listTable tbody').on('click', 'button[name="unlock"]', unlockObject);
    $('#listTable tbody').on('click', 'button[name="structs"]', getStructs);
}

function tc_page_loaded()
{
    var finished = getParameterByName('finished');
    var filter = '';
    if(finished !== null)
    {
    	filter = '&$filter=final eq true and year eq current';
    }
    $.ajax({
        url: '../api/v1/camps?no_logo=1'+filter,
        success: data_obtained
    });
}
