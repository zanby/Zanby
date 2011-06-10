<?php
    Warecorp::addTranslation('/modules/adminarea/adminarea.controller.php.xml');

class BaseAdminareaController extends Warecorp_Controller_Action
{
    protected $params;
    protected $admin;
    protected $changeTable;
    protected $changeId;
    protected $oldValues = null;
    protected $newValues = null;
    protected $changed = false;
    protected $KeyID = '';

    protected function getActiveMainMenu()
    {
        $active = $this->getRequest()->getActionName();
        if ( in_array($active, array('members', 'memberGroupMembership', 'importmembers')) ) return 'members';
        elseif ( in_array($active, array('groups', 'groupMembers', 'groupFamilyMembership', 'importgroups')) ) return 'groups';
        elseif ( in_array($active, array('families', 'familyMembers')) ) return 'families';
        else return $active;
    }

    /*
    protected function getActiveSubMenu()
    {
        $active = $this->getRequest()->getActionName();
        if ( in_array($active, array('members', 'memberGroupMembership')) ) return 'members';
        elseif ( in_array($active, array('groups', 'groupMembers', 'groupFamilyMembership')) ) return 'groups';
        elseif ( in_array($active, array('families', 'familyMembers')) ) return 'families';
        else return $active;
    }
    */

    public function init()
    {
        parent::init();
        $request = $this->getRequest();

        $this->changeTable = '';

        $this->admin = new Warecorp_Admin();
        $actionsList=array();
        Zend_Registry::set("Admin", $this->admin);

        if($this->admin->isLogined()) {
        /* Logined */
            $this->admin->loadById($_SESSION['admin_id']);
            Zend_Registry::set("Admin", $this->admin);
            if ($request->getActionName() == 'login') {
                $this->_redirect('http://'.BASE_HTTP_HOST.'/'.$this->_page->Locale.'/adminarea/index/');
            }
            $this->view->logined = $_SESSION['admin'];

            //
            //$this->view->active = $request->getActionName();
            $this->view->active = $this->getActiveMainMenu();
            //$this->view->active_submenu = $this->getActiveSubMenu();
            $this->view->admin = $this->admin;

            /**
             * Choose configuration file
             * if file exits in root access folder get it else
             * get configuration file from ESA|EIA folder
             */
            if ( file_exists(ACCESS_RIGHTS_DIR.'admin_allowed.xml') ) {
                $cfg_access_file = ACCESS_RIGHTS_DIR.'admin_allowed.xml';
            } elseif ( file_exists(ACCESS_RIGHTS_DIR.IMPLEMENTATION_TYPE.'/admin_allowed.xml') ) {
                $cfg_access_file = ACCESS_RIGHTS_DIR.IMPLEMENTATION_TYPE.'/admin_allowed.xml';
            } else {
               throw new Zend_Exception(Warecorp::t('Configuration file \'admin_allowed.xml\' was not found.'));
            }
            $adminAccess = new Warecorp_Admin_Access();
            $adminAccess->loadXmlConfig($cfg_access_file);
            if  (!$adminAccess->isAllowed($this->admin->getRole(), Warecorp::$actionName)){
                   $this->_redirect('http://'.BASE_HTTP_HOST.'/'.$this->_page->Locale.'/adminarea/index/');
            }
            $actionsList=$adminAccess->actionsList($this->admin->getRole());
        } elseif($request->getActionName() != 'forgotPassword') {
            /* Not Logined */
            if ( isset($_GET["xajax"]) || isset($_POST["xajax"]) ) {
                $objResponse = new xajaxResponse();
                $objResponse->addRedirect('http://'.BASE_HTTP_HOST.'/'.LOCALE.'/adminarea/login/');
                $sContentHeader = "Content-type: text/xml;";
                if ($this->_page->Xajax->sEncoding && strlen(trim($this->_page->Xajax->sEncoding)) > 0) {
                    $sContentHeader .= " charset=".$this->_page->Xajax->sEncoding;
                }
                $objResponse = $objResponse->getXML();
                header($sContentHeader);
                print $objResponse; exit;
            } else {
                if ($request->getActionName() != 'login') {
                    $this->_redirect('http://'.BASE_HTTP_HOST.'/'.$this->_page->Locale.'/adminarea/login/');
                }
            }
           }

        $this->params = $request->getParams();
        $this->_page->setTitle(Warecorp::t('Admin area'));
        $this->_page->setStyle('calendar.css');
        $this->view->actionsList = $actionsList;
    }

