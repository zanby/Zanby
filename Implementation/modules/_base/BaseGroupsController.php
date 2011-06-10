<?php
Warecorp::addTranslation('/modules/groups/BaseGroupsController.php.xml');

class BaseGroupsController extends Warecorp_Controller_Action
{
    public $currentGroup = null;
    public $params;
    public $mainBreadcrumb;
    public $userHasHostPriveleges = null;


    public function init()
    {
        /**
         * Detect global group for 2a-b implementations
         * @author Artem Sukharev
         */
        $globalGroup = null;
        if ( $this->_hasParam('groupname') ) {
            /**
             * Param 'groupname' comes from Route variable ':groupname' in Bootstrap.php
             */
            $this->_setParam('name', $this->_getParam('groupname'));
        }

        if ( 'ESA' != IMPLEMENTATION_TYPE ) {
            if ( Zend_Registry::isRegistered('globalGroup') ) {
                $globalGroup = Zend_Registry::get('globalGroup');
                /**
                 * if it is 2a-b implementation and user went from global navigation
                 * ( host is some as BASE_HTTP_HOST ) - redefine group
                 */
                if (!$this->_hasParam('groupname')) {
                    $this->getRequest()->setParam('name', $globalGroup->getPath());
                }
                //if ( $_SERVER['HTTP_HOST'] == BASE_HTTP_HOST ) {
                   // $this->getRequest()->setParam('name', $globalGroup->getPath());
                //}
            } else
                throw new Exception(Warecorp::t('Incorrect global group param'));
        }


        parent::init();

        $request = $this->getRequest();
        $this->params = $request->getParams();

        /**
         * Find current group by request params
         * by name or by groupId
         */
        if ( isset($this->params['name']) || isset($this->params['groupid']) ) {
            if ( isset($this->params['name']) && Warecorp_Group_Simple::isGroupExists('group_path', $this->params['name']) ) {
                $this->currentGroup = Warecorp_Group_Factory::loadByPath($this->params['name']);
                $this->view->CurrentGroup = $this->currentGroup;
            } elseif ( isset($this->params['groupid']) && Warecorp_Group_Simple::isGroupExists('id', $this->params['groupid']) ) {
                $this->currentGroup = Warecorp_Group_Factory::loadById($this->params['groupid']);
                $this->view->CurrentGroup = $this->currentGroup;
            } else { //No such group
                $this->_redirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/groups/');
            }
        }


        /**
         * use sso if it needs
         */
        if ( USE_SSO && $this->currentGroup ) {
            /**
             * 1. взять все параметры
             * 2. HTTPS ?
             */
            if ( 'GET' == $this->getRequest()->getMethod() ) {
                /**
                 * detect domain to redirect
                 */
                $cfgSSO = $this->getSSOConfig();

                $mainGroupUID       = ( $this->currentGroup->getMainGroupUID() ) ? $this->currentGroup->getMainGroupUID() : HTTP_CONTEXT;
                $mainGroupDomain    = ( isset($cfgSSO[$mainGroupUID]) ) ? $cfgSSO[$mainGroupUID]['host'] : BASE_HTTP_HOST;
                $mainGroupAllow     = ( isset($cfgSSO[$mainGroupUID]) ) ? $cfgSSO[$mainGroupUID]['allow'] : false;
                if ( $mainGroupAllow ) {
                    /**
                     * do redirect
                     */
                    if ( BASE_HTTP_HOST != $mainGroupDomain ) {
                        $redirectUrl = 'http://'.$mainGroupDomain.$this->getRequest()->getRequestUri();
                        $redirectUrl = base64_encode($redirectUrl);
                        $ssoUrl = 'http://'.BASE_HTTP_HOST.'/'.LOCALE.'/index/sso/redirect/'.$redirectUrl.'/';
                        $this->_redirect($ssoUrl);
                        exit;
                    }
                }
            }
        }

        if ( $this->currentGroup ) {
            if ( $this->currentGroup->getGroupType() == "family" ) {
                $this->_page->breadcrumb = array (Warecorp::t("Group families" ) => $this->_page->_user->getUserPath( 'familylanding' )) + array ($this->currentGroup->getName() => null);
            } else {
                $this->_page->breadcrumb = array ($this->currentGroup->getCategory( $this->currentGroup->getCategoryId() )->name => BASE_URL . "/" . $this->_page->Locale . "/groups/search/preset/category/id/" . $this->currentGroup->getCategoryId() . "/world/1/", $this->currentGroup->getCountry()->name => BASE_URL . "/" . $this->_page->Locale . "/groups/search/preset/category/id/" . $this->currentGroup->getCategoryId() . "/country/" . $this->currentGroup->getCountry()->id . "/", $this->currentGroup->getState()->name => BASE_URL . "/" . $this->_page->Locale . "/groups/search/preset/category/id/" . $this->currentGroup->getCategoryId() . "/state/" . $this->currentGroup->getState()->id . "/", $this->currentGroup->getCity()->name . ' ' => BASE_URL . "/" . $this->_page->Locale . "/groups/search/preset/category/id/" . $this->currentGroup->getCategoryId() . "/city/" . $this->currentGroup->getCity()->id . "/") + array ($this->currentGroup->getName() => "");
            }
        }

        $this->_page->setTitle(Warecorp::t('Groups'));
        $this->view->currentGroup = $this->currentGroup;

        /**
         * check GROUP HOST permissions
         */


        if ( $this->currentGroup ) {
            $this->userHasHostPriveleges = Warecorp_Group_AccessManager::isHostPrivileges($this->currentGroup, $this->_page->_user);
            if ( $this->currentGroup->getGroupType() == "simple" ) { //

                    if (!$this->userHasHostPriveleges) {

                    //if ( !($this->currentGroup->getMembers()->isHost( $this->_page->_user->getId() ) || $this->currentGroup->getMembers()->isCohost( $this->_page->_user->getId() )) ) { //if not a host of group
                    //if ($this->currentGroup->getHost()->getId() != $this->_page->_user->getId()){ //if not a host of group
                    /**
                     * Choose configuration file
                     * if file exits in root access folder get it else
                     * get configuration file from ESA|EIA folder
                     */
                    if ( file_exists( ACCESS_RIGHTS_DIR.'group_owner_allowed.xml' ) ) {
                        $cfg_access_file = ACCESS_RIGHTS_DIR.'group_owner_allowed.xml';
                    } elseif ( file_exists( ACCESS_RIGHTS_DIR.IMPLEMENTATION_TYPE . '/group_owner_allowed.xml' ) ) {
                        $cfg_access_file = ACCESS_RIGHTS_DIR.IMPLEMENTATION_TYPE . '/group_owner_allowed.xml';
                    } else {
                        throw new Zend_Exception( 'Configuration file \'group_owner_allowed.xml\' was not found.' );
                    }
                    $hostAccess = new Warecorp_Access( ); //
                    $hostAccess->loadXmlConfig( $cfg_access_file );
                    if ( $hostAccess->isAllowed( Warecorp::$controllerName, Warecorp::$actionName ) ) {
                        $hostAccess->redirectToLogin( $this->currentGroup->getGroupPath() . "summary/" );
                        exit();
                    }
                }
            } elseif ( $this->currentGroup->getGroupType() == "family" ) {
                if ( !($this->currentGroup->getMembers()->isHost( $this->_page->_user->getId() ) || $this->currentGroup->getGroups()->isCoowner( $this->_page->_user )) ) { //if not a host or coowner of group
                    /**
                     * Choose configuration file
                     * if file exits in root access folder get it else
                     * get configuration file from ESA|EIA folder
                     */
                    if ( file_exists( ACCESS_RIGHTS_DIR.'group_owner_allowed.xml' ) ) {
                        $cfg_access_file = ACCESS_RIGHTS_DIR.'group_owner_allowed.xml';
                    } elseif ( file_exists( ACCESS_RIGHTS_DIR.IMPLEMENTATION_TYPE.'/group_owner_allowed.xml' ) ) {
                        $cfg_access_file = ACCESS_RIGHTS_DIR.IMPLEMENTATION_TYPE.'/group_owner_allowed.xml';
                    } else {
                        throw new Zend_Exception( Warecorp::t('Configuration file \'group_owner_allowed.xml\' was not found.') );
                    }
                    $hostAccess = new Warecorp_Access( ); //
                    $hostAccess->loadXmlConfig( $cfg_access_file );
                    if ( $hostAccess->isAllowed( Warecorp::$controllerName, Warecorp::$actionName ) ) {
                        $hostAccess->redirectToLogin( $this->currentGroup->getGroupPath() . "summary/" );
                        exit();
                    }
                }
            }
            /**
             * check GROUP MEMBER permissions
             */
            if ( $this->currentGroup->getGroupType() == "simple" ) {
                if ( !$this->currentGroup->getMembers()->isMemberExistsAndApproved( $this->_page->_user->getId() ) && !$this->userHasHostPriveleges ) { //if not a host of group
                    /**
                     * Choose configuration file
                     * if file exits in root access folder get it else
                     * get configuration file from ESA|EIA folder
                     */
                    if ( file_exists( ACCESS_RIGHTS_DIR.'group_member_allowed.xml' ) ) {
                        $cfg_access_file = ACCESS_RIGHTS_DIR.'group_member_allowed.xml';
                    } elseif ( file_exists( ACCESS_RIGHTS_DIR.IMPLEMENTATION_TYPE.'/group_member_allowed.xml' ) ) {
                        $cfg_access_file = ACCESS_RIGHTS_DIR.IMPLEMENTATION_TYPE.'/group_member_allowed.xml';
                    } else {
                        throw new Zend_Exception( Warecorp::t('Configuration file \'group_member_allowed.xml\' was not found.') );
                    }
                    $memberAccess = new Warecorp_Access( );
                    $memberAccess->loadXmlConfig( $cfg_access_file );
                    if ( $memberAccess->isAllowed( Warecorp::$controllerName, Warecorp::$actionName ) ) {
                        $memberAccess->redirectToLogin( $this->currentGroup->getGroupPath() . "summary/" );
                        exit();
                    }
                }
            } elseif ( $this->currentGroup->getGroupType() == "family" ) {
            }
        }

        /**
         * REGISTER AJAX FUNCTIONS
         */
        $this->_page->Xajax->registerUriFunction("loadphoto", "/groups/loadphoto/");
        $this->_page->Xajax->registerUriFunction("loadavatar", "/groups/loadavatar/");

        /**
         * Detect if current group is the main family for 2a-b implementation
         * If current implementation is ESA it will be skiped.
         */
        $_IS_GLOBAL_GROUP = false;
        /* EIA */
        if ( $this->currentGroup && $globalGroup ) {
            if ( $this->currentGroup->getId() == $globalGroup->getId() ) {
                $_IS_GLOBAL_GROUP = true;

                /* for main family 2a-b implementetion if it isn't family stuff we must apply wide layout */
                if ( !Warecorp::is('Stuff', 'Group') && !Warecorp::is('Tools', 'Group') ) {
                    $this->view->setLayout('main_wide.tpl');
                    $showRightBlock =
                        (Warecorp::$controllerName == 'index' && Warecorp::$actionName == 'index') ||
                        (Warecorp::$controllerName == 'groups' && in_array(Warecorp::$actionName, array('summary', 'edit')));
                    if ( !$showRightBlock ) {
                        $this->view->isRightBlockHidden = true;
                    }
                } else {
                    $this->view->setLayout('main.tpl');
                }
            } else {
                $_IS_GLOBAL_GROUP = false;
                $this->view->setLayout('main.tpl');
            }
        }
        /* ESA */
        else {
            $this->view->setLayout('main.tpl');
        }
        defined('IS_GLOBAL_GROUP')
            || define( 'IS_GLOBAL_GROUP', $_IS_GLOBAL_GROUP );
        $this->view->IS_GLOBAL_GROUP = IS_GLOBAL_GROUP ;
    }

