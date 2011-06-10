if (document.getElementById('{$div_id}'))
{literal}
{
{/literal}
    //alert('Id "' + {$div_id}.id + '" already exists!');
{literal}
}
else
{
{/literal}
    var div_data = document.createElement("div");
    div_data.id = '{$div_id}';    
    document.body.appendChild(div_data);
}