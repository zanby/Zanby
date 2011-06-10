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

class BaseWarecorp_Round_Event_Mailing extends Warecorp_Data_Entity
{
    private $id;
    private $roundId;
    private $eventId;
    private $isMailed;
    private $usedDefaultAddress;
    private $usedAddress;

    public function setId ( $value ) {
        $this->id = $value;
        return $this;
    }
    public function getId () {
        return $this->id;
    }
    public function setRoundId ( $value ) {
        $this->roundId = $value;
        return $this;
    }
    public function getRoundId () {
        return $this->roundId;
    }
    public function setEventId ( $value ) {
        $this->eventId = $value;
        return $this;
    }
    public function getEventId () {
        return $this->eventId;
    }
    public function setIsMailed ( $value ) {
        $this->isMailed = $value;
        return $this;
    }
    public function getIsMailed () {
        return $this->isMailed;
    }
    public function setUsedDefaultAddress ( $value ) {
        $this->usedDefaultAddress = $value;
        return $this;
    }
    public function getUsedDefaultAddress () {
        return $this->usedDefaultAddress;
    }
    public function setUsedAddress ( $value ) {
        $this->usedAddress = $value;
        return $this;
    }
    public function getUsedAddress () {
        return $this->usedAddress;
    }

    /**
     * Constructor.
     * @author Artem Sukharev
     */
    public function __construct($id = null) {
        parent::__construct('zanby_rounds__event_mailing');

        $this->addField('id', 'id');
        $this->addField('round_id', 'roundId');
        $this->addField('event_id', 'eventId');
        $this->addField('is_mailed', 'isMailed');
        $this->addField('used_default_address', 'usedDefaultAddress');
        $this->addField('used_address', 'usedAddress');

        if ($id !== null){
            $this->pkColName = 'id';
            $this->load($id);
        }
    }

    static public function isMailed( $roundID, $eventID ) {
        if ( !$roundID ) throw new Exception('Incorrect round ID');
        if ( !$eventID ) throw new Exception('Incorrect event ID');

        $dbConn = Zend_Registry::get("DB");
        $query = $dbConn->select();
		$query->from('zanby_rounds__event_mailing', array('id'));
		$query->where('round_id = ?', $roundID);
        $query->where('event_id = ?', $eventID);
		$id = $dbConn->fetchOne($query);
        if ( $id ) return new Warecorp_Round_Event_Mailing ( $id );

        return null;
    }
    static public function getDefaultMailingAddress(Warecorp_User $objUser) {
        $objProfile = $objUser->getProfile();
        if ( $objProfile ) {
            $strAddress = '';
            $out = array();
            if ( $objProfile->getHomeaddressline1() != '' ) $out[] = htmlspecialchars ( $objProfile->getHomeaddressline1() );
            if ( $objProfile->getHomeaddressline2() != '' ) $out[] = htmlspecialchars ( $objProfile->getHomeaddressline2() );
            $strAddress = implode(', <br/>', $out);

            $strLocation = '';
            $out = array();
            if ( $objProfile->getHomecity() ) $out[] = htmlspecialchars ( $objProfile->getHomecity() );
            if ( $objProfile->getHomestate() ) $out[] = htmlspecialchars ( $objProfile->getHomestate() );
            if ( $objProfile->getHomezip() ) $out[] = htmlspecialchars ( $objProfile->getHomezip() );
            $strLocation = implode(', ', $out);

            $strAddress = ( $strAddress && $strLocation )
                ? $strAddress.', <br/>'.$strLocation
                : ( $strAddress ? $strAddress : $strLocation );
            return $strAddress;
        }
        return '';
    }
    static public function getPreferredMailingAddress(Warecorp_User $objUser) {
        $objProfile = $objUser->getProfile();
        if ( $objProfile ) {
            $strAddress = '';
            $out = array();
            if ( $objProfile->getWorkaddressline1() != '' ) $out[] = htmlspecialchars ( $objProfile->getWorkaddressline1() );
            if ( $objProfile->getWorkaddressline2() != '' ) $out[] = htmlspecialchars ( $objProfile->getWorkaddressline2() );
            $strAddress = implode(', <br/>', $out);

            $strLocation = '';
            $out = array();
            if ( $objProfile->getWorkcity() ) $out[] = htmlspecialchars ( $objProfile->getWorkcity() );
            if ( $objProfile->getWorkstate() ) $out[] = htmlspecialchars ( $objProfile->getWorkstate() );
            if ( $objProfile->getWorkzip() ) $out[] = htmlspecialchars ( $objProfile->getWorkzip() );
            $strLocation = implode(', ', $out);

            $strAddress = ( $strAddress && $strLocation )
                ? $strAddress.', <br/>'.$strLocation
                : ( $strAddress ? $strAddress : $strLocation );
            return $strAddress;
        }
        return '';
    }
}
