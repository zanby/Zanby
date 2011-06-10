<?php

/**
 * Smarty function
 * @param array $params
 * @param object $smarty
 * @return string
 * @author Serge Rybakov
 */
function smarty_function_showblock($params, &$smarty)
{
    $objPage = $smarty->get_template_vars("objPage");
    $arrBlocks = $smarty->get_template_vars("arrBlocks");
    $order = $params["order"];

    if(isset($arrBlocks[$order]))
    {
        $block_id      = $arrBlocks[$order]->getId();
        $block_content = $arrBlocks[$order]->getContent();
    }
    else
    {
        $block_id      = 0;
        $block_content = "";
    }
    $page_id = $objPage->getId();
    $timestamp = md5(rand());
    $contentDivId = "EBC_{$timestamp}_{$order}";

    $out = "\n<!-- Content block -->\n";
    // tinymce loader script **
    $tinymceInserted = $smarty->get_template_vars("tinymceInserted");
    if(empty($tinymceInserted))
    {
        $out .= "<script type='text/javascript'>";
        $out .= "  var head = document.getElementsByTagName('head')[0];";
        $out .= "  var script = document.createElement('script');";
        $out .= "  script.type= 'text/javascript';";
        $out .= "  script.src = '".JS_URL."/tinymceBlog/tiny_mce.js';";
        $out .= "  head.appendChild(script);";
        $out .= "</script>\n";
        $smarty->assign("tinymceInserted", true);
    }
    //** tinymce loader script

if (isset($params['user']) && ($params['user'] instanceof Warecorp_User)) {
$role = new Warecorp_Admin();
$role->loadById($params['user']->getId());
if($role->getRole()=='superadmin')
{
    $out .= "<div style='width: 99%; font-family: arial,verdana; font-size: 10px; color: blue; text-align: right;'>[&nbsp;<a href='javascript:void(0);' onclick='xajax_cms_showBlockEditPopupJS(\"$contentDivId\", $block_id, $page_id, $order); return false;'>edit</a>&nbsp;]</div>\n";
}
}

    $out .= "<div id='" . $contentDivId . "' style='border: 0px solid gray;'>\n";
    if(!empty($block_content))
    {
        $out .= $block_content;
    }
    else
    {
        $out .= "<font size='1'>There is no content available.</font>";
    }
    $out .= "\n</div>\n";
    $out .= "\n<!-- Content block -->\n";

    return $out;
}
