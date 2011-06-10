<?php
Warecorp::addTranslation('/modules/users/BaseUsersController.xml');

class BaseUsersController extends Warecorp_Controller_Action
{
    public $currentUser;
    public $params;

    public function init()
    {
        parent::init();

        /**
         * Param "username" comes from Router variable ':username' in Bootstrap.php
         */
        if ( $this->_hasParam('username') ) {
            $this->_setParam('name', $this->_getParam('username'));
        }

        $request    = $this->getRequest();
        $action     = $request->getActionName();
        $controller = $request->getControllerName();
        
        $this->currentUser = null;
        $this->params = $request->getParams();

        if ( isset($this->params['name']) ) {
            if ( USE_USER_PATH && Warecorp_User::isUserExists('path', $this->params['name']) ) {
                $this->currentUser = new Warecorp_User('path', $this->params['name']);
            } else {
                $this->_redirect('http://'.BASE_HTTP_HOST.'/'.$this->_page->Locale.'/users/');
            }
        } elseif ( isset($this->params['userid']) ) {
            if ( Warecorp_User::isUserExists('id', $this->params['userid']) ) {
                $this->currentUser = new Warecorp_User('id', $this->params['userid']);
            } else {
                $this->_redirect('http://'.BASE_HTTP_HOST.'/'.$this->_page->Locale.'/users/');
            }
        }
        if ( $this->currentUser === null ) {
            //by Halauniou - if no current user - set to empty object
            //need for correct highlighting in top main menu
            if ( empty($this->currentUser) && $action == 'profile' ) {
                $this->_redirect('http://'.BASE_HTTP_HOST.'/'.$this->_page->Locale.'/users/');
            }
            $this->currentUser = new Warecorp_User();
        }

        if ( $this->currentUser->getId() == $this->_page->_user->getId() ) {
            $this->view->menuColor = "blue"; //?? neeed to remove ??
        } else {
            $this->view->menuColor = "red"; //?? need to remove ??
            //default breadcrumb
            $this->_page->breadcrumb = array(Warecorp::t("Members") => 'http://'.BASE_HTTP_HOST.'/'.$this->_page->Locale.'/users/');

                $this->_page->breadcrumb = array_merge($this->_page->breadcrumb,
                array($this->currentUser->getCity()->getState()->getCountry()->name => BASE_URL.'/'.$this->_page->Locale. "/users/index/view/allstates/country/" .$this->currentUser->getCity()->getState()->getCountry()->id. "/",
                $this->currentUser->getCity()->getState()->name => BASE_URL. "/" .$this->_page->Locale. "/users/index/view/allcities/state/" .$this->currentUser->getCity()->getState()->id. "/",
                $this->currentUser->getCity()->name.' ' => BASE_URL. "/" .$this->_page->Locale. "/users/search/preset/city/id/" .$this->currentUser->getCity()->id. "/")
                );
                $this->_page->breadcrumb += array($this->currentUser->getLogin() => null);// fix for numeric logins

        }

        //check permissions
        if ($this->currentUser->getId() != $this->_page->_user->getId()){ //if not a owner of profile
            /**
             * Choose configuration file
             * if file exits in root access folder get it else
             * get configuration file from ESA|EIA folder
             */
            if ( file_exists(ACCESS_RIGHTS_DIR.'account_owner_allowed.xml') ) {
                $cfg_access_file = ACCESS_RIGHTS_DIR.'account_owner_allowed.xml';
            } elseif ( file_exists(ACCESS_RIGHTS_DIR.IMPLEMENTATION_TYPE.'/account_owner_allowed.xml') ) {
                $cfg_access_file = ACCESS_RIGHTS_DIR.IMPLEMENTATION_TYPE.'/account_owner_allowed.xml';
            } else {
               throw new Zend_Exception('Configuration file \'account_owner_allowed.xml\' was not found.');
            }
            $ownerAccess = new Warecorp_Access();
            $ownerAccess->loadXmlConfig($cfg_access_file);
            if  ($ownerAccess->isAllowed($controller, $action)){
                $ownerAccess->redirectToLogin($this->currentUser->getUserPath('profile'));
                return;
            }
        }
		

        $this->view->Warecorp_User_AccessManager = Warecorp_User_AccessManager::getInstance();

        $this->view->currentUser = $this->currentUser;
		
		if ( in_array($action, array('login', 'restore', 'login.openid', 'wplogin', 'wpprofile')) ) {
			$this->view->setLayout('main_wide.tpl');
		}

        $this->_page->setTitle(Warecorp::t('Members'));


        // REGISTER AJAX FUNCTIONS
        //_______________________________________________________________
        $this->_page->Xajax->registerUriFunction("loadphoto", "/users/loadphoto/");
        $this->_page->Xajax->registerUriFunction("loadavatar", "/users/loadavatar/");
    }

    public static function addRelationAction($mailObj, $returnedEmailParams, $params)
    {
        $oFriends = $params['request'];
        if ($oFriends instanceof Warecorp_User_Friend_Request_Item)
            $oFriends->addRelation($mailObj->message);
        else return false;
    }

    public function noRouteAction()                                                                                                         {$this->_redirect('/'.$this->_page->Locale.'/');}

    public function rssItAction()                                                                                                           {include(PRODUCT_MODULES_DIR.'/users/action.rssit.php');}
    public function gmapsingleuserAction()                                                                                                  {include(PRODUCT_MODULES_DIR.'/users/action.gmapsingleuser.php');}

    public function indexAction()                                                                                                           {include(PRODUCT_MODULES_DIR.'/users/action.index.php');}
    public function loginAction()	                                                                                                        {include(PRODUCT_MODULES_DIR.'/users/action.login.php');}
    public function logoutAction()	                                                                                                        {include(PRODUCT_MODULES_DIR.'/users/action.logout.php');}

    public function searchAction()	                                                                                                        {include(PRODUCT_MODULES_DIR.'/users/action.search.php');}
    public function restoreAction()                                                                                                         {include(PRODUCT_MODULES_DIR.'/users/action.restore.php');}
    public function restorePasswordAction()                                                                                                 {include(PRODUCT_MODULES_DIR.'/users/action.restorePassword.php');}
    public function printAction()                                                                                                           {include(PRODUCT_MODULES_DIR.'/users/action.print.php');}

    //  START User Profile Actions Block
    //**********************************************
    public function summaryAction()                                                                                                         {include(PRODUCT_MODULES_DIR.'/users/action.profile.php');}
    public function profileAction()                                                                                                         {include(PRODUCT_MODULES_DIR.'/users/action.profile.php');}
    public function profiledefaultAction()                                                                                                  {include(PRODUCT_MODULES_DIR.'/users/action.profiledefault.php');}
    public function composeAction()                                                                                                         {include(PRODUCT_MODULES_DIR.'/users/action.compose.php');}

    //public function themebckgAction()                                                                                                     {include(PRODUCT_MODULES_DIR.'/users/action.themebckg.php');}
    public function themeAction()                                                                                                           {include(PRODUCT_MODULES_DIR.'/users/action.themenew.php');}
    //**********************************************
    //  END User Profile Actions Block



    //  START User Account Settings Actions Block
    //**********************************************
    public function settingsAction()															                                            {include(PRODUCT_MODULES_DIR.'/users/action.settings.php');}

    public function showBasicInformationAction()                                                                                            {include(PRODUCT_MODULES_DIR.'/users/xajax/action.showBasicInformation.php'); return $objResponse;}
    public function hideBasicInformationAction()                                                                                            {include(PRODUCT_MODULES_DIR.'/users/xajax/action.hideBasicInformation.php'); return $objResponse;}
    public function saveBasicInformationAction($params)                                                                                     {include(PRODUCT_MODULES_DIR.'/users/xajax/action.saveBasicInformation.php'); return $objResponse;}

    public function showLoginInformationAction()                                                                                            {include(PRODUCT_MODULES_DIR.'/users/xajax/action.showLoginInformation.php'); return $objResponse;}
    public function hideLoginInformationAction()                                                                                            {include(PRODUCT_MODULES_DIR.'/users/xajax/action.hideLoginInformation.php'); return $objResponse;}
    public function saveLoginInformationAction($params)                                                                                     {include(PRODUCT_MODULES_DIR.'/users/xajax/action.saveLoginInformation.php'); return $objResponse;}

    public function showAccountCancelAction()                                                                                               {include(PRODUCT_MODULES_DIR.'/users/xajax/action.showAccountCancel.php'); return $objResponse;}
    public function hideAccountCancelAction()                                                                                               {include(PRODUCT_MODULES_DIR.'/users/xajax/action.hideAccountCancel.php'); return $objResponse;}
    public function saveAccountCancelAction($params)                                                                                        {include(PRODUCT_MODULES_DIR.'/users/xajax/action.saveAccountCancel.php'); return $objResponse;}

    //**********************************************
    //  END User Account Settings Actions Block



    // START User Privacy Settings Actions Block
    //**********************************************
    public function privacyAction()                                                                                                         {include(PRODUCT_MODULES_DIR.'/users/action.privacy.php');}

    public function showCommunicationPreferencesAction()                                                                                    {include(PRODUCT_MODULES_DIR.'/users/xajax/privacy/action.showCommunicationPreferences.php'); return $objResponse;}
    public function hideCommunicationPreferencesAction()                                                                                    {include(PRODUCT_MODULES_DIR.'/users/xajax/privacy/action.hideCommunicationPreferences.php'); return $objResponse;}
    public function saveCommunicationPreferencesAction($params)                                                                             {include(PRODUCT_MODULES_DIR.'/users/xajax/privacy/action.saveCommunicationPreferences.php'); return $objResponse;}

