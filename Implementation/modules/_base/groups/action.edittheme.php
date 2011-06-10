<?php
Warecorp::addTranslation('/modules/groups/action.edittheme.php.xml');

$MaxDelta = 10;

$smarty_vars = array();

$smarty_vars['entity_id'] = $this->currentGroup->getId();

$_data = Warecorp_DDPages::loadFromDB($this->currentGroup->getId(), $this->currentGroup->EntityTypeId);

$smarty_vars['group_path'] = $this->currentGroup->getGroupPath();     

$smarty_vars['group_id'] = $this->currentGroup->getId();
$smarty_vars['title'] = $this->currentGroup->getName();
$smarty_vars["layout_content"] = '';

foreach ( $_data as &$_v) {
    $data = unserialize($_v);
    if (empty($data)) $data = array();
    usort($data, "Warecorp_DDPages::ddpages_sort_items");
    Warecorp_DDPages::setLevel($data, $MaxDelta);
    if ( sizeof($data) != 0 ) {
        foreach ( $data as $item ) {
            foreach (array('Top','Left','Bottom','Right','Width','Height','DeltaTargetTop','DeltaTargetLeft') as $key) {
                $item[$key]=floor($item[$key]/1.368421);  //1,3684210526315789473684210526316
            }
            switch ($item["ContentType"]) {
                case 'image': case 'photo1': case 'photo2': case 'photo3': case 'avatar':
                    $item["bgcolor"] = "#C0C0C0";
                    $item["content"] = "Photo";
                    break;
                default:
                    if (empty($item["bgcolor"])) $item["bgcolor"] = "#FFFFFF";
                    break;
            }
            $smarty_vars["item"] = $item;
            $this->view->assign($smarty_vars);
            $smarty_vars["layout_content"] .= $this->view->getContents("ddpages/ddpages_theme_item.tpl");
        }
    }
}

$smarty_vars["layout_content_height"] = floor($MaxDelta/1.368421);

$links[] = array('title' => 'Summary', 'link' => $this->currentGroup->getGroupPath());
$links[] = array('title' => 'Members', 'link' => $this->currentGroup->getGroupPath("members"));
// if (og_check_user_permition($user->uid, $group_id, 5, 3)) {
$links[] = array('title' => 'Messages', 'link' => $this->currentGroup->getGroupPath("messages"));
// }
// if (og_check_user_permition($user->uid, $group_id, 4, 3)) {
$links[] = array('title' => 'Photos', 'link' => $this->currentGroup->getGroupPath("photos"));
//}
//if (og_check_user_permition($user->uid, $group_id, 1, 3)) {
$links[] = array('title' => 'Lists', 'link' => $this->currentGroup->getGroupPath("list"));
//}
//if (og_check_user_permition($user->uid, $group_id, 2, 3)) {
$links[] = array('title' => 'Events', 'link' => $this->currentGroup->getGroupPath("calendar"));
//}
//if (og_check_user_permition($user->uid, $group_id, 3, 3)) {
$links[] = array('title' => 'Tags', 'link' => $this->currentGroup->getGroupPath("tags"));
//}
// if (og_check_user_permition($user->uid, $group_id, 3, 3)) {
$links[] = array('title' => 'Documents', 'link' => $this->currentGroup->getGroupPath("documents"));
//}
/*
foreach ($link as $id => $path) {
$link[$id]['path'] = l($path['title'],$path['link'], array('class' => 'nav_text_a'));
}
*/
$smarty_vars["links"] = $links;

$css_data = Warecorp_DDPages::loadThemeFromDB($this->currentGroup->getId(), $this->currentGroup->EntityTypeId);
$smarty_vars['css_text'] = (isset($css_data[0])?$css_data[0]:'');

$this->view->assign($smarty_vars);
$this->view->currentGroup = $this->currentGroup;

$this->_page->Xajax->registerUriFunction("ddpages_color_picker", "/groups/ddpagescolorpicker/");
$this->_page->Xajax->registerUriFunction("ddpages_color_picker_close", "/groups/ddpagescolorpickerclose/");
$this->_page->Xajax->registerUriFunction("ddpages_save_theme_css", "/groups/ddpagessavethemecss/");

$this->_page->Xajax->registerUriFunction("family_select_image", "/groups/familyselectimage/");
$this->_page->Xajax->registerUriFunction("family_select_image_close", "/groups/familyselectimageclose/");
$this->_page->Xajax->registerUriFunction("family_load_gallery", "/groups/familyloadgallery/");
$this->_page->Xajax->registerUriFunction("family_upload_image", "/groups/familyuploadimage/");
$this->_page->Xajax->registerUriFunction("family_upload_image_close", "/groups/familyuploadimageclose/");
$this->_page->Xajax->registerUriFunction("family_show_preview", "/groups/familyshowpreview/");

$this->view->menuContent = '';

$this->view->bodyContent = 'groups/edittheme.tpl';
/**/

