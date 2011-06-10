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
 * @package Warecorp_Share_Entity
 * @author Artem Sukharev
 * @version 1.0
 */
class BaseWarecorp_Share_Entity extends Warecorp_Data_Entity
{
    const TABLE_SHARE = 'zanby_entity__share';
    const TABLE_SHARE_EXCEPTION = 'zanby_entity__share_exception';

    /**
     * @param string $groupId
     * @return int|false    If current sharing is for all family's groups return Family ID, else return false
     */
    static public function isSharedFamilyWith( $groupId )
    {
        $id = false;
        if ( !empty($groupId) && false !== strpos($groupId, 'family_') )
            $id = 0 + substr($groupId, 7);
        return $id;
    }

    /**
     * Add new share to all Family children groups
     *
     * @param integer $familyId
     * @param integer $entityId
     * @param integer $entityType
     * @return boolean
     */
    public static function addShare($familyId, $entityId, $entityType)
    {
        if ( empty($entityId) || empty($entityType) || empty($familyId) )
            return false;
        Zend_Registry::get("DB")->insert(self::TABLE_SHARE, array(
            'family_id'     => $familyId,
            'entity_type'   => $entityType,
            'entity_id'     => $entityId,
            'create_date'   => new Zend_Db_Expr('NOW()')
        ));
        return true;
    }

    /**
     * Remove share from all Family children groups
     *
     * @param integer|null $familyId If NULL given, remove share from all families
     * @param integer $entityId
     * @param integer $entityType
     * @param boolean $removeExceptions Is need remove all exceptions for current group
     * @return boolean
     */
    public static function removeShare($familyId = null, $entityId = null, $entityType = null, $removeExceptions = false)
    {
        $db = Zend_Registry::get("DB");
        $where = '1=1';
        if ( !empty($entityId) && !empty($entityType) ) {
            $where .= ' AND '.$db->quoteInto('entity_type = ?', $entityType)
                     .' AND '.$db->quoteInto('entity_id = ?', $entityId);
        }
        if ( !empty($familyId) ) {
            $where .= ' AND '.$db->quoteInto('family_id = ?', $familyId);
        }

        if ( $where === '1=1' ) return false; //  Prevent delete ALL Shares

        $db->delete(self::TABLE_SHARE, $where);
        if ( $removeExceptions ) {
            $db->delete(self::TABLE_SHARE_EXCEPTION, $where);
        }
        return true;
    }

    /**
     * Check share presence for all Family children groups
     *
     * @param integer $familyId
     * @param integer $entityId
     * @param integer $entityType
     * @return boolean
     */
    public static function isShareExists($familyId, $entityId, $entityType)
    {
        if ( empty($entityId) || empty($entityType) || empty($familyId) )
            return false;
        
        $db = Zend_Registry::get("DB");
        $select = $db->select()
            ->from(self::TABLE_SHARE, array('count' => new Zend_Db_Expr('count(*)')))
            ->where('family_id = ?', $familyId )
            ->where('entity_type = ?', $entityType )
            ->where('entity_id  = ?', $entityId );

        return (boolean) $db->fetchOne($select);
    }

    /**
     * Check share exception for needed group
     *
     * @param integer $familyId
     * @param integer $entityId
     * @param integer $entityType
     * @param integer $groupIdForCheckEx
     * @return boolean
     * @throw Warecorp_Share_Exception
     */
    static public function hasShareException($familyId, $entityId, $entityType, $groupIdForCheckEx)
    {
        if ( empty($familyId) || empty($entityId) || empty($entityType) || empty($groupIdForCheckEx) ) {
            require_once 'Warecorp/Share/Exception.php';
            throw new Warecorp_Share_Exception('Incorrect parameters. All parameters are required and cant be empty');
        }
        $query = Zend_Registry::get('DB')->select()
            ->from( self::TABLE_SHARE_EXCEPTION, new Zend_Db_Expr('COUNT(*)') )
            ->where('family_id = ?', $familyId )
            ->where('entity_type = ?', $entityType )
            ->where('entity_id  = ?', $entityId )
            ->where('group_id = ?', $groupIdForCheckEx);
        return (bool) Zend_Registry::get('DB')->fetchOne($query);
    }

