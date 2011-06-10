<link rel="stylesheet" type="text/css" href="{$AppTheme->css}/dnd.css" media="screen" />
{literal}
<style type="text/css">
    .tree-documents-folder-inactive {color: #000000; cursor:pointer; text-decoration: none;}
    .tree-documents-folder-active {color : #CB0000; cursor: pointer; text-decoration: none;}
    .ygtvitem table td {border: 0px;}
    #tree_div_0 {margin:10px 0px 0px 10px; overflow: auto; width:175px;}
</style>
{/literal}
<link rel="stylesheet" type="text/css" href="{$AppTheme->common->css}/tree.css" media="screen" />
<script src="{$JS_URL}/AKColorPicker.js" type="text/javascript"></script>
<script src="{$JS_URL}/AKLinePicker.js" type="text/javascript"></script>
<script src="{$JS_URL}/loadPreset.js" type="text/javascript"></script>
<!-- ******** THEME EDITOR BEGIN ******** -->
<!--[if IE]>
<script type="text/javascript" src="{$JS_URL}/ieselectfix.js"></script>
<![endif]-->
<!-- setting box begin -->


<script src="{$JS_URL}/content_objects/DDblockApp.js" type="text/javascript"></script>

<script type="text/javascript" src="{$JS_URL}/content_objects/DDC.class.js"></script>
<script type="text/javascript" src="{$JS_URL}/content_objects/DDCBlockFactory.class.js"></script>

<script type="text/javascript" src="{$JS_URL}/content_objects/DDCTextBlock.class.js"></script>
<script type="text/javascript" src="{$JS_URL}/content_objects/DDCGroupAvatar.class.js"></script>
<script type="text/javascript" src="{$JS_URL}/content_objects/DDCHeadline.class.js"></script>
<script type="text/javascript" src="{$JS_URL}/content_objects/DDCFastHeadline.class.js"></script>
<script type="text/javascript" src="{$JS_URL}/content_objects/DDCGroupHeadline.class.js"></script>
<script type="text/javascript" src="{$JS_URL}/content_objects/DDCGroupDescription.class.js"></script>
<script type="text/javascript" src="{$JS_URL}/content_objects/DDCRSSFeed.class.js"></script>
<script type="text/javascript" src="{$JS_URL}/content_objects/DDCGroupMembers.class.js"></script>
<script type="text/javascript" src="{$JS_URL}/content_objects/DDCMyDocuments.class.js"></script>
<script type="text/javascript" src="{$JS_URL}/content_objects/DDCGroupDocuments.class.js"></script>
<script type="text/javascript" src="{$JS_URL}/content_objects/DDCFamilyDiscussions.class.js"></script>
<script type="text/javascript" src="{$JS_URL}/content_objects/DDCMyLists.class.js"></script>
<script type="text/javascript" src="{$JS_URL}/content_objects/DDCGroupLists.class.js"></script>
<script type="text/javascript" src="{$JS_URL}/content_objects/DDCMyPhotos.class.js"></script>
<script type="text/javascript" src="{$JS_URL}/content_objects/DDCGroupPhotos.class.js"></script>
<script type="text/javascript" src="{$JS_URL}/content_objects/DDCImage.class.js"></script>
<script type="text/javascript" src="{$JS_URL}/content_objects/DDCGroupImage.class.js"></script>
<script type="text/javascript" src="{$JS_URL}/content_objects/DDCEvents.class.js"></script>
<script type="text/javascript" src="{$JS_URL}/content_objects/DDCGroupEvents.class.js"></script>
<script type="text/javascript" src="{$JS_URL}/content_objects/DDCGroupFamilyIcons.class.js"></script>

<script type="text/javascript" src="{$JS_URL}/content_objects/DDCMogulus.class.js"></script>
<script type="text/javascript" src="{$JS_URL}/content_objects/DDCIframe.class.js"></script>
<script type="text/javascript" src="{$JS_URL}/content_objects/DDCScript.class.js"></script>
<script type="text/javascript" src="{$JS_URL}/content_objects/DDCFamilyVideoContentBlock.class.js"></script>
<script type="text/javascript" src="{$JS_URL}/content_objects/DDCFamilyTopVideos.class.js"></script>

<script type="text/javascript" src="{$JS_URL}/content_objects/DDCGroupWidgetMap.class.js"></script>

<script type="text/javascript" src="{$JS_URL}/SWFUpload/swfupload.js"></script>
<script type="text/javascript" src="{$JS_URL}/SWFUpload/handlers.js"></script>
<script type="text/javascript" src="{$JS_URL}/SWFUpload/swfobject.js"></script>
<script src="{$JS_URL}/SWFUpload/swfuploadcode.js" type="text/javascript"></script>
<script type="text/javascript" src="{$JS_URL}/SWFUpload/swfupload.graceful_degradation.js"></script>

<script type="text/javascript" src="{$JS_URL}/yui/treeview/treeview.js"></script>
<script type="text/javascript">YAHOO.namespace("example.container");</script>

<script type="text/javascript" src="/js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>

{literal}
<script>
    var maxavatars = 12;
    function uploadAvatars() {
        var callback = {
            upload: uploadhandle
        }
        var oForm = YAHOO.util.Dom.get('edit_gallery');
        YAHOO.util.Connect.setForm(oForm, true);
        try {
            var cObj = YAHOO.util.Connect.asyncRequest('POST', oForm.action, callback);
        } catch (ex) {
            alert("Incorrect file name");
        }
    }

    function uploadhandle(oResponse) {
        //alert(oResponse.responseXML);
        xajax.processResponse(oResponse.responseXML);
    }

    function setSWFUploadParams()
    {
        setUploadURL("{/literal}{$CurrentGroup->getGroupPath('avatarupload/swf/1')}{literal}");
        setPostParams({{/literal}"SWFUploadID" : '{$SWFUploadID}', "group" : '{$CurrentGroup->getId()}'{literal}});
        setFileTypes("{/literal}{$IMAGES_EXT}{literal}", "{/literal}Images Files ({$IMAGES_EXT}){literal}");
        setFileSizeLimit("{/literal}{$AVATARS_SIZE_LIMIT/1024}{literal}");
        setQueuedLimit(maxavatars);
    }
</script>
<script type="text/javascript">
        var CODescriptionText="{/literal}{$CurrentGroup->getDescription()|strip|escape:'javascript'}{literal}";
        var COHeadlineText="{/literal}{$CurrentGroup->getHeadline()|strip|escape:'javascript'}{literal}";
        
		tinyMCE.init({
			mode : "textareas",
			theme : "headline",
			editor_selector : "headlineEditor"
		});

tinyMCE.init({
		mode : "textareas",
		theme : "zanby",
		editor_deselector : "headlineEditor",
		plugins : "advlink,advimage,contextmenu,paste",
		theme_zanby_toolbar_align : "left",
		/*content_css : "/css/ddpages/default.css",*/
	    plugi2n_insertdate_dateFormat : "%Y-%m-%d",
	    plugi2n_insertdate_timeFormat : "%H:%M:%S",

		paste_use_dialog : false,
		theme_zanby_resizing : true,
		theme_zanby_resize_horizontal : false,
		theme_zanby_link_targets : "_something=My somthing;_something2=My somthing2;_something3=My somthing3;",
		paste_auto_cleanup_on_paste : false,
		paste_convert_headers_to_strong : true,
		paste_strip_class_attributes : "all",
		paste_remove_spans : false,
		paste_remove_styles : false,
		relative_urls : false,
		inline_styles : true
	});



	function tinyMCEInit(id){//alert('init');
		return tinyMCE.execCommand( 'mceAddControl', true, id);
	}

	function tinyMCEDeinit(id){//alert('deinit');
		tinyMCE.execCommand( 'mceRemoveControl', true, id);
	}
</script>
{/literal}

<input type="hidden" id="DD_entity_id" name="DD_entity_id" value="{$entity_id}" />

<div>
	<div class="prInner prClr2">
		<h2 class="prFloatLeft">{$CurrentGroup->getName()|escape:html}</h2>
		{*<div class="prFloatRight">{linkbutton name="Exit template editor" link=$CurrentGroup->getGroupPath('summary')}</div>*}
	</div>
	{tab template="tabs1" active="edit"}
		{tabitem link=$CurrentGroup->getGroupPath('edit') name="edit"}{t}Layout and Content{/t}{/tabitem}
	{/tab}
	<div class="prInnerSmall12 prClr2">
		<!-- left column begin -->
		<div class="prFloatLeft">
			<p>
				<em><span class="prMarkRequired">*</span> {t}Drag and drop objects to arrange layout{/t}</em>
			</p>
			<p class="prInnerSmallTop">
				<em><span class="prMarkRequired">*</span> {t}Click object to edit{/t}</em>
			</p>

			<h3>{t}Customizable Content{/t}</h3>
			<a id="ddContentBlock" href="#null" title="Content Block" alt="Content Block"><img src="{$AppTheme->images}/buttons/btnContentBlock.gif" title="" /></a>
			<a id="ddGroupImage" href="#null" title="Picture" alt="Picture"><img src="{$AppTheme->images}/buttons/btnPicture.gif" title="" /></a>

			<h3>{t}Group Information{/t}</h3>
			<a id="ddGroupAvatar" href="#null" title="Group Profile Photo" alt="Group Profile Photo"><img src="{$AppTheme->images}/buttons/btnGroupAvatar.gif" title="" /></a>
			<a id="ddGroupHeadline" href="#null" title="Family Headline" alt="Family Headline"><img src="{$AppTheme->images}/buttons/btnGroupHeadline.gif" title="" /></a>
			<a id="ddGroupDescription" href="#null" title="Group Description" alt="Family Description"><img src="{$AppTheme->images}/buttons/btnGroupDescription.gif" title="" /></a>
			{if $CurrentGroup->getFamilyGroups()->getCount()}
				<a id="ddGroupFamilyIcons" href="#null" title="Family Icons" alt="Family Icons"><img src="{$AppTheme->images}/buttons/btnFamilyIcons.gif" title="" /></a>
			{/if}

			<h3>{t}Other Content{/t}</h3>
			<a id="ddGroupPhotos" href="#null" title="Group Photo Galleries" alt="Group Photo Galleries"><img src="{$AppTheme->images}/buttons/btnPhotoGalleries.gif" title="" /></a><a id="ddGroupLists" href="#null" title="Group Lists" alt="Group Lists"><img src="{$AppTheme->images}/buttons/btnGroupLists.gif" title="" /></a>



            <a id="ddFamilyVideoContentBlock" href="#null" title="Single Video" alt="Single Video"><img src="{$AppTheme->images}/buttons/Single-Story.gif" title="" /></a>
			<a id="ddFamilyTopVideos" href="#null" title="Top Videos" alt="Top Videos"><img src="{$AppTheme->images}/buttons/Top-Stories.gif" title="" /></a>

            <a id="ddMogulus" href="#null" title="LiveStream Video" alt="LiveStream Video"><img src="{$AppTheme->images}/buttons/btnEmbeddedVideo.gif" title="" /></a>
            <a id="ddIframe" href="#null" title="Iframe" alt="Iframe"><img src="{$AppTheme->images}/buttons/btnIFrame.gif" title="" /></a>
            <a id="ddScript" href="#null" title="Script" alt="Script"><img src="{$AppTheme->images}/buttons/btnScript.gif" title="" /></a>


			<a id="ddGroupDocuments" href="#null" title="Group Documents" alt="Group Documents"><img src="{$AppTheme->images}/buttons/btnGroupDocuments.gif" title="" /></a>
			<a id="ddGroupMembers" href="#null" title="Group Members" alt="Group Members"><img src="{$AppTheme->images}/buttons/btnGroupMembers.gif" title="" /></a>
			<a id="ddGroupEvents" href="#null" title="Events" alt="Events"><img src="{$AppTheme->images}/buttons/btnGroupEvents.gif" title="" /></a>
			<a id="ddFamilyDiscussions" href="#null" title="Group Discussions" alt="Group Discussions"><img src="{$AppTheme->images}/buttons/btnGroupDiscussions.gif" title="" /></a>
			<a id="ddRSSFeed" href="#null" title="RSS Feed" alt="RSS Feed"><img src="{$AppTheme->images}/buttons/btnRssFeed.gif" title="" /></a>

		</div>
		<!-- left column end -->
		{include file="content_objects/theme_css.tpl"}
		{include file="content_objects/compose_area_family.tpl"}
	</div>
</div>
