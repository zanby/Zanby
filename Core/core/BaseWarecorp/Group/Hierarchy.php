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
 * @package Warecorp_Group
 * @author Artem Sukharev
 */
class BaseWarecorp_Group_Hierarchy
{
    private $db;
    private $id     = null;
    private $name   = null;
    private $type   = null;
    private $groupID = null;
    private $default = null;
    private $system = null;

    private $present_custom_levels  = null;
    private $no_third_level         = null;
    private $break_after            = null;
    private $group_display          = null;

    private $hierarchy_type         = null;
    private $category_type          = null;
    private $category_focus         = null;

    public $maxHierarchyCount = 5;
    public $maxCategoryDepth = 3;
    public $allowedCategoryDepth = array(1,2,3);

    public function __construct($hierarchyId = null) {
        $this->db = Zend_Registry::get("DB");
        if ( $hierarchyId !== null ) {
            $this->loadById($hierarchyId);
        }
    }
    /**
     * Getters/Setters functions
     */
    public function setId($id) {
        $this->id = $id;
    }
    public function getId() {
        if ( $this->id === null ) throw new Zend_Exception("Hierarchy ID is not defined.");
        return $this->id;
    }
    
    public function setGroupId($groupID) {
        $this->groupID = $groupID;
    }
    public function getGroupId() {
        if ( $this->groupID === null ) throw new Zend_Exception("Group ID is not defined.");
        return $this->groupID;
    }
    
    public function setDefault($bool) {
        $this->default = (bool) $bool;
    }
    public function isDefault() {
        return (bool) $this->default;
    }
    
    public function setSystem($bool) {
        $this->system = (bool) $bool;
    }
    public function isSystem() {
        return (bool) $this->system;
    }
    
    public function setPresentCustomLevels($bool) {
        $this->present_custom_levels = $bool;
    }
    public function isPresentCustomLevels() {
        if ( $this->present_custom_levels === null ) throw new Zend_Exception("PresentCustomLevels is not defined.");
        return $this->present_custom_levels;
    }
    
    public function setNoThirdLevel($bool) {
        $this->no_third_level = $bool;
    }
    public function isNoThirdLevel() {
        if ( $this->no_third_level === null ) throw new Zend_Exception("NoThirdLevel is not defined.");
        return $this->no_third_level;
    }
    
    public function setBreakAfter($value) {
        $this->break_after = $value;
    }
    public function getBreakAfter() {
        if ( $this->break_after === null ) throw new Zend_Exception("BreakAfter is not defined.");
        return $this->break_after;
    }
    
    public function setGroupDisplay($value) {
        $this->group_display = $value;
    }
    public function getGroupDisplay() {
        if ( $this->group_display === null ) throw new Zend_Exception("GroupDisplay is not defined.");
        return $this->group_display;
    }
    
    public function setName($name) {
        $this->name = $name;
    }
    public function getName() {
        if ( $this->name === null ) throw new Zend_Exception("Hierarchy name is not defined.");
        return $this->name;
    }
    
    public function setType($type) {
        $this->type = $type;
    }
    public function getType() {
        if ( $this->type === null ) throw new Zend_Exception("Hierarchy type is not defined.");
        return $this->type;
    }
    
    public function setHierarchyType($hierarchy_type) {
        $this->hierarchy_type = $hierarchy_type;
    }
    public function getHierarchyType() {
        if ( $this->hierarchy_type === null ) throw new Zend_Exception("Hierarchy hierarchy type is not defined.");
        return $this->hierarchy_type;
    }
    
    public function setCategoryType($category_type) {
        $this->category_type = $category_type;
    }
    public function getCategoryType() {
        if ( $this->category_type === null ) throw new Zend_Exception("Hierarchy category type is not defined.");
        return $this->category_type;
    }
    
    public function setCategoryFocus($category_focus) {
        $this->category_focus = $category_focus;
    }
    public function getCategoryFocus() {
        if ( $this->category_focus === null ) throw new Zend_Exception("Hierarchy category focus is not defined.");
        return $this->category_focus;
    }
    /**
     * Load hierarchy by ID
     * @return void
     * @author Artem Sukharev
     */
    public function loadById($hierarchyId) {
        $query = $this->db->select();
        $query->from('zanby_groups__hierarchy_tree', '*');
        $query->where('id = ?', $hierarchyId);
        $res = $this->db->fetchRow($query);
        if ( sizeof($res) != 0 ) {
            $this->setId($hierarchyId);
            $this->setName($res['name']);
            $this->setType($res['type']);
            
            $query = $this->db->select();
            $query->from('zanby_groups__hierarchy_relation', '*')
                  ->where('hierarchy_id = ?', $this->getId());
            $rez = $this->db->fetchRow($query);
            $this->setDefault($rez['isdefault']);
            $this->setSystem($rez['issystem']);
            $this->setGroupId($rez['group_id']);
            $this->setPresentCustomLevels($rez['present_custom_levels']);
            $this->setNoThirdLevel($rez['no_third_level']);
            $this->setBreakAfter($rez['break_after']);
            $this->setGroupDisplay($rez['group_display']);
            $this->setHierarchyType($rez['hierarchy_type']);
            $this->setCategoryType($rez['category_type']);
            $this->setCategoryFocus($rez['category_focus']);
        }
    }

    /*
     +-----------------------------------
     |
     | ADD FUNCTIONS
     |
     +-----------------------------------
    */

    /**
     * Save new hierarchy for group
     * @return void
     * @author Artem Sukharev
     */
    public function save() {
        $data = array();
        $data['name'] = $this->getName();
        $data['type'] = 'hierarchy';
        $tree = new Warecorp_Tree('zanby_groups__hierarchy_tree');
        $h_id = $tree->add($data);
        $this->setId($h_id);
        $this->addHierarchyRelation();
        $gId = $this->addCustomGrouping();
        $cId1 = $this->addCustomCategory('Custom Level 1', $gId);
        $cId2 = $this->addCustomCategory('Custom Level 2', $cId1);
        $cId3 = $this->addCustomCategory('Custom Level 3', $cId2);
    }
    
    /**
     * Add new custom grouping for hierarchy
     * @return int Id of new grouping
     * @author Artem Sukharev
     */
    public function addCustomGrouping() {
        $data = array();
        $data['name'] = '';
        $data['type'] = 'grouping';
        $parent = $this->getId();
        $tree = new Warecorp_Tree('zanby_groups__hierarchy_tree');
        $g_id = $tree->add($data, $parent);
        return $g_id;
    }
    
    /**
     * Add new custom category
     * @param string $categoryName - name of new category
     * @param int $groupingId - Id of parent (grouping or category)
     * @return int Id of new category
     * @author Artem Sukharev
     */
    public function addCustomCategory($categoryName, $groupingId) {
        $data = array();
        $data['name'] = $categoryName;
        $data['type'] = 'category';
        $parent = $groupingId;
        $tree = new Warecorp_Tree('zanby_groups__hierarchy_tree');
        $c_id = $tree->add($data, $parent);
        return $c_id;
    }
    
    /**
     * create new relation for hierarchy in relation table
     * @return void
     * @author Artem Sukharev
     */
    private function addHierarchyRelation() {
        $data = array();
        $data['hierarchy_id']   = $this->getId();
        $data['group_id']       = $this->getGroupId();
        $data['isdefault']      = (int) $this->isDefault();
        //@todo сохранять дефолтовые значения для новой иерархии
        $data['hierarchy_type'] = Warecorp_Group_Hierarchy_Enum::TYPE_CUSTOM;
        $data['category_type']  = 0;    //  Regional
        $data['category_focus'] = Warecorp_Group_Hierarchy_Enum::CATEGORY_FOCUS_ALL;    //  United States & Canada
        $rows_affected = $this->db->insert('zanby_groups__hierarchy_relation', $data);
    }
    
    /**
     * add new custom item for category
     * @param int $groupId
     * @param int $categoryId
     * @return int $id - id of new item 
     * @author Artem Sukharev
     * @author Pavel Shutin
     */
    public function addCustomItem($groupId, $categoryId ) {

        if ($groupId instanceof Warecorp_Group_Standard && $this->getHierarchyType() == Warecorp_Group_Hierarchy_Enum::TYPE_LIVE) {
            return $this->_addCustomGroup($groupId);
        }

        $data = array();
        $data['name']       = '';
        $data['type']       = 'item';
        $data['group_id']   = $groupId;
        $parent = $categoryId;
        $tree = new Warecorp_Tree('zanby_groups__hierarchy_tree');
        $i_id = $tree->add($data, $parent);
        return $i_id;
    }

    /**
     * Special case for Regional Live Hierarchy.
     * @param Warecorp_Group_Standard $group - group object
     * @return int $id - id of new item
     * @author Pavel Shutin
     */
    protected function _addCustomGroup(Warecorp_Group_Standard $group) {

            $categoryName = $group->getCountry()->name;
            $parentCategory = $this->getGroupingList(1);
            $parentCategory = isset($parentCategory[0]['id']) ? $parentCategory[0]['id'] : $this->addCustomGrouping();

            if ($categoryName) {
                $category = $this->getCategory($categoryName,$parentCategory);
                $category = ($category) ? $category['id'] : $this->addCustomCategory($categoryName, $parentCategory);
                $parentCategory = $category;
                $categoryName = $group->getState()->name;
                $category = null;
            }

            if ($categoryName) {
                $category = $this->getCategory($categoryName,$parentCategory);
                $category = ($category) ? $category['id'] : $this->addCustomCategory($categoryName, $parentCategory);
                $parentCategory = $category;
                $categoryName = $group->getCity()->name;
                $category = null;
            }

            if ($categoryName) {
                $category = $this->getCategory($categoryName,$parentCategory);
                $category = ($category) ? $category['id'] : $this->addCustomCategory($categoryName, $parentCategory);
                $parentCategory = $category;
            }
            
            return $this->addCustomItem($group->getId(), $parentCategory);
    }
    
    /**
     * create new system hierarchy for group
     * @return void
     * @author Artem Sukharev
     */
    public function addSystemHierarchy() {
        if ( !$this->checkIsSystemHierarchyexists() ) {
            $data = array();
            $data['name'] = 'Regional';
            $data['type'] = 'hierarchy';
            $tree = new Warecorp_Tree('zanby_groups__hierarchy_tree');
            $h_id = $tree->add($data);

//            $this->setId($h_id);
//            $gId = $this->addCustomGrouping();

            $data = array();
            $data['hierarchy_id']   = $h_id;
            $data['group_id']       = $this->getGroupId();
            $data['isdefault']      = true;
            $data['issystem']       = 1;
            $data['hierarchy_type'] = Warecorp_Group_Hierarchy_Enum::TYPE_LIVE;
            $data['category_type']  = 1;    //  Regional
            $data['category_focus'] = Warecorp_Group_Hierarchy_Enum::CATEGORY_FOCUS_ALL;
            $rows_affected = $this->db->insert('zanby_groups__hierarchy_relation', $data);
        }
    }
    
