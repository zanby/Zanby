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

require_once 'icalcreator/iCalcreator.class.php';

/**
 *  @package Warecorp_ICal
 *  @class Warecorp_ICal_Export_ICalendar
 *  @author Roman Gabrusenok
 *  @date Mon Nov 15 17:10:29 EET 2010
 */
class BaseWarecorp_ICal_Export_ICalendar extends Warecorp_ICal_Export_Abstract
{
    /**
     * @var string
     * ENUM {stream, file}
     *  stream - buffer output to browser
     *  file - buffer output to file
     */
    private $_how_output = 'stream';
    /**
     * @var string
     * Path to file for FILE output type
     */
    private $_filename;
    /**
     * @var Warecorp_User who donwloading current Event
     */
    private $_user;

    public function __construct(Warecorp_ICal_Event $event, Warecorp_User $user)
    {
        parent::__construct($event);
        $this->_user = $user;
    }

    /**
     * @return Warecorp_ICal_Export_ICalendar
     */
    public function setOutStream()
    {
        $this->_how_output = 'stream';
        return $this;
    }

    /**
     * @return Warecorp_ICal_Export_ICalendar
     */
    public function setOutFile()
    {
        $this->_how_output = 'file';
        return $this;
    }

    /**
     * @return Warecorp_ICal_Export_ICalendar
     */
    public function setFilename($path)
    {
        $this->setOutFile();
        $this->_filename = $path;
        return $this;
    }

    /**
     * @return void
     */
    public function save()
    {
        switch ( $this->_how_output ) {
        case 'stream':
            return $this->makeString();
        case 'file':
            $string = $this->makeString();
            if ( empty($this->_filename) ) {
                return false;
            }
            $mask = umask(0);
            $dir = dirname($this->_filename);
            if ( !file_exists($dir) ) {
                if ( FALSE === @mkdir($dir, 0700, true) ) {
                    umask($mask);
                    return false;
                }
            }
            umask(077);
            if ( FALSE === touch($this->_filename) ) {
                umask($mask);
                return false;
            }
            umask($mask);
            return file_put_contents($this->_filename, $string, LOCK_EX);
        default :
            throw new Warecorp_ICal_Exception("Unknown export method");
        }
    }

    /**
     * makeICalName
     * 
     * @access private
     * @return string
     */
    private function makeICalName()
    {
        return preg_replace('/[^-_.a-z0-9]+/i', ' ', trim($this->_event->getTitle()));
    }

    /**
     * prepareLocale
     * 
     * @param strring $locale in en_US format
     * @access private
     * @return string for above locale return EN
     */
    private function prepareLocale( $locale = NULL )
    {

        $locale = DEFAULT_LOCALE;
        if ( is_string($locale) && trim($locale) !== ''  ) {
            if ( FALSE !== strpos($locale, '_') ) {
                $locale = strtoupper(substr($locale, 0, strpos($locale, '_')));
            } else {
                $locale = strtoupper($locale);
            }
        }
        return $locale;
    }

