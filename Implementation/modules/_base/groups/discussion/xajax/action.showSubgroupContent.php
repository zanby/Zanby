<?php
Warecorp::addTranslation('/modules/groups/discussion/xajax/action.showSubgroupContent.php.xml');
    $objResponse = new xajaxResponse();
    if ( $mode ) {
        if ( !isset($_SESSION['DiscussionServer']) ) $_SESSION['DiscussionServer'] = array();
        $_SESSION['DiscussionServer']['openSubgroups'][$this->currentGroup->getId()][$group_id] = true;
    } else {
        if ( isset($_SESSION['DiscussionServer']['openSubgroups'][$this->currentGroup->getId()][$group_id]) ) {
            unset($_SESSION['DiscussionServer']['openSubgroups'][$this->currentGroup->getId()][$group_id]);
        }
    }