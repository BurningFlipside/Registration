function render_camp_logo(data, type, row, meta)
{
    return '<a href="view_tc.php?id='+row._id+'"><img src="'+data+'" style="max-width:100px; max-height:100px;"/></a>';
}

function render_camp_name(data, type, row, meta)
{
    return '<a href="view_tc.php?id='+row._id+'">'+data+'</a>';
}

function render_art_logo(data, type, row, meta)
{
    return '<a href="view_art.php?id='+row._id+'"><img src="'+data+'" style="max-width:100px; max-height:100px;"/></a>';
}

function render_art_name(data, type, row, meta)
{
    return '<a href="view_art.php?id='+row._id+'">'+data+'</a>';
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
}

$(init_tables);