    public static function addJoinRequestAction($mailObj, $returnedEmailParams, $params)
    {
        $group = isset($params['group'])?(($params['group'] instanceof Warecorp_Group_Base)?$params['group']:null):null;
        $user = isset($params['user'])?(($params['user'] instanceof Warecorp_User)?$params['user']:null):null;
        if (null === $group) return false;
        $group->setRequestRelation($mailObj->message, $user);
    }
    public static function addJoinFamilyRequestAction($mailObj, $returnedEmailParams, $params)
    {
        $group = isset($params['group'])?(($params['group'] instanceof Warecorp_Group_Base)?$params['group']:null):null;
        $groups = is_array($params['groups'])?$params['groups']:null;
        if (null === $group || null === $groups) return false;
        foreach ( $groups as &$tmpGroup ) {
            if ($tmpGroup instanceof Warecorp_Group_Base)
                $group->setRequestRelation($mailObj->message, $tmpGroup);
        }
    }

    protected function getSSOConfig() {
        $cache = $this->getInvokeArg("bootstrap")->getResource("FileCache");
        if ( !$cfgSSO = $cache->load('cfg_sso_xml') ) {
            $cfgPath = realpath(CORE_CONFIG_DIR."/cfg.sso.xml");
            if ( file_exists($cfgPath) && is_readable($cfgPath) ) {
                $xml = Warecorp_Config_Loader::getInstance()->getCoreConfig('cfg.sso.xml');
                $cfgSSO = array();
                foreach ( $xml->implementations->implementation as $im ) {
                    $allow = (string)$im->alowSSO;
                    $cfgSSO[(string)$im->groupUID] = array(
                        'host' => (string)$im->groupHost,
                        'allow' => (( $allow == 'true' || $allow == 'on' || $allow == '1' ) ? true : false)
                    );
                }
            }
            $cache->save($cfgSSO, 'cfg_sso_xml', array(), Warecorp_Cache::LIFETIME_30DAYS);
        }

        return $cfgSSO;
    }

    //public function __call($action, $arguments)                                                                                             {$groupAlias = str_replace('Action', '', $action);}
    public function noRouteAction()                                                                                                         {$this->_redirect('/');}

    public function indexAction()                                                                                                           {include(PRODUCT_MODULES_DIR.'/groups/action.index.php');		}
    public function familylandingAction()														                                            {include(PRODUCT_MODULES_DIR.'/groups/action.familylanding.php');}
    public function summaryAction()		                                                                                                    {include(PRODUCT_MODULES_DIR.'/groups/action.summary.php');	}
    public function editAction()		                                                                                                    {include(PRODUCT_MODULES_DIR.'/groups/action.edit.php');	    }
    public function themeAction()                                                                                                           {include(PRODUCT_MODULES_DIR.'/groups/action.themenew.php');}
    public function publishAction()	                                                                                                        {include(PRODUCT_MODULES_DIR.'/groups/action.editpublish.php');}
    public function editpublishAction()	                                                                                                    {include(PRODUCT_MODULES_DIR.'/groups/action.editpublish.php');}
    public function editpublishstatusAction()                                                                                               {include(PRODUCT_MODULES_DIR.'/groups/action.editpublishstatus.php');}
    public function publishnowAction()                                                                                                      {include(PRODUCT_MODULES_DIR.'/groups/action.publishnow.php');}
    public function getpublishdataAction()                                                                                                  {include(PRODUCT_MODULES_DIR.'/groups/action.getpublishdata.php');}
    public function printAction()                                                                                                           {include(PRODUCT_MODULES_DIR.'/groups/action.print.php');}

