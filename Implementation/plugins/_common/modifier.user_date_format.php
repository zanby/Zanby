<?php
function smarty_modifier_user_date_format($string, $timeZone = null, $format = null, $timeZoneFrom = 'UTC')
{
	//get(Zend_Date::DATE_SHORT)
    date_default_timezone_set($timeZoneFrom);
    
    if ( $string instanceof Zend_Date ) {
    	$created = clone $string;
    } else {
        $created = new Zend_Date($string, Zend_Date::ISO_8601);
    }
    if ($timeZone) $created->setTimezone($timeZone);
    if ( $format !== null ) {
    	switch ( $format ) {
    		case 'MAIL_SHORT' :
    			return $created->toString('MM/dd/yyyy');
    			break;
    		default: 
                return $created->toString($format);
    	}
    } else {

//        return $created->__toString('m d, yyyy hh:ii:ss p');
        return $created->toString(Warecorp_Date::DATETIME);
//        return $created->get(Zend_Date::DATE_MEDIUM) . ' ' . $created->get(Zend_Date::TIME_MEDIUM);
    }
}