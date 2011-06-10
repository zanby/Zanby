<?php
Warecorp::addTranslation('/modules/groups/xajax/action.nameinvitation.php.xml');

	$objResponse = new xajaxResponse();
	$form = new Warecorp_Form('inForm', 'POST', 'javascript:void(0)');
	if (isset($params)) {
		if (strlen($params['inv_name']) == 0) {
			$form->addCustomErrorMessage(Warecorp::t('Enter please invitation name'));
			$form->setValid(false);
		}
		if (strlen($params['inv_name']) > 20) {
			$form->addCustomErrorMessage(Warecorp::t('Name you entered too long (max %s)',20));
			$form->setValid(false);
		}
		if ($form->isValid()) {
			$_SESSION['inv_name'] = $params['inv_name'];
			$objResponse->addScript('document.compose_form.submit();');
		}
    }

    $this->view->form = $form;
    $this->view->inv_name = isset($params['inv_name'])?$params['inv_name']:'';
    $template = 'groups/promotion/nameinvitation.tpl';
    $Content = $this->view->getContents ( $template ) ;	    

    $popup_window = Warecorp_View_PopupWindow::getInstance();
    $popup_window->title(Warecorp::t('Invitation Name'));
    $popup_window->content($Content);
    $popup_window->width(500)->height(350)->open($objResponse);

