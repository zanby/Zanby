<?php

if ( isset($this->params['isPostBack']) ) {
    if (isset($this->params["bservice"])) $this->params["bservice"] = floor($this->params["bservice"]); else $this->params["bservice"] = "";
    if (!isset($this->params["bookmarl_url"])) $this->params["bookmarl_url"] = "";
    if (!isset($this->params["bookmarl_title"])) $this->params["bookmarl_title"] = "";

    $bookmarkListObj = new Warecorp_User_BookmarkService_List($this->_page->_user->getId());
    $bookmarkListObj->returnAsAssoc();
    $userBookmarkServicesList = $bookmarkListObj->getList();

    if ($this->params["bservice"] && in_array($this->params["bservice"], $userBookmarkServicesList) ){
        $bookmarkService = new Warecorp_Bookmark_Item($this->params["bservice"]);

        $url = $bookmarkService->url;
        $url = str_replace("%URL%", $this->params["bookmark_url"], $url);
        $url = str_replace("%TITLE%", $this->params["bookmark_title"], $url);

        print "<script>window.open('$url', '_blank');</script>";
        exit;
    }
}

$bookmarkListObj = new Warecorp_User_BookmarkService_List($this->_page->_user->getId());
$userBookmarkServicesList = $bookmarkListObj->getList();

$this->view->userBookmarkServicesList = $userBookmarkServicesList;

if (isset($_SERVER["HTTP_REFERER"])) $referer = $_SERVER["HTTP_REFERER"]; else $referer = SITE_NAME_AS_FULL_DOMAIN;
$this->view->bookmarkUrl = $referer;


$this->view->bodyContent = 'users/bookmarkit.tpl';
