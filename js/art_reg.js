function user_ajax_done(data)
{
    $('#artLead_name').val(data.givenName+' '+data.sn);
    $('#artLead_burnerName').val(data.displayName);
    $('#artLead_email').val(data.mail);
    $('#artLead_phone').val(data.mobile);
}

function art_ajax_done(data, prefix)
{
    if(prefix === undefined || prefix === 'success')
    {
        prefix = '';
    }
    for(var key in data)
    {
        if(key === '_id')
        {
        }
        else if(key === 'structs')
        {
            add_existing_structs_to_table(data[key]);
        }
        else if(typeof(data[key]) === 'object')
        {
            art_ajax_done(data[key], prefix+key+'_');
        }
        else if($('#'+prefix+key).length > 0)
        {
            var control = $('#'+prefix+key);
            if(control.filter('select').length > 0)
            {
                if(control.val() === data[key]) continue;
            }
            else if(control.filter('[type=file]').length > 0)
            {
                if(data[key].length > 0)
                {
                    var img = $('<img>', {'class':'obj', 'src': data[key], 'style':'max-width: 200px; max-height: 200px'});
                    control.after(img);
                }
            }
            else
            {
                control.val(data[key]);
            }
            if(data[key].length > 0)
            {
                var panelID = control.parents('.tab-pane').attr('id');
                var id = $('a[href=#'+panelID+']').parent().attr('id');
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

function pop_data()
{
    if(_id !== null)
    {
        $.ajax({
            url: 'api/art/view/'+_id+'/full',
            type: 'get',
            dataType: 'json',
            success: art_ajax_done
        });
    }
    else
    {
        if(browser_supports_cors())
        {
            $.ajax({
                url: 'https://profiles.burningflipside.com/ajax/user.php',
                type: 'get',
                dataType: 'json',
                xhrFields: {withCredentials: true},
                success: user_ajax_done});
        }
        else
        {
            add_notification($('#content'), 'Your browser is out of date. Due to this some data may not be set automatically. Please make sure it is complete');
        }
        add_new_struct_to_table();
    }
}

function tc_wizard_init()
{
    pop_data();
}

$(tc_wizard_init);