    /**
     * convert custom hierarchy to live hierarchy
     * @return void
     * @author Artem Sukharev
     * @author Pavel Shutin
     */
    public function convertCustomToLive($data) {
        if ( $data['hierarchy_type'] == Warecorp_Group_Hierarchy_Enum::TYPE_LIVE ) {
            $this->removeChildren($this->getId());
            $countries = null;
            switch ( $data['category_focus'] ) {
                case Warecorp_Group_Hierarchy_Enum::CATEGORY_FOCUS_ALL :
                    $countries = null;  //  All Countries
                    break;
                case Warecorp_Group_Hierarchy_Enum::CATEGORY_FOCUS_USA_CANADA :
                    $countries = array(1, 38);  //  United States & Canada
                    break;
                case Warecorp_Group_Hierarchy_Enum::CATEGORY_FOCUS_USA :
                    $countries = array(1);  //  United States
                    break;
                case Warecorp_Group_Hierarchy_Enum::CATEGORY_FOCUS_CANADA :
                    $countries = array(38);  //  Canada
                    break;
            }
            $this->_buildRegionalCategories($countries);
        }
    }
    
    /**
     * convert custom hierarchy to new custom hierarchy
     * @param array $data
     * @return void
     * @author Artem Sukharev
     */
    public function convertCustomToCustom($data) {
        if ( $data['hierarchy_type'] == Warecorp_Group_Hierarchy_Enum::TYPE_CUSTOM ) {
        	/**
        	 * make old hierarchy as live
        	 */
            $this->removeChildren($this->getId());
            /**
             * convert live to custom
             */
            $this->convertLiveToCustom($data);
        }
    }
    
    /**
     * convert live hierarchy to custom hierarchy
     * @param array $data
     * @return void
     * @author Artem Sukharev
     */
    public function convertLiveToCustom($data) {
    	/**
    	 * if Custom Hierarchy
    	 */
        if ( $data['hierarchy_type'] == Warecorp_Group_Hierarchy_Enum::TYPE_CUSTOM ) {
        	/**
        	 * if Custom Categories
        	 */
            if ( $data['category_type'] == 0 ) {
                /**
                 * build custom hierarchy with custom categories
                 */
                $this->removeChildren($this->getId());
                call_user_func(array($this, '_buildCustomCategories'));
            } 
            /**
             * if Regional Categories
             */
            elseif ( $data['category_type'] == 1 ) {
            	/**
            	 * FIXME id стран сейчас забиты жестко, надо будет переделать (id для штатов и канады)
            	 * set allowed countries for regional categories
            	 */
                if ( $data['category_focus'] == Warecorp_Group_Hierarchy_Enum::CATEGORY_FOCUS_ALL )     $countries = null;              //  All Countries
                elseif ( $data['category_focus'] == Warecorp_Group_Hierarchy_Enum::CATEGORY_FOCUS_USA_CANADA ) $countries = array(1, 38);      //  USA & Canada
                elseif ( $data['category_focus'] == Warecorp_Group_Hierarchy_Enum::CATEGORY_FOCUS_USA ) $countries = array(1);          //  USA
                elseif ( $data['category_focus'] == Warecorp_Group_Hierarchy_Enum::CATEGORY_FOCUS_CANADA ) $countries = array(38);         //  Canada
                /**
                 * build custom hierarchy with regional categories
                 */
                call_user_func(array($this, '_buildRegionalCategories'), $countries);
            }
        }
    }
    
    /**
     * build default custom hierarchy with custom categories
     * @return void
     * @author Artem Sukharev
     */
    public function _buildCustomCategories() {
        $gId = $this->addCustomGrouping();
        $cId1 = $this->addCustomCategory('Custom Level 1', $gId);
        $cId2 = $this->addCustomCategory('Custom Level 2', $cId1);
        $cId3 = $this->addCustomCategory('Custom Level 3', $cId2);
    }
    
    /**
     * build default custom hierarchy with regional categories
     * @param array of int $countries - allowed countries
     * @return void
     * @author Artem Sukharev
     */
    public function _buildRegionalCategories($countries = null) {
        $groupingId = $this->addCustomGrouping();

        $tree               = array();
        $tree['countries']  = array();
        $tree['states']     = array();
        $tree['cities']     = array();
        $tree['groups']     = array();
        $tree_count         = array();
        $used_countries     = array();
        $groups_count_in_city = array();
        $currentGroup = Warecorp_Group_Factory::loadById($this->getGroupId(),Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY);
        if ( !($currentGroup instanceof Warecorp_Group_Family) ) throw new Zend_Exception('Can not use hierarchy tools for non family group');
        $groups = $currentGroup->getGroups()->setTypes(array('simple','family'))->returnAsAssoc()->getList();

        

        /**
         * Все выбранные для фокуса страны добавляются независемо, есть в них группы или нет.
         */
        if ( sizeof($countries) != 0 ) {
            foreach ( $countries as $country ) {
                $tmpCountry = Warecorp_Location_Country::create($country);
                $tree['countries'][] = array(
                    'id' => $tmpCountry->id,
                    'name' => $tmpCountry->name,
                    'parent' => null
                );
                $used_countries[] = $tmpCountry->id;
                $tree_count['country_'.$tmpCountry->id] = 0;
            }
        }

        /**
         * build tree
         * в дерево добавляются только страны, штаты, город в которых ЕСТЬ группы
         * остальные не добавляются
         */
        if ( is_array($groups) && sizeof($groups) > 0 ) {
            $used_states                = array();
            $used_cities                = array();
            $tree['allow_countries']    = $countries;

            foreach ( $groups as $groupId=>$groupName ) {
                $group = Warecorp_Group_Factory::loadById($groupId,Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE);
                $tmpCountry = $group->getCountry();
                if ( null === $tree['allow_countries'] || in_array($tmpCountry->id, $tree['allow_countries']) ) {
                    if ( $tmpCountry->id && !in_array($tmpCountry->id, $used_countries) ) {
                        //$tree['countries'][$tmpCountry->id] = $this->addCustomCategory($tmpCountry->name, $groupingId);
                        $tree['countries'][] = array(
                            'id' => $tmpCountry->id,
                            'name' => $tmpCountry->name,
                            'parent' => null
                        );
                        $used_countries[] = $tmpCountry->id;
                        $tree_count['country_'.$tmpCountry->id] = 0;
                    }
                    $tmpState = $group->getState();
                    if ( $tmpState->id && !in_array($tmpState->id, $used_states) ) {
                        //$tree['states'][$tmpState->id] = $this->addCustomCategory($tmpState->name, $tree['countries'][$tmpCountry->id]);
                        $tree['states'][] = array(
                            'id' => $tmpState->id,
                            'name' => $tmpState->name,
                            'parent' => $tmpCountry->id
                        );
                        $used_states[] = $tmpState->id;
                        $tree_count['state_'.$tmpState->id] = 0;
                    }
                    $tmpCity = $group->getCity();
                    if ( $tmpCity->id && !in_array($tmpCity->id, $used_cities) ) {
                        //$tree['cities'][$tmpCity->id] = $this->addCustomCategory($tmpCity->name, $tree['states'][$tmpState->id]);
                        $tree['cities'][] = array(
                            'id' => $tmpCity->id,
                            'name' => $tmpCity->name,
                            'parent' => $tmpState->id
                        );
                        $used_cities[] = $tmpCity->id;
                        $tree_count['city_'.$tmpCity->id] = 0;
                    }
                    if ( $tmpCountry->id && $tmpState->id && $tmpCity->id ) {
                        $tree['groups'][] = array(
                            'id' => $group->getId(),
                            'name' => $group->getName(),
                            'parent' => $tmpCity->id
                        );
                        $tree_count['country_'.$tmpCountry->id] ++;
                        $tree_count['state_'.$tmpState->id] ++;
                        $tree_count['city_'.$tmpCity->id] ++;
                    }
                }
            }
            usort($tree['countries'],   'sortHierarchyByName');
            usort($tree['states'],      'sortHierarchyByName');
            usort($tree['cities'],      'sortHierarchyByName');
            usort($tree['groups'],      'sortHierarchyByName');
        }
        foreach ( $tree['countries'] as $item ) {
            $countries[$item['id']] = $this->addCustomCategory($item['name'], $groupingId);
        }
        foreach ( $tree['states'] as $item ) {
            $states[$item['id']] = $this->addCustomCategory($item['name'], $countries[$item['parent']]);
        }
        foreach ( $tree['cities'] as $item ) {
            $cities[$item['id']] = $this->addCustomCategory($item['name'], $states[$item['parent']]);
        }
        foreach ( $tree['groups'] as $item ) {
            $this->addCustomItem($item['id'], $cities[$item['parent']] );
        }

        //  Вывод полного списка штатов для выбранных в фокусе стран.
//        if ( sizeof($countries) != 0 ) {
//            $countries_list = Warecorp_Location::getCountriesListByIds($countries);
//        }
//        if ( sizeof($countries_list) != 0 ) {
//            foreach ( $countries_list as $country ) {
//                $countryId = $this->addCustomCategory($country->name, $groupingId);
//                $states_list = $country->getStatesList();
//                if ( sizeof($states_list) != 0 ) {
//                    foreach( $states_list as $state ) {
//                        $stateId = $this->addCustomCategory($state->name, $countryId);
//                        /*
//                        $cities_list = $state->getCitiesList();
//                        if ( sizeof($cities_list) != 0 ) {
//                            foreach ( $cities_list as $city ) {
//                                $cityId = $this->addCustomCategory($city->name, $stateId);
//                            }
//                        }
//                        */
//                    }
//                }
//            }
//        }
    }
    
    /*
     +-----------------------------------
     |
     | UPDATE FUNCTIONS
     |
     +-----------------------------------
    */

    /**
     * update name of item (grouping, category, group) by id
     * @return void
     * @author Artem Sukharev
     */
    public function updateNodeName($id, $name) {
        $data = array();
        $data['name'] = $name;
        $where = $this->db->quoteInto('id = ?', $id);
        $rows_affected = $this->db->update('zanby_groups__hierarchy_tree', $data, $where);
    }
    
