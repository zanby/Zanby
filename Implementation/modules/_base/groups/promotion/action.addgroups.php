<?php
Warecorp::addTranslation('/modules/groups/promotion/action.addgroups.php.xml');

	$objResponse = new xajaxResponse();
	if (isset($params['selected_groups'])) {
		$alert = 'Added';
		$this->_page->showAjaxAlert($alert);
		$_SESSION['AjaxAlertProperty'] = $this->_page->getAjaxAlertProperty();
		$objResponse->addScript('document.add_selected_groups_form.submit();');
	} else {
		$Content = $this->view->getContents ( 'groups/promotion/addgroups.tpl' ) ;
        
        $popup_window = Warecorp_View_PopupWindow::getInstance();
        $popup_window->title(Warecorp::t('Invite Groups'));
        $popup_window->content($Content);
        $popup_window->width(500)->height(350)->open($objResponse);			
	}