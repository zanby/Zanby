<?php
function smarty_function_template_addressbook_default($params, &$smarty) {
	if (!isset($params['without_checkbox'])) {
			$output .= "<td class='prBorderTop'>";
				if ($params['object']->getClassName() != 'group' && $params['object']->getClassName() != 'groupmember' && $params['object']->getClassName() != 'friend') {
					$output .= "<input type='checkbox' name='".$params['contacts']."' id='contact_".$params['cid']."' class='prNoBorder' value='".$params['cid']."' onchange=\"checkActive(this,'contact_row_','".$params['cid']."','','znBG1')\"/>";
					$output .= "<input type='hidden' id='".$params['cid']."' name='0'/>";
				}		
			$output .= "&#160;</td>";
	}
		$output .= "<td class='prBorderTop'><span class='prEllipsis prAddressName'><span class='ellipsis_init'><a href='".$params['object']->url."' title='".$params['object']->displayName."'>".$params['object']->displayName."</a></span></span></td>";       
		$output .= "<td class='prBorderTop smallerTextVerdana'><span class='prEllipsis prAddressEmail' title='".$params['object']->getEmailsAsString()."'><span class='ellipsis_init'>".$params['object']->getEmailsAsString()."&#160;</span></span></td>";
		$output .= "<td class='prBorderTop smallerTextVerdana'><span class='prEllipsis prAddressList'>";
			foreach ($params['object']->getParentContactLists() as $contactList){
				if ($params['object']->getClassName() == 'user' || $params['object']->getClassName() == 'custom_user') {
					$output .= "<span class='ellipsis_init'><a href='".$params['currentUser']->getGlobalPath('addressbookmaillist')."id/".$contactList->getContactId()."/' title='".$contactList->getDisplayName()."'>".$contactList->getDisplayName().";</a></span> ";
				}
				if ($params['object']->getClassName() == 'groupmember') {
					$output .= "<span class='ellipsis_init'><a href='".$params['currentUser']->getGlobalPath('addressbookgroup')."id/".$contactList->getGroupId()."/' title='".$contactList->getDisplayName()."'>".$contactList->getDisplayName().";</a></span> ";
				}
			}
		$output .= "&#160;</span></td>";
		$output .= "<td class='prBorderTop'>";
			$output .= "<a href='".$params['object']->profile."'>";
				if ($params['object']->avatar) {
					$output .= "<img src='".$params['object']->avatar->setWidth(37)->setHeight(37)->setBorder(1)->getImage()."' border='0' />";
				}
			$output .= "</a>";
		$output .= "&#160;</td>";
	return $output;
}
?>