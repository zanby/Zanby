{if $discussion_mode == 'html'}<script type="text/javascript" src="/js/tinymceBlog/tiny_mce.js"></script>
{else}<script src="/js/discussion/bbcode.js"></script>{/if}

{form from=$form}
	{form_errors_summary}
{/form}
<h2>{t}New Topic{/t}</h2>
{form from=$form id="createTopicForm"}	
<table cellspacing="0" cellpadding="0" border="0" class="prForm">
	<col width="10%" />
	<col width="90%" />
	<tr>
		<td><label>{t}From:{/t}</label></td>
		<td>{$user->getLogin()|escape:html}</td>
	</tr>
	<tr>
		<td><label for="replyTo">{t}To:{/t}</label></td>
		<td >
			{$discussionObj->getGroup()->getDiscussionGroupName()|escape:html|longwords:30} - {$discussionObj->getTitle()|escape:html|longwords:30}
			{form_hidden name="discussion" value=$discussionObj->getId()}
		</td>
	</tr>
	<tr>
		<td><label for="replySubj">{t}Subject:{/t}</label></td>
		<td>
			{form_text name="subject" value=$postData.subject|escape:html"}
		</td>
	</tr>
</table>			

{if $discussion_mode == 'html'}{else}<div class="prIndentTop">{include file="groups/discussion/template.bbcode.panel.tpl"}</div>{/if}
	
{form_textarea name="content" id="content" rows="10" cols="80" value=$postData.content|escape:html  onselect="storeCaret(this);" onclick="storeCaret(this);" onkeyup="storeCaret(this);"}
	<div class="prTRight prIndentTop">{t var="in_submit"}Post Message{/t}{form_submit name="Post Message" value=$in_submit}</div>
{/form}
<!-- Vertical Spacer -->

{if $discussion_mode == 'html'}
	{literal}
	<!-- tinyMCE -->
	<script language="javascript" type="text/javascript">
	    // Notice: The simple theme does not use all options some of them are limited to the advanced theme
	    $(function(){
	        tinyMCE.init({
	            // General options
	            mode : "textareas",
	            theme : "advanced",
	            plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups",
	    
	            // Theme options
	            theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,outdent,indent,|,sub,sup,|,forecolor,backcolor,|,formatselect,fontselect,fontsizeselect",         
	            theme_advanced_buttons2 : "link,unlink,anchor,|,charmap,hr,|,cleanup,removeformat,|,undo,redo",
	            theme_advanced_buttons3 : '',           
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
	    })
	</script>
	<!-- /tinyMCE -->
	{/literal}
{/if}