    /**
     * Add share exception for needed group
     *
     * @param integer $familyId
     * @param integer $entityId
     * @param integer $entityType
     * @param integer $groupIdForCheckEx
     * @return true Return TRUE Always
     * @throw Warecorp_Share_Exception
     */
    static public function addShareException($familyId, $entityId, $entityType, $groupIdForCheckEx)
    {
        if ( empty($familyId) || empty($entityId) || empty($entityType) || empty($groupIdForCheckEx) ) {
            require_once 'Warecorp/Share/Exception.php';
            throw new Warecorp_Share_Exception('Incorrect parameters. All parameters are required and cant be empty');
        }
        $data = array(
            'family_id'     => $familyId,
            'entity_type'   => $entityType,
            'entity_id'     => $entityId,
            'group_id'      => $groupIdForCheckEx
        );
        Zend_Registry::get('DB')
            ->insert( self::TABLE_SHARE_EXCEPTION, $data );
        return true;
    }

    /**
     * Remove share exception for needed group
     *
     * @param integer $familyId
     * @param integer $entityId
     * @param integer $entityType
     * @param integer|null $groupId If NULL giveg will deleted all exceptions for current share, else for needs group only
     * @return true Return TRUE Always
     * @throw Warecorp_Share_Exception
     */
    static public function removeShareException($familyId, $entityId, $entityType, $groupId = null)
    {
        if ( empty($familyId) || empty($entityId) || empty($entityType) ) {
            require_once 'Warecorp/Share/Exception.php';
            throw new Warecorp_Share_Exception('Incorrect parameters. All parameters are required and cant be empty');
        }

        $db = Zend_Registry::get('DB');
        $where = $db->quoteInto('family_id = ?', $familyId).' AND '.
                 $db->quoteInto('entity_type = ?', $entityType).' AND '.
                 $db->quoteInto('entity_id = ?', $entityId);
        if ( !empty($groupId) && is_numeric($groupId) ) {
            $where .= ' AND '.$db->quoteInto('group_id = ?', $groupId);
        }

        $db->delete( self::TABLE_SHARE_EXCEPTION, $where);
        return true;
    }

    /**
     * Return array of Families' ID where to share current Entity
     *
     * @param integer $entityId
     * @param integer $entityType
     * @return array|null
     * @throw Warecorp_Share_Exception
     */
    static public function whichFamiliesSharedFrom( $entityId, $entityType )
    {
        if ( empty($entityId) || empty($entityType) ) {
            require_once 'Warecorp/Share/Exception.php';
            throw new Warecorp_Share_Exception('Incorrect parameters. All parameters are required and cant be empty');
        }
        $query = Zend_Registry::get('DB')->select()
            ->from(self::TABLE_SHARE, 'family_id')
            ->where('entity_type = ?', $entityType)
            ->where('entity_id = ?', $entityId);
        return Zend_Registry::get('DB')->fetchCol($query);
    }

    /**
     *  !!!Must be called before remove Groups/Family Relations!!!
     *
     *  @param Warecorp_Group_Family|int $family
     *  @param Warecorp_Group_Simple|int $simple
     *  @return array|false
     */
    static private function checkFamilySimpleRelations($family, $simple)
    {
        if ( empty($family) || empty($simple) )
            return false;

        if ( is_numeric($family) ) {
            $family = Warecorp_Group_Factory::loadById($family, Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY);
        }
        if ( is_numeric($simple) ) {
            $simple = Warecorp_Group_Factory::loadById($simple, Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE);
        }

        if ( !$family || !$family->getId() || !$simple || !$simple->getId() )
            return false;

        $families = $simple->getFamilyGroups()->returnAsAssoc(true)->getList();
        if ( !in_array( $simple->getId(), array_keys($families) ) )
            return false;

        return true;
    }

    /**
     *  !!!Must be called before remove Groups/Family Relations!!!
     *  Return all shares from Family to group without exception relations
     *
     *  @param Warecorp_Group_Family|int $family
     *  @param Warecorp_Group_Simple|int $simple
     *  @return array|false
     */
    static public function getAllFamilySharesToGroup( $family, $simple ) {
        if ( !self::checkFamilySimpleRelations($family, $simple) )
            return false;
        $db = Zend_Registry::get('DB');
        return $db->fetchAll($db->select()->from(self::TABLE_SHARE, array('entityId' => 'entity_id', 'entityType' => 'entity_type')));
    }
}
