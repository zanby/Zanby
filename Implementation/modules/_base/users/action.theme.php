<?php
 Warecorp::addTranslation('/modules/users/action.theme.xml');

$MaxDelta = 10;

$smarty_vars = array();

$smarty_vars['entity_id'] = $this->currentUser->getId();

$_data = Warecorp_DDPages::loadFromDB($this->currentUser->getId(), $this->currentUser->EntityTypeId);

$smarty_vars['group_path'] = $this->currentUser->getUserPath();        //group_get_group_path($group_id);

$smarty_vars['group_id'] = $this->currentUser->getId();
$smarty_vars['title'] = $this->currentUser->getLogin();
$smarty_vars["layout_content"] = '';

foreach ( $_data as &$_v) {
    $data = unserialize($_v);
    usort($data, "Warecorp_DDPages::ddpages_sort_items");
    //Warecorp_DDPages::setLevel($data, $MaxDelta);
    
    
        
    if ( sizeof($data) != 0 ) {
        foreach ( $data as $item ) {
           // foreach (array('Top','Left','Bottom','Right','Width','Height','DeltaTargetTop','DeltaTargetLeft') as $key) {
           //     $item[$key]=floor($item[$key]/1.368421);  //1,3684210526315789473684210526316
           // }
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
            $smarty_vars["layout_content"] .= $this->view->getContents("content_objects/theme/ddpages_theme_item.tpl");
        }
    }
    
    
    
    
}

$smarty_vars["layout_content_height"] = 300;//floor($MaxDelta/1.368421);

$links[] = array('title' => Warecorp::t('Summary'), 'link' => $this->currentUser->getUserPath());
$links[] = array('title' => Warecorp::t('Members'), 'link' => $this->currentUser->getUserPath("members"));
// if (og_check_user_permition($user->uid, $group_id, 5, 3)) {
$links[] = array('title' => Warecorp::t('Messages'), 'link' => $this->currentUser->getUserPath("messages"));
// }
// if (og_check_user_permition($user->uid, $group_id, 4, 3)) {
$links[] = array('title' => Warecorp::t('Photos'), 'link' => $this->currentUser->getUserPath("photos"));
//}
//if (og_check_user_permition($user->uid, $group_id, 1, 3)) {
$links[] = array('title' => Warecorp::t('Lists'), 'link' => $this->currentUser->getUserPath("list"));
//}
//if (og_check_user_permition($user->uid, $group_id, 2, 3)) {
$links[] = array('title' => Warecorp::t('Events'), 'link' => $this->currentUser->getUserPath("calendar"));
//}
//if (og_check_user_permition($user->uid, $group_id, 3, 3)) {
$links[] = array('title' => Warecorp::t('Tags'), 'link' => $this->currentUser->getUserPath("tags"));
//}
// if (og_check_user_permition($user->uid, $group_id, 3, 3)) {
$links[] = array('title' => Warecorp::t('Documents'), 'link' => $this->currentUser->getUserPath("documents"));
//}
/*
foreach ($link as $id => $path) {
$link[$id]['path'] = l($path['title'],$path['link'], array('class' => 'nav_text_a'));
}
*/
$smarty_vars["links"] = $links;

$css_data = Warecorp_DDPages::loadThemeFromDB($this->currentUser->getId(), $this->currentUser->EntityTypeId);
$smarty_vars['css_text'] = (isset($css_data[0])?$css_data[0]:'');

$this->view->assign($smarty_vars);
$this->view->currentUser = $this->currentUser;

$this->_page->Xajax->registerUriFunction("ddpages_color_picker", "/users/ddpagescolorpicker/");
$this->_page->Xajax->registerUriFunction("ddpages_color_picker_close", "/users/ddpagescolorpickerclose/");
$this->_page->Xajax->registerUriFunction("ddpages_save_theme_css", "/users/ddpagessavethemecss/");

$this->_page->Xajax->registerUriFunction("select_image", "/users/selectimage/");
$this->_page->Xajax->registerUriFunction("select_image_close", "/users/selectimageclose/");
$this->_page->Xajax->registerUriFunction("load_gallery", "/users/loadgallery/");
$this->_page->Xajax->registerUriFunction("upload_image", "/users/uploadimage/");
$this->_page->Xajax->registerUriFunction("upload_image_close", "/users/uploadimageclose/");
$this->_page->Xajax->registerUriFunction("show_preview", "/users/showpreview/");

$this->view->menuContent = '';

$this->view->setLayout('main_wide.tpl');
$this->view->bodyContent = 'users/theme.tpl';
