<?php
class BaseAjaxController extends Warecorp_Controller_Action
{
    public $params;
    public function init()
    {
        Warecorp::addTranslation("/modules/ajax/ajax.controller.php.xml");
        parent::init();
        $this->params = $this->_getAllParams();
    }
    public function loginAjaxAction()	                                        {include_once(PRODUCT_MODULES_DIR.'/ajax/action.login.ajax.php'); return $objResponse;}
    public static function addRelationAction($mailObj, $returnedEmailParams, $params)
    {
        $oFriends = $params['request'];
        if ($oFriends instanceof Warecorp_User_Friend_Request_Item)
            $oFriends->addRelation($mailObj->message);
        else return false;
    }
    public function location_stateAction() {}
    public function closePopupAction($div_id)
    {
        $xajaxPopup = new xajaxPopup($div_id);
        $response = $xajaxPopup->getClose();
        return $response;
    }
    public function setInvitePropertiesAction( $properties = array() )  { include_once PRODUCT_MODULES_DIR.'/ajax/action.setInviteProperties.php'; return $objResponse; }


    public function changeCountryAction($countryId)                                                         {include_once(PRODUCT_MODULES_DIR.'/ajax/action.changeCountry.php'); return $objResponse; }
    public function changeStateAction($stateId)                                                             {include_once(PRODUCT_MODULES_DIR.'/ajax/action.changeState.php'); return $objResponse; }
    public function detectCountryAction($countryId)                                                         {include_once(PRODUCT_MODULES_DIR.'/ajax/action.detectCountry.php'); return $objResponse; }
    public function autoCompleteCityAction($country, $query, $function)                                     {include_once(PRODUCT_MODULES_DIR.'/ajax/action.autoCompleteCity.php'); return $objResponse; }
    public function autoCompleteZipAction($country, $query, $function)                                      {include_once(PRODUCT_MODULES_DIR.'/ajax/action.autoCompleteZip.php'); return $objResponse; }
    public function autoCompleteGroupMembersAction($group, $query, $function, $type = array())              {include_once(PRODUCT_MODULES_DIR.'/ajax/action.autoCompleteGroupMembers.php'); return $objResponse; }    
    public function sendMessageAction($userId = null)                                                       {include_once(PRODUCT_MODULES_DIR.'/ajax/action.sendMessage.php'); return $objResponse;}
    public function sendMessageDoAction($params)       	                                                    {include_once(PRODUCT_MODULES_DIR.'/ajax/action.sendMessageDo.php'); return $objResponse;}
    public function addToFriendsAction($userId = null)                                                      {include_once(PRODUCT_MODULES_DIR.'/ajax/action.addFriend.php'); return $objResponse;}
    public function addToFriendsDoAction($userId = null, $message = null, $sendAgain = false)               {include_once(PRODUCT_MODULES_DIR.'/ajax/action.addFriendDo.php'); return $objResponse;}
    public function bookmarkitAction()                                                                      {include_once(PRODUCT_MODULES_DIR.'/ajax/action.bookmarkit.php'); return $objResponse;}
    public function addbookmarkAction($params)                                                              {include_once(PRODUCT_MODULES_DIR.'/ajax/action.addBookmark.php'); return $objResponse;}
    public function resignFromGroupAction($groupId)                                                         {include_once(PRODUCT_MODULES_DIR.'/ajax/action.resignFromGroup.php'); return $objResponse;}
    public function resignFromGroupDoAction($groupId)                                                       {include_once(PRODUCT_MODULES_DIR.'/ajax/action.resignFromGroupDo.php'); return $objResponse;}
    public function loginavailableAction($login = null)                                                     {include_once(PRODUCT_MODULES_DIR.'/ajax/action.loginavailable.php'); return $objResponse;}
    public function zipCodeAvailableAction($zipcode = null, $country = null)                                {include_once(PRODUCT_MODULES_DIR.'/ajax/action.zipcodeavailable.php'); return $objResponse;}
    public function cityAvailableAction($city = null, $country = null)                                      {include_once(PRODUCT_MODULES_DIR.'/ajax/action.cityavailable.php'); return $objResponse;}
    public function cityChooseAliasAction($alias, $query)                                                   {include_once(PRODUCT_MODULES_DIR.'/ajax/action.citychoosealias.php'); return $objResponse;}
    public function cityChooseCustomAction($query, $country, $checked)                                      {include_once(PRODUCT_MODULES_DIR.'/ajax/action.citychoosecustom.php'); return $objResponse;}
    public function viewsCountingAction($videoId)                                                           {include_once(PRODUCT_MODULES_DIR.'/ajax/action.viewsCounting.php'); return $objResponse;}
    public function reloadCaptchaAction( $captchaId=null )                                                  {include_once(PRODUCT_MODULES_DIR.'/ajax/action.reloadCaptcha.php'); return $objResponse;}

    /*komarovski*/
    public function setUpDownRankAction($video_id = 0, $direction = '', $redirect_url = '')                 {include_once(PRODUCT_MODULES_DIR.'/ajax/action.setUpDownRank.php'); return $objResponse;}
    public function setUpDownRankForCOAction($video_id = 0, $direction = '', $cloneId, $params = '')        {include_once(PRODUCT_MODULES_DIR.'/ajax/action.setUpDownRankForCO.php'); return $objResponse;}

    public function showVideoPopupAction($videoId)                                                          {include_once(PRODUCT_MODULES_DIR.'/ajax/action.showVideoPopup.php'); return $objResponse;}

    public function showTranslatePopupAction($key=null, $file=null)                                                   {include_once(PRODUCT_MODULES_DIR.'/ajax/action.translate.popup.php'); return $objResponse;}
    //public function showTranslatePopupHandleAction()                                                        {include_once(PRODUCT_MODULES_DIR.'/ajax/action.translate.popup.php'); return $objResponse;}

    public function noRouteAction() { $this->_redirect('/'); }
}
