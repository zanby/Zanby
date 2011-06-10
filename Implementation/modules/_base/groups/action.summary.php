<?php
    Warecorp::addTranslation('/modules/groups/action.summary.php.xml');
    //  check access
    if ( !$this->_page->_user->isAuthenticated() ) {
        if ( $this->currentGroup instanceof Warecorp_Group_Simple ) {
            if ( $this->currentGroup->isPrivate() ) {
                $this->_redirectToLogin();
            }
        }
    }/** @author Roman Gabrusenok What this code to do? Commented!
         else {
        if ( $this->currentGroup instanceof Warecorp_Group_Simple ) {
        }
    }*/

    //if ( $this->currentGroup->isPrivate() && !$this->currentGroup->getMembers()->isMemberExistsAndApproved($this->_page->_user) ) {
    if (!Warecorp_Group_AccessManager::canViewSummary($this->currentGroup, $this->_page->_user)) {
        $this->_page->Xajax->registerUriFunction ("sendMessage", "/groups/messageSendToHost/");
        $this->_page->Xajax->registerUriFunction ("sendMessageDo", "/groups/messageSendToHost/");
        $this->view->bodyContent = 'groups/summary.hide.private.tpl';
        return;
    }

    $theme = Warecorp_CO_Theme_Item::loadThemeFromDB($this->currentGroup);
    $theme->prepareFonts();
    $this->view->theme = $theme;


    /*
    function getPrivileges($group, $user) //тестинг Warecorp_Group_AccessManager
    {
	    return array('Calendar'=>(int)Warecorp_Group_AccessManager::canUseCalendar($group, $user),
				    'Email'=>(int)Warecorp_Group_AccessManager::canUseEmail($group, $user),
				    'Photos'=>(int)Warecorp_Group_AccessManager::canUsePhotos($group, $user),
				    'Documents'=>(int)Warecorp_Group_AccessManager::canUseDocuments($group, $user),
				    'Lists'=>(int)Warecorp_Group_AccessManager::canUseLists($group, $user),
				    'Polls'=>(int)Warecorp_Group_AccessManager::canUsePolls($group, $user),
				    'ManageMembers'=>(int)Warecorp_Group_AccessManager::canUseManageMembers($group, $user),
				    'ManageGroupFamilies'=>(int)Warecorp_Group_AccessManager::canUseManageGroupFamilies($group, $user),
				    'ModifyLayout'=>(int)Warecorp_Group_AccessManager::canUseModifyLayout($group, $user),
				    );
    }
    */

    $this->_page->Xajax->registerUriFunction("doAttendeeEvent", "/groups/calendarEventAttendee/" );
    $this->_page->Xajax->registerUriFunction("doEventInvite", "/groups/calendarEventInvite/");
    $this->_page->Xajax->registerUriFunction("doEventOrganizerSendMessage", "/groups/calendarEventOrganizerSendMessage/" );
    $this->_page->Xajax->registerUriFunction("bookmarkit", "/ajax/bookmarkit/");
    $this->_page->Xajax->registerUriFunction("addbookmark", "/ajax/addbookmark/");
    $this->_page->Xajax->registerUriFunction("sendMessage", "/ajax/sendMessage/");
    $this->_page->Xajax->registerUriFunction("select_document", "/groups/documentSelect/");
    $this->_page->Xajax->registerUriFunction("sendMessageDo", "/ajax/sendMessageDo/");
    $this->_page->Xajax->registerUriFunction("setUpDownRank", "/ajax/setUpdownRank/");
    $this->_page->Xajax->registerUriFunction("setUpDownRankForCO", "/ajax/setUpdownRankForCO/");
    $this->_page->Xajax->registerUriFunction("get_block_content", "/groups/getBlockContent/");
    $this->_page->Xajax->registerUriFunction("get_block_content_light", "/groups/getBlockContentLight/");
    $this->_page->Xajax->registerUriFunction("get_block_content_preview", "/groups/getBlockContentPreview/");
    $this->_page->Xajax->registerUriFunction("viewCounter", "/ajax/viewsCounting/");
    $this->_page->Xajax->registerUriFunction("showVideoPopup", "/ajax/showVideoPopup/");
    $this->view->canEditLayout = Warecorp_Group_AccessManager::canUseModifyLayout($this->currentGroup, $this->_page->_user);


    /* komarovski @TODO check necessity of following verification */
    //if( true ){
   // if ($this->_page->_user->getMembershipPlan() == 'premium' && $this->_page->_user->getMembershipPlanEnabled()) {
	    //$this->view->assign(Warecorp_CO_Content::getAllBlocksHTML($this->_page, $this->currentGroup));

	    $contentBlocksHTML = Warecorp_CO_Content::getAllBlocksHTML($this->_page, $this->currentGroup, $this->_page->_user);

	    //if (!empty($contentBlocksHTML))
	    //{
            $this->view->contentBlocksHTML = $contentBlocksHTML;
	    //}


	//}

	$this->_page->setTitle('Summary');
	$this->view->currentUser = $this->_page->_user;

	if (LOCALE == "rss") {
        include_once (ENGINE_DIR."/rss.class.php");
	    if ($this->currentGroup instanceof Warecorp_Group_Simple) {
	        $membership_type = array("anyone" , "request" , "code");
	        $rss = new UniversalFeedCreator();
            $rss->encoding = 'utf-8';
            $rss->xslStyleSheet = "http://".$_SERVER['HTTP_HOST'].'/RSSStyle/rssstyle.xsl';
	        $rss->link = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	        $rss->title = SITE_NAME_AS_STRING." group";
	        $rss->description = SITE_NAME_AS_STRING." group details";
	        $image_item = new FeedImage();
	        $image_item->url = "http://" . $_SERVER['HTTP_HOST'] . $this->currentGroup->getAvatar()->getMedium();
	        $image_item->title = "Image";
	        $image_item->link = str_replace('/rss/','/en/', $this->currentGroup->getGroupPath('summary'));
	        $rss->image = $image_item;
	        $rss->copyright = COPYRIGHT;
	        $item = new FeedItem();
	        $item->title = $this->currentGroup->getName();
	        $item->link = str_replace('/rss/','/en/', $this->currentGroup->getGroupPath('summary'));
	        $item->description = "Description: " . $rss->iTrunc($this->currentGroup->getDescription(), 200) . "<br />";
	        $category = new Warecorp_Group_Category($this->currentGroup->getCategoryId());
	        $item->description .= "Category: " . $category->name . "<br />";
	        $item->description .= "Host: " . $this->currentGroup->getHost()->getLogin() . "<br />";
	        $item->description .= "Location: " . $this->currentGroup->getCity()->name . ", " . $this->currentGroup->getState()->name . ", " . $this->currentGroup->getCountry()->name . "<br />";
	        $tags = $this->currentGroup->getTagsList();
	        $item->description .= "Tags: ";
	        for ($i = 0; $i < count($tags); $i ++) {
	            if ($i === 0)
	                $item->description .= $tags[$i]->name; else
	                $item->description .= ", " . $tags[$i]->name;
	        }

    	    $membersListObj = $this->currentGroup->getMembers();
    	    $membersCount = $membersListObj->getCount();

	        $item->description .= "<br />";
	        $item->description .= Warecorp::t("Founded: ") . date("d.m.y", strtotime($this->currentGroup->getCreateDate())) . "<br />";
	        $item->description .= Warecorp::t("Members count: ") . $membersCount . "<br />";
	        $item->description .= Warecorp::t("Membership: ") . $membership_type[$this->currentGroup->getJoinMode()];
	        $rss->addItem($item);
            header("Content-Type: ".$rss->contentType."; charset=".$rss->encoding);
            print $rss->createFeed("RSS2.0");
            exit;
	    }
	    if ($this->currentGroup instanceof Warecorp_Group_Family) {
	        $membership_type = array("anyone" , "request" , "code");
	        $rss = new UniversalFeedCreator();
            $rss->encoding = 'utf-8';
            $rss->xslStyleSheet = "http://".$_SERVER['HTTP_HOST'].'/RSSStyle/rssstyle.xsl';
	        $rss->link = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	        $rss->title = SITE_NAME_AS_STRING." group";
	        $rss->description = SITE_NAME_AS_STRING." group details";
	        $image_item = new FeedImage();
	        $image_item->url = "http://" . $_SERVER['HTTP_HOST'] . $this->currentGroup->getAvatar()->getMedium();
	        $image_item->title = "Image";
	        $image_item->link = str_replace('/rss/','/en/', $this->currentGroup->getGroupPath('summary'));
	        $rss->image = $image_item;
	        $rss->copyright = COPYRIGHT;
	        $item = new FeedItem();
	        $item->title = $this->currentGroup->getName();
	        $item->link = str_replace('/rss/','/en/', $this->currentGroup->getGroupPath('summary'));
	        $item->description = Warecorp::t("Description: ") . $rss->iTrunc($this->currentGroup->getDescription(), 200) . "<br />";
	        $category = new Warecorp_Group_Category($this->currentGroup->getCategoryId());
	        $item->description .= Warecorp::t("Category: ") . $category->name . "<br />";
	        $item->description .= Warecorp::t("Host: ") . $this->currentGroup->getHost()->getLogin() . "<br />";
	        $item->description .= Warecorp::t("Location: ") . $this->currentGroup->getCity()->name . ", " . $this->currentGroup->getState()->name . ", " . $this->currentGroup->getCountry()->name . "<br />";
	        $tags = $this->currentGroup->getTagsList();
	        $item->description .= "Tags: ";
	        for ($i = 0; $i < count($tags); $i ++) {
	            if ($i === 0)
	                $item->description .= $tags[$i]->name; else
	                $item->description .= ", " . $tags[$i]->name;
	        }
	        $item->description .= "<br />";
	        $item->description .= Warecorp::t("Founded: ") . date("d.m.y", strtotime($this->currentGroup->getCreateDate())) . "<br />";
	        $item->description .= Warecorp::t("Members count: ") . $this->currentGroup->getGroups()->setTypes(array('simple', 'family'))->getCount() . "<br />";
	        $item->description .= Warecorp::t("Membership: ") . $membership_type[$this->currentGroup->getJoinMode()];
	        $rss->addItem($item);
            header("Content-Type: ".$rss->contentType."; charset=".$rss->encoding);
            print $rss->createFeed("RSS2.0");
            exit;
	    }
    }
//	if ($this->currentGroup->getGroupType() == "family") {
//	    $this->_page->breadcrumb = array_merge($this->_page->breadcrumb, array("Group families" => "/" . $this->_page->Locale . "/summary/" , $this->currentGroup->getName() => ""));
//	} else {
//	    //breadcrumb
//        $this->_page->breadcrumb = array_merge(
//        $this->_page->breadcrumb,
//        array($this->currentGroup->getCategory($this->currentGroup->getCategoryId())->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/world/1/",
//            $this->currentGroup->getCountry()->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/country/" .$this->currentGroup->getCountry()->id. "/",
//            $this->currentGroup->getState()->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/state/" .$this->currentGroup->getState()->id. "/",
//            $this->currentGroup->getCity()->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/city/" .$this->currentGroup->getCity()->id. "/",
//            $this->currentGroup->getName() => "")
//        );
//	}
    $this->view->bodyContent = 'groups/summary.tpl';

