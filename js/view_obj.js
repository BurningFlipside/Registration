function validate_contact_dialog()
{
    var ret = true;
    var required = $('#contact_dialog :input:not(:button)');
    for(i = 0; i < required.length; i++)
    {
        var control = $(required[i]);
        var value = control.val()
        if(value == null || value.length == 0)
        {
            control.parents('.form-group').prop('class', 'form-group has-error');
            ret = false;
        }
        else
        {
            control.prop('class', 'form-control has-success');
        }
    }
    return ret;
}

function contact_done(data)
{
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

function get_obj_type()
{
    var name = get_page_name();
    name = name.substring(5);
    name = name.substring(0, name.length - 4);
    if(name === 'tc')
    {
        return 'camps';
    }
    return name;
}

function contact_lead()
{
    if(validate_contact_dialog())
    {
        $.ajax({
            url: 'api/v1/'+get_obj_type()+'/'+getParameterByName('id')+'/contact',
            type: 'post',
            data: $('#email_text').add('#subject').serialize(),
            dataType: 'json',
            success: contact_done});
        $('#contact_dialog').modal('hide');
    }
}

function obj_done(data)
{
    for(var propName in data)
    {
        switch(propName)
        {
            default:
                $('#'+propName).val(data[propName]);
                break;
            case 'logo':
                if(data[propName].length > 0)
                {
                    $('#logo').html('<img src="'+data[propName]+'" style="max-width:200px; max-height:200px;"/>');
                }
                else
                {
                    $('#logo').html('None Provided');
                }
                break;
            case 'site':
                if(data[propName].length > 0)
                {
                    $('#site').html('<a href="'+data[propName]+'">Site</a>');
                }
                else
                {
                    $('#site').html('None Provided');
                }
                break;
            case '':
		break;
        }
    }
}

function init_obj()
{
    $.ajax({
        url: 'api/v1/'+get_obj_type()+'/'+getParameterByName('id'),
        type: 'get',
        dataType: 'json',
        success: obj_done});
}

$(init_obj);
