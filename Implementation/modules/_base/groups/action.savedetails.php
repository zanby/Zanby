<?php
Warecorp::addTranslation('/modules/groups/action.savedetails.php.xml');

$groupId = isset($this->params["groupId"])?floor($this->params["groupId"]):0;
if (!$groupId || $this->currentGroup->getId() != $groupId){
    print "Error"; exit;
}

$form = new Warecorp_Form('form_step1', 'POST', '');
$form->addRule('categoryId',    'nonzero', Warecorp::t('Choose category'));
$form->addRule('countryId',     'nonzero', Warecorp::t('Choose country'));
$form->addRule('stateId',       'nonzero', Warecorp::t('Choose state'));
$form->addRule('cityId',        'nonzero', Warecorp::t('Choose city'));
$form->addRule('gname',         'required',     Warecorp::t('Enter Group Name'));
$form->addRule('gname',         'regexp',       Warecorp::t('Enter correct Group Name'), array('regexp' => '/^[A-Za-z0-9]{1}[A-Za-z0-9\s]+$/'));
$form->addRule('gname',         'rangelength',  Warecorp::t('Enter correct Group Name'), array('min' => 3, 'max' => 255));
$form->addRule('gname',         'callback',     Warecorp::t('Group with this name already exist'), array('func' => 'groupExist', 'params' => array($this->params['gname'], $this->params["groupId"])));
$form->addRule('description',   'required',     Warecorp::t('Enter Description'));
$form->addRule('description',   'maxlength',    Warecorp::t('Enter correct Description'), array('max' => 2000));

//if ($this->params["countryId"] == 1) {
//    $form->addRule('zipId',         'nonzero', 'Choose zip code');
//}


$form->validate($this->params);

$errors = "";
foreach ( $form->getrules() as $_field => $_rules ) {
    if ( sizeof($_rules) != 0 ) {
        foreach ( $_rules as $_rule ) {
            if ( $_rule['error'] ) {
                $errors .= $_rule['message']."<br>";
            }
        }
    }
}

if ($errors){
    print "<html><body><script>parent.document.getElementById('errorblock').innerHTML='$errors';</script></body></html>";
    exit;
}


//____________________________________________________________
$Group = new Warecorp_Group_Simple("id", $groupId);
$Group->setCategoryId( $this->params['categoryId'] );
$Group->setZipcode( $this->params['zipId'] );
$Group->setCityId( $this->params['cityId'] );
$Group->setName( $this->params['gname'] );
$Group->setPath( preg_replace("/\s{1,}/","-", strtolower(trim($this->params['gname']))) );
$Group->setMembersName( $this->params['membersName'] );
$Group->setDescription( $this->params['description'] );
$Group->setIsPrivate( $this->params['gtype'] );
$Group->setJoinMode( $this->params['hjoin'] );
$Group->setJoinCode( ($this->params['hjoin'] == 2) ? $this->params['jcode'] : null );

$city = Warecorp_Location_City::create($this->params['cityId']);
$Group->setLatitude( $city->getLatitude() );
$Group->setLongitude( $city->getLongitude() );

$Group->save();
$Group->deleteTags();
$Group->addTags($this->params['tags']);

print "<html><body><script>parent.document.location='".$Group->getGroupPath("settings/visible/groupdetails/")."';</script></body></html>";
exit;

/**
 * callback function for email already existing validation
 *
 * @param string $email
 * @return boolean  true if error
 */
function groupExist($data)
{
    $groupName  = $data[0];
    $groupId    = $data[1];

    $_db = Zend_Registry::get('DB');

    $where = $_db->quoteInto('name=?', $groupName)." AND ".$_db->quoteInto('id <> ?', $groupId);
    $sql = $_db->select()->from('zanby_groups__items', 'id')->where($where);
    return $_db->fetchOne($sql) ? true : false;
}
/**/