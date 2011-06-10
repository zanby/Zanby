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
 *
 * @package    Warecorp_Category
 * @copyright  Copyright (c) 2006, 2008
 * @author Dmitry Kostikov
 * @author Aleksei Gusev
 */

/**
 * group category class
 *
 */
class BaseWarecorp_Group_Category extends Warecorp_Data_Entity
{
    public $id;
    public $name;
    /**
     * Constructor.
     * @var int $id - category id
     */
    public function __construct($id = false)
    {
	parent::__construct('zanby_groups__categories');
	$this->addField('id');
	$this->addField('name');
	$this->loadByPk($id);
    }
    /*
     * set name property
     * @author Aleksei Gusev
     */
    public function setName( $category)
    {
	$this->name = $category;
	return $this;
    }

    /**
     * get name property
     * @author Aleksei Gusev
     */
    public function getName()
    {
	    return $this->name;
    }
    
    public static function getEventCategory($categoryId)
    {
        $db = Zend_Registry::get("DB");
        $sql = "SELECT event_cathegory_ref_id from zanby_groups__categories where id='".$categoryId."'";       
        $data = $db->fetchAll($sql);
        if (isset($data[0]['event_cathegory_ref_id'])){
            return $data[0]['event_cathegory_ref_id'];
        }
        else {
            return 0;
        }
    }
    
    public static function findIdByName($name)
    {
        
        $db = Zend_Registry::get("DB");                                              
        $sql = "SELECT id from zanby_groups__categories where name=".$db->quote($name);       
        $result  = $db->fetchRow($sql);
        if ( !$result ) return null;
        return $result['id']; 
    }
    
}
?>
