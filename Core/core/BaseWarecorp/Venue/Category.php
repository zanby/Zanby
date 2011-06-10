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
 * @package    Warecorp_Venue
 * @copyright  Copyright (c) 2006
 * @author Eugene Kirdzei
 */

/**
 * venues category class
 * 
 * @author Eugene Kirdzei
 */
class BaseWarecorp_Venue_Category extends Warecorp_Data_Entity 
{
    private $id;
    private $type;
    private $name;
    /**
     * Constructor.
     * @var int $id - category id
     * @author Eugene Kirdzei
     */
    public function __construct($id = false)
    {
        parent::__construct('zanby_event__venue_categories');
        $this->addField('id');
        $this->addField('type');
        $this->addField('name');
    
        $this->loadByPk($id);
    }

    /**
     * set id
     *
     * @param int $newVal
     * @return self
     * @author Eugene Kirdzei
     */
    public function setId($newVal)
    {
        $this->id = $newVal;
        return $this;
    }    
    
    /**
     * return category id
     *
     * @return int
     * @author Eugene Kirdzei
     */
    public function getId()
    {
        return $this->id;   
    }
    
    /**
     * set type
     *
     * @param string $newVal
     * @return self
     * @author Eugene Kirdzei
     */
    public function setType($newVal)
    {
    	$this->type = $newVal;
    	return $this;
    }
    
    /**
     * return category type
     *
     * @return string
     * @author Eugene Kirdzei
     */
    public function getType()
    {
        return $this->type;   
    }
    
    /**
     * Set name
     *
     * @param string $newVal
     * @return self
     * @author Eugene Kirdzei
     */
    public function setName($newVal)
    {
    	$this->name = $newVal;
    	return $this;
    }
    
    /**
     * return category name
     *
     * @return string
     * @author Eugene Kirdzei
     */
    public function getName(){
    	return $this->name;
    }
    
    
}

?>
