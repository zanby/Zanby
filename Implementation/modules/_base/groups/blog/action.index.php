<?php

    Warecorp::addTranslation('/modules/groups/blog/action.index.php.xml');
    /**
     * check access
     */
    if ( !$this->_page->_user->isAuthenticated() && !$this->currentGroup->getDiscussionAccessManager()->canAnonymousViewGroupDiscussions($this->currentGroup->getId()) ) {
        $this->_redirectToLogin();
    }
    if ( !$this->currentGroup->getDiscussionAccessManager()->canViewGroupDiscussions($this->currentGroup->getId(), $this->_page->_user->getId()) ) {
        $this->_redirect($this->currentGroup->getDiscussionGroupHomePageLink());
    }

    $this->_page->Xajax->registerUriFunction("remove_blog_post", "/groups/blogRemove/");
    $this->_page->Xajax->registerUriFunction("change_list_size", "/groups/changeListSize/");

    /**
     * get blog discussion for current group
     */
    $discussionList = new Warecorp_DiscussionServer_DiscussionList();
    $discussion = $discussionList->findBlogByGroupId($this->currentGroup->getId());
    if ( null === $discussion ) $this->_redirect('/');

    if ( $this->_page->_user->isAuthenticated() ) {
        $settings = new Warecorp_DiscussionServer_User_Settings($this->_page->_user->getId());
        $listSize = $settings->getBlogPerPage();  
    } else {
        $listSize = ( isset($_SESSION['blog']['size']) ) ? $_SESSION['blog']['size'] : 10;
    }
    $this->params['page'] = (isset($this->params['page']))? $this->params['page'] : 1;

    $objTopics      = $discussion->getTopics();
    $lstTopicsLen   = $discussion->getTopicsCount();
    $lstTopics      = $objTopics->setListSize($listSize)->setCurrentPage($this->params['page'])->setOrder('zdt.created DESC')->findByDiscussionId($discussion->getId());

    if (LOCALE == "rss") {
        include_once (ENGINE_DIR."/rss.class.php");
	    if ($this->currentGroup instanceof Warecorp_Group_Simple) {
	        $membership_type = array("anyone" , "request" , "code");
	        $rss = new UniversalFeedCreator();
            $rss->encoding = 'utf-8';
            $rss->xslStyleSheet = "http://".$_SERVER['HTTP_HOST'].'/RSSStyle/rssstyle.xsl';

             $rss->link = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	        $rss->title = "Blog on " . SITE_NAME_AS_STRING;
	        $rss->description = SITE_NAME_AS_STRING." blog details";
	        $rss->copyright = COPYRIGHT;

            if ($discussion->hasTopics()) {
                foreach ($lstTopics as $topic) {
                    $item = new FeedItem();
                    $item->title = $topic->getSubject();
                    $item->link = str_replace('/rss/','/en/', $this->currentGroup->getGroupPath('blog.details')). "id/" . $topic->getId();
                    $item->description = Warecorp::t("Description: ") . $rss->iTrunc(strip_tags($topic->getTopicPost()->getContent()), 600) . "<br />";
                    $rss->addItem($item);
                }
                header("Content-Type: ".$rss->contentType."; charset=".$rss->encoding);
                print $rss->createFeed("RSS2.0");
                exit;
            }
	    }
	    if ($this->currentGroup instanceof Warecorp_Group_Family) {
	        $membership_type = array("anyone" , "request" , "code");
	        $rss = new UniversalFeedCreator();
            $rss->encoding = 'utf-8';
            $rss->xslStyleSheet = "http://".$_SERVER['HTTP_HOST'].'/RSSStyle/rssstyle.xsl';
	        $rss->link = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	        $rss->title = "Blog on " . SITE_NAME_AS_STRING;
	        $rss->description = SITE_NAME_AS_STRING." blog details";
	        $rss->copyright = COPYRIGHT;

            if ($discussion->hasTopics()) {
                foreach ($lstTopics as $topic) {
                    $item = new FeedItem();
                    $item->title = $topic->getSubject();
                    $item->link = str_replace('/rss/','/en/', $this->currentGroup->getGroupPath('blog.details')). "id/" . $topic->getId();
                    $item->description = Warecorp::t("Description: ") . $rss->iTrunc(strip_tags($topic->getTopicPost()->getContent()), 600) . "<br />";
                    $rss->addItem($item);
                }
                header("Content-Type: ".$rss->contentType."; charset=".$rss->encoding);
                print $rss->createFeed("RSS2.0");
                exit;
	        }
	    }
    }


    // Paging
    $url = $this->currentGroup->getGroupPath('blog', false);
    $P = new Warecorp_Common_PagingProduct($lstTopicsLen, $listSize, $url);

    $this->view->paging = $P->makeLinkPaging($this->params['page'], 'znbColored');
    $this->view->discussion = $discussion;
    $this->view->lstTopics = $lstTopics;
    $this->view->listSize = $listSize;
    $this->view->bodyContent = 'groups/blog/index.tpl';
