/*global $,bootbox,bootstrap,Sentry*/
let _id = null;
let finalDone = false;

function show_tab(e) {
  e.preventDefault();
  if($(e.target).parent().filter('.disabled').length === 0) {
    $(this).tab('show');
  }
}

function tabChanged(e) {
  let tabIndex = $(e.target).parent().index();
  if(tabIndex == 0) {
    $('.previous').attr('class', 'previous disabled');
  } else {
    $('.previous').attr('class', 'previous');
  }
  let lastIndex = $(e.target).parent().siblings().last().index();
  if(tabIndex >= lastIndex) {
    if(finalDone) {
      $('.next').html('<a class="page-link" onclick="window.location=\'add.php\'" style="cursor: pointer;">Exit</a>');
    } else {
      $('.next').html('<a class="page-link" onclick="final_post(event)" style="cursor: pointer;">Save and Finish</a>');
    }
  } else {
    $('.next').html('<a class="page-link" href="#" onclick="next_tab(event)">Save and Continue <span aria-hidden="true">&rarr;</span></a>');
  }
}

function prev_tab() {
  let currentTab = document.querySelector('a.active.nav-link');
  if(currentTab === null) {
    alert('No active tab found. Please report this to the web team.');
    return;
  }
  let previous = currentTab.parentElement.previousElementSibling;
  if(previous === null) {
    // We are at the first tab
    return;
  }
  let prevTab = previous.children[0];
  if(prevTab === null) {
    alert('No previous tab found. Please report this to the web team.');
  }
  bootstrap.Tab.getOrCreateInstance(prevTab).show();
}

function validate_control_set(set) {
  let ret = true;
  for(let i = 0; i < set.length; i++) {
    let control = $(set[i]);
    let value = control.val();
    if(value == null || value.length == 0) {
      control.prop('class', 'form-control is-invalid');
      control.parents('tr').prop('class', 'is-invalid');
      if(control.parents('.panel-collapse').length > 0) {
        control.parents('.panel-collapse').collapse('show');
      }
      ret = false;
    } else {
      control.prop('class', 'form-control is-valid');
    }
  }
  return ret;
}

function pregQuote(str, delimiter) {
  return String(str).replace(new RegExp('[.\\\\+*?\\[\\^\\]$(){}=!<>|:\\' + (delimiter || '') + '-]', 'g'), '\\$&');
}

function nameCheckDone(data) {
  if(data.responseJSON !== undefined && data.responseJSON.length > 0) {
    $('#name').prop('class', 'form-group is-invalid'); 
    this.callback(false);
  } else {
    this.callback(this.ret);
  }
}

function validate_current(callback) {
  let ret = true;
  let required = $('div.tab-pane.active').find('[required]');
  if(required.length !== 0) {
    if(validate_control_set(required) === false) {
      ret = false;
    }
  }
  let special = $('div.tab-pane.active').find('[type=url]');
  if(special.length !== 0) {
    for(i = 0; i < special.length; i++) {
      let rawControl = special[i];
      let control = $(rawControl);
      if(rawControl.validity !== undefined && !rawControl.validity.valid) {
        if(rawControl.validationMessage !== undefined) {
          control.attr('title', rawControl.validationMessage);
        }
        control.prop('class', 'form-control is-invalid');
        if(control.parents('.panel-collapse').length > 0) {
          control.parents('.panel-collapse').collapse('show');
        }
        ret = false;
      } else {
        control.parents('.form-group').prop('class', 'form-control is-valid');
        control.removeAttr('title');
      }
    }
  }
  if(_id === null) {
    let name = $('#name').val();
    name = pregQuote(name);
    let obj = {};
    obj.callback = callback;
    obj.ret = ret;
    $.ajax({
      url: getListAllUrl()+'/Actions/Search',
      data: 'name=/^'+encodeURIComponent(name)+'/i',
      type: 'get',
      dataType: 'json',
      context: obj,
      complete: nameCheckDone
    });
  } else {
    callback(ret);
  }
}

function post_done(data) {
  if(data._id !== undefined) {
    _id = data._id; 
  } else if(data['$id'] !== undefined) {
    _id = data['$id'];
  } else if(data['$oid'] !== undefined) {
    _id = data['$oid'];
  } else {
    console.log(data);
  }
}

function finalPostDone(data) {
  if(data === true) {
    location.href = '/register/add.php';
  } else if(data['$id'] !== undefined) {
    location.href = '/register/add.php';
  } else if(data.message !== undefined) {
    alert('Error! '+data.message);
  } else {
    alert('Error! '+JSON.stringify(data));
  }
  console.log(data);
}

function getPageName() {
  let file, n;
  file = window.location.pathname;
  n = file.lastIndexOf('/');
  if(n >= 0) {
    file = file.substring(n + 1);
  }
  return file;
}