    /**
     * set hierarchy as default hierarchy
     * @return void
     * @author Artem Sukharev
     */
    public function setHierarchyAsDefault($isdefault) {
        $isdefault = (bool) $isdefault;
        if ( $isdefault == true ) {
            $data = array();
            $data['isdefault'] = 0;
            $where = $this->db->quoteInto('group_id = ?', $this->getGroupId());
            $rows_affected = $this->db->update('zanby_groups__hierarchy_relation', $data, $where);

            $data = array();
            $data['isdefault'] = 1;
            $where = $this->db->quoteInto('hierarchy_id = ?', $this->getId());
            $rows_affected = $this->db->update('zanby_groups__hierarchy_relation', $data, $where);
        } elseif ( $this->isDefault() ) {
            $data = array();
            $data['isdefault'] = 0;
            $where = $this->db->quoteInto('id = ?', $this->getId());
            $rows_affected = $this->db->update('zanby_groups__hierarchy_relation', $data, $where);
        }
    }
    
    /**
     * update options of hierarchy
     * @param array $data
     * @return void
     * @author Artem Sukharev
     */
    public function updateHierarchyOptions($data) {
        $where = $this->db->quoteInto('hierarchy_id = ?', $this->getId());
        $rows_affected = $this->db->update('zanby_groups__hierarchy_relation', $data, $where);
    }
    
    /**
     * update constraints of hierarchy
     * @param array $data
     * @return void
     * @author Artem Sukharev
     */    
    public function updateHierarchyConstraints($data) {
        $where = $this->db->quoteInto('hierarchy_id = ?', $this->getId());
        $rows_affected = $this->db->update('zanby_groups__hierarchy_relation', $data, $where);
    }
    
    /*
     +-----------------------------------
     |
     | REMOVE FUNCTIONS
     |
     +-----------------------------------
    */
    
    /**
     * remove hierarchy and hierarchy tree
     * @param int $hierarchy_id
     * @return void
     * @author Artem Sukharev
     */
    public function removeHierarchy($hierarchy_id) {
        $where = $this->db->quoteInto('hierarchy_id = ?', $hierarchy_id);
        $rows_affected = $this->db->delete('zanby_groups__hierarchy_relation', $where);
        $tree = new Warecorp_Tree('zanby_groups__hierarchy_tree');
        $tree->remove($hierarchy_id);
    }
    
    /**
     * remove current category
     * @param int $current_category - id of category in tree table
     * @return void
     * @author Artem Sukharev
     */
    public function removeCategory($current_category) {
        $tree = new Warecorp_Tree('zanby_groups__hierarchy_tree');
        $tree->remove($current_category);
    }
    
    /**
     * remove item (group) from tree by group id and category id
     * @return void
     * @author Artem Sukharev
     */
    public function removeItemByCategoryAndGroup($oldcatid, $groupId) {
        $row = $this->checkItemByCategoryAndGroup($oldcatid, $groupId);
        if ($row) {
            $tree = new Warecorp_Tree('zanby_groups__hierarchy_tree');
            $tree->remove($row);
        }

    }
    
    /**
     * remove grouping by id
     * @return void
     * @author Artem Sukharev
     */
    public function removeGrouping($groupId) {
        $tree = new Warecorp_Tree('zanby_groups__hierarchy_tree');
        $tree->remove($groupId);
    }
    
    /**
     * remove any item (grouping, category, group) from tree by id
     * @return void
     * @author Artem Sukharev
     */
    public function removeItem($itemId) {
        $tree = new Warecorp_Tree('zanby_groups__hierarchy_tree');
        $tree->remove($itemId);
    }
    
    /**
     * remove all children of any item (grouping, category, group) from tree by id
     * @return void
     * @author Artem Sukharev
     */
    public function removeChildren($catid) {
        $tree = new Warecorp_Tree('zanby_groups__hierarchy_tree');
        $tree->removeChildren($catid);
    }
    
    /*
     +-----------------------------------
     |
     | CHECK FUNCTION
     |
     +-----------------------------------
    */
    
    /**
     * check if item (group) exists in tree by category and group
     * @param int $oldcatid - id of category
     * @param int $groupId - id of group
     * @return int id of item (group relation in tree)
     * @author Artem Sukharev
     */
    public function checkItemByCategoryAndGroup($oldcatid, $groupId) {
        $query = $this->db->select();
        $query->from('zanby_groups__hierarchy_tree', 'id')
              ->where('parent = ?', $oldcatid)
              ->where('group_id = ?', $groupId)
              ->where('type = ?', 'item');
        $row = $this->db->fetchCol($query);
        return $row;
    }
    
    /**
     * check if hierarch is system hierarchy
     * @return boolean
     * @author Artem Sukharev
     */
    public function checkIsSystemHierarchyexists() {
        $query = $this->db->select();
        $query->from('zanby_groups__hierarchy_relation', new Zend_Db_Expr('count(id)'))
              ->where('group_id = ?', $this->getGroupId())
              ->where('issystem = ?', 1);
        $row = $this->db->fetchOne($query);
        return (bool) $row;
    }
    
    /**
     * check if category has groups
     * @param int $categoryId - id of category in tree
     * @return boolean
     * @author Artem Sukharev
     */
    public function checkCategoryHasGroups($categoryId)
    {
        $tmpTree = new Warecorp_Tree('zanby_groups__hierarchy_tree');
        $tmpTree->setCondition("type = 'item'");
        $res = $tmpTree->getSubNodes($categoryId);
        return (bool) sizeof($res);
    }
    
    /**
     * check if category has category with certain level
     * @param int $categoryId - id of category in tree
     * @param int level
     * @return boolean
     * @author Artem Sukharev
     */
    public function checkCategoryHasLevelCategories($categoryId, $level)
    {
    	$level = $level + 1;
        $tmpTree = new Warecorp_Tree('zanby_groups__hierarchy_tree');
        $tmpTree->setCondition("level = '".$level."' AND type = 'category'");
        $res = $tmpTree->getSubNodes($categoryId);
        return (bool) sizeof($res);
    }
    
    /**
     * check category
     * @param int $categoryId - id of category in tree
     * @param char $subCategoryLetter
     * @return boolean
     * @author Artem Sukharev\
     * @author Pavel Shutin
     */
    public function checkToPreview($categoryId, $subCategoryLetter = null)
    {
    	/**
    	 * if category hasn't groups - don't view it
    	 */
    	if ( false == $this->checkCategoryHasGroups($categoryId) ) return false;

        if ( null === $subCategoryLetter) return true;
        else {
    		$tmpTree = new Warecorp_Tree('zanby_groups__hierarchy_tree');
            $categoryNode = $tmpTree->getNode($categoryId);
            if (strtolower($categoryNode['name'][0])==strtolower($subCategoryLetter))
            {
                return true;
            }
        }
        return false;

    	/**
    	 * need check sub level by letter
    	 */
//    	if ( null === $subCategoryLetter ) return true;
//    	else {
//    		$tmpTree = new Warecorp_Tree('zanby_groups__hierarchy_tree');
//            $categoryNode = $tmpTree->getNode($categoryId);
//            $checkedLevel = $categoryNode['level'] + 1;
//
//            $tmpTree->setCondition("(level = '".$checkedLevel."' AND type = 'category' AND name LIKE '".$subCategoryLetter."%')");
//            $res = $tmpTree->getSubNodes($categoryId);
//            var_dump($res);
//            /**
//             * there are not subcategories started with certain letter
//             */
//            if ( !$res ) return false;
//            else {
//            	$hasGroups = false;
//                foreach ( $res as $_id ) {
//                	if ( true == $this->checkCategoryHasGroups($_id) ) $hasGroups = true;
//                }
//                return $hasGroups;
//            }
//    	}
    }
    /*
     +-----------------------------------
     |
     | GET FUNCTION
     |
     +-----------------------------------
    */

    /**
     * get list of hierarchies for current group ($this->getGroupId())
     * @return array of Warecorp_Group_Hierarchy
     * @author Artem Sukharev
     */
    public function getHierarchyList() {
        $result = array();
        $rels = $this->getHierarchyRelationsList();
        if ( sizeof($rels) != 0 ) {
            foreach ( $rels as $rel ) {
                $tmp = Warecorp_Group_Hierarchy_Factory::create($rel['hierarchy_id']);
                $tmp->setGroupId($this->getGroupId());
                $tmp->setDefault($rel['isdefault']);
                $result[] = $tmp;
            }
        }
        return $result;
    }
    
    /**
     * get number of hierarchies for current group ($this->getGroupId())
     * @return array of Warecorp_Group_Hierarchy
     * @author Artem Sukharev
     */
    public function getHierarchyCount() {
        $rels = $this->getHierarchyRelationsList();
        return sizeof($rels);
    }
    
    /**
     * get hierarchy relations for current group ($this->getGroupId())
     * @return array of hierarchy_id,isdefault
     * @author Artem Sukharev
     */
    private function getHierarchyRelationsList() {
        $query = $this->db->select();
        $query->from('zanby_groups__hierarchy_relation', array('hierarchy_id', 'isdefault'));
        $query->where('group_id = ?', $this->getGroupId());
        $query->order('isdefault DESC');
        $res = $this->db->fetchAll($query);
        return $res;
    }
    
    /**
     * return hierarchy relation for default hierarch of current group
     * @return int hierarchy_id
     * @author Artem Sukharev
     */
    private function getDefaultHierarchyRelation() {
        $query = $this->db->select();
        $query->from('zanby_groups__hierarchy_relation', 'hierarchy_id')
              ->where('group_id = ?', $this->getGroupId())
              ->where('isdefault = ?', 1);
        $result = $this->db->fetchOne($query);
        return $result;
    }
    
    /**
     * return default hierarchy of current group 
     * @return Warecorp_Group_Hierarchy
     * @author Artem Sukharev
     */
    public static function getGroupDefaultHierarchy($groupId)
    {
        $h = Warecorp_Group_Hierarchy_Factory::create();
        $h->setGroupId($groupId);
        $rel = $h->getDefaultHierarchyRelation();
        if ( $rel ) {
            $h->loadById($rel);
            return $h;
        }
        return null;
    }
    
    /**
     * return array of groupings for hierarchy
     * @return array
     *          - id
     *          - parent
     *          - lft
     *          - rgt
     *          - level
     *          - name
     *          - type (hierarchy|grouping|category|item)
     *          - group_id (if type == item)
     *  @author Artem Sukharev
     */
    public function getGroupingList($level = null) 
    {
//        if ( $level !== null ) {
//            $tree = new Warecorp_Tree('zanby_groups__hierarchy_tree', "type = 'grouping' AND level = '".$level."'");
//        }
        
        $tree = new Warecorp_Tree('zanby_groups__hierarchy_tree', "type = 'grouping'");
        return $tree->getTree($this->getId(), '*', false);;
    }
    
