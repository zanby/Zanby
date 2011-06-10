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
 * Zanby group class.
 *
 * @package    Warecorp_Group_Standard
 * @copyright  Copyright (c) 2007
 *
 */

require_once(WARECORP_DIR.'DiscussionServer/iDiscussionGroup.php');

class BaseWarecorp_Group_Standard extends Warecorp_Group_Base implements Warecorp_DiscussionServer_iDiscussionGroup, Warecorp_Global_iSearchFields 
{
    protected $path;
    protected $latitude;
    protected $longitude;
    protected $joinMode;
    protected $joinCode;
    protected $City;
    protected $State;
    protected $Country;
    protected $Zip;
    protected $Category;
    protected $Avatar;
    protected $Host;
    protected $GroupPath;
    protected $Privileges;
    protected $PublishSettings;
    protected $usePathParamsMode = false;
    protected $forceRedefine = false;
    protected $isUsedNamedPath = false;

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
    public function getPrivileges()
    {
        if ( $this->Privileges === null ) {
           $this->Privileges = new Warecorp_Group_Privileges($this->id);
        }
        return $this->Privileges;
    }
    public function setPath($newVal)
    {
    	$this->path = $newVal;
    	return $this;
    }
    public function getPath()
    {
    	return $this->path;
    }
    public function setHeadline($newVal)
    {
    	throw new Warecorp_Exception('setHeadline method is depreated');
    }
    public function getHeadline()
    {
    	return $this->getName();
    }
    public function setLatitude($newVal)
    {
    	$this->latitude = $newVal;
    	return $this;
    }
    public function getLatitude()
    {
    	return $this->latitude;
    }
    public function setLongitude($newVal)
    {
    	$this->longitude = $newVal;
    	return $this;
    }
    public function getLongitude()
    {
    	return $this->longitude;
    }
    public function setJoinMode($newVal)
    {
        $this->joinMode = $newVal;
        return $this;
    }
    public function getJoinMode()
    {
        return $this->joinMode;
    }
    public function setJoinCode($newVal)
    {
        $this->joinCode = $newVal;
        return $this;
    }
    public function getJoinCode()
    {
        return $this->joinCode;
    }
    /**
     * can user join group
     *
     * @param int|Warecorp_User $user
     * @return array
     * @author Vitaly Targonsky
     */
    public function getJoinAttempt($user)
    {

        if ($user instanceof Warecorp_User) {
            $userId = $user->getId();
        } else {
            $userId = $user;
        }

        $query = $this->_db->select();
        $query->from(array('zgja' => 'zanby_groups__join_attempts'), array('attempts', 'seconds' => new Zend_Db_Expr('UNIX_TIMESTAMP() - UNIX_TIMESTAMP(zgja.last_attempt_date)')))
              ->where('group_id =?', $this->getId())
              ->where('user_id =?', $userId);

        return $this->_db->fetchRow($query);
    }
    /**
     * increase attempts number of join with code
     *
     * @return unknown
     * @author Vitaly Targonsky
     */
    public function saveJoinAttempt($user)
    {
        if ($user instanceof Warecorp_User) {
            $userId = $user->getId();
        } else {
            $userId = $user;
        }

        $attempt = $this->getJoinAttempt($userId);

        if ($attempt) {
            $this->_db->update('zanby_groups__join_attempts',
                array('attempts'=>new Zend_Db_Expr('attempts + 1'), 'last_attempt_date'=>new Zend_Db_Expr('NOW()')),
                $this->_db->quoteInto('group_id = ? AND ', $this->getId()).
                $this->_db->quoteInto('user_id = ?', $userId)
            );
        } else {
            $this->_db->insert('zanby_groups__join_attempts',
                array('group_id' => $this->id,
                      'user_id'  => $userId,
                      'attempts'=>'1',
                      'last_attempt_date'=>new Zend_Db_Expr('NOW()'),
                     )
            );
        }
    }
    /**
     * reset attempts number of join with code
     *
     * @return unknown
     * @author Vitaly Targonsky
     */
    public function resetJoinAttempt($user)
    {
        if ($user instanceof Warecorp_User) {
            $userId = $user->getId();
        } else {
            $userId = $user;
        }
        $this->_db->delete('zanby_groups__join_attempts',
            $this->_db->quoteInto('group_id = ? AND ', $this->getId()).
            $this->_db->quoteInto('user_id = ?', $userId));

    }

    /**
	 * Возвращает Zip для группы
	 * @return Warecorp_Location_Zipcode
	 * @author Artem Sukharev
	 */
    public function getZip()
    {
        if ( $this->Zip === null ) {
            $this->Zip = Warecorp_Location_Zipcode::createByZip($this->getZipcode());
        }
        return $this->Zip;
    }

