if (document.getElementById('block_layer_{$div_id}'))
{literal}
{
{/literal}    
    document.getElementById('block_layer_{$div_id}').style.height = document.body.clientHeight;
    document.getElementById('block_layer_{$div_id}').style.top = document.body.scrollTop;
    document.getElementById('block_layer_{$div_id}').style.left = document.body.scrollLeft;
{literal}    
}
else
{
    alert('Id popup block layer not found!');
}
{/literal}