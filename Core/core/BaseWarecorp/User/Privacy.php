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
 * @package    Warecorp_User
 * @copyright  Copyright (c) 2006
 * @author Artem Sukharev
 */
class BaseWarecorp_User_Privacy extends Warecorp_Data_Entity
{
	/**
	 * id of user
	 */
	private $userId;
    private $cpAnyMembers;
    private $cpGroupOrganizers;
    private $cpMyGroupOrganizers;
    private $cpMyGroupMembers;
    private $cpMyFriends;
    private $cpMyNetwork;
    private $cvAnyone;
    private $cvAnyMembers;
    private $cvGroupOrganizers;
    private $cvMyGroupOrganizers;
    private $cvMyGroupMembers;
    private $cvMyFriends;
    private $cvMyNetwork;
    private $cvMyAddressBook;
    private $cvPublicPhotos;
    private $cvPublicLists;
    private $cvPublicDocuments;
    private $cvPublicEvents;
    private $cvPublicTags;
    private $cvPublicFriends;
    private $cvPublicVideos;
    private $srAnyone;
    private $srAnyMembers;
    private $srGroupOrganizers;
    private $srMyGroupOrganizers;
    private $srMyGroupMembers;
    private $srMyFriends;
    private $srMyNetwork;
    private $srMyAddressBook;
    private $srViewProfilePhoto;
    private $srViewSendMessage;
    private $srViewAddToFriend;
    private $srViewMyFriends;
	
    private $_blockList;
	/**
	 * Constructor
	 * @param int $value - user id
	 */
	public function __construct($value)
	{
	    
        parent::__construct('zanby_users__privacy', array(
    		'user_id'               => 'userId',
            'cp_any_members'        => 'cpAnyMembers',
            'cp_group_organizers'   => 'cpGroupOrganizers',
            'cp_my_group_organizers'=> 'cpMyGroupOrganizers',
            'cp_my_group_members'   => 'cpMyGroupMembers',
            'cp_my_friends'         => 'cpMyFriends',
            'cp_my_network'         => 'cpMyNetwork',
            'cv_anyone'             => 'cvAnyone',
            'cv_any_members'        => 'cvAnyMembers',
            'cv_group_organizers'   => 'cvGroupOrganizers',
            'cv_my_group_organizers'=> 'cvMyGroupOrganizers',
            'cv_my_group_members'   => 'cvMyGroupMembers',
            'cv_my_friends'         => 'cvMyFriends',
            'cv_my_videos'          => 'cvMyVideos',
            'cv_my_network'         => 'cvMyNetwork',
            'cv_my_address_book'    => 'cvMyAddressBook',
            'cv_public_photos'      => 'cvPublicPhotos',
            'cv_public_lists'       => 'cvPublicLists',
            'cv_public_documents'   => 'cvPublicDocuments',
            'cv_public_events'      => 'cvPublicEvents',
            'cv_public_tags'        => 'cvPublicTags',
            'cv_public_friends'     => 'cvPublicFriends',
            'cv_public_videos'      => 'cvPublicVideos',
            'sr_anyone'             => 'srAnyone',
            'sr_any_members'        => 'srAnyMembers',
            'sr_group_organizers'   => 'srGroupOrganizers',
            'sr_my_group_organizers'=> 'srMyGroupOrganizers',
            'sr_my_group_members'   => 'srMyGroupMembers',
            'sr_my_friends'         => 'srMyFriends',
            'sr_my_network'         => 'srMyNetwork',
            'sr_my_address_book'    => 'srMyAddressBook',
            'sr_view_profile_photo' => 'srViewProfilePhoto',
            'sr_view_send_message'  => 'srViewSendMessage',
            'sr_view_add_to_friend' => 'srViewAddToFriend',
            'sr_view_my_friends'    => 'srViewMyFriends',
    		));
    		
	    if ($value !== null){
	       $this->pkColName = 'user_id';
		   $this->loadByPk($value);
	    }
	}
	
