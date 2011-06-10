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

class BaseWarecorp_Location_Alias_List
{
	private $dbConn;
	static private $logRequests = false;
	static private $detectTimezone = true; 

	static public function setUpDetectTimezone( $bool )
	{
	    self::$detectTimezone = (boolean) $bool;
	}
	
	
	static public function checkAlias($name, $type = null)
	{
		$dbConn = Zend_Registry::get('DB');
		$query = $dbConn->select()->from('zanby_location__alias', '*');
		$query->where('alias_name = ?', $name);
		if ( null !== $type ) {
			$query->where('alias_entity_type = ?', $type);
		}
		$result = $dbConn->fetchRow($query);
		if ( !$result ) return null;
		else return $result;
	}

    /**
     * @param string $queryString
     * @param Warecorp_Location_Country $country
     */
    static public function isQueryProcessed($queryString, $country = null)
    {
        $dbConn = Zend_Registry::get('DB');
        $query = $dbConn->select()->from('zanby_location__alias_processed', new Zend_Db_Expr('COUNT(id)'));
        $query->where('query = ?', $queryString);
        if ( null !== $country ) $query->where('country = ?', $country->id);
        $result = $dbConn->fetchOne($query);
        return (boolean) $result;
    }

    /**
     *
     */
    static public function log($time, $url)
    {
    	if ( self::$logRequests ) {
	    	$dirName = APP_VAR_DIR.'/logs/geocoding/';
	    	if ( !file_exists($dirName) || !is_dir($dirName) ) {
	    	   mkdir($dirName, 0777);
	    	   chmod($dirName, 0777);
	    	}
	        if ( !file_exists($dirName.'requests_log.txt') ) {
	           $fp = fopen($dirName.'requests_log.txt', 'w');
	           fclose($fp);
	           chmod($dirName.'requests_log.txt', 0777);
	        }
	    	$fp = fopen($dirName.'requests_log.txt', 'a+');
	    	$logMessage = $time . ' : ' . $url . "\n";
	    	fwrite($fp, $logMessage);
	    	fclose($fp);
    	}
    }

    /**
     *
     */
	static public function getmicrotime()
	{
	    list($usec, $sec) = explode(" ", microtime());
	    return ((float)$usec + (float)$sec);
	}

    /**
     * @param string $queryString
     * @param Warecorp_Location_Country $country
     */
    static public function saveQueryAsProcessed($queryString, $country = null)
    {
    	$dbConn = Zend_Registry::get('DB');
    	$data = array();
    	$data['query'] = $queryString;
    	if ( null !== $country ) $data['country'] = $country->id;
    	$data['processing_date'] = new Zend_Db_Expr('NOW()');
    	$dbConn->insert('zanby_location__alias_processed', $data);
    }

	/**
	 * @param int|string $country - id or code
     * @return array of int - array of city ids related to aliases
	 */
	static public function detectAliasByQueryString($queryString, $country = null)
	{
		/**
		 * Validate country object
		 */
        if ( null !== $country ) {
            if ( is_numeric($country) )     $country = Warecorp_Location_Country::create($country);
            elseif ( is_string($country) )  $country = Warecorp_Location_Country::findByCode($country);
        }
        /**
		 * If query was processed - find it and return results
		 */
        if ( self::isQueryProcessed($queryString, $country) ) {
            $lstAliases = Warecorp_Location_Alias::findByName($queryString, $country, 'city');
            //  составить список cityId для всех алиасов, которые найденны
            //  вернуть этот список
            if ( null === $lstAliases ) return array();
            else {
                $return = array();
                foreach ( $lstAliases as $_alias ) $return[] = $_alias->getEntityId();
                unset($lstAliases);
                return $return;
            }
        }
		/**
		 * Run geocoding from maps.google.com
		 */
		$lstAliases = self::runGoogleGeocoding($queryString, $country);
		$return = array();
		if (null !== $lstAliases) {
			foreach ( $lstAliases as $_alias ) {
				if ($_alias->getCountry() && $_alias->getState() && $_alias->getCity()) {
                    $_alias->setEntityType('city');
					$_alias = self::saveAlias($queryString, $_alias);
                    if ( null !== $_alias->getEntityId() ) $return[] = $_alias->getEntityId();
				}
			}
		}

        unset($lstAliases);
		return $return;
	}

