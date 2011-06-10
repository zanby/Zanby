function check_all_checkboxes(form, _this)
{
    var flag;
    if (_this.checked) flag = Boolean(true);
    else flag = Boolean(false);
    for (i=0;i<form.elements.length;i++) {
            if (form.elements[i].type=='checkbox') {
                form.elements[i].checked= flag;
            }
        }
}