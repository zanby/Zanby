<?php
/**
*   Zanby Enterprise Group Family System
*
*    Copyright (C) 2005-2011 Zanby LLC. (http://www.zanby.com)
*
*    This program is free software: you can redistribute it and/or modify
*    it under the terms of the GNU General Public License as published by
*    the Free Software Foundation, either version 3 of the License, or
*    (at your option) any later version.
*
*    This program is distributed in the hope that it will be useful,
*    but WITHOUT ANY WARRANTY; without even the implied warranty of
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*    You should have received a copy of the GNU General Public License
*    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*
*    To contact Zanby LLC, send email to info@zanby.com.  Our mailing
*    address is:
*
*            Zanby LLC
*            3611 Farmington Road
*            Minnetonka, MN 55305
*
* @category   Zanby
* @package    Zanby
* @copyright  Copyright (c) 2005-2011 Zanby LLC. (http://www.zanby.com)
* @license    http://zanby.com/license/     GPL License
* @version    <this will be auto generated>
*/

  /**
   * Warecorp FRAMEWORK
   * @package Warecorp_User
   * @copyright  Copyright (c) 2006
   * @todo проверить на использование статуса deleted для пользователя
   */

  /**
   * Base class for Users
   *
   */

require_once(WARECORP_DIR.'DiscussionServer/iAuthor.php');
require_once(WARECORP_DIR.'DiscussionServer/iModerator.php');
require_once(WARECORP_DIR.'Message/iRecipient.php');
require_once(WARECORP_DIR.'Message/iSender.php');