    /* Append record to Log-table */
    public function appendLog($table,$itemid=0,$actionname){
        if($actionname==='login') {
            $action = 'Login';
            $message = $this->admin->getLogin().' '.Warecorp::t('has logged in');
            $this->setChanged();
        } elseif ($actionname==='logout') {
            $action = 'Logout';
            $message = $this->admin->getLogin().' '.Warecorp::t('has logged out');
            $this->setChanged();
        } elseif ($actionname==='loginas') {
            $action = 'Log in as';
            $a='<a href="'.$this->admin->getAdminPath('members/id/'.$itemid.'/').'">'.$table.'</a>';
            $message = $this->admin->getLogin().' '.Warecorp::t('logged in as ').$a;
            $this->setChanged();
        } elseif ($actionname==='edit' && $table==='members') {
            $action = 'Member record edit';
            $a='<a href="'.$this->admin->getAdminPath('members/id/'.$itemid.'/').'">'.$this->params['login'].'</a>';
            $message = Warecorp::t('Member %s was changed by ',array($a)).$this->admin->getLogin();
        } elseif ($actionname==='edit' && $table==='groups') {
            $action = 'Group record edit';
            $a='<a href="'.$this->admin->getAdminPath('groups/id/'.$itemid.'/').'">'.$this->params['gname'].'</a>';
            $message = Warecorp::t('Group %s was changed by ',array($a)).$this->admin->getLogin();
        } elseif ($actionname==='edit' && $table==='templates') {
            $action = 'Mail template edit';
            $a='<a href="'.$this->admin->getAdminPath('templates/id/'.$itemid.'/').'">'.$this->params['template_key'].'</a>';
            $message = Warecorp::t('Mail template %s was changed by ',array($a)).$this->admin->getLogin();
            $this->oldValues = null;
            $this->newValues = null;
        } elseif ($actionname==='new' && $table==='templates') {
            $action = 'Mail template create';
            $a=$this->params['template_key'];
            $message = Warecorp::t('Mail template %s was created by ',array($a)).$this->admin->getLogin();
            $this->setChanged();
        } elseif ($actionname==='import' && $table==='members') {
                $action = 'Import Members';
                $message = $this->admin->getLogin().Warecorp::t(' importeded members list');
                $this->setChanged();
        } elseif ($actionname==='delete' && $table==='members') {
                $action = 'Delete Imported Members';
                $message = $this->admin->getLogin().Warecorp::t(' deleted members list');
                $this->setChanged();
        } elseif ($actionname==='import' && $table==='groups') {
                $action = 'Import Groups';
                $message = $this->admin->getLogin().Warecorp::t(' importeded groups list');
                $this->setChanged();
        } else {
                $action = '';
                $message = '';
                $this->setChanged(false);
        }
        if($this->newValues!==$this->oldValues) {
               $message.= '<br>'.Warecorp::t('Old values').'<br>'.$this->oldValues;
               $message.= '<br>'.Warecorp::t('New values').'<br>'.$this->newValues;
        }
        if ($this->isChanged()) {
            $db = Zend_Registry::get("DB");
            $log = array(
                'admin_id'      	=> $_SESSION['admin_id'],
                'changed_table'     => $table,
                'item_id'      		=> $itemid,
                'action'      		=> $action,
                'message'      		=> $message,
                'change_time'      	=> new Zend_Db_Expr('NOW()')
            );
            $res = $db->insert('zanby_admin__log', $log);
        }
    }

    public function setKeyID($value) {
        $this->KeyID = $value;
    }
    public function getKeyID() {
        return $this->KeyID;
    }

    public function changeField($fieldname, $oldValue, $newValue){
        if( $oldValue !== $newValue ) {
            if( $this->oldValues !== null ) {
                $this->oldValues .= '; ';
                $this->newValues .= '; ';
            }
            $this->oldValues .= $fieldname.': '.htmlspecialchars($oldValue);
            $this->newValues .= $fieldname.': '.htmlspecialchars($newValue);
            $this->setChanged();
        }
    }
    public function setChanged($value=true)                                         { $this->changed = $value; }
    public function isChanged()                                                     { return $this->changed; }
    public function indexAction()                                                   {include(PRODUCT_MODULES_DIR.'/adminarea/action.index.php');}
    public function noRouteAction()                                                 {$this->_redirect($BASE_URL.'/'.$this->_page->Locale.'/adminarea/index/');}
    public function loginAction()                                                   {include(PRODUCT_MODULES_DIR.'/adminarea/action.login.php');}
    public function logoutAction()                                                  {include(PRODUCT_MODULES_DIR.'/adminarea/action.logout.php');}
    public function membersAction()                                                 {include(PRODUCT_MODULES_DIR.'/adminarea/action.members.php');}
    public function memberGroupMembershipAction()                                   {include(PRODUCT_MODULES_DIR.'/adminarea/action.member.group.membership.php');}
    public function newmemberAction()                                               {include(PRODUCT_MODULES_DIR.'/adminarea/action.newmember.php');}
    public function groupsAction()                                                  {include(PRODUCT_MODULES_DIR.'/adminarea/action.groups.php');}
    public function settingsAction()                                                {include(PRODUCT_MODULES_DIR.'/adminarea/action.settings.php');}

