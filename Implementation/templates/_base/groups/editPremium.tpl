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
<div id="COContainer" style="display: none;">
	{include file="content_objects/COContainer/emptyBlock.tpl"}
</div>
<!-- Dynamic Theme CSS for CO -->
	{include file="content_objects/theme_css.tpl"}
<!-- CO Application -->
<script type="text/javascript" src="{$AppTheme->common->js}/CO/DDBlocksApplication.js"></script>
<script type="text/javascript" src="{$AppTheme->common->js}/CO/DDBlocksAdditionalFunctions.js"></script>
<script type="text/javascript" src="{$AppTheme->common->js}/CO/DDBlocksFactory.class.js"></script>
<script type="text/javascript" src="{$JS_URL}/content_objects/DDC.class.js"></script>
<script src="{$JS_URL}/customize.js" type="text/javascript"></script>
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
<script type="text/javascript" src="{$JS_URL}/content_objects/DDCElectedOfficial.class.js"></script>

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
    var avatarContainerId = '';
    function uploadAvatars(containerId) {
        avatarContainerId = containerId;
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
	function tinyMCEInit(id){
		return tinyMCE.execCommand( 'mceAddControl', true, id);
	}
	function tinyMCEDeinit(id){//alert('deinit');
		tinyMCE.execCommand( 'mceRemoveControl', true, id);
	}      
</script>
{/literal}
<input type="hidden" id="DD_entity_id" name="DD_entity_id" value="{$entity_id}" />
{include file="content_objects/compose_area.tpl"}