class BaseWarecorp_User 
    extends Warecorp_Data_Entity 
    implements Warecorp_DiscussionServer_iAuthor, 
               Warecorp_Message_iRecipient, 
               Warecorp_Message_iSender, 
               Warecorp_Global_iSearchFields
{
    private $id;
    private $login;
	private $path;
    private $storedLogin;
    private $storedEmail;
    private $pass;
    private $email;
    private $zipcode;
    private $cityId;
    private $gender;
    private $birthday;
    private $isBirthdayPrivate;
    private $isGenderPrivate;
    private $firstname;
    private $lastname;
    private $realname;
    private $affiliation = "";
    private $comment = "";
    private $intro;
    private $headline;
    private $registerCode;
    private $registerDate;
    private $lastAccessDate;
    private $status;
    private $timezone = "UTC";
    private $age;
    private $latitude;
    private $longitude;
    private $membershipPlan;
    private $membershipPeriod;
    private $membershipExpired;
    private $membershipDowngrade;
    private $membershipPlanEnabled;
    private $calendarPrivacy;
    private $contactMode;
    private $importedUser;
    private $accessCode;
    private $restorePasswordCode;
    private $restoreRequestTime;
    private $lastOnline;
    private $artifacts;
    private $Avatar;
    private $profile = null;
    private $Zip;
    private $City;
    private $State;
    private $Country;
    private $UserPath;
    private $tagHeadline;
    private $Privacy;
    private $Friends;
    private $Addressbook;
    private $confirmationStatus;
    private $usePathParamsMode = false;
    private $forceRedefine = false;
    private $Groups;
    private $isUsedNamedPath = false;
    public static $age_requirement = 16;

    protected $groupMembership = array();

    /**
     * @var string Locale
     * @todo Property doesn't implemented in Database
     */
    private $locale = DEFAULT_LOCALE;

    /**
     * Retun user locale
     *
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * Set new user Locale
     *
     * @param string $locale
     * @return Warecorp_User
     * @todo Is need check for valid Locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
        return $this;
    }

    public function setAffiliation($newValue)
    {
        $this->affiliation = $newValue;
        return $this;
    }
    public function getAffiliation()
    {
        return $this->affiliation;
    }
    
    public function setComment($newValue)
    {
        $this->comment = $newValue;
        return $this;
    }
    public function getComment()
    {
        return $this->comment;
    }
    
    function getProfile()
    {
        if ($this->profile === null){        
            $this->profile = Warecorp_User_Profile_Factory::getProfile($this->id);
        }
        return $this->profile;
    }
    public function setUsePathParamsMode($val = true)
    {
        $this->usePathParamsMode = $val;
        $this->forceRedefine = true;
        return $this;    
    }    
    public function getUsePathParamsMode()
    {
        return $this->usePathParamsMode;
    }    
    public function setId($newValue)
    {
        $this->id = $newValue;
        return $this;
    }
    public function getId()
    {
        return $this->id;
    }
    public function setAccessCode($accessCode)
    {
        $this->accessCode = $accessCode;
        return $this;
    }
    public function getAccessCode()
    {
        return $this->accessCode;
    }    
    public function setConfirmationStatus($status)
    {
        $this->confirmationStatus = $status;
        return $this;
    }
    public function getConfirmationStatus()
    {
        return $this->confirmationStatus;
    }        
    public function setLogin($newValue)
    {
        $this->login = $newValue;
        return $this;
    }
    public function getLogin()
    {
        if ($this->status == Warecorp_User_Enum_UserStatus::USER_STATUS_DELETED) {
            return $this->storedLogin;            
        } else return $this->login;
    }
	public function setPath($newValue)
	{
		$this->path = $newValue;
		return $this;
	}
	public function getPath()
	{
		return $this->path;
	}    
    public function setStoredEmail($newValue)
    {
        $this->storedEmail = $newValue;
        return $this;
    }
    public function getStoredEmail()
    {
        return $this->storedEmail;
    }
    public function setStoredLogin($newValue)
    {
        $this->storedLogin = $newValue;
        return $this;
    }
    public function getStoredLogin()
    {
        return $this->storedLogin;
    }   
    public function setPass($newValue)
    {
        $this->pass = $newValue;
        return $this;
    }
    public function getPass()
    {
        return $this->pass;
    }
    public function setEmail($newValue)
    {
        $this->email = $newValue;
        return $this;
    }
    public function getEmail()
    {
//        return $this->email;
        if ($this->status == Warecorp_User_Enum_UserStatus::USER_STATUS_DELETED) {
            return $this->storedEmail;            
        } else return $this->email;
    }
    public function setZipcode($newValue)
    {
        $this->zipcode = $newValue;
        return $this;
    }
    public function getZipcode()
    {
        return $this->zipcode;
    }
    public function setCityId($newValue)
    {
        $this->cityId = $newValue;
        return $this;
    }
    public function getCityId()
    {
        return $this->cityId;
    }
    public function setGender($newValue)
    {
        $this->gender = $newValue;
        return $this;
    }
    public function getGender()
    {
        return $this->gender;
    }
    public function setBirthday($newValue)
    {
        $this->birthday = $newValue;
        return $this;
    }
    public function getBirthday()
    {
        return $this->birthday;
    }
    public function setIsBirthdayPrivate($newValue)
    {
        $this->isBirthdayPrivate = $newValue;
        return $this;
    }
    public function getIsBirthdayPrivate()
    {
        return $this->isBirthdayPrivate;
    }
    public function setIsGenderPrivate($newValue)
    {
        $this->isGenderPrivate = $newValue;
        return $this;
    }
    public function getIsGenderPrivate()
    {
        return $this->isGenderPrivate;
    }
    public function setFirstname($newValue)
    {
        $this->firstname = $newValue;
        return $this;
    }
    public function getFirstname()
    {
        return $this->firstname;
    }
    public function setLastname($newValue)
    {
        $this->lastname = $newValue;
        return $this;
    }
    public function getLastname()
    {
        return $this->lastname;
    }
    public function setRealname($newValue)
    {
        $this->realname = $newValue;
        return $this;
    }
    public function getRealname()
    {
        return $this->realname;
    }
    public function setIntro($newValue)
    {
        $this->intro = $newValue;
        return $this;
    }
    public function getIntro()
    {
        return $this->intro;
    }
    public function setHeadline($newValue)
    {
        $this->headline = $newValue;
        return $this;
    }
    public function getHeadline()
    {
        return $this->headline;
    }
    public function setRegisterCode($newValue)
    {
        $this->registerCode = $newValue;
        return $this;
    }
    public function getRegisterCode()
    {
        return $this->registerCode;
    }
    public function setRegisterDate($newValue)
    {
        $this->registerDate = $newValue;
        return $this;
    }
    public function getRegisterDate()
    {
        return $this->registerDate;
    }
    public function setLastAccessDate($newValue)
    {
        $this->lastAccessDate = $newValue;
        return $this;
    }
    public function getLastAccessDate()
    {
        return $this->lastAccessDate;
    }
    public function setStatus($newValue)
    {
        $this->status = $newValue;
        return $this;
    }
    public function getStatus()
    {
        return $this->status;
    }
    public function setTimezone($newValue)
    {
        $this->timezone = $newValue;
        return $this;
    }
    public function getTimezone()
    {
        return $this->timezone;
    }
    /**
     * Set timezone from city information.
     * @author Aleksei Gusev 
     */
    public function setTimezoneFromCity()
    {
        $tzname = $this->getCity()->getTimeZone();
        if ( null === $tzname) {
            $this->setTimezone( 'Europe/London');
        } else {
            $this->setTimezone( $tzname);
        }
        return $this;
    }
    public function setLatitude($newValue)
    {
        $this->latitude = $newValue;
        return $this;
    }
    public function getLatitude()
    {
        return $this->latitude;
    }
    public function setLongitude($newValue)
    {
        $this->longitude = $newValue;
        return $this;
    }
    public function getLongitude()
    {
        return $this->longitude;
    }
    public function setMembershipPlan($newValue)
    {
        $this->membershipPlan = $newValue;
        return $this;
    }
    /**
     * @param 
     */
    public function getMembershipPlan($exact = false)
    {
        return (!$exact && $this->membershipPlan == 'complimentary premium') ? 'premium' : $this->membershipPlan;
    }
    public function setMembershipPeriod($newValue)
    {
        $this->membershipPeriod = $newValue;
        return $this;
    }
    public function getMembershipPeriod()
    {
        return $this->membershipPeriod;
    }
    public function setMembershipExpired($newValue)
    {
        $this->membershipExpired = $newValue;
        return $this;
    }
    public function getMembershipExpired()
    {
        return $this->membershipExpired;
    }
    public function setMembershipDowngrade($newValue)
    {
        $this->membershipDowngrade = $newValue;
        return $this;
    }
    public function getMembershipDowngrade()
    {
        return $this->membershipDowngrade;
    }
    public function setMembershipPlanEnabled($newValue)
    {
        $this->membershipPlanEnabled = $newValue;
        return $this;
    }
    public function getMembershipPlanEnabled()
    {
        return $this->membershipPlanEnabled;
    }
    public function setCalendarPrivacy($newValue)
    {
        $this->calendarPrivacy = $newValue;
        return $this;
    }
    public function getCalendarPrivacy()
    {
        return $this->calendarPrivacy;
    }
    public function setContactMode($newValue)
    {
        $this->contactMode = $newValue;
        return $this;
    }
    public function getContactMode()
    {
        return $this->contactMode;
    }   
    public function setRestorePasswordCode($restorePasswordCode) {
    	$this->restorePasswordCode = $restorePasswordCode;
    	return $this;
    }    
    public function getRestorePasswordCode() {
    	return $this->restorePasswordCode;
    }    
    public function setRestoreRequestTime($restoreRequestTime) {
    	$this->restoreRequestTime = $restoreRequestTime;
    	return $this;
    }    
    public function getRestoreRequestTime() {
    	return $this->restoreRequestTime;
    }    
    /**
     * Return last activity time for user as string
     * @return String
     * @author Vitaly Targonsky
     */
    public function getLastOnline(){
        if ($this->lastOnline === null) {
            
            $defaultTimezone = date_default_timezone_get();
            date_default_timezone_set('UTC');
        	$_date = new Zend_Date();
			$_now = $_date->getTimestamp();

			if (empty($this->lastAccessDate)) {
                $_last = strtotime($this->registerDate);
            } else {
                $_last = strtotime($this->lastAccessDate);
            }
            $_sec = $_now - $_last;
            if ($_sec <= ini_get('session.gc_maxlifetime')) {
                $this->lastOnline = "Online";
            } elseif ($_last > strtotime("-1 hour", $_now)) {
                $this->lastOnline = "Less than hour";
            } elseif ($_last > strtotime("-2 hours", $_now)) {
                $this->lastOnline = "1 hour";
            } elseif ($_last > strtotime("-1 day", $_now)) {
                $this->lastOnline = round($_sec/60/60) ." hours";
            } elseif ($_last > strtotime("-2 days", $_now)) {
                $this->lastOnline = "1 day";
            } elseif ($_last > strtotime("-1 week", $_now)) {
                $this->lastOnline = round($_sec/(24*60*60)) ." days";
            } elseif ($_last > strtotime("-2 weeks", $_now)) {
                $this->lastOnline = "1 week";
            } elseif ($_last > strtotime("-1 month", $_now)) {
                $this->lastOnline = round($_sec/7/24/60/60) ." weeks";
            } elseif ($_last > strtotime("-2 months", $_now)) {
                $this->lastOnline = "1 month";
            } elseif ($_last > strtotime("-3 months", $_now)) {
                $this->lastOnline = "2 month";
            } else {
                $this->lastOnline = "Over 3 months";
            }
            date_default_timezone_set($defaultTimezone);
        }
        return $this->lastOnline;
    }
    /**
     * Return user's time 
     * @return Zend_Date
     * @author Sergey Vaninsky
     */
    public function getUserTime() {
		$_usertime = new Zend_Date();
		$_usertime->setTimezone($this->getTimezone());
    	return $_usertime;
    }
    
    /**
     * Return age of user (Considering user timezone
     * @return int
     */
    public function getAge()
    {
		$_birthdate = new Zend_Date();
    	$_birthdate->setTimezone($this->getTimezone());
        if( null !== $this->getBirthday() ) {
		    $_birthdate->set($this->getBirthday(),Zend_Date::ISO_8601);
		    $_usertime = $this->getUserTime();
		    $_usertime->subYear($_birthdate);
		    $_birthdate->setYear($_usertime->getYear());
		    $this->age = $_birthdate->get(Zend_Date::YEAR);
		    if($_birthdate->compare($_usertime)>=0)$this->age-=1;
        } else {
            $this->age = 0;
        }
        return $this->age;
    }
        
    /**
     * return http address for user
     * @param action string - URI action for user
     * @param withslash boolean - need add slash to end
     * @return string - http address
     * @author Artem Sukharev
     */
    public function getUserPath( $action = null, $withslash = true, $https = false)
    {
        if ($this->forceRedefine) {
            $this->UserPath = null;
            $this->forceRedefine = false;
        }
        if ( $this->UserPath === null) {
            if ( $this->id !== null ) {
                if ($this->getPath() && USE_USER_PATH && (!$this->usePathParamsMode)) {
                    $this->UserPath = 'http://'.BASE_HTTP_HOST.'/'.LOCALE.'/user/'.mb_strtolower($this->getPath(), 'utf-8').'/';
                    $this->isUsedNamedPath = true;
                } elseif ($this->usePathParamsMode) {
                    $this->UserPath = 'http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/';
                    $this->isUsedNamedPath = false;                    
                }
            }
            if ( $this->UserPath === null ) {
                $this->UserPath = 'http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/';
            }
        }
        
        if ($https) {
            $this->UserPath = str_replace('http://', 'https://', $this->UserPath);
        } else {
            $this->UserPath = str_replace('https://', 'http://', $this->UserPath);
        }
        
        if ( $action !== null ) {
            if ( preg_match_all('/^([^\/]{1,})\/(.*?)$/mi', $action, $matches) ) {
                $action         = $matches[1][0];
                $actionParams   = $matches[2][0];
                if ( $this->isUsedNamedPath == false ) {
                    return ($withslash) ? $this->UserPath.$action.'/userid/'.$this->id.'/'.$actionParams.'/' : $this->UserPath.$action.'/userid/'.$this->id.'/'.$actionParams;
                } else {
                    return ($withslash) ? $this->UserPath.$action.'/'.$actionParams.'/' : $this->UserPath.$action.'/'.$actionParams;
                }
            } else {
                if ( $this->isUsedNamedPath == false ) {
                    return ($withslash) ? $this->UserPath.$action.'/userid/'.$this->id.'/' : $this->UserPath.$action.'/userid/'.$this->id;
                } else {
                    return ($withslash) ? $this->UserPath.$action.'/' : $this->UserPath.$action;
                }                
            }
            /*
              if ( $this->isUsedNamedPath == false ) {
              return ($withslash) ? $this->UserPath.$action.'/userid/'.$this->id.'/' : $this->UserPath.$action.'/userid/'.$this->id;
              } else {
              return ($withslash) ? $this->UserPath.$action.'/' : $this->UserPath.$action;
              }
            */
        } else {    
            return ( $withslash ) ? $this->UserPath :  substr($this->UserPath, 0, -1);
            /*
              if ( $withslash ) return $this->UserPath;
              else return substr($this->UserPath, 0, -1);
            */
        }
    }
    /**
     * Alias to getUserPath
     * @param action string - URI action for user
     * @param withslash boolean - need add slash to end
     * @return string - http address
     * @return string - http address
     * @todo peplace it to getContextPath
     */
    public function getGlobalPath($action = null, $withslash = true, $https = false)
    {
         return $this->getUserPath( $action, $withslash, $https );
    }

    /**
     * Alias to getUserPath
     * @param action string - URI action for user
     * @param withslash boolean - need add slash to end
     * @return string - http address
     * @return string - http address
     * @todo peplace it to getContextPath
     */
    public function getOwnerPath( $action = null, $withslash = true, $https = false )
    {
        return $this->getUserPath($action, $withslash, $https);
    }

    /**
     * Alias to getGroupPath
     * @param action string - URI action for user
     * @param withslash boolean - need add slash to end
     * @return string - http address
     * @return string - http address
     * @todo remove from code getGlobalPath and getOwnerPath
     */
    public function getContextPath( $action = null, $withslash = true, $https = false )
    {
        return $this->getUserPath($action, $withslash, $https);
    }
    
    /**
     * return avatar object for user
     * @return obj Warecorp_User_Avatar
     * @author Artem Sukharev
     */
    public function getAvatar()
    {
        if ( $this->Avatar === null ) {
            $select = $this->_db->select();
            $select->from('zanby_users__avatars', '*')
                ->where('user_id = ?', ($this->id) ? $this->id : new Zend_Db_Expr('NULL'))
                ->where('bydefault = ?', 1);
            $res = $this->_db->fetchRow($select);
            $res = ($res === false)?0:$res;
            $this->Avatar = new Warecorp_User_Avatar($res);
            $this->Avatar->setByDefault(1);
        } 
        return $this->Avatar;
    }

    /**
     * return Zip
     * @return string
     * @author Artem Sukharev
     */
    public function getZip()
    {
        if ( $this->Zip === null ) {
            $this->Zip = $this->zipcode;
        }
        return $this->Zip;
    }

    /**
     * return City object
     * @return Warecorp_Location_City
     * @author Artem Sukharev
     */
    public function getCity()
    {
        if ( $this->City === null ) {
            $this->City = Warecorp_Location_City::create($this->cityId);
        }
        return $this->City;
    }

    /**
     * return State object
     * @return Warecorp_Location_State
     * @author Artem Sukharev
     */
    public function getState()
    {
        if ( $this->State === null ) {
            $this->State = $this->getCity()->getState();
        }
        return $this->State;
    }

    /**
     * return Country object
     * @return Warecorp_Location_Country
     *
     */
    public function getCountry()
    {
        if ( $this->Country === null ) {
            $this->Country = $this->getState()->getCountry();
        }
        return $this->Country;
    }

    /**
     * return Tagheadline
     * @return string
     * @author Komarovski
     */
    public function getTagHeadline()
    {
        if ( $this->tagHeadline === null ) {
            $this->tagHeadline = implode(" ", Warecorp_Data_Tag::getPreparedTagsNamesByEntity($this->id, $this->EntityTypeId) );
        }
        return $this->tagHeadline;
    }

    /**
     * return Warecorp_User_Privacy object for user
     * @return Warecorp_User_Privacy privacy
     * @author Artem Sukharev
     */
    public function getPrivacy()
    {
        if ( $this->Privacy === null ) {
            $this->Privacy = new Warecorp_User_Privacy($this->getId());
        }
        return $this->Privacy;
    }

    /**
     * get artifacts object
     * @return Warecorp_Artifacts
     * @author Artem Sukharev
     */
    public function getArtifacts()
    {
        if ( $this->artifacts === null ) {
            $this->artifacts = new Warecorp_Artifacts($this);
        }
        return $this->artifacts;
    }

    /**
     * return Warecorp_User_Group_List object
     * @return Warecorp_User_Group_List
     * @author Artem Sukharev
     */
    public function getGroups()
    {
        return new Warecorp_User_Group_List($this->getId());
    }

    /**
     * returns the role of user in the group
     * @param int|Warecorp_Group_Base $group
     * @return string | null
     * @author Pavel Shutin
     */
    public function getGroupRole( $group)
    {
        if ($group instanceof Warecorp_Group_Base) $group = $group->getId();

        
        if (!isset($this->groupMembership[$group])) return false;
        else return $this->groupMembership[$group];
    }

    /**
     *
     * @return array
     */
    public function getGroupsMembership() {
        return $this->groupMembership;
    }

    /**
     * return Gallery List object
     * @return Warecorp_Photo_Gallery_List_Abstract
     * @author Artem Sukharev
     * @todo implement this in next version
     */
    public function getGalleries()
    {
        return Warecorp_Photo_Gallery_List_Factory::load($this);
    }

    /**
     * return VideoGallery List object
     * @return Warecorp_Video_Gallery_List_Abstract
     * @author Yury Zolotarsky
     */
    public function getVideoGalleries()
    {
        return Warecorp_Video_Gallery_List_Factory::load($this);
    }

    public function setImportedUser($newValue=1)
    {
        $this->importedUser = $newValue;
        return $this;
    }
    public function getImportedUser()
    {
        return $this->importedUser;
    }
    
	/**
     * Constructor.
     * @param string $key - name of key for user load, range of id|login|email.
     * if null (default) - user data don't loading.
     * @param string $val - key value
     * @return void
     * @author Artem Sukharev
     */
    public function __construct($key = null, $val = null)
    {
        parent::__construct('zanby_users__accounts', array(
            'id'                     => 'id',
            'zipcode'                => 'zipcode',
            'city_id'                => 'cityId',
            'login'                  => 'login',
            'path'                   => 'path',
            'pass'                   => 'pass',
            'email'                  => 'email',
            'gender'                 => 'gender',
            'birthday'               => 'birthday',
            'birthday_private'       => 'isBirthdayPrivate',
            'gender_private'         => 'isGenderPrivate',
            'firstname'              => 'firstname',
            'lastname'               => 'lastname',
            'realname'               => 'realname',
            'affiliation'            => 'affiliation',
            'comment'                => 'comment',
            'intro'                  => 'intro',
            'headline'               => 'headline',
            'register_code'          => 'registerCode',
            'register_date'          => 'registerDate',
            'last_access'            => 'lastAccessDate',
            'status'                 => 'status',
            'timezone'               => 'timezone',
            'latitude'               => 'latitude',
            'longitude'              => 'longitude',
            'membership_plan'        => 'membershipPlan',
            'membership_period'      => 'membershipPeriod',
            'membership_expired'     => 'membershipExpired',
            'membership_downgrade'   => 'membershipDowngrade',
            'membership_plan_enabled'=> 'membershipPlanEnabled',
            'calendar_privacy'       => 'calendarPrivacy',
            'stored_login'           => 'storedLogin',
            'stored_email'           => 'storedEmail',
            'imported_user'          => 'importedUser',
            'contact_mode'           => 'contactMode',
            'access_code'            => 'accessCode',
            'restore_password_code'  => 'restorePasswordCode',
            'confirmation_status'    => 'confirmationStatus',
            'restore_request_time'   => 'restoreRequestTime',
            'user_locale'            => 'locale')
        );

        if ($key !== null){
            $pkColName = $this->pkColName;
            $this->pkColName = $key;
            $this->loadByPk($val);
            $this->pkColName = $pkColName;
        } elseif (is_array($val)) {
        	$this->load($val);
        }

        $this->profile = Warecorp_User_Profile_Factory::getProfile($this->getId());

        $this->_loadGroupMembership();
    }

    private function _loadGroupMembership() {
        if (!$this->getId()) return false;

        $query = $this->_db->select()->from(array('zgm'=>'zanby_groups__members'),'group_id, status')->where('user_id = ?',$this->getId());
        $query2 = $this->_db->select()->from(array('zgjr'=>'zanby_groups__join_requests'),'recipient_id as group_id, "pending" as status')->where('sender_id = ?',$this->getId())->where('sender_type = ?','user');
        $query = $this->_db->select()->union(array($query,$query2));
        $this->groupMembership = $this->_db->fetchPairs($query);
        return true;
    }

    /**
     * update last access date for user
     * @return void
     * @author Vitaly Targonsky
     */
    public function updateLastAccessDate()
    {
        $defaultTimezone = date_default_timezone_get();
        date_default_timezone_set('UTC');
	   	$_date = new Zend_Date();
    	$data = array();
    	$data["last_access"]=$_date->getIso();
    	$where = $this->_db->quoteInto("id = ?",$this->id);
    	$query = $this->_db->update("zanby_users__accounts",$data,$where);
        date_default_timezone_set($defaultTimezone);
    }
    
    /**
     * Check is current user authenticated or not
     * @return boolean
     * @author Artem Sukharev
     */
    public function isAuthenticated()
    {
        return (isset($_SESSION['user_id']) && $_SESSION['user_id'] !== null) ? true : false;
    }

    /**
     * Аутентификация пользователя
     * @return void
     * @author Artem Sukharev
     */
    public function authenticate()
    {
        $_SESSION['user_id'] = $this->id;     
        Warecorp_Wordpress_SSO::authenticate( $this->id );   
    }

    /**
     * Проверка вторичной аутентификации пользователя
     * @return boolean
     * @author Artem Sukharev
     */
    public function isAuthenticatedSec()
    {
        return (!empty($_SESSION['user_id']) && !empty($_SESSION['sec_user_id']) && $_SESSION['user_id'] == $_SESSION['sec_user_id'] ) ? true : false;
    }
    /**
     * Вторичная Аутентификация пользователя( для просмотра биллинга.)
     * @return void
     * @author Vitaly Targonsky
     */
    public function authenticateSec()
    {
        $_SESSION['sec_user_id'] = $_SESSION['user_id'] = $this->id;
    }
    /**
     * logout user from system
     * @return void
     * @author Artem Sukharev
     */
    public function logout()
    {
        // unset($_SESSION['user_id']);
        // if (isset($_SESSION['sec_user_id'])) unset($_SESSION['sec_user_id']);

        $_SESSION = array();
        session_unset();
        session_destroy();
        setcookie("zanby_username", "", time() - 3600, "/", '.'.BASE_HTTP_HOST);
        setcookie("zanby_password", "", time() - 3600, "/", '.'.BASE_HTTP_HOST);
    }

    /**
     * remove user from system.
     * (Физическое удаление пользователя не происходит, пользователю выставляется статус 'deleted')
     * @return void
     * @author Artem Sukharev
     */
    public function delete()
    {
        if ( $this->id !== null ) {
            $where = $this->_db->quoteInto("(entity_id = ?)", $this->id).
                $this->_db->quoteInto(" and (classname = ?)", Warecorp_User_Addressbook_eType::USER);
            $this->setStoredLogin($this->login);
            $this->setStoredEmail($this->email);
            $this->status = Warecorp_User_Enum_UserStatus::USER_STATUS_DELETED;

            //-----------------------------Resign as Host------------------------------------------------------------------------
            $groups = $this->getGroups()
                ->setTypes(array(Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY, Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE))
                ->setMembersRole(Warecorp_Group_Enum_MemberRole::MEMBER_ROLE_HOST)
                ->getList();

            foreach($groups as $group) {
                $membersListObj = $group->getMembers();
                $members = $membersListObj->getList();
                if ( sizeof($members) > 1 ) {
                    try { $client = Warecorp::getMailServerClient(); }
                    catch ( Exception $e ) { $client = NULL; }

                    if ( $client ) {
                        try {
                            $campaignUID = $client->createCampaign();
                            $request = $client->setSender($campaignUID, $this->getEmail(), null);
                            $client->setTemplate($campaignUID, 'RESIGN_MEMBERS_INFORMATION', HTTP_CONTEXT);

                            $params = new Warecorp_SOAP_Type_Params();
                            $params->loadDefaultCampaignParams();
                            $client->addParams($campaignUID, $params);

                            $recipients = new Warecorp_SOAP_Type_Recipients();
                            $pmbRecipients = array();
                            foreach ( $members as &$_member ) {
                                if ( $_member->getId() != $this->getId() ) {
                                    $req = new Warecorp_Group_Resign_Requests();
                                    $req->setGroupId($group->getId());
                                    $req->setUserId($_member->getId());
                                    $req->save();

                                    $recipient = new Warecorp_SOAP_Type_Recipient();
                                    $recipient->setEmail( $_member->getEmail() );
                                    $recipient->setName($_member->getFirstname().' '.$_member->getLastname());
                                    $recipient->setLocale( $_member->getLocale() );
                                    $recipient->addParam('CCFID', Warecorp::getCCFID($_member));
                                    $recipient->addParam('days_number', 60);
                                    $recipient->addParam('group_host_login', $group->getHost()->getLogin());
                                    $recipient->addParam('group_name', $group->getName());
                                    $recipient->addParam('link_set_new_host', $group->getGroupPath('setnewhost').'access_code/'.md5($req->getId()).'/');
                                    $recipient->addParam('message_body', '');
                                    $recipient->addParam('message_subject', '');
                                    $recipient->addParam('recipient_full_name', $_member->getFirstname().' '.$_member->getLastname());
                                    $recipient->addParam('SITE_LINK_UNSUBSCRIBE', $_member->getUserPath('settings'));
                                    $recipients->addRecipient($recipient);
                                    
                                    $pmbRecipients[] = $_member->getId();
                                }
                            }
                            $client->addRecipients($campaignUID, $recipients);
                            
                            /* add callback to mailsrv campaign to sent PMB message */
                            $objCallback = new Warecorp_SOAP_Type_Callback();
                            $objCallback->setType( Warecorp_SOAP_Type_Callback::TYPE_RECIPIENTS );
                            $objCallback->setWsdl( BASE_URL.'/wsdl.php?t=service' );
                            $objCallback->setAction( 'callbackAddPMBMessage' );
                            $callbackUID = $client->addCallback($campaignUID, $objCallback);
                
                            $client->addCallbackParam($callbackUID, 'mailsrv:pmb_subject', null);
                            $client->addCallbackParam($callbackUID, 'mailsrv:pmb_message', null);
                            $client->addCallbackParam($callbackUID, 'sender_id', $this->getId());
                            $client->addCallbackParam($callbackUID, 'sender_type', 'user');
                            $client->addCallbackParam($callbackUID, 'recipients', join(';', $pmbRecipients) );
                            unset( $pmbRecipients );                             
                            
                            $client->startCampaign($campaignUID);
                        } catch ( Exception $e ) { throw $e; }
                    }
                    $membersListObj->resignAsHost($this->getId());
                }
            }

            //-----------------------------Remove join group requests-------------------------------------------------------------

            $query = $this->_db->select();
            $query->from(array('zgjr' => 'zanby_groups__join_requests'), 'zgjr.id');
            $query->where('zgjr.sender_type = ?', 'user');
            $query->where('zgjr.sender_id = ?', $this->getId());
            $res = $this->_db->fetchCol($query);
            if ( !empty($res) ) {
                $this->_db->delete('zanby_requests__relations',
                                   $this->_db->quoteInto('group_request_id in (?)', $res));            
                $this->_db->delete('zanby_groups__join_requests',
                                   $this->_db->quoteInto('id in (?)', $res));            
            }          
            //-----------------------------Remove from groups--------------------------------------------------------------------
            $groups = $this->getGroups()
                ->getList();
            foreach($groups as $group) {
                $group->getMembers()->removeMember($this);   
            }
            
            //-----------------------------Remove my lists-----------------------------------------------------------------------
            $lists = new Warecorp_List_List($this);
            $lists = $lists->getList();
            foreach($lists as $list) {
                $list->delete();   
            }
            
            //-----------------------------Remove my galleries-------------------------------------------------------------------
            $galleriesObj = new Warecorp_Photo_Gallery_List_User($this->getId());
            $galleries = $galleriesObj->setSharingMode(Warecorp_Photo_Enum_SharingMode::OWN)
                ->setWatchingMode(Warecorp_Photo_Enum_WatchingMode::OWN)
                ->getList();
            foreach($galleries as $gallery) {
                $gallery->delete();   
            }
            //-----------------------------Remove my videos-------------------------------------------------------------------
            $galleriesObj = new Warecorp_Video_Gallery_List_User($this->getId());
            $galleries = $galleriesObj->setSharingMode(Warecorp_Photo_Enum_SharingMode::OWN)
                ->setWatchingMode(Warecorp_Photo_Enum_WatchingMode::OWN)
                ->getList();
            foreach($galleries as $gallery) {
                $gallery->delete();   
            }            
            
            //-----------------------------Remove my documents-------------------------------------------------------------------
            $documentObj = new Warecorp_Document_List($this);
            $documents = $documentObj->setShowShared(false)->getList();            
            foreach($documents as $document) {
                $document->delete();
            }
            $foldersObj = new Warecorp_Document_FolderList($this);
            $folders = $foldersObj->getList();
            foreach($folders as $folder) {
                $folder->deleteFolderRecursively();       
            }            
             
            //-----------------------------Remove my events----------------------------------------------------------------            
            $objEvents = new Warecorp_ICal_Event_List_Standard();            
            $objEvents->setOwnerIdFilter($this->getId());
            $objEvents->setOwnerTypeFilter(Warecorp_ICal_Enum_OwnerType::USER);           
            $objEvents->setPrivacyFilter(array(0,1));
            $objEvents->setSharingFilter(array(0));
            $objEvents->setCurrentEventFilter(true);
            $objEvents->setExpiredEventFilter(true);
            $events = $objEvents->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getList();
            foreach ($events as $event) {
                $event->delete();
            }
            //-----------------------------Remove from attendee----------------------------------------------------------------
            $where = $this->_db->quoteInto('attendee_owner_id = ?', $this->getId()).' AND '.
                $this->_db->quoteInto('attendee_owner_type = ?', 'user');
            $this->_db->delete('calendar_event_attendee', $where); 
            
            
            //-----------------------------Remove Tgas--------------------------
            parent::deleteTags();
            //-----------------------------Remove from friends-------------------------------------------------------------------
            $friendsItems = $this->getFriendsList()->getList();
            foreach($friendsItems as $item) {
                $item->delete();    
            }           
            //-----------------------------Remove friend requests----------------------------------------------------------------            
            $where = $this->_db->quoteInto('sender_id = ?', $this->getId()).' or '.
                $this->_db->quoteInto('recipient_id = ?', $this->getId());
            $this->_db->delete('zanby_users__friends_requests', $where); 
            //-----------------------------Remove messages------------------------------------------------------------------------
            $where = $this->_db->quoteInto('owner_id = ?', $this->getId());
            $rows_affected = $this->_db->delete('zanby_users__messages', $where);          

            parent::save();            
            $data['login'] = new Zend_Db_Expr('NULL');
            $data['email'] = new Zend_Db_Expr('NULL');
            $data['path']  = new Zend_Db_Expr('NULL');
            
            $where = $this->_db->quoteInto('id = ?', $this->getId()); 
            $this->_db->update('zanby_users__accounts', $data, $where);
            //-----------------------------Remove profile-------------------------------------------------------------------
            if ($this->profile !== null){
                $this->profile->delete();
                $this->profile = null;
            }
            //-----------------------------Remove registration on Facebook-------------------------------------------------------------------
            if ( defined('FACEBOOK_USED') && FACEBOOK_USED) {
                $facebookUser = Warecorp_Facebook_User::loadByUserId($this->getId());
                if ( $facebookUser ) {
                    $facebookUser->delete(true);
                }
                
            }
        }
    }

    /**
     * restore user password
     * @return void
     * @author Artem Sukharev
     */
    public function restorePassword()
    {
        $length = 10;

        // This variable contains the list of allowable characters for the
        // password.  Note that the number 0 and the letter 'O' have been
        // removed to avoid confusion between the two.  The same is true
        // of 'I' and 1.
        //$allowable_characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $allowable_characters = 'abcdefghijklmnopqrstuvwxyz23456789';

        // We see how many characters are in the allowable list:
        $len = strlen($allowable_characters);

        // Seed the random number generator with the microtime stamp.
        mt_srand((double)microtime() * 1000000);

        // Declare the password as a blank string.
        $pass = '';

        // Loop the number of times specified by $length.
        for ($i = 0; $i < $length; $i++) {

            // Each iteration, pick a random character from the
            // allowable string and append it to the password:
            $pass .= $allowable_characters[mt_rand(0, $len - 1)];
        }

        $this->pass = md5($pass);
        $this->save();

        $this->sendRestorePassword( $pass );
    }
    
    public function restorePasswordByUrl() {
        $allowable_characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';

        $code = '';
        $len = strlen($allowable_characters);
        
        for ($i = 0; $i < 30; $i++) {
            $code .= $allowable_characters[mt_rand(0, $len - 1)];
        }
        
        $this->setRestorePasswordCode($code);
        $this->setRestoreRequestTime(new Zend_Db_Expr('NOW()'));
        $this->save();

        $sender_object = new Warecorp_User();
        
        $this->sendRestorePasswordByUrl();
    }

    /**
     * Загружает дефолтовый аватар пользователя
     * @return voidd
     */
    public function loadDefaultAvatar() {
        $select = $this->_db->select();
        $select->from('zanby_users__avatars', 'id')
            ->where('user_id = ?', $this->id)
            ->where('bydefault = ?', 1);
        $res = $this->_db->fetchOne($select);
        if ( $res ) $this->avatar = new Warecorp_User_Avatar($res);
    }

    /**
     * validate user login data
     * @param string $login
     * @param string $password
     * @return boolean
     */
    public static function validateLogin($login, $password)
    {
        $db = Zend_Registry::get("DB");
        $select = $db->select();
        $select->from('zanby_users__accounts', array('count' => new Zend_Db_Expr('count(id)')))
            ->where('login  = ?', $login)
            ->where('pass   = ?', md5($password))
            ->where('status = ?', Warecorp_User_Enum_UserStatus::USER_STATUS_ACTIVE);

        $res = $db->fetchOne($select);
        return (boolean) $res;
    }

    /**
     * Проверяет, зарегестрированн ли пользовател с указанными параметрами
     * @param string $key - ключ, может быть id|login|email
     * @param mixed $value - заначение ключа
     * @param mixed $exclude - значения ключа, которые надо исключить
     * @return boolean
     */
    public static function isUserExists($key, $value, $exclude = null)
    {
        $db = Zend_Registry::get("DB");
        if ( !in_array($key, array('id','login','email', 'path')) ) {
            return false;
        }
        $select = $db->select();
        $select->from('zanby_users__accounts',array('count' => new Zend_Db_Expr('count(id)')))
            ->where($key.' = ?', $value)
            ->where('status != ?', Warecorp_User_Enum_UserStatus::USER_STATUS_DELETED);
        if ( $exclude !== null ) {
            $select->where($key.' NOT IN (?)', $exclude);
        }
        $res = $db->fetchOne($select);
        return (boolean) $res;
    }

    /*
    * @author Alexander Komarovski
    * This method checks inctance of Warecorp_User. User with ID and active status will be "Application member"
    * If user is not application member he will be anonymous user (this property will used in access managers)
    */
    public function isAppMember() {
        if (!empty($this->id) &&  $this->getStatus() == Warecorp_User_Enum_UserStatus::USER_STATUS_ACTIVE) {
            return true;
        }
        return false;
    }

    /**
     * Returns true if user has groups of professional family group
     * @author Alexander Komarovski
     */
    public function isCompliment()
    {
        $select = $this->_db->select();
        $select->from(array('zgm' => 'zanby_groups__members'), 'zgm.user_id')
            ->joinInner(array('zgi' => 'zanby_groups__items'), 'zgi.id = zgm.group_id')
            ->joinLeft(array('zgr' => 'zanby_groups__relations'), 'zgr.child_group_id = zgi.id')
            ->joinLeft(array('zgif' => 'zanby_groups__items'), 'zgif.id = zgr.parent_group_id')
            ->where('zgm.user_id = ?', $this->id)
            ->where('zgif.type IN (?)', 'family')
            ->where('zgif.payment_type IN (?)', 'business')
            ->where('zgi.type IN (?)', Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE)
            ->where('zgm.is_approved', 1);
        $groups = $this->_db->fetchCol($select);

        return (bool) $groups;
    }

    /**
     * Get list of current user friends
     * @return array 
     * @author Eugene Kirdzei
     */
    public function getFriendsList()
    {
        if ( null === $this->Friends ) {
            $this->Friends = new Warecorp_User_Friend_List();
            $this->Friends->setUserId($this->getId());
        }

        return $this->Friends;
    }

    /**
     * Get list of friends of friends
     * @return array 
     * @author Eugene Kirdzei
     */
    public function getFriendsOfFriendsList()
    {
        $fof = new Warecorp_User_Friend_ofFriend_List();
        $fof->setUserId($this->getId());

        return $fof;
    }

    /**
     * @author Komarovski
     */
    public function getFriendsListSortedByCountry($size = 10)
    {
        throw new Zend_Exception("This method is outworn. See class Warecorp_User_Friend_List");
    }

    /**
     * Return curent datetime for user
     * @return int
     */
    public function getNowTimeStamp()
    {
        $select = $this->_db->select();
        if ($this->id) {
            $select->from('zanby_users__accounts', array('now_time' => new Zend_Db_Expr('UNIX_TIMESTAMP(CONVERT_TZ(NOW(), "UTC", timezone))')))
                ->where('id = ?', $this->id);
        } else {
            $select->from('DUAL', array('now_time' => new Zend_Db_Expr('UNIX_TIMESTAMP(CONVERT_TZ(NOW(), "UTC", "America/New_York"))')));
        }
        $time = $this->_db->fetchOne($select);
        return $time;
    }

    /**
     * returns user's address book
     * @return Warecorp_User_Addressbook_ContactList
     * @author Ivan Khmurchik
     */
    public function getAddressbook()
    {
        $numArgs = func_num_args();
        if ($numArgs > 0) throw new Warecorp_Exception("ERROR USING METHOD getAddressbook. NO params.");
        if ( $this->Addressbook === null ) {
            $this->Addressbook = new Warecorp_User_Addressbook_ContactList(false, 'owner_id', $this->id);
        }
        return $this->Addressbook;
    }

    /**
     * @return Addressbook list
     * @author Ivan Khmurchik 
     */
    public function getAddressbookList()
    {
        throw new Warecorp_Exception('OBSOLETE USE METOD.');
    }
    /**
     * provides list of active letters, used in addressbook first-letter filter
     *
     * @return array('A'=> $num1, ..., 'Z'=> $num26). Letters can be skipped
     *
     * @todo compatibility with non-ascii and multichars symbols
     * @author Alexey Loshkarev
     */
    public function getAddressbookLetters()
    {
        throw new Warecorp_Exception('OBSOLETE USE METOD.');
        $select = $this->_db->select();

        $select->from('view_users__addressbook',
                      array('UPPER(SUBSTRING(first_name, 1, 1)) AS "letter"',
                            'COUNT(*) AS "count"'))
            ->where('owner_id = ?', $this->id)
            ->where('ORD(UPPER(SUBSTRING(first_name, 1, 1))) BETWEEN 65 AND 90')
            ->group('letter')
            ->order('letter');
        $result = $this->_db->fetchPairs($select);
        return $result;
    }
    /**
     * return addressbook size
     * @return integer count
     * @author Alexey Loshkarev
     * @author Ivan Khmurchik
     */
    public function getAddressbookCount($filter = false)
    {
        throw new Warecorp_Exception('OBSOLETE USE METOD.');
        $select = $this->_db->select()
            ->from('view_users__addressbook', new Zend_Db_Expr('COUNT(id)'))
            ->where('owner_id = ?', $this->id);
        if ($filter) {
            $select->where('UPPER(SUBSTRING(first_name, 1, 1)) = ?', $filter);
        }

        $count = $this->_db->fetchOne($select);

        return $count;
    }
    /**
     * Add member/group to user's addressbook. Designed for automatic contacts addition
     *
     * @param enum('user','group') - what type of contact we wan't to add
     * @param integer id - user/group ID
     * @return boolean - result
     *
     * @author Alexey Loshkarev
     */
    public function addToAddressbook($type, $id)
    {
        throw new Warecorp_Exception('OBSOLETE USE METOD.');
        $fields = array( 'user' => 'userId', 'group' => 'groupId' );
        if (in_array($type, array_keys($fields))) {
            $field = $fields[$type];
            if (!Warecorp_User_Addressbook::isExists($this->id, $type, $id)) {
                $abEntity = new Warecorp_User_Addressbook();
                $abEntity->ownerId = $this->id;
                $abEntity->$field = $id;
                $abEntity->save();
            }
            return true;
        } else {
            // @todo here must be exception rising...
            throw new Zend_Exception('Incorrect addressbook item type!');
        }
    }
    /**
     * Return list of maillists
     * @return array of Warecorp_User_Maillist
     * @author Alexey Loshkarev
     */
    public function getMaillists() {
        throw new Warecorp_Exception('OBSOLETE USE METOD.');
        $select = $this->_db->select()
            ->from('zanby_users__maillists', 'id')
            ->where('user_id = ?', $this->id);

        $maillists = $this->_db->fetchCol($select);

        foreach ($maillists as &$maillist) {
            $maillist = new Warecorp_User_Maillist($maillist);
        }

        return $maillists;
    }
    /**
     * @author Ivan Meleshko
     */
    public function save()
    {
        $userPath = preg_replace('/@.*/', '', $this->getLogin());
        $userPath = preg_replace('/[ _@\.]/', '-', $userPath);
        $userPath = preg_replace('/[^a-zA-Z0-9\-]+/', '', $userPath);
        $userPath = preg_replace('/^[^a-zA-Z0-9]*/', '', $userPath);//komarovski
        $userPath = preg_replace('/[^a-zA-Z0-9]*$/', '', $userPath);//sukharev
        
        if ( strlen($userPath) == 0 ) $userPath = 'user';
        if ( $this->isUserExists('path', $userPath, $this->getPath()) ) {
            $i = 1;
            $gen_flag = true;
            while ( $gen_flag ) {
                $_path = $userPath.'-'.$i++;
                if ( !$this->isUserExists('path', $_path, $this->getPath()) ) $gen_flag = false;
            }
            $userPath = $_path;
        }
        $this->setPath($userPath);

        if (!$this->id) {
            //Generating uniq register_code
            $gen_flag = true;
            $allowable_characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';
            $len = strlen($allowable_characters);
            while ( $gen_flag ) {
                mt_srand((double)microtime() * 1000000);
                $code = '';
                for ( $i = 0; $i < 50; $i++ ) {
                    $code .= $allowable_characters[mt_rand(0, $len - 1)];
                }
                $attenduance = new Warecorp_User('register_code', $code);
                if (!$attenduance->id) $gen_flag = false;
            }
            $this->registerCode = $code;

            parent::save();

            $addressbook = new Warecorp_User_Addressbook_ContactList(false);
            $addressbook->setIsMain('1');
            $addressbook->setContactListName($this->getLogin() . '\'s addressbook');
            $addressbook->setContactListOwnerId($this->getId());
            $addressbook->save();
        } else {
            parent::save();
        }
        //-----------------------------Save profile-------------------------------------------------------------------
        if ($this->profile !== null){
            $this->profile->setId($this->getId());
            $this->profile->save();
            $this->profile = null;
        }
    }
    
    public function saveStatus( $status )
    {
        $data = array();
        $data['status'] = $status;
        $result = $this->_db->update(
            $this->tableName, $data,
            $this->_db->quoteInto($this->pkColName . ' = ?', $this->getPKPropertyValue())
        );   
    }
    
    public function saveCity()
    {
        $data = array();
        $data['city_id'] = $this->getCityId();
        $result = $this->_db->update(
            $this->tableName, $data,
            $this->_db->quoteInto($this->pkColName . ' = ?', $this->getPKPropertyValue())
        );   
    }
        
    /*
      +-----------------------------------
      |
      | iAuthor Interface
      |
      +-----------------------------------
    */

    public function getAuthorId(){
        return $this->id;
    }
    public function getAuthorName(){
        return $this->login;
    }
    public function getAuthorEmail(){
        return $this->email;
    }
    public function getAuthorAvatar(){
        return $this->getAvatar();
    }
    public function getAuthorHomePageLink(){
        return $this->getUserPath('profile');
    }
    static public function createAuthorById($authorId){
        return new Warecorp_User('id', $authorId);
    }

    /*
      +-----------------------------------
      |
      | END: iAuthor Interface
      |
      +-----------------------------------
    */
    
    /*
      +-----------------------------------
      |
      | iModerator Interface
      |
      +-----------------------------------
    */

    public function getModeratorId(){
        return $this->id;
    }
    public function getModeratorName(){
        return $this->login;
    }
    public function getModeratorEmail(){
        return $this->email;
    }
    public function getModeratorHomePageLink(){
        return $this->getUserPath('profile');
    }

    /*
      +-----------------------------------
      |
      | iSender Interface
      |
      +-----------------------------------
    */

    public function getSenderId()
    {
        return $this->id;
    }
    public function getSenderDisplayName()
    {
        return $this->login;
    }

    /*
      +-----------------------------------
      |
      | iRecipient Interface
      |
      +-----------------------------------
    */

    public function getRecipientId()
    {
        return $this->id;
    }
    public function getRecipientDisplayName()
    {
        return $this->login;
    }
    
    /*
      +-----------------------------------
      |
      | END: iRecipient Interface
      |
      +-----------------------------------
    */
    
    /*
      +-----------------------------------
      |
      | Warecorp_Global_iSearchFields Interface
      |
      +-----------------------------------
    */
    
    /**
    * return object
    * @return void object
    */
    public function entityObject()
    {
        return $this;
    }
    
    /**
    * return object id
    * @return int
    */
    public function entityObjectId() 
    {
        return $this->getId(); 
    }

    /**
    * return object type. possible values: simple, family, committies and blank string or null
    * @return string
    */
    public function entityObjectType()
    {
        return null;
    }

    /**
    * return owner type
    * possible values: group, user
    * @return string
    */
    public function entityOwnerType()
    {
        return "user";
    }

    /**
    * return title for entity (like group name, username, photo or gallery title)
    * @return string
    */
    public function entityTitle()
    {
        return $this->getLogin();
    }

    /**
    * return headline for entity (like group headline, members first and last name, photo or gallery title,etc). 
    * for entities which didn't have headline will be returned entityTitle
    * @return string
    */
    public function entityHeadline()
    {
        return $this->getRealname();
    }
        
    /**
    * return description for entity (group description, user intro, gallery or photo description, etc.). 
    * for entities which didn't have headline will be returned entityTitle
    * @return string
    */
    public function entityDescription()
    {
        return $this->getIntro();
    }

    /**
    * return username of owner 
    * @return string
    */
    public function entityAuthor()
    {
        return "";
    }

    /**
    * return user_id of entity owner 
    * @return string
    */
    public function entityAuthorId()
    {
        return null;
    }

    /**
    * return picture URL (avatar, group picture, trumbnails, etc.) 
    * @return int
    */
    public function entityPicture()
    {
        return $this->getAvatar();
    }
    
    /**
    * return creation date for all elements
    * @return string
    */
    public function entityCreationDate()
    {
        return $this->getRegisterDate();
    }

    /**
    * return update date for all elements
    * @return string
    */
    public function entityUpdateDate()
    {
        return $this->getLastAccessDate(); 
    }

    /**
    * items count (members, posts, child groups, etc.)
    * @return int
    */
    public function entityItemsCount()
    {
        return null;
    }
    
    /**
    * get category for entity (event type, list type, group category, etc)
    * possible values: string 
    * @return int
    */
    public function entityCategory()
    {
        return "";
    }

    /**
    * get category_id for entity (event type, list type, group category, etc)
    * possible values: int , null 
    * @return int
    */
    public function entityCategoryId()
    {
        return null;
    }

    /**
    * get country for entity (users, groups, events)
    * possible values: string
    * @return int
    */
    public function entityCountry()
    {
        return $this->getCountry()->name;
    }

    /**
    * get country_int for entity (users, groups, events)
    * possible values: int, null
    * @return int
    */
    public function entityCountryId()
    {
        return $this->getCountry()->id;
    }

    
    /**
    * get city for entity (users, groups, events)
    * possible values: string
    * @return int
    */
    public function entityCity()
    {
        return $this->getCity()->name;
    }

    /**
    * get city_id for entity (users, groups, events)
    * possible values: int, null
    * @return int
    */
    public function entityCityId()
    {
        return $this->getCityId();
    }
    
    /**
    * get zip for entity (users, groups, events)
    * possible values: int, null
    * @return int
    */
    public function entityZIP()
    {
        return $this->getZipcode();
    }
    
    /**
    * get state for entity (users, groups, events)
    * possible values: int, null
    * @return int
    */
    public function entityState()
    {
        return $this->getState()->name;
    }

    /**
    * get state_id for entity (users, groups, events)
    * possible values: int, null
    * @return int
    */
    public function entityStateId()
    {
        return $this->getState()->id;
    }

    /**
    * path to video(video galleries)
    * possible values: string
    * @return int
    */
    public function entityVideo()
    {
        return "";
    }
    
    /**
    * comments count for entity
    * possible values: int
    * @return int
    */
    public function entityCommentsCount()
    {
        return null;
    }  
    
    public function entityURL()
    {
        return "";
        //return $this->getTopic()->getDiscussion()->getGroup()->getGroupPath('topic');
    }
    
    /*
      +-----------------------------------
      |
      | END: Warecorp_Global_iSearchFields Interface
      |
      +-----------------------------------
    */    
    
    /*
      +-----------------------------------
      |
      | Function that used to send email to MailSrv Service
      | @author Artem Sukharev
      |
      +-----------------------------------
    */        
    
    static public function isValidEmailAddress($value) {
        $regex = '/^((\"[^\"\f\n\r\t\v\b]+\")|([\w\!\#\$\%\&\'\*\+\-\~\/\^\`\|\{\}]+(\.[\w\!\#\$\%\&\'\*\+\-\~\/\^\`\|\{\}]+)*))@((\[(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))\])|(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))|((([A-Za-z0-9\-])+\.)+[A-Za-z\-]+))$/';
        if ( preg_match($regex, $value) ) {
            if (function_exists('checkdnsrr')) {
                $tokens = explode('@', $value);
                if (!(checkdnsrr($tokens[1], 'MX') || checkdnsrr($tokens[1], 'A'))) {
                    return false;
                }
            }
        } else {
            return false;
        }
        return true;
    }
    
    static public function getUsersFromEmailsString( $strEmails ) {
        $return = array();
        $split = preg_split("/,|\n/im",$strEmails);
        if ( sizeof($split) != 0 ) {
            foreach ( $split as $ind => $email ) {
                if ( trim($email) != "" ) {
                    $_user = str_replace("\r","",trim($email));
                    if ( Warecorp_User::isUserExists('login', $_user) ) {
                        $return[] = new Warecorp_User('login', $_user);
                    } elseif ( Warecorp_User::isUserExists('email', $_user) ) {
                        $return[] = new Warecorp_User('email', $_user);
                    } elseif ( Warecorp_User::isValidEmailAddress($_user) ) {
                        $User = new Warecorp_User();
                        $User->setFirstname('Guest');
                        $User->setEmail($_user);
                        $return[] = $User; 
                    }
                }
            }
        }
        return $return;        
    }
    
    public function sendForwardProfile( $objSender, $forwardUser, $toEmail, $Subject, $Message )
    {
        /* SOAP: MailSrv */
        $msrvRecipients = new Warecorp_SOAP_Type_Recipients();
        $msrvSended = false;
                
        /* SOAP: MailSrv */
        if ( Warecorp::isMailServerUsed() && Warecorp::isMailServerTemplateRegistered( 'FORWARD_PROFILE' ) ) {
                       
            /* SOAP: MailSrv */       
            try { $client = Warecorp::getMailServerClient(); }
            catch ( Exception $e ) { $client = null; }   
            
            if ( $client ) {   

                $objRecipient = new Warecorp_User('email', $toEmail);
                $objRecipient->setEmail($toEmail);
                
                $recipient = new Warecorp_SOAP_Type_Recipient();
                $recipient->setEmail( $objRecipient->getEmail() );
                $recipient->setName( $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : null );
                $recipient->setLocale( null );
                $recipient->addParam('CCFID', Warecorp::getCCFID($objRecipient));
                $recipient->addParam( 'recipient_full_name', $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : '' );
                $recipient->addParam( 'SITE_LINK_UNSUBSCRIBE', $objRecipient->getUserPath('settings') );
                $msrvRecipients->addRecipient($recipient);
                
                try { 
                    $campaignUID = $client->createCampaign();                        
                    $request = $client->setSender($campaignUID, 'messages-noreply@bounce.'.DOMAIN_FOR_EMAIL, SITE_NAME_AS_STRING );
                    $request = $client->setTemplate($campaignUID, 'FORWARD_PROFILE', HTTP_CONTEXT); /* FORWARD_PROFILE */
                                        
                    /* add params */
                    $params = new Warecorp_SOAP_Type_Params();
                    $params->loadDefaultCampaignParams();
                    $params->addParam( 'sender_login', $objSender->getId() ? $objSender->getLogin() : 'Anonimous' );
                    $params->addParam( 'message_plain', $Message );
                    $params->addParam( 'message_html', nl2br(htmlspecialchars($Message)) );
                    $params->addParam( 'message_subject', $Subject );
                    $params->addParam( 'url_forward_user_profile', $forwardUser->getUserPath('profile') );
                    $request = $client->addParams($campaignUID, $params);
                    
                    $request = $client->addRecipients($campaignUID, $msrvRecipients);
                    $request = $client->startCampaign($campaignUID);
                    
                    $msrvSended = true;
                } catch ( Exception $e ) { $msrvSended = false; }
            }
        }

        /**
         * TODO : MAILSRV_REMOVE : Remove it when transfer to mailsrv will be done
         * if emails haven't been sended by SOAP: MailSrv, send it 
         */
        if ( !$msrvSended ) {
            $mail = new Warecorp_Mail_Template('template_key', 'FORWARD_PROFILE');
            $sender = new Warecorp_User();
            $sender->setEmail(ADMIN_EMAIL);
            
            $recipient = new Warecorp_User('email', $toEmail);
            $recipient->setEmail($toEmail);
        
            $mail->setSender($sender);
            $mail->addRecipient($recipient);
            $mail->addParam('subject', $Subject);
            $mail->addParam('message', $Message);
            $mail->addParam('forwardUser', $forwardUser);
            $mail->addParam('senderUser', $objSender);
        
            $mail->send();
        }
    }
    
    public function sendMessageToNotRegisteredUser( $objSender, $arrEmails, $Subject, $Message )
    {
        /* SOAP: MailSrv */
        $msrvRecipients = new Warecorp_SOAP_Type_Recipients();
        $msrvSended = false;
                
        /* SOAP: MailSrv */
        if ( Warecorp::isMailServerUsed() && Warecorp::isMailServerTemplateRegistered( 'MESSAGE_TO_NOT_REGISTER_RECIPIENT' ) ) {
                       
            /* SOAP: MailSrv */       
            try { $client = Warecorp::getMailServerClient(); }
            catch ( Exception $e ) { $client = null; }   
            
            if ( $client ) {   

                if ( sizeof($arrEmails) != 0 ) {
                    foreach ( $arrEmails as $toEmail ) {
                        $objRecipient = new Warecorp_User('email', $toEmail);
                        $objRecipient->setEmail($toEmail);
                        $recipient = new Warecorp_SOAP_Type_Recipient();
                        $recipient->setEmail( $objRecipient->getEmail() );
                        $recipient->setName( $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : null );
                        $recipient->setLocale( null );
                        $recipient->addParam('CCFID', Warecorp::getCCFID($objRecipient));
                        $recipient->addParam( 'recipient_full_name', $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : '' );
                        $recipient->addParam( 'SITE_LINK_UNSUBSCRIBE', $objRecipient->getUserPath('settings') );
                        $msrvRecipients->addRecipient($recipient);
                    }
                }
                
                try { 
                    $campaignUID = $client->createCampaign();                        
                    $request = $client->setSender($campaignUID, $objSender->getEmail(), $objSender->getFirstname().' '.$objSender->getLastname() );
                    $request = $client->setTemplate($campaignUID, 'MESSAGE_TO_NOT_REGISTER_RECIPIENT', HTTP_CONTEXT); /* MESSAGE_TO_NOT_REGISTER_RECIPIENT */
                                        
                    /* add params */
                    $params = new Warecorp_SOAP_Type_Params();
                    $params->loadDefaultCampaignParams();
                    $params->addParam( 'message_content', $Message );
                    $params->addParam( 'message_subject', $Subject );
                    $request = $client->addParams($campaignUID, $params);
                    
                    $request = $client->addRecipients($campaignUID, $msrvRecipients);
                    $request = $client->startCampaign($campaignUID);
                    
                    $msrvSended = true;
                } catch ( Exception $e ) { $msrvSended = false; }
            }
        }

        /**
         * TODO : MAILSRV_REMOVE : Remove it when transfer to mailsrv will be done
         * if emails haven't been sended by SOAP: MailSrv, send it 
         */
        if ( !$msrvSended ) {
            //sending messages to not registered users
            $mail = new Warecorp_Mail_Template('template_key', 'MESSAGE_TO_NOT_REGISTER_RECIPIENT');
            $mail->setSender($objSender);
            $mail->setEmailCharset('UTF-8');
            $mail->sendToPMB(false);
            $mail->sendEmailTextPart(false);
            $mail->addParam('original_message', $Message);
            $mail->addParam('subject', $Subject);
            foreach ($arrEmails AS $recipient){
                $notRegUser = new Warecorp_User();
                $notRegUser->setEmail($recipient);
                $mail->addRecipient($notRegUser);
            }
            $mail->send();
        }
    }
    
    public function sendFriendInvite( Warecorp_User $objSender, Warecorp_User $objRecipient, $objRequest, $Message )
    {
        /* SOAP: MailSrv */
        $msrvRecipients = new Warecorp_SOAP_Type_Recipients();
        $pmbRecipients = array();
        $msrvSended = false;
                
        /* SOAP: MailSrv */
        if ( Warecorp::isMailServerUsed() && Warecorp::isMailServerTemplateRegistered( 'USERS_FRIEND_INVITE' ) ) {
                       
            /* SOAP: MailSrv */       
            try { $client = Warecorp::getMailServerClient(); }
            catch ( Exception $e ) { $client = null; }   
            
            if ( $client ) {   

                $recipient = new Warecorp_SOAP_Type_Recipient();
                $recipient->setEmail( $objRecipient->getEmail() );
                $recipient->setName( $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : null );
                $recipient->setLocale( null );
                $recipient->addParam('CCFID', Warecorp::getCCFID($objRecipient));
                $recipient->addParam( 'recipient_full_name', $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : '' );
                $recipient->addParam( 'url_requests', $objRecipient->getUserPath('friends/requests/received') );
                $recipient->addParam( 'SITE_LINK_UNSUBSCRIBE', $objRecipient->getUserPath('settings') );
                $msrvRecipients->addRecipient($recipient);
                $pmbRecipients[] = $objRecipient->getId() ? $objRecipient->getId() : $objRecipient->getEmail();
                
                try { 
                    $campaignUID = $client->createCampaign();                        
                    $request = $client->setSender($campaignUID, $objSender->getEmail(), $objSender->getFirstname().' '.$objSender->getLastname() );
                    $request = $client->setTemplate($campaignUID, 'USERS_FRIEND_INVITE', HTTP_CONTEXT); /* USERS_FRIEND_INVITE */
                                        
                    /* add params */
                    $params = new Warecorp_SOAP_Type_Params();
                    $params->loadDefaultCampaignParams();
                    $params->addParam( 'sender_login', $objSender->getLogin() );
                    $params->addParam( 'sender_pronoun', $objSender->getGender() == 'male' ? Warecorp::t('his') : ( $objSender->getGender() == 'female' ? 'her' : 'his/her' ) );                    
                    $params->addParam( 'message', $Message );
                    $params->addParam( 'message_plain', $Message );
                    $params->addParam( 'message_html', nl2br(htmlspecialchars($Message)) );
                    $request = $client->addParams($campaignUID, $params);
                    
                    /* add callback to mailsrv campaign to sent PMB message */
                    $objCallback = new Warecorp_SOAP_Type_Callback();
                    $objCallback->setType( Warecorp_SOAP_Type_Callback::TYPE_RECIPIENTS );
                    $objCallback->setWsdl( BASE_URL.'/wsdl.php?t=service' );
                    $objCallback->setAction( 'callbackAddPMBMessage' );
                    $callbackUID = $client->addCallback($campaignUID, $objCallback);
        
                    $pmbRecipients = ( null === $pmbRecipients || !is_array($pmbRecipients) ) ? array() : $pmbRecipients;
                    $client->addCallbackParam($callbackUID, 'mailsrv:pmb_subject', null);
                    $client->addCallbackParam($callbackUID, 'mailsrv:pmb_message', null);
                    $client->addCallbackParam($callbackUID, 'sender_id', $objSender->getId());
                    $client->addCallbackParam($callbackUID, 'sender_type', ($objSender instanceof Warecorp_User) ? 'user' : 'group');
                    $client->addCallbackParam($callbackUID, 'recipients', join(';', $pmbRecipients) );
                    unset( $pmbRecipients );
                    
                    /**
                     * add callback param as onSend method
                     * if 'onSend_addFriendRequest' parameter is present sooap method add request to this message 
                     * @author Artem Sukharev
                     */
                    $onSend_addFriendRequest = array();
                    $onSend_addFriendRequest['request'] = $objRequest->getId();
                    $client->addCallbackParam($callbackUID, 'onSend_addFriendRequest', Zend_Json::encode($onSend_addFriendRequest) );
                    
                    $request = $client->addRecipients($campaignUID, $msrvRecipients);
                    $request = $client->startCampaign($campaignUID);
                    
                    $msrvSended = true;
                } catch ( Exception $e ) { $msrvSended = false; }
            }
        }

        /**
         * TODO : MAILSRV_REMOVE : Remove it when transfer to mailsrv will be done
         * if emails haven't been sended by SOAP: MailSrv, send it 
         */
        if ( !$msrvSended ) {
            $mail = new Warecorp_Mail_Template('template_key', 'USERS_FRIEND_INVITE');
            $mail->setSender($objSender);
            $mail->addRecipient($objRecipient);
            $mail->sendToPMB(true);
            $mail->sendToEmail(true);
            $mail->addParam('message', $Message);
            $mail->setCallBackData(array(
                   'controller' => 'Registration',
                   'action'     => 'addRequestAction',
                   'params'     => array('request' => $objRequest)            
            ));
            $mail->send();
        }
    }
    
    public function sendRestorePassword( $password )
    {
        /* SOAP: MailSrv */
        $msrvRecipients = new Warecorp_SOAP_Type_Recipients();
        $msrvSended = false;
                
        /* SOAP: MailSrv */
        if ( Warecorp::isMailServerUsed() && Warecorp::isMailServerTemplateRegistered( 'USER_REMIND_PASS' ) ) {
                       
            /* SOAP: MailSrv */       
            try { $client = Warecorp::getMailServerClient(); }
            catch ( Exception $e ) { $client = null; }   
            
            if ( $client ) {   
                $objRecipient = $this;
                $recipient = new Warecorp_SOAP_Type_Recipient();
                $recipient->setEmail( $objRecipient->getEmail() );
                $recipient->setName( $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : null );
                $recipient->setLocale( null );
                $recipient->addParam('CCFID', Warecorp::getCCFID($objRecipient));
                $recipient->addParam( 'recipient_full_name', $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : '' );
                $recipient->addParam( 'recipient_login', $objRecipient->getLogin() );                                
                $recipient->addParam( 'recipient_password', $password );
                $recipient->addParam( 'url_login', BASE_URL.'/'.LOCALE.'/users/login/' );
                $recipient->addParam( 'url_settings', $objRecipient->getUserPath('settings') );
                $recipient->addParam( 'SITE_LINK_UNSUBSCRIBE', $objRecipient->getUserPath('settings') );
                $msrvRecipients->addRecipient($recipient);
                
                try { 
                    $campaignUID = $client->createCampaign();                        
                    $request = $client->setSender($campaignUID, 'messages-noreply@bounce.'.DOMAIN_FOR_EMAIL, 'Login Assistance' );
                    $request = $client->setTemplate($campaignUID, 'USER_REMIND_PASS', HTTP_CONTEXT); /* USER_REMIND_PASS */
                                        
                    /* add params */
                    $params = new Warecorp_SOAP_Type_Params();
                    $params->loadDefaultCampaignParams();
                    $request = $client->addParams($campaignUID, $params);
                    
                    $request = $client->addRecipients($campaignUID, $msrvRecipients);
                    $request = $client->startCampaign($campaignUID);
                    
                    $msrvSended = true;
                } catch ( Exception $e ) { $msrvSended = false; }
            }
        }

        /**
         * TODO : MAILSRV_REMOVE : Remove it when transfer to mailsrv will be done
         * if emails haven't been sended by SOAP: MailSrv, send it 
         */
        if ( !$msrvSended ) {
            $objSender = new Warecorp_User();
            $objSender->email = 'Zanbygroups@zanby.com';
            $objSender->login = 'Zanby Groups';
            $mail = new Warecorp_Mail_Template('template_key', 'USER_REMIND_PASS');
            $mail->setSender($objSender);
            $mail->addRecipient($this);
            $mail->addParam('sender', $objSender);
            $mail->addParam('recipient', $this);
            $mail->addParam('password', $password);
            $mail->send();
        }
    }
    
    public function sendRestorePasswordByUrl()
    {
        /* SOAP: MailSrv */
        $msrvRecipients = new Warecorp_SOAP_Type_Recipients();
        $msrvSended = false;
                
        /* SOAP: MailSrv */
        if ( Warecorp::isMailServerUsed() && Warecorp::isMailServerTemplateRegistered( 'USER_RESTORE_PASS_BY_URL' ) ) {
                       
            /* SOAP: MailSrv */       
            try { $client = Warecorp::getMailServerClient(); }
            catch ( Exception $e ) { $client = null; }   
            
            if ( $client ) {   
                $objRecipient = $this;
                $recipient = new Warecorp_SOAP_Type_Recipient();
                $recipient->setEmail( $objRecipient->getEmail() );
                $recipient->setName( $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : null );
                $recipient->setLocale( null );
                $recipient->addParam('CCFID', Warecorp::getCCFID($objRecipient));
                $recipient->addParam( 'recipient_full_name', $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : '' );
                $recipient->addParam( 'recipient_login', $objRecipient->getId() ? $objRecipient->getLogin() : '' );                                
                $recipient->addParam( 'url_restore_password', BASE_URL.'/'.LOCALE.'/users/restorePassword/login/'.$objRecipient->getLogin().'/code/'.$objRecipient->getRestorePasswordCode().'/' );
                $recipient->addParam( 'SITE_LINK_UNSUBSCRIBE', $objRecipient->getUserPath('settings') );
                $msrvRecipients->addRecipient($recipient);
                
                try { 
                    $campaignUID = $client->createCampaign();                        
                    $request = $client->setSender($campaignUID, 'messages-noreply@bounce.'.DOMAIN_FOR_EMAIL, 'Login Assistance' );
                    $request = $client->setTemplate($campaignUID, 'USER_RESTORE_PASS_BY_URL', HTTP_CONTEXT); /* USER_RESTORE_PASS_BY_URL */
                                        
                    /* add params */
                    $params = new Warecorp_SOAP_Type_Params();
                    $params->loadDefaultCampaignParams();
                    $request = $client->addParams($campaignUID, $params);
                    
                    $request = $client->addRecipients($campaignUID, $msrvRecipients);
                    $request = $client->startCampaign($campaignUID);
                    
                    $msrvSended = true;
                } catch ( Exception $e ) { $msrvSended = false; }
            }
        }

        /**
         * TODO : MAILSRV_REMOVE : Remove it when transfer to mailsrv will be done
         * if emails haven't been sended by SOAP: MailSrv, send it 
         */
        if ( !$msrvSended ) {
            $objSender = new Warecorp_User();
            $mail = new Warecorp_Mail_Template('template_key', 'USER_RESTORE_PASS_BY_URL');
            $mail->setSender($objSender);
            $mail->addRecipient($this);
            $mail->addParam('sender', $objSender);
            $mail->addParam('recipient', $this);
            $mail->send();
        }
    }

    public function sendRejectedByAdmin()
    {
        /* SOAP: MailSrv */
        $msrvRecipients = new Warecorp_SOAP_Type_Recipients();
        $msrvSended = false;
                
        /* SOAP: MailSrv */
        if ( Warecorp::isMailServerUsed() && Warecorp::isMailServerTemplateRegistered( 'USER_REJECTED_BY_ADMIN' ) ) {
                       
            /* SOAP: MailSrv */       
            try { $client = Warecorp::getMailServerClient(); }
            catch ( Exception $e ) { $client = null; }   
            
            if ( $client ) {   
                $objRecipient = $this;
                $recipient = new Warecorp_SOAP_Type_Recipient();
                $recipient->setEmail( $objRecipient->getEmail() );
                $recipient->setName( $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : null );
                $recipient->setLocale( null );
                $recipient->addParam('CCFID', Warecorp::getCCFID($objRecipient));
                $recipient->addParam( 'recipient_full_name', $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : '' );
                $recipient->addParam( 'url_contactus',BASE_URL.'/'.LOCALE.'/info/contactus/' );                                
                $recipient->addParam( 'SITE_LINK_UNSUBSCRIBE', $objRecipient->getUserPath('settings') );
                $msrvRecipients->addRecipient($recipient);
                
                try { 
                    $campaignUID = $client->createCampaign();                        
                    $request = $client->setSender($campaignUID, 'messages-noreply@bounce.'.DOMAIN_FOR_EMAIL, SITE_NAME_AS_STRING.' Registration' );
                    $request = $client->setTemplate($campaignUID, 'USER_REJECTED_BY_ADMIN', HTTP_CONTEXT); /* USER_REJECTED_BY_ADMIN */
                                        
                    /* add params */
                    $params = new Warecorp_SOAP_Type_Params();
                    $params->loadDefaultCampaignParams();
                    $request = $client->addParams($campaignUID, $params);
                    
                    $request = $client->addRecipients($campaignUID, $msrvRecipients);
                    $request = $client->startCampaign($campaignUID);
                    
                    $msrvSended = true;
                } catch ( Exception $e ) { $msrvSended = false; }
            }
        }

        /**
         * TODO : MAILSRV_REMOVE : Remove it when transfer to mailsrv will be done
         * if emails haven't been sended by SOAP: MailSrv, send it 
         */
        if ( !$msrvSended ) {
            $mail = new Warecorp_Mail_Template('template_key', 'USER_REJECTED_BY_ADMIN');
            $mail->setSender(new Warecorp_User());
            $mail->addRecipient($this);
            $mail->send();
        }
    }

    public function sendApprovedByAdmin()
    {
        /* SOAP: MailSrv */
        $msrvRecipients = new Warecorp_SOAP_Type_Recipients();
        $msrvSended = false;
                
        /* SOAP: MailSrv */
        if ( Warecorp::isMailServerUsed() && Warecorp::isMailServerTemplateRegistered( 'USER_APPROVED_BY_ADMIN' ) ) {
                       
            /* SOAP: MailSrv */       
            try { $client = Warecorp::getMailServerClient(); }
            catch ( Exception $e ) { $client = null; }   
            
            if ( $client ) {   
                $objRecipient = $this;
                $recipient = new Warecorp_SOAP_Type_Recipient();
                $recipient->setEmail( $objRecipient->getEmail() );
                $recipient->setName( $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : null );
                $recipient->setLocale( null );
                $recipient->addParam('CCFID', Warecorp::getCCFID($objRecipient));
                $recipient->addParam( 'recipient_full_name', $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : '' );
                $recipient->addParam( 'SITE_LINK_UNSUBSCRIBE', $objRecipient->getUserPath('settings') );
                $msrvRecipients->addRecipient($recipient);
                
                try { 
                    $campaignUID = $client->createCampaign();                        
                    $request = $client->setSender($campaignUID, 'messages-noreply@bounce.'.DOMAIN_FOR_EMAIL, SITE_NAME_AS_STRING.' Registration' );
                    $request = $client->setTemplate($campaignUID, 'USER_APPROVED_BY_ADMIN', HTTP_CONTEXT); /* USER_APPROVED_BY_ADMIN */
                                        
                    /* add params */
                    $params = new Warecorp_SOAP_Type_Params();
                    $params->loadDefaultCampaignParams();
                    $request = $client->addParams($campaignUID, $params);
                    
                    $request = $client->addRecipients($campaignUID, $msrvRecipients);
                    $request = $client->startCampaign($campaignUID);
                    
                    $msrvSended = true;
                } catch ( Exception $e ) { $msrvSended = false; }
            }
        }

        /**
         * TODO : MAILSRV_REMOVE : Remove it when transfer to mailsrv will be done
         * if emails haven't been sended by SOAP: MailSrv, send it 
         */
        if ( !$msrvSended ) {
            $mail = new Warecorp_Mail_Template('template_key', 'USER_APPROVED_BY_ADMIN');
            $mail->setSender(new Warecorp_User());
            $mail->addRecipient($this);
            $mail->send();
        }
    }
    
    public function sendRegistrationNotification( $ConfirmationLink )
    {
        /* SOAP: MailSrv */
        $msrvRecipients = new Warecorp_SOAP_Type_Recipients();
        $msrvSended = false;
                
        /* SOAP: MailSrv */
        if ( Warecorp::isMailServerUsed() && Warecorp::isMailServerTemplateRegistered( 'USER_REGISTER' ) ) {
                       
            /* SOAP: MailSrv */       
            try { $client = Warecorp::getMailServerClient(); }
            catch ( Exception $e ) { $client = null; }   
            
            if ( $client ) {   
                $objRecipient = $this;
                $recipient = new Warecorp_SOAP_Type_Recipient();
                $recipient->setEmail( $objRecipient->getEmail() );
                $recipient->setName( $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : null );
                $recipient->setLocale( null );
                $recipient->addParam('CCFID', Warecorp::getCCFID($objRecipient));
                $recipient->addParam( 'recipient_full_name', $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : '' );
                $recipient->addParam( 'url_confirmation', $ConfirmationLink );
                $recipient->addParam( 'SITE_LINK_UNSUBSCRIBE', $objRecipient->getUserPath('settings') );
                $msrvRecipients->addRecipient($recipient);
                
                try { 
                    $campaignUID = $client->createCampaign();                        
                    $request = $client->setSender($campaignUID, 'messages-noreply@bounce.'.DOMAIN_FOR_EMAIL, SITE_NAME_AS_STRING.' Registration' );
                    $request = $client->setTemplate($campaignUID, 'USER_REGISTER', HTTP_CONTEXT); /* USER_REGISTER */

                    /* for ZCCF only, add BCC */
                    if (Warecorp::checkHttpContext('zccf')) {
                        if ( APPLICATION_ENV == 'production' ) $client->addHeader($campaignUID , 'BCC', 'admin@communityforums.org');
                        else $client->addHeader($campaignUID , 'BCC', 'admin@'.DOMAIN_FOR_EMAIL);
                    }

                    /* add params */
                    $params = new Warecorp_SOAP_Type_Params();
                    $params->loadDefaultCampaignParams();
                    $request = $client->addParams($campaignUID, $params);
                    
                    $request = $client->addRecipients($campaignUID, $msrvRecipients);
                    $request = $client->startCampaign($campaignUID);
                    
                    $msrvSended = true;
                } catch ( Exception $e ) { $msrvSended = false; }
            }
        }

        /**
         * TODO : MAILSRV_REMOVE : Remove it when transfer to mailsrv will be done
         * if emails haven't been sended by SOAP: MailSrv, send it 
         */
        if ( !$msrvSended ) {
            $mail = new Warecorp_Mail_Template('template_key', 'USER_REGISTER');
            $mail->setSender(new Warecorp_User());
            $mail->addRecipient($this);
            $mail->addParam('ConfirmationLink', $ConfirmationLink);
            $mail->send();
        }
    }
    
    public function sendRegistrationFBUserNotification( $password, $ConfirmationLink )
    {
        /* SOAP: MailSrv */
        $msrvRecipients = new Warecorp_SOAP_Type_Recipients();
        $msrvSended = false;
                
        /* SOAP: MailSrv */
        if ( Warecorp::isMailServerUsed() && Warecorp::isMailServerTemplateRegistered( 'USER_FB_REGISTER' ) ) {
                       
            /* SOAP: MailSrv */       
            try { $client = Warecorp::getMailServerClient(); }
            catch ( Exception $e ) { $client = null; }   
            
            if ( $client ) {   
                $objRecipient = $this;
                $recipient = new Warecorp_SOAP_Type_Recipient();
                $recipient->setEmail( $objRecipient->getEmail() );
                $recipient->setName( $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : null );
                $recipient->setLocale( null );
                $recipient->addParam('CCFID', Warecorp::getCCFID($objRecipient));
                $recipient->addParam( 'recipient_full_name', $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : '' );
                $recipient->addParam( 'recipient_login', $objRecipient->getLogin() );
                $recipient->addParam( 'recipient_password', $password );
                $recipient->addParam( 'url_confirmation', $ConfirmationLink );
                $recipient->addParam( 'url_login', BASE_URL.'/'.LOCALE.'/users/login/' );
                $recipient->addParam( 'SITE_LINK_UNSUBSCRIBE', $objRecipient->getUserPath('settings') );
                $msrvRecipients->addRecipient($recipient);
                
                try { 
                    $campaignUID = $client->createCampaign();                        
                    $request = $client->setSender($campaignUID, 'messages-noreply@bounce.'.DOMAIN_FOR_EMAIL, SITE_NAME_AS_STRING.' Registration' );
                    $request = $client->setTemplate($campaignUID, 'USER_FB_REGISTER', HTTP_CONTEXT); /* USER_FB_REGISTER */

                    /* for ZCCF only, add BCC */
                    if ( Warecorp::checkHttpContext('zccf')) {
                        if ( APPLICATION_ENV == 'production' ) $client->addHeader($campaignUID , 'BCC', 'admin@communityforums.org');
                        else $client->addHeader($campaignUID , 'BCC', 'admin@'.DOMAIN_FOR_EMAIL);
                    }

                    /* add params */
                    $params = new Warecorp_SOAP_Type_Params();
                    $params->loadDefaultCampaignParams();
                    $request = $client->addParams($campaignUID, $params);
                    
                    $request = $client->addRecipients($campaignUID, $msrvRecipients);
                    $request = $client->startCampaign($campaignUID);
                    
                    $msrvSended = true;
                } catch ( Exception $e ) { $msrvSended = false; }
            }
        }

        /**
         * TODO : MAILSRV_REMOVE : Remove it when transfer to mailsrv will be done
         * if emails haven't been sended by SOAP: MailSrv, send it 
         */
        if ( !$msrvSended ) {
            $mail = new Warecorp_Mail_Template('template_key', 'USER_FB_REGISTER');
            $mail->setSender(new Warecorp_User());
            $mail->addRecipient($this);
            $mail->addParam('password', $password);
            $mail->addParam('ConfirmationLink', $ConfirmationLink);
            $mail->send();
        }
    }
    
    public function sendRegistrationNotificationToAdmin()
    {
        /* SOAP: MailSrv */
        $msrvRecipients = new Warecorp_SOAP_Type_Recipients();
        $msrvSended = false;
                
        /* SOAP: MailSrv */
        if ( Warecorp::isMailServerUsed() && Warecorp::isMailServerTemplateRegistered( 'USER_REGISTER_TO_ADMIN' ) ) {
                       
            /* SOAP: MailSrv */       
            try { $client = Warecorp::getMailServerClient(); }
            catch ( Exception $e ) { $client = null; }   
            
            if ( $client ) {   
                $objRecipient = new Warecorp_User('login', WHO_APPROVE_USER_ACCOUNT);
                $recipient = new Warecorp_SOAP_Type_Recipient();
                $recipient->setEmail( $objRecipient->getEmail() );
                $recipient->setName( $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : null );
                $recipient->setLocale( null );
                $recipient->addParam('CCFID', Warecorp::getCCFID($objRecipient));
                $recipient->addParam( 'recipient_full_name', $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : '' );
                $recipient->addParam( 'user_login', $this->getLogin() );
                $recipient->addParam( 'url_approve', BASE_URL.'/'.LOCALE.'/registration/index/code/'.$this->getRegisterCode().'/act/approve/' );
                $recipient->addParam( 'url_reject', BASE_URL.'/'.LOCALE.'/registration/index/code/'.$this->getRegisterCode().'/act/reject/' );
                $recipient->addParam( 'SITE_LINK_UNSUBSCRIBE', $objRecipient->getUserPath('settings') );
                $msrvRecipients->addRecipient($recipient);
                
                try { 
                    $campaignUID = $client->createCampaign();                        
                    $request = $client->setSender($campaignUID, 'messages-noreply@bounce.'.DOMAIN_FOR_EMAIL, SITE_NAME_AS_STRING.' Registration' );
                    $request = $client->setTemplate($campaignUID, 'USER_REGISTER_TO_ADMIN', HTTP_CONTEXT); /* USER_REGISTER_TO_ADMIN */
                                        
                    /* add params */
                    $params = new Warecorp_SOAP_Type_Params();
                    $params->loadDefaultCampaignParams();
                    $request = $client->addParams($campaignUID, $params);
                    
                    $request = $client->addRecipients($campaignUID, $msrvRecipients);
                    $request = $client->startCampaign($campaignUID);
                    
                    $msrvSended = true;
                } catch ( Exception $e ) { $msrvSended = false; }
            }
        }

        /**
         * TODO : MAILSRV_REMOVE : Remove it when transfer to mailsrv will be done
         * if emails haven't been sended by SOAP: MailSrv, send it 
         */
        if ( !$msrvSended ) {
            $approver = new Warecorp_User('login', WHO_APPROVE_USER_ACCOUNT);            
            $mail = new Warecorp_Mail_Template('template_key', 'USER_REGISTER_TO_ADMIN');
            $mail->setSender(new Warecorp_User());
            $mail->addRecipient($approver);
            $mail->addParam('newAccount', $this);
            $mail->send();
        }
    }
    
    public function sendUserImportedNotification()
    {
        /* SOAP: MailSrv */
        $msrvRecipients = new Warecorp_SOAP_Type_Recipients();
        $msrvSended = false;
                
        /* SOAP: MailSrv */
        if ( Warecorp::isMailServerUsed() && Warecorp::isMailServerTemplateRegistered( 'USER_IMPORT_WELCOME' ) ) {
                       
            /* SOAP: MailSrv */       
            try { $client = Warecorp::getMailServerClient(); }
            catch ( Exception $e ) { $client = null; }   
            
            if ( $client ) {   
                $objRecipient = $this;
                $recipient = new Warecorp_SOAP_Type_Recipient();
                $recipient->setEmail( $objRecipient->getEmail() );
                $recipient->setName( $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : null );
                $recipient->setLocale( null );
                $recipient->addParam('CCFID', Warecorp::getCCFID($objRecipient));
                $recipient->addParam( 'recipient_full_name', $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : '' );
                $recipient->addParam( 'SITE_LINK_UNSUBSCRIBE', $objRecipient->getUserPath('settings') );
                $msrvRecipients->addRecipient($recipient);
                
                try { 
                    $campaignUID = $client->createCampaign();                        
                    $request = $client->setSender($campaignUID, 'messages-noreply@bounce.'.DOMAIN_FOR_EMAIL, SITE_NAME_AS_STRING.' Registration' );
                    $request = $client->setTemplate($campaignUID, 'USER_IMPORT_WELCOME', HTTP_CONTEXT); /* USER_IMPORT_WELCOME */
                                        
                    /* add params */
                    $params = new Warecorp_SOAP_Type_Params();
                    $params->loadDefaultCampaignParams();
                    $request = $client->addParams($campaignUID, $params);
                    
                    $request = $client->addRecipients($campaignUID, $msrvRecipients);
                    $request = $client->startCampaign($campaignUID);
                    
                    $msrvSended = true;
                } catch ( Exception $e ) { $msrvSended = false; }
            }
        }

        /**
         * TODO : MAILSRV_REMOVE : Remove it when transfer to mailsrv will be done
         * if emails haven't been sended by SOAP: MailSrv, send it 
         */
        if ( !$msrvSended ) {
            //  Send message
            $mail = new Warecorp_Mail_Template('template_key', 'USER_IMPORT_WELCOME');
            $mail->setSender(new Warecorp_User());
            $mail->addRecipient($this);
            $mail->send();
        }
    }

    /*
      +-----------------------------------
      |
      | END: Function that used to send email to MailSrv Service
      |
      +-----------------------------------
    */        
    
}