    /**
	 * Возвращает City для группы
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
	 * Возвращает State для группы
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
	 * Возвращает Country для группы
	 * @return Warecorp_Location_Country
	 * @author Artem Sukharev
	 */
    public function getCountry()
    {
        if ( $this->Country === null ) {
            $this->Country = $this->getState()->getCountry();
        }
        return $this->Country;
    }
    
    public function getGlobalPath($action = null, $withslash = true, $https = false)
    {
         return $this->getGroupPath( $action, $withslash, $https );
    }

    /**
	 * Возвращает http адрес для группы
	 * @return string
	 * @author Artem Sukharev
	 */
    public function getGroupPath( $action = null, $withslash = true, $https = false)
    {
        if ($this->forceRedefine) {
            $this->GroupPath = null;
            $this->forceRedefine = false;
        }
        if ( $this->GroupPath === null ) {
	        if ( $this->getId() !== null ) {
	            if (!$this->usePathParamsMode) {
                    //$this->GroupPath = 'http://'.$this->path.'.groups.'.BASE_HTTP_HOST.'/'.LOCALE.'/';
                    $this->GroupPath = 'http://'.BASE_HTTP_HOST.'/'.LOCALE.'/group/'.mb_strtolower($this->path, 'utf-8').'/';
                    $this->isUsedNamedPath = true;
                } else {
                    $this->GroupPath = 'http://'.BASE_HTTP_HOST.'/'.LOCALE.'/groups/';
                    $this->isUsedNamedPath = false;                
                }                
	        }
	        if ( $this->GroupPath === null ) {
	            $this->GroupPath = 'http://'.BASE_HTTP_HOST.'/'.LOCALE.'/groups/';
	        }
        }

        if ($https) {
            $this->GroupPath = str_replace('http://', 'https://', $this->GroupPath);
        } else {
            $this->GroupPath = str_replace('https://', 'http://', $this->GroupPath);
        }
        
        if ( $action !== null ) {
            if ( $this->isUsedNamedPath == false ) {
			    return ($withslash) ? $this->GroupPath.$action.'/groupid/'.$this->getId().'/' : $this->GroupPath.$action.'/groupid/'.$this->getId();
            } else {
			    return ($withslash) ? $this->GroupPath.$action.'/' : $this->GroupPath.$action;
            }
        } else {
		    return $this->GroupPath;
        }
    }

    /**
     * Alias to getGroupPath
     * @param action string - URI action for user
     * @param withslash boolean - need add slash to end
     * @return string - http address
     * @return string - http address
     */
    public function getContextPath( $action = null, $withslash = true, $https = false )
    {
        return $this->getGroupPath($action, $withslash, $https);
    }
    
    /**
	 * Возвращает аватар для группы
	 * @return Warecorp_Group_Avatar
	 * @author Artem Sukharev
	 */
    public function getAvatar()
    {
        if ( $this->Avatar === null ) {
	        $select = $this->_db->select();
	        $select->from('zanby_groups__avatars', '*')
	               ->where('group_id = ?', $this->getId())
	               ->where('bydefault = ?', 1);
	        $res = $this->_db->fetchRow($select);
	        $res = ($res === false)?0:$res;
	        $this->Avatar = new Warecorp_Group_Avatar($res);
	        $this->Avatar->setGroupId($this->getId());
	        $this->Avatar->setByDefault(1);
        }
        return $this->Avatar;
    }

    /**
	 * Возвращает хоста группы
	 * @return Warecorp_User
	 * @author Artem Sukharev
	 */
    function getHost()
    {
        if ( $this->Host === null ) {
	        $select = $this->_db->select();
	        $select->from('zanby_groups__members','user_id')
	               ->where('group_id = ?', $this->getId())
	               ->where('status = ?', 'host');
	        $res = $this->_db->fetchOne($select);
	        $this->Host = new Warecorp_User('id', $res);
        }
        return $this->Host;
    }

    /**
	 * Возвращает Category для группы
	 * @return Warecorp_Group_Category
	 * @author Artem Sukharev
	 */
    public function getCategory()
    {
        if ( $this->Category === null ) {
            $this->Category = new Warecorp_Group_Category($this->getCategoryId());
        }
        return $this->Category;
    }

    /**
     * return Warecorp_Group_Standard_FamilyGroup_List object
     * @return Warecorp_Group_Standard_FamilyGroup_List
     * @author Artem Sukharev
     */
    public function getFamilyGroups()
    {
    	return new Warecorp_Group_Standard_FamilyGroup_List($this->getId());
    }