	public function getBlockList()
	{
	    if ($this->_blockList === null) {
	        $this->_blockList = new Warecorp_User_Privacy_BlockList($this->getUserId());
	    }
	    return $this->_blockList;
	}
	
    public function setUserId($newValue)
    {
        $this->userId = $newValue;
        return $this;
    }
    public function getUserId()
    {
        return $this->userId;
    }
    
    /**
     * alias to getUserId to match warecorp_data_entity rules
     */
    public function getId()
    {
        return $this->getUserId();
    }
    
    
    public function setCpAnyMembers($newValue)
    {
        $this->cpAnyMembers = $newValue;
        return $this;
    }
    public function getCpAnyMembers()
    {
        return $this->cpAnyMembers;
    }
    public function setCpGroupOrganizers($newValue)
    {
        $this->cpGroupOrganizers = $newValue;
        return $this;
    }
    public function getCpGroupOrganizers()
    {
        return $this->cpGroupOrganizers;
    }
    public function setCpMyGroupOrganizers($newValue)
    {
        $this->cpMyGroupOrganizers = $newValue;
        return $this;
    }
    public function getCpMyGroupOrganizers()
    {
        return $this->cpMyGroupOrganizers;
    }
    public function setCpMyGroupMembers($newValue)
    {
        $this->cpMyGroupMembers = $newValue;
        return $this;
    }
    public function getCpMyGroupMembers()
    {
        return $this->cpMyGroupMembers;
    }
    public function setCpMyFriends($newValue)
    {
        $this->cpMyFriends = $newValue;
        return $this;
    }
    public function getCpMyFriends()
    {
        return $this->cpMyFriends;
    }
    public function setCpMyNetwork($newValue)
    {
        $this->cpMyNetwork = $newValue;
        return $this;
    }
    public function getCpMyNetwork()
    {
        return $this->cpMyNetwork;
    }
    public function setCvAnyone($newValue)
    {
        $this->cvAnyone = $newValue;
        return $this;
    }
    public function getCvAnyone()
    {
        return $this->cvAnyone;
    }
    public function setCvAnyMembers($newValue)
    {
        $this->cvAnyMembers = $newValue;
        return $this;
    }
    public function getCvAnyMembers()
    {
        return $this->cvAnyMembers;
    }
    public function setCvGroupOrganizers($newValue)
    {
        $this->cvGroupOrganizers = $newValue;
        return $this;
    }
    public function getCvGroupOrganizers()
    {
        return $this->cvGroupOrganizers;
    }
    public function setCvMyGroupOrganizers($newValue)
    {
        $this->cvMyGroupOrganizers = $newValue;
        return $this;
    }
    public function getCvMyGroupOrganizers()
    {
        return $this->cvMyGroupOrganizers;
    }
    public function setCvMyGroupMembers($newValue)
    {
        $this->cvMyGroupMembers = $newValue;
        return $this;
    }
    public function getCvMyGroupMembers()
    {
        return $this->cvMyGroupMembers;
    }
    public function setCvMyFriends($newValue)
    {
        $this->cvMyFriends = $newValue;
        return $this;
    }
    public function getCvMyFriends()
    {
        return $this->cvMyFriends;
    }
    public function setCvMyNetwork($newValue)
    {
        $this->cvMyNetwork = $newValue;
        return $this;
    }
    public function getCvMyNetwork()
    {
        return $this->cvMyNetwork;
    }
    public function setCvMyAddressBook($newValue)
    {
        $this->cvMyAddressBook = $newValue;
        return $this;
    }
    public function getCvMyAddressBook()
    {
        return $this->cvMyAddressBook;
    }
    public function setCvPublicPhotos($newValue)
    {
        $this->cvPublicPhotos = $newValue;
        return $this;
    }
    public function getCvPublicPhotos()
    {
        return $this->cvPublicPhotos;
    }
    public function setCvPublicLists($newValue)
    {
        $this->cvPublicLists = $newValue;
        return $this;
    }
    public function getCvPublicLists()
    {
        return $this->cvPublicLists;
    }
    public function setCvPublicDocuments($newValue)
    {
        $this->cvPublicDocuments = $newValue;
        return $this;
    }
    public function getCvPublicDocuments()
    {
        return $this->cvPublicDocuments;
    }
    public function setCvPublicEvents($newValue)
    {
        $this->cvPublicEvents = $newValue;
        return $this;
    }
    public function getCvPublicEvents()
    {
        return $this->cvPublicEvents;
    }
    public function setCvPublicTags($newValue)
    {
        $this->cvPublicTags = $newValue;
        return $this;
    }
    public function getCvPublicTags()
    {
        return $this->cvPublicTags;
    }
    public function setCvPublicFriends($newValue)
    {
        $this->cvPublicFriends = $newValue;
        return $this;
    }
    public function getCvPublicFriends()
    {
        return $this->cvPublicFriends;
    }
    public function setCvPublicVideos($newValue)
    {
    	$this->cvPublicVideos = $newValue;
    	return $this;
    }
    public function getCvPublicVideos()
    {
        return $this->cvPublicVideos;
    }
    public function setSrAnyone($newValue)
    {
        $this->srAnyone = $newValue;
        return $this;
    }
    public function getSrAnyone()
    {
        return $this->srAnyone;
    }
    public function setSrAnyMembers($newValue)
    {
        $this->srAnyMembers = $newValue;
        return $this;
    }
    public function getSrAnyMembers()
    {
        return $this->srAnyMembers;
    }
    public function setSrGroupOrganizers($newValue)
    {
        $this->srGroupOrganizers = $newValue;
        return $this;
    }
    public function getSrGroupOrganizers()
    {
        return $this->srGroupOrganizers;
    }
    public function setSrMyGroupOrganizers($newValue)
    {
        $this->srMyGroupOrganizers = $newValue;
        return $this;
    }
    public function getSrMyGroupOrganizers()
    {
        return $this->srMyGroupOrganizers;
    }
    public function setSrMyGroupMembers($newValue)
    {
        $this->srMyGroupMembers = $newValue;
        return $this;
    }
    public function getSrMyGroupMembers()
    {
        return $this->srMyGroupMembers;
    }
    public function setSrMyFriends($newValue)
    {
        $this->srMyFriends = $newValue;
        return $this;
    }
    public function getSrMyFriends()
    {
        return $this->srMyFriends;
    }
    public function setSrMyNetwork($newValue)
    {
        $this->srMyNetwork = $newValue;
        return $this;
    }
    public function getSrMyNetwork()
    {
        return $this->srMyNetwork;
    }
    public function setSrMyAddressBook($newValue)
    {
        $this->srMyAddressBook = $newValue;
        return $this;
    }
    public function getSrMyAddressBook()
    {
        return $this->srMyAddressBook;
    }
    public function setSrViewProfilePhoto($newValue)
    {
        $this->srViewProfilePhoto = $newValue;
        return $this;
    }
    public function getSrViewProfilePhoto()
    {
        return $this->srViewProfilePhoto;
    }
    public function setSrViewSendMessage($newValue)
    {
        $this->srViewSendMessage = $newValue;
        return $this;
    }
    public function getSrViewSendMessage()
    {
        return $this->srViewSendMessage;
    }
    public function setSrViewAddToFriend($newValue)
    {
        $this->srViewAddToFriend = $newValue;
        return $this;
    }
    public function getSrViewAddToFriend()
    {
        return $this->srViewAddToFriend;
    }
    public function setSrViewMyFriends($newValue)
    {
        $this->srViewMyFriends = $newValue;
        return $this;
    }
    public function getSrViewMyFriends()
    {
        return $this->srViewMyFriends;
    }
	
}
