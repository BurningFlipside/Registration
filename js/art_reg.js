function user_ajax_done(data)
{
    $('#artLead_name').val(data.givenName+' '+data.sn);
    $('#artLead_burnerName').val(data.displayName);
    $('#artLead_email').val(data.mail);
    $('#artLead_phone').val(data.mobile);
}

function pop_data()
{
    if(_id !== null)
    {
        //Now done in reg.js
    }
    else
    {
        if(browser_supports_cors())
        {
            $.ajax({
                url: window.profilesUrl+'/api/v1/users/me',
                type: 'get',
                dataType: 'json',
                xhrFields: {withCredentials: true},
                success: user_ajax_done});
        }
        else
        {
            add_notification($('#content'), 'Your browser is out of date. Due to this some data may not be set automatically. Please make sure it is complete');
        }
    }
}

function tc_wizard_init()
{
    pop_data();
}

$(tc_wizard_init);