    /**
     * @param int|string $country - id or code
     * @return array of int - array of city ids related to aliases
     */
    static public function coordinatesAliasByQueryString($queryString, $country = null)
    {
        /**
         * Validate country object
         */
        if ( null !== $country ) {
            if ( is_numeric($country) )     $country = Warecorp_Location_Country::create($country);
            elseif ( is_string($country) )  $country = Warecorp_Location_Country::findByCode($country);
        }
        /**
         * If query was processed - find it and return results
         */
        if ( self::isQueryProcessed($queryString, $country) ) {
            $lstAliases = Warecorp_Location_Alias::findByName($queryString, $country, 'city');
            //  составить список cityId для всех алиасов, которые найденны
            //  вернуть этот список
            if ( null === $lstAliases ) return array();
            else {
                $return = array();
                foreach ( $lstAliases as $_alias ) 
                    $return[] = array(
                        'lat' => $_alias->getLat(), 
                        'lng' => $_alias->getLong(), 
                        'east' => $_alias->getEast(),
                        'north' => $_alias->getNorth(),
                        'south' => $_alias->getSouth(),
                        'west' => $_alias->getWest()
                    );
                unset($lstAliases);
                return $return;
            }
        }
        /**
         * Run geocoding from maps.google.com
         */
        $lstAliases = self::runGoogleGeocoding($queryString, $country);
        $return = array();
        if (null !== $lstAliases) {
            foreach ( $lstAliases as $_alias ) {
                if ($_alias->getCountry() && $_alias->getState() && $_alias->getCity()) {
                    $_alias->setEntityType('city');
                    $_alias = self::saveAlias($queryString, $_alias);
                    if ( null !== $_alias->getEntityId() ) 
                        $return[] = array(
                            'lat' => $_alias->getLat(), 
                            'lng' => $_alias->getLong(), 
                            'east' => $_alias->getEast(),
                            'north' => $_alias->getNorth(),
                            'south' => $_alias->getSouth(),
                            'west' => $_alias->getWest()
                        );
                }
            }
        }

        unset($lstAliases);
        return $return;
    }
    
	/**
	 * @param $country Warecorp_Location_Country|id
	 */
	static public function runGoogleGeocoding($queryString, $country = null, $format = 'json')
	{
        /**
         * @todo apply google key from config file
         */
        $key = GOOGLE_MAP_KEY;

        /**
         * Create query string
         */
        $queryString = Warecorp_Location_Alias::prepareQueryString($queryString);
        if ( null !== $country ) {
            if ( $country instanceof Warecorp_Location_Country ) $address = urlencode($queryString.', '.$country->name);
            else {
            	$country = Warecorp_Location_Country::create($country);
            	if ( $country->id ) $address = urlencode($queryString.', '.$country->name);
            	else $address = urlencode($queryString);
            }
        } else $address = urlencode($queryString);

        /**
         * run query
         */
        if ( 'json' == $format ) {
            $url = "http://maps.google.com/maps/geo?output=json&oe=utf-8&q=".$address."&key=".$key."&hl=en";
        } elseif ( 'cvs' == $format ) {
            $url = "http://maps.google.com/maps/geo?output=csv&q=".$address."&key=".$key."";
        } else {
            throw new Zend_Exception('Incorrect format');
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER,0);
       // curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept-Language: en-us,en;q=0.5"));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $startTime = self::getmicrotime();
        //  exec curl and log time
        $data = curl_exec($ch);
        $endTime = self::getmicrotime();
        curl_close($ch);

        $time = $endTime - $startTime;
        unset($startTime);
        unset($endTime);
        self::log($time, $url);

        if ( 'json' == $format ) {
        	try {
                $data = Zend_Json::decode($data);
        	} catch ( Zend_Json_Exception $ex ) {
        	   return null;
        	}
        } else {
            return $data;
        }

        $lstAliases = null;
        $isFound = false;
        if (is_array( $data ) && isset( $data['Status'] ) && isset( $data['Status']['code'] )) {
            if ($data['Status']['code'] == '200') {
                if (isset( $data['Placemark'] ) && is_array( $data['Placemark'] ) && sizeof( $data['Placemark'] ) != 0) {
                    foreach ( $data['Placemark'] as $Placemark ) {
                        if ( null !== $_tmp = self::parseGeocodingResult($Placemark, $country) ) {
                            $lstAliases[] = $_tmp;
                        }
                    }
                }
            }
        }

        self::saveQueryAsProcessed($queryString, $country);
		return $lstAliases;
	}

