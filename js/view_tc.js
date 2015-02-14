function tc_done(data)
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
        }
    }
    console.log(data);
}

function init_tc()
{
    $.ajax({
        url: 'api/tc/view/'+getParameterByName('id'),
        type: 'get',
        dataType: 'json',
        success: tc_done});
}

$(init_tc);
