<?php

    //ini_set('memory_limit', '512M');
    //ini_set('max_execution_time', 0);
    //komarovski
    if (!Warecorp_Group_AccessManager::canViewMembers($this->currentGroup, $this->_page->_user)) {
        $this->_redirect($this->currentGroup->getGroupPath('summary'));
    }

    //@TODO @auhor komarovski remove commented blocks of code with incorrect define of host priveleges
    Warecorp::addTranslation('/modules/groups/action.members.php.xml');

    $this->_page->Xajax->registerUriFunction("addToFriends", "/ajax/addToFriends/");
    $this->_page->Xajax->registerUriFunction("addToFriendsDo", "/ajax/addToFriendsDo/");
    $this->_page->Xajax->registerUriFunction("sendMessage", "/ajax/sendMessage/");
    $this->_page->Xajax->registerUriFunction("sendMessageDo", "/ajax/sendMessageDo/");
    $this->_page->Xajax->registerUriFunction("removeMember", "/groups/removeMember/");
    $this->_page->Xajax->registerUriFunction("declineMember", "/groups/declineMember/");
    $this->_page->Xajax->registerUriFunction("invitemembers", "/groups/invitemembers/");
    $this->_page->Xajax->registerUriFunction("bookmarkit", "/ajax/bookmarkit/");
    $this->_page->Xajax->registerUriFunction("addbookmark", "/ajax/addbookmark/");

    $items_per_page = 10;
    $this->params['mode']   = ( !isset($this->params['mode']) || !in_array($this->params['mode'], array('approved', 'pending', 'request')) ) ? 'approved' : $this->params['mode'];
    $user                   = $this->_page->_user;
    $order                  = isset($this->params['order']) ? $this->params['order'] : 'name';
    $direction              = (isset($this->params['order']) && isset($this->params['direction'])) ? $this->params['direction'] : 'asc';
    $_SESSION['login_return_page'] = $this->currentGroup->getGroupPath('members');

    /**
     * handler for family group
     */
    if( $this->currentGroup->getGroupType() == "family" ){
        $sort = (!isset($this->params['sort']) || !preg_match("/^[a-zA-Z]$/", $this->params['sort'])) ? null : strtoupper($this->params['sort']);

        $membersList = $this->currentGroup->getGroups()->setTypes(array('simple', 'family'));
        //@komarovski $isHostPrivileges   = ($membersList->isCoowner($user)) || ($this->currentGroup->getMembers()->isHost($user));
    //    $showPending        = $isHostPrivileges && ($this->currentGroup->getJoinMode() == 1);

        //vsv
        $privileges = $this->currentGroup->getPrivileges();
    //    $memberPrivileges = false;
        $memberPrivileges = Warecorp_Group_AccessManager::canUseManageMembers($this->currentGroup, $user->getId());
    /*    if($this->currentGroup->getMembers()->isMemberExists($user->getId()))
        {
            if(2==$privileges->getManageMembers()) {
                $memberPrivileges = true;
            } elseif (3==$privileges->getManageMembers()) {
                $memberPrivileges = $privileges->getUsersListByTool('gpManageMembers')->isExist($user);
            }
        }
    */
        $showPending = ($this->userHasHostPriveleges || $memberPrivileges) && ($this->currentGroup->getJoinMode() == 1);

        if ( !($showPending == true) ) $this->params['mode'] = 'approved';
        //if ($this->params['mode'] == 'request') $this->params['mode'] = 'pending';
        $membersList->setStatus($this->params['mode']=='approved'?'active':'pending');


        /**
         * set order
         */
        switch ($order) {
            case 'name'    :   $order_path = 'zgi.name '.$direction;       break;
            case 'request' :   $order_path = 'zgi.name '.$direction;       break;
            default        :
                $order = 'name';
                $order_path = 'zgi.name '.$direction;
                break;
        }
        $membersList->setOrder($order_path);

        /**
         * build hierarchy
         */
        $h = Warecorp_Group_Hierarchy_Factory::create();
        $h->setGroupId($this->currentGroup->getId());

        $r = $h->getHierarchyList();
        $curr_hid = (isset($this->params['hid'])) ? $this->params['hid'] : (sizeof($r) != 0 ? $r[0]->getId() : null);
        if ($curr_hid !== null) $h->loadById($curr_hid);

        $tree = $h->getHierarchyTree();
        $allowed_letters = $h->getHierarchyLetters();

        $tree = $h->getHierarchyTree($sort);
        $this->view->globalCategories = Warecorp_Group_Hierarchy::prepareTreeToPreview($h, $tree, $sort);

        $this->view->totalGroupsCount = $membersList->getCount();
        $this->view->totalMembersCount = $this->currentGroup->getMembers()->getCount();
        $this->view->sort = $sort;
        $this->view->tree = $tree;
        $this->view->allowed_letters = $allowed_letters;
        $this->view->hierarchyList = $r;
        $this->view->curr_hid = $curr_hid;
        $this->view->current_hierarchy = $h;

           /**
            * do any action from members page
            * remove - from approved members page; accept, decline, acceptall, declineall - from pending members page
            */
        if (
           isset($this->params['remove']) || isset($this->params['accept']) ||
           isset($this->params['decline']) || isset($this->params['acceptall']) ||
           isset($this->params['declineall'])
           )
        {
            $order    = (isset($this->params['order']) && isset($this->params['direction']))?'order/'.$this->params['order'].'/direction/'.$this->params['direction'].'/':'';
            $page     = isset($this->params['page'])?'page/'.$this->params['page'].'/':'';
            $mode     = $this->params['mode'];
            $id       = isset($this->params['id'])?'id/'.$this->params['id'].'/':'';

            if ( $mode == 'approved' ) {
                 if ( isset($this->params['remove']) ) {
                    if ( Warecorp_Group_Standard::isGroupExists('id',$this->params['remove']) ) {
                        $groupToRemove = Warecorp_Group_Factory::loadById(floor($this->params['remove']));
    //			    	if (($isHostPrivileges && !$membersList->isCoowner($groupToRemove)) || ($this->currentGroup->getMembers()->isHost($this->_page->_user))) {
                        if(Warecorp_Group_AccessManager::canUseManageMembers($this->currentGroup, $user->getId())) {
                            $membersList->removeGroup($groupToRemove->getId());
                        }
                    }
                 }
            } else {
                $list = $membersList->getList();
                if (isset($this->params['acceptall'])) {
                    foreach ($list as $group) {				//принять всех в фэмили
                        $membersList->approveGroup($group);
                        $this->currentGroup->deleteRequestRelation($group);
                    }
                }
                if (isset($this->params['declineall'])) {
                    foreach ($list as $group) {				//отказать всем
                        $this->currentGroup->deleteRequestRelation($group);
                        $membersList->removeGroup($group->getId());
                    }
                }
                if (isset($this->params['accept'])) {
                    if ($membersList->isGroupExistAndPending($this->params['accept'])) { //принять группу
                        $group = Warecorp_Group_Factory::loadById($this->params['accept']);
                        $this->currentGroup->deleteRequestRelation($group);
                        $membersList->approveGroup($this->params['accept']);
                    } else $this->_redirect($this->currentGroup->getGroupPath('members').'mode/'.$this->params['mode'].'/');
                }
                  if (isset($this->params['decline'])) {
                    if ($membersList->isGroupExistAndPending($this->params['decline'])) { //отказать
                        $group = Warecorp_Group_Factory::loadById($this->params['decline']);
                          $this->currentGroup->deleteRequestRelation($group);
                        $membersList->removeGroup($this->params['decline']);
                    } else $this->_redirect($this->currentGroup->getGroupPath('members').'mode/'.$this->params['mode'].'/');
                }
            }
            $this->_redirect($this->currentGroup->getGroupPath('members').'mode/'.$this->params['mode'].'/'.$order.$page.$id);
        }

        if ($this->params['mode'] == 'approved') {
            $this->params['page'] = (isset($this->params['page']))? $this->params['page'] : 1;
            $membersList->setCurrentPage(intval($this->params['page']));
            $membersList->setListSize($items_per_page);
        } elseif ($this->params['mode'] == 'pending') {
            $pendingMembersList = $membersList->getList();
            if ( $order == 'request' ) {
                Zend_Registry::set('CG', $this->currentGroup);
                if ( $direction == 'asc' ) {
                    usort($pendingMembersList, 'sortPendingMembersByRequestDateAsc');
                } else {
                    usort($pendingMembersList, 'sortPendingMembersByRequestDateDesc');
                }
            }
            $this->view->pendingMembersList = $pendingMembersList;
        } elseif ($this->params['mode'] == 'request') { //вывод по одному
            $items_per_page = 1;
            $memlist = $membersList->returnAsAssoc()->setAssocKey('zgi.id')->setAssocValue('zgi.id')->getList();
            $memlist1 = array_values($memlist);
            $memlist2 = array_flip($memlist1);
            $prev_id = (array_key_exists($memlist2[(int)$this->params['id']] - 1, $memlist1))?$memlist1[$memlist2[(int)$this->params['id']] - 1]:null;
            $next_id = (array_key_exists($memlist2[(int)$this->params['id']] + 1, $memlist1))?$memlist1[$memlist2[(int)$this->params['id']] + 1]:null;
            $page = $memlist2[(int)$this->params['id']] + 1;
            $this->view->prevId = $prev_id;
            $this->view->nextId = $next_id;
            $membersList->returnAsAssoc(false);

            $page = !empty($page)? $page : 1;
            $membersList->setCurrentPage(intval($page));
            $membersList->setListSize($items_per_page);
       }
    }
    /**
     * handler for simple group
     */
    else {
        $membersList = $this->currentGroup->getMembers();
        //@komarovski $isHostPrivileges = ($membersList->isCohost($user)) || ($membersList->isHost($user) ) ;

        $privileges = $this->currentGroup->getPrivileges();
        $memberPrivileges = false;
        if( $this->currentGroup->getMembers()->isMemberExists($user->getId()) ){
            if( 0 == $privileges->getManageMembers() ) {
                $memberPrivileges = true;
            } elseif ( 2 == $privileges->getManageMembers() ) {
                $memberPrivileges = $privileges->getUsersListByTool('gpManageMembers')->isExist($user);
            }
        }

        $showPending = ($this->userHasHostPriveleges || $memberPrivileges) && ($this->currentGroup->getJoinMode() == 1);

        if ( !($showPending == true) ) $this->params['mode'] = 'approved';
        $membersList->setMembersStatus( $this->params['mode'] == 'request' ? 'pending' : $this->params['mode'] );

        /**
         * do any action from members page
         * remove - from approved members page; accept, decline, acceptall, declineall - from pending members page
         */
        if (
           isset($this->params['remove']) || isset($this->params['accept']) ||
           isset($this->params['decline']) || isset($this->params['acceptall']) ||
           isset($this->params['declineall'])
        )
        {
            $order = (isset($this->params['order']) && isset($this->params['direction']))?'order/'.$this->params['order'].'/direction/'.$this->params['direction'].'/':'';
            $page = isset($this->params['page'])?'page/'.$this->params['page'].'/':'';
            $mode = $this->params['mode'];
            $id = isset($this->params['id'])?'id/'.$this->params['id'].'/':'';

            if ($mode == 'approved') {
                 if (isset($this->params['remove']))
                    if (Warecorp_User::isUserExists('id', $this->params['remove'])) {
                        $userToRemove = floor($this->params['remove']);
                        if (($membersList->isHost($user) && $userToRemove != $user->getId()) ||
                            ($membersList->isCohost($user) && $userToRemove != $user->getId() &&
                             !$membersList->isCohost($userToRemove) && !$membersList->isHost($userToRemove))) {
                             $membersList->removeMember($userToRemove);
                        }
                    }
            } else {
                $list = $membersList->getList();
                if (isset($this->params['acceptall'])) {
                    foreach ($list as $member) {
                        $membersList->approveMember($member);
                        $this->currentGroup->deleteRequestRelation($member);
                    }
                }
                if (isset($this->params['declineall'])) {
                    foreach ($list as $member) {
                        $this->currentGroup->deleteRequestRelation($member);
                        $membersList->removeMember($member);
                    }
                }
                if (isset($this->params['accept'])) {
                    if ($membersList->isMemberExistsAndPending($this->params['accept'])) {
                        $u = new Warecorp_User('id', $this->params['accept']);
                        $this->currentGroup->deleteRequestRelation($u);
                        $membersList->approveMember($this->params['accept']);
                    } else $this->_redirect($this->currentGroup->getGroupPath('members').'mode/'.$this->params['mode'].'/');
                }
                  if (isset($this->params['decline'])) {
                    if ($membersList->isMemberExistsAndPending($this->params['decline'])) {
                        $u = new Warecorp_User('id', $this->params['decline']);
                          $this->currentGroup->deleteRequestRelation($u);
                        $membersList->removeMember($this->params['decline']);
                    } else $this->_redirect($this->currentGroup->getGroupPath('members').'mode/'.$this->params['mode'].'/');
                }
            }
            $this->_redirect($this->currentGroup->getGroupPath('members').'mode/'.$this->params['mode'].'/'.$order.$page.$id);
        }
        /**
         * set order
         */
           switch ($order) {
               case 'name'    :   $order_path = 'zgm.status, zua.login '.$direction;                  break;
               case 'joined'  :   $order_path = 'zgm.status, zgm.creation_date '.$direction;          break;
               case 'laston'  :   $order_path = 'zgm.status, zua.last_access '.$direction;            break;
               default        :   $order_path = 'zgm.status, zua.login '.$direction;                  break;
           }
           $membersList->setOrder($order_path);

            if ($this->params['mode'] == 'approved') {
            $this->params['page'] = (isset($this->params['page']))? $this->params['page'] : 1;
            $membersList->setCurrentPage(intval($this->params['page']));
            $membersList->setListSize($items_per_page);
        } elseif ($this->params['mode'] == 'pending') {

        } elseif ($this->params['mode'] == 'request') {
            $items_per_page = 1;

            $memlist = $membersList->returnAsAssoc()->setAssocKey('zua.id')->setAssocValue('zua.id')->getList();
            $memlist1 = array_values($memlist);
            $memlist2 = array_flip($memlist1);
            $prev_id = (array_key_exists($memlist2[(int)$this->params['id']] - 1, $memlist1))?$memlist1[$memlist2[(int)$this->params['id']] - 1]:null;
            $next_id = (array_key_exists($memlist2[(int)$this->params['id']] + 1, $memlist1))?$memlist1[$memlist2[(int)$this->params['id']] + 1]:null;
            $page = $memlist2[(int)$this->params['id']] + 1;
            $this->view->prevId = $prev_id;
            $this->view->nextId = $next_id;
            $membersList->returnAsAssoc(false);

            $page = !empty($page)? $page : 1;
            $membersList->setCurrentPage(intval($page));
            $membersList->setListSize($items_per_page);
       }
    }

    $this->view->friends = $user->getId() ?  $user->getFriendsList()->returnAsAssoc()->getList() : array();
    $this->view->assign('mode_'.$this->params['mode'], true);
    $this->view->membersList = $membersList;
    $this->view->showPending = isset($showPending)?$showPending:null;
    $this->view->order = $order;
    $this->view->direction = $direction;
    $this->view->isHostPrivileges = $this->userHasHostPriveleges;
    $this->view->page = isset($this->params['page']) ? $this->params['page'] : null;

    /**
     * Build paging for approved members page
     */
    $paging_link = '';
    if (isset($this->params['order'])) {
        $paging_link = "/order/$order/direction/$direction";
        if ($this->params['direction'] == 'asc') $this->params['direction'] = 'desc';
        else $this->params['direction'] = 'asc';
    }
    $this->view->paging_link = $paging_link;

    if ($this->params['mode'] == 'approved') {
        $url = $this->currentGroup->getGroupPath('members').'mode/'.$this->params['mode'].$paging_link;
        $P = new Warecorp_Common_PagingProduct($membersList->getCount(), $items_per_page, $url);
        $this->view->paging = $P->makePaging(intval(isset($this->params['page'])?$this->params['page']:null));
    }

    $this->view->currentUser = $user;
    $this->view->currentGroup = $this->currentGroup;


    /**
     * @todo - remove breadcrumb creation
     * build breadcrumb
     * set certain template page
     */
    if($this->currentGroup->getGroupType() == "family"){
    //    $this->_page->breadcrumb = array_merge($this->_page->breadcrumb, array("Group families" => "/" .$this->_page->Locale. "/summary/", $this->currentGroup->getName() => ""));
        $this->view->bodyContent = 'groups/family_members.tpl';
    } else{
    //    $this->_page->breadcrumb = array_merge(
    //        $this->_page->breadcrumb,
    //        array($this->currentGroup->getCategory($this->currentGroup->getCategoryId())->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/world/1/",
    //            $this->currentGroup->getCountry()->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/country/" .$this->currentGroup->getCountry()->id. "/",
    //            $this->currentGroup->getState()->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/state/" .$this->currentGroup->getState()->id. "/",
    //            $this->currentGroup->getCity()->name => BASE_URL. "/" .$this->_page->Locale. "/groups/search/preset/category/id/" .$this->currentGroup->getCategoryId(). "/city/" .$this->currentGroup->getCity()->id. "/",
    //            $this->currentGroup->getName() => "")
    //        );
        $this->view->bodyContent = 'groups/members'.($this->params['mode'] == "pending"?'Pending':($this->params['mode'] == "approved"?'Approved':'Request')).'.tpl';
    }

    /**
     * RSS Handler
     */
    /*if(LOCALE == "rss"){
        include_once(ENGINE_DIR."/rss.class.php");
        if ($this->currentGroup instanceof Warecorp_Group_Simple){
            $rss = new UniversalFeedCreator();
            $rss->encoding = 'utf-8';
            $rss->xslStyleSheet = "http://".$_SERVER['HTTP_HOST'].'/RSSStyle/rssstyle.xsl';
            $rss->link = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
            $rss->title = $this->currentGroup->getName() . " members ";
            $rss->description = $this->currentGroup->getName() . " member list ";
            $rss->copyright = "Copyright &copy; 2007, Zanby";

            $membersListObj = $this->currentGroup->getMembers();
            $membersListObj->setOrder('zua.login');
            $members = $membersListObj->getList();

            foreach ($members as $member){

                $item = new FeedItem();
                $item->title = $member->getLogin();
                $path = substr($member->getUserPath(),0,count($member->getUserPath())-5);
                $item->link = $path . "en/profile/";
                $item->description = "Member " . $member->getLogin() . " since " . date("d.m.y", strtotime($member->getRegisterDate())) . "<br />";

                $item->description .= "Location: " . $member->getCity()->name . ", "
                                                   . $member->getState()->name . ", "
                                                   . $member->getCountry()->name . "<br />";
                $tags = $member->getTagsList();
                 $item->description .= "Tags: ";
                 for ($i=0; $i<count($tags); $i++){
                     if ($i===0) $item->description .= $tags[$i]->name;
                     else $item->description .= ", " . $tags[$i]->name;
                 }
                    $rss->addItem($item);
                }

            header("Content-Type: ".$rss->contentType."; charset=".$rss->encoding);
            print $rss->createFeed("RSS2.0");
            exit;
        }

        if ($this->currentGroup instanceof Warecorp_Group_Family){
            $membership_type = array("anyone", "request", "code");
            $rss = new UniversalFeedCreator();
            $rss->encoding = 'utf-8';
            $rss->xslStyleSheet = "http://".$_SERVER['HTTP_HOST'].'/RSSStyle/rssstyle.xsl';
            $rss->link          = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
            $rss->title         = $this->currentGroup->getName() . " members ";
            $rss->description   = $this->currentGroup->getName() . " member list ";
            $rss->copyright     = "Copyright &copy; 2007, Zanby";

            $members = $this->currentGroup->getGroups()->setTypes(array('simple', 'family'))->getList();

            foreach ($members as $member){
                $item = new FeedItem();
                $item->title        = $member->getName();
                $path               = substr($member->getGroupPath(),0,count($member->getGroupPath())-5);
                $item->link         = $path . "en/summary/";
                $item->description .= "Description: " . $rss->iTrunc($member->getDescription(), 200) . "<br />";
                $category           = $member->getCategory();
                $item->description .= "Category: " .$category->name . "<br />";
                $host               = $member->getHost();
                $item->description .= "Host: " . $host->getLogin() . "<br />";

                $item->description .= "Location: " . $member->getCity()->name . ", "
                                                   . $member->getState()->name . ", "
                                                   . $member->getCountry()->name . "<br />";
                $tags = $member->getTagsList();
                 $item->description .= "Tags: ";
                 for ($i=0; $i<count($tags); $i++){
                     if ($i===0) $item->description .= $tags[$i]->name;
                     else $item->description .= ", " . $tags[$i]->name;
                 }
                 $item->description .= "<br />";

                 $item->description .= "Founded: " . date("d.m.y", strtotime($member->getCreateDate())) . "<br />";
                 $item->description .= "Members: " . count($member->getMembers()) . "<br />";
                 $item->description .= "Membership: " . $membership_type[$member->getJoinMode()];

                $rss->addItem($item);
            }

            header("Content-Type: ".$rss->contentType."; charset=".$rss->encoding);
            print $rss->createFeed("RSS2.0");
            exit;
        }

    }*/


    function sortPendingMembersByRequestDateAsc($a, $b)
    {
        $CG = Zend_Registry::get('CG');
        $aRequest = $CG->getRequestRelation($a);
        $bRequest = $CG->getRequestRelation($b);
        $aRequestDate = new Zend_Date($aRequest->requestDate, Zend_Date::ISO_8601);
        $bRequestDate = new Zend_Date($bRequest->requestDate, Zend_Date::ISO_8601);
        if ( $aRequestDate->isEarlier($bRequestDate) ) return -1;
        else return 1;

    }

    function sortPendingMembersByRequestDateDesc($a, $b)
    {
        $CG = Zend_Registry::get('CG');
        $aRequest = $CG->getRequestRelation($a);
        $bRequest = $CG->getRequestRelation($b);
        $aRequestDate = new Zend_Date($aRequest->requestDate, Zend_Date::ISO_8601);
        $bRequestDate = new Zend_Date($bRequest->requestDate, Zend_Date::ISO_8601);
        if ( $aRequestDate->isEarlier($bRequestDate) ) return 1;
        else return -1;
    }
