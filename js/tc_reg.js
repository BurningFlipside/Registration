function user_ajax_done(jqXHR)
{
    if(jqXHR.responseJSON !== undefined)
    {
      let data = jqXHR.responseJSON;
      $('#campLead_name').val(data.givenName+' '+data.sn);
      $('#campLead_burnerName').val(data.displayName);
      $('#campLead_email').val(data.mail);
      $('#campLead_phone').val(data.mobile);
    }
    else
    {
      alert('There was a problem attempting to get your registered email. Please try again and if it still fails try a different browser.');
      console.log(jqXHR);
    }
}

function setControl(key, value) {
  var control = $('#'+key);
  if(control.length === 0) {
    console.log('Cannot find control for '+key);
    return;
  }
  if(control.filter('select').length > 0) {
    if(control.val() === value) {
      return;
    }
    control.val(value);
  }
  else if(control.filter('[type=file]').length > 0) {
    if(value.length > 0) {
      var img = $('<img>', {'class':'obj', 'src': value, 'style':'max-width: 200px; max-height: 200px'});
      control.after(img);
    }
  }
  else if(control.filter('[type=checkbox]').length > 0) {
    if(value === 'true' || value === true) {
      control.click();
      control.attr('checked', 'true');
    }
  }
  else {
    control.val(value);
  }
  if(value.length > 0) {
    var panelID = control.parents('.tab-pane').attr('id');
    var id = $("a[href='#"+panelID+"']").parent().attr('id');
    $('[data-tabcontrol='+id+']').prop('checked', 'true').change();
  }
}

function addChild(prefix, data) {
  for(var key in data) {
    if(typeof(data[key]) === 'object') {
      addChild(prefix+key+'_', data[key]);
    }
    else {
      setControl(prefix+key, data[key]);
    }
  }
}

function tcAjaxDone(jqXHR) {
  var data = jqXHR.responseJSON;
  if(jqXHR.status !== 200) {
    if(data.message !== undefined) {
      alert("Unable to load data because: "+data.message);
    }
    else {
      alert("Unable to load data for unknown reason!");
    }
    console.log(jqXHR);
    return;
  }
  for(var key in data) {
    if(key === '_id' || key === '') {
      continue;
    }
    else if(key === 'structs') {
      addExistingStructsToTable(data[key]);
    }
    else {
      if(typeof(data[key]) === 'object') {
        addChild(key+'_', data[key]);
      }
      else {
        setControl(key, data[key]);
      }
    }
  }
}

function tc_ajax_done(data, prefix)
{
    if(prefix === undefined || prefix === 'success')
    {
        prefix = '';
    }
    for(var key in data)
    {
        if(typeof(data[key]) === 'object')
        {
            tc_ajax_done(data[key], prefix+key+'_');
        }
        else if($('#'+prefix+key).length > 0)
        {
            var control = $('#'+prefix+key);
            if(control.filter('select').length > 0)
            {
                if(control.val() === data[key])
                {
                     continue;
                }
                control.val(data[key]);
            }
            else if(control.filter('[type=file]').length > 0)
            {
                if(data[key].length > 0)
                {
                    var img = $('<img>', {'class':'obj', 'src': data[key], 'style':'max-width: 200px; max-height: 200px'});
                    control.after(img);
                }
            }
            else if(control.filter('[type=checkbox]').length > 0)
            {
                if(data[key] === 'true' ||  data[key] === true)
                {
                    control.click();
                    control.attr('checked', 'true');
                }
            }
            else
            {
                control.val(data[key]);
            }
            if(data[key].length > 0)
            {
                var panelID = control.parents('.tab-pane').attr('id');
                var id = $("a[href='#"+panelID+"']").parent().attr('id');
                $('[data-tabcontrol='+id+']').prop('checked', 'true').change();
            }
        }
        else
        {
            //console.log(prefix+key);
            //console.log(data[key]);
        }
    }
}

function deleteStruct(control) {
  var tr = $(control).closest('tr');
  var obj = tr.data('structure');
  if(obj.Type === 'tent') {
    var val = $('#placement_tents').val();
    val = parseInt(val) - 1;
    if(val < 0) {
      val = 0;
    }
    $('#placement_tents').val(val);
  }
  tr.remove();
}

function popData() {
  var id = getParameterByName('_id');
  if(id === null) {
    id = getParameterByName('id');
  }
  if(id !== null) {
    $.ajax({
      url: 'api/v1/camps/'+id+'?full=true',
      type: 'get',
      dataType: 'json',
      complete: tcAjaxDone
    });
  }
  else {
    if(browser_supports_cors()) {
      $.ajax({
        url: window.profilesUrl+'/api/v1/users/me',
        type: 'get',
        dataType: 'json',
        xhrFields: { withCredentials: true },
        complete: user_ajax_done});
    }
    else {
      add_notification($('#content'), 'Your browser is out of date. Due to this some data may not be set automatically. Please make sure it is complete');
    }
  }
}

function shouldShow(opt, structClass) {
  if(structClass === 'living') {
    switch(opt) {
      case 'rv':
      case 'popup':
      case 'trailer':
      case 'car':
      case 'tent':
      case 'bigtent':
        return true;
      default:
        return false;
    }
  }
  else if(structClass === 'art') {
    switch(opt) {
      case 'art':
      case 'pyroart':
      case 'artcar':
        return true;
      default:
        return false;
    }
  }
  else if(structClass === 'infrastructure') {
    switch(opt) {
      case 'dome':
      case 'lounge':
      case 'bar':
      case 'stage':
        return true;
      default:
        return false;
    }
  }
}

