<script type="text/javascript" src="/js/tinymceBlog/tiny_mce.js"></script>
		<h2>{t}Blog{/t}</h2>
    	<div class="prClr2">
            <div class="prFloatLeft">
                <p>{t}Edit blog post details{/t}</p>
            </div>
            <div class="prFloatRight prInnerRight">
				<a href="{$currentGroup->getGroupPath('blog')}">{t}Return to blog main page{/t} &raquo;</a>
            </div>
        </div>
        {form from=$form onsubmit="xajax_settings_loginInformation_save(xajax.getFormValues('liForm')); return false;" id="liForm" name="liForm"}
		{form_errors_summary}
            <table class="prForm">
                <col width="25%" />
                <col width="40%" />
                <col width="35%" />
                <tbody>
                    <tr>
                        <td class="prTRight"><span>*</span> <label for="login">{t}Subject:{/t}</label></td>
                        <td colspan="2">{form_text name="subject" value=$postData.subject|escape:html}</td>
                    </tr>
                    <tr>
                        <td colspan="3">{form_textarea name="content" value=$postData.content|escape:html}</td>
                    </tr>
                    <tr>
                        <td class="prTRight"></td>
                        <td>&nbsp;</td>
                        <td class="prTip">
                        	<div class="prFloatRight">
							{t var="in_submit"}Save Changes{/t}
                            {form_submit name="form_save" value=$in_submit}&nbsp;
							<span class="prIEVerticalAling">{t}or{/t} <a href="$currentGroup->getGroupPath('blog')">{t}Cancel{/t}</a></span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        {/form}
{literal}
<!-- tinyMCE -->
<script language="javascript" type="text/javascript">
    // Notice: The simple theme does not use all options some of them are limited to the advanced theme
	tinyMCE.init({
		// General options
		mode : "textareas",
		theme : "advanced",
		plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups",

		// Theme options
		theme_advanced_buttons1 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "undo,redo,|,justifyleft,justifycenter,justifyright,justifyfull,|,bold,italic,underline,strikethrough,|,bullist,numlist,outdent,indent,|,sub,sup,|,forecolor,backcolor",
		theme_advanced_buttons3 : "link,unlink,anchor,|,image,|,hr,|,charmap,emotions,iespell,media,advhr,|,insertdate,inserttime,|,cleanup,removeformat",
		theme_advanced_buttons4 : "tablecontrols,visualaid,|,preview,code",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resize_horizontal : false,
		theme_advanced_resizing : true,
		width : "99%",
		// Example word content CSS (should be your site CSS) this one removes paragraph margins
		content_css : "css/word.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js"

	});

</script>
<!-- /tinyMCE -->
{/literal}
