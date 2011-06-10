<?php
Warecorp::addTranslation('/modules/groups/discussion/_action.update.posts.position.php.xml');

    //$this->_redirect($this->currentGroup->getGroupPath('discussion'));

    $db = Zend_Registry::get('DB');
    $query = $db->select()->from('zanby_discussion__topics', 'topic_id');
    $topics = $db->fetchCol($query);
    if ( $topics ) {
        foreach ( $topics as $topic ) {
            $query = $db->select();
            $query->from('zanby_discussion__posts', 'post_id')
                  ->where('topic_id = ?', $topic)
                  ->order('post_id ASC');
            $posts = $db->fetchAll($query);
            if ( $posts ) {
                $i = 1;
                foreach ( $posts as $post ) {
                    $data['position'] = $i;
                    $where = $db->quoteInto('post_id = ?', $post);
                    $rows_affected = $db->update('zanby_discussion__posts', $data, $where);
                    $i++;
                }
            }
        }
    }

    print Warecorp::t("Update Script OK");
    exit;