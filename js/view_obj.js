/*global $, getParameterByName, Sentry*/
/*exported contactLead*/
function validateContactDialog() {
  var ret = true;
  var required = $('#contact_dialog :input:not(:button)');
  for(let requiredControl of required) {
    var control = $(requiredControl);
    var value = control.val();
    if(value === null || value.length === 0) {
      control.prop('class', 'form-control is-invalid');
      ret = false;
    } else {
      control.prop('class', 'form-control is-valid');
    }
  }
  return ret;
}

function contactDone(jqXHR) {
  console.log(jqXHR);
}

function getPageName() {
  var file, n;
  file = window.location.pathname;
  n = file.lastIndexOf('/');
  if(n >= 0) {
    file = file.substring(n + 1);
  }
  return file;
}

function getObjType() {
  var name = getPageName();
  name = name.substring(5);
  name = name.substring(0, name.length - 4);
  if(name === 'tc') {
    return 'camps';
  }
  return name;
}

function contactLead() {
  if(validateContactDialog()) {
    $.ajax({
      url: 'api/v1/'+getObjType()+'/'+getParameterByName('id')+'/contact',
      type: 'post',
      data: $('#email_text').add('#subject').serialize(),
      dataType: 'json',
      complete: contactDone});
    $('#contact_dialog').modal('hide');
  }
}

function objDone(jqXHR) {
  if(jqXHR.status === 401) {
    //User is not logged in
    return;
  }
  if(jqXHR.status !== 200) {
    Sentry.withScope(scope => {
      scope.setExtra('jqXHR', jqXHR);
      scope.setLevel('info');
      Sentry.captureMessage('Unable to obtain registration data '+jqXHR.status);
    });
    alert('Unable to obtain data!');
    return;
  }
  let data = jqXHR.responseJSON;
  for(var propName in data) {
    switch(propName) {
      default:
        var control = $('#'+propName);
        if(control.length >= 1) {
          if(control[0].type === 'checkbox') {
            if(data[`${propName}`]) {
              control[0].checked = true;
            }
          } else {
            control.val(data[`${propName}`]);
          }
        }
        break;
      case 'logo':
        if(data[`${propName}`].length > 0) {
          $('#logo').html('<img src="'+data[`${propName}`]+'" style="max-width:200px; max-height:200px;"/>');
        } else {
          $('#logo').html('None Provided');
        }
        break;
      case 'site':
        if(data[`${propName}`].length > 0) {
          $('#site').html('<a href="'+data[`${propName}`]+'">Site</a>');
        } else {
          $('#site').html('None Provided');
        }
        break;
      case '':
        break;
    }
  }
}

function initObj() {
  $.ajax({
    url: 'api/v1/'+getObjType()+'/'+getParameterByName('id'),
    type: 'get',
    dataType: 'json',
    complete: objDone});
}

$(initObj);