    /**
     * makeString
     * 
     * @access private
     * @return string
     */
    private function makeString()
    {
        $user       = $this->_user;
        $event      = $this->_event->getRootEvent();
        $venues     = $event->getVenues()->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getList();
        $creator    = $event->getCreator();
        $owner      = $event->getOwner();
        $categories = $event->getCategories()->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getList();
        $documents  = $event->getDocuments()->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getList();
        $pathMethod = ( $event->getOwnerType() === 'group' ) ? 'getGroupPath' : 'getUserPath';

        $iCalendar = new vcalendar();
        $iCalendar->prodid = 'PRODID:-//Warecorp Inc//'.SITE_NAME_AS_STRING.' Calendar//'.$this->prepareLocale($user->getLocale());
        $iCalendar->setProperty('VERSION', '2.0');
        $iCalendar->setProperty('CALSCALE', 'GREGORIAN');
        $iCalendar->setProperty('METHOD', 'PUBLISH');
        $iCalendar->setProperty('X-WR-CALNAME', $this->makeICalName());
        if ( $user->getTimezone() )
            $iCalendar->setProperty('X-WR-TIMEZONE', $user->getTimezone());

        $events = array($event);
        $recurrences = $event->getRecurrences()->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getList();
        if ( $recurrences ) $events = $events + $recurrences;

        foreach ( $events as $event ) {
            $iEvent = new vevent();
            $iEvent->setProperty('class', ($event->getPrivacy() === Warecorp_ICal_Enum_Privacy::PRIVACY_PRIVATE)?'PRIVATE' : 'PUBLIC');
            $iEvent->setProperty('created', $event->getCreateTime());
            $iEvent->setProperty('summary', $event->getTitle());
            $description = trim(str_replace(array('&nbsp;', '&#160;'), ' ', strip_tags($event->getDescription())));
            $description = preg_replace('/[ \t]+/', ' ', $description);
            $description = preg_replace("/^\s*$\n/m", '', $description);
            $iEvent->setProperty('description', $description);

            if ( $venues ) {
                $venue = $venues[0];
                $iEvent->setProperty('geo', $venue->getLat(), $venue->getLng());
                $iEvent->setProperty('location', trim($venue->getAddress2().', ', ', ').$venue->getAddress1(), array('ALTREP' => $owner->$pathMethod('calendar.map.view')));
            }
            $iEvent->setProperty('organizer', $creator->getEmail(), array('CN' => $creator->getFirstname().' '.$creator->getLastname()));
            $iEvent->setProperty('status', 'CONFIRMED');
            if ( $categories ) {
                $s = '';
                foreach ( $categories as $cat ) $s .= $cat->getCategory()->getName().', ';
                $iEvent->setProperty('categories', trim($s, ', '));
            }

            if ( $documents ) {
                foreach ( $documents as $doc ) {
                    $mime = ( ($mime = $doc->getMimeType()) ) ? $mime : 'application/binary';
                    $iEvent->setProperty('attach', $owner->$pathMethod('calendar.event.docget/docid').$doc->getId().'/id/'.$event->getId(), array('fmttype' => $mime));
                }
            }

            /**
             *  Attendee
             */
            $attendees = $event->getAttendee()->setFetchMode('object')->setDateFilter($event->getDtstart())->getList();
            if ( $attendees ) {
                $anyInvite = ( $event->getInvite()->getAllowGuestToInvite() ) ? 1 : 0;
                foreach ( $attendees as $at ) {

                    $atOwner = $at->getOwner();
                    $atOEmail = $at->getOwner()->getEmail();
                    if ( $at->getAnswer() === 'YES' )       $answer = 'ACCEPTED';
                    elseif ( $at->getAnswer() === 'NO' )    $answer = 'DECLINED';
                    elseif ( $at->getAnswer() === 'MAYBE' ) $answer = 'TENTATIVE';
                    else                                    $answer = 'NEEDS-ACTION';

                    if ( $at->getOwnerType() === 'user' ) {
                        if ( NULL === $atOwner->getId() ) {
                            $iEvent->setProperty('attendee', $atOEmail, array(
                                'CUTYPE'        => 'INDIVIDUAL',
                                'ROLE'          => 'REQ-PARTICIPANT',
                                'PARTSTAT'      => $answer,
                                'CN'            => ( ($name = $at->getName()) ) ? $name : substr($atOEmail, 0, strpos($atOEmail, '@')),
                                'X-NUM-GUESTS'  => $anyInvite
                            ));
                        } else {
                            $iEvent->setProperty('attendee', $atOEmail, array(
                                'CUTYPE'        => 'INDIVIDUAL',
                                'ROLE'          => ($atOwner->getId() === $creator->getId()) ? 'CHAIR' : 'REQ-PARTICIPANT',
                                'PARTSTAT'      => $answer,
                                'CN'            => $atOwner->getFirstname().' '.$atOwner->getLastname(),
                                'X-NUM-GUESTS'  => $anyInvite,
                                'DIR'           => $atOwner->getUserPath('profile')
                            ));
                        }
                    } else if ( $at->getOwnerType() === 'fbuser' ) {
                        $iEvent->setProperty('attendee', $at->getName(), array(
                            'CUTYPE'        => 'INDIVIDUAL',
                            'ROLE'          => 'REQ-PARTICIPANT',
                            'PARTSTAT'      => $answer,
                            'CN'            => $at->getName(),
                            'X-NUM-GUESTS'  => $anyInvite
                        ));
                    }
                }
            }

            $reminders = $event->getReminders()->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getList();
            if ( $reminders && $user->getEmail() ) {
                foreach ( $reminders as $reminder ) {
                    if ( $user->getId() !== $owner->getId() && $reminder->getEntireGuests() === FALSE )
                        break;

                    $iAlarm = new valarm();
                    $iAlarm->setProperty('action', "EMAIL");
                    $iAlarm->setProperty('trigger', array('sec' => $reminder->getDuration()), array("related" => "start"));
                    $iAlarm->setProperty('repeat', 1);

                    $iCalendar->setComponent($iAlarm);
                }
            }

            $date = new Zend_Date($event->getDtstart(), Zend_Date::ISO_8601);
            $estart = array(
                'year' => $date->get(Zend_Date::YEAR),
                'month' => $date->get(Zend_Date::MONTH),
                'day'   => $date->get(Zend_Date::DAY),
                'hour'  => $date->get(Zend_Date::HOUR),
                'min'   => $date->get(Zend_Date::MINUTE),
                'sec'   => $date->get(Zend_Date::SECOND)
            );
            if ( !$event->isAllDay() && $event->getTimezone() ) {
                $estart['tz'] = $event->getTimezone();
            }
            $iEvent->setProperty('dtstart', $estart);
            if ( $event->isAllDay() ) {
                $iEvent->setProperty('duration', array('day' => 1));
            } else {
                $date = new Zend_Date($event->getDtend(), Zend_Date::ISO_8601);
                $eend = array(
                    'year' => $date->get(Zend_Date::YEAR),
                    'month' => $date->get(Zend_Date::MONTH),
                    'day'   => $date->get(Zend_Date::DAY),
                    'hour'  => $date->get(Zend_Date::HOUR),
                    'min'   => $date->get(Zend_Date::MINUTE),
                    'sec'   => $date->get(Zend_Date::SECOND),
                    'tz'    => $event->getTimezone()
                );
                $iEvent->setProperty('dtend', $eend);
            }

            if ( ($rrule = $event->getRrule()) != NULL ) {
                $prop = array(
                    'freq'  => $rrule->getFreq(),
                    'interval' => $rrule->getInterval()
                );
                if ( $rrule->getUntil() !== NULL )
                    $prop['until'] = $rrule->getUntil();
                switch ( $rrule->getFreq() ) {
                case 'DAILY':
                    if ( $rrule->getByDay() )
                        $prop['byday'] = join(', ',$rrule->getByDay());
                    break;
                case 'WEEKLY':
                    if ( $rrule->getByDay() )
                        $prop['wkst'] = $rrule->getWkst();
                    break;
                case 'MONTHLY':
                    if ( $rrule->getBySetPos() ) {
                        $prop['byday'] = join(',', $rrule->getByDay());
                        $prop['bysetpos'] = join(',', $rrule->getBySetPos());
                    } else {
                        $prop['bymonthday'] = join(',', $rrule->getByMonthDay());
                    }
                    break;
                case 'YEARLY':
                    $prop['bymonth'] = join(',', $rrule->getByMonth());
                    if ( $rrule->getBySetPos() ) {
                        $prop['freq'] = 'MONTHLY';
                        $prop['byday'] = join(',', $rrule->getByDay());
                        $prop['bysetpos'] = join(',', $rrule->getBySetPos());
                    } else {
                        $prop['bymonthday'] = join(',', $rrule->getByMonthDay());
                    }
                    break;
                }
                $iEvent->setProperty('rrule', $prop);
            }
            if ( $exdates = $event->getExDates()->getAll() ) {
                $iEvent->setProperty('exdate', $exdates);
            }

            $iCalendar->setComponent( $iEvent );
        }

        $output = $iCalendar->createCalendar();
        $output = preg_replace("/\n/", "\r\n", $output); //  For M$ Outlook
        return $output;
    }
}

