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
 * @package Warecorp_Photo_List
 * @author Artem Sukharev
 * @version 1.0
 */
abstract class BaseWarecorp_Photo_List_Abstract extends Warecorp_Abstract_List
{
	/**
	 * id of gallery for select
	 */
	private $galleryId;
	/**
	 * return random
	 */
	private $random;

	function __construct($galleryId = null)
	{
        if ( null !== $galleryId ) $this->setGalleryId($galleryId);
		parent::__construct();
	}

	/**
	 * id of gallery for select
	 */
	public function getGalleryId()
	{
		return $this->galleryId;
	}

	/**
	 * id of gallery for select
	 * 
	 * @param newVal
	 */
	public function setGalleryId($newVal)
	{
		$this->galleryId = $newVal;
		return $this;
	}

	/**
	 * return random
	 */
	public function getRandom()
	{
		return $this->random;
	}

	/**
	 * return random
	 * 
	 * @param newVal
	 */
	public function setRandom($newVal)
	{
		$this->random = $newVal;
	}

	/**
	 * return last photo for gallery
	 * @return Warecorp_Photo_Abstract
	 * @author Artem Sukharev
	 */
	abstract public function getLastPhoto();
    
    /**
	 * return random photo for gallery
	 * @return Warecorp_Photo_Abstract
	 * @author Alexander Komarovski
	 */
    abstract public function getRandomPhoto();	
}
