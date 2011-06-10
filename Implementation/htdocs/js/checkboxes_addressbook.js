
    function check_uncheck_checkboxes(element, formId, prefix)
    {
        if (element.checked) check_all_checkboxes(document.getElementById(formId), prefix);
        else clear_all_checkboxes(document.getElementById(formId), prefix);
    }
    function checkActive( _this, prefix, messageId, defaultV, activeV)
    {
        var element = document.getElementById('newContacts');
        if (element)
        {
            var cont_ids = element.value.split(',');
            if (cont_ids[0] == "") cont_ids.shift();
        
            if (_this.checked) {
                var tr = document.getElementById(prefix + messageId);
                tr.className = activeV;
                if (element.value.indexOf(messageId) == -1) cont_ids.push(messageId);
            }
            else {
                var tr = document.getElementById(prefix + messageId);
                tr.className = defaultV;
                var k=-1
                for (var i=0; i <= cont_ids.length-1; i++)
                    if (cont_ids[i] == messageId)
                    {
                        k=i; 
                        break;
                    }
                cont_ids.splice(k, 1);
            }
            element.value = cont_ids.toString();
        }
        else
        {
            if (_this.checked) {
                var tr = document.getElementById(prefix + messageId);
                tr.className = activeV;
            }
            else {
                var tr = document.getElementById(prefix + messageId);
                tr.className = defaultV;
            }
        }     
       
    }
    // check all
    function check_all_checkboxes(form, prefix)
    {
        for (i=0;i<form.elements.length;i++) {
            if (form.elements[i].type=='hidden' && (form.elements[i].name=='0'||form.elements[i].name=='1')) {
                var checkBox = document.getElementById(prefix + form.elements[i].id);
                if (!checkBox.disabled){
                    checkBox.checked = true;
                    if (form.elements[i].name == '1'){
                        checkActive(checkBox, prefix + 'row_', form.elements[i].id, '', '');
                    } else {
                        checkActive(checkBox, prefix + 'row_', form.elements[i].id, '', 'znBG1');
                    }    
                }    
            }
        }

    }
    // uncheck all
    function clear_all_checkboxes(form, prefix)
    {
        for (i=0;i<form.elements.length;i++) {
            if (form.elements[i].type=='hidden' && (form.elements[i].name=='0'||form.elements[i].name=='1')) {
                
                var checkBox = document.getElementById(prefix + form.elements[i].id);
                checkBox.checked = false;
                if (form.elements[i].name == '1'){
                    checkActive(checkBox, prefix + 'row_', form.elements[i].id, '', '');
                } else {
                    checkActive(checkBox, prefix + 'row_', form.elements[i].id, '', 'znBG1');
                }
            }
        }
    }
