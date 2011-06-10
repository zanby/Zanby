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
 * Search class for global search 
 * Global search will be availeble only with sphinx
 * @package Warecorp_Global_Search
 * @author Michail Pianko
 */

class BaseWarecorp_Global_ObjectFactory
{
    static function getObject( $entityId, $entityType )  
    {
        switch ($entityType){
            case '1': 
                return new Warecorp_User('id', $entityId);
            case '2':
                return Warecorp_Group_Factory::loadById($entityId);
            case '3':
                return Warecorp_Photo_Gallery_Factory::loadById($entityId);
            case '4':
                return Warecorp_Photo_Factory::loadById($entityId);
            case '5':
                return new Warecorp_Document_Item($entityId);
            case '6':
                return new Warecorp_ICal_Event($entityId) ;
            case '20':
                return new Warecorp_List_Item($entityId);  
            case '21':
                return new Warecorp_List_Record($entityId);
            case '36': 
                return Warecorp_Video_Gallery_Factory::loadById($entityId);
            case '37':
                return Warecorp_Video_Factory::loadById($entityId);
            case '40':
                return new Warecorp_DiscussionServer_Post($entityId);
            default: 
                throw new Zend_Exception('unknown object type or not supported by global search');   
        }
    }
    
    static function getSearchTitle( $entityId, $entityType )
    {
        $object = self::getObject( $entityId, $entityType );
        switch ($entityType){
            case '1': 
                return $object->getLogin();
            case '2':
                return $object->getName();
            case '3':
                return $object->getTitle();
            case '4':
                return $object->getTitle();
            case '5':
                return $object->getOriginalName();
            case '6':
                return $object->getTitle();
            case '20':
                return $object->getTitle();  
            case '21':
                return $object->getTitle();
            case '36': 
                return $object->getTitle();
            case '37':
                return $object->getTitle();
            case '40':
                return $object->getTitle();
            default: 
                throw new Zend_Exception('unknown object type or not supported by global search');   
        }  
    
    }
    
    static function getSearchDescription( $entityId, $entityType )
    {
        $object = self::getObject( $entityId, $entityType );
        switch ($entityType){
            case '1': 
                return $object->getIntro();
            case '2':
                return $object->getDescription();
            case '3':
                return $object->getDescription();
            case '4':
                return $object->getDescription();
            case '5':
                return $object->getDescription();
            case '6':
                return $object->getDescription();
            case '20':
                return $object->getDescription();  
            case '21':
                return $object->getEntry();
            case '36': 
                return $object->getDescription();
            case '37':
                return $object->getDescription();
            case '40':
                return $object->getDescription();
            default: 
                throw new Zend_Exception('unknown object type or not supported by global search');   
        }  
    
    }
    
    
}                     
