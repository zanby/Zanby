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
 * @package    Warecorp_Group_Simple
 * @copyright  Copyright (c) 2006
 */

class BaseWarecorp_Group_Family extends Warecorp_Group_Standard
{
	private $company;
	private $position;
	private $address1;
	private $address2;
	private $paymentType;
	private $paymentPlan;
	private $familySize;

	private $Groups;
	private $GroupLocations;
	
	private $groupsInFamilyCount;

	public function setCompany($newValue)
	{
		$this->company = $newValue;
		return $this;
	}
	public function getCompany()
	{
		return $this->company;
	}
	public function setPosition($newValue)
	{
		$this->position = $newValue;
		return $this;
	}
	public function getPosition()
	{
		return $this->position;
	}
	public function setAddress1($newValue)
	{
		$this->address1 = $newValue;
		return $this;
	}
	public function getAddress1()
	{
		return $this->address1;
	}
	public function setAddress2($newValue)
	{
		$this->address2 = $newValue;
		return $this;
	}
	public function getAddress2()
	{
		return $this->address2;
	}
	public function setPaymentType($newValue)
	{
		$this->paymentType = $newValue;
		return $this;
	}
	public function getPaymentType()
	{
		return $this->paymentType;
	}
	public function setPaymentPlan($newValue)
	{
		$this->paymentPlan = $newValue;
		return $this;
	}
	public function getPaymentPlan()
	{
		return $this->paymentPlan;
	}
	public function setFamilySize($newValue)
	{
		$this->familySize = $newValue;
		return $this;
	}
	public function getFamilySize()
	{
		return $this->familySize;
	}
	
    public function getIsPrivate()
    {
    	return false;
    }

	/**
     * return Warecorp_Group_Family_Group_List object for family
     * @return Warecorp_Group_Family_Group_List
     * @author Artem Sukharev
     */
	public function getGroups()
	{
		return new Warecorp_Group_Family_Group_List($this->getId());
		//if ( $this->Groups === null ) $this->Groups = new Warecorp_Group_Family_Group_List($this->getId());
		//return $this->Groups;
	}

	/**
     * return Warecorp_Group_Family_Group_Locations object for family
     * @return Warecorp_Group_Family_Group_Locations
     * @author Artem Sukharev
     */
	public function getGroupLocations()
	{
		return new Warecorp_Group_Family_Group_LocationList($this->getId());
	}

	/**
     * Constructor.
     *
     */
	public function __construct($key = null, $val = null)
	{
		parent::__construct();

		$this->addField('company');
		$this->addField('position');
		$this->addField('address1');
		$this->addField('address2');
		$this->addField('payment_type', 'paymentType');
		$this->addField('payment_plan', 'paymentPlan');
		$this->addField('family_size', 'familySize');
		$this->addField('join_mode', 'joinMode');
		$this->addField('join_code', 'joinCode');

		if ($key !== null){
			$pkColName = $this->pkColName;
			$this->pkColName = $key;
			$this->loadByPk($val);
			$this->pkColName = $pkColName;
		}
	}

	/**
     * Save Family
     * @author Eugene Halauniou
     */
	public function save()
	{
		parent::save();
		if ($this->getId()){
            /**
             * create system hierarchy for family
             */
            $h = Warecorp_Group_Hierarchy_Factory::create();
            $h->setGroupId($this->getId());
            $h->addSystemHierarchy();
		}
	}

	/**
     * Delete All group artefacts
     * @todo delete files
     */
	public function delete()
	{
		$groups = $this->getGroups()
		->setStatus(Warecorp_Group_Enum_GroupStatus::GROUP_STATUS_BOTH)
		->setTypes(array(Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE, Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY))
		->returnAsAssoc()
		->getList();
		if ( sizeof($groups) != 0 ) {
			foreach ( $groups as $groupId => $groupName ) {
				$this->getGroups()->removeGroup($groupId);
			}
		}
        Warecorp_Share_Entity::removeShare($this->getId(), null, null, true);
		parent::delete();

	}

	/**
     * returns group's invitations were sent
     *
     * @param Warecorp_Group_Invitation_eStatuses orderBy
     * @param boolean dest    - false ('ASC'), true ('DESC')
     * @param integer page - 1..num - invitations page number
     * @param integer size - page size
     * @return array of Warecorp_Group_Simple
     *
     * @author Andrew Perasalyak
     */

    public function getInvitationList()
    {
    	return new Warecorp_Group_Invitation_List($this->id);
/*        $sql = $this->_db->select()->from(array('zginv' => 'zanby_groups__invitations'), 'zginv.id');
        $sql->join(array('zgit' => 'zanby_groups__items'), 'zginv.group_owner_id = zgit.id');
        $sql->where('zginv.group_owner_id = ?', $this->id);
        $sql->where('zginv.folder = ?', $folder);

	public function getInvitationList($folder, $orderBy = 'creation_date', $desc = true, $page = 1, $size = 10)
	{
		$sql = $this->_db->select()->from(array('zginv' => 'zanby_groups__invitations'), 'zginv.id');
		$sql->join(array('zgit' => 'zanby_groups__items'), 'zginv.group_sender_id = zgit.id');
		$sql->where('zginv.group_owner_id = ?', $this->id);
		$sql->where('zginv.folder = ?', $folder);
>>>>>>> .r3670

		$orderBy = (Warecorp_Group_Invitation_eHeaders::isAllowed($orderBy)) ? $orderBy : 'creation_date';


		$sql->order('zginv.' . $orderBy .' '. (($desc) ? 'DESC' : 'ASC'));

		if ($page) {
			$sql->limitPage($page, $size);
		}
		$invitations = $this->_db->fetchAll($sql);
		foreach ($invitations as &$invitation) {
			$invitation = new Warecorp_Group_Invitation_Item($invitation['id']);
		}

		return $invitations;
	}
*/
    }

    // interfaces
    public function entityHeadline()
    {
        return $this->getHeadline();
    }

    public function entityPicture()
    {
        return $this->getAvatar()->getSrc();
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
    
    /**
     * Set amount of groups in family. Used in Warecorp_Group_Family_List to set number of child groups in family.
     * @param $groupsInFamilyCount groups count in family
     * @return Warecorp_Group_Family
     */
    public function setGroupsInFamilyCount($groupsInFamilyCount) {
        $this->groupsInFamilyCount = intval($groupsInFamilyCount);
        return $this;
    }
    
    /**
     * Return number of child groups in family. If setGroupsInFamilyCount was called before, take value from setGroupsInFamilyCount.
     * Else count child groups.
     * @return int
     */
    public function getGroupsInFamilyCount() {
        if ($this->groupsInFamilyCount !== null) return $this->groupsInFamilyCount;
        return $this->getGroups()->setTypes(array(Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE, Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY))->getCount();
    }
}
