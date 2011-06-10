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
 * @copyright  Copyright (c) 2007
 * @author Artem Sukharev
 */


class BaseWarecorp_Group_Simple extends Warecorp_Group_Standard
{
    private $membersName;
    private $isPrivate;
    private $groupPaymentType;
    protected $joinNotifyMode;
    
    public function setMembersName($newVal)
    {
    	$this->membersName = $newVal;
    	return $this;
    }
    public function getMembersName()
    {
    	return $this->membersName;
    }
    public function setIsPrivate($newVal)
    {
    	$this->isPrivate = $newVal;
    	return $this;
    }
    public function getIsPrivate()
    {
    	return $this->isPrivate;
    }
    public function setGroupPaymentType($newVal)
    {
    	$this->groupPaymentType = $newVal;
    	return $this;
    }
    public function getGroupPaymentType()
    {
    	return $this->groupPaymentType;
    }
    public function setJoinNotifyMode($newVal)
    {
        $this->joinNotifyMode = $newVal;
        return $this;
    }
    public function getJoinNotifyMode()
    {
        return $this->joinNotifyMode;
    }
    /**
     * Constructor
     * @param string  $key - ключ для поиска, может быть id|name|group_path
     * @param variant $val - значение ключа
     */
    public function __construct($key = null, $val = null)
    {
        parent::__construct();

        $this->addField('members_name', 'membersName');
        $this->addField('private', 'isPrivate');
        $this->addField('payment_type', 'groupPaymentType');
        $this->addField('join_notify_mode', 'joinNotifyMode');
        if ($key !== null){
            $pkColName = $this->pkColName;
        	$this->pkColName = $key;
            $this->loadByPk($val);
            $this->pkColName = $pkColName;
        }
    }

    /**
     * return is group private
     * @return bool
     * @author Artem Sukharev
     */
    public function isPrivate()
    {
        return (bool) $this->isPrivate;
    }

    /**
     * This function updates all hierarchies this group belongs to. Currently supports only main regional hierarchy.
     * @todo should be a part of save function, but we don't support "changed" values yet.
     * @return bool
     * @author Pavel Shutin
     */
    public function updateHierarchies()
    {
        $query = $this->_db->select()->from('zanby_groups__hierarchy_tree')->where('group_id = ?',$this->getId());
        $rows = $this->_db->fetchAll($query);
        foreach ($rows as $row) {
            $hierarchies = $this->_db->select()->from('zanby_groups__hierarchy_tree t','t.id')->where('lft < ? ', $row['lft'])
                    ->where('rgt > ?', $row['rgt'])->where('level = 0')
                    ->joinLeft('zanby_groups__hierarchy_relation r','r.hierarchy_id = t.id')
                    ->where('r.hierarchy_type = ?',  Warecorp_Group_Hierarchy_Enum::TYPE_LIVE);
            //echo $hierarchies;exit;
            $hierarchies = $this->_db->fetchAll($hierarchies);
            foreach ($hierarchies as $id) {
                $h = Warecorp_Group_Hierarchy_Factory::create($id);
                $h->removeItem($row['id']);
                $h->addCustomItem($this, null);
            }
        }
    }

}
