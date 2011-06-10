<?php

/**
 * Smarty form function
 * @param array $params
 * @param object $smarty
 * @return string
 * @author Serge Rybakov
 */
function smarty_function_showblockbyid($params, &$smarty)
{
    $objBlock = new Warecorp_CMS_Block_Item($params["id"]);
    if($objBlock->isExist)
    {
        $block_id       = $objBlock->getId();
        $block_content  = $objBlock->getContent();
        $order          = $objBlock->getOrder();
        $page_id        = $objBlock->getPageId();
    }
    else
    {
        $block_id      = $params["id"];
        $block_content = "";
        $order         = 0;
        $page_id       = 0;
    }
    $timestamp = md5(rand());
    $contentDivId = "EBC_{$timestamp}_{$block_id}";

    $out = "";
    if($objBlock->isExist)
    {
        $out .= "\n<!-- Content block -->\n";
        // tinymce loader script **
        $tinymceInserted = $smarty->get_template_vars("tinymceInserted");
        if(empty($tinymceInserted))
        {
            $out .= "<script type='text/javascript'>";
            $out .= "    var head = document.getElementsByTagName('head')[0];";
            $out .= "    var script = document.createElement('script');";
            $out .= "    script.type= 'text/javascript';";
            $out .= "    script.src = '".JS_URL."/tinymceBlog/tiny_mce.js';";
            $out .= "    head.appendChild(script);";
            $out .= "</script>";
            $smarty->assign("tinymceInserted", true);
        }
        //** tinymce loader script
        //$out .= "<div class='editblock'><a href='".BASE_URL."/".LOCALE."/cms/blockedit/id/$block_id/uid/$contentDivId/ord/$order/pid/$page_id' target='block_editor'>[ edit ]</a></div>\n";
if (isset($params['user']) && ($params['user'] instanceof Warecorp_User)) {
$role = new Warecorp_Admin();
$role->loadById($params['user']->getId());
if($role->getRole()=='superadmin')
{
        $out .= "<div style='font-family: arial,verdana; font-size: 12px; color: blue; text-align: right;'>[&nbsp;<a href='javascript:void(0);' onclick='xajax_cms_showBlockEditPopupJS(\"$contentDivId\", $block_id, $page_id, $order); return false;'>edit</a>&nbsp;]</div>\n";
}
}


        $out .= "<div id='" . $contentDivId . "' style='border: 0px solid gray;'>\n";
        $out .= $block_content;
        $out .= "\n</div>\n";
        $out .= "\n<!-- Content block -->\n";
    }
    else
    {
        // if block with given ID does not exist in DB, it should not be displayed, i think ))
        /*$out .= "\n<!-- Content block -->\n";
        $out .= "<div id='" . $contentDivId . "' class='DivShowBlock'>\n";
        $out .= "<font size='1'>Sorry, this block does not exist and not available for editing.</font>";
        $out .= "\n</div>\n";
        $out .= "\n<!-- Content block -->\n";*/
    }
    
    return $out;
}

