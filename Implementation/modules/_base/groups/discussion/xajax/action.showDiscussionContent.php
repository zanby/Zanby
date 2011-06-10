<?php
Warecorp::addTranslation('/modules/groups/discussion/xajax/action.showDiscussionContent.php.xml');
    $objResponse = new xajaxResponse();
    if ( $mode ) {
        if ( !isset($_SESSION['DiscussionServer']) ) $_SESSION['DiscussionServer'] = array();
        $_SESSION['DiscussionServer']['openDiscussions'][$discussion_id] = true;
    } else {
        if ( isset($_SESSION['DiscussionServer']['openDiscussions'][$discussion_id]) ) {
            unset($_SESSION['DiscussionServer']['openDiscussions'][$discussion_id]);
        }
    }