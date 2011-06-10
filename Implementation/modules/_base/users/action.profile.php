<?php
Warecorp::addTranslation("/modules/users/action.profile.php.xml");

if(LOCALE == "rss"){
    include_once(ENGINE_DIR."/rss.class.php");
    $rss = new UniversalFeedCreator();
    $rss->encoding = 'utf-8';
    $rss->xslStyleSheet = "http://".$_SERVER['HTTP_HOST'].'/RSSStyle/rssstyle.xsl';
    $rss->link = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    $rss->title = Warecorp::t(SITE_NAME_AS_STRING." member");
    $rss->description = Warecorp::t(SITE_NAME_AS_STRING." member details");
    $image_item = new FeedImage();
    $image_item->url = "http://".$_SERVER['HTTP_HOST'] . $this->currentUser->getAvatar()->getMedium();
    $image_item->title = Warecorp::t("Image");
    $image_item->link = "http://".$_SERVER['HTTP_HOST'];
    $rss->image = $image_item;
    $rss->copyright = COPYRIGHT;
    $item = new FeedItem();
    $item->title = $this->currentUser->getLogin();
    $item->link = "http://".$_SERVER['HTTP_HOST'] . "/en/profile/";
    $item->description = Warecorp::t("Gender: %s", $this->currentUser->getGender()) . "<br />";
    $item->description .= Warecorp::t("Real Name: %s %s", array($this->currentUser->getFirstname(), $this->currentUser->getLastname())) . "<br />";
    $item->description .= Warecorp::t("Age: %s",$this->currentUser->getAge()) . "<br />";
    $item->description .= Warecorp::t("Date joined: %s", date("d.m.y", strtotime($this->currentUser->getRegisterDate()))) . "<br />";
    $item->description .= Warecorp::t("Location: %s", $this->currentUser->getCity()->name);
    $item->description .= " ". $this->currentUser->getState()->name ;
    $item->description .= " " . $this->currentUser->getCountry()->name . "<br/>";
    $item->description .= Warecorp::t("Tags: %s", $this->currentUser->getTagHeadline()) . "<br/>";
    $groups = $this->currentUser->getGroups()->getList();
    $item->description .= Warecorp::t("Groups: ");
    foreach ($groups as $group){
        $item->description .= $group->getName() . ", ";
    }
    $str = $item->description;
    $str[strlen($str)-2] = ".";
    $item->description = $str;
    $rss->addItem($item);
    header("Content-Type: ".$rss->contentType."; charset=".$rss->encoding);
    print $rss->createFeed("RSS2.0");
    exit;
}

$theme = Warecorp_CO_Theme_Item::loadThemeFromDB($this->currentUser);
// $theme = new Warecorp_Theme;
$theme->prepareFonts();
$this->view->theme = $theme;
// print_r($theme);

$this->_page->Xajax->registerUriFunction("addToFriends", "/ajax/addToFriends/");
$this->_page->Xajax->registerUriFunction("addToFriendsDo", "/ajax/addToFriendsDo/");
$this->_page->Xajax->registerUriFunction("doAttendeeEvent", "/users/calendarEventAttendee/" ); 
$this->_page->Xajax->registerUriFunction("bookmarkit", "/ajax/bookmarkit/");
$this->_page->Xajax->registerUriFunction("addbookmark", "/ajax/addbookmark/");
$this->_page->Xajax->registerUriFunction("doEventInvite", "/users/calendarEventInvite/"); 
$this->_page->Xajax->registerUriFunction("doEventOrganizerSendMessage", "/users/calendarEventOrganizerSendMessage/" );

$this->_page->Xajax->registerUriFunction("sendMessage", "/ajax/sendMessage/");
$this->_page->Xajax->registerUriFunction("sendMessageDo", "/ajax/sendMessageDo/");

$this->_page->Xajax->registerUriFunction("viewCounter", "/ajax/viewsCounting/");
$this->_page->Xajax->registerUriFunction("showVideoPopup", "/ajax/showVideoPopup/");

$owner = $this->currentUser;
$user = $this->_page->_user;

if (!Warecorp_User_AccessManager::getInstance()->canViewProfile($owner, $user)) {
    $this->_redirect($this->currentUser->getUserPath('profiledefault'));
    exit;
}

$email = Warecorp_User_AccessManager::getInstance()->canContact($owner, $user);

$this->view->contentBlocksHTML = Warecorp_CO_Content::getAllBlocksHTML($this->_page, $this->currentUser, $user);

//breadcrumb
//if ($this->_page->breadcrumb != null){
//	$this->_page->breadcrumb = array_merge($this->_page->breadcrumb,
//	array($this->currentUser->getCity()->getState()->getCountry()->name => BASE_URL.'/'.$this->_page->Locale. "/users/index/view/allstates/country/" .$this->currentUser->getCity()->getState()->getCountry()->id. "/",
//	$this->currentUser->getCity()->getState()->name => BASE_URL. "/" .$this->_page->Locale. "/users/index/view/allcities/state/" .$this->currentUser->getCity()->getState()->id. "/",
//	$this->currentUser->getCity()->name => BASE_URL. "/" .$this->_page->Locale. "/users/search/preset/city/id/" .$this->currentUser->getCity()->id. "/")
//	);
//	$this->_page->breadcrumb += array($this->currentUser->getLogin() => null);// fix for numeric logins
//}

// This needs to be reviewed further
$friendsAssoc = $this->_page->_user->getId() ?  $this->currentUser->getFriendsList()->returnAsAssoc()->getList() : array();
$this->view->friends = $friendsAssoc;
$this->view->friendsAssoc = $friendsAssoc;

// makes only one call of friendStatus() (there are more than 1 checks)
$this->view->showEmail = $email;

$this->view->bodyContent = 'users/profile.tpl';
$this->view->Warecorp = new Warecorp();