function getListAllUrl() {
  let url = null;
  let page = getPageName();
  if(page.startsWith('tc_')) {
    url = 'api/v1/camps';
  } else if(page.startsWith('art_')) {
    url = 'api/v1/art';
  } else if(page.startsWith('artCar_')) {
    url = 'api/v1/dmv';
  } else if(page.startsWith('event_')) {
    url = 'api/v1/event';
  }
  return url;
}

function getPostUrl() {
  let url = null;
  let page = getPageName();
  if(page.startsWith('tc_')) {
    if(_id == null) {
      url = 'api/v1/camps';
    } else {
      url = 'api/v1/camps/'+_id;
    }
  } else if(page.startsWith('art_')) {
    if(_id == null) {
      url = 'api/v1/art';
    } else {
      url = 'api/v1/art/'+_id;
    }
  } else if(page.startsWith('artCar_')) {
    if(_id == null) {
      url = 'api/v1/dmv';
    } else {
      url = 'api/v1/dmv/'+_id;
    }
  } else if(page.startsWith('event_')) {
    if(_id == null) {
      url = 'api/v1/event';
    } else {
      url = 'api/v1/event/'+_id;
    }
  }
  return url;
}

function postError(data) {
  if(data.responseJSON !== undefined) {
    data = data.responseJSON;
  }
  if(data.message !== undefined) {
    alert('Unable to save data because: '+data.message);
  } else if(data.status === 401) {
    alert('Unable to save data because your session has expired! Please log back in and retry.');
  } else {
    bootbox.alert('Unable to save data for unknown reason! Please send all the data below to the web team or relevant lead:<br/><pre>'+JSON.stringify(data)+'</pre>');
    console.log(data);
    Sentry.configureScope(function(scope) {
      scope.setExtra('server_data', data);
      Sentry.captureException(new Error('Failed to save registration data'));
    });
  }
}

function postData() {
  let data = form_data_to_obj();
  if(_id !== null) {
    data['_id'] = _id;
  }
  if(window.getAdditionalData !== undefined) {
    data = Object.assign(data, getAdditionalData());
  }
  if(window.filterData !== undefined) {
    data = filterData(data);
  }
  $.ajax({
    url: getPostUrl(),
    type: 'post',
    dataType: 'json',
    contentType: 'application/json',
    data: JSON.stringify(data),
    processData: false,
    success: post_done,
    error: postError
  });
}

function doFinalPost(cont) {
  if(cont) {
    let data = form_data_to_obj();
    if(window.getAdditionalData !== undefined) {
      data = Object.assign(data, getAdditionalData());
    }
    if(window.filterData !== undefined) {
      data = filterData(data);
      console.log(data);
    }
    data['_id'] = _id;
    data['final'] = true;
    $.ajax({
      url: getPostUrl(),
      type: 'post',
      dataType: 'json',
      contentType: 'application/json',
      data: JSON.stringify(data),
      processData: false,
      success: finalPostDone,
      error: postError
    });
  }
}

function reallyDoFinal(result) {
  if(result.length === 1 && result[0] === 'agree') {
    doFinalPost(true);
  } else {
    bootbox.alert('You must agree to check your email to continue!');
  }
}

function doFinalDialog(cont) {
  if(cont) {
    //bootbox.confirm("Are you sure you want to save? After clicking OK on this dialog you will not be able to edit the registration further.", reallyDoFinal);
    bootbox.prompt({
      title: 'I will check my email',
      message: '<p>By submitting this registration I understand that I may be contacted by Burning Flipside volunteers for clarification and with important information about my registration. I agree that I will check my email for this information and will respond promptly.</p>',
      inputType: 'checkbox',
      inputOptions: [{
        text: 'I agree',
        value: 'agree'
      }],
      callback: reallyDoFinal
    });
  } else {
    bootbox.alert('You are missing one or more required fields!');
  }
}

function doNextTab(cont) {
  if(cont) {
    let currentTab = document.querySelector('a.active.nav-link');
    if(currentTab === null) {
      alert('No active tab found. Please report this to the web team.');
      return;
    }
    let nextTab = currentTab.parentElement.nextElementSibling.children[0];
    if(nextTab === null) {
      alert('No next tab found. Please report this to the web team.');
    }
    bootstrap.Tab.getOrCreateInstance(nextTab).show();
    if(!finalDone) {
      postData();
    }
  }
}

function final_post(e) {
  e.preventDefault();
  validate_current(doFinalDialog);
  return false;
}

function next_tab(e) {
  if(!finalDone) {
    doNextTab(true);
  } else {
    validate_current(doNextTab);
  }
}