    public function groupMembersAction()                                            {include(PRODUCT_MODULES_DIR.'/adminarea/action.group.members.php');}
    public function groupFamilyMembershipAction()                                   {include(PRODUCT_MODULES_DIR.'/adminarea/action.group.family.membership.php');}
    public function groupAddToFamilyAction($groupId, $familyId)                     {include(PRODUCT_MODULES_DIR.'/adminarea/xajax/action.groupAddToFamily.php'); return $objResponse; }
    public function groupRemoveFromFamilyAction($groupId, $familyId)                {include(PRODUCT_MODULES_DIR.'/adminarea/xajax/action.groupRemoveFromFamily.php'); return $objResponse; }
    public function groupRemoveFromFamilyDoAction($groupId, $familyId)              {include(PRODUCT_MODULES_DIR.'/adminarea/xajax/action.groupRemoveFromFamilyDo.php'); return $objResponse; }
    public function familiesAction()                                                {include(PRODUCT_MODULES_DIR.'/adminarea/action.families.php');}
    public function familyMembersAction()                                           {include(PRODUCT_MODULES_DIR.'/adminarea/action.family.members.php');}

    public function templatesAction()                                               {include_once(PRODUCT_MODULES_DIR.'/adminarea/template/action.templates.php');}
    public function newtemplateAction()                                             {include_once(PRODUCT_MODULES_DIR.'/adminarea/template/action.newtemplate.php');}
    public function importTemplateAction()                                          {include_once(PRODUCT_MODULES_DIR.'/adminarea/template/action.importTemplate.php');}
    public function exportTemplateAction()                                          {include_once(PRODUCT_MODULES_DIR.'/adminarea/template/action.exportTemplate.php');}
    public function importTemplatesAction()                                         {include_once(PRODUCT_MODULES_DIR.'/adminarea/template/action.importTemplates.php');}
    public function exportTemplatesAction()                                         {include_once(PRODUCT_MODULES_DIR.'/adminarea/template/action.exportTemplates.php');}
    public function removeTemplateAction()                                          {include_once(PRODUCT_MODULES_DIR.'/adminarea/template/action.removeTemplate.php');}
    
    public function rebuildweightAction()                                           {include_once(PRODUCT_MODULES_DIR.'/adminarea/action.rebuildweight.php');}
    public function importmembersAction()                                           {include_once(PRODUCT_MODULES_DIR.'/adminarea/action.importmembers.php');}
    public function importgroupsAction()                                            {include_once(PRODUCT_MODULES_DIR.'/adminarea/action.importgroups.php');}
    public function deleteimportedusersAction()                                     {include_once(PRODUCT_MODULES_DIR.'/adminarea/action.deleteimportedusers.php');}
    public function deletetemplatesAction()                                         {include_once(PRODUCT_MODULES_DIR.'/adminarea/action.deletetemplates.php');}
    public function customizetemplatesAction()                                      {include_once(PRODUCT_MODULES_DIR.'/adminarea/action.customizetemplates.php');}
    public function aboutAction()                                                   {include_once(PRODUCT_MODULES_DIR.'/adminarea/action.about.php');}

    //deleteuserstrans
    public function loginasAction()                                                 {include(PRODUCT_MODULES_DIR.'/adminarea/action.loginas.php');}
    public function logAction() 	                                                {include(PRODUCT_MODULES_DIR.'/adminarea/action.log.php');}
    public function userlogAction() 	                                            {include(PRODUCT_MODULES_DIR.'/adminarea/action.userlog.php');}

    // CMS-related actions
    public function newpageAction()                                                 {include(PRODUCT_MODULES_DIR.'/adminarea/cms/action.newpage.php');}
    public function pagesAction()                                                   {include(PRODUCT_MODULES_DIR.'/adminarea/cms/action.pages.php');}
    public function blocksAction()                                                  {include(PRODUCT_MODULES_DIR.'/adminarea/cms/action.blocks.php');}

    public function userActivityLogsAction()                                        {include(PRODUCT_MODULES_DIR.'/adminarea/action.userActivityLogs.php'); }
    public function userActivityLogsCsvAction()                                     {include(PRODUCT_MODULES_DIR.'/adminarea/action.userActivityLogsCsv.php'); }

    /**
     * I18N
     */
    public function translateAction()                                               {include(PRODUCT_MODULES_DIR.'/adminarea/translate/action.translate.php'); }
    public function translateShowFileAction($file)                                  {include(PRODUCT_MODULES_DIR.'/adminarea/translate/xajax/action.translate.show.file.php'); return $objResponse; }
    public function translateEditFileAction($file, $key, $handle = null)            {include(PRODUCT_MODULES_DIR.'/adminarea/translate/xajax/action.translate.edit.file.php'); return $objResponse; }
    public function translateExportAction($file)                                    {include(PRODUCT_MODULES_DIR.'/adminarea/translate/xajax/action.translate.export.php'); return $objResponse; }
    public function translateGetAction()                                            {include(PRODUCT_MODULES_DIR.'/adminarea/translate/xajax/action.translate.get.php');}
    public function translateImportAction($file = null)                             {include(PRODUCT_MODULES_DIR.'/adminarea/translate/xajax/action.translate.import.php'); return $objResponse; }

}
