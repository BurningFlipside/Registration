var idIndex = -1;
var nameIndex = -1;

function getAPISource() {
  return '../api/v1/'+$('#dataGrid').data('source');
}

function getAPICall() {
  var finished = getParameterByName('finished');
  var filter = '';
  if(finished !== null)
  {
    filter = '&$filter=final eq true and year eq current';
  }
  return getAPISource()+'?no_logo=1'+filter;
}

function getEditPage() {
  return $('#dataGrid').data('editor');
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

function deleteData(e, cell) {
  var data = cell.getRow().getData();
  bootbox.confirm("Are you sure you want to delete this registration "+data.name+"?", function(result){
    if(result) {
      $.ajax({
        type: 'DELETE',
          url: getAPISource()+'/'+data['_id'],
          complete: doneDelete
      });
    }
  });
}

function deleteIcon(cell, formatterParams, onRendered) {
  return "<i class='fa fa-trash'></i>";
}

function editLink(cell, formatterParams, onRendered) {
  var data = cell.getRow().getData();
  return '<a href="'+getEditPage()+'?is_admin=true&id='+data['_id']+'">'+data['name']+'</a>';
}

function calculateSquareFootage(cell, formatterParams, onRendered) {
  var data = cell.getRow().getData();
  var squareFootage = 0;
  for(var i = 0; i < data.structs.length; i++) {
    if(data.structs[i] === null) {
      continue;
    }
    squareFootage += data.structs[i].Width*data.structs[i].Length;
  }
  return ''+squareFootage;
}

function toggleLead(e, column) {
  var children = column.getSubColumns();
  children.shift();
  children.forEach(function(child) {
    child.toggle();
  });
}

var locationParams = {
  values: {
    'any': 'Any',
    'borderlands': 'Borderlands',
    'corral': 'Corral',
    'effigy': 'Effigy Loop',
    'mid': 'Mid-City',
    'ownCamp': 'Own Camp'
  }
}

var renderers = [
  {field: 'campLead.just.me', visible: false},
  {field: 'artlead.just.me', visible: false},
  {field: 'year', visible: false},
  {field: 'registrars', visible: false},
  {field: 'camp.reg.prev', formatter: 'tickCross', editor: 'tickCross', title: 'Previous Year'},
  {field: 'cityplanning.notes', formatter: 'textarea', editor: 'textarea', title: 'City Planning Notes'},
  {field: 'description', formatter: 'textarea', editor: 'textarea', title: 'Description'},
  {field: 'earlyArrival.bool', formatter: 'tickCross', editor: 'tickCross', title: 'Requested Early Arrival'},
  {field: 'earlyArrival.desc', formatter: 'textarea', editor: 'textarea', title: 'Early Arrival Info'},
  {field: 'has.sound', formatter: 'tickCross', editor: 'tickCross', title: 'Amplified Sound'},
  {field: 'has.heavy', formatter: 'tickCross', editor: 'tickCross', title: 'Wants Heavy Equipment'},
  {field: 'has.burnable', formatter: 'tickCross', editor: 'tickCross', title: 'Burnable Art'},
  {field: 'has.food', formatter: 'tickCross', editor: 'tickCross', title: 'Food'},
  {field: 'has.beverage', formatter: 'tickCross', editor: 'tickCross', title: 'Beverages'},
  {field: 'has.music', formatter: 'tickCross', editor: 'tickCross', title: 'Music'},
  {field: 'has.sex', formatter: 'tickCross', editor: 'tickCross', title: 'Sex Camp'},
  {field: 'has.spa', formatter: 'tickCross', editor: 'tickCross', title: 'Spa Camp'},
  {field: 'has.kids', formatter: 'tickCross', editor: 'tickCross', title: 'Kid Camp'},
  {field: 'num.campers', editor: 'input', title: 'Number of Campers'},
  {field: 'placement.pref1', title: 'Placement Pref 1', editor: 'select', editorParams: locationParams, formatter: 'lookup', formatterParams: locationParams.values},
  {field: 'placement.pref2', title: 'Placement Pref 2', editor: 'select', editorParams: locationParams, formatter: 'lookup', formatterParams: locationParams.values},
  {field: 'placement.pref3', title: 'Placement Pref 3', editor: 'select', editorParams: locationParams, formatter: 'lookup', formatterParams: locationParams.values},
  {field: 'placement.desc', formatter: 'textarea', editor: 'textarea', title: 'Placement Info'},
  {field: 'placement.special', formatter: 'textarea', editor: 'textarea', title: 'Placement Special Requests'},
  {field: 'placement.tents', editor: 'number', title: 'Number of Tents'},
  {field: 'site', formatter: 'link', title: 'Website'},
];

var groups = {
  'campLead': {
    title:'Camp Lead',
    headerClick: toggleLead,
    columns:[
     {title: "Name", field: "campLead.name"},
     {title: 'Burner Name', field: 'campLead.burnerName', visible: false},
     {title: 'Email', field: 'campLead.email', visible: false, formatter: 'link', formatterParams:{urlPrefix:"mailto://"}},
     {title: 'Phone', field: 'campLead.phone', visible: false, formatter: 'link', formatterParams:{urlPrefix:"tel:"}},
     {title: 'Can Text', field: 'campLead.sms', visible: false, formatter: 'tickCross', editor:'tickCross'}
    ]
  },
  'cleanupLead': {
    title: 'Cleanup Lead',
    headerClick: toggleLead,
    columns:[
     {title: "Name", field: "cleanupLead.name"},
     {title: 'Burner Name', field: 'cleanupLead.burnerName', visible: false},
     {title: 'Email', field: 'cleanupLead.email', visible: false, formatter: 'link', formatterParams:{urlPrefix:"mailto://"}},
     {title: 'Phone', field: 'cleanupLead.phone', visible: false, formatter: 'link', formatterParams:{urlPrefix:"tel:"}},
     {title: 'Can Text', field: 'cleanupLead.sms', visible: false, formatter: 'tickCross', editor:'tickCross'},
     {title: 'Plan', field: 'cleanupLead.plan', visible: false, formatter: 'textarea', editor: 'textarea'}
    ]
  },
  'safetyLead': {
    title: 'Safety Lead',
    headerClick: toggleLead,
    columns:[
     {title: "Name", field: "safetyLead.name"},
     {title: 'Burner Name', field: 'safetyLead.burnerName', visible: false},
     {title: 'Email', field: 'safetyLead.email', visible: false, formatter: 'link', formatterParams:{urlPrefix:"mailto://"}},
     {title: 'Phone', field: 'safetyLead.phone', visible: false, formatter: 'link', formatterParams:{urlPrefix:"tel:"}},
     {title: 'Can Text', field: 'safetyLead.sms', visible: false, formatter: 'tickCross', editor:'tickCross'},
     {title: 'Plan', field: 'safetyLead.plan', visible: false, formatter: 'textarea', editor: 'textarea'}
    ]
  },
  'volunteering': {
    title: 'Volunteer Contact',
    headerClick: toggleLead,
    columns:[
     {title: "Name", field: "volunteering.name"},
     {title: 'Burner Name', field: 'volunteering.burnerName', visible: false},
     {title: 'Email', field: 'volunteering.email', visible: false, formatter: 'link', formatterParams:{urlPrefix:"mailto://"}},
     {title: 'Phone', field: 'volunteering.phone', visible: false, formatter: 'link', formatterParams:{urlPrefix:"tel:"}},
     {title: 'Can Text', field: 'volunteering.sms', visible: false, formatter: 'tickCross', editor:'tickCross'},
     {title: 'Plan', field: 'volunteering.plan', visible: false, formatter: 'textarea', editor: 'textarea'}
    ]
  },
  'sound': {
    title: 'Sound',
    headerClick: toggleLead,
    columns:[
     {title: 'Description', field: 'sound.desc'},
     {title: 'From', field: 'sound.from', visible: false},
     {title: 'To', field: 'sound.to', visible: false}
    ]
  },
  'structs': {
    title: 'Structures',
    headerClick: toggleLead,
    columns:[
     {title: 'Types', field: 'structs.type'},
     {title: 'Widths', field: 'structs.width', visible: false},
     {title: 'Lengths', field: 'structs.length', visible: false},
     {title: 'Heights', field: 'structs.height', visible: false},
     {title: 'Descriptions', field: 'structs.desc', visible: false}
    ]
  },
  'artlead': {
    title:'Project Lead',
    headerClick: toggleLead,
    columns:[
     {title: "Name", field: "artlead.name"},
     {title: 'Burner Name', field: 'artlead.burnerName', visible: false},
     {title: 'Email', field: 'artlead.email', visible: false, formatter: 'link', formatterParams:{urlPrefix:"mailto://"}},
     {title: 'Phone', field: 'artlead.phone', visible: false, formatter: 'link', formatterParams:{urlPrefix:"tel:"}},
     {title: 'Can Text', field: 'artlead.sms', visible: false, formatter: 'tickCross', editor:'tickCross'},
     {title: 'Camp', field: 'artLead.camp', visible: false}
    ]
  },
  'artLead': null,
  'firelead': {
    title:'Fire Lead',
    headerClick: toggleLead,
    columns:[
     {title: "Name", field: "firelead.name"},
     {title: 'Burner Name', field: 'firelead.burnerName', visible: false},
     {title: 'Email', field: 'firelead.email', visible: false, formatter: 'link', formatterParams:{urlPrefix:"mailto://"}},
     {title: 'Phone', field: 'firelead.phone', visible: false, formatter: 'link', formatterParams:{urlPrefix:"tel:"}},
     {title: 'Can Text', field: 'firelead.sms', visible: false, formatter: 'tickCross', editor:'tickCross'},
     {title: 'Camp', field: 'firelead.camp', visible: false}
    ]
  },
  'soundlead': {
    title:'Sound Lead',
    headerClick: toggleLead,
    columns:[
     {title: "Name", field: "soundlead.name"},
     {title: 'Burner Name', field: 'soundlead.burnerName', visible: false},
     {title: 'Email', field: 'soundlead.email', visible: false, formatter: 'link', formatterParams:{urlPrefix:"mailto://"}},
     {title: 'Phone', field: 'soundlead.phone', visible: false, formatter: 'link', formatterParams:{urlPrefix:"tel:"}},
     {title: 'Can Text', field: 'soundlead.sms', visible: false, formatter: 'tickCross', editor:'tickCross'},
     {title: 'Camp', field: 'soundlead.camp', visible: false}
    ]
  },
  'fire': {
    title:'Fire Info',
    headerClick: toggleLead,
    columns:[
     {title: 'Has Fire', field: 'fire.hasFlameEffects', formatter: 'tickCross', editor:'tickCross'},
     {title: 'Info', field: 'fire.flameEffects', visible: false, formatter: 'textarea', editor: 'textarea'},
     {title: 'Burn Day', field: 'fire.burnDay', visible: false, editor: 'input'},
     {title: 'Burn Plan', field: 'fire.burnPlan', visible: false, formatter: 'textarea', editor: 'textarea'},
     {title: 'Cleanup Plan', field: 'fire.cleanupPlan', visible: false, formatter: 'textarea', editor:'textarea'}
    ]
  }
};

function valueChanged(value, field, id) {
  console.log(getAPISource()+'/'+id);
  var propParts = field.split('.');
  var obj = {};
  var current = obj;
  for(var i = 0; i < propParts.length-1; i++) {
    current = current[propParts[i]] = {};
  }
  current[propParts[propParts.length-1]] = value;
  console.log(obj);
}

function dataChanged(cell) {
  console.log(cell);
  valueChanged(cell.getValue(), cell.getColumn().getField(), cell.getRow().getData()['_id']);
}

function dataObtained(data) {
  if(data.length === 0) return;
  var options = {
    height: 1000,
    columns: this,
    data: data,
    cellEdited: dataChanged,
  };
  var table = new Tabulator("#dataGrid", options);
  table.setSort('name', 'asc');
  console.log(table);
}

function columnsObtained(data) {
  if(data.length === 0) {
    return;
  }
  var columns = [];
  columns.push({formatter: deleteIcon, width:40, align:"center", cellClick: deleteData});
  columns.push({field: 'name', title: 'Name', formatter: editLink});
  columns.push({formatter: calculateSquareFootage, title: 'Square Footage'});
  for(var_name in data[0]) {
    if(var_name === '' || var_name === 'name' || var_name === '_id') {
      continue;
    }
    if(var_name === 'safetylead.camp') {
      for(var i = 0; i < columns.length; i++) {
        if(columns[i].title === 'Safety Lead') {
          columns[i].columns.push({title: 'Camp', field: 'safetylead.camp', visible: false});
          break;
        }
      }
      continue;
    }
    var part = var_name.split('.')[0];
    if(groups[part] !== undefined) {
      if(groups[part] !== null) {
        columns.push(groups[part]);
      }
      groups[part] = null;
      continue;
    }
    var col = {};
    var found = false;
    for(var i = 0; i < renderers.length; i++) {
      if(renderers[i].field === var_name) {
        found=true;
        col = renderers[i];
      }
    }
    if(found === false) {
      col.title = var_name;
      col.field = var_name;
      col.editor = 'input';
    }
    columns.push(col);
  }
  $.ajax({
    url: getAPICall(),
    success: dataObtained,
    context: columns
  });
}

function changeDLType()
{
  var format = $('#dlFormat').val();
  var links = $('.dl_link').each(function(){
    this.href = this.href.replace(/(fmt=)[^\&]+/, '$1'+format);
  });
}

function getColumns() {
  $.ajax({
    url: getAPISource()+'?no_logo=1&$format=json-ss&$top=1',
    success: columnsObtained
  });
}

function initPage() {
  getColumns();
}

$(initPage);
