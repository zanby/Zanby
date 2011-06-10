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
  * @version 1.0
  */
interface BaseWarecorp_Global_iSearchFields
{

    /**
    * return object
    * @return void object
    */
    public function entityObject();
    
    /**
    * return object id
    * @return int
    */
    public function entityObjectId();

    /**
    * return object type. possible values: simple, family, blank string or null
    * @return string
    */
    public function entityObjectType();

    /**
    * return owner type
    * possible values: group, user
    * @return string
    */
    public function entityOwnerType();

    /**
    * return title for entity (like group name, username, photo or gallery title)
    * @return string
    */
    public function entityTitle();

    /**
    * return headline for entity (like group headline, members first and last name, photo or gallery title,etc). 
    * for entities which didn't have headline will be returned entityTitle
    * @return string
    */
    public function entityHeadline();
    
    /**
    * return description for entity (group description, user intro, gallery or photo description, etc.). 
    * for entities which didn't have headline will be returned entityTitle
    * @return string
    */
    public function entityDescription();

    /**
    * return username of owner 
    * @return string
    */
    public function entityAuthor();

    /**
    * return user_id of entity owner 
    * @return string
    */
    public function entityAuthorId();

    /**
    * return picture URL (avatar, group picture, trumbnails, etc.) 
    * @return int
    */
    public function entityPicture();
    
    /**
    * return creation date for all elements
    * @return string
    */
    public function entityCreationDate();

    /**
    * return update date for all elements
    * @return string
    */
    public function entityUpdateDate();

    /**
    * items count (members, posts, child groups, etc.)
    * @return int
    */
    public function entityItemsCount();
    
    /**
    * get category for entity (event type, list type, group category, etc)
    * possible values: string 
    * @return int
    */
    public function entityCategory();

    /**
    * get category_id for entity (event type, list type, group category, etc)
    * possible values: int , null 
    * @return int
    */
    public function entityCategoryId();

    /**
    * get country for entity (users, groups, events)
    * possible values: string
    * @return int
    */
    public function entityCountry();

    /**
    * get country_int for entity (users, groups, events)
    * possible values: int, null
    * @return int
    */
    public function entityCountryId();

    
    /**
    * get city for entity (users, groups, events)
    * possible values: string
    * @return int
    */
    public function entityCity();

    /**
    * get city_id for entity (users, groups, events)
    * possible values: int, null
    * @return int
    */
    public function entityCityId();
    
    /**
    * get zip for entity (users, groups, events)
    * possible values: int, null
    * @return int
    */
    public function entityZIP();
    
    /**
    * get state for entity (users, groups, events)
    * possible values: int, null
    * @return int
    */
    public function entityState();

    /**
    * get state_id for entity (users, groups, events)
    * possible values: int, null
    * @return int
    */
    public function entityStateId();

    /**
    * path to video(video galleries)
    * possible values: string
    * @return int
    */
    public function entityVideo();
    
    /**
    * comments count for entity
    * possible values: int
    * @return int
    */
    public function entityCommentsCount();

    /**
    * comments count for entity
    * possible values: int
    * @return int
    */
    public function entityURL();

}
