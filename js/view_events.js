function getEndpoint()
{
  return 'api/v1/event';
}

function renderLogo(data, type, row)
{
    return '<img src="'+getEndpoint()+'/'+data+'/logo"/ onerror="nukeImage(this)" style="max-width: 100px; max-height: 100px;">';
}

function nukeImage(img)
{
  delete img.onerror;
  jQuery(img).hide();
}

function rowClicked()
{
  var table = $(this).closest('table');
  table = table.DataTable();
  var row = table.row(this);
  var data = row.data();
  window.location = 'view_event.php?id='+data['_id'];
}

function initPage()
{
    $('#ThursdayTable').dataTable({
        'ajax': getEndpoint()+'?$filter=Thursday eq true and year eq current&fmt=data-table&$select=_id,name,teaser',
        'order': [[ 1, 'desc' ]],
        'columns': [
          {'data': '_id', 'render': renderLogo},
          {'data': 'name'},
          {'data': 'teaser'}
        ],
        'deferRender': true
    });
    $('#FridayTable').dataTable({
        'ajax': getEndpoint()+'?$filter=Friday eq true and year eq current&fmt=data-table&$select=_id,name,teaser',
        'order': [[ 1, 'desc' ]],
        'columns': [
          {'data': '_id', 'render': renderLogo},
          {'data': 'name'},
          {'data': 'teaser'}
        ],
        'deferRender': true
    });
    $('#SaturdayTable').dataTable({
        'ajax': getEndpoint()+'?$filter=Saturday eq true and year eq current&fmt=data-table&$select=_id,name,teaser',
        'order': [[ 1, 'desc' ]],
        'columns': [
          {'data': '_id', 'render': renderLogo},
          {'data': 'name'},
          {'data': 'teaser'}
        ],
        'deferRender': true
    });
    $('#SundayTable').dataTable({
        'ajax': getEndpoint()+'?$filter=Sunday eq true and year eq current&fmt=data-table&$select=_id,name,teaser',
        'order': [[ 1, 'desc' ]],
        'columns': [
          {'data': '_id', 'render': renderLogo},
          {'data': 'name'},
          {'data': 'teaser'}
        ],
        'deferRender': true
    });
    $('#MondayTable').dataTable({
        'ajax': getEndpoint()+'?$filter=Monday eq true and year eq current&fmt=data-table&$select=_id,name,teaser',
        'order': [[ 1, 'desc' ]],
        'columns': [
          {'data': '_id', 'render': renderLogo},
          {'data': 'name'},
          {'data': 'teaser'}
        ],
        'deferRender': true
    });
    $('table tbody').on('click', 'tr', rowClicked);
    $('table tbody').css( 'cursor', 'pointer' );
}

$(initPage);
