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
 * @package    Warecorp_Location
 * @copyright  Copyright (c) 2006
 */

/**
 *
 *
 */
class BaseWarecorp_Location_Zipcode extends Warecorp_Data_Entity
{
    public $id;
    public $cityId;
    public $zipcode;
    public $latitude;
    public $longitude;
    public $status;

    private $City = null;

   /**
    * Cache life time for save Zip objects
    */
    const CACHE_LIFETIME = 864000;  //  10 days

    /**
    * Factory
    */
    public static function create($value)
    {
        if ( is_string($value) && preg_match('/^[0-9a-zA-Z]$/i', $value) ) {
            $cache = Warecorp_Cache::getFileCache();
            if ( !$zipcode = $cache->load('Warecorp_Location_Zipcode_'.$value) ) {
                $zipcode = new Warecorp_Location_Zipcode($value);
                $cache->save($zipcode, 'Warecorp_Location_Zipcode_'.$value, array(), self::CACHE_LIFETIME);
            }
        } else {
            $zipcode = new Warecorp_Location_Zipcode($value);
        }
        return $zipcode;
    }

   /**
    * Factory
    * @param string $zipcode
    * @return Warecorp_Location_Zipcode
    */
    public static function createByZip( $zipcode )
    {
        $cache = Warecorp_Cache::getFileCache();
        if ( !$objZipcode = $cache->load('Warecorp_Location_ZipcodeItem_'.md5($zipcode)) ) {
            $objZipcode = new Warecorp_Location_Zipcode;
            $objZipcode->pkColName = 'zipcode';
            $objZipcode->loadByPk( $zipcode );
            $cache->save($objZipcode, 'Warecorp_Location_ZipcodeItem_'.md5($zipcode), array(), self::CACHE_LIFETIME);
        }
        return $objZipcode;
    }

    /**
     * Constructor.
     *
     */
	public function __construct($value = null)
	{
        parent::__construct('zanby_location__zipcodes', array(
            'id'        => 'id',
            'city_id'   => 'cityId',
            'zipcode'   => 'zipcode',
            'latitude'  => 'latitude',
            'longitude' => 'longitude',
            'status'    => 'status'));

        $this->load($value);
	}
	/**
	 * Устанавливает City для Zip
	 * @return void
     * Cached
	 */
    public function setCity()
    {
        $this->City = Warecorp_Location_City::create($this->cityId);
    }
    /**
     * @return Warecorp_Location_City
     */
    public function getCity()
    {
        if ( $this->City === null ) $this->setCity();
        return $this->City;
    }
}
