function get_id_from_row(row)
{
    if(row._id.$id !== undefined)
    {
        return row._id.$id;
    }
    return row._id;
}

function render_camp_logo(data, type, row, meta)
{
    if(data === undefined)
    {
        return '';
    }
    return '<a href="view_tc.php?id='+get_id_from_row(row)+'"><img src="'+data+'" style="max-width:100px; max-height:100px;"/></a>';
}

function render_camp_name(data, type, row, meta)
{
    return '<a href="view_tc.php?id='+get_id_from_row(row)+'">'+data+'</a>';
}

function render_art_logo(data, type, row, meta)
{
    if(data === undefined)
    {
        return '';
    }
    return '<a href="view_art.php?id='+get_id_from_row(row)+'"><img src="'+data+'" style="max-width:100px; max-height:100px;"/></a>';
}

function render_art_name(data, type, row, meta)
{
    return '<a href="view_art.php?id='+get_id_from_row(row)+'">'+data+'</a>';
}

function render_dmv_logo(data, type, row, meta)
{
    if(data === undefined)
    {
        return '';
    }
    return '<a href="view_dmv.php?id='+get_id_from_row(row)+'"><img src="'+data+'" style="max-width:100px; max-height:100px;"/></a>';
}

function render_dmv_name(data, type, row, meta)
{
    return '<a href="view_dmv.php?id='+get_id_from_row(row)+'">'+data+'</a>';
}

function render_event_logo(data, type, row, meta)
{
    if(data === undefined)
    {
        return '';
    }
    return '<a href="view_event.php?id='+get_id_from_row(row)+'"><img src="'+data+'" style="max-width:100px; max-height:100px;"/></a>';
}

function render_event_name(data, type, row, meta)
{
    return '<a href="view_event.php?id='+get_id_from_row(row)+'">'+data+'</a>';
}

function init_tables()
{
    $('#tcTable').dataTable({
        'sAjaxSource': 'api/v1/camps',
        'iODataVersion': 4,
        'columns': [
            {'data': 'logo', 'render': render_camp_logo, 'width': '100px', 'orderable': false},
            {'data': 'name', 'render': render_camp_name},
            {'data': 'teaser'}
        ],
        'order': [[1, 'asc']],
        'fnServerData': fnServerOData,
        'bServerSide': true
    });
    $('#artTable').dataTable({
        'sAjaxSource': 'api/v1/art',
        'iODataVersion': 4,
        'columns': [
            {'data': 'logo', 'render': render_art_logo, 'width': '100px', 'orderable': false},
            {'data': 'name', 'render': render_art_name},
            {'data': 'teaser'}
        ],
        'order': [[1, 'asc']],
        'fnServerData': fnServerOData,
        'bServerSide': true
    });
    $('#dmvTable').dataTable({
        'sAjaxSource': 'api/v1/dmv',
        'iODataVersion': 4,
        'columns': [
            {'data': 'logo', 'render': render_dmv_logo, 'width': '100px', 'orderable': false},
            {'data': 'name', 'render': render_dmv_name},
            {'data': 'teaser'}
        ],
        'order': [[1, 'asc']],
        'fnServerData': fnServerOData,
        'bServerSide': true
    });
    $('#eventTable').dataTable({
        'sAjaxSource': 'api/v1/event',
        'iODataVersion': 4,
        'ajax': 'api/v1/event?fmt=data-table&select=logo,name,teaser',
        'columns': [
            {'data': 'logo', 'render': render_event_logo, 'width': '100px', 'orderable': false},
            {'data': 'name', 'render': render_event_name},
            {'data': 'teaser'}
        ],
        'order': [[1, 'asc']],
        'fnServerData': fnServerOData,
        'bServerSide': true
    });
}

$(init_tables);