    public function showContentVisibilityAction()                                                                                           {include(PRODUCT_MODULES_DIR.'/users/xajax/privacy/action.showContentVisibility.php'); return $objResponse;}
    public function hideContentVisibilityAction()                                                                                           {include(PRODUCT_MODULES_DIR.'/users/xajax/privacy/action.hideContentVisibility.php'); return $objResponse;}
    public function saveContentVisibilityAction($params)                                                                                    {include(PRODUCT_MODULES_DIR.'/users/xajax/privacy/action.saveContentVisibility.php'); return $objResponse;}

    public function showSearchResultSettingsAction()                                                                                        {include(PRODUCT_MODULES_DIR.'/users/xajax/privacy/action.showSearchResultSettings.php'); return $objResponse;}
    public function hideSearchResultSettingsAction()                                                                                        {include(PRODUCT_MODULES_DIR.'/users/xajax/privacy/action.hideSearchResultSettings.php'); return $objResponse;}
    public function saveSearchResultSettingsAction($params)                                                                                 {include(PRODUCT_MODULES_DIR.'/users/xajax/privacy/action.saveSearchResultSettings.php'); return $objResponse;}

    public function showBlockUsersAction()                                                                                                  {include(PRODUCT_MODULES_DIR.'/users/xajax/privacy/action.showBlockUsers.php'); return $objResponse;}
    public function hideBlockUsersAction()                                                                                                  {include(PRODUCT_MODULES_DIR.'/users/xajax/privacy/action.hideBlockUsers.php'); return $objResponse;}
    public function blockBlockUsersAction($params)                                                                                          {include(PRODUCT_MODULES_DIR.'/users/xajax/privacy/action.blockBlockUsers.php'); return $objResponse;}
    public function unblockBlockUsersAction($userId)                                                                                        {include(PRODUCT_MODULES_DIR.'/users/xajax/privacy/action.unblockBlockUsers.php'); return $objResponse;}
    public function autocompleteLoginsAction($filter = "", $function)                                                                       {include(PRODUCT_MODULES_DIR.'/users/xajax/privacy/action.autocompleteLogins.php'); return $objResponse;}

    //**********************************************
    // END User Privacy Settings Actions Block


    // START User Networks Settings Actions Block
    //**********************************************
    public function networksAction()                                                                                                        {include(PRODUCT_MODULES_DIR.'/users/action.networks.php');}
    //**********************************************
    // END User Networks Settings Actions Block

    //  START User Avatars Actions Block
    //**********************************************
    public function avatarsAction()        														                                            {include(PRODUCT_MODULES_DIR.'/users/avatar/action.avatar.php');}
    public function avatarDeleteAction()   														                                            {include(PRODUCT_MODULES_DIR.'/users/avatar/action.avatarDelete.php');}
    public function avatarUploadAction()   														                                            {include(PRODUCT_MODULES_DIR.'/users/avatar/action.avatarUpload.php');}
    public function avatarMakePrimaryAction()													                                            {include(PRODUCT_MODULES_DIR.'/users/avatar/action.avatarMakePrimary.php');}
    public function loadavatarAction($avatarId)                                                                                             {include(PRODUCT_MODULES_DIR.'/users/xajax/action.loadavatar.php'); return $objResponse;}
    public function avatarLoadFromGalleriesAction($jsCallbackCode)                                                                          {include(PRODUCT_MODULES_DIR.'/users/xajax/action.avatar.load.from.galleries.php'); return $objResponse;}
    public function doAvatarLoadFromGalleriesAction($photoId, $jsCallbackCode)                                                              {include(PRODUCT_MODULES_DIR.'/users/xajax/action.avatar.do.load.from.galleries.php'); return $objResponse;}
    //**********************************************
    //  END User Avatars Actions Block



    //  START User Addressbook Actions Block
    //**********************************************
    public function addressbookAction()                                 						                                            {include(PRODUCT_MODULES_DIR.'/users/addressbook/action.addressbook.php');}
    public function addressbookAddContactAction()                       						                                            {include(PRODUCT_MODULES_DIR.'/users/addressbook/action.addcontact.php');}
    public function addressbookDeleteContactAction($maillistId=null,$listContacts=null,$isShowed=null,$isContact='true', $isMailList='false')                       						{include(PRODUCT_MODULES_DIR.'/users/xajax/action.addressbookDeleteContact.php'); return $objResponse;}
    public function addressbookAjaxUtilitiesAction($maillistId = null,$existingContacts=null, $newContacts=null, $page=null, $pageSize=null, $orderby=null, $direction=null, $filter=null)                       {include(PRODUCT_MODULES_DIR.'/users/xajax/action.addressbookAjaxUtilities.php'); return $objResponse;}
    public function addressbookAjaxUtilitiesDoAction($params)                                                                               {include(PRODUCT_MODULES_DIR.'/users/xajax/action.addressbookAjaxUtilitiesDo.php'); return $objResponse;}
    public function addressbookAjaxUtilitiesDoMaillistAction($params)                                                                       {include(PRODUCT_MODULES_DIR.'/users/xajax/action.addressbookAjaxUtilitiesDoMaillist.php'); return $objResponse;}
    public function addressbookAddMailListAction()                                                                                          {include(PRODUCT_MODULES_DIR.'/users/addressbook/action.addmaillist.php');}
    public function addressbookMailListAction()                                                                                             {include(PRODUCT_MODULES_DIR.'/users/addressbook/action.maillist.php');}
    public function addressbookGroupAction()                                                                                                {include(PRODUCT_MODULES_DIR.'/users/addressbook/action.group.php');}
    public function addressbookViewMailListAction()                                                                                         {include(PRODUCT_MODULES_DIR.'/users/addressbook/action.maillist.php');}
    //**********************************************
    //  END User Addressbook Actions Block


    //  START User Friends Actions Block
    //**********************************************
    public function friendsAction()                                                                                                         {include(PRODUCT_MODULES_DIR.'/users/action.friends.php');}
    public function deleteFriendAction($friendId,$x,$y)                                                                                     {include(PRODUCT_MODULES_DIR.'/users/xajax/action.deleteFriend.php'); return $objResponse;}
    public function deleteFriendDoAction($friendId)                                                                                         {include(PRODUCT_MODULES_DIR.'/users/xajax/action.deleteFriendDo.php'); return $objResponse;}
    public function friendAction()                                                                                                          {include(PRODUCT_MODULES_DIR.'/users/action.friend.php');}
    public function acceptFriendRequestAction($requestId)                                                                                   {include(PRODUCT_MODULES_DIR.'/users/xajax/action.friendRequest.accept.php'); return $objResponse;}
    public function declineFriendRequestAction($requestId, $redirect = null)                                                                {include(PRODUCT_MODULES_DIR.'/users/xajax/action.friendRequest.decline.php'); return $objResponse;}
    public function declineConfirmAction($requestId, $redirect = null)                                                                      {include(PRODUCT_MODULES_DIR.'/users/xajax/action.friendRequest.decline.confirm.php'); return $objResponse;}

    public function findFriendsAction()                                                                                                     {include(PRODUCT_MODULES_DIR.'/users/addressbook/action.import.php');}

    /*
    public function addFriendPopupShowAction($friend_id, $x, $y)                                                                            {include(PRODUCT_MODULES_DIR.'/users/xajax/action.addfriend.popup.show.php'); return $objResponse;}
    public function addFriendPopupCloseAction()                                                                                             {include(PRODUCT_MODULES_DIR.'/users/xajax/action.addfriend.popup.close.php'); return $objResponse;}
    public function addFriendSaveAction($friend_id, $message)                                                                               {include(PRODUCT_MODULES_DIR.'/users/xajax/action.addfriend.save.php'); return $objResponse;}
    */
    public function addressbookInstructionAction($file_type='outlook')                                                                      {include(PRODUCT_MODULES_DIR.'/users/xajax/action.addressbookInstruction.php'); return $objResponse;}
    public function addressbookInstructionCloseAction()                                                                                     {include(PRODUCT_MODULES_DIR.'/users/xajax/action.addressbookInstructionClose.php'); return $objResponse;}
    public function attachmentDeleteAction($attach_id)                                                                                      {include(PRODUCT_MODULES_DIR.'/users/xajax/action.attachmentDelete.php'); return $objResponse;}
    //**********************************************
    //  END User Friends Actions Block



