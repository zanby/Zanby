<script type="text/javascript">
{literal}
    //chenge style class
    function delete_selected(x, y)
    {
        var mes_ids = document.getElementById('checked_mes_ids').value.split(',');
        if (mes_ids[0] == "") mes_ids.shift();
        if (mes_ids)
            xajax_deleteMessage(mes_ids, false, x, y); 
        return false;
    }
    function restore_selected(x, y)
    {
        var mes_ids = document.getElementById('checked_mes_ids').value.split(',');
        if (mes_ids[0] == "") mes_ids.shift();
        if (mes_ids)
            xajax_restoreMessage(mes_ids, x, y); 
        return false;
    }
    function checkActive( _this, messageId, defaultV, activeV)
    {
        var element = document.getElementById('checked_mes_ids');
        var mes_ids = element.value.split(',');
        if (mes_ids[0] == "") mes_ids.shift();
        if (_this.checked) {
            var tr = document.getElementById('mess_' + messageId);
            if (defaultV!='' & activeV!=''){
				tr.className = defaultV + ' ' + activeV;
			}
            mes_ids.push(messageId);
        }
        else {
            var tr = document.getElementById('mess_' + messageId);
            if (defaultV!='' & activeV!=''){
				tr.className = defaultV;
            }
            var k=-1
            for (var i=0; i <= mes_ids.length-1; i++)
                if (mes_ids[i] == messageId)
                {
                    k=i; 
                    break;
                }
            mes_ids.splice(k, 1);
        }
        element.value = mes_ids.toString();
    }    
    
    // check all
    function check_all_checkboxes(form)
    {
        for (i=0;i<form.elements.length;i++) {
            if (form.elements[i].type=='hidden' && (form.elements[i].name=='0'||form.elements[i].name=='1')) {
                var checkBox = document.getElementById('message_' + form.elements[i].id);
                checkBox.checked = true;
                if (form.elements[i].name == '1'){
                    checkActive(checkBox, form.elements[i].id, '', '');
                } else {
                    checkActive(checkBox, form.elements[i].id, '', '');
                }
            }
        }

    }
    
    // uncheck all
    function clear_all_checkboxes(form)
    {
        for (i=0;i<form.elements.length;i++) {
            if (form.elements[i].type=='hidden' && (form.elements[i].name=='0'||form.elements[i].name=='1')) {
                var checkBox = document.getElementById('message_' + form.elements[i].id);
                checkBox.checked = false;
                if (form.elements[i].name == '1'){
                    checkActive(checkBox, form.elements[i].id, '', '');
                } else {
                    checkActive(checkBox, form.elements[i].id, '', '');
                }
            }
        }
    }
    
    // check read
    function selectIsRead( form )
    {
        clear_all_checkboxes( form );
        for (i=0;i<form.elements.length;i++) {
            if (form.elements[i].type=='hidden' && form.elements[i].name=='1') {
                var checkBox = document.getElementById('message_' + form.elements[i].id);
                checkBox.checked = true;
                checkActive(checkBox, form.elements[i].id, '', '');
            }
        }
    }
    
    // check unread
    function selectIsUnRead( form )
    {
        clear_all_checkboxes( form );
        for (i=0;i<form.elements.length;i++) {
            if (form.elements[i].type=='hidden' && form.elements[i].name=='0') {
                var checkBox = document.getElementById('message_' + form.elements[i].id);
                checkBox.checked = true;
                checkActive(checkBox, form.elements[i].id, '', '');
            }
        }
    }
{/literal}
</script>
 
	<!-- =========================================== -->
			<!-- my messages container -->
			
				<!-- init menu -->
				<script type="text/javascript">
					initMessagesMenu('prMessages-menu');
				</script>
				<!-- /init menu -->
				{assign var="redirectUrl" value=""|cat:$currentUser->getUserPath("messagelist/folder/`$folder`")}
				<div class="prInnerTop prInnerBottom"><a href="{$currentUser->getUserPath('messagecompose')}">{t}Compose Message{/t}</a></div>
				{if $folders.$folder.all != 0}
					<div class="prClr3">
						<h3 class="prFloatLeft">{$folder|capitalize} <span>{$infoPaging}</span> </h3>
						<div class="prFloatRight prPaginatorRight">{$linkPaging}</div>
					</div>
						<label>{t}Select: {/t}</label>
						<a href="#null" onclick="check_all_checkboxes(document.getElementById('form')); return false;">{t}All{/t}</a>  |&nbsp;
						<a href="#" onclick="clear_all_checkboxes(document.getElementById('form')); return false;">{t}None{/t}</a>&nbsp;
						{if $folder == 'inbox' || $folder == 'trash'}|&nbsp;
							<a href="#" onclick="selectIsRead(document.getElementById('form')); return false;">{t}Read{/t}</a>  |&nbsp;
							<a href="#" onclick="selectIsUnRead(document.getElementById('form')); return false;">{t}Unread{/t}</a>
						{/if}
				
					{form id="form" from=$deleteForm}
						{form_hidden id="checked_mes_ids" name="checked_mes_ids" value=""}
						{include file="users/messages/folder_`$folder`.tpl"}
					{/form}
				{else}
					<h3>{$folder|capitalize}</h3>
			 
						<p>
							{t}There are no messages.{/t}
						</p>
 
				{/if}
			

			<!--  /my messages container -->
	

	<!-- =========================================== -->
