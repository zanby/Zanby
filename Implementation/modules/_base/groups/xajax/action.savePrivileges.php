<?php    
Warecorp::addTranslation('/modules/groups/xajax/action.savePrivileges.php.xml');

    $this->view->visibility = false;
    $objResponse = new xajaxResponse();
    
    $form = new Warecorp_Form('gpForm', 'POST', 'javasript:void(0);'); 
    if (isset($params['_wf__gpForm'])) {
            $_REQUEST['_wf__gpForm'] = $params['_wf__gpForm'];
        }
            
    $privileges = $this->currentGroup->getPrivileges();
	$privileges
        ->setCalendar(isset($params['gpCalendar_access'])?$params['gpCalendar_access']:0)
        ->setEmail(isset($params['gpEmail_access'])?$params['gpEmail_access']:0)
        ->setPhotos(isset($params['gpPhotos_access'])?$params['gpPhotos_access']:0)
        ->setVideos(isset($params['gpVideos_access'])?$params['gpVideos_access']:0)
        ->setDocuments(isset($params['gpDocuments_access'])?$params['gpDocuments_access']:0)
        ->setLists(isset($params['gpLists_access'])?$params['gpLists_access']:0)
        ->setPolls(isset($params['gpPolls_access'])?$params['gpPolls_access']:0)
        ->setForumsPosts(/*isset($params['gpForumsPosts_access'])?$params['gpForumsPosts_access']:*/0)
        ->setForumsModerate(/*isset($params['gpForumsModerate_access'])?$params['gpForumsModerate_access']:*/0)
        ->setManageGroupFamilies(isset($params['gpManageGroupFamilies_access'])?$params['gpManageGroupFamilies_access']:0)
        ->setManageMembers(isset($params['gpManageMembers_access'])?$params['gpManageMembers_access']:0)
        ->setModifyLayout(isset($params['gpModifyLayout_access'])?$params['gpModifyLayout_access']:0)
        ->setGroupsCreation(isset($params['gpCreateGroup_access'])?$params['gpCreateGroup_access']:1)
        ->setSendEmail(empty($params['gpSendEmail']) ? 0 : 1)
        ->setShareToFamily(empty($params['gpShareToFamily_access']) ? 0 : $params['gpShareToFamily_access']);
    

	$privileges->save();

    $templatePrefix = $this->currentGroup->getGroupType() == 'simple'?'':$this->currentGroup->getGroupType();
    $Content = $this->view->getContents('groups/settings.'.$templatePrefix.'privileges.tpl');
 
    $objResponse->addClear( "GroupSettingsPrivilegies_Content", "innerHTML" );
    $objResponse->addAssign( "GroupSettingsPrivilegies_Content", "innerHTML", $Content );
    $objResponse->addScript('TitltPaneAppGroupSettingsPrivilegies.hide();');    
    $objResponse->addScript('setAutoComplete();');
    $objResponse->showAjaxAlert(Warecorp::t('Changes saved'));
