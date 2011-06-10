<?php
Warecorp::addTranslation('/modules/groups/discussion/_action.create.discussions.for.groups.php.xml');

    //$this->_redirect($this->currentGroup->getGroupPath('discussion'));


    $db = Zend_Registry::get('DB');
    /**
     * remove all information about discussions
     */
    $where = '1 = 1';
    $db->delete('zanby_discussion__user_post', $where);
    $db->delete('zanby_discussion__subscription_topics', $where);
    $db->delete('zanby_discussion__subscription_groups', $where);
    $db->delete('zanby_discussion__subscription_discussions', $where);
    $db->delete('zanby_discussion__obsolete_emails', $where);
    $db->delete('zanby_discussion__moderators', $where);
    $db->delete('zanby_discussion__group_settings', $where);
    $db->delete('zanby_discussion__group_publishing', $where);
    $db->delete('zanby_discussion__posts', $where);       
    $db->delete('zanby_discussion__posts', $where);
    $db->delete('zanby_discussion__topics', $where);
    $db->delete('zanby_discussion__discussions', $where);
    
    /**
     * create main discussions for groups
     */
    $query = $db->select()->from('zanby_groups__items', 'id');
    $res = $db->fetchCol($query);
    if ( sizeof($res) != 0 ) {
        $glist = new Warecorp_DiscussionServer_DiscussionList();
        foreach ( $res as $group_id ) {
            $tmpMain = $glist->findMainByGroupId($group_id);
            if ( !$tmpMain ) {
                $group = Warecorp_Group_Factory::loadById($group_id);
                if ( $group->getId() !== null ) {
                    $group->createMainDiscussion();
                }
            }
        }
    }
    
    $db = Zend_Registry::get('messageDB');
    $where = '1 = 1';
    $db->delete('zanby_subscription__discussion_delivery', $where);
    $db->delete('zanby_subscription__group_delivery', $where);
    $db->delete('zanby_subscription__topic_delivery', $where);
    $db->delete('zanby_subscription__discussion_messages', $where);    
    $db->delete('zanby_subscription__group_messages', $where);
    $db->delete('zanby_subscription__topic_messages', $where);
    $db->delete('zanby_subscription__messages', $where);
    
    
    print Warecorp::t("Update Script OK");
    exit;
    
