<?php
Warecorp::addTranslation("/modules/cms/xajax/action.blockEditPopupJS.php.xml");

/* temporary disapled $block = new Warecorp_CMS_Block_Item($id);
if($block->isExist)
{
    $arr = array(
        "id"        => $block->getId(),
        "page_id"   => $block->getPageId(),
        "content"   => $block->getContent(),
        "order"     => $block->getOrder()        
    );
}
else 
{
    $arr = array(
        "id"        => $id,
        "page_id"   => $pid,
        "content"   => "",
        "order"     => $ord
    );
}*/

$arr["container"] = $divid;

$this->view->arrData = $arr;
$content = $this->view->getContents("cms/blockEditPopupJs.tpl");
 
$objResponse = new xajaxResponse();

$popup_window = Warecorp_View_PopupWindow::getInstance();
$popup_window->title(Warecorp::t("Edit Block"));
$popup_window->content($content);
$popup_window->width(600)->height(450)->open($objResponse);

$objResponse->addScript("document.getElementById('edit_block_content_area').innerHTML = document.getElementById('".$divid."').innerHTML");
$objResponse->addScript(
"tinyMCE.init({
    // General options
    mode : 'exact',
    elements : 'edit_block_content_area',
    theme : 'advanced',
    plugins : 'safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups',

    force_br_newlines : true,
    forced_root_block : '',
    convert_fonts_to_spans : false, // disable converting all font elements to span elements

    // Theme options
    theme_advanced_buttons1 : 'cut,copy,paste,pastetext,pasteword,|,search,replace,|,styleselect,formatselect,fontselect,fontsizeselect',
    theme_advanced_buttons2 : 'undo,redo,|,justifyleft,justifycenter,justifyright,justifyfull,|,bold,italic,underline,strikethrough,|,bullist,numlist,outdent,indent,|,sub,sup,|,forecolor,backcolor',
    theme_advanced_buttons3 : 'link,unlink,anchor,|,image,|,hr,|,charmap,emotions,iespell,media,advhr,|,insertdate,inserttime,|,cleanup,removeformat',
    theme_advanced_buttons4 : 'tablecontrols,visualaid,|,preview,code',
    theme_advanced_toolbar_location : 'top',
    theme_advanced_toolbar_align : 'left',
    theme_advanced_statusbar_location : 'bottom',
    theme_advanced_resize_horizontal : false,
    theme_advanced_resizing : true,
    width : '99%',
    // Example word content CSS (should be your site CSS) this one removes paragraph margins
    content_css : 'css/word.css',

    // Drop lists for link/image/media/template dialogs
    template_external_list_url : 'lists/template_list.js',
    external_link_list_url : 'lists/link_list.js',
    external_image_list_url : 'lists/image_list.js',
    media_external_list_url : 'lists/media_list.js'
});");
