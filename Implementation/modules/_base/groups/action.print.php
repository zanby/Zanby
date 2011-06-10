<?php
Warecorp::addTranslation('/modules/groups/action.print.php.xml');

	$this->view->contentBlocksHTML = Warecorp_DDPages::getAllBlocksHTML($this->_page, $this->currentGroup, $this->_page->_user);

    $theme = Warecorp_Theme::loadThemeFromDB($this->currentGroup);
    $theme->prepareFonts();
    $this->view->theme = $theme;
	
    $this->view->setLayout('main_print.tpl');
	$this->view->bodyContent = 'users/print.tpl';
