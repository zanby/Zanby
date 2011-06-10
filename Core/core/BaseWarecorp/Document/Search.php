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
 * Search class
 * @package Warecorp_List_Search
 * @author Vitaly Targonsky
 */

class BaseWarecorp_Document_Search extends Warecorp_Search
{

    private $_resByType = null;
    private $_order     = "@weight desc";

    /**
     * Constructor
     */
    public function setOrder($value, $direction)
    {
        if (!in_array($direction, array('asc','desc'))) { $direction = 'desc'; };
        switch ($value) {
            case 'name':
                $this->_order = "name ".$direction;
                break;
            case 'date':
                $this->_order = "creation_date ".$direction;
                break;
            default:
                $this->_order = "@weight desc";
        }
        //$this->_order = $value;
        return $this;
    }

    public function getOrder()
    {
        return $this->_order;
    }


    public function __construct($id = null)
    {
        parent::__construct($id);
    }

    /**
     * Return url for pager for custom search page (not globalsearch)
     *
     * @param unknown_type $params
     */

    static public function getPagerLink($params)
    {
        return "";
    }


    /**
     * @return array
     * @author Vitaly Targonsky
     */
    public function searchByCriterions( $params )
    {
        if (WITH_SPHINX){
            $cl = new Warecorp_Data_Search();
            // initialization
            $cl->init('document');
            $query = "";

            $cl->SetFilter( 'group_private', array( 0 ) );
            $cl->SetFilter( 'private', array( 0 ) );

            $this->setBlockedUserFilter($cl);

            // set include and exclude filters if it's necessary
            if ( $this->getIncludeIds() ) $cl->SetIDFilter ( $this->getIncludeIds() );
            if ( $this->getExcludeIds() ) $cl->SetIDFilter ( $this->getExcludeIds(), true );

            if (EI_FILTER_ENABLED){
                $cl->SetFilter ( 'main_group_uid', array( crc32('user'), crc32(HTTP_CONTEXT) ));
            }

            $this->setLocationFilter($cl, $params);

            if (is_array($this->keywords) && count($this->keywords)) {
                $query = implode(' ', $this->keywords );
            }

            $cl->SetSort($this->_order);
            $cl->Query($query);
            return $cl->getResultPairs();

        }
        else{
            return array();
        }
    }

}

