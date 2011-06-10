<?php
Warecorp::addTranslation('/plugins/function.menu_account_tools.php.xml');
function smarty_function_menu_account_tools($params, &$smarty)
{
	$theme = Zend_Registry::get('AppTheme');
	$objUser = Zend_Registry::get('User');

	if ( !$objUser || null === $objUser->getId() ) return '';

	$messageManager = new Warecorp_Message_List();
	$folderList = $messageManager->getMessagesFoldersList($objUser->getId());
	$messageManager->setFolder(Warecorp_Message_eFolders::toInteger('inbox'));
	$messageManager->setOrder($order);
	$messageCount = $messageManager->countUnreadByOwner($objUser->getId());

	//$groupsCount = $objUser->getGroups()->setMembersRole('host')->setTypes('simple')->getCount();
	$cache = Warecorp_Cache::getCache();
	if ( !$groups = $cache->load('all_mygroups_menu_account_tools_'.$objUser->getId()) ) {
		$cacheLifetime = 600; // 10 min
		$groups = $objUser->getGroups()->setMembersRole(array('host', 'cohost', 'member'))->setTypes(array('simple'))->getList();
		$cache->save($groups, 'all_mygroups_menu_account_tools_'.$objUser->getId(), array(), $cacheLifetime);
	}
	$groupsCount = sizeof($groups);

	$output = array();
	$output[] = "<ul class='prTopNav'>";
	$output[] = "<li><a class='prNoBorder' href='{$objUser->getUserPath('messagelist')}'>".Warecorp::t('Inbox')." ({$messageCount})</a></li>";
	$output[] = "<li class='prDropDown'>";   
	$output[] = "<a href='".$objUser->getUserPath('groups')."'> ".Warecorp::t('My Groups')." ({$groupsCount})</a>"; 
	
	if ( $groupsCount )  {

		$output[] = "<ul class='prTopSubNav'>";
		foreach ( $groups as $group )
			$output[] = '<li class="prCustomDropDoun"><a href="javascript:void(0);" onclick="location.href=\''.$group->getGroupPath('summary').'\'">'.$group->getName().'</a></li>';
		$output[] = "</ul>";
	}
	$output[] = "</li>";   
	$output[] = "</ul>";
	$output = join('', $output);

	return $output;
}