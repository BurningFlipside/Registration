var _id = null;
var final_done = false;

function show_tab(e)
{
    e.preventDefault();
    if($(e.target).parent().filter('.disabled').length === 0)
    {
        $(this).tab('show');
    }
}

function tab_changed(e)
{
    var tab_index = $(e.target).parent().index();
    if(tab_index == 0)
    {
        $('.previous').attr('class', 'previous disabled');
    }
    else
    {
        $('.previous').attr('class', 'previous');
    }
    var last_index = $(e.target).parent().siblings().last().index();
    if(tab_index >= last_index)
    {
        if(final_done)
        {
            $('.next').html('<a class="page-link" onclick="window.location=\'add.php\'" style="cursor: pointer;">Exit</a>');
        }
        else
        {
            $('.next').html('<a class="page-link" onclick="final_post(event)" style="cursor: pointer;">Save and Finish</a>');
        }
    }
    else
    {
        $('.next').html('<a class="page-link" href="#" onclick="next_tab(event)">Save and Continue <span aria-hidden="true">&rarr;</span></a>');
    }
}

function prev_tab(e)
{
    $('li.active').prevAll(":not('.disabled')").first().contents().tab('show');
}

function validate_control_set(set)
{
    var ret = true;
    for(i = 0; i < set.length; i++)
    {
        var control = $(set[i]);
        var value = control.val();
        if(value == null || value.length == 0)
        {
            control.prop('class', 'form-control is-invalid');
            control.parents('tr').prop('class', 'is-invalid');
            if(control.parents('.panel-collapse').length > 0)
            {
                control.parents('.panel-collapse').collapse('show');
            }
            ret = false;
        }
        else
        {
            control.prop('class', 'form-control is-valid');
        }
    }
    return ret;
}

function preg_quote(str, delimiter)
{
    return String(str).replace(new RegExp('[.\\\\+*?\\[\\^\\]$(){}=!<>|:\\' + (delimiter || '') + '-]', 'g'), '\\$&');
}

function name_check_done(data)
{
    if(data.responseJSON !== undefined && data.responseJSON.length > 0)
    {
        $('#name').prop('class', 'form-group is-invalid'); 
        this.callback(false);
    }
    else
    {
        this.callback(this.ret);
    }
}

function validate_current(callback)
{
    var ret = true;
    var required = $('div.tab-pane.active').find('[required]');
    if(required.length !== 0)
    {
        if(validate_control_set(required) === false)
        {
            ret = false;
        }
    }
    var special = $('div.tab-pane.active').find('[type=url]');
    if(special.length !== 0)
    {
        for(i = 0; i < special.length; i++)
        {
            var raw_control = special[i];
            var control = $(raw_control);
            if(raw_control.validity !== undefined && !raw_control.validity.valid)
            {
                if(raw_control.validationMessage !== undefined)
                {
                    control.attr('title', raw_control.validationMessage);
                }
                control.prop('class', 'form-control is-invalid');
                if(control.parents('.panel-collapse').length > 0)
                {
                    control.parents('.panel-collapse').collapse('show');
                }
                ret = false;
            }
            else
            {
                control.parents('.form-group').prop('class', 'form-control is-valid');
                control.removeAttr('title');
            }
        }
    }
    if(_id === null)
    {
        var name = $('#name').val();
        name = preg_quote(name);
        var obj = {};
        obj.callback = callback;
        obj.ret = ret;
        $.ajax({
            url: get_list_all_url()+'/Actions/Search',
            data: 'name=/^'+encodeURIComponent(name)+'/i',
            type: 'get',
            dataType: 'json',
            context: obj,
            complete: name_check_done
        });
    }
    else
    {
        callback(ret);
    }
}

function post_done(data)
{
  if(data._id !== undefined) {
    _id = data._id; 
  }
  else if(data['$id'] !== undefined) {
    _id = data['$id'];
  }
  else if(data['$oid'] !== undefined) {
    _id = data['$oid'];
  }
  else {
    console.log(data);
  }
}

