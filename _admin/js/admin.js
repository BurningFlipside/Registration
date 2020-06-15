var idIndex = -1;
var nameIndex = -1;

function getAPISource()
{
  return '../api/v1/'+$('#listTable').data('source');
}

function getAPICall()
{
  var finished = getParameterByName('finished');
  var filter = '';
  if(finished !== null)
  {
    filter = '&$filter=final eq true and year eq current';
  }
  return getAPISource()+'?no_logo=1&fmt=json-ss'+filter;
}

function getEditPage()
{
  return $('#listTable').data('editor');
}

function getRowForEvent(trigger)
{
  var tr = $(trigger).closest('tr');
  var table = tr.closest('table');
  var api = table.DataTable();
  return api.row(tr).data();
}

function getIdForEvent(trigger)
{
  var row = getRowForEvent(trigger);
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

function getNameForEvent(trigger)
{
  var row = getRowForEvent(trigger);
  return row[2];
}

function doneDelete(jqXHR)
{
  if(jqXHR.status < 400) {
    bootbox.alert("Deleted!", function(){location.reload();});
  }
  else {
    console.log(jqXHR);
    bootbox.alert("Unable to delete registraion!");
  }
}

function deleteObject()
{
  var _id = getIdForEvent(this);
  var name = getNameForEvent(this);
  bootbox.confirm("Are you sure you want to delete this registration "+name+"?", function(result){
    if(result) {
      $.ajax({
        type: 'DELETE',
          url: getAPISource()+'/'+_id,
          complete: doneDelete
      });
    }
  });
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
  if(nameIndex === 1)
  {
    table.colReorder.move(idIndex, 1);
  }
  else
  {
    table.colReorder.move(idIndex, 1, false, false);
    table.colReorder.move(nameIndex, 2);
  }
  table.order([2, 'desc']);
  table.draw(false);
  $('a.toggle-vis').on('click', function (e){
    e.preventDefault();
    // Get the column API object
    var column = table.column(":contains("+$(this).attr('data-column')+")");
    // Toggle the visibility
    column.visible( ! column.visible() );
  });
  $('#listTable tbody').on('click', 'button[name="edit"]', edit_obj);
  $('#listTable tbody').on('click', 'button[name="del"]', deleteObject);
  $('#listTable tbody').on('click', 'button[name="unlock"]', unlockObject);
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
