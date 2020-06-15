function dateToHtml5Str(date) {
  var str = date.getFullYear()+'-';
  if(date.getMonth()+1 < 10) {
    str += '0';
  }
  str += date.getMonth()+1+'-';
  if(date.getDate() < 10) {
    str += '0';
  }
  str+= date.getDate();
  return str;
}

function dateToPhpStr(date) {
  var month = date.toDateString().substring(4, 7);
  return date.getDate()+' '+month+' '+date.getFullYear();
}

function getInputForVar(variable) {
  if(typeof(variable.value) === 'string') {
    if(variable.name === 'year') {
      return '<input type="number" id="'+variable.name+'" value="'+variable.value+'" onChange="updateVar(this);"></input>';
    }
    else {
      return '<input type="text" id="'+variable.name+'" value="'+variable.value+'" onChange="updateVar(this);"></input>';
    }
  }
  else if(typeof(variable.value) === 'object') {
    if(variable.value.start !== undefined) {
      var start = new Date(variable.value.start);
      var end = new Date(variable.value.end);
      var startStr = dateToHtml5Str(start);
      var endStr = dateToHtml5Str(end);
      var ret = 'Start Date: <input type="date" id="'+variable.name+'_start" value="'+startStr+'" onChange="updateVar(this);"></input><br/>';
      ret += 'End Date: <input type="date" id="'+variable.name+'_end" value="'+endStr+'" onChange="updateVar(this);"></input>';
      return ret;
    }
    else {
      console.log(variable);
      alert("Don't know how to process variable "+variable.name);
    }
  }
  return '';
}

function gotVariables(vars) {
  var table = $('#vars tbody');
  for(var i = 0; i < vars.length; i++) {
    var html = getInputForVar(vars[i]);
    table.append('<tr><td>'+vars[i].name+'</td><td>'+html+'</td></tr>');
  }
}

function updateDone(jqXHR) {
  if(jqXHR.status !== 200) {
    console.log(jqXHR);
    alert('Unable to update variable!');
  }
}

function updateVar(control) {
  var jc = $(control);
  var value = jc.val();
  var type = jc.attr('type');
  var name = jc.attr('id');
  if(type === 'date') {
    value = dateToPhpStr(new Date(value+'T00:00'));
  }
  if(name.indexOf('_') !== -1) {
    name = name.substring(0, name.indexOf('_')) +'/'+ name.substring(name.indexOf('_')+1)
  }
  $.ajax({
    url: '../api/v1/vars/'+name,
    type: 'patch',
    dataType: 'json',
    contentType: 'application/json',
    data: JSON.stringify(value),
    processData: false,
    complete: updateDone
  });
}

function initPage() {
  $.getJSON('../api/v1/vars', gotVariables);
}

$(initPage);