function final_post_done(data)
{
    if(data === true)
    {
        location.href = '/register/add.php';
    }
    else if(data['$id'] !== undefined)
    {
        location.href = '/register/add.php';
    }
    else if(data.message !== undefined)
    {
        alert('Error! '+data.message);
    }
    else
    {
        alert('Error! '+JSON.stringify(data));
    }
    console.log(data);
}

function get_page_name()
{
    var file, n;
    file = window.location.pathname;
    n = file.lastIndexOf('/');
    if(n >= 0)
    {
        file = file.substring(n + 1);
    }
    return file;
}

function get_list_all_url()
{
    var url = null;
    var page = get_page_name();
    if(page.startsWith('tc_'))
    {
        url = 'api/v1/camps';
    }
    else if(page.startsWith('art_'))
    {
        url = 'api/v1/art';
    }
    else if(page.startsWith('artCar_'))
    {
        url = 'api/v1/dmv';
    }
    else if(page.startsWith('event_'))
    {
        url = 'api/v1/event';
    }
    return url;
}

function get_post_url()
{
    var url = null;
    var page = get_page_name();
    if(page.startsWith('tc_'))
    {
        if(_id == null)
        {
            url = 'api/v1/camps';
        }
        else
        {
            url = 'api/v1/camps/'+_id;
        }
    }
    else if(page.startsWith('art_'))
    {
        if(_id == null)
        {
            url = 'api/v1/art';
        }
        else
        {
            url = 'api/v1/art/'+_id;
        }
    }
    else if(page.startsWith('artCar_'))
    {
        if(_id == null)
        {
            url = 'api/v1/dmv';
        }
        else
        {
            url = 'api/v1/dmv/'+_id;
        }
    }
    else if(page.startsWith('event_'))
    {
        if(_id == null)
        {
            url = 'api/v1/event';
        }
        else
        {
            url = 'api/v1/event/'+_id;
        }
    }
    return url;
}

function post_error(data)
{
    if(data.responseJSON !== undefined)
    {
        data = data.responseJSON;
    }
    if(data.message !== undefined)
    {
        alert("Unable to save data because: "+data.message);
    }
    else if(data.status === 401)
    {
        alert("Unable to save data because your session has expired! Please log back in and retry.");
    }
    else
    {
        alert("Unable to save data for unknown reason!");
        console.log(data);
    }
}

function post_data()
{
    var data = form_data_to_obj();
    if(_id !== null)
    {
        data['_id'] = _id;
    }
    if(window.getAdditionalData !== undefined) {
      data = Object.assign(data, getAdditionalData());
    }
    if(window.filterData !== undefined) {
      data = filterData(data);
    }
    $.ajax({
        url: get_post_url(),
        type: 'post',
        dataType: 'json',
        contentType: 'application/json',
        data: JSON.stringify(data),
        processData: false,
        success: post_done,
        error: post_error
    });
}

function do_final_post(cont)
{
    if(cont)
    {
        var data = form_data_to_obj();
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
            url: get_post_url(),
            type: 'post',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(data),
            processData: false,
            success: final_post_done,
            error: post_error
        });
    }
}

function really_do_final(result)
{
    if(result)
    {
        do_final_post(true);
    }
}

function do_final_dialog(cont)
{
    if(cont)
    {
        bootbox.confirm("Are you sure you want to save? After clicking OK on this dialog you will not be able to edit the registration further.", really_do_final);
    }
}

function do_next_tab(cont)
{
    if(cont)
    {
        $('.nav-tabs .active').parent().next('li').find('a').trigger('click');
        if(!final_done)
        {
            post_data();
        }
    }
}

function final_post(e)
{
    e.preventDefault();
    validate_current(do_final_dialog);
    return false;
}

function next_tab(e)
{
    if(!final_done)
    {
        do_next_tab(true);
    }
    else
    {
        validate_current(do_next_tab);
    }
}

function tabcontrol_change()
{
    var control = $(this);
    var tab_id  = control.data('tabcontrol');
    if(control.is(':checked'))
    {
        $('#'+tab_id).attr('class', '');
        $('#'+tab_id+' a').attr('data-toggle', 'tab');
    }
    else
    {
        var others = $('[data-tabcontrol='+tab_id+']:checked');
        if(others.length > 0)
        {
            return;
        }
        $('#'+tab_id).attr('class', 'disabled');
        $('#'+tab_id+' a').attr('data-toggle', '');
    }
}

