function just_me_clicked()
{
    if($('#just_me').is(':checked'))
    {
        if($('#safetyLead_name').val().length <= 0)
        {
            $('#safetyLead_name').val($('#campLead_name').val());
        }
        if($('#safetyLead_burnerName').val().length <= 0)
        {
            $('#safetyLead_burnerName').val($('#campLead_burnerName').val());
        }
        if($('#safetyLead_email').val().length <= 0)
        {
            $('#safetyLead_email').val($('#campLead_email').val());
        }
        if($('#safetyLead_phone').val().length <= 0)
        {
            $('#safetyLead_phone').val($('#campLead_phone').val());
        }
    }
}

function user_ajax_done(data)
{
    $('#campLead_name').val(data.givenName+' '+data.sn);
    $('#campLead_burnerName').val(data.displayName);
    $('#campLead_email').val(data.mail);
    $('#campLead_phone').val(data.mobile);
}

function tc_ajax_done(data, prefix)
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
            tc_ajax_done(data[key], prefix+key+'_');
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

function add_new_struct_to_table(type, width, length, height, desc)
{
    var tbody    = $('#structs_table tbody');
    var row      = $('<tr/>');
    var cell     = $('<td/>');
    var button   = $('<button/>', {type: 'button', class: 'btn btn-link btn-sm', onclick: 'delete_struct_from_table()'});

    if(type === undefined) type = '';
    if(width === undefined) width = '';
    if(length === undefined) length = '';
    if(height === undefined) height = '';
    if(desc === undefined) desc = '';

    $('<span/>', {class: 'glyphicon glyphicon-remove'}).appendTo(button);
    button.appendTo(cell);
    cell.appendTo(row);
    cell = $('<td/>');
    var dropdown = $('<select/>', {name: 'structs_type[]', 'class': 'form-control'});
    $('<option/>', {value: 'other', text: 'Other'}).appendTo(dropdown);
    $('<option/>', {value: 'oversidedTent', text: 'Oversized Tent'}).appendTo(dropdown);
    $('<option/>', {value: 'rv', text: 'RV'}).appendTo(dropdown);
    $('<option/>', {value: 'kitchen', text: 'Kitchen'}).appendTo(dropdown);
    $('<option/>', {value: 'bar', text: 'Bar'}).appendTo(dropdown);
    $('<option/>', {value: 'lounge', text: 'Lounge'}).appendTo(dropdown);
    $('<option/>', {value: 'dome', text: 'Dome'}).appendTo(dropdown);
    $('<option/>', {value: 'stage', text: 'Stage'}).appendTo(dropdown);
    $('<option/>', {value: 'art', text: 'Art Installation'}).appendTo(dropdown);
    $('<option/>', {value: 'dmv', text: 'Art Car/Mutant Vehicle'}).appendTo(dropdown);
    $('<option/>', {value: 'car', text: 'Car/Truck Camping'}).appendTo(dropdown);
    dropdown.appendTo(cell);
    dropdown.val(type);
    cell.appendTo(row);
    cell = $('<td/>');
    $('<input>', {type: "text", name: 'structs_width[]', required: true, class: 'form-control', val: width}).appendTo(cell);
    cell.appendTo(row);
    cell = $('<td/>');
    $('<input>', {type: "text", name: 'structs_length[]', required: true, class: 'form-control', val: length}).appendTo(cell);
    cell.appendTo(row);
    cell = $('<td/>');
    $('<input>', {type: "text", name: 'structs_height[]', required: true, class: 'form-control', val: height}).appendTo(cell);
    cell.appendTo(row);
    cell = $('<td/>');
    $('<input>', {type: "text", name: 'structs_desc[]', required: true, class: 'form-control', val: desc}).appendTo(cell);
    cell.appendTo(row);
    row.appendTo(tbody);
}

function add_existing_structs_to_table(struct)
{
    for(i = 0; i < struct.type.length; i++)
    {
        add_new_struct_to_table(struct.type[i], struct.width[i], struct.length[i], struct.height[i], struct.desc[i]);
    }
}

function pop_data()
{
    if(_id !== null)
    {
        $.ajax({
            url: 'api/tc/view/'+_id+'/full',
            type: 'get',
            dataType: 'json',
            success: tc_ajax_done
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
    //TODO - Make agnostic
    $('#just_me').change(just_me_clicked);
    pop_data();
}

$(tc_wizard_init);