    /**
     * return tree of categories
     * @return array
     *          - id
     *          - parent
     *          - lft
     *          - rgt
     *          - level
     *          - name
     *          - type (hierarchy|grouping|category|item)
     *          - group_id (if type == item)
     */
    public function getCategoryTree($groupingId) 
    {
        $tree = new Warecorp_Tree('zanby_groups__hierarchy_tree');
        return $tree->getTree($groupingId, '*', false);
    }

    public function getCategory($name,$parentId) {
        $query = $this->db->select()->from('zanby_groups__hierarchy_tree')->where('parent = ? ',$parentId)->where('name like ?',$name);
        return $this->db->fetchRow($query);
    }
    
    /**
     * return opened tree of categories
     * @return array
     *          - id
     *          - parent
     *          - lft
     *          - rgt
     *          - level
     *          - name
     *          - type (hierarchy|grouping|category|item)
     *          - group_id (if type == item)
     */
    public function getCategoryOpenTree($groupingId) 
    {
        $tree = new Warecorp_Tree('zanby_groups__hierarchy_tree');
        return $tree->getOpenTree($groupingId, '*', false);
    }
    
    /**
     * Return tree of hierarchy (custom hierarchy or live hierarchy)
     * @param string $stateLetter DEPRICATED PARAM
     * @return array of hierarchy items
     * @author Artem Sukharev
     */
    public function getHierarchyTree($stateLetter = null) 
    {
        /**
         * @author Pavel Shutin. This tree should be pulled entirely and REQUIRES long-term caching. 
         */
        if ($stateLetter !== null) {
            error_log('This method should be used without params. Param is depricated and useless',E_NOTICE);
        }
    	/**
    	 * Live Hierarchy
    	 */
       // if ( $this->hierarchy_type == Warecorp_Group_Hierarchy_Enum::TYPE_LIVE ) {
        //    return $this->getLiveTree($stateLetter);
       // }
        /**
         * Custom Hierarchy
         */ 
        //else {
            $tree = $this->getTree();
            foreach ( $tree as &$item ) {
                if ( $item['type'] == 'item' ) {
                	$item['group'] = Warecorp_Group_Factory::loadById($item['group_id'],Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE);
                }
            }
            return $tree;
       // }
    }
    
    /**
     * Return tree of hierarchy (custom hierarchy or live hierarchy)
     * @param string $stateLetter - first letter of 2 level category
     * @return array of hierarchy items
     * @author Artem Sukharev
     */
    public function getHierarchyTreeWithEvents($stateLetter = null, $currentTimezone, $objUser, &$eventIds = null) 
    {
        return $this->getHierarchyTree();
        /**
         * Live Hierarchy
         */
//        if ( $this->hierarchy_type == Warecorp_Group_Hierarchy_Enum::TYPE_LIVE ) {
//            return $this->getLiveTreeWithEvents($stateLetter, $currentTimezone, $objUser, $eventIds);
//        }
        /**
         * Custom Hierarchy
         */ 
//        else {
            $tree = $this->getTree();
            foreach ( $tree as &$item ) {
                if ( $item['type'] == 'item' ) {
                    $item['group'] = Warecorp_Group_Factory::loadById($item['group_id'],Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE);
                }
            }
            return $tree;
//        }
    }
    
    public function getCustomHierarchyLetters()
    {
        /*
         * @author Pavel Shutin
         */
        error_log('This method is depricated. Use getHierarchyLetters instead.', E_NOTICE);
        /* Live Hierarchy */
        if ( $this->hierarchy_type == Warecorp_Group_Hierarchy_Enum::TYPE_LIVE ) { throw new Zend_Exception('Can not use this method for live hierarchy'); }
        
        /* Custom Hierarchy */ 
        else { 
            $letters = array();
            $tree = $this->getTree();
            $objTree = new Warecorp_Tree('zanby_groups__hierarchy_tree');
            $objTree->setCondition("type = 'item'");
            foreach ( $tree as &$item ) {
                if ( $item['type'] == 'category' && $item['level'] == 3 ) {
                    $ch = $objTree->getChildren($item['id']);
                    if ( sizeof($ch) != 0 ) { $letters[strtoupper(substr($item['name'],0,1))] = true; }
                }
            }
            return $letters;
        }
    }

    public function getHierarchyLetters()
    {
        $letters = array();
        $tree = $this->getTree();
        $objTree = new Warecorp_Tree('zanby_groups__hierarchy_tree');
        $objTree->setCondition("type = 'item'");
        foreach ( $tree as &$item ) {
            if ( $item['type'] == 'category' && $item['level'] == 3 && (bool)$objTree->getSubnodes($item['id']) ) {
                $letters[strtoupper(substr($item['name'],0,1))] = true;
            }
        }
        return $letters;
    }
    
    /**
     * Return tree of hierarchy (custom hierarchy)
     * @return array of hierarchy items
     * @access private
     */
    private function getTree() 
    {
        $tree = new Warecorp_Tree('zanby_groups__hierarchy_tree');
        return $tree->getTree($this->getId(), '*', false);
    }
    