function groupcontrol_change()
{
    var control = $(this);
    var group_id = control.data('groupcontrol');
    var group_ctrl = $('#'+group_id).parent('.panel');
    if(control.is(':checked'))
    {
        group_ctrl.show();
        group_ctrl.find('[data-was-required]').attr('required', 'true');
    }
    else
    {
        group_ctrl.hide();
        group_ctrl.find('[required]').removeAttr('required').attr('data-was-required', 1);
    }
}

function questcontrol_change()
{
    var control = $(this);
    var quest_id = control.data('questcontrol');
    var group_ctrl = $('#'+quest_id).parents('.form-group');
    if(control.is(':checked'))
    {
        group_ctrl.show();
        group_ctrl.find('[data-was-required]').attr('required', 'true');
    }
    else
    {
        group_ctrl.hide();
        group_ctrl.find('[required]').removeAttr('required').attr('data-was-required', 1);
    }
}

function copytrigger_changed(e)
{
    var control = e.data;
    var original = $('#'+control.data('copyfrom'));
    control.val(original.val());
}

function setup_copycontrol()
{
    var control = $(this);
    var trigger = control.data('copytrigger');
    var trigger_control = $(trigger);
    trigger_control.change(control, copytrigger_changed);
}

function add_val_to_field(obj, fieldname, val)
{
    var index = fieldname.indexOf('[]');
    if(index != -1)
    {
        fieldname = fieldname.substr(0, index);
        if(obj[fieldname] === undefined)
        {
            obj[fieldname] = [];
        }
        obj[fieldname].push(val);
    }
    else
    {
        obj[fieldname] = val;
    }
}

function resize_img(img, element, imageType)
{
    if(img.height <= 640 && img.width <= 640)
    {
        element.attr('src', img.src);
    }
    else
    {
        var canvas = document.createElement('canvas');
        var max = 640;
        var width = img.width;
        var height = img.height;

        if(width > height)
        {
            height *= max/width;
            width   = max;
        }
        else
        {
            width *= max/height;
            height = max;
        }
        canvas.width = width;
        canvas.height = height;
        canvas.getContext('2d').drawImage(img, 0, 0, width, height);
        element.attr('src', canvas.toDataURL(imageType));
    }
}

function handle_files()
{
    var files = $(this)[0].files;
    for(i = 0; i < files.length; i++)
    {
        var file = files[i];
        var imageType = /image.*/;
        if(!file.type.match(imageType))
        {
            alert('Not an image');
            console.log(file);
            continue;
        }
        var image = new Image();
        var img = $(this).next('.obj');
        if(img.length == 0)
        {
            img = $('<img>', {'class': 'obj', 'style':'max-width: 200px; max-height: 200px;'});
        }
        var reader = new FileReader();
        reader.onloadend = function() {image.src=reader.result; resize_img(image, img, imageType);}
        $(this).after(img);
        reader.readAsDataURL(file);
    }
}

function add_file_to_field(obj, fieldname, control)
{
    var src = control.nextAll('.obj').attr('src');
    obj[fieldname] = src;
}

function form_data_to_obj()
{
    var ret = {};
    var controls = $('.tab-content :input:not(.ignore)');
    for(i = 0; i < controls.length; i++)
    {
        var control = $(controls[i]);
        var name    = control.prop('name');
        if(name.indexOf('_') != -1)
        {
            var names = name.split('_');
            var obj = ret;
            for(j = 0; j < names.length - 1; j++)
            {
                if(obj[names[j]] === undefined)
                {
                    obj[names[j]] = {};
                }
                obj = obj[names[j]];
            }
            if(control.attr('type') === 'file')
            {
                add_file_to_field(ret, name, control);
            }
            else if(control.attr('type') === 'checkbox')
            {
                add_val_to_field(obj, names[j], control.is(':checked'));
            }
            else
            {
                add_val_to_field(obj, names[j], control.val());
            }
        }
        else
        {
            if(control.attr('type') === 'file')
            {
                add_file_to_field(ret, name, control);
            }
            else if(control.attr('type') === 'checkbox')
            {
                add_val_to_field(ret, name, control.is(':checked'));
            }
            else
            {
                add_val_to_field(ret, name, control.val());
            }
        }
    }
    return ret;
}

