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
 * Factory of Warecorp_Group_Base successors
 * @author Yury Nelipovich
 * @author Pavel Shutin
 */
class BaseWarecorp_Group_Factory
{
    protected static $typesCache = array();


    /**
     * Loads instance of group by its id
     * @param int $groupId
     * @param string $type
     * @return Warecorp_Group_Base successor
     * @author Yury Nelipovich
     * @author Artem Sukharev
     * @author Pavel Shutin
     */
	public static function loadById($groupId, $type = null)
	{
        if ($type === null) {
            if (!isset(self::$typesCache[$groupId])) {
                $_dbConn = Zend_Registry::get('DB');
                $query = $_dbConn->select()->from('zanby_groups__items', 'type')->where('id = ?', ($groupId === NULL) ? new Zend_Db_Expr('NULL') : $groupId );
                $type = $_dbConn->fetchOne($query);
                self::$typesCache[$groupId] = $type;
            }
            $type = self::$typesCache[$groupId];
        }



        /**
         * I know it's a hack but we have no better way to extend factories in our Core :(
         * @author Pavel Shutin
         */
        if ( Warecorp::checkHttpContext('zccf')) {
            if ($type == Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE) return  new ZCCF_Group_Simple('id',$groupId);
            elseif ($type == Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY) return  new ZCCF_Group_Family('id',$groupId);
        }


        if ( !$type )                   $obj =  new Warecorp_Group_Base();
        if ( $type == Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE )        return  new Warecorp_Group_Simple('id', $groupId);
        elseif ( $type == Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY )    return  new Warecorp_Group_Family('id', $groupId);
        else throw new Zend_Exception("Incorrect group type");
	}

    /**
     * Loads instance of group by its path
     * @param int $groupId
     * @param string $type
     * @return Warecorp_Group_Base successor
     * @author Artem Sukharev
     * @author Pavel Shutin
     */
	public static function loadByPath($path, $type = null)
	{
        if ($type === null) {
			$group = new Warecorp_Group_Base();
			$group->pkColName = 'group_path';
	        $group->loadByPk($path);
            if ($group->getId() === null) return $group;

            $type = $group->getGroupType();
        }

        /**
         * I know it's a hack but we have no better way to extend factories in our Core :(
         * @author Pavel Shutin
         */
        if ( Warecorp::checkHttpContext('zccf')) {
            if ($type == Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE) return new ZCCF_Group_Simple('group_path', $path);
            elseif ($type == Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY) return new ZCCF_Group_Family('group_path', $path);
        }

        if ( $type == Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE )        return new Warecorp_Group_Simple('group_path', $path);
        elseif ( $type == Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY )    return new Warecorp_Group_Family('group_path', $path);
        else throw new Zend_Exception("Incorrect group type");
	}

    /**
     * Loads instance of group by its name
     * @param int $groupId
     * @param string $type
     * @return Warecorp_Group_Base successor
     * @author Artem Sukharev
     * @author Pavel Shutin
     */
    public static function loadByName($name, $type = null)
    {
        if ($type === null) {
			$group = new Warecorp_Group_Base();
			$group->pkColName = 'name';
	        $group->loadByPk($name);
            if ($group->getId() === null) return $group;

            $type = $group->getGroupType();
        }

        /**
         * I know it's a hack but we have no better way to extend factories in our Core :(
         * @author Pavel Shutin
         */
        if ( Warecorp::checkHttpContext('zccf')) {
            if ($type == Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE) return new ZCCF_Group_Simple('name', $name);
            elseif ($type == Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY) return new ZCCF_Group_Family('name', $name);
        }

        if ( $type == Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE )        return new Warecorp_Group_Simple('name', $name);
        elseif ( $type == Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY )    return new Warecorp_Group_Family('name', $name);
        else throw new Zend_Exception("Incorrect group type");
    }

    /**
     * Loads instance of group by its id
     * @param int $groupId
     * @param string $type
     * @return Warecorp_Group_Base successor
     * @author Yury Nelipovich
     * @author Artem Sukharev
     * @author Pavel Shutin
     */
    public static function loadByGroupUID($groupUID, $type = null)
    {
    	//@autor Komarovski
    	if (empty($groupUID)) return null;//throw new Zend_Exception("Empty Group UID");


        $group = null;
        if ($type == null) {
            $group = new Warecorp_Group_Base();
            $group->pkColName = 'groupUID';
            $group->loadByPk($groupUID);

            if ( null === $group->getId() ) return null;

            $type = $group->getGroupType();
        }
    	

        /**
         * I know it's a hack but we have no better way to extend factories in our Core :(
         * @author Pavel Shutin
         */
        if ( Warecorp::checkHttpContext('zccf')) {
            if ($type == Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE) return new ZCCF_Group_Simple('groupUID', $groupUID);
            elseif ($type == Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY) return new ZCCF_Group_Family('groupUID', $groupUID);
        }

        

        if ( $type == Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE ) $group = new Warecorp_Group_Simple('groupUID', $groupUID);
        elseif ( $type == Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY ) $group = new Warecorp_Group_Family('groupUID', $groupUID);

        if ($group == null) {
            $group = new Warecorp_Group_Base();
            $group->pkColName = 'groupUID';
            $group->loadByPk($groupUID);
        }
        return $group;
    }
    
    public static function loadByGroupUIDWithoutException($groupUID)
    {
        /**
         * @author Pavel Shutin
         */
        error_log('This function is senseless and depricated',E_NOTICE);
        return self::loadByGroupUID($groupUID);

//        $group = new Warecorp_Group_Base();
//        $group->pkColName = 'groupUID';
//        $group->loadByPk($groupUID);
//        if ( null === $group->getId() ) return null;
//
//        if ( $group->getGroupType() == Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE ) $group = new Warecorp_Group_Simple('groupUID', $groupUID);
//        elseif ( $group->getGroupType() == Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY ) $group = new Warecorp_Group_Family('groupUID', $groupUID);
//
//        return $group;
    }

   /**
    * Loads the group by regional flag, examp: 'z1sky_district_LA1', 'z1sky_state_NY'
    * @param string $regionalFlag
    * @return null|Warecorp_Group_Base successor
    * @author Roman Gabrusenok
    */
    public static function loadByRegionalFlag( $regionalFlag )
    {
        $group = new Warecorp_Group_Base();
        $group->pkColName = 'context_regional_flag';
        $group->loadByPk($regionalFlag);

        if ( null == $group->getId() ) return null;

        /**
         * I know it's a hack but we have no better way to extend factories in our Core :(
         * @author Pavel Shutin
         */
        if ( Warecorp::checkHttpContext('zccf')) {
            if ($group->getGroupType() == Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE) return new ZCCF_Group_Simple('context_regional_flag', $regionalFlag);
            elseif ($group->getGroupType() == Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY) return new ZCCF_Group_Family('context_regional_flag', $regionalFlag);
        }

        if ( $group->getGroupType() == Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE ) $group = new Warecorp_Group_Simple('context_regional_flag', $regionalFlag);
        elseif ( $group->getGroupType() == Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY ) $group = new Warecorp_Group_Family('context_regional_flag', $regionalFlag);
        else throw new Zend_Exception("Incorrect group type");

        if ( null == $group->getId() ) return null;
        return $group;
    }
}