    /**
     *
     */
	static public function parseGeocodingResult($Placemark, $country = null)
	{
        $newAlias = new Warecorp_Location_Alias();
        $newAlias->setAddress($Placemark['address']);
        if (isset( $Placemark['AddressDetails'] )) {
            $AddressDetails = $Placemark['AddressDetails'];
            if ( isset($AddressDetails['Country']) ) {
	            if ( null !== $country && $country->code != $AddressDetails['Country']['CountryNameCode'] ) {
	                return null;
	            }
	            $newAlias->setAccuracy($AddressDetails['Accuracy']);
	            $newAlias->setCountry($AddressDetails['Country']['CountryName']);
	            $newAlias->setCountryCode($AddressDetails['Country']['CountryNameCode']);
	            if (isset( $AddressDetails['Country']['AdministrativeArea'] )) {
	                $newAlias->setState($AddressDetails['Country']['AdministrativeArea']['AdministrativeAreaName']);
	                if (isset( $AddressDetails['Country']['AdministrativeArea']['SubAdministrativeArea'] )) {
	                    if (isset( $AddressDetails['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality'] )) {
	                        if (isset( $AddressDetails['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['DependentLocality'] )) {
	                            $newAlias->setCity($AddressDetails['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['DependentLocality']['DependentLocalityName']);
	                        } else {
	                            $newAlias->setCity($AddressDetails['Country']['AdministrativeArea']['SubAdministrativeArea']['Locality']['LocalityName']);
	                        }
	                    } elseif (isset( $AddressDetails['Country']['AdministrativeArea']['SubAdministrativeArea']['DependentLocality'] )) {
	                        $newAlias->setCity($AddressDetails['Country']['AdministrativeArea']['SubAdministrativeArea']['DependentLocality']['DependentLocalityName']);
	                    }
	                } elseif (isset( $AddressDetails['Country']['AdministrativeArea']['Locality'] )) {
	                    if (isset( $AddressDetails['Country']['AdministrativeArea']['Locality']['DependentLocality'] )) {
	                        $newAlias->setCity($AddressDetails['Country']['AdministrativeArea']['Locality']['DependentLocality']['DependentLocalityName']);
	                    } else {
	                        $newAlias->setCity($AddressDetails['Country']['AdministrativeArea']['Locality']['LocalityName']);
	                    }
	                } elseif ( isset( $AddressDetails['Country']['AdministrativeArea']['DependentLocality'] ) ) {
	                    $newAlias->setCity($AddressDetails['Country']['AdministrativeArea']['DependentLocality']['DependentLocalityName']);
	                }
	            } elseif (isset( $AddressDetails['Country']['SubAdministrativeArea'] )) {
	                $newAlias->setState($AddressDetails['Country']['SubAdministrativeArea']['SubAdministrativeAreaName']);
	                if (isset( $AddressDetails['Country']['SubAdministrativeArea']['Locality'] )) {
	                    if (isset( $AddressDetails['Country']['SubAdministrativeArea']['Locality']['DependentLocality'] )) {
	                        $newAlias->setCity($AddressDetails['Country']['SubAdministrativeArea']['Locality']['DependentLocality']['DependentLocalityName']);
	                    } else {
	                        $newAlias->setCity($AddressDetails['Country']['SubAdministrativeArea']['Locality']['LocalityName']);
	                    }
	                } elseif (isset( $AddressDetails['Country']['SubAdministrativeArea']['DependentLocality'] )) {
	                    $newAlias->setCity($AddressDetails['Country']['SubAdministrativeArea']['DependentLocality']['DependentLocalityName']);
	                }
	            } elseif (isset( $AddressDetails['Country']['Locality'] )) {
	                if (isset( $AddressDetails['Country']['Locality']['DependentLocality'] )) {
	                	$newAlias->setState($AddressDetails['Country']['Locality']['LocalityName']);
	                    $newAlias->setCity($AddressDetails['Country']['Locality']['DependentLocality']['DependentLocalityName']);
	                } else {
	                    $newAlias->setCity($AddressDetails['Country']['Locality']['LocalityName']);
	                    $newAlias->setState($newAlias->getCity());
	                }
	            }
            }
        }
        if ( isset($Placemark['Point']) ) {
            $Point = $Placemark['Point'];
            $newAlias->setLong($Point['coordinates'][0]);
            $newAlias->setLat($Point['coordinates'][1]);
            if ( self::$detectTimezone ) {
                if ( null !== $timezoneId = self::runTimezoneDetect($Point['coordinates'][1], $Point['coordinates'][0]) ) {
                    $newAlias->setTimezone($timezoneId);
                }
            }
        }
        if ( isset($Placemark['ExtendedData']) ) {
            $newAlias->setNorth($Placemark['ExtendedData']['LatLonBox']['north']);
        	$newAlias->setSouth($Placemark['ExtendedData']['LatLonBox']['south']);
        	$newAlias->setEast($Placemark['ExtendedData']['LatLonBox']['east']);
        	$newAlias->setWest($Placemark['ExtendedData']['LatLonBox']['west']);
        }
        return $newAlias;
	}

    /**
     *
     */
	static public function saveAlias($originalQuery, Warecorp_Location_Alias $objAlias)
	{
		$dbConn = Zend_Registry::get('DB');
		if ($objAlias->getCountry() && $objAlias->getState() && $objAlias->getCity()) {
			/**
			 * Validate country
			 */
			$objCountry = Warecorp_Location_Country::findByName($objAlias->getCountry());
			if (null === $objCountry) $objCountry = Warecorp_Location_Country::findByCode($objAlias->getCountryCode());
			if (null === $objCountry) {
				/**
				 * Create new country as google data
				 */
				$objCountry = Warecorp_Location_Country::create();
				$objCountry->name = $objAlias->getCountry();
				$objCountry->code = $objAlias->getCountryCode();
				$objCountry->source = 'google';
				$objCountry->save();
			}
			/**
			 * Validate state
			 */
			$objState = Warecorp_Location_State::findByName($objAlias->getState(), $objCountry->id);
			if ( null === $objState ) $objState = Warecorp_Location_State::findByCode($objAlias->getState(), $objCountry->id);
			if (null === $objState) {
                /**
                 * @todo try to detect state by alias
                 */
                /*
                $lstStates = Warecorp_Location_Alias::findByName($objAlias->getState(), $objCountry, 'state');
                if ( sizeof($lstStates) == 1 ) {
                    $objState = Warecorp_Location_State::create($lstStates[0]->getEntityId());
                    if ( null === $objState->id ) $objState = null;
                }
                */
			}
			if (null === $objState) {
				/**
				 * Create new state in current country as google data
				 */
				$objState = new Warecorp_Location_State();
				$objState->countryId = $objCountry->id;
				$objState->name = $objAlias->getState();
				$objState->code = '';
				$objState->source = 'google';
				$objState->save();
			}
			/**
			 * Validate city
			 */
			$objCity = Warecorp_Location_City::findByName($objAlias->getCity(), $objState->id);
			if (null === $objCity) {
				/**
				 * Create new city in current state and country as google data
				 */
				$objCity = new Warecorp_Location_City();
				$objCity->stateId = $objState->id;
				$objCity->name = $objAlias->getCity();
				$objCity->source = 'google';
				$objCity->save();
				$objCity->updateCityInfo($objAlias->getLat(), $objAlias->getLong(), $objAlias->getTimezone());
			}

			if ($objCountry && $objState && $objCity) {
				/**
				 * Create Alias
				 */
				$objAlias->setName($originalQuery);
				$objAlias->setEntityId($objCity->id);
				$objAlias->save();
			}
		}
        return $objAlias;
	}

	/**
	 *
	 */
    static public function runTimezoneDetect($lat, $long)
    {
        $url = "http://ws.geonames.org/timezone?lat=".$lat."&lng=".$long."";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER,0);
        //curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //  exec curl and log time
        $startTime = self::getmicrotime();
        $data = curl_exec($ch);
        $endTime = self::getmicrotime();

        curl_close($ch);

        $time = $endTime - $startTime;
        unset($startTime);
        unset($endTime);
        self::log($time, $url);

        $dom = new DOMDocument();
        $dom->loadXML($data);
        $timezoneId = $dom->getElementsByTagName('timezoneId');
        if ( $timezoneId->length != 0 ) return $timezoneId->item(0)->nodeValue;
        else return null;
    }

    static public function runContinentDetect($countryCode)  /** RU, US,  **/
    {
        $url = "http://ws.geonames.org/countryInfo?lang=en&country=".$countryCode;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER,0);
        //curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //  exec curl and log time
        $startTime = self::getmicrotime();
        $data = curl_exec($ch);
        $endTime = self::getmicrotime();

        curl_close($ch);

        $time = $endTime - $startTime;
        unset($startTime);
        unset($endTime);
        self::log($time, $url);

        $dom = new DOMDocument();
        $dom->loadXML($data);
        $timezoneId = $dom->getElementsByTagName('continent');
        if ( $timezoneId->length != 0 ) return $timezoneId->item(0)->nodeValue;
        else return null;
    }
}