function prior_ajax_done(data, prefix)
{
    var first = false;
    if(prefix === undefined || prefix === 'success')
    {
        prefix = '';
        first = true;
    }
    for(var key in data)
    {
        if(key === '_id' || key === '')
        {
        }
        else if(typeof(data[key]) === 'object')
        {
            prior_ajax_done(data[key], prefix+key+'_');
        }
        else if($("[id='"+prefix+key+"']").length > 0)
        {
            var control = $("[id='"+prefix+key+"']");
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
                if(data[key] === 'true' || data[key] === true)
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
            //console.log("[id='"+prefix+key+"']");
        }
    }
    if(first)
    {
      var obj = null;
      var page = get_page_name();
      if(page.startsWith('tc_'))
      {
        obj = data['camplead'];
      }
      else if(page.startsWith('art_'))
      {
        obj = data['artlead'];
      }
      if(obj !== null) {
        if(obj === undefined || obj.email === undefined || obj.email === '') {
          getUserData();
        }
      }
    }
}

function prior_ajax_error(data)
{
    console.log(data);
    if(data.message !== undefined)
    {
        alert("Unable to load data because: "+data.message);
    }
    else
    {
        alert("Unable to load data for unknown reason!");
    }
}

function gotUserData(jqxhr) {
  var objName = null;
  var page = get_page_name();
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
    }
    var data = jqxhr.responseJSON;
    $('#'+objName+'_name').val(data.givenName+' '+data.sn);
    $('#'+objName+'_burnerName').val(data.displayName);
    $('#'+objName+'_email').val(data.mail);
    $('#'+objName+'_phone').val(data.mobile);
  }
}

function getUserData() {
   if(browser_supports_cors()) {
     $.ajax({
       url: window.profilesUrl+'/api/v1/users/me',
       type: 'get',
       dataType: 'json',
       xhrFields: { withCredentials: true },
       complete: gotUserData});
  }
  else {
    add_notification($('#content'), 'Your browser is out of date. Due to this some data may not be set automatically. Please make sure it is complete');
  }
}

function populate_prior_data()
{
    if(_id !== null)
    {
        $.ajax({
            url: get_post_url()+'?full=true',
            type: 'get',
            dataType: 'json',
            success: prior_ajax_done,
            error: prior_ajax_error
        });
    }
    else {
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

function wizard_init()
{
    _id = getParameterByName('id');
    if(_id === null) {
      _id = getParameterByName('_id');
    }
    $('[title]').tooltip();
    $('input[data-tabcontrol]').change(tabcontrol_change);
    $('input[data-groupcontrol]').change(groupcontrol_change);
    $('input[data-questcontrol]').change(questcontrol_change);
    $('input[type=file]').change(handle_files);
    $('input[data-tabcontrol]').each(tabcontrol_change);
    $('input[data-groupcontrol]').each(groupcontrol_change);
    $('input[data-questcontrol]').each(questcontrol_change);
    $('input[data-copytrigger]').each(setup_copycontrol);
    $('.previous').attr('class', 'previous disabled');
    $('a[data-toggle="tab"]').on('shown.bs.tab', tab_changed);
    if(browser_supports_input_type('url'))
    {
        $('#site').attr('type', 'url');
    }
    var page = get_page_name();
    if(page.startsWith('tc_') === false)
    {
        populate_prior_data();
    }
    var onepage = getParameterByName('onepage');
    if(onepage === 'true')
    {
        $('.tab-pane').addClass('active');
        $('#rootwizard .navbar').hide();
        $('.alert').hide();
        $('.panel-collapse').addClass('in');
        $('.embed-responsive').hide();
    }
}

$(wizard_init);
