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
 * @package    Warecorp_Data
 * @author Dmitry Kostikov
 * @copyright  Copyright (c) 2006
 */
class BaseWarecorp_Data_Comment extends Warecorp_Data_Entity
{
    public $id;
    public $userId;
    public $entityTypeId;
    public $entityId;
    public $creationDate;
    public $content;
    private $_creator;
    
    /**
     * Constructor.
     */
	public function __construct($id = false)
	{
	    parent::__construct('zanby_users__comments');
	    
	    $this->addField('id');
	    $this->addField('user_id', 'userId');
	    $this->addField('entity_type_id', 'entityTypeId');
	    $this->addField('entity_id', 'entityId');
	    $this->addField('creation_date', 'creationDate');
	    $this->addField('content');
	    
	    parent::loadByPk($id);
	}
	
	/**
	 * return creator object
	 * @return Warecorp_User
	 * @author Artem Sukharev
	 */
	public function getCreator()
	{
		if ( $this->_creator === null ) {
            $this->_creator = new Warecorp_User('id', $this->userId);
		}
		return $this->_creator;
	}
    
    public function getId()
    {
        return $this->id;
    }
}