    //  START User Messages Actions Block
    //**********************************************
    public function deleteMessageAction($messageId,$showDialog,$x,$y)                                                                       {include(PRODUCT_MODULES_DIR.'/users/xajax/action.deleteMessage.php'); return $objResponse->getXML();}
    public function deleteMessageDoAction($messageId)                                                                                       {include(PRODUCT_MODULES_DIR.'/users/xajax/action.deleteMessageDo.php'); return $objResponse->getXML();}
    public function restoreMessageAction($messageId,$x,$y)                                                                                  {include(PRODUCT_MODULES_DIR.'/users/xajax/action.restoreMessage.php'); return $objResponse->getXML();}
    public function restoreMessageDoAction($messageId)                                                                                      {include(PRODUCT_MODULES_DIR.'/users/xajax/action.restoreMessageDo.php'); return $objResponse->getXML();}
    public function messagelistAction()                                                                                                     {include(PRODUCT_MODULES_DIR.'/users/messages/action.messagelist.php');}
    public function messageViewAction()                                                                                                     {include(PRODUCT_MODULES_DIR.'/users/messages/action.messageview.php');}
    public function messageComposeAction()                                                                                                  {include(PRODUCT_MODULES_DIR.'/users/messages/action.messagecompose.php');}
    public function messageDeleteAction()                                                                                                   {include(PRODUCT_MODULES_DIR.'/users/messages/action.messagedelete.php');}
    public function importContactsAction()                                                                                                  {include(PRODUCT_MODULES_DIR.'/users/addressbook/action.import.php');}
    //  @todo Ð¿ÑÐ¾Ð²ÐµÑÐ¸ÑÑ, Ð³Ð´Ðµ Ð¸ÑÐ¿Ð¾Ð»ÑÐ·ÑÑÑÑÑ
    public function attachgetAction()                                                                                                       {include(PRODUCT_MODULES_DIR.'/users/attachment/action.attachget.php');}
    public function attachdelAction()                                                                                                       {include(PRODUCT_MODULES_DIR.'/users/attachment/action.attachdel.php');}

    //xajax controllers for community with addressbook
    public function messagesAddAddressFromAddressbookAction($params=null, $page = null, $orderby=null, $direction=null, $filter=null)       {include(PRODUCT_MODULES_DIR.'/users/xajax/action.messagesAddAddressFromAddressbook.php'); return $objResponse;}
    public function messagesAddAddressToFieldAction($params=null, $old_value=null, $lists = null, $groups = null)                           {include(PRODUCT_MODULES_DIR.'/users/xajax/action.messagesAddAddressToField.php'); return $objResponse;}
    public function messagesDeleteAddressFromFieldAction($mode, $itemId, $lists = null, $groups = null)                                     {include(PRODUCT_MODULES_DIR.'/users/xajax/action.messagesDeleteAddressFromField.php'); return $objResponse;}


    public function autocompleteLoadContactListAction($filter = "", $fieldValue = '', $function_name)                                                         {include(PRODUCT_MODULES_DIR.'/users/xajax/action.autocompleteLoadContactList.php'); return $objResponse;}
    //**********************************************
    //  END User Messages Actions Block




    //  START User Groups Actions Block
    //  @todo move actions and templates in users/group folder
    //**********************************************
    public function groupsAction()                                                                                                          {include(PRODUCT_MODULES_DIR.'/users/action.groups.php');}
    public function groupresignAction()                                                                                                     {include(PRODUCT_MODULES_DIR.'/users/action.groupresign.php');}
    //**********************************************
    //  END User Groups Actions Block



    //  START Document Actions Block
    //**********************************************
    public function documentsAction()	                                                                                                    {include(PRODUCT_MODULES_DIR.'/users/documents/action.documents.php');}
    public function docgetAction()                                                                                                          {include(PRODUCT_MODULES_DIR.'/users/documents/action.document.docget.php');}
    public function documentChangeContent($objResponse, $objOwner, $currentFolderId)                                                           {include(PRODUCT_MODULES_DIR.'/users/documents/xajax/action.documentChangeContent.php'); return $objResponse;}
    public function documentChangeMainFolderAction($id)                                                                                     {include(PRODUCT_MODULES_DIR.'/users/documents/xajax/action.documentChangeMainFolder.php'); return $objResponse;}
    public function documentChangeFolderAction($folder_id)                                                                                  {include(PRODUCT_MODULES_DIR.'/users/documents/xajax/action.documentChangeFolder.php'); return $objResponse;}
    public function documentCreateFolderAction()                                                                                            {include(PRODUCT_MODULES_DIR.'/users/documents/xajax/action.documentCreateFolder.php'); return $objResponse;}
    //public function documentDeleteFolderAction($folder_id, $curr_folder_id)                                                                 {include(PRODUCT_MODULES_DIR.'/users/documents/xajax/action.documentDeleteFolder.php'); return $objResponse;}
    //public function documentEditFolderAction($name, $folder_id, $curr_folder_id)                                                            {include(PRODUCT_MODULES_DIR.'/users/documents/xajax/action.documentEditFolder.php'); return $objResponse;}
    public function documentAddAction()                                                                                                     {include(PRODUCT_MODULES_DIR.'/users/documents/xajax/action.documentAddFile.php'); return $objResponse;}
    //public function documentDeleteFileAction($file_id, $curr_folder_id)                                                                     {include(PRODUCT_MODULES_DIR.'/users/documents/xajax/action.documentDeleteFile.php'); return $objResponse;}
    //public function documentEditFileAction($file_id)                                                                                        {include(PRODUCT_MODULES_DIR.'/users/documents/xajax/action.documentEditFile.php'); return $objResponse;}
    //public function documentEditAction()                                                                                                    {include(PRODUCT_MODULES_DIR.'/users/documents/xajax/action.documentEditFileDo.php'); return $objResponse;}
    public function documentEditAction()                                                                                                    {include(PRODUCT_MODULES_DIR.'/users/documents/xajax/action.documentEdit.php'); return $objResponse;}
    public function documentShareFileAction()                                                                                               {include(PRODUCT_MODULES_DIR.'/users/documents/xajax/action.documentShareFile.php'); return $objResponse;}
    //public function documentShareFileDoAction($file_id, $owner_type, $owner_id)                                                             {include(PRODUCT_MODULES_DIR.'/users/documents/xajax/action.documentShareFileDo.php'); return $objResponse;}
    public function documentManageSharingAction()                                                                                           {include(PRODUCT_MODULES_DIR.'/users/documents/xajax/action.documentManageSharing.php'); return $objResponse;}
    public function documentUnshareFileAction()                                                                                             {include(PRODUCT_MODULES_DIR.'/users/documents/xajax/action.documentUnshareFile.php'); return $objResponse;}
    //public function documentUnshareFileDirectAction($file_id, $owner_type, $owner_id)                                                       {include(PRODUCT_MODULES_DIR.'/users/documents/xajax/action.documentUnshareFileDirect.php'); return $objResponse;}
    public function documentDeleteGroupAction()                                                                                             {include(PRODUCT_MODULES_DIR.'/users/documents/xajax/action.documentDeleteGroup.php'); return $objResponse;}
    //public function documentMoveGroupPreAction()                                                                                            {include(PRODUCT_MODULES_DIR.'/users/documents/xajax/action.documentMoveGroupPre.php'); return $objResponse;}
    public function documentMoveGroupAction()                                                                                               {include(PRODUCT_MODULES_DIR.'/users/documents/xajax/action.documentMoveGroup.php'); return $objResponse;}
    public function documentSortAction($curr_owner_id, $curr_folder_id, $curr_order, $curr_direction, $order)                               {include(PRODUCT_MODULES_DIR.'/users/documents/xajax/action.documentSort.php'); return $objResponse;}
    public function documentAddToMyAction()                                                                                                 {include(PRODUCT_MODULES_DIR.'/users/documents/xajax/action.documentAddToMy.php'); return $objResponse;}
    public function documentCheckInAction()                                                                                                 {include(PRODUCT_MODULES_DIR.'/users/documents/xajax/action.documentCheckIn.php'); return $objResponse;}
    public function documentCheckOutAction()                                                                                                {include(PRODUCT_MODULES_DIR.'/users/documents/xajax/action.documentCheckOut.php'); return $objResponse;}
    public function documentCancelCheckOutAction()                                                                                          {include(PRODUCT_MODULES_DIR.'/users/documents/xajax/action.documentCancelCheckOut.php'); return $objResponse;}
    public function documentRevisionsAction()                                                                                               {include(PRODUCT_MODULES_DIR.'/users/documents/xajax/action.documentRevisions.php'); return $objResponse;}
    public function documentRevertRevisionAction()                                                                                          {include(PRODUCT_MODULES_DIR.'/users/documents/xajax/action.documentRevertRevision.php'); return $objResponse;}
    public function documentAddWeblinkAction()                                                                                              {include(PRODUCT_MODULES_DIR.'/users/documents/xajax/action.documentAddWeblink.php'); return $objResponse;}

    //**********************************************
    //  END Document Actions Block



    //  START User Lists Actions Block
    //**********************************************
    public function listsAction()                                                                                                           {include(PRODUCT_MODULES_DIR.'/users/lists/action.lists.php');}
    public function listsSearchAction()                                                                                                     {include(PRODUCT_MODULES_DIR.'/users/lists/action.lists.search.php');}
    public function listsAddAction()                                                                                                        {include(PRODUCT_MODULES_DIR.'/users/lists/action.lists.add.php');}
    public function listsViewAction()                                                                                                       {include(PRODUCT_MODULES_DIR.'/users/lists/action.lists.view.php');}
    public function listsEditAction()                                                                                                       {include(PRODUCT_MODULES_DIR.'/users/lists/action.lists.edit.php');}
    public function listsSearchRememberAction()                                                                                             {include(PRODUCT_MODULES_DIR.'/users/lists/action.lists.search.remember.php');}
    public function listsSearchDelAction()                                                                                                  {include(PRODUCT_MODULES_DIR.'/users/lists/action.lists.search.delete.php');}

    public function listsExportAction()                                                                                                {include(PRODUCT_MODULES_DIR.'/users/lists/action.lists.export.php');}