    /**
     * Return tree of hierarchy (live hierarchy)
     * @param string $stateLetter - first letter of state name
     * @return array of hierarchy items
     * @access private
     * @author Artem Sukharev
     */
    private function getLiveTree($stateLetter = null) 
    {
        /*
         * @author Pavel Shutin
         */
        error_log('This method is depricated.', E_NOTICE);
        $tree = array();
        $tree['countries']  = array();
        $tree['counts']     = array();
        $tree['letters']    = array();
        
        $groups_count_in_city = array();

        $currentGroup = Warecorp_Group_Factory::loadById($this->getGroupId(), Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY);

        $cache = Warecorp_Cache::getFileCache();
        if ( !$groups = $cache->load('groups_members_cache_'.$this->getGroupId()) ) {
            $groups = $currentGroup->getGroups()->getList();
            $cache->save($groups, 'groups_members_cache_'.$this->getGroupId(), array(), 600);
        }

        $cacheLetter = ($stateLetter === null) ? "null" : $stateLetter;

        if ( is_array($groups) && sizeof($groups) > 0 ) {
            $used_countries             = array();
            $used_states                = array();
            $used_cities                = array();
            $tree['allow_countries']    = array();
            //  @todo надо заменить, чтобы бралось все это из базы
            if ( $this->getCategoryType() == 1 ) { //  если тип категорий - Regional
                switch ( $this->getCategoryFocus() ) {
                    case 0 :
                        $tree['allow_countries'] = null;  //  All Countries
                        break;
                    case 1 :
                        $tree['allow_countries'] = array(1, 38);  //  United States & Canada
                        break;
                    case 2 :
                        $tree['allow_countries'] = array(1);  //  United States
                        break;
                    case 3 :
                        $tree['allow_countries'] = array(38);  //  Canada
                        break;
                }
            }

            if ( $treeCache = $cache->load('group_hierarchy_tree_'.$this->getGroupId()."_".$this->getCategoryType()."_".$this->getCategoryFocus()."_".$cacheLetter) ) {
                return $treeCache;
            }


            foreach ( $groups as $group ) {
            	$tmpState =& $group->getState();
            	if ( $stateLetter === null || strtoupper(substr($tmpState->name, 0, 1)) == strtoupper($stateLetter) ) {            	
	                $tmpCountry =& $group->getCountry();
	                if ( null === $tree['allow_countries'] || in_array($tmpCountry->id, $tree['allow_countries']) ) {
	                    if ( $tmpCountry->id && !in_array($tmpCountry->id, $used_countries) ) {
	                        $tree['countries']['country_'.$tmpCountry->id] = array(
	                            'id' => $tmpCountry->id,
	                            'name' => $tmpCountry->name,
	                            'countOfStates' => 0,
	                            'children' => array()                            
	                        );
	                        $used_countries[] = $tmpCountry->id;
	                        $tree['counts']['country_'.$tmpCountry->id] = 0;
	                    }
	                    $tmpState = $group->getState();
	                    if ( $tmpState->id && !in_array($tmpState->id, $used_states) ) {
	                        $tree['countries']['country_'.$tmpCountry->id]['children']['states']['state_'.$tmpState->id]
	                        = array(
	                            'id' => $tmpState->id,
	                            'name' => $tmpState->name,
	                            'group_count' => 0,
	                            'children' => array()
	                        );
	                        $used_states[] = $tmpState->id;
	                        $tree['counts']['state_'.$tmpState->id] = 0;
	                        $tree['countries']['country_'.$tmpCountry->id]['countOfStates']++;
	                    }
	                    $tmpCity = $group->getCity();
	                    $count = (isset($groups_count_in_city[$tmpCity->id])) ? $groups_count_in_city[$tmpCity->id] : $currentGroup->getGroups()->setTypes(array('simple','family'))->addWhere('zgi.city_id = '.$tmpCity->id)->getCount();
	                    if ( $this->isNoThirdLevel() || $count < $this->getBreakAfter() ) {
	                        if ( $tmpCountry->id && $tmpState->id ) {
	                            $tree['countries']['country_'.$tmpCountry->id]['children']['states']['state_'.$tmpState->id]['children']['groups']['group_'.$group->getId()]
	                            = array(
	                                'id' => $group->getId(),
	                                'name' => $group->getName(),
	                                'members_count' => $group->getMembers()->getCount(),
	                                'path' => $group->getGroupPath('summary'),
	                                'group' => $group
	                            );
	                            $tree['countries']['country_'.$tmpCountry->id]['children']['states']['state_'.$tmpState->id]['group_count'] ++;
	                            $tree['counts']['country_'.$tmpCountry->id] ++;
	                            $tree['counts']['state_'.$tmpState->id] ++;
	                        }
	                    } else {
	                        if ( $tmpCity->id && !in_array($tmpCity->id, $used_cities) ) {
	                            $tree['countries']['country_'.$tmpCountry->id]['children']['states']['state_'.$tmpState->id]['children']['cities']['city_'.$tmpCity->id]
	                            = array(
	                                'id' => $tmpCity->id,
	                                'name' => $tmpCity->name,
                                    'countOfEvents' => 0,
	                                'children' => array()
	                            );
	                            $used_cities[] = $tmpCity->id;
	                            $tree['counts']['city_'.$tmpCity->id] = 0;
	                        }
	                        if ( $tmpCountry->id && $tmpState->id && $tmpCity->id ) {
	                            $tree['countries']['country_'.$tmpCountry->id]['children']['states']['state_'.$tmpState->id]['children']['cities']['city_'.$tmpCity->id]['children']['groups']['group_'.$group->getId()]
	                            = array(
	                                'id' => $group->getId(),
	                                'name' => $group->getName(),
	                                'members_count' => $group->getMembers()->getCount(),
	                                'path' => $group->getGroupPath('summary'),
	                                'group' => $group
	                            );
	                            $tree['countries']['country_'.$tmpCountry->id]['children']['states']['state_'.$tmpState->id]['group_count'] ++;
	                            $tree['counts']['country_'.$tmpCountry->id] ++;
	                            $tree['counts']['state_'.$tmpState->id] ++;
	                            $tree['counts']['city_'.$tmpCity->id] ++;
	                        }
	                    }
	                }
            	}
            }
            usort($tree['countries'], 'sortHierarchyByName');
            foreach ( $tree['countries'] as &$_country ) {
                if ( isset($_country['children']['states']) ) {
                    usort($_country['children']['states'], 'sortHierarchyByName');
                    foreach ( $_country['children']['states'] as &$_state ) {
                        if ( isset($_state['children']['cities']) ) {
                            $tree['letters'][strtoupper(substr($_state['name'],0,1))] = true;
                            usort($_state['children']['cities'], 'sortHierarchyByName');
                            foreach ( $_state['children']['cities'] as &$_city ) {
                                usort($_city['children']['groups'], 'sortHierarchyByName');
                            }
                        } else {
                            usort($_state['children']['groups'], 'sortHierarchyByName');
                        }
                    }
                }
            }
        }
        $cache->save($tree,'group_hierarchy_tree_'.$this->getGroupId()."_".$this->getCategoryType()."_".$this->getCategoryFocus()."_".$cacheLetter, array('group_hierarchy_tree_'.$this->getGroupId()), 600);
        return $tree;
    }
    
    
    /**
     * Return tree of hierarchy (live hierarchy)
     * @param string $stateLetter - first letter of state name
     * @return array of hierarchy items
     * @access private
     * @author Artem Sukharev
     */
    private function getLiveTreeWithEvents($stateLetter = null, $currentTimezone, $objUser, &$eventIds = null) 
    {
        /*
         * @author Pavel Shutin
         */
        error_log('This method is depricated.', E_NOTICE);
        $tree = array();
        $tree['countries']  = array();
        $tree['counts']     = array();
        $tree['letters']    = array();
        
        $groups_count_in_city = array();
        $currentGroup = Warecorp_Group_Factory::loadById($this->getGroupId(), Warecorp_Group_Enum_GroupType::GROUP_TYPE_FAMILY);
        $groups = $currentGroup->getGroups()->setTypes(array('simple','family'))->getList();

        if ( is_array($groups) && sizeof($groups) > 0 ) {
            $used_countries             = array();
            $used_states                = array();
            $used_cities                = array();
            $tree['allow_countries']    = array();
            //  @todo надо заменить, чтобы бралось все это из базы
            if ( $this->getCategoryType() == 1 ) { //  если тип категорий - Regional
                switch ( $this->getCategoryFocus() ) {
                    case 0 :
                        $tree['allow_countries'] = null;        //  All Countries
                        break;
                    case 1 :
                        $tree['allow_countries'] = array(1, 38);  //  United States & Canada
                        break;
                    case 2 :
                        $tree['allow_countries'] = array(1);  //  United States
                        break;
                    case 3 :
                        $tree['allow_countries'] = array(38);  //  Canada
                        break;
                }
            }
            foreach ( $groups as $group ) {
                $tmpState = $group->getState();
                if ( $stateLetter === null || strtoupper(substr($tmpState->name, 0, 1)) == strtoupper($stateLetter) ) {                
                    $tmpCountry = $group->getCountry();
                    if ( null === $tree['allow_countries'] || in_array($tmpCountry->id, $tree['allow_countries']) ) {
                        if ( $tmpCountry->id && !in_array($tmpCountry->id, $used_countries) ) {
                            $tree['countries']['country_'.$tmpCountry->id] = array(
                                'id' => $tmpCountry->id,
                                'name' => $tmpCountry->name,
                                'countOfStates' => 0,
                                'countOfEvents' => 0,
                                'children' => array()                            
                            );
                            $used_countries[] = $tmpCountry->id;
                            $tree['counts']['country_'.$tmpCountry->id] = 0;
                        }
                        $tmpState = $group->getState();
                        if ( $tmpState->id && !in_array($tmpState->id, $used_states) ) {
                            $tree['countries']['country_'.$tmpCountry->id]['children']['states']['state_'.$tmpState->id]
                            = array(
                                'id' => $tmpState->id,
                                'name' => $tmpState->name,
                                'group_count' => 0,
                                'countOfEvents' => 0,
                                'children' => array()
                            );
                            $used_states[] = $tmpState->id;
                            $tree['counts']['state_'.$tmpState->id] = 0;
                            $tree['countries']['country_'.$tmpCountry->id]['countOfStates']++;
                        }
                        $tmpCity = $group->getCity();
                        $count = (isset($groups_count_in_city[$tmpCity->id])) ? $groups_count_in_city[$tmpCity->id] : count($currentGroup->getGroups()->setTypes(array('simple','family'))->getListByCity($tmpCity->id));
                        if ( $this->isNoThirdLevel() || $count < $this->getBreakAfter() ) {
                            if ( $tmpCountry->id && $tmpState->id ) {
                                /**
                                * ADD GROUP ITEM
                                */
                                $objEvents = new Warecorp_ICal_Event_List_Standard();
                                $objEvents->setTimezone($currentTimezone);
                                $objEvents->setOwnerIdFilter($group->getId());
                                $objEvents->setOwnerTypeFilter(Warecorp_ICal_Enum_OwnerType::GROUP);
                                $objEventAccessManager = Warecorp_ICal_AccessManager_Factory::create();
                                // privacy
                                if ( $objEventAccessManager->canViewPublicEvents($group, $objUser) && $objEventAccessManager->canViewPrivateEvents($group, $objUser) ) $objEvents->setPrivacyFilter(array(0,1));
                                elseif ( $objEventAccessManager->canViewPublicEvents($group, $objUser) ) $objEvents->setPrivacyFilter(array(0));
                                elseif ( $objEventAccessManager->canViewPrivateEvents($group, $objUser) ) $objEvents->setPrivacyFilter(array(1));
                                else $objEvents->setPrivacyFilter(null);
                                // sharing
                                if ( $objEventAccessManager->canViewSharedEvents($group, $objUser) ) $objEvents->setSharingFilter(array(0,1));
                                else $objEvents->setSharingFilter(array(0));
                                //
                                $objEvents->setCurrentEventFilter(true);
                                $objEvents->setExpiredEventFilter(false);
                                $arrEvents = $objEvents->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::PAIRS)->getList();
                                $eventIds = array_merge($eventIds,$arrEvents);
                                $tree['countries']['country_'.$tmpCountry->id]['countOfEvents'] = $tree['countries']['country_'.$tmpCountry->id]['countOfEvents'] + sizeof($arrEvents);
                                $tree['countries']['country_'.$tmpCountry->id]['children']['states']['state_'.$tmpState->id]['countOfEvents'] = $tree['countries']['country_'.$tmpCountry->id]['children']['states']['state_'.$tmpState->id]['countOfEvents'] + sizeof($arrEvents);
                                
                                $tree['countries']['country_'.$tmpCountry->id]['children']['states']['state_'.$tmpState->id]['children']['groups']['group_'.$group->getId()]
                                = array(
                                    'id' => $group->getId(),
                                    'name' => $group->getName(),
                                    'members_count' => $group->getMembers()->getCount(),
                                    'path' => $group->getGroupPath('summary'),
                                    'countOfEvents' => sizeof($arrEvents)   ,
                                    'group' => $group
                                );
                                $tree['countries']['country_'.$tmpCountry->id]['children']['states']['state_'.$tmpState->id]['group_count'] ++;
                                $tree['counts']['country_'.$tmpCountry->id] ++;
                                $tree['counts']['state_'.$tmpState->id] ++;
                            }
                        } else {
                            if ( $tmpCity->id && !in_array($tmpCity->id, $used_cities) ) {
                                $tree['countries']['country_'.$tmpCountry->id]['children']['states']['state_'.$tmpState->id]['children']['cities']['city_'.$tmpCity->id]
                                = array(
                                    'id' => $tmpCity->id,
                                    'name' => $tmpCity->name,
                                    'countOfEvents' => 0,
                                    'children' => array()
                                );
                                $used_cities[] = $tmpCity->id;
                                $tree['counts']['city_'.$tmpCity->id] = 0;
                            }
                            if ( $tmpCountry->id && $tmpState->id && $tmpCity->id ) {
                                /**
                                * ADD GROUP ITEM
                                */
                                $objEvents = new Warecorp_ICal_Event_List_Standard();
                                $objEvents->setTimezone($currentTimezone);
                                $objEvents->setOwnerIdFilter($group->getId());
                                $objEvents->setOwnerTypeFilter(Warecorp_ICal_Enum_OwnerType::GROUP);
                                $objEventAccessManager = Warecorp_ICal_AccessManager_Factory::create();
                                // privacy
                                if ( $objEventAccessManager->canViewPublicEvents($group, $objUser) && $objEventAccessManager->canViewPrivateEvents($group, $objUser) ) $objEvents->setPrivacyFilter(array(0,1));
                                elseif ( $objEventAccessManager->canViewPublicEvents($group, $objUser) ) $objEvents->setPrivacyFilter(array(0));
                                elseif ( $objEventAccessManager->canViewPrivateEvents($group, $objUser) ) $objEvents->setPrivacyFilter(array(1));
                                else $objEvents->setPrivacyFilter(null);
                                // sharing
                                if ( $objEventAccessManager->canViewSharedEvents($group, $objUser) ) $objEvents->setSharingFilter(array(0,1));
                                else $objEvents->setSharingFilter(array(0));
                                //
                                $objEvents->setCurrentEventFilter(true);
                                $objEvents->setExpiredEventFilter(false);
                                $arrEvents = $objEvents->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::PAIRS)->getList();
                                $eventIds = array_merge($eventIds,$arrEvents);
                                $tree['countries']['country_'.$tmpCountry->id]['countOfEvents'] = $tree['countries']['country_'.$tmpCountry->id]['countOfEvents'] + sizeof($arrEvents);
                                $tree['countries']['country_'.$tmpCountry->id]['children']['states']['state_'.$tmpState->id]['countOfEvents'] = $tree['countries']['country_'.$tmpCountry->id]['children']['states']['state_'.$tmpState->id]['countOfEvents'] + sizeof($arrEvents);
                                $tree['countries']['country_'.$tmpCountry->id]['children']['states']['state_'.$tmpState->id]['children']['cities']['city_'.$tmpCity->id]['countOfEvents'] = $tree['countries']['country_'.$tmpCountry->id]['children']['states']['state_'.$tmpState->id]['children']['cities']['city_'.$tmpCity->id]['countOfEvents'] + sizeof($arrEvents);
                                
                                $tree['countries']['country_'.$tmpCountry->id]['children']['states']['state_'.$tmpState->id]['children']['cities']['city_'.$tmpCity->id]['children']['groups']['group_'.$group->getId()]
                                = array(
                                    'id' => $group->getId(),
                                    'name' => $group->getName(),
                                    'members_count' => $group->getMembers()->getCount(),
                                    'path' => $group->getGroupPath('summary'),
                                    'countOfEvents' => sizeof($arrEvents),
                                    'group' => $group
                                );
                                $tree['countries']['country_'.$tmpCountry->id]['children']['states']['state_'.$tmpState->id]['group_count'] ++;
                                $tree['counts']['country_'.$tmpCountry->id] ++;
                                $tree['counts']['state_'.$tmpState->id] ++;
                                $tree['counts']['city_'.$tmpCity->id] ++;
                            }
                        }
                    }
                }
            }
            usort($tree['countries'], 'sortHierarchyByName');
            foreach ( $tree['countries'] as &$_country ) {
                if ( isset($_country['children']['states']) ) {
                    usort($_country['children']['states'], 'sortHierarchyByName');
                    foreach ( $_country['children']['states'] as &$_state ) {
                        if ( isset($_state['children']['cities']) ) {
                            $tree['letters'][strtoupper(substr($_state['name'],0,1))] = true;
                            usort($_state['children']['cities'], 'sortHierarchyByName');
                            foreach ( $_state['children']['cities'] as &$_city ) {
                                usort($_city['children']['groups'], 'sortHierarchyByName');
                            }
                        } else {
                            usort($_state['children']['groups'], 'sortHierarchyByName');
                        }
                    }
                }
            }
        }
        return $tree;
    }
    
    /**
     * prepare custom hierarchy tree to preview
     * @param object $h - hierarchy object
     * @param array $tree - tree
     * @param string $Letter - first letter of X level category name
     * @param int $LetterLevel - category level of first letter
     * @return array
     * @author Artem Sukharev
     * @author Pavel Shutin
     */
    static public function prepareTreeToPreview($h, $tree, $Letter = null, $LetterLevel = 3)
    {
        $globalCategories = array();
        $globalCategories['groupings'] = array();
        $globalGroupings =& $globalCategories['groupings'];

        /**
         * Pointers array
         */
        $links_array = array();

        if ( sizeof($tree) != 0 ) {
            usort($tree,'sortByLevel');
            foreach ($tree as $treeItem) {
                /**
                 * level 0 (Grouping)
                 */
                if ( $treeItem['level'] == 1 ) { //    grouping
                    $globalGroupings[$treeItem['id']] = array();
                    $globalGroupings[$treeItem['id']]['categories'] = array();
                    $globalGroupings[$treeItem['id']]['groups'] = array();
                    $globalGroupings[$treeItem['id']]['parent'] = null;
                    $globalGroupings[$treeItem['id']]['countOfCategories'] = 0;
                    $globalGroupings[$treeItem['id']]['countOfGroups'] = 0;
                    $links_array[$treeItem['id']] = &$globalGroupings[$treeItem['id']];
                }else{

                    if (!isset($links_array[$treeItem['parent']])) continue; //There is no parent in resulting tree. So all children should be skipped.

                    
                    $parent = &$links_array[$treeItem['parent']];
                    if (!isset($parent['categories'])) $parent['categories'] = array();
                    if (!isset($parent['categories'])) $parent['groups'] = array();

                    if ($treeItem['type'] == 'category') {
                        
                        if ( $Letter !== null && $treeItem['level'] == $LetterLevel && !$h->checkToPreview($treeItem['id'],$Letter)) {
                            continue;
                        }elseif (!$h->checkToPreview($treeItem['id'],null)) {
                            continue;
                        }

                        /**
                         * ADD NEW CATEGORY
                         */
                        $parent['categories'][$treeItem['id']] = array();
                        $parent['categories'][$treeItem['id']]['countOfCategories'] = 0;
                        $parent['categories'][$treeItem['id']]['countOfGroups'] = 0;
                        $parent['categories'][$treeItem['id']]['name'] = $treeItem['name'];
                        $parent['categories'][$treeItem['id']]['id'] = $treeItem['id'];
                        $parent['categories'][$treeItem['id']]['categories'] = array();
                        $parent['categories'][$treeItem['id']]['groups'] = array();
                        $parent['categories'][$treeItem['id']]['parent'] = $treeItem['parent'];
                        $links_array[$treeItem['id']] = &$parent['categories'][$treeItem['id']];
                    }else{
                        /**
                        * ADD GROUP ITEM
                        */
                        $parent['groups'][$treeItem['id']] = array();
                        $parent['groups'][$treeItem['id']]['name'] = $treeItem['group']->getName();
                        $parent['groups'][$treeItem['id']]['id'] = $treeItem['group']->getId();
                        $parent['groups'][$treeItem['id']]['catid'] = $treeItem['id'];
                        $parent['groups'][$treeItem['id']]['group'] = $treeItem['group'];
                        $parent['groups'][$treeItem['id']]['parent'] = $treeItem['parent'];
                        $links_array[$treeItem['id']] = &$parent['groups'][$treeItem['id']];
                        //$level2countOfGroups[$treeItem['parent']]++;
                    }
                    /**
                     * Update all parent counters
                     */
                    //This is simple one-level version
                    /*if ($treeItem['type'] == 'category') {
                        $parent['countOfCategories']++;
                    }else{
                        $parent['countOfGroups']++;
                    }*/
                    //This is recursive version
                    while ($parent !== null) {
                        if ($treeItem['type'] == 'category') {
                            $parent['countOfCategories']++;
                        }else{
                            $parent['countOfGroups']++;
                        }
                        $parent = &$links_array[$parent['parent']];
                    }
                }
            }
        }
        /**
         * sorting
         */
        //if (true ||  1 == $h->getGroupDisplay() ) {
            foreach ( $globalCategories['groupings'] as &$grouping ) {
                self::_previewSort($grouping['categories']);
            }
        //}

        return $globalCategories;
    }

    /**
     *
     * @param array $subtree
     * @return void
     */
    static private function _previewSort(&$subtree) {
        if (empty($subtree)) {
            return;
        }
        usort($subtree,'sortCustomPreview');
        foreach ($subtree as &$item) {
            if (is_array($item)) {
                self::_previewSort($item['categories']);
                self::_previewSort($item['groups']);
            }
        }
    }
    
    
    /**
     * prepare custom hierarchy tree to preview
     * @param object $h - hierarchy object
     * @param array $tree - tree
     * @param string $Letter - first letter of $LetterLevel level category name
     * @param string $currentTimezone - user timezone
     * @param Warecorp_User $objUser - current user
     * @param array $eventIds - array of all event Ids in tree
     * @param int $LetterLevel - Letter level category name
     * @return array
     * @author Artem Sukharev
     * @author Pavel Shutin
     */
    static public function prepareTreeToPreviewWithEvents($h, $tree, $Letter = null, $currentTimezone, $objUser, &$eventIds = null, $LetterLevel = 3)
    {
        $globalCategories = array();
        $eventIds = array();
        $globalCategories['groupings'] = array();
        $globalGroupings =& $globalCategories['groupings'];
        //var_dump($tree);

        /**
         * Pointers array
         */
        $links_array = array();

        if ( sizeof($tree) != 0 ) {
            usort($tree,'sortByLevel');

            $arrEvents = array();

            foreach ($tree as $treeItem) {


//======================================================================================================================
                /**
                 * level 0 (Grouping)
                 */
                if ( $treeItem['level'] == 1 ) { //    grouping
                    $globalGroupings[$treeItem['id']] = array();
                    $globalGroupings[$treeItem['id']]['categories'] = array();
                    $globalGroupings[$treeItem['id']]['parent'] = null;
                    $globalGroupings[$treeItem['id']]['countOfCategories'] = 0;
                    $globalGroupings[$treeItem['id']]['countOfEvents'] = 0;
                    $globalGroupings[$treeItem['id']]['countOfGroups'] = 0;
                    $links_array[$treeItem['id']] = &$globalGroupings[$treeItem['id']];
                }else{

                    if (!isset($links_array[$treeItem['parent']])) continue; //There is no parent in resulting tree. So all children should be skipped.


                    $parent = &$links_array[$treeItem['parent']];
                    if (!isset($parent['categories'])) $parent['categories'] = array();
                    if (!isset($parent['categories'])) $parent['groups'] = array();

                    if ($treeItem['type'] == 'category') {

                        if ( $Letter !== null && $treeItem['level'] == $LetterLevel && !$h->checkToPreview($treeItem['id'],$Letter)) {
                            continue;
                        }elseif (!$h->checkToPreview($treeItem['id'],null)) {
                            continue;
                        }

                        /**
                         * ADD NEW CATEGORY
                         */
                        $parent['categories'][$treeItem['id']] = array();
                        $parent['categories'][$treeItem['id']]['countOfCategories'] = 0;
                        $parent['categories'][$treeItem['id']]['countOfEvents'] = 0;
                        $parent['categories'][$treeItem['id']]['countOfGroups'] = 0;
                        $parent['categories'][$treeItem['id']]['name'] = $treeItem['name'];
                        $parent['categories'][$treeItem['id']]['id'] = $treeItem['id'];
                        $parent['categories'][$treeItem['id']]['categories'] = array();
                        $parent['categories'][$treeItem['id']]['groups'] = array();
                        $parent['categories'][$treeItem['id']]['parent'] = $treeItem['parent'];
                        $links_array[$treeItem['id']] = &$parent['categories'][$treeItem['id']];
                    }else{
                        /**
                        * ADD GROUP ITEM
                        */
                        $parent['groups'][$treeItem['id']] = array();
                        $parent['groups'][$treeItem['id']]['name'] = $treeItem['group']->getName();
                        $parent['groups'][$treeItem['id']]['id'] = $treeItem['group']->getId();
                        $parent['groups'][$treeItem['id']]['catid'] = $treeItem['id'];
                        //$parent['groups'][$treeItem['id']]['group'] = $treeItem['group'];
                        $parent['groups'][$treeItem['id']]['parent'] = $treeItem['parent'];
                        $links_array[$treeItem['id']] = &$parent['groups'][$treeItem['id']];
                        /**
                         * ADD GROUP EVENTS
                         */
                        $objEvents = new Warecorp_ICal_Event_List_Standard();
                        $objEvents->setTimezone($currentTimezone);
                        $objEvents->setOwnerIdFilter($treeItem['group']->getId());
                        $objEvents->setOwnerTypeFilter(Warecorp_ICal_Enum_OwnerType::GROUP);
                        $objEventAccessManager = Warecorp_ICal_AccessManager_Factory::create();
                        // privacy
                        if ( $objEventAccessManager->canViewPublicEvents($treeItem['group'], $objUser) && $objEventAccessManager->canViewPrivateEvents($treeItem['group'], $objUser) ) $objEvents->setPrivacyFilter(array(0,1));
                        elseif ( $objEventAccessManager->canViewPublicEvents($treeItem['group'], $objUser) ) $objEvents->setPrivacyFilter(array(0));
                        elseif ( $objEventAccessManager->canViewPrivateEvents($treeItem['group'], $objUser) ) $objEvents->setPrivacyFilter(array(1));
                        else $objEvents->setPrivacyFilter(null);
                        // sharing
                        if ( $objEventAccessManager->canViewSharedEvents($treeItem['group'], $objUser) ) $objEvents->setSharingFilter(array(0,1));
                        else $objEvents->setSharingFilter(array(0));
                        //
                        $objEvents->setCurrentEventFilter(true);
                        $objEvents->setExpiredEventFilter(false);
                        $arrEvents = $objEvents->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::PAIRS)->getList();
                        $eventIds = array_merge($eventIds,$arrEvents);

                        $parent['groups'][$treeItem['id']]['countOfEvents']    = sizeof($arrEvents);
                    }
                    /**
                     * Update all parent counters
                     */
                    //This is simple one-level version
                    /*if ($treeItem['type'] == 'category') {
                        $parent['countOfCategories']++;
                    }else{
                        $parent['countOfGroups']++;
                        $parent['countOfEvents']+=sizeof($arrEvents);
                    }*/
                    //This is recursive version
                    while ($parent !== null) {
                        if ($treeItem['type'] == 'category') {
                            $parent['countOfCategories']++;
                        }else{
                            $parent['countOfGroups']++;
                            $parent['countOfEvents']+=sizeof($arrEvents);
                        }
                        $parent = &$links_array[$parent['parent']];
                    }
                }
            }
        }
        /**
         * sorting
         */
        //if ( 1 == $h->getGroupDisplay() ) {
            foreach ( $globalCategories['groupings'] as &$grouping ) {
                self::_previewSort($grouping);
            }
        //}
        return $globalCategories;
    }
    /**
     * return opened tree
     * @author Artem Sukharev
     */
    public function getOpenTree() 
    {
        $tree = new Warecorp_Tree('zanby_groups__hierarchy_tree');
        return $tree->getOpenTree($this->getId(), '*', false);
    }
    
    /**
     * return item (grouping, category, group) information as array from tree table by id
     * @param int id
     * @return array
     *          - id
     *          - parent
     *          - lft
     *          - rgt
     *          - level
     *          - name
     *          - type (hierarchy|grouping|category|item)
     *          - group_id (if type == item)
     * @author Artem Sukharev
     */
    public function getNode($id)
    {
        $tree = new Warecorp_Tree('zanby_groups__hierarchy_tree');
        return $tree->getNode($id);
    }
    
    /**
     * returns a path to a specified node uses the tree traversal method
     * @param int $node - ID of the node to which we need to get the path
     * @param string $what - SQL field list of what to retreive into the path array
     * @param bool $including - [optional, default value = true]
     * @param bool $dropRoot - [optional, default value = false]
     * @return An associative array of all the nodes leading to the selected node
     */
    public static function getPath($node, $what = "name", $including = true, $dropRoot = false)
    {
        $tree = new Warecorp_Tree('zanby_groups__hierarchy_tree');
        return $tree->getPath($node, $what, $including, $dropRoot) ;
    }
    
    /**
     * return array of id group of hierarchy
     * @return array
     * @author Artem Sukharev
     */
    public function getHierarchyGroupIds() 
    {
        $return = array();
        $tree = new Warecorp_Tree('zanby_groups__hierarchy_tree', "type = 'item'");
        $tree_nodes = $tree->getTree($this->id);
        if ( $tree_nodes ) {
            foreach ($tree_nodes as $tree_node) {
                $return[] = $tree_node['group_id'];
            }
        }
        return $return;
    }
    
    /**
     * return constraints for hierarch type
     * for example : Live 
     *                  - Regional
     *                      - United States & Canada
     *                      - United States
     *                      - Canada
     * @return array
     * @author Artem Sukharev
     */
    public function getConstraintsAssoc($level, $parent_value = null) 
    {
        /*
        CREATE TABLE `zanby_groups__hierarchy_constraints_tree` (
          `id` int(10) unsigned NOT NULL auto_increment,
          `parent` int(10) unsigned NOT NULL,
          `lft` int(10) unsigned NOT NULL default '1',
          `rgt` int(10) unsigned NOT NULL default '2',
          `level` int(10) unsigned NOT NULL default '1',
          `name` varchar(255) default NULL,
          `value` int(11) default NULL,
          PRIMARY KEY  (`id`),
          UNIQUE KEY `ParentValueInd` (`parent`,`value`),
          KEY `rgt` (`rgt`),
          KEY `lft` (`lft`),
          KEY `parent` (`parent`),
          KEY `level` (`level`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        */
//        $tree = new Warecorp_Tree('zanby_groups__hierarchy_constraints_tree');
//        $ROOTID = $tree->add(array('name' => 'ROOT'));
//            $LiveID = $tree->add(array('name' => 'Live', 'value' => 1), $ROOTID);
//                $LiveRegionalID = $tree->add(array('name' => 'Regional', 'value' => 1), $LiveID);
//                    $tree->add(array('name' => 'All Countries', 'value' => 0), $LiveRegionalID);
//                    $tree->add(array('name' => 'United States & Canada', 'value' => 1), $LiveRegionalID);
//                    $tree->add(array('name' => 'United States', 'value' => 2), $LiveRegionalID);
//                    $tree->add(array('name' => 'Canada', 'value' => 3), $LiveRegionalID);
//            $CustomID = $tree->add(array('name' => 'Custom', 'value' => 2), $ROOTID);
//                $CustomCustomID = $tree->add(array('name' => 'Custom', 'value' => 0), $CustomID);
//                    $tree->add(array('name' => 'Custom', 'value' => 0), $CustomCustomID);
//                $CustomRegionalID = $tree->add(array('name' => 'Regional', 'value' => 1), $CustomID);
//                    $tree->add(array('name' => 'All Countries', 'value' => 0), $CustomRegionalID);
//                    $tree->add(array('name' => 'United States & Canada', 'value' => 1), $CustomRegionalID);
//                    $tree->add(array('name' => 'United States', 'value' => 2), $CustomRegionalID);
//                    $tree->add(array('name' => 'Canada', 'value' => 3), $CustomRegionalID);

//        exit;

        
        $tree_array = array();
        $parent = null;
        if ( $parent_value !== null ) {
            $parent_level = $level - 1;
            if ( $parent_level < 0 ) return $tree_array;
            $query = $this->db->select();
            $query->from('zanby_groups__hierarchy_constraints_tree', 'id');
            $query->where('level = ?', $parent_level);
            $query->where('value = ?', $parent_value);
            $parent = $this->db->fetchOne($query);
            if ( !$parent ) return $tree_array;
        }
        $tree = new Warecorp_Tree('zanby_groups__hierarchy_constraints_tree', 'level = '.$level);
        if ( $parent ) {
            return $tree->getTree($parent, '*', false);
        } else {
            return $tree->getTree(1, '*', false);
        }
    }
    
    /**
     * swap two items in tree
     * @return void
     * @author Artem Sukharev
     */
    public function swap($id1, $id2) {
        $tree = new Warecorp_Tree('zanby_groups__hierarchy_tree');
        $tree->swap($id1, $id2);
    }
    
    /**
     * generate javascript for building tree of LIVE Hierarchy
     * @param Warecorp_Group_Family $currentGroup
     * @return string
     * @author Artem Sukharev
     */
    public function getJSLiveTree($currentGroup) 
    {
        Warecorp::loadSmartyPlugin('modifier.utf8truncate.php');
        $tree = array();
        $tree['countries']  = array();
        $tree['states']     = array();
        $tree['cities']     = array();
        $tree['groups']     = array();

        $tree_count = array();
        $groups_count_in_city = array();
        $groups = $currentGroup->getGroups()->setTypes(array('simple','family'))->getList();

        if ( is_array($groups) && sizeof($groups) > 0 ) {
            $used_countries             = array();
            $used_states                = array();
            $used_cities                = array();
            $tree['allow_countries']    = array();
            // FIXME надо заменить, чтобы бралось все это из базы
            if ( $this->getCategoryType() == 1 ) { //  если тип категорий - Regional
                switch ( $this->getCategoryFocus() ) {
                    case Warecorp_Group_Hierarchy_Enum::CATEGORY_FOCUS_ALL : 
                        $tree['allow_countries'] = null;  //  All Countries
                        break;
                    case Warecorp_Group_Hierarchy_Enum::CATEGORY_FOCUS_USA_CANADA :
                        $tree['allow_countries'] = array(1, 38);  //  United States & Canada
                        break;
                    case Warecorp_Group_Hierarchy_Enum::CATEGORY_FOCUS_USA :
                        $tree['allow_countries'] = array(1);  //  United States
                        break;
                    case Warecorp_Group_Hierarchy_Enum::CATEGORY_FOCUS_CANADA :
                        $tree['allow_countries'] = array(38);  //  Canada
                        break;
                }
            }
            foreach ( $groups as $group ) {
                $tmpCountry = $group->getCountry();
                if ( null === $tree['allow_countries'] || in_array($tmpCountry->id, $tree['allow_countries']) ) {
                    if ( $tmpCountry->id && !in_array($tmpCountry->id, $used_countries) ) {
                        $tree['countries'][] = array(
                            'id' => $tmpCountry->id,
                            'name' => smarty_modifier_utf8truncate(str_replace("'", "\'", str_replace("\\", "\\\\", $tmpCountry->name)),27),
                            'jsnode' => 'country_'.$tmpCountry->id,
                            'parent' => null,
                            'parent_jsnode' => 'root_node',
                            'level' => 1
                        );
                        $used_countries[] = $tmpCountry->id;
                        $tree_count['country_'.$tmpCountry->id] = 0;
                    }
                    $tmpState = $group->getState();
                    if ( $tmpState->id && !in_array($tmpState->id, $used_states) ) {
                        $tree['states'][] = array(
                            'id' => $tmpState->id,
                            'name' => smarty_modifier_utf8truncate(str_replace("'", "\'", str_replace("\\", "\\\\", $tmpState->name)),27),
                            'jsnode' => 'state_'.$tmpState->id,
                            'parent' => $tmpCountry->id,
                            'parent_jsnode' => 'country_'.$tmpCountry->id,
                            'level' => 2
                        );
                        $used_states[] = $tmpState->id;
                        $tree_count['state_'.$tmpState->id] = 0;
                    }
                    $tmpCity = $group->getCity();
                    $count = (isset($groups_count_in_city[$tmpCity->id])) ? $groups_count_in_city[$tmpCity->id] : count($currentGroup->getGroups()->setTypes(array('simple','family'))->getListByCity($tmpCity->id));
                    if ( $this->isNoThirdLevel() || $count < $this->getBreakAfter() ) {
                        if ( $tmpCountry->id && $tmpState->id ) {
                            $tree['groups'][] = array(
                                'id' => $group->getId(),
                                'name' => smarty_modifier_utf8truncate(str_replace("'", "\'", str_replace("\\", "\\\\", $group->getName())),27),
                                'jsnode' => 'group_'.$tmpCountry->id,
                                'parent' => $tmpState->id,
                                'parent_jsnode' => 'state_'.$tmpState->id,
                                'level' => 3
                            );
                            $tree_count['country_'.$tmpCountry->id] ++;
                            $tree_count['state_'.$tmpState->id] ++;
                        }
                    } else {
                        if ( $tmpCity->id && !in_array($tmpCity->id, $used_cities) ) {
                            $tree['cities'][] = array(
                                'id' => $tmpCity->id,
                                'name' => smarty_modifier_utf8truncate(str_replace("'", "\'", str_replace("\\", "\\\\", $tmpCity->name)),27),
                                'jsnode' => 'city_'.$tmpCity->id,
                                'parent' => $tmpState->id,
                                'parent_jsnode' => 'state_'.$tmpState->id,
                                'level' => 3
                            );
                            $used_cities[] = $tmpCity->id;
                            $tree_count['city_'.$tmpCity->id] = 0;
                        }
                        if ( $tmpCountry->id && $tmpState->id && $tmpCity->id ) {
                            $tree['groups'][] = array(
                                'id' => $group->getId(),
                                'name' => smarty_modifier_utf8truncate($group->getName(),27),
                                'jsnode' => 'group_'.$tmpCountry->id,
                                'parent' => $tmpCity->id,
                                'parent_jsnode' => 'city_'.$tmpCity->id,
                                'level' => 4
                            );
                            $tree_count['country_'.$tmpCountry->id] ++;
                            $tree_count['state_'.$tmpState->id] ++;
                            $tree_count['city_'.$tmpCity->id] ++;
                        }
                    }
                }
            }
            usort($tree['countries'],   'sortHierarchyByName');
            usort($tree['states'],      'sortHierarchyByName');
            usort($tree['cities'],      'sortHierarchyByName');
            usort($tree['groups'],      'sortHierarchyByName');
        }
        $Script = "tree_0 = new YAHOO.widget.TreeView('tree_div_0');";
        $Script .= "var root_node = tree_0.getRoot();";
        foreach ( $tree['countries'] as $item ) {
            $Script .= $item['jsnode']." = new YAHOO.widget.TextNode('".$item['name']." (".$tree_count[$item['jsnode']].")', ".$item['parent_jsnode'].", true);";
            $Script .= $item['jsnode'].'.labelStyle = "";';
        }
        foreach ( $tree['states'] as $item ) {
            $Script .= $item['jsnode']." = new YAHOO.widget.TextNode('".$item['name']." (".$tree_count[$item['jsnode']].")', ".$item['parent_jsnode'].", true);";
            $Script .= $item['jsnode'].'.labelStyle = "";';
        }
        foreach ( $tree['cities'] as $item ) {
            $Script .= $item['jsnode']." = new YAHOO.widget.TextNode('".$item['name']." (".$tree_count[$item['jsnode']].")', ".$item['parent_jsnode'].", true);";
            $Script .= $item['jsnode'].'.labelStyle = "";';
        }
        foreach ( $tree['groups'] as $item ) {
            $Script .= $item['jsnode']." = new YAHOO.widget.TextNode('".$item['name']."', ".$item['parent_jsnode'].", true);";
            $Script .= $item['jsnode'].'.labelStyle = "znbHierarchyCategoryGroupLabel";';
        }
        $Script .= "tree_0.draw();";

        return $Script;

    }

    /**
     * generate javascript for building tree of Custom Hierarchy for current grouping
     * @param array $grouping
     * @return string
     * @author Artem Sukharev
     */
    public function getJSCategoryTree($grouping) {
        Warecorp::loadSmartyPlugin('modifier.utf8truncate.php');
        $Script = "";
        $tree_name = "tree_" . $grouping['id'];
        $tree_targete = "tree_div_" . $grouping['id'];
        $Script .= $tree_name." = new YAHOO.widget.TreeView('".$tree_targete."');";
        $Script .= "treeCollection[treeCollection.length] = ".$tree_name.";";
        $Script .= "var el_".$grouping['id']." = ".$tree_name.".getRoot();";
        $tree_categories = $this->getCategoryTree($grouping['id']);
        if ( sizeof($tree_categories) != 0 ) {
            foreach ( $tree_categories as $category ) {
                if ( $category['type'] == 'category' ) {
                    $label = $this->getJSCategoryLabel($category, $tree_name, $div_name);
                    $Script .= "categoryObj = { label : '".$label."', catid : '".$category['id']."', level : '".($category['level'] - 1)."', divid : '".$div_name."', groupid: '".$grouping['id']."'};";
                    $Script .= "el_".$category['id']." = new YAHOO.widget.TextNode(categoryObj, el_".$category['parent'].", true);";
                    $Script .= "el_".$category['id'].".labelStyle = '';";
                } elseif ( $category['type'] == 'item' ) {
                    $g = Warecorp_Group_Factory::loadById($category['group_id'],Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE);
                    //  @todo сделать проверку на существование группы
                    $label = $this->getJSItemLabel($category, $tree_name, $g, $div_name);

                    $Script .= "categoryObj = { label : '".$label."', divid : '".$div_name."', treeObj : tree_".$grouping['id'].", groupID : '".$g->getId()."'};";
                    $Script .= "el_".$category['id']." = new YAHOO.widget.TextNode(categoryObj, el_".$category['parent'].", true);";
                    $Script .= "el_".$category['id'].".labelStyle = '';";

                    $Script .= "var dragged = new TreeDDProxy('treegroupitems".$category['id']."');";
                    $Script .= "dragged.relatedNode = el_".$category['id'].";";
                    $Script .= "dragged.parentNode = el_".$category['parent'].";";
                    $Script .= "dragged.groupID = '".$g->getId()."';";
                    $Script .= "dragged.groupName = '".smarty_modifier_utf8truncate(str_replace("'", "\'", str_replace("\\", "\\\\", $g->getName())),27)."';";
                }
            }
        }
        $Script .= $tree_name.".draw();";
        return $Script;
    }
    
    /**
     * generate text label for current category
     * @param array $category - category info
     * @param string $tree_name - name of tree
     * @param string $div_name - name of div element
     * @return string
     * @author Artem Sukharev
     */
    public function getJSCategoryLabel($category, $tree_name, &$div_name) {
        Warecorp::loadSmartyPlugin('modifier.utf8truncate.php');
        $div_name = "target_catedory_".$category['id'];
        if ( in_array($category["level"] - 1, $this->allowedCategoryDepth) ) {
            $label =    '<div id="'.$div_name.'" class="znbHierarchyCategoryLabel"' .
                        ' onMouseUp="targetMouseUp(this, '.$tree_name.');"' .
                        ' onMouseOver="targetMouseOver(this);"' .
                        ' onMouseOut="targetMouseOut(this);"' .
                        '>' . smarty_modifier_utf8truncate(htmlspecialchars(str_replace("'","\'",str_replace("\\", "\\\\", $category["name"]))),27) .
                        '</div>';
        } else {
            $label = $category['name'];
        }
        return $label;
    }
    
    /**
     * generate text label for current group in tree
     * @param array $category - category info
     * @param string $tree_name - name of tree
     * @param Warecorp_Group_Family $group
     * @param string $div_name - name of div element
     * @return string
     * @author Artem Sukharev
     */
    public function getJSItemLabel($category, $tree_name, $group, &$div_name) {
        Warecorp::loadSmartyPlugin('modifier.utf8truncate.php');
        $div_name = 'treegroupitems'.$category['id'];
        $label =    '<div class="znbHierarchyCategoryGroupLabel" id="'.$div_name.'"'.
                    ' onMouseUp="targetItemMouseUp(this, '.$tree_name.');"' .
                    ' onMouseOver="targetItemMouseOver(this);"' .
                    ' onMouseOut="targetItemMouseOut(this);"' .
                    '>' . smarty_modifier_utf8truncate(htmlspecialchars(str_replace("'","\'",str_replace("\\", "\\\\", $group->getName()))),27) .
                    '<div id="'.$div_name.'_selector" style="display:none;" align="left" class="znbHierarchyUnderline"><div><div><img src="/images/hierarchy/pix.gif" border="0"></div></div></div>' .
                    '</div>';
        return $label;
    }
}

/**
 *
 */
function sortHierarchyByName($a, $b)
{
    return strcasecmp($a["name"], $b["name"]);
}

/**
 *
 */
function sortCustomPreview($a, $b)
{
    return strcasecmp($a["name"], $b["name"]);
}

/**
 * sort tree plain array by level
 */
function sortByLevel($a, $b)
{
    if ($a['level'] == $b['level']) return 0;
    else return ($a['level']>$b['level']) ? 1 : -1;
}