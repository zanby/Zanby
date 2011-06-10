<?php
Warecorp::addTranslation('/modules/groups/discussion/xajax/action.savePostReply.php.xml');
    $objResponse = new xajaxResponse();
	if ( floor($post_id) != 0 ) {
        $post = new Warecorp_DiscussionServer_Post($post_id);
	    $_SESSION['Post'.$post_id] = $content;
        $objResponse->addRedirect(
            $this->currentGroup->getGroupPath('replytopic/topicid').$post->getTopicId().'/postid/'.$post->getId().'/'
        );
	} else {
        $objResponse->addAlert(Warecorp::t('Invalid parent post.'));
    }