function changeStructClass() {
  var val = $('#structClass').val();
  var opts = $('#structType option');
  for(var i = 0; i < opts.length; i++) {
    if(shouldShow(opts[i].value, val)) {
      $(opts[i]).show().removeAttr('disabled');
    }
    else {
      $(opts[i]).hide().attr('disabled', true);
    }
  }
  var style = $('#structType option:selected').attr('style');
  if(style !== '') {
    var newVal = $('#structType option:not([style*=none])')[0].value;
    $('#structType').val(newVal);
    changeStructType();
  }
  $('.classCond').addClass('d-none');
  $('.'+val).removeClass('d-none');
}

function changeStructType() {
  var val = $('#structType').val();
  $('[id|=alert]').hide();
  $('#alert-'+val).show();
  $('.typeCond').addClass('d-none');
  $('.'+val).removeClass('d-none');
  if(val === 'car' || val === 'rv' || val === 'popup' || val === 'trailer') {
    $('.vehicle').removeClass('d-none');
  }
  else {
    $('.vehicle').addClass('d-none');
  }
  if(val === 'tent') {
    $('#structLength').val(10).attr('disabled', true);
    $('#structWidth').val(10).attr('disabled', true);
    $('#structHeight').val(8).attr('disabled', true);
  }
  else {
    $('#structLength').val('').removeAttr('disabled');
    $('#structWidth').val('').removeAttr('disabled');
    $('#structHeight').val('').removeAttr('disabled');
  }
}

function addStruct(e) {
  var tbody = $('#structs_table tbody');
  var row = $('<tr class="structRow"/>');
  var cell = $('<td><button type="button" class="btn btn-link" onClick="deleteStruct(this)"><i class="fas fa-trash-alt"></i></button></td>');
  row.append(cell);
  cell = $('<td>'+$('#structType option:selected')[0].label+'</td>');
  row.append(cell);
  cell = $('<td>'+e.structWidth+'x'+e.structLength+'</td>');
  row.append(cell);
  cell = $('<td>'+e.structHeight+'</td>');
  row.append(cell);
  var content = '';
  if(e.structFrontage) {
    content += '<i class="fas fa-archway" title="Camp Frontage"></i>';
  }
  if(e.structLit) {
    content += '<i class="fas fa-lightbulb" title="Lit"></i>';
  }
  if(e.structFire) {
    content += '<i class="fas fa-fire" title="Burnable/Fire Art"></i>';
  }
  if(e.structHeavy) {
    content += '<i class="fas fa-tractor" title="Needs Heavy Equipment"></i>';
  }
  if(e.structWeigth === 'heavy') {
    content += '<i class="fas fa-weight-hanging" title="&gt; 2500lbs"></i>';
  }
  cell = $('<td>'+content+'</td>');
  row.append(cell);
  var obj = {};
  obj.Type = e.structType;
  obj.Width = Number(e.structWidth);
  obj.Length = Number(e.structLength);
  obj.Height = Number(e.structHeight);
  obj.Weigth = e.structWeigth;
  obj.Frontage = e.structFrontage;
  obj.Lit = e.structLit;
  obj.Fire = e.structFire;
  obj.HeavyEquipment = e.structHeavy;
  row.data('structure', obj);
  var count = parseInt(e.structCount);
  tbody.append(row);
  for(var i = 1; i < count; i++) {
    tbody.append(row.clone());
  }
  if(obj.Type === 'tent') {
    var val = $('#placement_tents').val();
    val = parseInt(val)+count;
    $('#placement_tents').val(val);
  }
  $('#structureWizard').modal('hide');
  resetWizard($('#structureWizard'));
}

function addExistingStructsToTable(structs) {
  var tbody = $('#structs_table tbody');
  for(var i = 0; i < structs.length; i++) {
    var row = $('<tr class="structRow"/>');
    var cell = $('<td><button type="button" class="btn btn-link" onClick="deleteStruct(this)"><i class="fas fa-trash-alt"></i></button></td>');
    row.append(cell);
    cell = $('<td>'+$('#structType option[value='+structs[i].Type+']')[0].label+'</td>');
    row.append(cell);
    cell = $('<td>'+structs[i].Width+'x'+structs[i].Length+'</td>');
    row.append(cell);
    cell = $('<td>'+structs[i].Height+'</td>');
    row.append(cell);
    var content = '';
    if(structs[i].Frontage) {
      content += '<i class="fas fa-archway" title="Camp Frontage"></i>';
    }
    if(structs[i].Lit) {
      content += '<i class="fas fa-lightbulb" title="Lit"></i>';
    }
    if(structs[i].Fire) {
      content += '<i class="fas fa-fire" title="Burnable/Fire Art"></i>';
    }
    if(structs[i].HeavyEquipment) {
      content += '<i class="fas fa-tractor" title="Needs Heavy Equipment"></i>';
    }
    if(structs[i].Weigth === 'heavy') {
      content += '<i class="fas fa-weight-hanging" title="&gt; 2500lbs"></i>';
    }
    cell = $('<td>'+content+'</td>');
    row.append(cell);
    row.data('structure', structs[i]);
    tbody.append(row);
  }
}

function getAdditionalData() {
  var obj = {structs: []};
  var structs = $('.structRow');
  for(var i = 0; i < structs.length; i++) {
    obj.structs.push($(structs[i]).data('structure'));
  }
  console.log(structs.length);
  console.log(obj);
  return obj;
}

function filterData(data) {
  delete data.structClass;
  delete data.structType;
  delete data.structLength;
  delete data.structWidth;
  delete data.structHeight;
  delete data.structWeight;
  delete data.structFrontage;
  delete data.structLit;
  delete data.structFire;
  delete data.structHeavy;
  delete data.structCount;
  return data;
}

function tcWizardInit() {
  popData();
}

$(tcWizardInit);
