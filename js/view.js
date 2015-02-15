function render_camp_logo(data, type, row, meta)
{
    if(data === undefined)
    {
        return '';
    }
    return '<a href="view_tc.php?id='+row._id+'"><img src="'+data+'" style="max-width:100px; max-height:100px;"/></a>';
}

function render_camp_name(data, type, row, meta)
{
    return '<a href="view_tc.php?id='+row._id+'">'+data+'</a>';
}

function render_art_logo(data, type, row, meta)
{
    if(data === undefined)
    {
        return '';
    }
    return '<a href="view_art.php?id='+row._id+'"><img src="'+data+'" style="max-width:100px; max-height:100px;"/></a>';
}

function render_art_name(data, type, row, meta)
{
    return '<a href="view_art.php?id='+row._id+'">'+data+'</a>';
}

function render_dmv_logo(data, type, row, meta)
{
    if(data === undefined)
    {
        return '';
    }
    return '<a href="view_dmv.php?id='+row._id+'"><img src="'+data+'" style="max-width:100px; max-height:100px;"/></a>';
}

function render_dmv_name(data, type, row, meta)
{
    return '<a href="view_dmv.php?id='+row._id+'">'+data+'</a>';
}

function render_event_logo(data, type, row, meta)
{
    if(data === undefined)
    {
        return '';
    }
    return '<a href="view_event.php?id='+row._id+'"><img src="'+data+'" style="max-width:100px; max-height:100px;"/></a>';
}

function render_event_name(data, type, row, meta)
{
    return '<a href="view_event.php?id='+row._id+'">'+data+'</a>';
}

function init_tables()
{
    $('#tcTable').dataTable({
        'ajax': 'api/tc/list?fmt=data-table',
        'columns': [
            {'data': 'logo', 'render': render_camp_logo, 'width': '100px'},
            {'data': 'name', 'render': render_camp_name},
            {'data': 'teaser'}
        ]
    });
    $('#artTable').dataTable({
        'ajax': 'api/art/list?fmt=data-table',
        'columns': [
            {'data': 'logo', 'render': render_art_logo, 'width': '100px'},
            {'data': 'name', 'render': render_art_name},
            {'data': 'teaser'}
        ]
    });
    $('#dmvTable').dataTable({
        'ajax': 'api/dmv/list?fmt=data-table',
        'columns': [
            {'data': 'logo', 'render': render_dmv_logo, 'width': '100px'},
            {'data': 'name', 'render': render_dmv_name},
            {'data': 'teaser'}
        ]
    });
    $('#eventTable').dataTable({
        'ajax': 'api/event/list?fmt=data-table',
        'columns': [
            {'data': 'logo', 'render': render_event_logo, 'width': '100px'},
            {'data': 'name', 'render': render_event_name},
            {'data': 'teaser'}
        ]
    });
}

$(init_tables);
