<?php
    $log = new Warecorp_Log_User();

    $defaultTimezone = date_default_timezone_get();
    date_default_timezone_set('UTC');

    if (!empty($this->params['export'])) {
        
        $filterdate = new Zend_Date();
        $filterdate = $filterdate->subMonth(3);

        $log->addFilters(array('date_start'=>$filterdate->toString('Y-M-d H:m:s')));
        $log->exportToCSV();
        exit;
    }





    $period = $this->params['period'];
    if (!$period) $period = 1;
    
    


    

    switch ($period) {
        case 3: //30 days
            $filterdate = new Zend_Date();
            $filterdate = $filterdate->subDay(30);
            $log->addFilters(array('date_start'=> $filterdate->toString('Y-M-d H:m:s')));
            break;
        case 2: //7 days
            $filterdate = new Zend_Date();
            $filterdate = $filterdate->subDay(7);
            $log->addFilters(array('date_start'=> $filterdate->toString('Y-M-d H:m:s')));
            break;
        default: //1 day
            date_default_timezone_set($defaultTimezone);
            $filterdate = new Zend_Date();
            $filterdate->setTime(0);
            $filterdate->setTimezone('UTC');
            $log->addFilters(array('date_start'=> $filterdate->toString('Y-M-d H:m:s')));
            date_default_timezone_set('UTC');
            break;
    }

    $this->view->period = $period;

    /**
     * Logs
     */
    $log->addFilters(array('action'=>'login','status'=>Warecorp_Log_User::SUCCESS));
    $successLogin = $log->getCount();
    $this->view->successCount = $successLogin;

    $log->addFilters(array('action'=>'login','status'=>Warecorp_Log_User::FAILURE));
    $failureLogin = $log->getCount();
    $this->view->failureCount = $failureLogin;
    if ($successLogin > 0) {
        $this->view->percentage = round($successLogin/($successLogin+$failureLogin) * 100,1);
    }else{
        $this->view->percentage = 0;
    }
    

    $log->addFilters(array('action'=>'password_restore','status'=>null));
    $restoreCount = $log->getCount();
    $this->view->restoreCount = $restoreCount;

    $form = new Warecorp_Form('sForm','POST',$this->admin->getAdminPath('userlog'));
    $this->view->form = $form;
    $this->view->USER_LOG = defined('USER_LOG') && USER_LOG;

    $this->view->bodyContent = 'adminarea/userlog.tpl';
    date_default_timezone_set($defaultTimezone);
    
    return;
	
