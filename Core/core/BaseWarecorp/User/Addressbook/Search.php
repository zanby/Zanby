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
 * @author Konstantin Stepanov
 * @version 1.0
 * @created 20-окт-2008 18:39:48
 */
class BaseWarecorp_User_Addressbook_Search extends Warecorp_Search
{

	public function __construct()
	{
		parent::__construct();
	}

	public function searchByCriterions($filter, $with_weight = false)
	{

		if (!is_array($filter))
		{
			$filter = array( 'login' => $filter );
		}

		if (WITH_SPHINX)
		{
			$cl = new Warecorp_Data_Search();
			$cl->init('addressbook');
		
			$query = "";

			if ($this->getIncludeIds()) $cl->SetFilter ( "id", $this->getIncludeIds() ); 
			if ($this->getExcludeIds()) $cl->SetFilter ( "id", $this->getExcludeIds(), true ); 

			if (empty($filter['owner_id']))
			{
				$user = Zend_Registry::get('User');
				if ($user->getId())
				{
					$cl->SetFilter("owner_id", array( $user->getId() ));
					if ($user->getCityId() && empty($filter['city']))
					{
						$City = Warecorp_Location_City::create($user->getCityId());
						$City->setLatitudeLongitude();
						$latitude = deg2rad($City->getLatitude());
						$longitude = deg2rad($City->getLongitude());
						$cl->SetGeoAnchor('latitude', 'longitude', floatval($latitude), floatval($longitude));
					}
				}
			}
			else
			{
				$cl->SetFilter("owner_id", array( intval($filter['owner_id']) ));
			}

			if (!empty($filter['city']))
			{
				$City = Warecorp_Location_City::create($filter["city"]);
				$City->setLatitudeLongitude();
				$latitude = deg2rad($City->getLatitude());
				$longitude = deg2rad($City->getLongitude());
				$cl->SetFilterGeo('latitude', 'longitude', floatval($latitude), floatval($longitude), (defined('DISTANCE_OF_SEARCH')? DISTANCE_OF_SEARCH: 200.0 ) * 1000);
			}

			if (!empty($filter['login']))
			{
				$query .= $filter['login'] . "*";
			}

			if (!empty($this->keywords))
			{
				$query .= implode(" ", $this->keywords);
			}

			$cl->Query($query);

            if ($with_weight) {
                // getting result with id, weight, distance and membres count
                $this->resByCriterions = $cl->getResultIWDLRD();
            } else {
                // getting result as array [id] => id
                $this->resByCriterions = $cl->getResultPairs();
            }

            unset ($cl);
		}
		else
		{
			$login = $filter['login'];
			$owner_id = $filter['owner_id'];
			if (empty($owner_id))
			{
				$user = Zend_Registry::get('User');
				if ($user->getId())
					$owner_id = $user->getId();
			}

			$select = $this->_db->select()
				->from(array('vas' => 'view_addressbook__search'), 'id');

			if (!empty($owner_id))
				$select->where('owner_id = ?', $owner_id);

			if (!empty($login))
				$select->where("login LIKE ?", $login . '%');

			if (!empty($filter['city']))
				$select->where("city_id = ?", $filter['city']);

            if ($this->getIncludeIds()) $select->where('id IN (?)', $this->getIncludeIds());
            if ($this->getExcludeIds()) $select->where('id NOT IN (?)', $this->getExcludeIds());

			$contacts = $this->_db->fetchCol($select);

			$contacts = array_unique($contacts);

			$this->resByCriterions = $contacts;
		}
	}

}
?>