    // START Members Page
    //**********************************************
    public function membersAction()		                                                                                                    {include(PRODUCT_MODULES_DIR.'/groups/action.members.php');	}
    public function membersAddStep1Action()                                                                                                 {include(PRODUCT_MODULES_DIR.'/groups/newgroup/action.step1.php');   }
    public function membersAddStep2Action()                                                                                                 {include(PRODUCT_MODULES_DIR.'/groups/newgroup/action.step2.php');   }
    public function membersSaveTempDataAction($data, $stepfrom, $stepto)                                                                    {include(PRODUCT_MODULES_DIR.'/groups/newgroup/xajax/action.saveTempData.php'); return $objResponse; }
    public function membersAddressbookAjaxUtilitiesAction($params=null, $page=null, $pageSize=null, $orderby=null, $direction=null, $filter=null) { include(PRODUCT_MODULES_DIR.'/groups/newgroup/xajax/action.addressbookAjaxUtilities.php'); return $objResponse; }
    public function membersAddaddressesAction($params)                                                                                      {include(PRODUCT_MODULES_DIR.'/groups/newgroup/xajax/action.addaddresses.php'); return $objResponse;}
    public function invitemembersAction($groupId, $params=null)                                                                             {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.invitemembers.php'); return $objResponse;}
    public function familymembersAllAction()		                                                                                        {include(PRODUCT_MODULES_DIR.'/groups/action.familymembers.all.php');}

    //**********************************************
    //  END Members Page

    public function attachgetAction()                                                                                                       {include(PRODUCT_MODULES_DIR.'/groups/attachment/action.attachget.php');}
    public function attachdelAction()                                                                                                       {include(PRODUCT_MODULES_DIR.'/groups/attachment/action.attachdel.php');}

    public function avatarsAction()                                                                                                         {include(PRODUCT_MODULES_DIR.'/groups/avatar/action.avatar.php');}
    public function avatarDeleteAction()                                                                                                    {include(PRODUCT_MODULES_DIR.'/groups/avatar/action.avatarDelete.php');}
    public function avatarUploadAction()                                                                                                    {include(PRODUCT_MODULES_DIR.'/groups/avatar/action.avatarUpload.php');}
    public function avatarMakePrimaryAction()                                                                                               {include(PRODUCT_MODULES_DIR.'/groups/avatar/action.avatarMakePrimary.php');}


    public function listsAction()                                                                                                           {include(PRODUCT_MODULES_DIR.'/groups/lists/action.lists.php');}
    public function listsAddAction()                                                                                                        {include(PRODUCT_MODULES_DIR.'/groups/lists/action.lists.add.php');}
    public function listsViewAction()                                                                                                       {include(PRODUCT_MODULES_DIR.'/groups/lists/action.lists.view.php');}
    public function listsEditAction()                                                                                                       {include(PRODUCT_MODULES_DIR.'/groups/lists/action.lists.edit.php');}
    public function listsExportAction()                                                                                                     {include(PRODUCT_MODULES_DIR.'/groups/lists/action.lists.export.php');}

    /*
     +-----------------------------------
     |
     | Group  Actions Block
     |
     +-----------------------------------
    */
    //  START Group Calendar Actions Block
    //**********************************************
    public function addAddressFromAddressbookAction($params=null, $page = null, $orderby=null, $direction=null, $filter=null)               {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.addAddressFromAddressbook.php'); return $objResponse;}
    public function addAddressToFieldAction($params = null, $old_value = null, $lists = null, $groups = null)                               {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.addAddressToField.php'); return $objResponse;}
    public function deleteAddressFromFieldAction($mode, $itemId, $lists = null, $groups = null)                                             {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.deleteAddressFromField.php'); return $objResponse;}

    /**
    * @desc
    */
    public function calendarActionConfirmAction()                                                                                           {include(PRODUCT_MODULES_DIR.'/groups/calendar/action.confirm.php');}
    public function calendarMonthViewAction()                                                                                               {include(PRODUCT_MODULES_DIR.'/groups/calendar/action.month.view.php');}
    public function calendarListViewAction()                                                                                                {include(PRODUCT_MODULES_DIR.'/groups/calendar/action.list.view.php');}
    public function calendarMapViewAction()                                                                                                 {include(PRODUCT_MODULES_DIR.'/groups/calendar/action.map.view.php');}
    public function calendarHierarchyViewAction()                                                                                           {include(PRODUCT_MODULES_DIR.'/groups/calendar/action.hierarchy.view.php');}
    public function calendarMemberViewAction()                                                                                              {include(PRODUCT_MODULES_DIR.'/groups/calendar/action.member.view.php');}
    public function calendarEventCreateAction()                                                                                             {include(PRODUCT_MODULES_DIR.'/groups/calendar/action.event.create.php');}
    public function calendarEventViewAction()                                                                                               {include(PRODUCT_MODULES_DIR.'/groups/calendar/action.event.view.php');}
    public function calendarEventEditAction()                                                                                               {include(PRODUCT_MODULES_DIR.'/groups/calendar/action.event.edit.php');}
    public function calendarEventCopyDoAction()                                                                                             {include(PRODUCT_MODULES_DIR.'/groups/calendar/action.event.copy.php');}
    public function calendarEventApplyRequestAction()                                                                                       {include(PRODUCT_MODULES_DIR.'/groups/calendar/action.event.apply.request.php');}
    public function calendarEventDocgetAction()                                                                                             {include(PRODUCT_MODULES_DIR.'/groups/calendar/action.event.docget.php');}
    public function calendarEventAttendeeDownloadAction()                                                                                   {include(PRODUCT_MODULES_DIR.'/groups/calendar/action.event.attendee.download.php');}
    public function calendarEventAttendeePrintAction()                                                                                      {include(PRODUCT_MODULES_DIR.'/groups/calendar/action.event.attendee.print.php');}
    public function calendarEventExportAction()                                                                                             {include(PRODUCT_MODULES_DIR.'/groups/calendar/action.event.export.php');}

    /**
    * @desc
    */
    public function calendarEventAddToMyAction($id, $uid, $handle = null)                                                                   {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.event.add.to.my.php'); return $objResponse;}
    public function calendarEventCancelAction($mode, $id, $uid, $view = 'month', $year = null, $month = null, $day = null, $handle = false) {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.event.cancel.php'); return $objResponse;}
    public function calendarEventEasyAddAction($year = null, $month = null, $day = null, $handle = false)                                   {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.event.easy.add.php'); return $objResponse;}
    public function calendarEventAttendeeAction($id, $uid, $view = 'month', $handle = false, $date = null)                                  {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.event.attendee.php'); return $objResponse;}
    public function calendarEventAttendeeSignupAction($id, $uid, $view = 'month', $handle = false)                                          {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.event.attendee.signup.php'); return $objResponse;}
    public function calendarEventAttendeeViewAction($id, $uid)                                                                              {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.event.attendee.view.php'); return $objResponse;}
    public function calendarEventCopyAction($id, $uid, $handle = false)                                                                     {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.event.copy.php'); return $objResponse;}
    public function calendarEventShareAction($id, $uid, $mode = null, $handle = false)                                                      {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.event.share.php'); return $objResponse;}
    public function calendarEventUnShareAction($id, $uid, $groupId, $mode = null, $handle = false)                                          {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.event.unshare.php'); return $objResponse;}
    public function calendarHostUnshareEventAction($id, $uid, $handle = false)                                                              {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.event.host.unshare.php'); return $objResponse;}
    public function calendarEventInviteAction($id, $uid, $handle = false, $params = array())                                                {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.event.invite.php'); return $objResponse;}
    public function calendarEventRemoveGuestAction($id, $uid, $handle = false)                                                              {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.event.remove.guest.php'); return $objResponse;}
    public function calendarEventRemoveMeAction($id, $uid, $handle = false)                                                                 {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.event.remove.me.php'); return $objResponse;}
    public function calendarEventSendMessageAction($id, $uid, $handle = false)                                                              {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.event.send.message.php'); return $objResponse;}
    public function calendarEventOrganizerSendMessageAction($id, $uid, $handle = false)                                                     {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.event.organizer.send.message.php'); return $objResponse;}
    public function calendarEventChangeHostAction($id, $uid, $handle = false)                                                               {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.event.change.host.php'); return $objResponse;}

    public function calendarEventAttachPhotoAction($photoId = null, $handle = null)                                                         {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.event.attach.photo.php'); return $objResponse;}
    public function calendarEventAttachPhotoUpdateAction($currentPage = 1, $perPage = 20)                                                   {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.event.attach.photo.update.php'); return $objResponse;}
    public function calendarEventAttachPhotoChooseAction($src, $title, $photoId)                                                            {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.event.attach.photo.choose.php'); return $objResponse;}
    public function calendarEventAttachPhotoDeleteAction()                                                                                  {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.event.attach.photo.delete.php'); return $objResponse;}

    public function calendarEventAttachDocumentAction($handle = null, $mode = 'ADD')                                                        {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.event.attach.document.php'); return $objResponse;}
    public function calendarEventAttachListAction($handle = null, $mode = 'ADD')                                                            {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.event.attach.list.php'); return $objResponse;}

    public function calendarEventExpandListAction($id, $uid, $listId)                                                                       {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.event.expand.list.php'); return $objResponse;}
    public function calendarEventCollapseListAction($id, $uid, $listId)                                                                     {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.event.collapse.list.php'); return $objResponse;}
    public function calendarEventInviteEntireGroupAction($groupId, $checked, $groups)                                                       {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.event.invite.group.php'); return $objResponse;}
    public function calendarEventInviteMembersAction($groupId, $strEmails, $handle = null)                                                  {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.event.invite.members.php'); return $objResponse;}
    public function calendarEventDayDetailsAction($strDate)                                                                                 {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.event.day.details.php'); return $objResponse;}
    //**********************************************
    //  END Group Calendar Actions Block

    //  START Group Venue Actions Block
    //**********************************************
    public function addNewVenueAction($aParams = array())                                                                                   {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.venue.add.php'); return $objResponse; }
    public function chooseSavedVenueAction($venueId = null)                                                                                 {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.venue.choose.php'); return $objResponse; }
    public function setVenueAction($venueId = null)                                                                                         {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.venue.set.php'); return $objResponse; }
    public function editVenueAction($venueId = null, $editedBlock, $aParams = array() )                                                     {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.venue.edit.php'); return $objResponse; }
    public function loadSavedVenuesAction($aParams = array())                                                                               {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.venue.load.saved.php'); return $objResponse; }
    public function copyVenueAction($venueId = null,$x,$y)                                                                                  {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.venue.copy.php'); return $objResponse; }
    public function copyVenueDoAction($venueId = null,$newName = null, $venue_type = null)                                                  {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.venue.copy.do.php'); return $objResponse; }
    public function deleteVenueAction($venueId = null)                                                                                      {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.venue.delete.php'); return $objResponse; }
    public function deleteVenueDoAction($venueId = null, $venue_type = null)                                                                {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.venue.delete.do.php'); return $objResponse->getXML(); }
    public function chooseSavedWWVenueAction($venueId = null)                                                                               {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.ww.venue.choose.php'); return $objResponse; }
    public function setWWVenueAction($venueId = null)                                                                                       {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.ww.venue.set.php'); return $objResponse; }
    public function addNewWWVenueAction($aParams = array())                                                                                 {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.ww.venue.add.php'); return $objResponse; }
    public function editWWVenueAction($venueId = null, $editedBlock, $aParams = array())                                                    {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.ww.venue.edit.php'); return $objResponse; }
    public function loadSavedWWVenuesAction($aParams = array())                                                                             {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.ww.venue.load.saved.php'); return $objResponse; }
    public function copyWWVenueAction($venueId = null,$x,$y)                                                                                {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.ww.venue.copy.php'); return $objResponse; }
    public function copyWWVenueDoAction($venueId = null, $newName = null, $venue_type = null)                                               {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.ww.venue.copy.do.php'); return $objResponse; }
    public function deleteWWVenueAction($venueId = null)                                                                                    {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.ww.venue.delete.php'); return $objResponse; }
    public function deleteWWVenueDoAction($venueId = null, $venue_type = null)                                                              {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.ww.venue.delete.do.php'); return $objResponse->getXML(); }
    public function findaVenueAction($aParams = array())                                                                                    {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.venue.search.form.php'); return $objResponse->getXML(); }
    public function copyVenueFromSearchAction($aParams = array())                                                                           {include(PRODUCT_MODULES_DIR.'/groups/calendar/ajax/action.venue.copy.fromSearch.php'); return $objResponse->getXML(); }
    //**********************************************
    //  END Venues
    /*
     +-----------------------------------
     |
     | Group Calendar Actions Block END
     |
     +-----------------------------------
    */

    public function attachmentDeleteAction($attach_id)                                                                                      {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.attachmentDelete.php'); return $objResponse;}
    public function tagsAction()		                                                                                                    {include(PRODUCT_MODULES_DIR.'/groups/action.tags.php');		}

    //  START Document Actions Block
    //**********************************************
    public function documentsAction()	                                                                                                    {include(PRODUCT_MODULES_DIR.'/groups/documents/action.documents.php');	}
    public function docgetAction()                                                                                                          {include(PRODUCT_MODULES_DIR.'/groups/documents/action.document.docget.php');}
    public function documentChangeContent($objResponse, $objOwner, $currentFolderId)                                                        {include(PRODUCT_MODULES_DIR.'/groups/documents/xajax/action.documentChangeContent.php'); return $objResponse;}
    public function documentChangeMainFolderAction($id, $entityType = null)                                                                 {include(PRODUCT_MODULES_DIR.'/groups/documents/xajax/action.documentChangeMainFolder.php'); return $objResponse;}
    public function documentChangeFolderAction($folder_id)                                                                                  {include(PRODUCT_MODULES_DIR.'/groups/documents/xajax/action.documentChangeFolder.php'); return $objResponse;}
    public function documentCreateFolderAction()                                                                                            {include(PRODUCT_MODULES_DIR.'/groups/documents/xajax/action.documentCreateFolder.php'); return $objResponse;}
    public function documentAddAction()                                                                                                     {include(PRODUCT_MODULES_DIR.'/groups/documents/xajax/action.documentAddFile.php'); return $objResponse;}
    public function documentEditAction()                                                                                                    {include(PRODUCT_MODULES_DIR.'/groups/documents/xajax/action.documentEdit.php'); return $objResponse;}
    public function documentShareFileAction()                                                                                               {include(PRODUCT_MODULES_DIR.'/groups/documents/xajax/action.documentShareFile.php'); return $objResponse;}
    public function documentManageSharingAction()                                                                                           {include(PRODUCT_MODULES_DIR.'/groups/documents/xajax/action.documentManageSharing.php'); return $objResponse;}
    public function documentUnshareFileAction()                                                                                             {include(PRODUCT_MODULES_DIR.'/groups/documents/xajax/action.documentUnshareFile.php'); return $objResponse;}
    public function documentDeleteGroupAction()                                                                                             {include(PRODUCT_MODULES_DIR.'/groups/documents/xajax/action.documentDeleteGroup.php'); return $objResponse;}
    public function documentMoveGroupAction()                                                                                               {include(PRODUCT_MODULES_DIR.'/groups/documents/xajax/action.documentMoveGroup.php'); return $objResponse;}
    public function documentSortAction($curr_owner_id, $curr_folder_id, $curr_order, $curr_direction, $order)                               {include(PRODUCT_MODULES_DIR.'/groups/documents/xajax/action.documentSort.php'); return $objResponse;}
    public function documentAddToMyAction()                                                                                                 {include(PRODUCT_MODULES_DIR.'/groups/documents/xajax/action.documentAddToMy.php'); return $objResponse;}
    public function documentCheckInAction()                                                                                                 {include(PRODUCT_MODULES_DIR.'/groups/documents/xajax/action.documentCheckIn.php'); return $objResponse;}
    public function documentCheckOutAction()                                                                                                {include(PRODUCT_MODULES_DIR.'/groups/documents/xajax/action.documentCheckOut.php'); return $objResponse;}
    public function documentCancelCheckOutAction()                                                                                          {include(PRODUCT_MODULES_DIR.'/groups/documents/xajax/action.documentCancelCheckOut.php'); return $objResponse;}
    public function documentRevisionsAction()                                                                                               {include(PRODUCT_MODULES_DIR.'/groups/documents/xajax/action.documentRevisions.php'); return $objResponse;}
    public function documentRevertRevisionAction()                                                                                          {include(PRODUCT_MODULES_DIR.'/groups/documents/xajax/action.documentRevertRevision.php'); return $objResponse;}
    public function documentAddWeblinkAction()                                                                                              {include(PRODUCT_MODULES_DIR.'/groups/documents/xajax/action.documentAddWeblink.php'); return $objResponse;}
    //**********************************************
    //  END Document Actions Block


    public function settingsAction()	                                                                                                    {include(PRODUCT_MODULES_DIR.'/groups/action.settings.php');	}
    public function exportmembersAction()                                                                                                   {include(PRODUCT_MODULES_DIR.'/groups/action.export.members.php');    }
    public function setnewhostAction()                                                                                                      {include(PRODUCT_MODULES_DIR.'/groups/action.setnewhost.php');	}

    public function browseAction()                                                                                                          {include(PRODUCT_MODULES_DIR.'/groups/action.browse.php');		}
    public function searchAction()	                                                                                                        {include(PRODUCT_MODULES_DIR.'/groups/action.search.php');	}
    public function inviteSearchAction()                                                                                                    {include(PRODUCT_MODULES_DIR.'/groups/action.search.php');	}

    public function savedetailsAction($params)                                                                                              {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.saveDetails.php');	return $objResponse;}
    public function savefamilydetailsAction($params)                                                                                        {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.saveFamilyDetails.php');	return $objResponse;}
    public function savePrivilegesAction($params)                                                                                           {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.savePrivileges.php');	return $objResponse;}
    public function saveFamilyPrivilegesAction($params)                                                                                     {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.saveFamilyPrivileges.php');	return $objResponse;}
    public function userdeletePrivilegesAction($tool_type, $userId, $params)                                                                {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.userdeletePrivileges.php');	return $objResponse;}
    public function useraddPrivilegesAction($tool_type, $params)                                                                            {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.useraddPrivileges.php');	return $objResponse;}
    public function autocompleteLoginsAction($filter = "", $function)                                                                       {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.autocompleteLogins.php'); return $objResponse;}
    public function autocompleteMembersAction($filter = "", $function)                                                                      {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.autocompleteMembers.php'); return $objResponse;}

    public function joingroupAction()	                                                                                                    {include(PRODUCT_MODULES_DIR.'/groups/join/action.joingroup.php');	}
    public function joinsuccessAction()                                                                                                     {include(PRODUCT_MODULES_DIR.'/groups/join/action.joinuccess.php');	}
    public function joinSendMessageToHostAction($params = null)                                                                             {include(PRODUCT_MODULES_DIR.'/groups/join/action.sendMessageToHost.php'); return $objResponse;}

    public function joinfamilygroupAction()                                                                                                 {include(PRODUCT_MODULES_DIR.'/groups/joinfamily/action.joinfamilygroup.php');	}
    public function joinfamilystep0Action()                                                                                                 {include(PRODUCT_MODULES_DIR.'/groups/joinfamily/action.joinfamilystep0.php');	}
    public function joinfamilystep1Action()                                                                                                 {include(PRODUCT_MODULES_DIR.'/groups/joinfamily/action.joinfamilystep1.php');	}
    public function joinfamilystep2Action()                                                                                                 {include(PRODUCT_MODULES_DIR.'/groups/joinfamily/action.joinfamilystep2.php');	}

    //  START Group Photos Actions Block
    //**********************************************
    public function photosAction()                                                                                                          {include(PRODUCT_MODULES_DIR.'/groups/gallery/action.photos.php');}
    public function popupAction()                                                                                                           {include(PRODUCT_MODULES_DIR.'/groups/gallery/action.popup.php');}
    public function galleryAction()                                                                                                         {include(PRODUCT_MODULES_DIR.'/groups/gallery/action.gallery.php');}
    public function galleryCreateAction()                                                                                                   {include(PRODUCT_MODULES_DIR.'/groups/gallery/action.galleryCreate.php');}
    public function galleryEditAction()                                                                                                     {include(PRODUCT_MODULES_DIR.'/groups/gallery/action.galleryEdit.php');}
    public function galleryDeleteAction()                                                                                                   {include(PRODUCT_MODULES_DIR.'/groups/gallery/action.galleryDelete.php');}
    public function galleryUnshareAction()                                                                                                  {include(PRODUCT_MODULES_DIR.'/groups/gallery/action.galleryUnshare.php');}
    public function galleryViewAction()                                                                                                     {include(PRODUCT_MODULES_DIR.'/groups/gallery/action.galleryView.php');}
    public function galleryShareGroupAction($galleryId, $groupId, $application)                                                                       {include(PRODUCT_MODULES_DIR.'/groups/gallery/xajax/action.galleryShareGroup.php'); return $objResponse;}
    public function galleryShareGroupDoAction($galleryId, $groupId, $application)                                                           {include(PRODUCT_MODULES_DIR.'/groups/gallery/xajax/action.galleryShareGroupDo.php'); return $objResponse;}
    public function galleryShareFriendAction($galleryId, $application)                                                                      {include(PRODUCT_MODULES_DIR.'/groups/gallery/xajax/action.galleryShareFriend.php'); return $objResponse;}
    public function galleryShareFriendDoAction($galleryId, $data, $application)                                                             {include(PRODUCT_MODULES_DIR.'/groups/gallery/xajax/action.galleryShareFriendDo.php'); return $objResponse;}
    public function galleryAddGalleryAction($galleryId, $photoId, $application)                                                             {include(PRODUCT_MODULES_DIR.'/groups/gallery/xajax/action.galleryAddGallery.php'); return $objResponse;}
    public function galleryAddGalleryDoAction($galleryId, $photoId, $data)                                                                  {include(PRODUCT_MODULES_DIR.'/groups/gallery/xajax/action.galleryAddGalleryDo.php'); return $objResponse;}
    public function galleryAddPhotoAction($galleryId, $photoId, $application)                                                               {include(PRODUCT_MODULES_DIR.'/groups/gallery/xajax/action.galleryAddPhoto.php'); return $objResponse;}
    public function galleryAddPhotoDoAction($galleryId, $photoId, $data)                                                                    {include(PRODUCT_MODULES_DIR.'/groups/gallery/xajax/action.galleryAddPhotoDo.php'); return $objResponse;}
    public function galleryUnShareDoAction($galleryId, $application)                                                                        {include(PRODUCT_MODULES_DIR.'/groups/gallery/xajax/action.galleryUnShareDo.php'); return $objResponse;}
    public function galleryUnShareGroupDoAction($galleryId, $groupId, $application)				                                            {include(PRODUCT_MODULES_DIR.'/groups/gallery/xajax/action.galleryUnShareGroupDo.php'); return $objResponse;}
    public function galleryUnShareFriendDoAction($galleryId, $userId, $application)	     		                                            {include(PRODUCT_MODULES_DIR.'/groups/gallery/xajax/action.galleryUnShareFriendDo.php'); return $objResponse;}
    public function galleryDeleteGalleryAction($galleryId, $application, $new = false)                                                      {include(PRODUCT_MODULES_DIR.'/groups/gallery/xajax/action.galleryDeleteGallery.php'); return $objResponse;}
    public function galleryAddCommentDoAction($galleryId, $photoId, $message, $application)                                                 {include(PRODUCT_MODULES_DIR.'/groups/gallery/xajax/action.galleryAddCommentDo.php'); return $objResponse;}
    public function galleryUpdateCommentDoAction($galleryId, $photoId, $commentId, $message, $application)                                  {include(PRODUCT_MODULES_DIR.'/groups/gallery/xajax/action.galleryUpdateCommentDo.php'); return $objResponse;}
    public function galleryDeleteCommentDoAction($galleryId, $photoId, $commentId, $application)                                            {include(PRODUCT_MODULES_DIR.'/groups/gallery/xajax/action.galleryDeleteCommentDo.php'); return $objResponse;}
    public function galleryShowShareHistoryAction($galleryId, $application)                                                                 {include(PRODUCT_MODULES_DIR.'/groups/gallery/xajax/action.galleryShowShareHistory.php'); return $objResponse;}
    public function galleryShowShareHistoryDoAction()                                                                                       {include(PRODUCT_MODULES_DIR.'/groups/gallery/xajax/action.galleryShowShareHistoryDo.php'); return $objResponse;}
    public function galleryPublishAction($galleryId)                                                                                        {include(PRODUCT_MODULES_DIR.'/groups/gallery/xajax/action.galleryPublish.php'); return $objResponse;}
    public function galleryPublishDoAction($galleryId, $groupId, $application)                                                              {include(PRODUCT_MODULES_DIR.'/groups/gallery/xajax/action.galleryPublishDo.php'); return $objResponse;}
    public function galleryMoveToAction($photoId)                                                                                           {include(PRODUCT_MODULES_DIR.'/groups/gallery/xajax/action.galleryMoveTo.php'); return $objResponse;}
    public function galleryMoveToDoAction($photoId, $galleryId)                                                                             {include(PRODUCT_MODULES_DIR.'/groups/gallery/xajax/action.galleryMoveToDo.php'); return $objResponse;}
    public function editshowpageAction($page, $gallery_id, $expand_mode = 'none')                                                           {include(PRODUCT_MODULES_DIR.'/groups/gallery/xajax/action.galleryEditshowpage.php'); return $objResponse;}
    public function galleryuploadandsubmitAction($gallery_id = 0, $galleryTitle = null, $filescount = null)                                 {include(PRODUCT_MODULES_DIR.'/groups/gallery/xajax/action.galleryuploadandsubmit.php'); return $objResponse;}
    /**
     * Methods for view gallery page
     */
    public function galleryEditPhotoAction($galleryId, $photoId, $application)                                                              {include(PRODUCT_MODULES_DIR.'/groups/gallery/xajax/action.galleryEditPhoto.php'); return $objResponse;}
    public function galleryEditPhotoDoAction()                                                                                              {include(PRODUCT_MODULES_DIR.'/groups/gallery/xajax/action.galleryEditPhotoDo.php'); return $objResponse;}
    public function galleryDeletePhotoAction($galleryId, $photoId, $application)                                                            {include(PRODUCT_MODULES_DIR.'/groups/gallery/xajax/action.galleryDeletePhoto.php'); return $objResponse;}
    /**
     * Actions for edit gallery page
     *
     */
    public function galleryEditGallPhotoAction($galleryId, $photoId)                                                                        {include(PRODUCT_MODULES_DIR.'/groups/gallery/xajax/action.galleryEditGallPhoto.php'); return $objResponse;}
    public function galleryEditGallPhotoDoAction()                                                                                          {include(PRODUCT_MODULES_DIR.'/groups/gallery/xajax/action.galleryEditGallPhotoDo.php'); return $objResponse;}
    public function galleryCancelEditGallPhotoAction($galleryId, $photoId)                                                                  {include(PRODUCT_MODULES_DIR.'/groups/gallery/xajax/action.galleryCancelEditGallPhoto.php'); return $objResponse;}
    public function galleryDeleteGallPhotoDoAction($galleryId, $photoId)                                                                    {include(PRODUCT_MODULES_DIR.'/groups/gallery/xajax/action.galleryDeleteGallPhotoDo.php'); return $objResponse;}
    public function galleryUploadPhotoAction($galleryId)                                                                                    {include(PRODUCT_MODULES_DIR.'/groups/gallery/xajax/action.galleryUploadPhoto.php'); return $objResponse;}
    public function galleryUploadPhotoDoAction()                                                                                            {include(PRODUCT_MODULES_DIR.'/groups/gallery/xajax/action.galleryUploadPhotoDo.php'); return $objResponse;}
    public function galleryShowTmbPageAction($page, $galleryId)                                                                             {include(PRODUCT_MODULES_DIR.'/groups/gallery/xajax/action.galleryShowTmbPage.php'); return $objResponse;}
    //**********************************************
    //  END User Photos Actions Block

    //  START Discussion Server Actions Block
    //**********************************************
    public function discussionAction()                                                                                                      {include(PRODUCT_MODULES_DIR.'/groups/discussion/action.index.php');}
    public function discussionsettingsAction()                                                                                              {include(PRODUCT_MODULES_DIR.'/groups/discussion/action.discussion.settings.php');}
    public function discussionhostsettingsAction()                                                                                          {include(PRODUCT_MODULES_DIR.'/groups/discussion/action.discussion.settings.host.php');}
    public function createtopicAction()                                                                                                     {include(PRODUCT_MODULES_DIR.'/groups/discussion/action.create.topic.php');}
    public function replytopicAction()                                                                                                      {include(PRODUCT_MODULES_DIR.'/groups/discussion/action.reply.topic.php');}
    public function loadDisTopicsAction($discussion_id, $currentPage)                                                                       {include(PRODUCT_MODULES_DIR.'/groups/discussion/xajax/action.loadTopics.php'); return $objResponse;}
    public function showDiscussionContentAction($discussion_id, $mode)                                                                      {include(PRODUCT_MODULES_DIR.'/groups/discussion/xajax/action.showDiscussionContent.php'); return $objResponse;}
    public function showSubgroupContentAction($group_id, $mode)                                                                             {include(PRODUCT_MODULES_DIR.'/groups/discussion/xajax/action.showSubgroupContent.php'); return $objResponse;}
    public function topicAction()                                                                                                           {include(PRODUCT_MODULES_DIR.'/groups/discussion/action.topic.php');}
    public function recenttopicAction()                                                                                                     {include(PRODUCT_MODULES_DIR.'/groups/discussion/action.recent.topic.php');}
    public function markalltopicsreadAction()                                                                                               {include(PRODUCT_MODULES_DIR.'/groups/discussion/action.markalltopicsread.php');}
    public function closePopupAction($name)                                                                                                 {include(PRODUCT_MODULES_DIR.'/groups/discussion/xajax/action.closePopup.php'); return $objResponse;}
    public function replyPostAction($x, $y, $post_id, $currentPage, $sortmode)                                                              {include(PRODUCT_MODULES_DIR.'/groups/discussion/xajax/action.replyPost.php'); return $objResponse;}
    public function replyPostDoAction($post_id, $content, $subscriptionType, $currentPage, $sortmode)                                       {include(PRODUCT_MODULES_DIR.'/groups/discussion/xajax/action.replyPostDo.php'); return $objResponse;}
    public function savePostReplyAction($post_id, $content)                                                                                       {include(PRODUCT_MODULES_DIR.'/groups/discussion/xajax/action.savePostReply.php'); return $objResponse;}
    public function editPostAction($x, $y, $post_id)                                                                                        {include(PRODUCT_MODULES_DIR.'/groups/discussion/xajax/action.editPost.php'); return $objResponse;}
    public function editPostDoAction($post_id, $content, $subscriptionType)                                                                 {include(PRODUCT_MODULES_DIR.'/groups/discussion/xajax/action.editPostDo.php'); return $objResponse;}
    public function deletePostAction($x, $y, $post_id)                                                                                      {include(PRODUCT_MODULES_DIR.'/groups/discussion/xajax/action.deletePost.php'); return $objResponse;}
    public function deletePostDoAction($post_id)                                                                                            {include(PRODUCT_MODULES_DIR.'/groups/discussion/xajax/action.deletePostDo.php'); return $objResponse;}
    public function emailAuthorAction($x, $y, $post_id)                                                                                     {include(PRODUCT_MODULES_DIR.'/groups/discussion/xajax/action.emailAuthor.php'); return $objResponse;}
    public function emailAuthorDoAction($post_id, $content)                                                                                 {include(PRODUCT_MODULES_DIR.'/groups/discussion/xajax/action.emailAuthorDo.php'); return $objResponse;}
    public function reportPostAction($x, $y, $post_id)                                                                                      {include(PRODUCT_MODULES_DIR.'/groups/discussion/xajax/action.reportPost.php'); return $objResponse;}
    public function reportPostDoAction($post_id)                                                                                            {include(PRODUCT_MODULES_DIR.'/groups/discussion/xajax/action.reportPostDo.php'); return $objResponse;}
    public function deleteTopicSubscriptionAction($subsctiption_id)                                                                         {include(PRODUCT_MODULES_DIR.'/groups/discussion/xajax/action.deleteTopicSubscription.php'); return $objResponse;}
    public function notifyTopicAction($x, $y, $topic_id)                                                                                    {include(PRODUCT_MODULES_DIR.'/groups/discussion/xajax/action.notifyTopic.php'); return $objResponse;}
    public function notifyTopicDoAction($topic_id, $subscriptionType)                                                                       {include(PRODUCT_MODULES_DIR.'/groups/discussion/xajax/action.notifyTopicDo.php'); return $objResponse;}
    public function chooseDiscussionForEditAction($discussion_id)                                                                           {include(PRODUCT_MODULES_DIR.'/groups/discussion/xajax/action.chooseDiscussionForEdit.php'); return $objResponse;}
    public function discussionsearchAction()                                                                                                {include(PRODUCT_MODULES_DIR.'/groups/discussion/action.discussion.search.php');}
    public function changeListSizeAction($size, $mode, $topic_id)                                                                           {include(PRODUCT_MODULES_DIR.'/groups/discussion/xajax/action.changeListSize.php'); return $objResponse;}
    public function moveTopicAction($topic_id)                                                                                              {include(PRODUCT_MODULES_DIR.'/groups/discussion/xajax/action.moveTopic.php'); return $objResponse;}
    public function moveTopicDoAction($topic_id, $discussion_id)                                                                            {include(PRODUCT_MODULES_DIR.'/groups/discussion/xajax/action.moveTopicDo.php'); return $objResponse;}
    public function removeTopicAction($topic_id)                                                                                            {include(PRODUCT_MODULES_DIR.'/groups/discussion/xajax/action.removeTopic.php'); return $objResponse;}
    public function removeTopicDoAction($topic_id)                                                                                          {include(PRODUCT_MODULES_DIR.'/groups/discussion/xajax/action.removeTopicDo.php'); return $objResponse;}
    public function closeTopicAction($topic_id)                                                                                             {include(PRODUCT_MODULES_DIR.'/groups/discussion/xajax/action.closeTopic.php'); return $objResponse;}
    public function reopenTopicAction($topic_id)                                                                                            {include(PRODUCT_MODULES_DIR.'/groups/discussion/xajax/action.reopenTopic.php'); return $objResponse;}

    public function creatediscussionsforgroupsAction()                                                                                      {include(PRODUCT_MODULES_DIR.'/groups/discussion/_action.create.discussions.for.groups.php');}
    public function updatepostspositionAction()                                                                                             {include(PRODUCT_MODULES_DIR.'/groups/discussion/_action.update.posts.position.php');}
    //**********************************************
    //  END Discussion Server Actions Block

    //  START Blog Actions Block
    //**********************************************
    public function blogAction()                                                                                                            {include(PRODUCT_MODULES_DIR.'/groups/blog/action.index.php');}
    public function blogDetailsAction()                                                                                                     {include(PRODUCT_MODULES_DIR.'/groups/blog/action.details.php');}
    public function blogCreateAction()                                                                                                      {include(PRODUCT_MODULES_DIR.'/groups/blog/action.create.php');}
    public function blogEditAction()                                                                                                        {include(PRODUCT_MODULES_DIR.'/groups/blog/action.edit.php');}

    public function blogRemoveAction($id, $handle = null)                                                                                   {include(PRODUCT_MODULES_DIR.'/groups/blog/xajax/action.remove.php'); return $objResponse;}
    public function blogRemoveCommentAction($id, $handle = null)                                                                            {include(PRODUCT_MODULES_DIR.'/groups/blog/xajax/action.remove.comment.php'); return $objResponse;}
    public function blogEditCommentAction($id, $handle = null)                                                                              {include(PRODUCT_MODULES_DIR.'/groups/blog/xajax/action.edit.comment.php'); return $objResponse;}
    //**********************************************
    //  END Blog Actions Block

    public function promotionAction()                                                                                                       {include(PRODUCT_MODULES_DIR.'/groups/promotion/action.promotion.php');}
    public function invite1Action()                                                                                                         {include(PRODUCT_MODULES_DIR.'/groups/promotion/action.invite1.php');}
    public function inviteconfirmAction()                                                                                                   {include(PRODUCT_MODULES_DIR.'/groups/action.inviteconfirm.php');}
    public function inviteComposeAction()                                                                                                   {include(PRODUCT_MODULES_DIR.'/groups/promotion/action.invitecompose.php');}
    public function invitationRemoveAction()                                                                                                {include(PRODUCT_MODULES_DIR.'/groups/promotion/action.invitationremove.php');}
    public function invitationgroupsRemoveAction()                                                                                          {include(PRODUCT_MODULES_DIR.'/groups/promotion/action.invitationgroupsremove.php');}
    public function inviteListAction()                                                                                                      {include(PRODUCT_MODULES_DIR.'/groups/promotion/action.invitelist.php');}
    public function inviteSearchRememberAction()                                                                                            {include(PRODUCT_MODULES_DIR.'/groups/promotion/action.invitesearchremember.php');}
    public function inviteSearchDeleteAction()                                                                                              {include(PRODUCT_MODULES_DIR.'/groups/promotion/action.invitesearchdelete.php');}
    public function addgroupsAction($params)									                                                            {include(PRODUCT_MODULES_DIR.'/groups/promotion/action.addgroups.php');return $objResponse;}
    public function nameinvitationAction($params = null)						                                                            {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.nameinvitation.php');return $objResponse;}
    public function groupsremoveAction($params = null)				        	                                                            {include(PRODUCT_MODULES_DIR.'/groups/promotion/action.invitationgroupsremove.php');return $objResponse;}
    public function invitationsremoveAction($params = null)					                                                                {include(PRODUCT_MODULES_DIR.'/groups/promotion/action.invitationremove.php');return $objResponse;}
    public function brandGalleryAction()                                                                                                    {include(PRODUCT_MODULES_DIR.'/groups/promotion/action.brandgallery.php');}
    public function webbadgesAction()                                                                                                       {include(PRODUCT_MODULES_DIR.'/groups/promotion/action.webbadges.php');}
    public function deletesearchAction($url)									                                                            {include(PRODUCT_MODULES_DIR.'/groups/promotion/action.deletesearch.php');return $objResponse;}

    public function gmapsinglegroupAction()                                                                                                 {include(PRODUCT_MODULES_DIR.'/groups/action.gmapsinglegroup.php');}
    public function gmapfullgroupAction()                                                                                                   {include(PRODUCT_MODULES_DIR.'/groups/action.gmapfullgroup.php');}

    //  START Hierarchy Functions
    //**********************************************
    public function hierarchyAction()                                                                                                       {include(PRODUCT_MODULES_DIR.'/groups/hierarchy/action.index.php');}
    /**
     * Hierarchy Actions
     */
    public function addHierarchyAction($x, $y)                                                                                              {include(PRODUCT_MODULES_DIR.'/groups/hierarchy/xajax/action.addHierarchy.php'); return $objResponse;}
    public function addHierarchyHandlerAction($name)                                                                                        {include(PRODUCT_MODULES_DIR.'/groups/hierarchy/xajax/action.addHierarchyHandler.php'); return $objResponse;}
    public function remaneHierarchyAction($x, $y, $curr_hid)                                                                                {include(PRODUCT_MODULES_DIR.'/groups/hierarchy/xajax/action.renameHierarchy.php'); return $objResponse;}
    public function renameHierarchyHandlerAction($curr_hid, $name)                                                                          {include(PRODUCT_MODULES_DIR.'/groups/hierarchy/xajax/action.renameHierarchyHandler.php'); return $objResponse;}
    public function deleteHierarchyAction($curr_hid)                                                                                        {include(PRODUCT_MODULES_DIR.'/groups/hierarchy/xajax/action.deleteHierarchy.php'); return $objResponse;}
    public function deleteHierarchyHandlerAction($curr_hid)                                                                                 {include(PRODUCT_MODULES_DIR.'/groups/hierarchy/xajax/action.deleteHierarchyHandler.php'); return $objResponse;}
    /**
     * Constraints Actions
     */
    public function changeConstraintsAction($curr_hid, $level, $value)                                                                      {include(PRODUCT_MODULES_DIR.'/groups/hierarchy/xajax/action.changeConstraints.php'); return $objResponse;}
    public function saveConstraintsAction($curr_hid, $data)                                                                                 {include(PRODUCT_MODULES_DIR.'/groups/hierarchy/xajax/action.saveConstraints.php'); return $objResponse;}
    /**
     * Options Actions
     */
    public function saveOptionsAction($curr_hid, $options)                                                                                  {include(PRODUCT_MODULES_DIR.'/groups/hierarchy/xajax/action.saveOptions.php'); return $objResponse;}
    /**
     * Grouping Actions
     */
    public function addGroupingAction($curr_hid)                                                                                            {include(PRODUCT_MODULES_DIR.'/groups/hierarchy/xajax/action.addGrouping.php'); return $objResponse;}
    public function removeGroupingAction($groupId, $curr_hid)                                                                               {include(PRODUCT_MODULES_DIR.'/groups/hierarchy/xajax/action.removeGrouping.php'); return $objResponse;}
    public function moveGroupingAction($groupid, $curr_hid, $dir)                                                                           {include(PRODUCT_MODULES_DIR.'/groups/hierarchy/xajax/action.moveGrouping.php'); return $objResponse;}

    public function changeHierarchyTypeAction($type)                                                                                        {include(PRODUCT_MODULES_DIR.'/groups/hierarchy/xajax/action.changeHierarchyType.php'); return $objResponse;}
    public function rendererTreeAction($stateid)                                                                                            {include(PRODUCT_MODULES_DIR.'/groups/hierarchy/xajax/action.rendererTree.php'); return $objResponse;}
    public function categoryChangeHandlerAction($catid, $groupid, $hid, $name)                                                              {include(PRODUCT_MODULES_DIR.'/groups/hierarchy/xajax/action.categoryChangeHandler.php'); return $objResponse;}
    public function addCategoryAction($catid, $groupid, $hid)                                                                               {include(PRODUCT_MODULES_DIR.'/groups/hierarchy/xajax/action.addCategory.php'); return $objResponse;}
    public function removeCategoryAction($catid, $groupid, $hid)                                                                            {include(PRODUCT_MODULES_DIR.'/groups/hierarchy/xajax/action.removeCategory.php'); return $objResponse;}
    public function addItemAction($catid, $groupId, $oldcatid)                                                                              {include(PRODUCT_MODULES_DIR.'/groups/hierarchy/xajax/action.addItem.php'); return $objResponse;}
    public function removeItemAction($catid, $groupId)                                                                                      {include(PRODUCT_MODULES_DIR.'/groups/hierarchy/xajax/action.removeItem.php'); return $objResponse;}
    public function saveTreeItemsAction($list)                                                                                              {include(PRODUCT_MODULES_DIR.'/groups/hierarchy/xajax/action.saveTreeItems.php'); return $objResponse;}
    public function orderCategoryAction($catid, $children, $mode, $oldCatId)                                                                {include(PRODUCT_MODULES_DIR.'/groups/hierarchy/xajax/action.orderCategory.php'); return $objResponse;}
    public function previewhierarchyAction()                                                                                                {include(PRODUCT_MODULES_DIR.'/groups/hierarchy/action.preview.php');}
    //public function mimemailAction()                                                                                                      {include(PRODUCT_MODULES_DIR.'/groups/hierarchy/action.mimemail.php');}

    //**********************************************
    // END Hierarchy Functions

    // XAJAX Action

    public function searchOnChangeCountryAction($countryId)                                                                                 {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.searchOnChangeCountry.php'); return $objResponse;}
    public function searchOnChangeStateAction($stateId)                                                                                     {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.searchOnChangeState.php'); return $objResponse;}

    // lists methods for ajax actions
    public function listsCollapseRecord($objResponse, $record_id, &$list)                                                                   {include(PRODUCT_MODULES_DIR.'/groups/xajax/lists/method.lists.collapse.record.php');}
    public function listsExpandRecord(&$objResponse, $record_id, &$list)                                                                    {include(PRODUCT_MODULES_DIR.'/groups/xajax/lists/method.lists.expand.record.php');}
    public function listsAppendRecord(&$objResponse, &$list)                                                                                {include(PRODUCT_MODULES_DIR.'/groups/xajax/lists/method.lists.append.record.php');}
    public function listsDeleteRecord(&$objResponse, $record_id, &$list)                                                                    {include(PRODUCT_MODULES_DIR.'/groups/xajax/lists/method.lists.delete.record.php');}
    public function listsVerify(&$list)                                                                                                     {include(PRODUCT_MODULES_DIR.'/groups/xajax/lists/method.lists.verify.php'); return $_error;}
    public function listsViewRefresh(&$objResponse, $list_id)                                                                               {include(PRODUCT_MODULES_DIR.'/groups/xajax/lists/method.lists.view.refresh.php');}
    // end lists methods for ajax actions

    public function changehostAction($link)														                                            {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.changehost.confirm.php'); return $objResponse;}
    public function removeMemberAction($link)													                                            {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.removemember.php'); return $objResponse;}
    public function declineMemberAction($link, $all = false)									                                            {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.declinemember.php'); return $objResponse;}
    public function listsAddSaveAction($record_id = null, $data = array())                                                                  {include(PRODUCT_MODULES_DIR.'/groups/xajax/lists/action.add.save.php'); return $objResponse;}
    public function listsAddDeleteRecordAction($record_id)                                                                                  {include(PRODUCT_MODULES_DIR.'/groups/xajax/lists/action.add.delete.record.php'); return $objResponse;}
    public function listsAddExpandAction($record_id = null)                                                                                 {include(PRODUCT_MODULES_DIR.'/groups/xajax/lists/action.add.expand.php'); return $objResponse;}
    public function listsAddPublishAction($data = array())                                                                                  {include(PRODUCT_MODULES_DIR.'/groups/xajax/lists/action.add.publish.php'); return $objResponse;}
    public function listsAddShareAction($share_id = null)                                                                                   {include(PRODUCT_MODULES_DIR.'/groups/xajax/lists/action.add.share.php'); return $objResponse;}
    public function listsAddUnshareAction($share_id = null)                                                                                 {include(PRODUCT_MODULES_DIR.'/groups/xajax/lists/action.add.unshare.php'); return $objResponse;}
    public function listsAddChangeTypeAction($type_id)                                                                                      {include(PRODUCT_MODULES_DIR.'/groups/xajax/lists/action.add.change.type.php'); return $objResponse;}


    public function listsEditSaveAction($record_id = null, $data = array())                                                                 {include(PRODUCT_MODULES_DIR.'/groups/xajax/lists/action.edit.save.php'); return $objResponse;}
    public function listsEditDeleteRecordAction($record_id, $contextId = null)                                                              {include(PRODUCT_MODULES_DIR.'/groups/xajax/lists/action.edit.delete.record.php'); return $objResponse;}
    public function listsEditExpandAction($record_id = null, $contextId = null)                                                             {include(PRODUCT_MODULES_DIR.'/groups/xajax/lists/action.edit.expand.php'); return $objResponse;}
    public function listsEditPublishAction($data = array())                                                                                 {include(PRODUCT_MODULES_DIR.'/groups/xajax/lists/action.edit.publish.php'); return $objResponse;}
    public function listsEditShareAction($share_id = null)                                                                                  {include(PRODUCT_MODULES_DIR.'/groups/xajax/lists/action.edit.share.php'); return $objResponse;}
    public function listsEditUnshareAction($share_id = null)                                                                                {include(PRODUCT_MODULES_DIR.'/groups/xajax/lists/action.edit.unshare.php'); return $objResponse;}
    public function listsEditChangeTypeAction($type_id)                                                                                     {include(PRODUCT_MODULES_DIR.'/groups/xajax/lists/action.edit.change.type.php'); return $objResponse;}

    public function listsViewSaveAction($record_id = null, $data=array(), $contextId = null)                                                                   {include(PRODUCT_MODULES_DIR.'/groups/xajax/lists/action.view.save.php'); return $objResponse;}
    public function listsViewAddFormAction($list_id, $contextId = null)                                                                                        {include(PRODUCT_MODULES_DIR.'/groups/xajax/lists/action.view.add.form.php'); return $objResponse;}
    public function listsViewExpandAction($record_id = null, $mode="view", $contextId = null)                                                                  {include(PRODUCT_MODULES_DIR.'/groups/xajax/lists/action.view.expand.php'); return $objResponse;}
    public function listsViewCollapseAction($record_id = null, $contextId = null)                                                                              {include(PRODUCT_MODULES_DIR.'/groups/xajax/lists/action.view.collapse.php'); return $objResponse;}
    public function listsViewAppendCommentAction($params = array(), $contextId = null)                                                                         {include(PRODUCT_MODULES_DIR.'/groups/xajax/lists/action.view.append.comment.php'); return $objResponse;}
    public function listsViewSaveCommentAction($comment_id, $commentText, $contextId = null)                                                                   {include(PRODUCT_MODULES_DIR.'/groups/xajax/lists/action.view.save.comment.php'); return $objResponse;}

    public function listsViewDeleteRecordAction($record_id, $contextId = null)                                                                                 {include(PRODUCT_MODULES_DIR.'/groups/xajax/lists/action.view.delete.record.php'); return $objResponse;}
    public function listsViewDeleteCommentAction($comment_id, $contextId = null)                                                                               {include(PRODUCT_MODULES_DIR.'/groups/xajax/lists/action.view.delete.comment.php'); return $objResponse;}
    public function listsViewRankRecordAction($record_id, $rank)                                                                            {include(PRODUCT_MODULES_DIR.'/groups/xajax/lists/action.view.rank.record.php'); return $objResponse;}
    public function listsViewOnchangeOrderAction($list_id, $order, $contextId = null)                                                                          {include(PRODUCT_MODULES_DIR.'/groups/xajax/lists/action.view.onchange.order.php'); return $objResponse;}
    public function listsVolunteerPopupShowAction($record_id = null)                                                                        {include(PRODUCT_MODULES_DIR.'/groups/xajax/lists/action.volunteer.popup.show.php'); return $objResponse;}
    public function listsVolunteerPopupCloseAction($data = array())                                                                         {include(PRODUCT_MODULES_DIR.'/groups/xajax/lists/action.volunteer.popup.close.php'); return $objResponse;}
    public function listsVolunteerDeleteAction($record_id = null, $volunteer_id = null)                                                     {include(PRODUCT_MODULES_DIR.'/groups/xajax/lists/action.volunteer.delete.php'); return $objResponse;}

    public function listsSharePopupShowAction($list_id, $group_id = null, $contextId = null)                                                                   {include(PRODUCT_MODULES_DIR.'/groups/xajax/lists/action.share.popup.show.php'); return $objResponse;}
    public function listsSharePopupCloseAction()                                                                                            {include(PRODUCT_MODULES_DIR.'/groups/xajax/lists/action.share.popup.close.php'); return $objResponse;}
    //	public function listsUnsharePopupShowAction($list_id)                                                                               {include(PRODUCT_MODULES_DIR.'/groups/xajax/lists/action.unshare.popup.show.php'); return $objResponse;}
    //	public function listsUnsharePopupCloseAction()                                                                                      {include(PRODUCT_MODULES_DIR.'/groups/xajax/lists/action.unshare.popup.close.php'); return $objResponse;}
    public function listsShareAction($list_id, $owner_type, $owner_id, $contextId = null)                                                                      {include(PRODUCT_MODULES_DIR.'/groups/xajax/lists/action.share.php'); return $objResponse;}
    public function listsUnshareAction($list_id, $owner_type, $owner_id, $contextId = null)                                                                    {include(PRODUCT_MODULES_DIR.'/groups/xajax/lists/action.unshare.php'); return $objResponse;}
    public function listsConfirmPopupShowAction($list_id, $action, $contextId = null)			                        {include(PRODUCT_MODULES_DIR.'/groups/xajax/lists/action.confirm.popup.show.php'); return $objResponse;}
    public function listsConfirmPopupCloseAction($data = array(), $contextId = null)                                                                           {include(PRODUCT_MODULES_DIR.'/groups/xajax/lists/action.confirm.popup.close.php'); return $objResponse;}

    public function listsAddListPopupShowAction($list_id)                                                                                   {include(PRODUCT_MODULES_DIR.'/groups/xajax/lists/action.add.list.popup.show.php'); return $objResponse;}
    public function listsAddListPopupCloseAction($data = array())                                                                           {include(PRODUCT_MODULES_DIR.'/groups/xajax/lists/action.add.list.popup.close.php'); return $objResponse;}


    /**
     * AJAX function - show new photo and all photo atributes in "view gallery"
     * @param int $photoId
     * @param int $galleryId
     * @return xAJAX response
     */
    public function loadphotoAction($photoId, $galleryId)                                                                                   {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.loadphoto.php'); return $objResponse;}
    /**
     * AJAX function - show new avatar in "view avatars" section
     * @param int $avatarId
     * @return xAJAX response
     */
    public function loadavatarAction($avatarId)                                                                                             {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.loadavatar.php'); return $objResponse;}
    public function showLocationAction()                                                                                                    {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.showLocation.php'); return $objResponse;}
    public function hideLocationAction()                                                                                                    {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.hideLocation.php'); return $objResponse;}
    public function showPrivilegesAction()                                                                                                  {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.showPrivileges.php'); return $objResponse;}
    public function hidePrivilegesAction()                                                                                                  {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.hidePrivileges.php'); return $objResponse;}
    public function showCohostsAction()                                                                                                     {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.showCohosts.php'); return $objResponse;}
    public function hideCohostsAction()                                                                                                     {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.hideCohosts.php'); return $objResponse;}
    public function addCohostAction($Id)	                                                                                                {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.addCohosts.php'); return $objResponse;}
    public function deleteCohostAction($Id)                                                                                                 {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.deleteCohosts.php'); return $objResponse;}


    public function showDetailsAction($groupId)                                                                                             {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.showDetails.php'); return $objResponse;}
    public function hideDetailsAction()                                                                                                     {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.hideDetails.php'); return $objResponse;}
    public function showResignAction()                                                                                                      {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.showResign.php'); return $objResponse;}
    public function hideResignAction()                                                                                                      {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.hideResign.php'); return $objResponse;}
    public function resignChangeHostAction($new_host, $confirm = true)                                                                      {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.resignChangeHost.php'); return $objResponse;}
    public function resignShowSendFormAction()                                                                                              {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.resignShowSendForm.php'); return $objResponse;}
    public function resignHandleSendFormAction($subject, $sbody, $confirm=true)                                                             {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.resignHandleSendForm.php'); return $objResponse;}
    public function convertFamilyAction()                                                                                                   {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.convertFamily.php'); return $objResponse;}

    public function showTransferAction( $form = null )                                                                                      {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.showTransfer.php'); return $objResponse;}
    public function hideTransferAction()                                                                                                    {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.hideTransfer.php'); return $objResponse;}
    public function doTransferAction($params)                                                                                               {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.doTransfer.php'); return $objResponse;}

    public function showGroupDeleteAction()                                                                                                 {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.showGroupDelete.php'); return $objResponse;}
    public function hideGroupDeleteAction()                                                                                                 {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.hideGroupDelete.php'); return $objResponse;}

    public function deleteGroupStep1Action()                                                                                                {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.deleteGroupStep1.php'); return $objResponse;}
    public function deleteGroupStep2Action()                                                                                                {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.deleteGroupStep2.php'); return $objResponse;}
    public function deleteGroupStep3Action()                                                                                                {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.deleteGroupStep3.php'); return $objResponse;}

    public function loadVenueDataAction($venueId)                                                                                           {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.loadVenue.php'); return $objResponse; }


    // THEMES
    public function uploadBCKGAvatarAction()                                   {include(PRODUCT_MODULES_DIR.'/groups/theme/xajax/action.uploadAvatar.php');  return $objResponse->getXML(); }
    public function uploadBCKGAvatarOKAction()                                 {include(PRODUCT_MODULES_DIR.'/groups/theme/xajax/action.uploadAvatarOK.php'); }
    public function copyBCKGAvatarOKAction($avatar_id)                         {include(PRODUCT_MODULES_DIR.'/groups/theme/xajax/action.copyAvatarOK.php'); return $objResponse->getXML();}
    public function ddImageSelectBCKGAvatarAction($pageNum=0, $perPage=10)     {include(PRODUCT_MODULES_DIR.'/groups/theme/xajax/action.selectAvatar.php'); return $objResponse->getXML(); }
    public function ddImageUpdateBCKGThumbsAreaAction($pageNum=0, $perPage=10) {include(PRODUCT_MODULES_DIR.'/groups/theme/xajax/action.updateThumbsArea.php'); return $objResponse->getXML(); }
    public function ddImageShowBCKGAvatarPreviewAction($url, $title, $id)      {include(PRODUCT_MODULES_DIR.'/groups/theme/xajax/action.showAvatarPreview.php'); return $objResponse->getXML(); }
    public function themeSaveAction($themeString, $clear=false)                {include(PRODUCT_MODULES_DIR.'/groups/contentblocks/action.themeSave.php'); return $objResponse->getXML(); }
    public function removeBCKGImageAction($path)                               {include(PRODUCT_MODULES_DIR.'/groups/contentblocks/action.removeBCKGImage.php'); return $objResponse->getXML(); }


    //Content Objects ================================================================================================================

    // Common AJAX Functions

    public function contentObjectsLoadFromDbAction($group_id/*ignored*/) { include(PRODUCT_MODULES_DIR.'/groups/contentblocks/action.contentObjectsLoadFromDb.php'); return $objResponse->getXML(); }

    public function getBlockContentInPreviewModeAction($CloneElID, $params = '') {include(PRODUCT_MODULES_DIR.'/groups/contentblocks/action.getblockcontentinpreviewmode.php'); return $objResponse->getXML(); }

    public function getBlockContentLightAction($targetId, $CloneElID, $ContentDivId, $ContentType, $editMode = 0, $Data = array())
    {
        $saveAfterLoad = false;
        $lightReload = true;
        include(PRODUCT_MODULES_DIR.'/groups/contentblocks/action.getblockcontent.php');
        return $objResponse->getXML();
    }

    public function getBlockContentPreviewAction($targetId, $CloneElID, $ContentDivId, $ContentType, $editMode = 0, $Data = array())
    {
        $saveAfterLoad = false;
        $previewMode = true;
        include(PRODUCT_MODULES_DIR.'/groups/contentblocks/action.getblockcontent.php');
        return $objResponse->getXML();
    }

    public function getBlockContentAction($targetId, $CloneElID, $ContentDivId, $ContentType, $editMode = 0, $Data = array())
    {
        $saveAfterLoad = false;
        include(PRODUCT_MODULES_DIR.'/groups/contentblocks/action.getblockcontent.php');
        return $objResponse->getXML();
    }

    public function getBlockContentThanSaveAction($targetId, $CloneElID, $ContentDivId, $ContentType, $editMode = 0, $Data = array())
    {
        $saveAfterLoad = true;
        include(PRODUCT_MODULES_DIR.'/groups/contentblocks/action.getblockcontent.php');
        return $objResponse->getXML();
    }
    public function contentObjectsSaveAction($items, $group_id/*ignored*/) { include(PRODUCT_MODULES_DIR.'/groups/contentblocks/action.contentObjectsSave.php'); return $objResponse->getXML(); }

    //ddGroupAvatar
    public function selectAvatarAction($cloneId, $refer="", $openAction = "open") { include(PRODUCT_MODULES_DIR.'/groups/contentblocks/ddGroupAvatar/action.selectAvatar.php'); return $objResponse->getXML(); }
    public function uploadAvatarAction($cloneId=0) { include(PRODUCT_MODULES_DIR.'/groups/contentblocks/ddGroupAvatar/action.uploadAvatar.php');  return $objResponse->getXML(); }
    public function loadAvatarsAction($refresh = false, $cloneId ='') { include(PRODUCT_MODULES_DIR.'/groups/contentblocks/ddGroupAvatar/action.loadAvatars.php'); return $objResponse->getXML(); }
    public function uploadAvatarOKAction() { include(PRODUCT_MODULES_DIR.'/groups/contentblocks/ddGroupAvatar/action.uploadAvatarOK.php'); }
    public function uploadAvatarCloseAction() { $objResponse = new xajaxResponse(); include(PRODUCT_MODULES_DIR.'/groups/contentblocks/ddGroupAvatar/action.uploadAvatarClose.php'); return $objResponse->getXML(); }
    public function showAvatarPreviewAction($url, $title, $id) { include(PRODUCT_MODULES_DIR.'/groups/contentblocks/ddGroupAvatar/action.showAvatarPreview.php'); return $objResponse->getXML(); }
    public function selectAvatarCloseAction() { include(PRODUCT_MODULES_DIR.'/groups/contentblocks/ddGroupAvatar/action.selectAvatarClose.php'); return $objResponse->getXML(); }
    public function loadAvatarInEditModeAction($cloneId, $avatar_id) { include(PRODUCT_MODULES_DIR.'/groups/contentblocks/ddGroupAvatar/action.loadAvatarInEditMode.php'); return $objResponse->getXML(); }

    //ddScript
    public function ddScriptSaveScriptCodeAction($code='', $contents='', $cHeight=0, $cloneId='') { include(PRODUCT_MODULES_DIR.'/groups/contentblocks/ddScript/action.ddScriptSaveScriptCode.php'); return $objResponse->getXML(); }
    public function ddScriptRemoveScriptCodeAction($code='',$cloneId='') { include(PRODUCT_MODULES_DIR.'/groups/contentblocks/ddScript/action.ddScriptRemoveScriptCode.php'); return $objResponse->getXML(); }

    //ddFamilyMap
    public function ddFamilyMapValidateAction($params,$cloneId='',$isRedrawElementLight) { include(PRODUCT_MODULES_DIR.'/groups/contentblocks/ddFamilyMap/action.ddFamilyMapValidate.php'); return $objResponse->getXML(); }

    //ddGroupMap
    public function ddGroupMapValidateAction($params,$cloneId='',$isRedrawElementLight) { include(PRODUCT_MODULES_DIR.'/groups/contentblocks/ddGroupMap/action.ddGroupMapValidate.php'); return $objResponse->getXML(); }

    //ddFamilyIcons
    public function selectBGIAction($cloneId, $refer="", $avatar_id=0, $openAction = "open") { include(PRODUCT_MODULES_DIR.'/groups/contentblocks/ddFamilyIcons/action.selectBGI.php'); return $objResponse->getXML(); }
    public function uploadBGIAction($cloneId=0) { include(PRODUCT_MODULES_DIR.'/groups/contentblocks/ddFamilyIcons/action.uploadBGI.php');  return $objResponse->getXML(); }
    public function loadBGIsAction($refresh = false, $cloneId ='') { include(PRODUCT_MODULES_DIR.'/groups/contentblocks/ddFamilyIcons/action.loadBGIs.php'); return $objResponse->getXML(); }
    public function uploadBGIOKAction() { include(PRODUCT_MODULES_DIR.'/groups/contentblocks/ddFamilyIcons/action.uploadBGIOK.php'); }
    //public function uploadBGICloseAction() { $objResponse = new xajaxResponse(); include(PRODUCT_MODULES_DIR.'/groups/contentblocks/ddFamilyIcons/action.uploadBGIClose.php'); return $objResponse->getXML(); }
    public function showBGIPreviewAction($url, $title, $id) { include(PRODUCT_MODULES_DIR.'/groups/contentblocks/ddFamilyIcons/action.showBGIPreview.php'); return $objResponse->getXML(); }
    //public function selectBGICloseAction() { include(PRODUCT_MODULES_DIR.'/groups/contentblocks/ddFamilyIcons/action.selectBGIClose.php'); return $objResponse->getXML(); }

    //ddGroupFamilyIcons
    public function selectGBGIAction($cloneId, $refer="", $avatar_id=0, $selectedFamily=0) { include(PRODUCT_MODULES_DIR.'/groups/contentblocks/ddGroupFamilyIcons/action.selectGBGI.php'); return $objResponse->getXML(); }
    public function showGBGIPreviewAction($url, $title, $id) { include(PRODUCT_MODULES_DIR.'/groups/contentblocks/ddGroupFamilyIcons/action.showGBGIPreview.php'); return $objResponse->getXML(); }
    //ddGroupDocuments
    public function documentsGetContentAction($el_id, $Data = array(), $blockType = 'wide') { include(PRODUCT_MODULES_DIR.'/groups/contentblocks/ddGroupDocuments/action.documentsGetContent.php'); return $objResponse->getXML(); }
    public function documentSelectAction($document_id, $js_array_key, $mousex, $mousey, $div_id, $element_id, $blockType = 'wide') { include(PRODUCT_MODULES_DIR.'/groups/contentblocks/ddGroupDocuments/action.documentSelect.php'); return $objResponse->getXML(); }
    public function shareMyDocumentsToGroupAction($documents_hash = array()) { include(PRODUCT_MODULES_DIR.'/groups/contentblocks/ddGroupDocuments/action.shareMyDocumentsToGroup.php'); return $objResponse->getXML(); }

    //ddGroupPhotos
    public function selectGalleryAction($cloneId, $gallery_index)           { include(PRODUCT_MODULES_DIR.'/groups/contentblocks/ddGroupPhotos/action.selectGallery.php'); return $objResponse->getXML(); }
    public function setGalleryAction($gallery_id, $cloneId, $gallery_index) { include(PRODUCT_MODULES_DIR.'/groups/contentblocks/ddGroupPhotos/action.setGallery.php'); return $objResponse->getXML(); }
    public function loadMyPhotosGalleryAction($gallery_id, $my_only = false){ include(PRODUCT_MODULES_DIR.'/groups/contentblocks/ddGroupPhotos/action.loadGallery.php'); return $objResponse->getXML(); }

    //ddImage
    public function ddImageSelectAvatarAction($cloneId, $pageNum=0, $perPage=10) {include(PRODUCT_MODULES_DIR.'/groups/contentblocks/ddGroupImage/action.selectAvatar.php'); return $objResponse->getXML(); }
    public function ddImageUpdateThumbsAreaAction($cloneId, $pageNum=0, $perPage=10) {include(PRODUCT_MODULES_DIR.'/groups/contentblocks/ddGroupImage/action.updateThumbsArea.php'); return $objResponse->getXML(); }
    public function ddImageShowAvatarPreviewAction($url, $title, $id) { include(PRODUCT_MODULES_DIR.'/groups/contentblocks/ddGroupImage/action.showAvatarPreview.php'); return $objResponse->getXML(); }

    //ddFamilyVideoContentBlock
    public function ddFamilyVideoContentBlockSelectAvatarAction($cloneId, $pageNum=0, $perPage=10) {include(PRODUCT_MODULES_DIR.'/groups/contentblocks/ddFamilyVideoContentBlock/action.selectAvatar.php'); return $objResponse->getXML(); }
    public function ddFamilyVideoContentBlockUpdateThumbsAreaAction($cloneId, $pageNum=0, $perPage=10) {include(PRODUCT_MODULES_DIR.'/groups/contentblocks/ddFamilyVideoContentBlock/action.updateThumbsArea.php'); return $objResponse->getXML(); }
    public function ddFamilyVideoContentBlockShowAvatarPreviewAction($videoId = 0) { include(PRODUCT_MODULES_DIR.'/groups/contentblocks/ddFamilyVideoContentBlock/action.showAvatarPreview.php'); return $objResponse->getXML(); }

    //DDPages END   =============================================================================================================




    //ajax BrandGallery actions

    public function brandgalleryuploadAction()		           {include(PRODUCT_MODULES_DIR.'/groups/promotion/action.brandgalleryupload.php'); return $objResponse;}
    public function brandgalleryuploadsaveAction()             {include(PRODUCT_MODULES_DIR.'/groups/promotion/action.brandgalleryuploadsave.php');}
    public function brandgallerydeleteAction()                 {include(PRODUCT_MODULES_DIR.'/groups/promotion/action.brandgallerydelete.php');}
    public function branditemDeleteAction($id) 		           {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.branditemDelete.php'); return $objResponse;}
    public function branditemDeleteDoAction($id)       		   {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.branditemDeleteDo.php'); return $objResponse;}
    public function loadBrandImageAction($imageId)             {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.loadbrandimage.php'); return $objResponse;}


    //ajax custom badge actions
    public function custombadgeuploadAction()       	      {include(PRODUCT_MODULES_DIR.'/groups/promotion/action.custombadgeupload.php'); return $objResponse;}
    public function custombadgeuploadsaveAction()             {include(PRODUCT_MODULES_DIR.'/groups/promotion/action.custombadgeuploadsave.php'); return $objResponse;}
    public function webbadgeDeleteAction($id) 		          {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.webbadgeDelete.php'); return $objResponse;}
    public function webbadgeDeleteDoAction($id)       		  {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.webbadgeDeleteDo.php'); return $objResponse;}

    //public function custombadgedeleteAction()                 {include(PRODUCT_MODULES_DIR.'/groups/promotion/action.custombadgedelete.php');}


    public function saveTempDataAction($data, $step, $gotoStep)    {include(PRODUCT_MODULES_DIR.'/groups/xajax/action.saveTempData.php'); return $objResponse; }


    //  START Group Videos Actions Block
    //**********************************************
    public function videosAction()                                                              {include(PRODUCT_MODULES_DIR.'/groups/videogallery/action.videos.php');}
    public function videogalleryAction()                                                             {include(PRODUCT_MODULES_DIR.'/groups/videogallery/action.videogallery.php');}
    public function videogalleryCreateAction()                                                       {include(PRODUCT_MODULES_DIR.'/groups/videogallery/action.videogalleryCreate.php');}
    public function videogalleryCreateTrackStatusAction()                                            { include(PRODUCT_MODULES_DIR.'/groups/videogallery/action.videogalleryCreateTrackStatus.php');}
    public function videogalleryTrackStatusAction()                                                  { include(PRODUCT_MODULES_DIR.'/groups/videogallery/xajax/action.videogalleryTrackStatus.php');}
    public function videogalleryEditAction()                                                         {include(PRODUCT_MODULES_DIR.'/groups/videogallery/action.videogalleryEdit.php');}
    public function videogalleryDeleteAction()                                                       {include(PRODUCT_MODULES_DIR.'/groups/videogallery/action.videogalleryDelete.php');}
    public function videogalleryUnshareAction()                                                      {include(PRODUCT_MODULES_DIR.'/groups/videogallery/action.videogalleryUnshare.php');}
    public function videogalleryViewAction()                                                         {include(PRODUCT_MODULES_DIR.'/groups/videogallery/action.videogalleryView.php');}
    public function videogalleryShareGroupAction($galleryId, $groupId, $application)                           {include(PRODUCT_MODULES_DIR.'/groups/videogallery/xajax/action.videogalleryShareGroup.php'); return $objResponse;}
    public function videogalleryShareGroupDoAction($galleryId, $groupId, $application)               {include(PRODUCT_MODULES_DIR.'/groups/videogallery/xajax/action.videogalleryShareGroupDo.php'); return $objResponse;}
    public function videogalleryShareFriendAction($galleryId, $application)                          {include(PRODUCT_MODULES_DIR.'/groups/videogallery/xajax/action.videogalleryShareFriend.php'); return $objResponse;}
    public function videogalleryShareFriendDoAction($galleryId, $data, $application)                 {include(PRODUCT_MODULES_DIR.'/groups/videogallery/xajax/action.videogalleryShareFriendDo.php'); return $objResponse;}
    public function videogalleryAddGalleryAction($galleryId, $videoId, $application)                 {include(PRODUCT_MODULES_DIR.'/groups/videogallery/xajax/action.videogalleryAddGallery.php'); return $objResponse;}
    public function videogalleryAddGalleryDoAction($galleryId, $videoId, $data)                      {include(PRODUCT_MODULES_DIR.'/groups/videogallery/xajax/action.videogalleryAddGalleryDo.php'); return $objResponse;}
    public function videogalleryAddVideoAction($galleryId, $videoId, $application)                   {include(PRODUCT_MODULES_DIR.'/groups/videogallery/xajax/action.videogalleryAddVideo.php'); return $objResponse;}
    public function videogalleryAddVideoDoAction($galleryId, $videoId, $data)                        {include(PRODUCT_MODULES_DIR.'/groups/videogallery/xajax/action.videogalleryAddVideoDo.php'); return $objResponse;}
    public function videogalleryUnShareDoAction($galleryId, $application)                            {include(PRODUCT_MODULES_DIR.'/groups/videogallery/xajax/action.videogalleryUnShareDo.php'); return $objResponse;}
    public function videogalleryUnShareGroupDoAction($galleryId, $groupId, $application)                {include(PRODUCT_MODULES_DIR.'/groups/videogallery/xajax/action.videogalleryUnShareGroupDo.php'); return $objResponse;}
    public function videogalleryUnShareFriendDoAction($galleryId, $userId, $application)                 {include(PRODUCT_MODULES_DIR.'/groups/videogallery/xajax/action.videogalleryUnShareFriendDo.php'); return $objResponse;}
    public function videogalleryDeleteGalleryAction($galleryId, $application, $new = false)          {include(PRODUCT_MODULES_DIR.'/groups/videogallery/xajax/action.videogalleryDeleteGallery.php'); return $objResponse;}
    public function videogalleryAddCommentDoAction($galleryId, $videoId, $message, $application)     {include(PRODUCT_MODULES_DIR.'/groups/videogallery/xajax/action.videogalleryAddCommentDo.php'); return $objResponse;}
    public function videogalleryUpdateCommentDoAction($galleryId, $videoId, $commentId, $message, $application)     {include(PRODUCT_MODULES_DIR.'/groups/videogallery/xajax/action.videogalleryUpdateCommentDo.php'); return $objResponse;}
    public function videogalleryDeleteCommentDoAction($galleryId, $videoId, $commentId, $application){include(PRODUCT_MODULES_DIR.'/groups/videogallery/xajax/action.videogalleryDeleteCommentDo.php'); return $objResponse;}
    public function videogalleryShowShareHistoryAction($galleryId, $application)                     {include(PRODUCT_MODULES_DIR.'/groups/videogallery/xajax/action.videogalleryShowShareHistory.php'); return $objResponse;}
    public function videogalleryShowShareHistoryDoAction()                                           {include(PRODUCT_MODULES_DIR.'/groups/videogallery/xajax/action.videogalleryShowShareHistoryDo.php'); return $objResponse;}
    public function videogalleryPublishAction($galleryId)                                            {include(PRODUCT_MODULES_DIR.'/groups/videogallery/xajax/action.videogalleryPublish.php'); return $objResponse;}
    public function videogalleryPublishDoAction($galleryId, $groupId, $application)                  {include(PRODUCT_MODULES_DIR.'/groups/videogallery/xajax/action.videogalleryPublishDo.php'); return $objResponse;}
    public function videogalleryMoveToAction($videoId)                                               {include(PRODUCT_MODULES_DIR.'/groups/videogallery/xajax/action.videogalleryMoveTo.php'); return $objResponse;}
    public function videogalleryMoveToDoAction($videoId, $collectionId)                              {include(PRODUCT_MODULES_DIR.'/groups/videogallery/xajax/action.videogalleryMoveToDo.php'); return $objResponse;}
    public function videoeditshowpageAction($page, $gallery_id, $expand_mode = 'none')                                           {include(PRODUCT_MODULES_DIR.'/groups/videogallery/xajax/action.videogalleryEditshowpage.php'); return $objResponse;}
    public function videogalleryuploadandsubmitAction($gallery_id = 0, $galleryTitle = null, $filescount = null)         {include(PRODUCT_MODULES_DIR.'/groups/videogallery/xajax/action.videogalleryuploadandsubmit.php'); return $objResponse;}
    /**
     * Methods for view videogallery page
     */
    public function videogalleryEditVideoAction($galleryId, $videoId, $application)                  {include(PRODUCT_MODULES_DIR.'/groups/videogallery/xajax/action.videogalleryEditVideo.php'); return $objResponse;}
    public function videogalleryEditVideoDoAction()                                                  {include(PRODUCT_MODULES_DIR.'/groups/videogallery/xajax/action.videogalleryEditVideoDo.php'); return $objResponse;}
    public function videogalleryDeleteVideoAction($galleryId, $videoId, $application)                {include(PRODUCT_MODULES_DIR.'/groups/videogallery/xajax/action.videogalleryDeleteVideo.php'); return $objResponse;}
    public function videogalleryDeleteRawVideoAction()                                               {include(PRODUCT_MODULES_DIR.'/groups/videogallery/action.videogalleryDeleteRawVideo.php');}
    /**
     * Actions for edit videogallery page
     *
     */
    public function videogalleryEditGallVideoAction($galleryId, $videoId)                            {include(PRODUCT_MODULES_DIR.'/groups/videogallery/xajax/action.videogalleryEditGallVideo.php'); return $objResponse;}
    public function videogalleryEditGallVideoDoAction()                                              {include(PRODUCT_MODULES_DIR.'/groups/videogallery/xajax/action.videogalleryEditGallVideoDo.php'); return $objResponse;}
    public function videogalleryCancelEditGallVideoAction($galleryId, $videoId)                      {include(PRODUCT_MODULES_DIR.'/groups/videogallery/xajax/action.videogalleryCancelEditGallVideo.php'); return $objResponse;}
    public function videogalleryDeleteGallVideoDoAction($galleryId, $videoId)                        {include(PRODUCT_MODULES_DIR.'/groups/videogallery/xajax/action.videogalleryDeleteGallVideoDo.php'); return $objResponse;}
    public function videogalleryUploadVideoAction($galleryId)                                        {include(PRODUCT_MODULES_DIR.'/groups/videogallery/xajax/action.videogalleryUploadVideo.php'); return $objResponse;}
    public function videogalleryUploadVideoDoAction()                                                {include(PRODUCT_MODULES_DIR.'/groups/videogallery/xajax/action.videogalleryUploadVideoDo.php'); return $objResponse;}
    public function videogalleryShowTmbPageAction($page, $galleryId)                                 {include(PRODUCT_MODULES_DIR.'/groups/videogallery/xajax/action.videogalleryShowTmbPage.php'); return $objResponse;}
    //**********************************************
    //  END Group Videos Actions Block
    public function messageSendToHostAction($params = null)                                         {include(PRODUCT_MODULES_DIR.'/groups/xajax/message.send.to.host.php'); return $objResponse; }
}
