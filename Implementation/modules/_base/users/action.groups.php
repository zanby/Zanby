<?php
/*    if (LOCALE == "rss") {
        include_once (ENGINE_DIR."/rss.class.php");
        $rss = new UniversalFeedCreator();
        $rss->encoding = 'utf-8';
        $rss->xslStyleSheet = "http://".$_SERVER['HTTP_HOST'].'/RSSStyle/rssstyle.xsl';         
        $rss->title         = $this->currentUser->getLogin() . " groups ";
        $rss->description   = $this->currentUser->getLogin() . " group list ";
        $rss->link          = "http://".$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $rss->copyright     = "Copyright &copy; 2007, Zanby";
        $groups             = $this->currentUser->getGroups()->setTypes(array(Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE, Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE))->getList();
        $membership_type    = array("anyone" , "request" , "code");
        foreach ($groups as $group) {
            $item = new FeedItem();
            $item->title        = $group->getName();
            $item->link         = str_replace('/rss/','/en/', $group->getGroupPath('summary'));
            $item->description  = "Description: " . $rss->iTrunc($group->getDescription(), 200) . "<br/>";
            $category = new Warecorp_Group_Category($group->getCategoryId());
            $item->description .= "Category: " . $category->name . "<br/>";
            $item->description .= "Host: " . $group->getHost()->getLogin() . "<br/>";
            $item->description .= "Location: " . $group->getCity()->name . ", " . $group->getState()->name . ", " . $group->getCountry()->name . "<br/>";
            $tags = $group->getTagsList();
            $item->description .= "Tags:";
            foreach ($tags as $tag) {
                $item->description .= " " . $tag->name;
            }
            $membersListObj = $group->getMembers();
            $membersCount = $membersListObj->getCount();
            $item->description .= "<br />";
            $item->description .= "Founded: " . date("d.m.y", strtotime($group->getCreateDate())) . "<br/>";
            $item->description .= "Members: " . $membersCount . "<br />";
            $item->description .= "Membership: " . $membership_type[$group->getJoinMode()];
            $rss->addItem($item);
        }
        header("Content-Type: ".$rss->contentType."; charset=".$rss->encoding);
        print $rss->createFeed("RSS2.0");
        exit; 
    } */

    //FIXME определить , какая таймзона является дефолтовой 
    //@todo Когда пользователь просматривает календарь другого пользователя в какой таймзоне должны быть показаны события, в таймзоне того, 
    //      кто просматривает, или в той, чей это профайл?
    $currentTimezone = ( null !== $this->_page->_user->getId() && null !== $this->_page->_user->getTimezone() ) ? $this->_page->_user->getTimezone() : 'UTC';

    $objPostsList = new Warecorp_DiscussionServer_PostList();
    $this->view->objPostsList = $objPostsList;

    $this->_page->Xajax->registerUriFunction ( "resignFromGroup", "/ajax/resignFromGroup/" ) ;
    $this->_page->Xajax->registerUriFunction ( "resignFromGroupDo", "/ajax/resignFromGroupDo/" ) ;
    
    $this->view->MEMBER_STATUS_PENDING = Warecorp_Group_Enum_MemberStatus::MEMBER_STATUS_PENDING;
    $this->view->bodyContent = 'users/groups.tpl';

    $objEventList = new Warecorp_ICal_Event_List();
    $objEventList->setTimeZone($currentTimezone);
    $this->view->objEventList = $objEventList;
