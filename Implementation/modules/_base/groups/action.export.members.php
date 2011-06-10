<?php
Warecorp::addTranslation('/modules/groups/xajax/action.export.members.php.xml');
/*
* Export lists
* @author Sergey Vaninsky
* return CSV file
*/

set_time_limit(3000);
$g = $this->currentGroup;
$userTimeZone = $this->_page->_user->getTimeZone();
if ($g instanceof Warecorp_Group_Family) {
    $filename = $g->getName();
    $filename = str_replace("\\", "_", $filename);
    $filename = preg_replace("/[^A-z|^0-9]{1,}/", "_", $filename);
    $filename = preg_replace("/_{2,}/", "_", $filename);
    if($filename[0]==='_')$filename=substr($filename,1);
    $filename = 'LIST_'.substr($filename,0,40).".csv";
    $newDoc = new Warecorp_Document_Item();
    $newDoc->setOwnerType   ('user')
           ->setOwnerId        ($this->_page->_user->getId())
           ->setCreatorId      ($this->_page->_user->getId())
           ->setOriginalName   ($filename)
           ->setCreationDate   (new Zend_Db_Expr('NOW()'))
           ->setMimeType('text/comma-separated-values')
           ->setDescription('Exported list')
           ->setPrivate        ('public');

    $time_start = microtime(true);

    $db = & Zend_Registry::get("DB");
    $query = $db->select()->distinct();
    $query->from('view_family__users as vfu', array('id' => 'vfu.user_id', 'zua.login', 'zua.email',
                             'zua.firstname', 'zua.lastname','zua.birthday','zua.last_access','zua.zipcode',
                             'zua.city_id', 'zua.gender', 'zua.register_date', 'age' => 'YEAR(now()) - YEAR(zua.birthday)', 'vfu.family_id', 'vfu.family_id', 'zua.last_access', 'zua.register_date') );
    //$query->disctinct();
    $query->joininner('zanby_users__accounts as zua', 'zua.id = vfu.user_id', array());
    //$query->limit(1000);
    $query->where('vfu.family_id = ?', $g->getId());       
    //echo $query->__toString();
    //echo "<br/>";
    //exit();

    
    
    $allusers = $db->fetchAll($query);
    
    $usersArray = array();
    foreach ($allusers as $u) {
        $usersArray[] = $u['id'];
    }
    
    $query = $db->select()->distinct()
                ->from('zanby_groups__relations zgr', array('zgm.user_id', 'CASE WHEN zgr.join_date AND zgr.join_date > zgm.creation_date THEN zgr.join_date ELSE zgm.creation_date END as join_date', 'city' => 'zul.city_name', 'state' => 'zul.state_name', 'country' => 'zul.country_name'))
                ->join('zanby_groups__members zgm', 'zgr.child_group_id = zgm.group_id', array())
                ->join('view_users__locations zul', 'zgm.user_id = zul.id', array()) 
                ->where('zgr.parent_group_id = ?', $u['family_id'])
                ->where('zgm.user_id in (?)', $usersArray);

    $datesArray = $db->fetchAll($query);  
    
    $familyHost = $g->getHost();
 $userDates = array();
   
        $userDates[$familyHost->getId()]['join_date'] = $g->getCreateDate();
        $userDates[$familyHost->getId()]['city'] = $familyHost->getCity()->name;
        $userDates[$familyHost->getId()]['state'] = $familyHost->getCity()->getState()->name;
        $userDates[$familyHost->getId()]['country'] = $familyHost->getCity()->getState()->getCountry()->name;


    foreach ($datesArray as $value) {
        $userDates[$value['user_id']]['join_date'] = $value['join_date'];
        $userDates[$value['user_id']]['city'] = $value['city'];
        $userDates[$value['user_id']]['state'] = $value['state'];
        $userDates[$value['user_id']]['country'] = $value['country'];
    }
    unset($datesArray);
    
    $path = DOC_ROOT.'/upload/documents/'.$filename;
    $csv_file = fopen($path, "w");
    /* write header */
    fwrite($csv_file,Warecorp::t("First name,Last name,User name,Email,City,State,Zip,Country,Gender,Age,Last Active,Joined Date")."\n");

    $defaultTimezone = date_default_timezone_get();
    foreach ($allusers as $u) {
        $join_date =  $userDates[$u['id']]['join_date'];

        if ( empty($join_date) ) continue;

        date_default_timezone_set('UTC');
        //$member = new Warecorp_User(null,$u);
        //$city = Warecorp_Location_City::create($u['city_id']); 
        fwrite($csv_file, $u['firstname'].",".$u['lastname'].",".$u['login']);
        fwrite($csv_file,",".$u['email'].",".$userDates[$u['id']]['city']);
        fwrite($csv_file,",".$userDates[$u['id']]['state'].",".$u['zipcode']);
        fwrite($csv_file,",".$userDates[$u['id']]['country'].",".$u['gender'].",".$u['age']);
        unset($query);
        
        $jd = new Zend_Date($join_date,Zend_Date::ISO_8601);
        $jd->setTimezone($userTimeZone);

        $jla = new Zend_Date(empty($u['last_access']) ? $u['register_date'] : $u['last_access'], Zend_Date::ISO_8601);
        $jla->setTimezone($userTimeZone);

        date_default_timezone_set($userTimeZone);
        fwrite($csv_file,",".$jla->toString('d.M.Y H:m').",".$jd->toString('d.M.Y H:m'));
        fwrite($csv_file,"\n");

        unset($member);
    }
    fclose($csv_file);
    date_default_timezone_set($defaultTimezone);

    $time_end = microtime(true);
    $time = $time_end - $time_start;
    
    /* give file for downloading */
    header("Content-Type: " . $newDoc->getMimeType());
    header("Content-Length: ". filesize($path));
    header("Content-Disposition: attachment; filename=\"" . $newDoc->getOriginalName() . "\"");
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: must-revalidate");
    header("Content-Location: ".$newDoc->getOriginalName());

    readfile($path);
    unlink($path);
    exit;
} else {
    throw new Zend_Exception(Warecorp::t("It's not intended for Simple Group "));
}

