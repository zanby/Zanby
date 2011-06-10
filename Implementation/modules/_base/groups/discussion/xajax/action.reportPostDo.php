<?php
Warecorp::addTranslation('/modules/groups/discussion/xajax/action.reportPostDo.php.xml');

    $objResponse = new xajaxResponse();

    if ( floor($post_id) != 0 ) {
        $post = new Warecorp_DiscussionServer_Post($post_id);
        if ( null !== $post->getId() ) {
            if ( !$this->_page->_user->isAuthenticated() ) {
                //@todo надо добавить еще страницу, с которой пришел пользователь
                $_SESSION['login_return_page'] = $this->currentGroup->getGroupPath('topic').'topicid/'.$post->getTopicId().'/';
                $objResponse->addRedirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/login/');
            } else {
                if ( !$post->getDiscussionAccessManager()->canReportPost($post, $this->_page->_user->getId()) ) {
                    $objResponse->addAlert(Warecorp::t('You can not report this post. Contact please host of group.'));
                } else {
                    //@todo  Validate Content

                    $recipientsIds = array();
                    $recipients = array();
                    $groupId = $post->getTopic()->getDiscussion()->getGroupId();
                    $group = Warecorp_Group_Factory::loadById($groupId);
                    $hosts = $group->getMembers()->setMembersRole(array(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_HOST))->getList();
                    if ( sizeof($hosts) != 0 ) {
                       $recipients[] = $hosts[0];
                       $recipientsIds[] = $hosts[0]->getId();
                    }

                    $moderatorsList = new Warecorp_DiscussionServer_ModeratorList();
                    $moderators = $moderatorsList->findByDiscussionId($post->getTopic()->getDiscussionId());
                    if ( sizeof($moderators) != 0 ) {
                        foreach ( $moderators as $moderator ) {
                            $tmpUser = new Warecorp_User('id', $moderator);
                            if ( !in_array($tmpUser->getId(), $recipientsIds) ) {
                                $recipients[] = $tmpUser;
                            }
                        }
                    }
                    $moderators = $moderatorsList->findByGroupId($groupId);
                    if ( sizeof($moderators) != 0 ) {
                        foreach ( $moderators as $moderator ) {
                            $tmpUser = new Warecorp_User('id', $moderator);
                            if ( !in_array($tmpUser->getId(), $recipientsIds) ) {
                                $recipients[] = $tmpUser;
                            }
                        }
                    }

                    $post->sendPostReport( $this->_page->_user, $recipients );

                    $objResponse->addScript('popup_window.close();');
                    $objResponse->showAjaxAlert(Warecorp::t('Report was sent'));
                }
            }
        } else {
            $objResponse->addRedirect($this->currentGroup->getGroupPath('discussion'));
        }
    } else {
        $objResponse->addRedirect($this->currentGroup->getGroupPath('discussion'));
    }