function tabControlChange() {
  let control = $(this);
  let tabId  = control.data('tabcontrol');
  if(control.is(':checked')) {
    $('#'+tabId).attr('class', '');
    $('#'+tabId+' a').attr('data-toggle', 'tab');
  } else {
    let others = $('[data-tabcontrol='+tabId+']:checked');
    if(others.length > 0) {
      return;
    }
    $('#'+tabId).attr('class', 'disabled');
    $('#'+tabId+' a').attr('data-toggle', '');
  }
}

function groupControlChange() {
  let control = $(this);
  let groupId = control.data('groupcontrol');
  let groupCtrl = $('#'+groupId).parent('.panel');
  if(control.is(':checked')) {
    groupCtrl.show();
    groupCtrl.find('[data-was-required]').attr('required', 'true');
  } else {
    groupCtrl.hide();
    groupCtrl.find('[required]').removeAttr('required').attr('data-was-required', 1);
  }
}

function questControlChange() {
  let control = $(this);
  let questId = control.data('questcontrol');
  let groupCtrl = $('#'+questId).parents('.form-group');
  if(control.is(':checked')) {
    groupCtrl.show();
    groupCtrl.find('[data-was-required]').attr('required', 'true');
  } else {
    groupCtrl.hide();
    groupCtrl.find('[required]').removeAttr('required').attr('data-was-required', 1);
  }
}

function copyTriggerChanged(e) {
  let control = e.data;
  let original = $('#'+control.data('copyfrom'));
  control.val(original.val());
}

function setupCopyControl() {
  let control = $(this);
  let trigger = control.data('copytrigger');
  let triggerControl = $(trigger);
  triggerControl.change(control, copyTriggerChanged);
}

function add_val_to_field(obj, fieldname, val) {
  let index = fieldname.indexOf('[]');
  if(index != -1) {
    fieldname = fieldname.substr(0, index);
    if(obj[fieldname] === undefined) {
      obj[fieldname] = [];
    }
    obj[fieldname].push(val);
  } else {
    obj[fieldname] = val;
  }
}

function resizeImg(img, element, imageType) {
  if(img.height <= 640 && img.width <= 640) {
    element.attr('src', img.src);
  } else {
    let canvas = document.createElement('canvas');
    let max = 640;
    let width = img.width;
    let height = img.height;

    if(width > height) {
      height *= max/width;
      width   = max;
    } else {
      width *= max/height;
      height = max;
    }
    canvas.width = width;
    canvas.height = height;
    canvas.getContext('2d').drawImage(img, 0, 0, width, height);
    element.attr('src', canvas.toDataURL(imageType));
  }
}

function handleFiles() {
  let files = $(this)[0].files;
  for(i = 0; i < files.length; i++) {
    let file = files[i];
    let imageType = /image.*/;
    if(!file.type.match(imageType)) {
      alert('Not an image');
      console.log(file);
      continue;
    }
    let image = new Image();
    let img = $(this).next('.obj');
    if(img.length === 0) {
      img = $('<img>', {'class': 'obj', 'style':'max-width: 200px; max-height: 200px;'});
    }
    let reader = new FileReader();
    reader.onloadend = function(){
      image.src=reader.result;
      resizeImg(image, img, imageType);
    };
    $(this).after(img);
    reader.readAsDataURL(file);
  }
}

function add_file_to_field(obj, fieldname, control) {
  let src = control.nextAll('.obj').attr('src');
  obj[fieldname] = src;
}

function form_data_to_obj() {
  let ret = {};
  let controls = $('.tab-content :input:not(.ignore)');
  for(i = 0; i < controls.length; i++) {
    let control = $(controls[i]);
    let name    = control.prop('name');
    if(name.indexOf('_') != -1) {
      let names = name.split('_');
      let obj = ret;
      for(j = 0; j < names.length - 1; j++) {
        if(obj[names[j]] === undefined) {
          obj[names[j]] = {};
        }
        obj = obj[names[j]];
      }
      if(control.attr('type') === 'file') {
        add_file_to_field(ret, name, control);
      } else if(control.attr('type') === 'checkbox') {
        add_val_to_field(obj, names[j], control.is(':checked'));
      } else {
        add_val_to_field(obj, names[j], control.val());
      }
    } else {
      if(control.attr('type') === 'file') {
        add_file_to_field(ret, name, control);
      } else if(control.attr('type') === 'checkbox') {
        add_val_to_field(ret, name, control.is(':checked'));
      } else {
        add_val_to_field(ret, name, control.val());
      }
    }
  }
  return ret;
}