    /**
     * return Pablish Settings object for group
     * @return Warecorp_Group_Publish
     * @author Artem Sukharev
     */
    public function getPublishSettings(){
    	if ( $this->PublishSettings === null ) {
            $this->PublishSettings = new Warecorp_Group_Publish("group_id", $this->id);
    	}
        return $this->PublishSettings;
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
     * return Gallery List object
     * @return Warecorp_Video_Gallery_List_Abstract
     * @author Yury Zolotarsky
     */
    public function getVideoGalleries()
    {
        return Warecorp_Video_Gallery_List_Factory::load($this);
    }
    
    /**
     * Constructor
     * @return void
     * @author Artem Sukharev
     */
    public function __construct()
    {
        parent::__construct();

        $this->addField('group_path', 'path');
        //$this->addField('headline');
        $this->addField('latitude');
        $this->addField('longitude');
        $this->addField('join_mode', 'joinMode');
        $this->addField('join_code', 'joinCode');
        
    }

    /**
     * Save Group
     * @author Artem Sukharev
     */
    public function save()
    {
    	if ( $this->getId() ) {
            parent::save();
            $this->updateMainDiscussion();
            //create webbadge
            Warecorp_Group_WebBadges_Item::getNamedBadge($this->getId(), true, $this->getName());
        } else {
            $this->setCreateDate(new Zend_Db_Expr('NOW()'));
            parent::save();
            $this->createMainDiscussion();
        }
    }

    /**
     * Return Group email
     * @return string
     * @author Artem Sukharev
     */
    public function getGroupEmail($whithDomain = true)
    {
    	if ( $whithDomain ) {
            return $this->path.'@'.DOMAIN_FOR_GROUP_EMAIL;
    	} else {
    		return $this->path;
    	}
    }

    /**
     * Check Join Code for group
     * @param string $code
     * @return bool
     * @author Artem Sukharev
     */
    public function checkJoinCode($code)
    {
        $select = $this->_db->select();
        $select->from('zanby_groups__items', 'id')
            ->where('id = ?', $this->id)
            ->where('BINARY join_code = ?', $code);
        $res = $this->_db->fetchCol($select);
        return (bool) $res;
    }


    /**
     * ********************************************
     * STATIC METHODS
     * ********************************************
     */

    /**
     * Проверяет, существует ли группа с указанными параметрами
     * @param string $key - ключ, для поиска группы, возможные варианты id|name|group_path
     * @param variant $value - значение ключа
     * @param mixed $exclude - значения ключа, которые надо исключить
     * @return boolean
     * @author Artem Sukharev
     */
    public static function isGroupExists($key, $value, $exclude = null)
    {
        $db = Zend_Registry::get("DB");
        if ( !in_array($key, array('id','name','group_path')) ) return false;

        $select = $db->select()
            ->from('zanby_groups__items', array('count' => new Zend_Db_Expr('count(id)')))
            ->where($key.' = ?', $value);
        if ( $exclude !== null ) {
            $select->where($key.' NOT IN (?)', $exclude);
        }
        $res = $db->fetchOne($select);
        return (boolean) $res;
    }

    /*
     +-----------------------------------
     |
     | iDiscussionServer Interface
     |
     +-----------------------------------
    */

    /**
	 * return Warecorp_DiscussionServer_iAuthor object
	 * @return object Warecorp_DiscussionServer_iModerator
	 * @author Artem Sukharev
	 */
    public function getDiscussionGroupHost()
    {
        return $this->getHost();
    }
    /**
	 * create default discussion for group
	 * @author Artem Sukharev
	 */
    public function createMainDiscussion()
    {
        $authorId = ($this->getHost()->getId()) ? $this->getHost()->getId() : 1;
        $email = $this->getGroupEmail(false);

        $discussion = new Warecorp_DiscussionServer_Discussion();
        $discussion->setGroupId($this->id);
        $discussion->setAuthorId($authorId);
        $discussion->setTitle('Main');
        $discussion->setEmail($email);
        $discussion->setDescription($this->name . ' Main Discussion');
        $discussion->setMain(1);
        $discussion->save();
    }
    /**
	 * update default discussion for group
	 * @author Artem Sukharev
	 */
    public function updateMainDiscussion()
    {
        $email = $this->getGroupEmail(false);

        $glist = new Warecorp_DiscussionServer_DiscussionList();
        $discussion = $glist->findMainByGroupId($this->id);
        if ( $discussion ) {
            $discussion->setEmail($email);
            $discussion->updateEmail();
        }
    }
    
    /**
     * delete request to join
     * @param object $sender - Warecorp_User or Warecorp_Group_Standard object
     * @return boolean
     * @author Artem Sukharev
     */
    public function deleteRequestRelation($sender)
    {
        if ( $sender instanceof Warecorp_User ) {
            $senderId = $sender->getId();
            $senderType = 'user';
        } elseif ( $sender instanceof Warecorp_Group_Standard ) {
            $senderId = $sender->getId();
            $senderType = 'group';
        } else {
            throw new Zend_Exception('Incorrect sender');
        }
        $query = $this->_db->select();
        $query->from(array('zgjr' => 'zanby_groups__join_requests'), 'zgjr.id');
        $query->where('zgjr.sender_type = ?', $senderType);
        $query->where('zgjr.sender_id = ?', $senderId);
        $query->where('zgjr.recipient_id = ?', $this->getId());
        $res = $this->_db->fetchOne($query);
        if ( $res ) {
            $this->_db->delete('zanby_requests__relations',
                               $this->_db->quoteInto('group_request_id = ?', $res));            
            $this->_db->delete('zanby_groups__join_requests',
                               $this->_db->quoteInto('id = ?', $res));            
        }
    }
    
    /**
     * return message related with request to join
     * @param object $sender - Warecorp_User or Warecorp_Group_Standard object
     * @return obj Warecorp_Message_Standard
     * @author Artem Sukharev
     */
    public function getRequestRelation($sender)
    {
        //if ( !($recipient instanceof Warecorp_Group_Standard) ) throw new Zend_Exception('Incorrect recipient');

        if ( $sender instanceof Warecorp_User ) {
            $senderId = $sender->getId();
            $senderType = 'user';
        } elseif ( $sender instanceof Warecorp_Group_Standard ) {
            $senderId = $sender->getId();
            $senderType = 'group';
        } else {
            throw new Zend_Exception('Incorrect sender');
        }
        $query = $this->_db->select();
        $query->from(array('zgjr' => 'zanby_groups__join_requests'), array('zrr.message_id', 'zgjr.request_date'));
        $query->join(array('zrr' => 'zanby_requests__relations'), 'zgjr.id = zrr.group_request_id');
        $query->where('zgjr.sender_type = ?', $senderType);
        $query->where('zgjr.sender_id = ?', $senderId);
        $query->where('zgjr.recipient_id = ?', $this->getId());
        $res = $this->_db->fetchRow($query);       
        if ( $res ) {
            $message = new Warecorp_Message_Standard($res['message_id']);
            $message->requestDate = $res['request_date'];
        } 
        else {
            $message = new Warecorp_Message_Standard();
            $message->requestDate = null;
        }
        return $message;
    }
    
    /**
     * save join request for user to group, group to family
     * @param int $messageId - id of message from zanby_user__messages (message sent to host of group)
     * @param object $sender - Warecorp_User or Warecorp_Group_Standard object
     * @return boolean
     * @author Artem Sukharev
     */
    public function setRequestRelation($messageId, $sender)
    {
        if ( $sender instanceof Warecorp_User ) {
            $senderId = $sender->getId();
            $senderType = 'user';
        } elseif ( $sender instanceof Warecorp_Group_Standard ) {
            $senderId = $sender->getId();
            $senderType = 'group';
        } else {
            throw new Zend_Exception('Incorrect sender');
        }
        $this->_db->insert('zanby_groups__join_requests',
            array(
                'sender_type' => $senderType,
                'sender_id' => $senderId,
                'recipient_id' => $this->id,
                'request_date' => new Zend_Db_Expr('NOW()')
            )
        );
        $requestId = $this->_db->lastInsertId();
        $this->_db->insert('zanby_requests__relations',
                array(
                    'message_id' => $messageId,
                    'group_request_id'  => $requestId
                )
        );    	
    }
    
     /**
     * return path to the named webbadge for this group (initial generating in save method)
     * 
     * @author Eugene Halauniou
     */

	public function getNamedBadge(){
		return Warecorp_Group_WebBadges_Item::getNamedBadge($this->getId());
	}
 
    // interfaces
    public function entityHeadline()
    {
        return $this->getHeadline();
    }

    public function entityPicture()
    {
        return $this->getAvatar();
    }
    
    public function entityItemsCount()
    {
        return $this->getMembers()->setMembersStatus('approved')->getCount();
    }
    
    public function entityCategory()
    {
        return "";
    }
    
    public function entityCountry()
    {
        return $this->getCountry()->name;
    }
    
    public function entityCountryId()
    {
        return $this->getCountry()->id;
    }
    
    public function entityCity()
    {
        return $this->getCity()->name;
    }
    
    public function entityState()
    {
        return $this->getState()->name;
    }    
    
    public function entityStateId()
    {
        return $this->getState()->id;
    }
    
    public function entityURL()
    {
        return $this->getGlobalPath("summary");
    }
}
