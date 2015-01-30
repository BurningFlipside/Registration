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

function tc_ajax_done(data)
{
    console.log(data);
    for(var key in data.camp)
    {
        if($('#'+key).length > 0)
        {
            $('#'+key).val(data.camp[key]);
        }
        else
        {
            console.log(key);
            console.log(data.camp[key]);
        }
    }
}

function add_new_struct_to_table()
{
    var tbody    = $('#structs_table tbody');
    var row      = $('<tr/>');
    var cell     = $('<td/>');
    var button   = $('<button/>', {type: 'button', class: 'btn btn-link btn-sm', onclick: 'delete_struct_from_table()'});
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
    cell.appendTo(row);
    cell = $('<td/>');
    $('<input>', {type: "text", name: 'structs_width[]', required: true, class: 'form-control'}).appendTo(cell);
    cell.appendTo(row);
    cell = $('<td/>');
    $('<input>', {type: "text", name: 'structs_length[]', required: true, class: 'form-control'}).appendTo(cell);
    cell.appendTo(row);
    cell = $('<td/>');
    $('<input>', {type: "text", name: 'structs_height[]', required: true, class: 'form-control'}).appendTo(cell);
    cell.appendTo(row);
    cell = $('<td/>');
    $('<input>', {type: "text", name: 'structs_desc[]', required: true, class: 'form-control'}).appendTo(cell);
    cell.appendTo(row);
    row.appendTo(tbody);
}

function pop_data()
{
    if(_id !== null)
    {
        $.ajax({
            url: 'ajax/tc.php',
            type: 'get',
            dataType: 'json',
            data: '_id='+_id,
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
