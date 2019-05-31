function saveDone(jqXHR)
{
  if(jqXHR.status !== 200)
  {
    alert('Unable to save text source!');
    console.log(jqXHR);
    return;
  }
}

function save()
{
  $.ajax({
    url: '../api/v1/text/'+$('#registration_text_name').val(),
    type: 'PATCH',
    data: $('#pdf-source').val(),
    processData: false,
    contentType: false,
    complete: saveDone});
}

function gotTextSource(jqXHR)
{
  if(jqXHR.status !== 200)
  {
    alert('Unable to obtain text source!');
    console.log(jqXHR);
    return;
  }
  $('#pdf-source').val(jqXHR.responseJSON);
}

function registration_text_changed()
{
  $.ajax({
    url: '../api/v1/text/'+$('#registration_text_name').val(),
    type: 'get',
    complete: gotTextSource});
}

function pageInit()
{
  $('#pdf-source').ckeditor({
    'allowedContent': true
  });
  var type = getParameterByName('type');
  if(type !== null)
  {
    $('#registration_text_name').val(type);
  }
  registration_text_changed();
}

$(pageInit);
