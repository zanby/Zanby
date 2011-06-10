function check_all_checkboxes(form)
{
    for (i=0;i<form.elements.length;i++) {
        if (form.elements[i].type=='checkbox') {
            form.elements[i].checked= true;
        }
    }
}
function clear_all_checkboxes(form)
{
    for (i=0;i<form.elements.length;i++) {
        if (form.elements[i].type=='checkbox') {
            form.elements[i].checked= false;
        }
    }
}