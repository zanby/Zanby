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
 * @package    Warecorp
 * @copyright  Copyright (c) 2006
 * @author     Artem Sukharev
 */

/**
 * Tree Class
 *
 */
class BaseWarecorp_Tree
{
    public $_db;

    private $table = null;

    /**
     * Class Constructor
     * @param string $table
     * @param string $condition
     * @param int $limitStart
     * @param int $limitSet
     */
    public function __construct($table = "tree", $condition = null, $limitStart = null, $limitSet = null)
    {
        $this->_db = Zend_Registry::get("DB");
        $this->setTable($table);
        $this->setCondition($condition);
        $this->setLimit($limitStart, $limitSet);
    }
    public function setTable($table_name)
    {
        $this->table = $table_name;
    }
    public function getTable()
    {
        return $this->table;
    }
    function setCondition($condition)
    {
        $this->condition = $condition;
    }
    public function getCondition()
    {
        if($this->condition){
            return $this->condition;
        }
    }
    public function setLimit($limitStart, $limitSet)
    {
        $this->limitStart 	= (int)$limitStart;
        $this->limitSet		= (int)$limitSet;
    }
    public function getLimitStart()
    {
        return $this->limitStart;
    }
    public function getLimitSet()
    {
        return $this->limitSet;
    }
    public function getLimitClause(&$select)
    {
        if(is_int($this->getLimitStart()) and is_int($this->getLimitSet()) and $this->getLimitSet()>0){
            $select->limit($this->getLimitSet(), $this->getLimitStart());
        }
    }
    public function getLimitClauseAsString()
    {
        if(is_int($this->getLimitStart()) and is_int($this->getLimitSet()) and $this->getLimitSet()>0){
            return ' LIMIT ' . $this->getLimitStart() . ',' . $this->getLimitSet();
        }
        return '';
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $node
     * @param unknown_type $what
     * @param unknown_type $including
     * @return unknown
     */
    public function buildTree($node = "full", $what = "*", $including = true){

        if ($node == "full"){
            $select = $this->_db->select()->from($this->getTable(), 'id')->where('parent IS NULL');
            $home = $this->_db->fetchCol($select);
            if (count($home) > 0){
                $tree = array();
                for ($i = 0; $i < count($home); $i++){
                    $tmp_tree = $this->getTree($home[$i], $what, $including);
                    foreach ($tmp_tree as $_node){
                        $tree[] = $_node;
                    }
                }
                return $tree;
            } else {
                return false;
            }
        } else {
            if (is_int($node) && $node > 0){
                return $this->getTree($node, $what, $including);
            } else {
                return false;
            }
        }
    }
    /**
     * Enter description here...
     *
     * @param unknown_type $node
     * @param unknown_type $what
     * @param unknown_type $including
     * @return unknown
     */
    public function getTree($node = 1, $what = "*", $including = true)
    {
        //get this node first
        $node = $this->getPosition($node);

        //check if the node exists actually ;)
        if( !$node ){
            return array();
        }
        $select = $this->_db->select();
        $select->from($this->getTable(), $what)->where('lft BETWEEN :left AND :right');
        if ( !$including ) {
            $select->where('id != ?', $node['id']);
        }
        if ( $this->getCondition() ) {
           $select->where($this->getCondition());
        }
        $select->order('lft ASC');
        $this->getLimitClause($select);
        $params = array('left' => $node['lft'], 'right' => $node['rgt']);
        $res = $this->_db->fetchAll($select, $params);

        return $res;
    }
    /**
     * Enter description here...
     *
     * @param unknown_type $node
     * @param unknown_type $what
     * @param unknown_type $including
     * @param unknown_type $one_sheet
     * @return unknown
     */
    public function getOpenTree($node = "", $what = "*", $including = true, $one_sheet = false)
    {
        if( $node == "" ){
            $sql_home = "select " . $what . " FROM " . $this->getTable() . " WHERE parent IS NULL";
            return $this->_db->fetchAll($sql_home);
        }
        $path = $this->getPath($node, "id", $including = true, $dropRoot = false);
        $node = $this->getPosition($node);
        $where = " (((lft BETWEEN " . $node['lft'] . " AND " . $node['rgt'] . ") AND level = " . $node['level'] . " + 1) ";
        for ( $i = 0; $i < count($path); $i++ ){
            $el_path_node = $this->getPosition($path[$i]["id"]);
            $where .= " OR ((lft BETWEEN " . $el_path_node['lft'] . " AND " . $el_path_node['rgt'] . ") AND level <= " . $el_path_node['level'] . " + 1) ";
        }
        $where .= " OR (level = 0)) ";
        if( !$including ){
            $where .= " AND (id != " . $node['id'] . ")";
        }
        if ($one_sheet){
            $where .= " AND ( rgt != lft + 1) ";
        }
        $sql = "SELECT " . $what . " FROM " . $this->getTable() . " where " . $where;
        if ( $this->getCondition() ) $sql .= ' AND ' . $this->getCondition();
        $sql .= " ORDER BY lft ASC " . $this->getLimitClauseAsString();
        $result = $this->_db->query($sql);
        return $result->fetchAll();
    }
    /**
     * returns a path to a specified node uses the tree traversal method
     * @param int $node - ID of the node to which we need to get the path
     * @param string $what - SQL field list of what to retreive into the path array
     * @param bool $including - [optional, default value = true]
     * @param bool $dropRoot - [optional, default value = false]
     * @return An associative array of all the nodes leading to the selected node
     */
    public function getPath($node, $what = "title", $including = true, $dropRoot = false)
    {
        //get this node first
        if( !$node = $this->getPosition($node) ){
            return array();
        }
        //now get the path in form of an array
        if($including){
            $sql = "SELECT " . $what . " FROM " . $this->getTable() . " WHERE lft <= " . $node['lft'] . " AND rgt >= " . $node['rgt'];
            if ( $this->getCondition() ) $sql .= ' AND ' . $this->getCondition();
            $sql .= '  ORDER BY lft ASC';
        }else{
            $sql = "SELECT " . $what . " FROM " . $this->getTable() . " WHERE lft < " . $node['lft'] . " AND rgt > " . $node['rgt'];
            if ( $this->getCondition() ) $sql .= ' AND ' . $this->getCondition();
            $sql .= '  ORDER BY lft ASC';
        }
        $result = $this->_db->fetchAll($sql);
        //
        if($dropRoot){
            array_shift($result);
        }
        return $result;
    }
    /**
     * returns the parent node of the current node uses the adjacency method.
     * @param int $node - ID of the node of which one needs to get the parent of
     * @param string $what - SQL Field list of what to retreive into the array
     * @return An associative array of data relevant to the parent node
     */
    public function getParent($node, $what = "*")
    {
        $node = $this->getPosition($node);
        $sql = "SELECT " . $what . " FROM " . $this->getTable() . " WHERE id = ".$node['parent']."";
        if ( $this->getCondition() ) $sql .= ' AND ' . $this->getCondition();
        return $this->_db->fetchAll($sql);
    }
    //***********
    public function getParentRoot($node)
    {
        $root = $this->getPath($node, $what = "id", $including = false, $dropRoot = false);
        if (count($root) == 0){
            return $node;
        }else{
            return $root[0]["id"];
        }
    }
    /**
     * returns a set of all the nearest children of this node not going into any deeper levels of hierarchy. Uses the near adjacency method
     * @param int $node - ID of the node from which to return first level child set from
     * @param string $what - SQL Field list of what to retreive into the array
     * @return An array of associative arrays containing all the first level children of the selected node
     */
    public function getChildren($node, $what = "*")
    {
        $sql = "SELECT " . $what . " FROM " . $this->getTable() . " WHERE parent = " . $node . "";
        if ( $this->getCondition() ) $sql .= ' AND ' . $this->getCondition();
        return $this->_db->fetchAll($sql);
    }
    /**
     * returns an array of ids of all the subnodes of a given node
     * @param int $node
     * @param string $what - [optional, default value = "id"]
     * @param bool $including - [optional, default value = true]
     * @return array - with IDs of all the subnodes of a given node
     */
    public function getSubNodes($node, $what = "id", $including = true)
    {
        $node = $this->getPosition($node);

        if( $including ){
            $sql = "SELECT " . $what . " FROM " . $this->getTable() . " WHERE lft >= " . $node['lft'] . " AND rgt <= " . $node['rgt'];
            if ( $this->getCondition() ) $sql .= ' AND ' . $this->getCondition();
        }else{
            $sql = "SELECT " . $what . " FROM " . $this->getTable() . " WHERE lft > " . $node['lft'] . " AND rgt < " . $node['rgt'];
            if ( $this->getCondition() ) $sql .= ' AND ' . $this->getCondition();
        }
        return $this->_db->fetchAll($sql);
    }
    function getNode($node, $what = "*")
    {
        return $this->_db->fetchRow("SELECT " . $what . " FROM " . $this->getTable() . " WHERE id = " . $node . "");
    }
    /**
     * get the absolute position in the tree of the specified node returns an array with the traversal and adjacenct parameters
     * @param int$node - ID of the node, of which position is to be returned
     * @return An associative array listing the Left Traverse, The Right Traverse and the Parent ID the Level and the ID of the selected node
     */
    private function getPosition($node)
    {
        if( !$node ){
            return false;
        }
        $select = $this->_db->select();
        $select->from($this->getTable(), array('lft', 'rgt', 'parent', 'level', 'id'))
               ->where('id =?', $node);
        $res = $this->_db->fetchRow($select);

        //$sql = "SELECT lft, rgt, parent, level, id FROM " . $this->getTable() . " WHERE id = $node";
        //$res = $this->db->execute($sql);
        return $res;
    }
    /**
     * recursive converter that will traverse the tree based on the adjacency method and store the relevant data in the database
     * @param int $parent - ID of the parent node [optional, default value = 1]
     * @param int $left - Identifier of the current left adjacency method [optional, default value = 1]
     * @param int $level - Deepness level of the current node [optional, default value = 0]
     * @return Deepness level of the current node
     */
    private function traverse($parent = 1, $left = 1, $level = 0)
    {
        // the right value of this node is the left value + 1
        $right      = $left + 1;
        $newlevel   = $level + 1;

        // get all children of this node
        $sql = "SELECT id FROM " . $this->getTable() . " WHERE parent = " . $parent;
        if ( $this->getCondition() ) $sql .= ' AND ' . $this->getCondition();
        $result = $this->_db->fetchCol($sql);

        //go through all the children
        foreach($result as $row){
            $right = $this->traverse($row, $right, $newlevel);
        }

        //update the database record
        $sql = "UPDATE " . $this->getTable() . " SET lft = " . $left . ", rgt = " . $right . ", level = " . $level . " WHERE id = " . $parent . "";
        $this->_db->query($sql);

        // return the right value of this node + 1
        return $right + 1;
    }
    /**
     * adds a node to the tree as a child of the specified node, if parent is not specified then a root node is added
     * @param array $data - Associative array of SQL datasets where keys represent fields names
     * @param int $parent - ID of the parent node to which the child has to be added [optional, default value = null]
     * @return int on success and false on failure
     */
    public function add($data, $parent = null)
    {
        if( $parent == null ){
            $select = $this->_db->select()->from($this->getTable(), array('max' => new Zend_Db_Expr('MAX(rgt)')));
            $max = $this->_db->fetchOne($select);
            if ($max == 0){
                $lft_in = 1;
                $rgt_in = 2;
            } else {
                $lft_in = $max + 1;
                $rgt_in = $lft_in + 1;
            }
            $data['lft']    = $lft_in;
            $data['rgt']    = $rgt_in;
            $data['level']  = 0;
            $this->_db->insert($this->getTable(), $data);
            $last_id = $this->_db->lastInsertId();
        }else{
            $node = $this->getPosition($parent);

            $sql = 'UPDATE ' . $this->getTable() . ' SET rgt = rgt + 2 WHERE rgt >= :right';
            if ( $this->getCondition() ) $sql .= ' AND '.$this->getCondition();
            $result = $this->_db->query($sql, array('right' => $node['rgt']));

            $sql = 'UPDATE ' . $this->getTable() . ' SET lft = lft + 2 WHERE lft > :right';
            if ( $this->getCondition() ) $sql .= ' AND '.$this->getCondition();
            $result = $this->_db->query($sql, array('right' => $node['rgt']));

            $data['lft']    = (int)$node['rgt'];
            $data['rgt']    = (int)$node['rgt'] + 1;
            $data['level']  = (int)$node['level'] + 1;
            $data['parent']  = $parent;
            $this->_db->insert($this->getTable(), $data);
            $last_id = $this->_db->lastInsertId();
        }
        return $last_id;
    }
    /**
     * removes the specified node from the tree and all its children!
     * @param int $node - ID of the node which has to be removed
     */
    public function remove($node)
    {
        //get the node first
        $node = $this->getPosition($node);

        //delete node
        $sql = 'DELETE FROM  ' . $this->getTable() . ' WHERE rgt <= :right AND lft >= :left';
        if ( $this->getCondition() ) $sql .= ' AND '.$this->getCondition();
        $result = $this->_db->query($sql, array('right' => $node['rgt'], 'left' => $node['lft']));

        $sql = 'UPDATE ' . $this->getTable() . ' SET rgt = rgt - (? - ? + 1) WHERE rgt > ?';
        if ( $this->getCondition() ) $sql .= ' AND '.$this->getCondition();
        $sql = $this->_db->quoteInto($sql, $node['rgt'], $node['lft'], $node['rgt'] );
        $this->_db->query($sql);


        $sql = 'UPDATE ' . $this->getTable() . ' SET lft = lft - (? - ? + 1) WHERE lft > ?';
        if ( $this->getCondition() ) $sql .= ' AND '.$this->getCondition();
        $sql = $this->_db->quoteInto($sql, $node['rgt'], $node['lft'], $node['lft'] );
        $this->_db->query($sql);

        //$sql = 'OPTIMIZE TABLE ' . $this->getTable() . '';
        //$result = $this->_db->query($sql);
    }
    /**
     * removes the specified node from the tree and all its children!
     * @param int $node - ID of the node which has to be removed
     */
    public function removeChildren($node)
    {
        //get the node first
        $node = $this->getPosition($node);

        //delete node
        $sql = 'DELETE FROM  ' . $this->getTable() . ' WHERE rgt < :right AND lft > :left';
        if ( $this->getCondition() ) $sql .= ' AND '.$this->getCondition();
        $result = $this->_db->query($sql, array('right' => $node['rgt'], 'left' => $node['lft']));

        $sql = 'UPDATE ' . $this->getTable() . ' SET rgt = rgt - ('
        . $this->_db->quote($node['rgt']) . ' - '
        . $this->_db->quote($node['lft']). ' + 1) + 2 WHERE rgt >= '
        . $this->_db->quote($node['rgt']);
        if ( $this->getCondition() ) $sql .= ' AND '.$this->getCondition();
        $this->_db->query($sql);


        $sql = 'UPDATE ' . $this->getTable() . ' SET lft = lft - ('
        . $this->_db->quote($node['rgt']) . ' - '
        . $this->_db->quote($node['lft']) . ' + 1) + 2 WHERE lft > '
        . $this->_db->quote($node['lft']);
        if ( $this->getCondition() ) $sql .= ' AND '.$this->getCondition();
        $this->_db->query($sql);

        //$sql = 'OPTIMIZE TABLE ' . $this->getTable() . '';
        //$result = $this->_db->query($sql);
    }
    /**
     * moves portions of a tree from the source node to destination node
     * @param int $source - ID of the source node
     * @param int $destination - ID of the destination node
     * @return false on error true on completion
     * @todo некорректно как то работает - проверить
     */
    public function move($source, $destination)
    {
        //get the nodes
        $source = $this->getPosition($source);
        $destination = $this->getPosition($destination);

        //check that the nodes exist
        if(!$source or !$destination){
            return false;
        }

        //check that it is a valid move
        if($source['lft'] == $destination['lft'] or ($destination['lft'] >= $source['lft'] and $destination['lft'] <= $source['rgt'])){
            return false;
        }

        //decide where to move, left or right
        if($destination['lft'] < $source['lft']) {	# left
            $sql = 'UPDATE ' . $this->getTable() . ' SET '
            .'level = IF(lft BETWEEN '.$source['lft'].' AND '.$source['rgt'].', level -'. $source['level'] . ' + 1 + ' . $destination['level'] . ', level), '
            .'lft = IF(lft BETWEEN '.$destination['rgt'].' AND '.($source['lft'] - 1).', lft+'.($source['rgt'] - $source['lft'] + 1).', '
            . 'IF(lft BETWEEN '.$source['lft'].' AND '.$source['rgt'].', lft-'.($source['lft'] - $destination['rgt']).', lft) '
            . '), '
            . 'rgt = IF(rgt BETWEEN '.$destination['rgt'].' AND '.$source['lft'].', rgt+'.($source['rgt'] - $source['lft'] + 1).', '
            . 'IF(rgt BETWEEN '.$source['lft'].' AND '.$source['rgt'].', rgt-'.($source['lft']-$destination['rgt']).', rgt) '
            . ') '
            . 'WHERE lft BETWEEN '.$destination['lft'].' AND '.$source['rgt'];
            if ( $this->getCondition() ) $sql .= ' AND ' . $this->getCondition();
        } else { #right
            $sql = 'UPDATE ' . $this->getTable() . ' SET '
            .'level = IF(lft BETWEEN '.$source['lft'].' AND '.$source['rgt'].', level -'. $source['level'] . ' + 1 + ' . $destination['level'] . ', level), '
            .'lft = IF(lft BETWEEN '.$source['rgt'].' AND '.$destination['rgt'].', lft-'.($source['rgt'] - $source['lft'] + 1).', '
            . 'IF(lft BETWEEN '.$source['lft'].' AND '.$source['rgt'].', lft+'.($destination['rgt'] - 1 - $source['rgt']).', lft)'
            . '), '
            .'rgt = IF(rgt BETWEEN '.($source['rgt'] + 1).' AND '.($destination['rgt'] - 1).', rgt-'.($source['rgt'] - $source['lft'] + 1).', '
            . 'IF(rgt BETWEEN '.$source['lft'].' AND '.$source['rgt'].', rgt+'.($destination['rgt'] - 1 - $source['rgt']).', rgt) '
            . ') '
            . 'WHERE lft BETWEEN '.$source['lft'].' AND '.$destination['rgt'];
            if ( $this->getCondition() ) $sql .= ' AND ' . $this->getCondition();
        }


        //change parent reference
        $updateSql = 'UPDATE ' . $this->getTable() . ' SET parent = :destination WHERE id = :source';
        $result = $this->_db->query($updateSql, array('destination' => $destination['id'], 'source' => $source['id']));

        //execute query
        return $result = $this->_db->query($sql);
    }
    /**
     * swaps two nodes on the tree places
     * @param unknown_type $node1 - ID of the node1
     * @param unknown_type $node2 - ID of the node2
     * @return false on error true on completion
     */
    public function swap($node1, $node2)
    {
        $node1 = $this->getPosition($node1);
        $node2 = $this->getPosition($node2);

        if($node1 and $node2 and $node1['parent'] == $node2['parent']){	//bogus check

            //make sure the call is always interpreted as a move LEFT
            if($node1['lft'] > $node2['rgt']){
                $tnode = $node1;
                $node1 = $node2;
                $node2 = $tnode;
            }

            $sql = 'UPDATE ' . $this->getTable() . ' SET '
            .'lft = IF(lft BETWEEN '.$node2['lft'].' AND '.$node2['rgt'].',lft - '. ($node2['lft'] - $node1['lft']) .',IF(lft BETWEEN '.$node1['lft'].' AND '.$node1['rgt'].',lft + '. ($node2['rgt'] - $node1['rgt']) .',IF(lft BETWEEN '.$node1['rgt'].' AND '.$node2['lft'].',lft + '. (($node2['rgt'] - $node2['lft'] - 1) - ($node1['rgt'] - $node1['lft'] - 1)) .',lft))), '
            .'rgt = IF(rgt BETWEEN '.$node2['lft'].' AND '.$node2['rgt'].',rgt - '. ($node2['lft'] - $node1['lft']) .',IF(rgt BETWEEN '.$node1['lft'].' AND '.$node1['rgt'].',rgt + '. ($node2['rgt'] - $node1['rgt']) .',IF(rgt BETWEEN '.$node1['rgt'].' AND '.$node2['lft'].',rgt + '. (($node2['rgt'] - $node2['lft'] - 1) - ($node1['rgt'] - $node1['lft'] - 1)) .',rgt))) '
            .'WHERE lft BETWEEN '.$node1['lft'].' AND '.$node2['rgt'];

            return $result = $this->_db->query($sql);
        }else{
            return false;
        }
    }
}
