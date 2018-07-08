
function getEndpoint()
{
    return 'api/v1/'+$('#listTable').data('endpoint');
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
  var table = $('#listTable').DataTable();
  var row = table.row(this);
  var data = row.data();
  window.location = $('#listTable').data('viewpage')+'?id='+data['_id'];
}

function initPage()
{
    var table = $('#listTable').dataTable({
        'ajax': getEndpoint()+'?fmt=data-table&$select=_id,name,teaser',
        'order': [[ 1, 'desc' ]],
        'columns': [
          {'data': '_id', 'render': renderLogo},
          {'data': 'name'},
          {'data': 'teaser'}
        ],
        'deferRender': true
    });
    $('#listTable tbody').on('click', 'tr', rowClicked);
    $('#listTable tbody').css( 'cursor', 'pointer' );
}

$(initPage);
