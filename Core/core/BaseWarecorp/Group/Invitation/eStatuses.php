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
 * Перечисление возможных состояний для приглашения
 * @author Andrew Peresalyak
 * @version 1.0
 * @created 31-Aug-2007 16:58:32
 */
class BaseWarecorp_Group_Invitation_eStatuses
{
	const PENDING_APPROVAL  = 1;
	const APPROVED          = 2;
	const HAS_NOT_RESPONDED = 3;
	const DECLINED          = 4;
	const DRAFT             = 5;
	
	static function toInteger($var){	    
	    switch (strtolower($var)){
	        case 'pending_approval': return Warecorp_Group_Invitation_eStatuses::PENDING_APPROVAL;
	        case 'approved':  return Warecorp_Group_Invitation_eStatuses::APPROVED;
	        case 'has_not_responded': return Warecorp_Group_Invitation_eStatuses::HAS_NOT_RESPONDED;
	        case 'declined': return Warecorp_Group_Invitation_eStatuses::DECLINED;
	        case 'draft': return Warecorp_Group_Invitation_eStatuses::DRAFT;
	        default: throw new Zend_Exception('Wrong parametr \'' . $val . '\'.');
	    }
	}
	
	static function toString($var){
	    switch ($var){
	        case Warecorp_Group_Invitation_eStatuses::PENDING_APPROVAL: return 'pending approval';
	        case Warecorp_Group_Invitation_eStatuses::APPROVED:  return 'approved';
	        case Warecorp_Group_Invitation_eStatuses::HAS_NOT_RESPONDED: return 'has not responded';
	        case Warecorp_Group_Invitation_eStatuses::DECLINED: return 'declined';
	        case Warecorp_Group_Invitation_eStatuses::DRAFT: return 'draft';
	        default: throw new Zend_Exception('Wrong parametr \'' . $val . '\'.');
	    }
	}
}
