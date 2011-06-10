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
 * @package    Warecorp_Group_Privileges
 * @copyright  Copyright (c) 2007
 * @author Yury Zolotarsky
 */
class BaseWarecorp_Group_Privileges extends Warecorp_Data_Entity
{
	protected $groupId;
    protected $gpCalendar;
    protected $gpEmail;
    protected $gpPhotos;
    protected $gpVideos;
    protected $gpDocuments;
    protected $gpLists;
    protected $gpPolls;
    protected $gpManageMembers;
    protected $gpManageGroupFamilies;
    protected $gpModifyLayout;
    protected $gpSendEmail;
    protected $gpForumsPosts;
    protected $gpForumsModerate;
    protected $gpGroupsCreation;
    protected $gpShareToFamily;
    
		
	/**
	 * Constructor
	 * @param int $value - group id
	 */
	public function __construct($value)
	{
		parent::__construct('zanby_groups__privileges', array(
    		'group_id'               => 'groupId',
            'gp_calendar'            => 'gpCalendar',
            'gp_email'               => 'gpEmail',
            'gp_photos'              => 'gpPhotos',
            'gp_videos'              => 'gpVideos',
            'gp_documents'           => 'gpDocuments',
            'gp_lists'               => 'gpLists',
            'gp_polls'               => 'gpPolls',
            'gp_manage_members'      => 'gpManageMembers',
            'gp_manage_families'     => 'gpManageGroupFamilies',
            'gp_modify_layout'       => 'gpModifyLayout',
            'gp_send_mail'           => 'gpSendEmail',
            'gp_forums_posts'        => 'gpForumsPosts',
            'gp_forums_moderate'     => 'gpForumsModerate',
            'gp_groups_creation'     => 'gpGroupsCreation',
            'gp_share_to_family'     => 'gpShareToFamily',
    		));
    		
	    if ($value !== null){
	       $this->pkColName = 'group_id';
		   $this->loadByPk($value);
	    }
	}
	
	private function getGroup()
	{
		return Warecorp_Group_Factory::loadById($this->groupId);
	}
	
	public function getUsersListByTool($tool)
	{
		$usersList = new Warecorp_Group_Privileges_ToolUserList($this->getGroupId(), $tool);
		return $usersList;
	}
	
	public function getModeratedToolsByUser($user)
	{
		//вернуть массив всего что модерирует юзер
	}
	
	public function allowUserToModerateToolsList($tools, $user)
	{
		
	}
	
	public function deleteUserFromAllTools($user)
	{
    	if (!($user instanceof Warecorp_User)) $user = new Warecorp_User('id', $user);		
    	$memberIds = $this->getGroup()->getMembers()->getMemberId($user);
    	if (!empty($memberIds)) {
	    	$res = $this->_db->delete('zanby_groups__privileges_users', 
	    	       $this->_db->quoteInto('member_id in (?)', $memberIds).
	    	       $this->_db->quoteInto('AND group_id = ?', $this->getGroupId()));    	
	    	return (boolean) $res;	
    	}
    	return true;	
	}

	public function setCalendar($gpCalendar)
	{
		$this->gpCalendar = $gpCalendar;
		return $this;
	}
	
    public function setEmail($gpEmail)
    {
    	$this->gpEmail = $gpEmail;
    	return $this;
    }
    
    public function setPhotos($gpPhotos)
    {
    	$this->gpPhotos = $gpPhotos;
    	return $this;
    }

    public function setVideos($gpVideos)
    {
        $this->gpVideos = $gpVideos;
        return $this;
    }
    
    public function setDocuments($gpDocuments)
    {
    	$this->gpDocuments = $gpDocuments;
    	return $this;
    }
    
    public function setLists($gpLists)
    {
    	$this->gpLists = $gpLists;
    	return $this;
    }
    
    public function setPolls($gpPolls)
    {
    	$this->gpPolls = $gpPolls;
    	return $this;
    }
    
    public function setManageMembers($gpManageMembers)
    {
    	$this->gpManageMembers = $gpManageMembers;
    	return $this;
    }
    
    public function setManageGroupFamilies($gpManageGroupFamilies)
    {
    	$this->gpManageGroupFamilies = $gpManageGroupFamilies;
    	return $this;
    }
    
    public function setModifyLayout($gpModifyLayout)
    {
    	$this->gpModifyLayout = $gpModifyLayout;
    	return $this;
    }
    
    public function setSendEmail($gpSendEmail)
    {
    	$this->gpSendEmail = $gpSendEmail;
    	return $this;
    }
    
    public function setForumsPosts($gpForumsPosts)
    {
    	$this->gpForumsPosts = $gpForumsPosts;
    	return $this;
    }

    public function setForumsModerate($gpForumsModerate)
    {
    	$this->gpForumsModerate = $gpForumsModerate;
    	return $this;
    }
        
    public function setGroupId($groupId)
	{
		$this->groupId = $groupId;				
		return $this;
	}
	
    public function getGroupId()
	{
		return $this->groupId;				
	}
	
    public function getCalendar()
	{
		return $this->gpCalendar;
	}
	
    public function getEmail()
    {
    	return $this->gpEmail;
    }
    
    public function getPhotos()
    {
    	return $this->gpPhotos;
    }

    public function getVideos()
    {
        return $this->gpVideos;
    }

    public function getDocuments()
    {
    	return $this->gpDocuments;
    }
    
    public function getLists()
    {
    	return $this->gpLists;
    }
    
    public function getPolls()
    {
    	return $this->gpPolls;
    }
    
    public function getManageMembers()
    {
    	return $this->gpManageMembers;
    }
    
    public function getManageGroupFamilies()
    {
    	return $this->gpManageGroupFamilies;
    }
    
    public function getModifyLayout()
    {
    	return $this->gpModifyLayout;
    }
    
    public function getSendEmail()
    {
    	return $this->gpSendEmail;
    }
    
    public function getForumsPosts()
    {
    	return $this->gpForumsPosts;
    }
    
    public function getForumsModerate()
    {
    	return $this->gpForumsModerate;
    }    

        public function getGroupsCreation()
    {
        return $this->gpGroupsCreation;
    }    
    
    public function setGroupsCreation($gpGroupsCreation)
    {
        $this->gpGroupsCreation = $gpGroupsCreation;
        return $this;
    }
    
    public function getShareToFamily()
    {
        return $this->gpShareToFamily;
    }
    
    public function setShareToFamily($gpShareToFamily)
    {
        $this->gpShareToFamily = $gpShareToFamily;
        return $this;
    }
}