function prior_ajax_done(data, prefix) {
  let first = false;
  if(prefix === undefined || prefix === 'success') {
    prefix = '';
    first = true;
  }
  for(let key in data) {
    if(key === '_id' || key === '') {
      continue;
    } else if(typeof(data[key]) === 'object') {
      prior_ajax_done(data[key], prefix+key+'_');
    } else if($("[id='"+prefix+key+"']").length > 0) {
      let control = $("[id='"+prefix+key+"']");
      if(control.filter('select').length > 0) {
        if(control.val() === data[key]) {
          continue;
        }
        control.val(data[key]);
      } else if(control.filter('[type=file]').length > 0) {
        if(data[key].length > 0) {
          let img = $('<img>', {'class':'obj', 'src': data[key], 'style':'max-width: 200px; max-height: 200px'});
          control.after(img);
        }
      } else if(control.filter('[type=checkbox]').length > 0) {
        if(data[key] === 'true' || data[key] === true) {
          control.click();
          control.attr('checked', 'true');
        }
      } else {
        control.val(data[key]);
      }
      if(data[key].length > 0) {
        let panelID = control.parents('.tab-pane').attr('id');
        let id = $("a[href='#"+panelID+"']").parent().attr('id');
        $('[data-tabcontrol='+id+']').prop('checked', 'true').change();
      }
    } else {
      //console.log("[id='"+prefix+key+"']");
    }
  }
  if(first) {
    let obj = null;
    let page = getPageName();
    if(page.startsWith('tc_')) {
      obj = data['camplead'];
    } else if(page.startsWith('art_')) {
      obj = data['artlead'];
    }
    if(obj !== null) {
      if(obj === undefined || obj.email === undefined || obj.email === '') {
        getUserData();
      }
    }
  }
}

function prior_ajax_error(data) {
  console.log(data);
  if(data.message !== undefined) {
    alert('Unable to load data because: '+data.message);
  } else {
    alert('Unable to load data for unknown reason!');
  }
}

function gotUserData(jqxhr) {
  let objName = null;
  let page = getPageName();
  if(page.startsWith('tc_')) {
    objName = 'camplead';
  }
  else if(page.startsWith('art_')) {
    objName = 'artlead';
  }
  if(objName !== null) {
    if(jqxhr.status !== 200) {
      alert('Unable to obtain logged in user! Try logging out and logging back in!');
      console.log(jqxhr);
      return;
    }
    let data = jqxhr.responseJSON;
    $('#'+objName+'_name').val(data.givenName+' '+data.sn);
    $('#'+objName+'_burnerName').val(data.displayName);
    $('#'+objName+'_email').val(data.mail);
    $('#'+objName+'_phone').val(data.mobile);
  }
}

function getUserData() {
  $.ajax({
    url: window.profilesUrl+'/api/v1/users/me',
    type: 'get',
    dataType: 'json',
    xhrFields: { withCredentials: true },
    complete: gotUserData});
}

function populate_prior_data() {
  if(_id !== null) {
    $.ajax({
      url: getPostUrl()+'?full=true',
      type: 'get',
      dataType: 'json',
      success: prior_ajax_done,
      error: prior_ajax_error
    });
  } else {
    getUserData();
  }
}

function toggleClassVisible(elem, classId) {
  var all = $('.'+classId);
  var invis = all.filter('.d-none');
  var vis = all.filter(':not(.d-none)');
  invis.removeClass('d-none');
  vis.addClass('d-none');
}

function toggleVisible(elem, elemId) {
  var all = $('#'+elemId);
  var invis = all.parent('.d-none');
  var vis = all.parent(':not(.d-none)');
  invis.removeClass('d-none');
  vis.addClass('d-none');
}

function wizard_init() {
  _id = getParameterByName('id');
  if(_id === null) {
    _id = getParameterByName('_id');
  }
  if($('[title]').tooltip !== undefined) {
    $('[title]').tooltip();
  }
  $('input[data-tabcontrol]').change(tabControlChange);
  $('input[data-groupcontrol]').change(groupControlChange);
  $('input[data-questcontrol]').change(questControlChange);
  $('input[type=file]').change(handleFiles);
  $('input[data-tabcontrol]').each(tabControlChange);
  $('input[data-groupcontrol]').each(groupControlChange);
  $('input[data-questcontrol]').each(questControlChange);
  $('input[data-copytrigger]').each(setupCopyControl);
  $('.previous').attr('class', 'previous disabled');
  $('a[data-bs-toggle="tab"]').on('shown.bs.tab', tabChanged);
  if(browser_supports_input_type('url')) {
    $('#site').attr('type', 'url');
  }
  var page = getPageName();
  if(page.startsWith('tc_') === false) {
    populate_prior_data();
  }
  var onepage = getParameterByName('onepage');
  if(onepage === 'true') {
    $('.tab-pane').addClass('active');
    $('#rootwizard .navbar').hide();
    $('.alert').hide();
    $('.panel-collapse').addClass('in');
    $('.embed-responsive').hide();
}
}

$(wizard_init);
