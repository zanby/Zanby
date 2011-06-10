<?php
	$items_per_page = 20;
	$form = new Warecorp_Form('sForm', 'POST', $this->admin->getAdminPath('log'));
	require_once (WARECORP_DIR.'Log/List.php');
	$logs = new Warecorp_Log_List();
	$url = $this->admin->getAdminPath('log');
	$wrsymb = array("/","*", "%", "\\", "<", ">",'"',"'","&","$");
	if(isset($this->params['date'])){
		$logs->setDateFilter(str_replace($wrsymb,array('-'),$this->params['date']));
	}
	if(isset($this->params['adminname'])){
		$logs->setNameFilter(str_replace($wrsymb,array('-'),$this->params['adminname']));
	}
	if($logs->getDateFilter()!==''){
		$url .= '/date/'.$logs->getDateFilter();
	}
	if($logs->getNameFilter()!==''){
		$url .= '/adminname/'.$logs->getNameFilter();
	}
	$pageNumber = isset($this->params['page'])?$this->params['page']:1;
	$P = new Warecorp_Common_PagingProduct($logs->getLogCount(), $items_per_page, $url);	
	$this->view->paging = $P->makePaging(intval($pageNumber));
	$logList=$logs->getLogPage($pageNumber,$items_per_page);
	$this->view->logList = $logList;
	$this->view->form = $form;
	$this->view->dateFilter = $logs->getDateFilter();
	$this->view->nameFilter = $logs->getNameFilter();
    $this->view->USER_LOG = defined('USER_LOG') && USER_LOG;
	$template = 'adminarea/log.tpl';	

	$this->view->bodyContent = $template;
	
