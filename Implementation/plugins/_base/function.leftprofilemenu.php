<?php
	Warecorp::addTranslation('/plugins/function.leftprofilemenu.php.xml');
    function smarty_function_leftprofilemenu($params, &$smarty)
    {
        /*
        $_content = $smarty->fetch("_design/menu/leftprofile_menu.tpl");
        return $_content;
        */

        $leftMenuActions = array(
            "myProfile"     =>array('profile','compose'),
            "myGroups"      =>array('groups'),
            "myDiscussions" =>array('discussion'),
            "myMessages"    =>array('messagelist','messageview','messagecompose','messagedelete','addressbook','importcontacts'),
            "myFriends"     =>array('friends','findfriends'),
            "myEvents"      =>array('calendar.list.view','calendar.month.view','calendar.event.view','calendar.event.create','calendar.event.edit','calendar.event.copy.do','calendar.action.confirm','calendar.event.apply.request',                   'calendar','calendarview','calendarviewevent','calendarexpired','calendaradd','calendaredit','calendarical','calendarconfirm','calendarsearch','calendarsearchindex'),
            "myDocuments"   =>array('documents'),
            "myLists"       =>array('lists','listsdelete','listsedit','listsadd','listsview','listssearch'),
            "myPhotos"      =>array('photos','gallery','gallerycreate','galleryedit','galleryview', 'photossearch'),
            "myVideos"     =>array('videos','videogallery','videogallerycreate','videogalleryedit','videogalleryview', 'videossearch'),
            "myAccount"     =>array('settings','avatars','privacy')
        );
        $currentUser    = $smarty->_tpl_vars['currentUser'];
        $user           = $smarty->_tpl_vars['user'];
        $ACTION_NAME    = strtolower($smarty->_tpl_vars['ACTION_NAME']);
        $MOD_NAME       = $smarty->_tpl_vars['MOD_NAME'];
        $out = '<div class="znUserMenu"><ul>';
        if ( $currentUser && $user && $currentUser->getId() == $user->getId() && $MOD_NAME == 'users' ) {
            $out .= '
            <li'. (in_array($ACTION_NAME,$leftMenuActions['myProfile']) ? ' class="znUM-current"' : ' class="znUM-first"') .'><a href="'.$user->getUserPath('profile').'">'.Warecorp::t("My Profile").'</a></li>
            <li'. (in_array($ACTION_NAME,$leftMenuActions['myFriends']) ? ' class="znUM-current"' : '') .'><a href="'.$user->getUserPath('friends').'">'.Warecorp::t("My Friends").'</a></li>
            <li'. (in_array($ACTION_NAME,$leftMenuActions['myDiscussions']) ? ' class="znUM-current"' : '') .'><a href="'.$user->getUserPath('discussion').'">'.Warecorp::t("My Discussions").'</a></li>
            <li'. (in_array($ACTION_NAME,$leftMenuActions['myMessages']) ? ' class="znUM-current"' : '') .'><a href="'.$user->getUserPath('messagelist').'">'.Warecorp::t("My Messages").'</a></li>
            <li'. (in_array($ACTION_NAME,$leftMenuActions['myGroups']) ? ' class="znUM-current"' : '') .'><a href="'.$user->getUserPath('groups').'">'.Warecorp::t("My Groups").'</a></li>
            <li'. (in_array($ACTION_NAME,$leftMenuActions['myEvents']) ? ' class="znUM-current"' : '') .'><a href="'.$user->getUserPath('calendar.list.view').'">'.Warecorp::t("My Events").'</a></li>
            <li'. (in_array($ACTION_NAME,$leftMenuActions['myVideos']) ? ' class="znUM-current"' : '') .'><a href="'.$user->getUserPath('videos').'">'.Warecorp::t("My Videos").'</a></li>
            <li'. (in_array($ACTION_NAME,$leftMenuActions['myDocuments']) ? ' class="znUM-current"' : '') .'><a href="'.$user->getUserPath('documents').'">'.Warecorp::t("My Documents").'</a></li>
            <li'. (in_array($ACTION_NAME,$leftMenuActions['myLists']) ? ' class="znUM-current"' : '') .'><a href="'.$user->getUserPath('lists').'">'.Warecorp::t("My Lists").'</a></li>
            <li'. (in_array($ACTION_NAME,$leftMenuActions['myPhotos']) ? ' class="znUM-current"' : '') .'><a href="'.$user->getUserPath('photos').'">'.Warecorp::t("My Photos").'</a></li>
            <li'. (in_array($ACTION_NAME,$leftMenuActions['myAccount']) ? ' class="znUM-current"' : ' class="znUM-last"') .'><a href="'.$user->getUserPath('settings').'">'.Warecorp::t("My Account").'</a></li>
            ';
        } else {
            $out .= '
            <li class="znUM-first"><a href="'.$user->getUserPath('profile').'">'.Warecorp::t("My Profile").'</a></li>
            <li><a href="'.$user->getUserPath('friends').'">'.Warecorp::t("My Friends").'</a></li>
            <li><a href="'.$user->getUserPath('discussion').'">'.Warecorp::t("My Discussions").'</a></li>
            <li><a href="'.$user->getUserPath('messagelist').'">'.Warecorp::t("My Messages").'</a></li>
            <li><a href="'.$user->getUserPath('groups').'">'.Warecorp::t("My Groups").'</a></li>
            <li><a href="'.$user->getUserPath('calendar.list.view').'">'.Warecorp::t("My Events").'</a></li>
            <li><a href="'.$user->getUserPath('videos').'">'.Warecorp::t("My Videos").'</a></li>
            <li><a href="'.$user->getUserPath('documents').'">'.Warecorp::t("My Documents").'</a></li>
            <li><a href="'.$user->getUserPath('lists').'">'.Warecorp::t("My Lists").'</a></li>
            <li><a href="'.$user->getUserPath('photos').'">'.Warecorp::t("My Photos").'</a></li>
            <li class="znUM-last"><a href="'.$user->getUserPath('settings').'">'.Warecorp::t("My Account").'My Account</a></li>
            ';
        }
        $out .= '</ul></div>';
        return $out;
    }
?>