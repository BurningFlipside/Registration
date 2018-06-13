function getAPICall()
{
  var finished = getParameterByName('finished');
  var filter = '';
  if(finished !== null)
  {
    filter = '&$filter=final eq true and year eq current';
  }
  return '../api/v1/'+$('#listTable').data('source')+'?no_logo=1&fmt=json-ss'+filter;
}

function getEditPage()
{
  return $('#listTable').data('editor');
}

function renderName(data, type, row)
{
  return '<a href="'+getEditPage()+'?id='+row[1]+'&is_admin=true">'+data+'</a>';
}

var renderers = [
  {'name': renderName}
];

function dataObtained(data)
{
  if(data.length === 0) return;
  var columns = [{
    'data': null,
    'defaultContent': '<button name="del"><span class="fa fa-remove"></span></button> <button name="unlock"><span class="fa fa-unlock"></span></button>'
  }];
  var toggles = $('#coltoggle');
  var idIndex = -1;
  var nameIndex = -1;
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
    if(col.title === 'name')
    {
      nameIndex = len-1;
    }
    else if(col.title === '_id')
    {
      idIndex = len-1;
    }
    toggles.append('<a class="toggle-vis" data-column="'+var_name+'">'+var_name+'</a> | ');
  }
  $.fn.dataTable.ext.errMode = 'none';
  $('#listTable').dataTable({
    'colReorder': true,
    'data': [],
    'columns': columns
  });
  var table = $('#listTable').DataTable();
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
  table.colReorder.move(idIndex, 1);
  table.colReorder.move(nameIndex, 2);
  table.order([2, 'desc']);
  table.draw(false);
  $('a.toggle-vis').on('click', function (e){
    e.preventDefault();
    // Get the column API object
    var column = table.column(":contains("+$(this).attr('data-column')+")");
    // Toggle the visibility
    column.visible( ! column.visible() );
  });
}

function changeDLType()
{
  var format = $('#dlFormat').val();
  var links = $('.dl_link').each(function(){
    this.href = this.href.replace(/(fmt=)[^\&]+/, '$1'+format);
  });
} 

function initPage()
{
  $.ajax({
    url: getAPICall(),
    success: dataObtained
  });
}

$(initPage);
