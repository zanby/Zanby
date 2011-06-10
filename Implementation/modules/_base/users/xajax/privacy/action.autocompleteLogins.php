<?php
    
    $objResponse = new xajaxResponse();
    
    $usersList = new Warecorp_User_List();
    $users = $usersList->returnAsAssoc()
                       ->setOrder('login')
                       ->setListSize(10)
                       ->setCurrentPage(1)
                       ->addWhere("zua.login LIKE '".preg_replace("/(_|%|'|\\\\)/", "\\\\\\1", $filter)."%'")
                       ->getList();
    $users = array_values($users);
    foreach ($users as &$user) {
        $user = array($user);
    }

	$objResponse->addScriptCall($function, $users);