    public function listsReloadShareWhomAction()                                                                                                    {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/action.share.whom.update.php'); return $objResponse;}
    public function listsCollapseRecord($objResponse, $record_id, &$list)                                                                   {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/method.lists.collapse.record.php');}
    public function listsExpandRecord(&$objResponse, $record_id, &$list)                                                                    {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/method.lists.expand.record.php');}
    public function listsAppendRecord(&$objResponse, &$list)                                                                                {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/method.lists.append.record.php');}
    public function listsConfirmDelete()                                                                                                    {include(PRODUCT_MODULES_DIR.'/users/lists/action.confirm.delete.record.php');}
    public function listsDeleteRecord(&$objResponse, $record_id, &$list)                                                                    {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/method.lists.delete.record.php');}
    public function listsVerify(&$list)                                                                                                     {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/method.lists.verify.php'); return $_error;}
    public function listsViewRefresh(&$objResponse, $list_id)                                                                               {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/method.lists.view.refresh.php');}
    public function listsAddSaveAction($record_id = null, $data = array())                                                                  {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/action.add.save.php'); return $objResponse;}
    public function listsAddDeleteRecordAction($record_id)                                                                                  {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/action.add.delete.record.php'); return $objResponse;}
    public function listsAddExpandAction($record_id = null)                                                                                 {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/action.add.expand.php'); return $objResponse;}
    public function listsAddPublishAction($data = array())                                                                                  {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/action.add.publish.php'); return $objResponse;}
    public function listsAddShareAction($share_id = null)                                                                                   {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/action.add.share.php'); return $objResponse;}
    public function listsAddUnshareAction($share_id = null)                                                                                 {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/action.add.unshare.php'); return $objResponse;}
    public function listsAddChangeTypeAction($type_id)                                                                                      {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/action.add.change.type.php'); return $objResponse;}

    public function listsEditSaveAction($record_id = null, $data = array())                                                                 {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/action.edit.save.php'); return $objResponse;}
    public function listsEditDeleteRecordAction($record_id)                                                                                 {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/action.edit.delete.record.php'); return $objResponse;}
    public function listsEditExpandAction($record_id = null)                                                                                {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/action.edit.expand.php'); return $objResponse;}
    public function listsEditPublishAction($data = array())                                                                                 {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/action.edit.publish.php'); return $objResponse;}
    public function listsEditShareAction($share_id = null)                                                                                  {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/action.edit.share.php'); return $objResponse;}
    public function listsEditUnshareAction($share_id = null)                                                                                {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/action.edit.unshare.php'); return $objResponse;}
    public function listsEditChangeTypeAction($type_id)                                                                                     {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/action.edit.change.type.php'); return $objResponse;}

    public function listsViewSaveAction($record_id = null, $data=array())                                                                   {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/action.view.save.php'); return $objResponse;}
    public function listsViewAddFormAction($list_id)                                                                                        {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/action.view.add.form.php'); return $objResponse;}
    public function listsViewExpandAction($record_id = null, $mode="view")                                                                  {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/action.view.expand.php'); return $objResponse;}
    public function listsViewCollapseAction($record_id = null)                                                                              {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/action.view.collapse.php'); return $objResponse;}
    public function listsViewAppendCommentAction($params=array())                                                                           {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/action.view.append.comment.php'); return $objResponse;}
    public function listsViewSaveCommentAction($comment_id, $commentText)                                                                   {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/action.view.save.comment.php'); return $objResponse;}

    public function listsViewDeleteRecordAction($record_id)                                                                                 {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/action.view.delete.record.php'); return $objResponse;}
    public function listsViewDeleteCommentAction($comment_id)                                                                               {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/action.view.delete.comment.php'); return $objResponse;}
    public function listsViewRankRecordAction($record_id, $rank)                                                                            {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/action.view.rank.record.php'); return $objResponse;}
    public function listsViewOnchangeOrderAction($list_id, $order)                                                                          {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/action.view.onchange.order.php'); return $objResponse;}
    public function listsVolunteerPopupShowAction($record_id = null)                                                                        {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/action.volunteer.popup.show.php'); return $objResponse;}
    public function listsVolunteerPopupCloseAction($data = array())                                                                         {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/action.volunteer.popup.close.php'); return $objResponse;}
    public function listsVolunteerDeleteAction($record_id = null, $volunteer_id = null)                                                     {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/action.volunteer.delete.php'); return $objResponse;}

    public function listsSharePopupShowAction($list_id, $group_id = null)                                                                   {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/action.share.popup.show.php'); return $objResponse;}
    public function listsSharePopupCloseAction()                                                                                            {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/action.share.popup.close.php'); return $objResponse;}
    //    public function listsUnsharePopupShowAction($list_id)                                                                             {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/action.unshare.popup.show.php'); return $objResponse;}
    //    public function listsUnsharePopupCloseAction()                                                                                    {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/action.unshare.popup.close.php'); return $objResponse;}
    public function listsShareAction($list_id, $owner_type, $owner_id)                                                                      {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/action.share.php'); return $objResponse;}
    public function listsUnshareAction($list_id, $owner_type, $owner_id)                                                                    {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/action.unshare.php'); return $objResponse;}
    public function listsConfirmPopupShowAction($list_id, $action)                                                                          {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/action.confirm.popup.show.php'); return $objResponse;}
    public function listsConfirmPopupCloseAction($data = array())                                                                           {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/action.confirm.popup.close.php'); return $objResponse;}

    public function listsAddListPopupShowAction($list_id)                                                                                   {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/action.add.list.popup.show.php'); return $objResponse;}
    public function listsAddListPopupCloseAction($data = array())                                                                           {include(PRODUCT_MODULES_DIR.'/users/xajax/lists/action.add.list.popup.close.php'); return $objResponse;}
    //**********************************************
    //  END User Lists Actions Block



    //  START User Photos Actions Block
    //**********************************************
    public function photosAction()		                                                                                                    {include(PRODUCT_MODULES_DIR.'/users/gallery/action.photos.php');}
    public function photossearchAction()                                                                                                    {include(PRODUCT_MODULES_DIR.'/users/gallery/action.photossearch.php');}
    public function photossearchdeleteAction($searchId)											                                            {include(PRODUCT_MODULES_DIR.'/users/gallery/xajax/action.searchDelete.php');return $objResponse;}

    public function popupAction()   	                                                                                                    {include(PRODUCT_MODULES_DIR.'/users/gallery/action.popup.php');}
    public function galleryAction()	                                                                                                        {include(PRODUCT_MODULES_DIR.'/users/gallery/action.gallery.php');}
    public function galleryCreateAction()                                                                                                   {include(PRODUCT_MODULES_DIR.'/users/gallery/action.galleryCreate.php');}
    public function galleryEditAction()                                                                                                     {include(PRODUCT_MODULES_DIR.'/users/gallery/action.galleryEdit.php');}
    public function galleryDeleteAction()                                                                                                   {include(PRODUCT_MODULES_DIR.'/users/gallery/action.galleryDelete.php');}
    public function galleryUnshareAction()                                                                                                  {include(PRODUCT_MODULES_DIR.'/users/gallery/action.galleryUnshare.php');}
    public function galleryViewAction()                                                                                                     {include(PRODUCT_MODULES_DIR.'/users/gallery/action.galleryView.php');}
    //public function loadphotoAction($photoId, $galleryId)                                                                                 {include(PRODUCT_MODULES_DIR.'/users/xajax/action.loadphoto.php'); return $objResponse;}
    public function galleryShareGroupAction($galleryId, $groupId, $application)                                                                       {include(PRODUCT_MODULES_DIR.'/users/gallery/xajax/action.galleryShareGroup.php'); return $objResponse;}
    public function galleryShareGroupDoAction($galleryId, $groupId, $application)                                                           {include(PRODUCT_MODULES_DIR.'/users/gallery/xajax/action.galleryShareGroupDo.php'); return $objResponse;}
    public function galleryShareFriendAction($galleryId, $application)                                                                      {include(PRODUCT_MODULES_DIR.'/users/gallery/xajax/action.galleryShareFriend.php'); return $objResponse;}
    public function galleryShareFriendDoAction($galleryId, $data, $application)                                                             {include(PRODUCT_MODULES_DIR.'/users/gallery/xajax/action.galleryShareFriendDo.php'); return $objResponse;}
    public function galleryAddGalleryAction($galleryId, $photoId, $application)                                                             {include(PRODUCT_MODULES_DIR.'/users/gallery/xajax/action.galleryAddGallery.php'); return $objResponse;}
    public function galleryAddGalleryDoAction($galleryId, $photoId, $data)                                                                  {include(PRODUCT_MODULES_DIR.'/users/gallery/xajax/action.galleryAddGalleryDo.php'); return $objResponse;}
    public function galleryAddPhotoAction($galleryId, $photoId, $application)                                                               {include(PRODUCT_MODULES_DIR.'/users/gallery/xajax/action.galleryAddPhoto.php'); return $objResponse;}
    public function galleryAddPhotoDoAction($galleryId, $photoId, $data)                                                                    {include(PRODUCT_MODULES_DIR.'/users/gallery/xajax/action.galleryAddPhotoDo.php'); return $objResponse;}
    public function galleryUnShareDoAction($galleryId, $application)                                                                        {include(PRODUCT_MODULES_DIR.'/users/gallery/xajax/action.galleryUnShareDo.php'); return $objResponse;}
    public function galleryUnShareGroupDoAction($galleryId, $groupId, $application)				                                            {include(PRODUCT_MODULES_DIR.'/users/gallery/xajax/action.galleryUnShareGroupDo.php'); return $objResponse;}
    public function galleryUnShareFriendDoAction($galleryId, $userId, $application)				                                            {include(PRODUCT_MODULES_DIR.'/users/gallery/xajax/action.galleryUnShareFriendDo.php'); return $objResponse;}
    public function galleryStopWatchingDoAction($galleryId, $application)                                                                   {include(PRODUCT_MODULES_DIR.'/users/gallery/xajax/action.galleryStopWatchingDo.php'); return $objResponse;}
    public function galleryDeleteGalleryAction($galleryId, $application, $new = false)                                                      {include(PRODUCT_MODULES_DIR.'/users/gallery/xajax/action.galleryDeleteGallery.php'); return $objResponse;}
    public function galleryAddCommentDoAction($galleryId, $photoId, $message, $application)                                                 {include(PRODUCT_MODULES_DIR.'/users/gallery/xajax/action.galleryAddCommentDo.php'); return $objResponse;}
    public function galleryUpdateCommentDoAction($galleryId, $photoId, $commentId, $message, $application)                                  {include(PRODUCT_MODULES_DIR.'/users/gallery/xajax/action.galleryUpdateCommentDo.php'); return $objResponse;}
    public function galleryDeleteCommentDoAction($galleryId, $photoId, $commentId, $application)                                            {include(PRODUCT_MODULES_DIR.'/users/gallery/xajax/action.galleryDeleteCommentDo.php'); return $objResponse;}
    public function galleryShowShareHistoryAction($galleryId, $application)                                                                 {include(PRODUCT_MODULES_DIR.'/users/gallery/xajax/action.galleryShowShareHistory.php'); return $objResponse;}
    public function galleryShowShareHistoryDoAction()                                                                                       {include(PRODUCT_MODULES_DIR.'/users/gallery/xajax/action.galleryShowShareHistoryDo.php'); return $objResponse;}
    public function galleryMoveToAction($photoId)                                                                                           {include(PRODUCT_MODULES_DIR.'/users/gallery/xajax/action.galleryMoveTo.php'); return $objResponse;}
    public function galleryMoveToDoAction($photoId, $galleryId)                                                                             {include(PRODUCT_MODULES_DIR.'/users/gallery/xajax/action.galleryMoveToDo.php'); return $objResponse;}
    public function editshowpageAction($page, $gallery_id, $expand_mode = 'none')                                                           {include(PRODUCT_MODULES_DIR.'/users/gallery/xajax/action.galleryEditshowpage.php'); return $objResponse;}
    public function galleryuploadandsubmitAction($gallery_id = 0, $galleryTitle = null, $filescount = null)                                 {include(PRODUCT_MODULES_DIR.'/users/gallery/xajax/action.galleryuploadandsubmit.php'); return $objResponse;}
    /**
     * Methods for view gallery page
     */
    public function galleryEditPhotoAction($galleryId, $photoId, $application)                                                              {include(PRODUCT_MODULES_DIR.'/users/gallery/xajax/action.galleryEditPhoto.php'); return $objResponse;}
    public function galleryEditPhotoDoAction()                                                                                              {include(PRODUCT_MODULES_DIR.'/users/gallery/xajax/action.galleryEditPhotoDo.php'); return $objResponse;}
    public function galleryDeletePhotoAction($galleryId, $photoId, $application)                                                            {include(PRODUCT_MODULES_DIR.'/users/gallery/xajax/action.galleryDeletePhoto.php'); return $objResponse;}
    /**
     * Actions for edit gallery page
     *
     */
    public function galleryEditGallPhotoAction($galleryId, $photoId)                                                                        {include(PRODUCT_MODULES_DIR.'/users/gallery/xajax/action.galleryEditGallPhoto.php'); return $objResponse;}
    public function galleryEditGallPhotoDoAction()                                                                                          {include(PRODUCT_MODULES_DIR.'/users/gallery/xajax/action.galleryEditGallPhotoDo.php'); return $objResponse;}
    public function galleryCancelEditGallPhotoAction($galleryId, $photoId)                                                                  {include(PRODUCT_MODULES_DIR.'/users/gallery/xajax/action.galleryCancelEditGallPhoto.php'); return $objResponse;}
    public function galleryDeleteGallPhotoDoAction($galleryId, $photoId)                                                                    {include(PRODUCT_MODULES_DIR.'/users/gallery/xajax/action.galleryDeleteGallPhotoDo.php'); return $objResponse;}
    public function galleryUploadPhotoAction($galleryId)                                                                                    {include(PRODUCT_MODULES_DIR.'/users/gallery/xajax/action.galleryUploadPhoto.php'); return $objResponse;}
    public function galleryUploadPhotoDoAction()                                                                                            {include(PRODUCT_MODULES_DIR.'/users/gallery/xajax/action.galleryUploadPhotoDo.php'); return $objResponse;}

    public function galleryShowTmbPageAction($page, $galleryId)                                                                             {include(PRODUCT_MODULES_DIR.'/users/gallery/xajax/action.galleryShowTmbPage.php'); return $objResponse;}

    public function imageRotateAction($galleryId, $photoId, $direction, $elementId)                                                         {include(PRODUCT_MODULES_DIR.'/users/gallery/xajax/action.imageRotate.php'); return $objResponse;}


    //**********************************************
    //  END User Photos Actions Block



    //  START Discussion Server Actions Block
    //**********************************************
    public function discussionAction()                                                                                                      {include(PRODUCT_MODULES_DIR.'/users/discussion/action.index.php');}
    public function excludeTopicAction($topic_id)                                                                                           {include(PRODUCT_MODULES_DIR.'/users/discussion/xajax/action.excludeTopic.php'); return $objResponse;}
    public function excludeTopicDoAction($topic_id)                                                                                         {include(PRODUCT_MODULES_DIR.'/users/discussion/xajax/action.excludeTopicDo.php'); return $objResponse;}
    public function discussionShowTopicsAction($discussion_id)                                                                              {include(PRODUCT_MODULES_DIR.'/users/discussion/xajax/action.show.topics.php'); return $objResponse;}

    //**********************************************
    //  END Discussion Server Actions Block



    //  START User Bookmarks Actions Block
    //**********************************************
    //public function bookmarksAction()                                                                                                       {include(PRODUCT_MODULES_DIR.'/users/action.bookmark.settings.php');}
    public function bookmarkItAction()                                                                                                      {include(PRODUCT_MODULES_DIR.'/users/action.bookmarkit.php');}
    //**********************************************
    //  END User Bookmarks Actions Block


    /*
     +-----------------------------------
     |
     | User Calendar Actions Block
     |
     +-----------------------------------
    */
    //  START User Calendar Actions Block
    //**********************************************
    public function calendarSearchIndexAction()                                                                                             {include(PRODUCT_MODULES_DIR.'/users/calendar/action.calendar.search.index.php');}
    public function calendarSearchAction()                                                                                                  {include(PRODUCT_MODULES_DIR.'/users/calendar/action.calendar.search.php');}
    public function calendarSearchRememberAction()                                                                                          {include(PRODUCT_MODULES_DIR.'/users/calendar/action.calendar.search.remember.php');}
    public function calendarSearchDelAction()                                                                                               {include(PRODUCT_MODULES_DIR.'/users/calendar/action.calendar.search.delete.php');}

    public function addAddressFromAddressbookAction($params=null, $page = null, $orderby=null, $direction=null, $filter=null)               {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.addAddressFromAddressbook.php'); return $objResponse;}
    public function addAddressToFieldAction($params=null, $old_value=null, $lists = null, $groups = null)                                   {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.addAddressToField.php'); return $objResponse;}
    public function deleteAddressFromFieldAction($mode, $itemId, $lists = null, $groups = null)                                             {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.deleteAddressFromField.php'); return $objResponse;}

    /**
    * @desc
    */
    public function calendarActionConfirmAction()                                                                                           {include(PRODUCT_MODULES_DIR.'/users/calendar/action.confirm.php');}
    public function calendarMonthViewAction()                                                                                               {include(PRODUCT_MODULES_DIR.'/users/calendar/action.month.view.php');}
    public function calendarListViewAction()                                                                                                {include(PRODUCT_MODULES_DIR.'/users/calendar/action.list.view.php');}
    public function calendarMapViewAction()                                                                                                 {include(PRODUCT_MODULES_DIR.'/users/calendar/action.map.view.php');}
    public function calendarEventCreateAction()                                                                                             {include(PRODUCT_MODULES_DIR.'/users/calendar/action.event.create.php');}
    public function calendarEventViewAction()                                                                                               {include(PRODUCT_MODULES_DIR.'/users/calendar/action.event.view.php');}
    public function calendarEventEditAction()                                                                                               {include(PRODUCT_MODULES_DIR.'/users/calendar/action.event.edit.php');}
    public function calendarEventCopyDoAction()                                                                                             {include(PRODUCT_MODULES_DIR.'/users/calendar/action.event.copy.php');}
    public function calendarEventApplyRequestAction()                                                                                       {include(PRODUCT_MODULES_DIR.'/users/calendar/action.event.apply.request.php');}
    public function calendarEventDocgetAction()                                                                                             {include(PRODUCT_MODULES_DIR.'/users/calendar/action.event.docget.php');}
    public function calendarEventAttendeeDownloadAction()                                                                                   {include(PRODUCT_MODULES_DIR.'/users/calendar/action.event.attendee.download.php');}
    public function calendarEventAttendeePrintAction()                                                                                      {include(PRODUCT_MODULES_DIR.'/users/calendar/action.event.attendee.print.php');}
    public function calendarEventExportAction()                                                                                             {include(PRODUCT_MODULES_DIR.'/users/calendar/action.event.export.php');}


    /**
     * @desc
     */
    public function calendarEventAddToMyAction($id, $uid, $handle = null)                                                                   {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.event.add.to.my.php'); return $objResponse;}
    public function calendarEventCancelAction($mode, $id, $uid, $view = 'month', $year = null, $month = null, $day = null, $handle = false) {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.event.cancel.php'); return $objResponse;}
    public function calendarEventEasyAddAction($year = null, $month = null, $day = null, $handle = false)                                   {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.event.easy.add.php'); return $objResponse;}
    public function calendarEventAttendeeAction($id, $uid, $view = 'month', $handle = false, $date = null, $params = array())               {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.event.attendee.php'); return $objResponse;}
    public function calendarEventAttendeeSignupAction($id, $uid, $view = 'month', $handle = false)                                          {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.event.attendee.signup.php'); return $objResponse;}
    public function calendarEventAttendeeViewAction($id, $uid)                                                                              {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.event.attendee.view.php'); return $objResponse;}
    public function calendarEventCopyAction($id, $uid, $handle = false)                                                                     {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.event.copy.php'); return $objResponse;}
    public function calendarEventShareAction($id, $uid, $mode = null, $handle = false)                                                      {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.event.share.php'); return $objResponse;}
    public function calendarEventUnShareAction($id, $uid, $mode = null, $handle = false)                                                    {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.event.unshare.php'); return $objResponse;}
    public function calendarClientUnshareEventAction($id, $uid, $handle = false)                                                            {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.event.client.unshare.php'); return $objResponse;}
    public function calendarEventInviteAction($id, $uid, $handle = false)                                                                   {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.event.invite.php'); return $objResponse;}
    public function calendarEventRemoveGuestAction($id, $uid, $handle = false)                                                              {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.event.remove.guest.php'); return $objResponse;}
    public function calendarEventRemoveMeAction($id, $uid, $handle = false)                                                                 {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.event.remove.me.php'); return $objResponse;}
    public function calendarEventSendMessageAction($id, $uid, $handle = false)                                                              {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.event.send.message.php'); return $objResponse;}
    public function calendarEventOrganizerSendMessageAction($id, $uid, $handle = false)                                                     {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.event.organizer.send.message.php'); return $objResponse;}
    public function calendarEventChangeHostAction($id, $uid, $handle = false)                                                               {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.event.change.host.php'); return $objResponse;}
    public function calendarEventAttachPhotoAction($photoId = null, $handle = null)                                                         {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.event.attach.photo.php'); return $objResponse;}
    public function calendarEventAttachPhotoUpdateAction($currentPage = 1, $perPage = 20)                                                   {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.event.attach.photo.update.php'); return $objResponse;}
    public function calendarEventAttachPhotoChooseAction($src, $title, $photoId)                                                            {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.event.attach.photo.choose.php'); return $objResponse;}
    public function calendarEventAttachPhotoDeleteAction()                                                                                  {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.event.attach.photo.delete.php'); return $objResponse;}
    public function calendarEventAttachDocumentAction($handle = null, $mode = 'ADD')                                                        {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.event.attach.document.php'); return $objResponse;}
    public function calendarEventAttachListAction($handle = null, $mode = 'ADD')                                                            {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.event.attach.list.php'); return $objResponse;}
    public function calendarEventExpandListAction($id, $uid, $listId)                                                                       {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.event.expand.list.php'); return $objResponse;}
    public function calendarEventCollapseListAction($id, $uid, $listId)                                                                     {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.event.collapse.list.php'); return $objResponse;}
    public function calendarEventDayDetailsAction($strDate)                                                                                 {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.event.day.details.php'); return $objResponse;}
    //**********************************************
    //  END User Calendar Actions Block


    //  START User Venue Actions Block
    //**********************************************
    public function addNewVenueAction($aParams = array())                                                                                   {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.venue.add.php'); return $objResponse; }
    public function chooseSavedVenueAction($venueId = null)                                                                                 {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.venue.choose.php'); return $objResponse; }
    public function setVenueAction($venueId = null)                                                                                         {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.venue.set.php'); return $objResponse; }
    public function editVenueAction($venueId = null, $editedBlock, $aParams = array() )                                                     {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.venue.edit.php'); return $objResponse; }
    public function loadSavedVenuesAction($aParams = array())                                                                               {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.venue.load.saved.php'); return $objResponse; }
    public function copyVenueAction($venueId = null)                                                                                        {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.venue.copy.php'); return $objResponse; }
    public function copyVenueDoAction($venueId = null,$newName = null, $venue_type = null)                                                  {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.venue.copy.do.php'); return $objResponse; }
    public function deleteVenueAction($venueId = null)                                                                                      {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.venue.delete.php'); return $objResponse; }
    public function deleteVenueDoAction($venueId = null, $venue_type = null)                                                                {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.venue.delete.do.php'); return $objResponse->getXML(); }
    public function chooseSavedWWVenueAction($venueId = null)                                                                               {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.ww.venue.choose.php'); return $objResponse; }
    public function setWWVenueAction($venueId = null)                                                                                       {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.ww.venue.set.php'); return $objResponse; }
    public function addNewWWVenueAction($aParams = array())                                                                                 {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.ww.venue.add.php'); return $objResponse; }
    public function editWWVenueAction($venueId = null, $editedBlock, $aParams = array())                                                    {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.ww.venue.edit.php'); return $objResponse; }
    public function loadSavedWWVenuesAction($aParams = array())                                                                             {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.ww.venue.load.saved.php'); return $objResponse; }
    public function copyWWVenueAction($venueId = null)                                                                                      {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.venue.copy.php'); return $objResponse; }
    public function copyWWVenueDoAction($venueId = null,$newName = null)                                                                    {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.venue.copy.do.php'); return $objResponse; }
    public function deleteWWVenueAction($venueId = null)                                                                                    {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.venue.delete.php'); return $objResponse; }
    public function deleteWWVenueDoAction($venueId = null, $venue_type = null)                                                              {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.venue.delete.do.php'); return $objResponse->getXML(); }
    public function findaVenueAction($aParams = array())                                                                                    {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.venue.search.form.php'); return $objResponse->getXML(); }
    public function copyVenueFromSearchAction($aParams = array())                                                                           {include(PRODUCT_MODULES_DIR.'/users/calendar/ajax/action.venue.copy.fromSearch.php'); return $objResponse->getXML(); }
    //**********************************************
    //  START User Venue Actions Block
    /*
     +-----------------------------------
     |
     | User Calendar Actions Block END
     |
     +-----------------------------------
    */

    //  START User Search Actions Block
    //**********************************************
    public function searchOnChangeCountryAction($countryId)                                                                                 {include(PRODUCT_MODULES_DIR.'/users/xajax/action.searchOnChangeCountry.php'); return $objResponse;}
    public function searchOnChangeStateAction($stateId)                                                                                     {include(PRODUCT_MODULES_DIR.'/users/xajax/action.searchOnChangeState.php'); return $objResponse;}
    //**********************************************
    //  END User Search Actions Block



    // THEMES
    public function uploadBCKGAvatarAction()                                                                                                {include(PRODUCT_MODULES_DIR.'/users/theme/xajax/action.uploadAvatar.php');  return $objResponse->getXML(); }
    public function uploadBCKGAvatarOKAction()                                                                                              {include(PRODUCT_MODULES_DIR.'/users/theme/xajax/action.uploadAvatarOK.php'); }
    public function copyBCKGAvatarOKAction($avatar_id)                                                                                      {include(PRODUCT_MODULES_DIR.'/users/theme/xajax/action.copyAvatarOK.php'); return $objResponse->getXML();}
    public function ddImageSelectBCKGAvatarAction($pageNum=0, $perPage=10)                                                                  {include(PRODUCT_MODULES_DIR.'/users/theme/xajax/action.selectAvatar.php'); return $objResponse->getXML(); }
    public function ddImageUpdateBCKGThumbsAreaAction($pageNum=0, $perPage=10)                                                              {include(PRODUCT_MODULES_DIR.'/users/theme/xajax/action.updateThumbsArea.php'); return $objResponse->getXML(); }
    public function ddImageShowBCKGAvatarPreviewAction($url, $title, $id)                                                                   {include(PRODUCT_MODULES_DIR.'/users/theme/xajax/action.showAvatarPreview.php'); return $objResponse->getXML(); }
    public function themeSaveAction($themeString, $clear=false)                                                                             {include(PRODUCT_MODULES_DIR.'/users/contentblocks/action.themeSave.php'); return $objResponse->getXML(); }
    public function removeBCKGImageAction($path)                                                                                            {include(PRODUCT_MODULES_DIR.'/users/contentblocks/action.removeBCKGImage.php'); return $objResponse->getXML(); }


      //    public function ddpagescolorpickerAction($mousex, $mousey, $refer="", $title="") { include(PRODUCT_MODULES_DIR.'/users/ddpages/action.ddpagescolorpicker.php'); return $objResponse->getXML(); }
    //    public function ddpagescolorpickercloseAction() { include(PRODUCT_MODULES_DIR.'/users/ddpages/action.ddpagescolorpickerclose.php'); return $objResponse->getXML(); }
    //    public function ddpagessavethemecssAction($css_text, $entity_id) { include(PRODUCT_MODULES_DIR.'/users/ddpages/action.ddpagessavethemecss.php'); return $objResponse->getXML(); }



    //Content Objects ================================================================================================================

    // Common AJAX Functions
    //------------------------------------------------------------------------------------------------------
    public function contentObjectsLoadFromDbAction($user_id/*ignored*/) { include(PRODUCT_MODULES_DIR.'/users/contentblocks/action.contentObjectsLoadFromDb.php'); return $objResponse->getXML(); }


    public function getBlockContentLightAction($targetId, $CloneElID, $ContentDivId, $ContentType, $editMode = 0, $Data = array())
    {
        $saveAfterLoad = false;
        $lightReload = true;
        include(PRODUCT_MODULES_DIR.'/users/contentblocks/action.getblockcontent.php');
        return $objResponse->getXML();
    }

    public function getBlockContentAction($targetId, $CloneElID, $ContentDivId, $ContentType, $editMode = 0, $Data = array())
    {
        $saveAfterLoad = false;
        include(PRODUCT_MODULES_DIR.'/users/contentblocks/action.getblockcontent.php');
        return $objResponse->getXML();
    }

    public function getBlockContentThanSaveAction($targetId, $CloneElID, $ContentDivId, $ContentType, $editMode = 0, $Data = array())
    {
        $saveAfterLoad = true;
        include(PRODUCT_MODULES_DIR.'/users/contentblocks/action.getblockcontent.php');
        return $objResponse->getXML();
    }
    public function contentObjectsSaveAction($items, $user_id/*ignored*/) { include(PRODUCT_MODULES_DIR.'/users/contentblocks/action.contentObjectsSave.php'); return $objResponse->getXML(); }
    //------------------------------------------------------------------------------------------------------
    //ddProfileDetails
    public function updateUserProfileAction($gender, $realname, $tags, $cloneId = '') { include(PRODUCT_MODULES_DIR.'/users/contentblocks/ddProfileDetails/action.updateUserProfile.php'); return $objResponse->getXML(); }

    //ddPicture
    public function selectAvatarAction($cloneId, $refer="", $openAction = "open") { include(PRODUCT_MODULES_DIR.'/users/contentblocks/ddPicture/action.selectAvatar.php'); return $objResponse->getXML(); }
    public function uploadAvatarAction($cloneId=0) { include(PRODUCT_MODULES_DIR.'/users/contentblocks/ddPicture/action.uploadAvatar.php');  return $objResponse->getXML(); }
    public function loadAvatarsAction($refresh = false, $cloneId ='') { include(PRODUCT_MODULES_DIR.'/users/contentblocks/ddPicture/action.loadAvatars.php'); return $objResponse->getXML(); }
    public function uploadAvatarOKAction() { include(PRODUCT_MODULES_DIR.'/users/contentblocks/ddPicture/action.uploadAvatarOK.php'); }
    //public function uploadAvatarCloseAction() { $objResponse = new xajaxResponse(); include(PRODUCT_MODULES_DIR.'/users/contentblocks/ddPicture/action.uploadAvatarClose.php'); return $objResponse->getXML(); }
    public function showAvatarPreviewAction($url, $title, $id) { include(PRODUCT_MODULES_DIR.'/users/contentblocks/ddPicture/action.showAvatarPreview.php'); return $objResponse->getXML(); }
    //public function selectAvatarCloseAction() { include(PRODUCT_MODULES_DIR.'/users/contentblocks/ddPicture/action.selectAvatarClose.php'); return $objResponse->getXML(); }
    public function loadAvatarInEditModeAction($cloneId, $avatar_id) { include(PRODUCT_MODULES_DIR.'/users/contentblocks/ddPicture/action.loadAvatarInEditMode.php'); return $objResponse->getXML(); }

    //ddMyDocuments
    public function documentsGetContentAction($el_id, $Data = array(), $blockType = 'wide') { include(PRODUCT_MODULES_DIR.'/users/contentblocks/ddMyDocuments/action.documentsGetContent.php'); return $objResponse->getXML(); }
    public function documentSelectAction($document_id, $js_array_key, $mousex, $mousey, $div_id, $element_id, $blockType = 'wide') { include(PRODUCT_MODULES_DIR.'/users/contentblocks/ddMyDocuments/action.documentSelect.php'); return $objResponse->getXML(); }

    //ddScript
    public function ddScriptSaveScriptCodeAction($code='', $contents='', $cHeight=0, $cloneId='') { include(PRODUCT_MODULES_DIR.'/users/contentblocks/ddScript/action.ddScriptSaveScriptCode.php'); return $objResponse->getXML(); }
    public function ddScriptRemoveScriptCodeAction($code='') { include(PRODUCT_MODULES_DIR.'/users/contentblocks/ddScript/action.ddScriptRemoveScriptCode.php'); return $objResponse->getXML(); }

    //ddMyPhotos
    public function selectGalleryAction($cloneId, $gallery_index)           { include(PRODUCT_MODULES_DIR.'/users/contentblocks/ddMyPhotos/action.selectGallery.php'); return $objResponse->getXML(); }
    public function setGalleryAction($gallery_id, $cloneId, $gallery_index) { include(PRODUCT_MODULES_DIR.'/users/contentblocks/ddMyPhotos/action.setGallery.php'); return $objResponse->getXML(); }
    public function loadMyPhotosGalleryAction($gallery_id, $my_only = false){ include(PRODUCT_MODULES_DIR.'/users/contentblocks/ddMyPhotos/action.loadGallery.php'); return $objResponse->getXML(); }

    //ddMyVideos
    public function selectVideoGalleryAction($cloneId, $gallery_index)           { include(PRODUCT_MODULES_DIR.'/users/contentblocks/ddMyVideos/action.selectGallery.php'); return $objResponse->getXML(); }
    public function setVideoGalleryAction($gallery_id, $cloneId, $gallery_index) { include(PRODUCT_MODULES_DIR.'/users/contentblocks/ddMyVideos/action.setGallery.php'); return $objResponse->getXML(); }
    public function loadMyVideosGalleryAction($gallery_id, $my_only = false){ include(PRODUCT_MODULES_DIR.'/users/contentblocks/ddMyVideos/action.loadGallery.php'); return $objResponse->getXML(); }

    //ddImage
    public function ddImageSelectAvatarAction($cloneId, $pageNum=0, $perPage=10, $avatarId=0) {include(PRODUCT_MODULES_DIR.'/users/contentblocks/ddImage/action.selectAvatar.php'); return $objResponse->getXML(); }
    public function ddImageUpdateThumbsAreaAction($cloneId, $pageNum=0, $perPage=10, $avatarId=0) {include(PRODUCT_MODULES_DIR.'/users/contentblocks/ddImage/action.updateThumbsArea.php'); return $objResponse->getXML(); }
    public function ddImageShowAvatarPreviewAction($url, $title, $id) { include(PRODUCT_MODULES_DIR.'/users/contentblocks/ddImage/action.showAvatarPreview.php'); return $objResponse->getXML(); }

    //ddMyVideoContentBlock
    public function ddMyVideoContentBlockSelectAvatarAction($cloneId, $pageNum=0, $perPage=10) {include(PRODUCT_MODULES_DIR.'/users/contentblocks/ddMyVideoContentBlock/action.selectAvatar.php'); return $objResponse->getXML(); }
    public function ddMyVideoContentBlockUpdateThumbsAreaAction($cloneId, $pageNum=0, $perPage=10) {include(PRODUCT_MODULES_DIR.'/users/contentblocks/ddMyVideoContentBlock/action.updateThumbsArea.php'); return $objResponse->getXML(); }
    public function ddMyVideoContentBlockShowAvatarPreviewAction($videoId = 0) { include(PRODUCT_MODULES_DIR.'/users/contentblocks/ddMyVideoContentBlock/action.showAvatarPreview.php'); return $objResponse->getXML(); }

    //Content Objects END =============================================================================================================

    /**
    * Messages AJAX Functions
    */
    public function messagesMarkAsReadAction($messages_ids = array(), $url) { include(PRODUCT_MODULES_DIR.'/users/xajax/action.messagesMarkAsRead.php'); return $objResponse->getXML(); }
    public function messagesMarkAsUnreadAction($messages_ids = array(), $url) { include(PRODUCT_MODULES_DIR.'/users/xajax/action.messagesMarkAsUnread.php'); return $objResponse->getXML(); }

   //-----------------------------------------------------------VIDEOS----------------------------------------------------------------------------------------
    public function videossearchAction()                                                        {include(PRODUCT_MODULES_DIR.'/users/videogallery/action.videossearch.php');}
    public function videossearchdeleteAction($searchId)                                         {include(PRODUCT_MODULES_DIR.'/users/videogallery/xajax/action.searchDelete.php');return $objResponse;}

   public function videosAction()                          { include(PRODUCT_MODULES_DIR.'/users/videogallery/action.videos.php');}
   public function videogalleryCreateAction()              { include(PRODUCT_MODULES_DIR.'/users/videogallery/action.videogalleryCreate.php');}
   public function videogalleryCreateTrackStatusAction()   { include(PRODUCT_MODULES_DIR.'/users/videogallery/action.videogalleryCreateTrackStatus.php');}
   public function videogalleryTrackStatusAction()         { include(PRODUCT_MODULES_DIR.'/users/videogallery/xajax/action.videogalleryTrackStatus.php');}
   public function videogalleryuploadandsubmitAction($gallery_id = 0, $galleryTitle = null, $filescount = null)          {include(PRODUCT_MODULES_DIR.'/users/videogallery/xajax/action.videogalleryuploadandsubmit.php'); return $objResponse;}
   public function videogalleryEditAction()                { include(PRODUCT_MODULES_DIR.'/users/videogallery/action.videogalleryEdit.php');}
   public function videogalleryViewAction()                { include(PRODUCT_MODULES_DIR.'/users/videogallery/action.videogalleryView.php');}


   //-------------------------AJAX Videogallery----------------------------------------------------------------------------------------------------------------------
    public function videogalleryShareGroupAction($galleryId, $groupId, $application)                           {include(PRODUCT_MODULES_DIR.'/users/videogallery/xajax/action.videogalleryShareGroup.php'); return $objResponse;}
    public function videogalleryShareGroupDoAction($galleryId, $groupId, $application)               {include(PRODUCT_MODULES_DIR.'/users/videogallery/xajax/action.videogalleryShareGroupDo.php'); return $objResponse;}
    public function videogalleryShareFriendAction($galleryId, $application)                          {include(PRODUCT_MODULES_DIR.'/users/videogallery/xajax/action.videogalleryShareFriend.php'); return $objResponse;}
    public function videogalleryShareFriendDoAction($galleryId, $data, $application)                 {include(PRODUCT_MODULES_DIR.'/users/videogallery/xajax/action.videogalleryShareFriendDo.php'); return $objResponse;}
    public function videogalleryAddGalleryAction($galleryId, $photoId, $application)                 {include(PRODUCT_MODULES_DIR.'/users/videogallery/xajax/action.videogalleryAddGallery.php'); return $objResponse;}
    public function videogalleryAddGalleryDoAction($galleryId, $photoId, $data)                      {include(PRODUCT_MODULES_DIR.'/users/videogallery/xajax/action.videogalleryAddGalleryDo.php'); return $objResponse;}
    public function videogalleryAddVideoAction($galleryId, $videoId, $application)                   {include(PRODUCT_MODULES_DIR.'/users/videogallery/xajax/action.videogalleryAddVideo.php'); return $objResponse;}
    public function videogalleryAddVideoDoAction($galleryId, $videoId, $data)                        {include(PRODUCT_MODULES_DIR.'/users/videogallery/xajax/action.videogalleryAddVideoDo.php'); return $objResponse;}
    public function videogalleryUnShareDoAction($galleryId, $application)                            {include(PRODUCT_MODULES_DIR.'/users/videogallery/xajax/action.videogalleryUnShareDo.php'); return $objResponse;}
    public function videogalleryUnShareGroupDoAction($galleryId, $groupId, $application)                {include(PRODUCT_MODULES_DIR.'/users/videogallery/xajax/action.videogalleryUnShareGroupDo.php'); return $objResponse;}
    public function videogalleryUnShareFriendDoAction($galleryId, $userId, $application)                {include(PRODUCT_MODULES_DIR.'/users/videogallery/xajax/action.videogalleryUnShareFriendDo.php'); return $objResponse;}
    public function videogalleryStopWatchingDoAction($galleryId, $application)                       {include(PRODUCT_MODULES_DIR.'/users/videogallery/xajax/action.videogalleryStopWatchingDo.php'); return $objResponse;}
    public function videogalleryDeleteGalleryAction($galleryId, $application, $new = false)          {include(PRODUCT_MODULES_DIR.'/users/videogallery/xajax/action.videogalleryDeleteGallery.php'); return $objResponse;}
    public function videogalleryAddCommentDoAction($galleryId, $videoId, $message, $application)     {include(PRODUCT_MODULES_DIR.'/users/videogallery/xajax/action.videogalleryAddCommentDo.php'); return $objResponse;}
    public function videogalleryUpdateCommentDoAction($galleryId, $videoId, $commentId, $message, $application)     {include(PRODUCT_MODULES_DIR.'/users/videogallery/xajax/action.videogalleryUpdateCommentDo.php'); return $objResponse;}
    public function videogalleryDeleteCommentDoAction($galleryId, $videoId, $commentId, $application){include(PRODUCT_MODULES_DIR.'/users/videogallery/xajax/action.videogalleryDeleteCommentDo.php'); return $objResponse;}
    public function videogalleryShowShareHistoryAction($galleryId, $application)                     {include(PRODUCT_MODULES_DIR.'/users/videogallery/xajax/action.videogalleryShowShareHistory.php'); return $objResponse;}
    public function videogalleryShowShareHistoryDoAction()                                           {include(PRODUCT_MODULES_DIR.'/users/videogallery/xajax/action.videogalleryShowShareHistoryDo.php'); return $objResponse;}
    public function videogalleryMoveToAction($videoId)                                               {include(PRODUCT_MODULES_DIR.'/users/videogallery/xajax/action.videogalleryMoveTo.php'); return $objResponse;}
    public function videogalleryMoveToDoAction($videoId, $collectionId)                              {include(PRODUCT_MODULES_DIR.'/users/videogallery/xajax/action.videogalleryMoveToDo.php'); return $objResponse;}
    public function videoeditshowpageAction($page, $gallery_id, $expand_mode = 'none')                                           {include(PRODUCT_MODULES_DIR.'/users/videogallery/xajax/action.videogalleryEditshowpage.php'); return $objResponse;}

    public function videogalleryEditVideoAction($galleryId, $videoId, $application)                  {include(PRODUCT_MODULES_DIR.'/users/videogallery/xajax/action.videogalleryEditVideo.php'); return $objResponse;}
    public function videogalleryEditVideoDoAction()                                                  {include(PRODUCT_MODULES_DIR.'/users/videogallery/xajax/action.videogalleryEditVideoDo.php'); return $objResponse;}
    public function videogalleryDeleteVideoAction($galleryId, $videoId, $application)                {include(PRODUCT_MODULES_DIR.'/users/videogallery/xajax/action.videogalleryDeleteVideo.php'); return $objResponse;}
    /**
     * Actions for edit gallery page
     *
     */
    public function videogalleryEditGallVideoAction($galleryId, $videoId)                            {include(PRODUCT_MODULES_DIR.'/users/videogallery/xajax/action.videogalleryEditGallVideo.php'); return $objResponse;}
    public function videogalleryEditGallVideoDoAction()                                              {include(PRODUCT_MODULES_DIR.'/users/videogallery/xajax/action.videogalleryEditGallVideoDo.php'); return $objResponse;}
    public function videogalleryCancelEditGallVideoAction($galleryId, $videoId)                      {include(PRODUCT_MODULES_DIR.'/users/videogallery/xajax/action.videogalleryCancelEditGallVideo.php'); return $objResponse;}
    public function videogalleryDeleteGallVideoDoAction($galleryId, $videoId)                        {include(PRODUCT_MODULES_DIR.'/users/videogallery/xajax/action.videogalleryDeleteGallVideoDo.php'); return $objResponse;}
    public function videogalleryUploadVideoAction($galleryId)                                        {include(PRODUCT_MODULES_DIR.'/users/videogallery/xajax/action.videogalleryUploadVideo.php'); return $objResponse;}
    public function videogalleryUploadVideoDoAction()                                                {include(PRODUCT_MODULES_DIR.'/users/videogallery/xajax/action.videogalleryUploadVideoDo.php'); return $objResponse;}
    public function videogalleryDeleteRawVideoAction()                                                     {include(PRODUCT_MODULES_DIR.'/users/videogallery/action.videogalleryDeleteRawVideo.php');}

    public function videogalleryShowTmbPageAction($page, $galleryId)                                 {include(PRODUCT_MODULES_DIR.'/users/videogallery/xajax/action.videogalleryShowTmbPage.php'); return $objResponse;}
    public function videogalleryWatchAction($galleryId)                                              {include(PRODUCT_MODULES_DIR.'/users/videogallery/xajax/action.videogalleryWatch.php'); return $objResponse;}
    
    
    //---------
    public function loginOpenidAction() 
    {
        $formOpenID = new Warecorp_Form('loginOpenIDForm', 'post', '/'.$this->_page->Locale.'/users/login.openid/');
        
        $data = null;
        $sreg = new Zend_OpenId_Extension_Sreg(array(
            'nickname' => false,
            'email' => false,
            'fullname' => false), null, 1.1);
        $consumer = new Zend_OpenId_Consumer();
        
        /**
         * The Authentication Request Handler
         */
        if ( $formOpenID->isPostback() ) {            
            if ( !$consumer->login($_POST['openid_identifier'], '/'.$this->_page->Locale.'/users/login.openid/', null, $sreg) ) {
                $status = "OpenID login failed.";
            }
        } elseif ( isset($_GET['openid_mode']) && 'id_res' == $_GET['openid_mode'] ) {
            if ( $consumer->verify( $_GET, $id, $sreg ) ) {
                $status = "VALID " . htmlspecialchars($id);
                $data = $sreg->getProperties();
            } else {
                $status = "INVALID " . htmlspecialchars($id);
            }
        } elseif ( isset($_GET['openid_mode']) && 'cancel' == $_GET['openid_mode'] ) {
            $status = "CANCELLED";
        }
        
        print_r($data);
        exit($status);
    }
}
