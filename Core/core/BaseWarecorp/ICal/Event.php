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

class BaseWarecorp_ICal_Event implements  Warecorp_Global_iSearchFields
{
	private $DbConn;
	private $id;
	private $uid;
    private $refId;
    private $rootId;
	private $creatorId;
	protected $ownerId;
	private $ownerType;
	private $privacy;
	private $created;
	private $recurrenceId;
    private $recurrenceType;
	private $title;
	private $description;
	private $rrule;
	private $dtstart;
	private $dtend;
	private $duration;
	private $timezone;
	private $original_dtstart;
	private $original_dtend;
	private $original_timezone;
	private $allDay;
    private $pictureId;
    private $contactName;
    private $contactEmail;
    private $contactPhone;
    private $markerGroupId;
    private $maxRSVP;
    private $eventRequestFacilitator;
    private $eventIsPartOfRound;
    
    /**
     * used by sending letters to customer
     */
    private $httpContext;

    private $whithTimezone;

    /**
    * dynamic properties
    */
    private $exDates;
    private $recurrences;
    private $attendee;
    private $creator;
    protected $owner;
    private $invite;
    private $rootEvent;
    private $categories;
    private $sharing;
    private $reminders;
    private $tags;
    private $documents;
    private $lists;
    private $venues;
    private $eventVenue;

    private $eventPicture;
    private $isExpired;

    private $dtstartCahce = array();
    private $dtendCache = array();
    private $originalDtstartCahce = array();
    public $EntityTypeName = 'event';
    /**
     * @author Roman Gabrusenok
     * @see Warecorp_Data_Entity::__construct
     * @var int
     */
    public $EntityTypeId = 6;
    /**
    * @desc
    */
    public $weight;
    protected static $copyIds;
    protected static $useFamilyAvatarAsIcon = false;

    
    public static function createEvent($eventId)
    {

    }

    public static function createEventCopy($eventId, $eventUid, $year, $month, $day, $currentTimezone)
    {

    }

	/**
	 *
	 */
	public function __construct($eventId = null)
	{
		$this->DbConn = Zend_Registry::get('DB');
		if ( $this->DbConn === null ) throw new Warecorp_Exception('Database connection is not set.');

		if ( null !== $eventId ) $this->loadById($eventId);

        /**
        * Загрузка весов для формирования тегов
        */
        $cfgWeight = Warecorp_Config_Loader::getInstance()->getCoreConfig('cfg.weight.xml');

        $_type = 'event';
        if ( isset($cfgWeight->$_type) ) $this->weight = $cfgWeight->$_type;
	}
	/**
	 *
	 */
	public function getId()
	{
		return $this->id;
	}
	/**
	 *
	 * @param newVal
	 */
	public function setId($newVal)
	{
		$this->id = $newVal;
		return $this;
	}
	/**
	 *
	 */
	public function getUid()
	{
		return $this->uid;
	}
	/**
	 *
	 * @param newVal
	 */
	public function setUid($newVal)
	{
		$this->uid = $newVal;
		return $this;
	}
    /**
     *
     */
    public function getRefId()
    {
        return $this->refId;
    }
    /**
     *
     * @param newVal
     */
    public function setRefId($newVal)
    {
        $this->refId = $newVal;
        return $this;
    }
    /**
     *
     */
    public function getRootId()
    {
        return $this->rootId;
    }
    /**
     *
     * @param newVal
     */
    public function setRootId($newVal)
    {
        $this->rootId = $newVal;
        return $this;
    }
	/**
	* @desc
	*/
	public function setCreatorId($newVal)
	{
		$this->creatorId = $newVal;
		return $this;
	}
	/**
	* @desc
	*/
	public function getCreatorId()
	{
		if ( null === $this->creatorId ) throw new Warecorp_ICal_Exception('Creator ID is not set');
		return $this->creatorId;
	}
	/**
	* @desc
	*/
	public function setOwnerId($newVal)
	{
		$this->ownerId = $newVal;
		return $this;
	}
	/**
	* @desc
	*/
	public function getOwnerId()
	{
		if ( null === $this->ownerId ) throw new Warecorp_ICal_Exception('Owner ID is not set');
		return $this->ownerId;
	}
	/**
	* @desc
	*/
	public function setOwnerType($newVal)
	{
		if ( !Warecorp_ICal_Enum_Privacy::inEnum($newVal) ) throw new Warecorp_ICal_Exception('Incorrect Owner Type');
		$this->ownerType = $newVal;
		return $this;
	}
	/**
	* @desc
	*/
	public function getOwnerType()
	{
		if ( null === $this->ownerType ) throw new Warecorp_ICal_Exception('Owner Type is not set');
		return $this->ownerType;
	}
	/**
	* @desc
	*/
	public function setCreateTime($newVal)
	{
		$this->created = $newVal;
		return $this;
	}
	/**
	* @desc
	*/
	public function getCreateTime()
	{
		return $this->created;
	}
	/**
	* @desc
	*/
	public function setPrivacy($newVal)
	{
		if ( !Warecorp_ICal_Enum_Privacy::inEnum($newVal) ) throw new Warecorp_ICal_Exception('Incorrect privacy type');
		$this->privacy = $newVal;
		return $this;
	}
	/**
	* @desc
	*/
	public function getPrivacy()
	{
		if ( null === $this->privacy ) throw new Warecorp_ICal_Exception('Privacy can not be null');
		return $this->privacy;
	}
	/**
	 *
	 */
	public function getRecurrenceId()
	{
		return $this->recurrenceId;
	}
	/**
	 *
	 * @param newVal
	 */
	public function setRecurrenceId($newVal)
	{
		$this->recurrenceId = $newVal;
		return $this;
	}
	/**
	 *
	 */
	public function getTitle()
	{
		return $this->title;
	}
	/**
	 *
	 * @param newVal
	 */
	public function setTitle($newVal)
	{
		$this->title = $newVal;
		return $this;
	}
	/**
	 *
	 */
	public function getDescription()
	{
		return $this->description;
	}
	/**
	 *
	 * @param newVal
	 */
	public function setDescription($newVal)
	{
		$this->description = $newVal;
		return $this;
	}
	/**
	 * @return Warecorp_ICal_Rrule|null
	 */
	public function getRrule()
	{
		return $this->rrule;
	}
	/**
	 *
	 * @param newVal
	 */
	public function setRrule(Warecorp_ICal_Rrule $newVal)
	{
		$this->rrule = $newVal;
		return $this;
	}
	/**
	 *
	 * @param newVal
	 */
	public function setRruleToNULL()
	{
		$this->rrule = null;
		return $this;
	}
	/**
	 * return Zend_Date object created from dtstart string in current timezone of event or in UTC timezone if timezone isn't recognized
	 * @return Zend_Date
	 */
	public function getDtstart()
	{
		if ( null === $this->dtstart ) throw new Warecorp_ICal_Exception('Event start date is not set.');
		$tz = ($this->getTimezone()) ? $this->getTimezone() : 'UTC';
		if ( !isset($this->dtstartCahce[$tz]) ) {
			$defaultTimeZone = date_default_timezone_get();
			date_default_timezone_set($tz);
			$this->dtstartCahce[$tz] = new Zend_Date($this->dtstart, Zend_Date::ISO_8601, 'en_US');
			date_default_timezone_set($defaultTimeZone);

		}
		return $this->dtstartCahce[$tz];
    }
    /**
     * return value of dtstart of event as it has been saved in database
	 * @return string
	 */
	public function getDtstartValue()
	{
		if ( null === $this->dtstart ) throw new Warecorp_ICal_Exception('Event start date is not set.');
		return $this->dtstart;
	}
	/**
	 * save to object dtstart value as string
	 * @param newVal
	 */
	public function setDtstart($newVal)
	{
		if ( !is_string($newVal) )  throw new Warecorp_ICal_Exception('dtstart must be string');
		$this->dtstart = $newVal;
        $this->dtstartCahce = array();
        /**
         *
         */
        $objConvertedDate = clone $this->getDtstart();
        $objConvertedDate->setTimezone(($this->getOriginalTimezone()) ? $this->getOriginalTimezone() : 'UTC');
        $this->setOriginalDtstart($objConvertedDate->get(Zend_Date::ISO_8601));
		return $this;
	}
	/**
     * return Zend_Date object created from dtend string in current timezone of event or in UTC timezone if timezone isn't recognized
     * @return Zend_Date
	 */
	public function getDtend()
	{
		if ( null === $this->dtend ) throw new Warecorp_ICal_Exception('Event end date is not set.');
		$tz = ($this->getTimezone()) ? $this->getTimezone() : 'UTC';
		if ( !isset($this->dtendCache[$tz]) ) {
			$defaultTimeZone = date_default_timezone_get();
			date_default_timezone_set($tz);
			$this->dtendCache[$tz] = new Zend_Date($this->dtend, Zend_Date::ISO_8601);
			date_default_timezone_set($defaultTimeZone);

		}
		return $this->dtendCache[$tz];
	}
	/**
     * return value of dtend of event as it has been saved in database
     * @return string
	 */
	private function getDtendValue()
	{
		if ( null === $this->dtend ) throw new Warecorp_ICal_Exception('Event end date is not set.');
		return $this->dtend;
	}
	/**
	 * save to object dtend value as string
	 * @param newVal
	 */
	public function setDtend($newVal)
	{
		if ( !is_string($newVal) )  throw new Warecorp_ICal_Exception('dtend must be string');
		$this->dtend = $newVal;
        $this->dtendCache = array();
        /**
         *
         */
        $objConvertedDate = clone $this->getDtend();
        $objConvertedDate->setTimezone(($this->getOriginalTimezone()) ? $this->getOriginalTimezone() : 'UTC');
        $this->setOriginalDtend($objConvertedDate->get(Zend_Date::ISO_8601));
		return $this;
	}
	/**
	 *
	 */
	public function getDuration()
	{
		return $this->duration;
	}
	/**
	 * calculate duration of event in sec from dtstart and dtend
	 * @return int
	 */
	public function getDurationSec()
	{
		return $this->getDtend()->getTimestamp() - $this->getDtstart()->getTimestamp();
	}
	/**
	 *
	 * @param newVal
	 */
	public function setDuration($newVal)
	{
		$this->duration = $newVal;
		return $this;
	}
	/**
	 *
	 */
	public function setTimezone($newVal)
	{
		$this->timezone = $newVal;
		return $this;
	}
	/**
	 *
	 */
	public function getTimezone()
	{
		return $this->timezone;
	}
    /**
     * return Zend_Date object created from original dtstart string in original event timezone (how it has been saved in database )
     * or in UTC timezone if timezone isn't recognized (when event is all day event)
     * @return Zend_Date
     */
    public function getOriginalDtstart()
    {
    	/*
        $objConvertedDate = clone $this->getDtstart();
        $objConvertedDate->setTimezone(($this->getOriginalTimezone()) ? $this->getOriginalTimezone() : 'UTC');
        return $objConvertedDate;
    	*/
        if ( null === $this->original_dtstart ) throw new Warecorp_ICal_Exception('Event original start date is not set.');
        $tz = ($this->getOriginalTimezone()) ? $this->getOriginalTimezone() : 'UTC';
        $defaultTimeZone = date_default_timezone_get();
        date_default_timezone_set($tz);
        $originalDtstart = new Zend_Date($this->original_dtstart, Zend_Date::ISO_8601);
        date_default_timezone_set($defaultTimeZone);
        return $originalDtstart;
    }
    /**
     * return original dtstart of event as string
     * @return string
     */
    public function getOriginalDtstartValue()
    {
        if ( null === $this->original_dtstart ) throw new Warecorp_ICal_Exception('Event original start date is not set.');
        return $this->original_dtstart;
    }
    /**
     * save to object original_dtstart value as string
     * @param string $newVal
     */
    public function setOriginalDtstart($newVal)
    {
        if ( !is_string($newVal) )  throw new Warecorp_ICal_Exception('original dtstart must be string');
        $this->original_dtstart = $newVal;
        return $this;
    }
    /**
     * return Zend_Date object created from original dtend string in original event timezone (how it has been saved in database )
     * or in UTC timezone if timezone isn't recognized (when event is all day event)
     */
    public function getOriginalDtend()
    {
    	/*
        $objConvertedDate = clone $this->getDtend();
        $objConvertedDate->setTimezone(($this->getOriginalTimezone()) ? $this->getOriginalTimezone() : 'UTC');
        return $objConvertedDate;
        */
        if ( null === $this->original_dtend ) throw new Warecorp_ICal_Exception('Event original end date is not set.');
        $tz = ($this->getOriginalTimezone()) ? $this->getOriginalTimezone() : 'UTC';
        $defaultTimeZone = date_default_timezone_get();
        date_default_timezone_set($tz);
        $originalDtend = new Zend_Date($this->original_dtend, Zend_Date::ISO_8601);
        date_default_timezone_set($defaultTimeZone);
        return $originalDtend;
    }
    /**
     * return original dtend of event as string
     * @return string
     */
    public function getOriginalDtendValue()
    {
        if ( null === $this->original_dtend ) throw new Warecorp_ICal_Exception('Event original end date is not set.');
        return $this->original_dtend;
    }
    /**
     * save to object original_dtend value as string
     * @param string $newVal
     */
    public function setOriginalDtend($newVal)
    {
        if ( !is_string($newVal) )  throw new Warecorp_ICal_Exception('original dtend must be string');
        $this->original_dtend = $newVal;
        return $this;

    }
    /**
	 * return original timezone as it has been saved in database
	 * @return string
	 */
	public function getOriginalTimezone()
	{
		return $this->original_timezone;
	}
	/**
	 *
	 */
	public function getHttpContext()
	{
	    if ( null === $this->httpContext ) {
	        if ( defined('HTTP_CONTEXT') ) $this->setHttpContext(HTTP_CONTEXT);
	        else $this->httpContext = 'zanby';
	    }
	    return $this->httpContext;
	}
	/**
	 *
	 */
	public function setHttpContext($value)
	{
	    $this->httpContext = $value;
	    return $this;
	}
    /**
    * @desc
    */
	public function isTimezoneExists()
    {
        return (boolean) $this->whithTimezone;
    }
    /**
	 *
	 */
	public function setAllDay($newVal)
	{
		$this->allDay = (boolean) $newVal;
	}
	/**
	 *
	 */
	public function isAllDay()
	{
		return (boolean) $this->allDay;
	}
	/**
	 * @return Warecorp_ICal_ExDate object
	 */
	public function getExDates()
	{
        /**
        * Закоменченно, каждый раз надо создавать новый объект и загружать его
        * т.к. происходят ошибки при кешировании в частности при cancel события
        */
		//if ( null === $this->exDates ) {
			$this->exDates = new Warecorp_ICal_ExDate($this);
			$this->exDates->loadByEvent();
		//}
		return $this->exDates;
	}
	/**
	 * @return Warecorp_ICal_Event_List_Recurrence object
	 */
	public function getRecurrences()
	{
		if ( null === $this->recurrences ) $this->recurrences = new Warecorp_ICal_Event_List_Recurrence($this);
		return $this->recurrences;
	}
    /**
    * @return Warecorp_ICal_Event_List_Category object
    */
    public function getCategories()
    {
        if ( null == $this->categories ) $this->categories = new Warecorp_ICal_Event_List_Category($this);
        return $this->categories;
    }
    /**
    * @return Warecorp_ICal_Event_List_Sharing object
    */
    public function getSharing()
    {
        if ( null === $this->sharing ) {
            $this->sharing = new Warecorp_ICal_Event_List_Sharing($this);
        }
        return $this->sharing;
    }
    /**
    * @return Warecorp_ICal_Event_List_Reminder object
    */
    public function getReminders()
    {
        if ( null === $this->reminders ) {
            $this->reminders = new Warecorp_ICal_Event_List_Reminder($this);
        }
        return $this->reminders;
    }
    /**
    * Фукнция строко связывает класс с конкретной реализацией классов-владельцев
    * Должна быть изменена при переносе модуля в другой проект
    */
    public function getCreator()
    {
        if ( null === $this->creator ) {
            $this->creator = new Warecorp_User('id', $this->getCreatorId());
        }
        return $this->creator;
    }
    /**
    * Фукнция строко связывает класс с конкретной реализацией классов-владельцев
    * Должна быть изменена при переносе модуля в другой проект
    */
    public function getOwner()
    {
        if ( null === $this->owner ) {
            if ( $this->getOwnerType() == Warecorp_ICal_Enum_OwnerType::USER ) {
                $this->owner = new Warecorp_User('id', $this->getOwnerId());
            } elseif ( $this->getOwnerType() == Warecorp_ICal_Enum_OwnerType::GROUP ) {
                $this->owner = Warecorp_Group_Factory::loadById($this->getOwnerId());
            }
        }
        return $this->owner;
    }
    /**
    * @desc
    */
    public function setPictureId($newValue)
    {
        $this->pictureId = $newValue;
        return $this;
    }
    /**
    * @desc
    */
    public function getPictureId()
    {
        return $this->pictureId;
    }
    /**
    * Фукнция строко связывает класс с конкретной реализацией классов-фотографий
    * Должна быть изменена при переносе модуля в другой проект
     * @return Warecorp_Photo_Abstract | null
    */
    public function getEventPicture()
    {
        if ( (null === $this->eventPicture && null != $this->pictureId) || (null !== $this->eventPicture && $this->eventPicture->getId() === $this->pictureId) ) {
            $this->eventPicture = Warecorp_Photo_Factory::loadById($this->getPictureId());
        }elseif (null !== $this->eventPicture){
            return $this->eventPicture;
        }
        elseif ( Warecorp_ICal_Event::getUseFamilyAvatarAsIcon()) {
            $impl_context = $this->getHttpContext();
            if ($impl_context !== null) {
                  $gfGroup = Warecorp_Group_Factory::loadByGroupUIDWithoutException($impl_context);
                  if ($gfGroup !== null){
                        $this->eventPicture = $gfGroup->getAvatar();   
                  }
            }
        }
        return $this->eventPicture;
    }
    
         /**
     *
     */
    public static function getUseFamilyAvatarAsIcon()
    {
        return Warecorp_ICal_Event::$useFamilyAvatarAsIcon;
    }
    /**
     *
     * @param newVal
     */
    public static function setUseFamilyAvatarAsIcon($newVal)
    {
        Warecorp_ICal_Event::$useFamilyAvatarAsIcon = $newVal;
    }
    /**
    * Фукнция строко связывает класс с конкретной реализацией классов-тегов
    * Должна быть изменена при переносе модуля в другой проект
    */
    public function getTags()
    {
        if ( null === $this->tags ) {
            $this->tags = new Warecorp_ICal_Event_List_Tag($this->getRootEvent());
        }
        return $this->tags;
    }
    /**
    * @return Warecorp_ICal_Attendee_List object
    */
    public function getAttendee()
    {
        if ( null === $this->attendee ) {
            $this->attendee = new Warecorp_ICal_Attendee_List($this);
        }
        return $this->attendee;
    }
    /**
    * @return Warecorp_ICal_Event_List_Document
    */
    public function getDocuments()
    {
        if ( null === $this->documents ) {
            $this->documents = new Warecorp_ICal_Event_List_Document($this);
        }
        return $this->documents;
    }
    /**
    * @return Warecorp_ICal_Event_List_List
    */
    public function getLists()
    {
        if ( null === $this->lists ) {
            $this->lists = new Warecorp_ICal_Event_List_List($this);
        }
        return $this->lists;
    }
    /**
    * @return Warecorp_ICal_Event_List_Venue
    */
    public function getVenues()
    {
        if ( null === $this->venues ) {
            $this->venues = new Warecorp_ICal_Event_List_Venue($this);
        }
        return $this->venues;
    }
    /**
    * @return Warecorp_Venue_Item $objVenue or null
    */
    public function getEventVenue()
    {
        if ( null === $this->eventVenue ) {
            $this->eventVenue = $this->getVenues()->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getList();
            if ( sizeof($this->eventVenue) != 0 ) {
                $this->eventVenue = $this->eventVenue[0];
            } else {
                $this->eventVenue = null;
            }
        }
        return $this->eventVenue;
    }
    /**
    * @return Warecorp_ICal_Invite
    */
    public function getInvite()
    {
        if ( null === $this->invite ) {
            $this->invite = new Warecorp_ICal_Invitation();
            $this->invite->loadByEventId($this->getId());
        }
        return $this->invite;
    }
    /**
    * @return Warecorp_ICal_Event
    */
    public function getRootEvent()
    {
        if ( null === $this->rootEvent ) {
            if ( $this->getId() == $this->getRootId() ) return $this;
            else {
                $this->rootEvent = new Warecorp_ICal_Event($this->getRootId());
            }
            /*
            $objRef = new Warecorp_ICal_Event_List_Reference($this);
            $rootId = $objRef->getRootId();
            $this->rootEvent = new Warecorp_ICal_Event($rootId);
            unset($objRef);
            */
        }
        return $this->rootEvent;
    }
    
    /**
     * @return unknown
     */
    public function getContactEmail()
    {
        return $this->contactEmail;
    }
    
    /**
     * @param unknown_type $contactEmail
     */
    public function setContactEmail( $contactEmail )
    {
        $this->contactEmail = $contactEmail;
    }
    
    /**
     * @return unknown
     */
    public function getContactName()
    {
        return $this->contactName;
    }
    
    /**
     * @param unknown_type $contactName
     */
    public function setContactName( $contactName )
    {
        $this->contactName = $contactName;
    }
    
    /**
     * @return unknown
     */
    public function getContactPhone()
    {
        return $this->contactPhone;
    }
    
    /**
     * @param unknown_type $contactPhone
     */
    public function setContactPhone( $contactPhone )
    {
        $this->contactPhone = $contactPhone;
    }
    
    /**
     * @return unknown
     */
    public function getMarkerGroupId()
    {
        return $this->markerGroupId;
    }
    
    /**
     * @param unknown_type $markerGroupId
     */
    public function setMarkerGroupId( $markerGroupId )
    {
        $this->markerGroupId = $markerGroupId;
    }

    public function getEventRequestFacilitator()
    {
        return $this->eventRequestFacilitator;
    }

    public function setEventRequestFacilitator($value)
    {
        $this->eventRequestFacilitator = $value;
    }

    public function getEventIsPartOfRound()
    {
        return $this->eventIsPartOfRound;
    }

    public function setEventIsPartOfRound($value)
    {
        $this->eventIsPartOfRound = $value;
    }

    /**
     * getMaxRsvp
     * 
     * @access public
     * @return int|NULL
     */
    public function getMaxRsvp()
    {
        return $this->maxRSVP;
    }

    /**
     * setMaxRsvp
     * 
     * @param int|NULL $number 
     * @access public
     * @return void
     */
    public function setMaxRsvp( $number )
    {
        if ( $number === NULL ) {
            $this->maxRSVP = NULL;
        } else {
            $this->maxRSVP = $number + 0;
        }
    }

    
    /**
    * @desc
    */
    public function convertTZ($objDate, $timezone)
    {
        $objConvertedDate = clone $objDate;
        $objConvertedDate->setTimezone($timezone);
        return $objConvertedDate;
    }
    /**
     * Enter description here...
     *
     */
    public function displayDate($displayMode, $objUser = null, $currentTimezone = null)
    {
        $return = '';

    	$currentTimezone   = ( null !== $currentTimezone )     ? $currentTimezone : $this->getTimezone();    	
    	$currentTimezone   = ( null === $currentTimezone )     ? 'UTC' : $currentTimezone;
    	$originalTimezone  = ( $this->getOriginalTimezone() )  ? $this->getOriginalTimezone() : 'UTC';
    	
    	$objDtstart            = null;
    	$objDtstartAlternate   = null;
        $objDtend              = null;
        $objDtendAlternate     = null;
        /**
         * Setup Locale
         */
        $locale = Zend_Registry::get('Zend_Locale');//new Zend_Locale('en_US');
		/*
		$locale1 = new Zend_Locale(Zend_Locale::BROWSER);         // default behavior, same as above
		$locale2 = new Zend_Locale(Zend_Locale::ENVIRONMENT);     // prefer settings on host server
		$locale3 = new Zend_Locale(Zend_Locale::FRAMEWORK);       // perfer framework app default settings
		Zend_Locale::setDefault('de');
        */
        
    	/**
    	 * Anonymous user
    	 */
        $userID = ( $objUser && $objUser instanceof Warecorp_User ) ? $objUser->getId() : $objUser;
    	//if ( !$objUser || null === $objUser->getId() ) {
    	if ( !$userID ) {
    		$objDtstart = $this->getOriginalDtstart();
    		$objDtend = $this->getOriginalDtend();
    		$objDtstart->setLocale($locale);
    		$objDtend->setLocale($locale);
    		
    		/**
    		 * set up timezone to display as timezone from event
    		 * if event timezone is null (all day event) we can use any timezone (current used UTC)
    		 * if timezone isn't defined it shows as GMT +/- N - it isn't correct
    		 * @author Artem Sukharev
    		 */
            $objDtstart->setTimezone( $originalTimezone );
            $objDtend->setTimezone( $originalTimezone );
    	}
    	/**
    	 * Registered user
    	 */
    	else {
    		if ( Warecorp_ICal_Calendar_Cfg::getShowOriginalTime() && Warecorp_ICal_Calendar_Cfg::getShowUserTime() ) {
	            $objDtstart = $this->convertTZ($this->getDtstart(), $currentTimezone);
	            $objDtstartAlternate = $this->getOriginalDtstart();
	            $objDtend = $this->convertTZ($this->getDtend(), $currentTimezone);
	            $objDtendAlternate = $this->getOriginalDtend();
	            $objDtstart->setLocale($locale);
	            $objDtend->setLocale($locale);
	            $objDtstartAlternate->setLocale($locale);
	            $objDtendAlternate->setLocale($locale);
	            
                /**
                 * set up timezone to display as timezone from event
                 * if event timezone is null (all day event) we can use any timezone (current used UTC)
                 * if timezone isn't defined it shows as GMT +/- N - it isn't correct
                 * @author Artem Sukharev
                 */
                $objDtstartAlternate->setTimezone( $originalTimezone );
                $objDtendAlternate->setTimezone( $originalTimezone );
	            
    		} elseif ( Warecorp_ICal_Calendar_Cfg::getShowOriginalTime() ) {
                $objDtstart = $this->getOriginalDtstart();
                $objDtend = $this->getOriginalDtend();
                $objDtstart->setLocale($locale);
                $objDtend->setLocale($locale);
                
                /**
                 * set up timezone to display as timezone from event
                 * if event timezone is null (all day event) we can use any timezone (current used UTC)
                 * if timezone isn't defined it shows as GMT +/- N - it isn't correct
                 * @author Artem Sukharev
                 */
                $objDtstart->setTimezone( $originalTimezone );
                $objDtend->setTimezone( $originalTimezone );
                
    		} else {
                $objDtstart = $this->convertTZ($this->getDtstart(), $currentTimezone);
                $objDtend = $this->convertTZ($this->getDtend(), $currentTimezone);
                $objDtstart->setLocale($locale);
                $objDtend->setLocale($locale);
    		}
    	}
        switch ( strtolower($displayMode) ) {
        	case 'list.view' :
            case 'list.view.family' :
            case 'list.view.family.members' :
        	case 'attendee.view' :
            case 'profile.my.groups' :
        		$return .= '<strong>'.$objDtstart->toString(Warecorp_Date::DATE_MEDIUM).'</strong>';
        		if ( $this->isAllDay() ) $return .= '&#160;All Day';
        		else $return .= '&#160;'.$objDtstart->toString(Warecorp_Date::TIME_SHORT).(($this->isTimezoneExists() && Warecorp_ICal_Calendar_Cfg::getShowTimezoneAbbr()) ? ' '.$objDtstart->get(Zend_Date::TIMEZONE) : '');
        		if ( !$this->isAllDay() && $objDtstartAlternate && $objDtstart->getTimezone() != $objDtstartAlternate->getTimezone() ) {
        			$return .= '<br>(';
	                $return .= '<strong>'.$objDtstartAlternate->toString(Warecorp_Date::DATE_MEDIUM).'</strong>';
	                if ( $this->isAllDay() ) $return .= '&#160;All Day';
	                else $return .= '&#160;'.$objDtstartAlternate->toString(Warecorp_Date::TIME_SHORT).(($this->isTimezoneExists()) ? ' '.$objDtstartAlternate->get(Zend_Date::TIMEZONE) : '');
	                $return .= ')';
        		}
        		break;

        	case 'event.view' :
                $return .= ( $objDtstart->toString(Warecorp_Date::DATE_MEDIUM) != $objDtend->toString(Warecorp_Date::DATE_MEDIUM) ) ? $objDtstart->toString(Warecorp_Date::DATE_MEDIUM).' - '.$objDtend->toString(Warecorp_Date::DATE_MEDIUM) : $objDtstart->toString(Warecorp_Date::DATE_MEDIUM);
                $return .= '<br>';
                if ( $this->isAllDay() ) $return .= '&#160;All Day';
                else $return .= $objDtstart->toString(Warecorp_Date::TIME_SHORT).' - '.$objDtend->toString(Warecorp_Date::TIME_SHORT).( ($this->isTimezoneExists() && Warecorp_ICal_Calendar_Cfg::getShowTimezoneAbbr()) ? ' '.$objDtstart->get(Zend_Date::TIMEZONE) : '' );
                if ( !$this->isAllDay() && $objDtstartAlternate && $objDtstart->getTimezone() != $objDtstartAlternate->getTimezone() ) {
	                $return .= '<br>(';
                	$return .= ( $objDtstartAlternate->toString(Warecorp_Date::DATE_MEDIUM) != $objDtendAlternate->toString(Warecorp_Date::DATE_MEDIUM) ) ? $objDtstartAlternate->toString(Warecorp_Date::DATE_MEDIUM).' - '.$objDtendAlternate->toString(Warecorp_Date::DATE_MEDIUM) : $objDtstartAlternate->toString(Warecorp_Date::DATE_MEDIUM);
	                $return .= '<br>';
	                if ( $this->isAllDay() ) $return .= '&#160;All Day';
	                else $return .= $objDtstartAlternate->toString(Warecorp_Date::TIME_SHORT).' - '.$objDtendAlternate->toString(Warecorp_Date::TIME_SHORT).( ($this->isTimezoneExists()) ? ' '.$objDtstartAlternate->get(Zend_Date::TIMEZONE) : '' );
                	$return .= ')';
                }
                break;
            case 'month.view.day.details' :
            	$return .= $objDtstart->toString(Warecorp_Date::DATE_FULL);
            	$return .= '<br>';
            	if ( $this->isAllDay() ) $return .= 'All Day';
            	else $return .= $objDtstart->toString(Warecorp_Date::TIME_SHORT).(($this->isTimezoneExists() && Warecorp_ICal_Calendar_Cfg::getShowTimezoneAbbr()) ? ' '.$objDtstart->get(Zend_Date::TIMEZONE) : '');
            	if ( !$this->isAllDay() && $objDtstartAlternate && $objDtstart->getTimezone() != $objDtstartAlternate->getTimezone() ) {
                    $return .= '<br>(';
                    $return .= $objDtstartAlternate->toString(Warecorp_Date::DATE_FULL);
                    $return .= '<br>';
                    if ( $this->isAllDay() ) $return .= 'All Day';
                    else $return .= $objDtstartAlternate->toString(Warecorp_Date::TIME_SHORT).' - '.$objDtendAlternate->toString(Warecorp_Date::TIME_SHORT).( ($this->isTimezoneExists()) ? ' '.$objDtstartAlternate->get(Zend_Date::TIMEZONE) : '' );
                    $return .= ')';
            	}
            	break;
            case 'index.page.events' :
            case 'search.index.events' :
                //$return .= '<strong>'.$objDtstart->toString(Warecorp_Date::DATE_MEDIUM).'</strong>';
                //$return .= '<br>';
                if ( $this->isAllDay() ) $return .= 'All Day';
                else $return .= ''.$objDtstart->toString(Warecorp_Date::TIME_SHORT).(($this->isTimezoneExists() && Warecorp_ICal_Calendar_Cfg::getShowTimezoneAbbr()) ? ' '.$objDtstart->get(Zend_Date::TIMEZONE) : '');
                if ( !$this->isAllDay() && $objDtstartAlternate && $objDtstart->getTimezone() != $objDtstartAlternate->getTimezone() ) {
                    $return .= '<br>(';
                    //$return .= '<strong>'.$objDtstartAlternate->toString(Warecorp_Date::DATE_MEDIUM).'</strong>';
                    //$return .= '<br>';
                    if ( $this->isAllDay() ) $return .= 'All Day';
                    else $return .= ''.$objDtstartAlternate->toString(Warecorp_Date::TIME_SHORT).(($this->isTimezoneExists()) ? ' '.$objDtstartAlternate->get(Zend_Date::TIMEZONE) : '');
                    $return .= ')';
                }
                break;
            case 'search.results' :
            case 'nda.search.results' :
                $return .= $objDtstart->toString(Warecorp_Date::DATE_MEDIUM);
                $return .= '<br>';
                if ( $this->isAllDay() ) $return .= 'All Day';
                else $return .= ''.$objDtstart->toString(Warecorp_Date::TIME_SHORT).(($this->isTimezoneExists() && Warecorp_ICal_Calendar_Cfg::getShowTimezoneAbbr()) ? ' '.$objDtstart->get(Zend_Date::TIMEZONE) : '');
                if ( !$this->isAllDay() && $objDtstartAlternate && $objDtstart->getTimezone() != $objDtstartAlternate->getTimezone() ) {
                    $return .= '<br>(';
                    $return .= $objDtstartAlternate->toString(Warecorp_Date::DATE_MEDIUM);
                    $return .= '<br>';
                    if ( $this->isAllDay() ) $return .= 'All Day';
                    else $return .= ''.$objDtstartAlternate->toString(Warecorp_Date::TIME_SHORT).(($this->isTimezoneExists()) ? ' '.$objDtstartAlternate->get(Zend_Date::TIMEZONE) : '');
                    $return .= ')';
                }
                break;
            /**
             * SECTION : Automatically rotate featured events
             * SECTION : Manually select events to display
             */
            case 'dd.myevents.wide.auto.rotate.event.list' :
                $return .= $objDtstart->toString(Warecorp_Date::DATE_MEDIUM);
                $return .= '<br>';
                if ( $this->isAllDay() ) $return .= 'All Day';
                else $return .= ''.$objDtstart->toString(Warecorp_Date::TIME_SHORT).(($this->isTimezoneExists() && Warecorp_ICal_Calendar_Cfg::getShowTimezoneAbbr()) ? ' '.$objDtstart->get(Zend_Date::TIMEZONE) : '');
                if ( !$this->isAllDay() && $objDtstartAlternate && $objDtstart->getTimezone() != $objDtstartAlternate->getTimezone() ) {
                    $return .= '<br>(';
                    $return .= $objDtstartAlternate->toString(Warecorp_Date::DATE_MEDIUM);
                    $return .= '<br>';
                    if ( $this->isAllDay() ) $return .= 'All Day';
                    else $return .= ''.$objDtstartAlternate->toString(Warecorp_Date::TIME_SHORT).(($this->isTimezoneExists()) ? ' '.$objDtstartAlternate->get(Zend_Date::TIMEZONE) : '');
                    $return .= ')';
                }
                break;
            case 'dd.myevents.narrow.auto.rotate.event.list' :
                $return .= '<strong>'.$objDtstart->toString(Warecorp_Date::DATE_MEDIUM).'</strong>';
                $return .= '<br>';
                if ( $this->isAllDay() ) $return .= 'All Day';
                else $return .= ''.$objDtstart->toString(Warecorp_Date::TIME_SHORT).(($this->isTimezoneExists() && Warecorp_ICal_Calendar_Cfg::getShowTimezoneAbbr()) ? ' '.$objDtstart->get(Zend_Date::TIMEZONE) : '');
                if ( !$this->isAllDay() && $objDtstartAlternate && $objDtstart->getTimezone() != $objDtstartAlternate->getTimezone() ) {
                    $return .= '<br>(';
                    $return .= $objDtstartAlternate->toString(Warecorp_Date::DATE_MEDIUM);
                    $return .= '<br>';
                    if ( $this->isAllDay() ) $return .= 'All Day';
                    else $return .= ''.$objDtstartAlternate->toString(Warecorp_Date::TIME_SHORT).(($this->isTimezoneExists()) ? ' '.$objDtstartAlternate->get(Zend_Date::TIMEZONE) : '');
                    $return .= ')';
                }
                break;
            /**
             * SECTION : Automatically rotate events on a list with calendar
             */
            case 'dd.myevents.wide.event.list' :
            case 'dd.myevents.narrow.event.list' :
                //$return .= '<strong>'.$objDtstart->toString(Warecorp_Date::DATE_MEDIUM).'</strong>';
                //$return .= '<br>';
                if ( $this->isAllDay() ) $return .= 'All Day';
                else $return .= ''.$objDtstart->toString(Warecorp_Date::TIME_SHORT).(($this->isTimezoneExists() && Warecorp_ICal_Calendar_Cfg::getShowTimezoneAbbr()) ? ' '.$objDtstart->get(Zend_Date::TIMEZONE) : '');
                if ( !$this->isAllDay() && $objDtstartAlternate && $objDtstart->getTimezone() != $objDtstartAlternate->getTimezone() ) {
                    $return .= '<br>(';
                    //$return .= '<strong>'.$objDtstartAlternate->toString(Warecorp_Date::DATE_MEDIUM).'</strong>';
                    //$return .= '<br>';
                    if ( $this->isAllDay() ) $return .= 'All Day';
                    else $return .= ''.$objDtstartAlternate->toString(Warecorp_Date::TIME_SHORT).(($this->isTimezoneExists()) ? ' '.$objDtstartAlternate->get(Zend_Date::TIMEZONE) : '');
                    $return .= ')';
                }
                break;
            case 'dd.myevents.tooltip' :
                $return .= $objDtstart->toString(Warecorp_Date::DATE_FULL);
                $return .= '<br>';
                if ( $this->isAllDay() ) $return .= 'All Day';
                else $return .= ''.$objDtstart->toString(Warecorp_Date::TIME_SHORT).(($this->isTimezoneExists() && Warecorp_ICal_Calendar_Cfg::getShowTimezoneAbbr()) ? ' '.$objDtstart->get(Zend_Date::TIMEZONE) : '');
                if ( !$this->isAllDay() && $objDtstartAlternate && $objDtstart->getTimezone() != $objDtstartAlternate->getTimezone() ) {
                    $return .= '<br>(';
                    $return .= $objDtstartAlternate->toString(Warecorp_Date::DATE_FULL);
                    $return .= '<br>';
                    if ( $this->isAllDay() ) $return .= 'All Day';
                    else $return .= ''.$objDtstartAlternate->toString(Warecorp_Date::TIME_SHORT).(($this->isTimezoneExists()) ? ' '.$objDtstartAlternate->get(Zend_Date::TIMEZONE) : '');
                    $return .= ')';
                }
                break;
            case 'nda.index.list' :
                $return .= '<strong>'.$objDtstart->toString(Warecorp_Date::DATE_MEDIUM).'</strong>';
                $return .= '<br>';
                if ( $this->isAllDay() ) $return .= 'All Day';
                else $return .= ''.$objDtstart->toString(Warecorp_Date::TIME_SHORT).(($this->isTimezoneExists() && Warecorp_ICal_Calendar_Cfg::getShowTimezoneAbbr()) ? ' '.$objDtstart->get(Zend_Date::TIMEZONE) : '');
                if ( !$this->isAllDay() && $objDtstartAlternate && $objDtstart->getTimezone() != $objDtstartAlternate->getTimezone() ) {
                    $return .= '<br><font color="CB0000">(';
                    $return .= $objDtstartAlternate->toString(Warecorp_Date::DATE_MEDIUM);
                    $return .= '<br>';
                    if ( $this->isAllDay() ) $return .= 'All Day';
                    else $return .= ''.$objDtstartAlternate->toString(Warecorp_Date::TIME_SHORT).(($this->isTimezoneExists()) ? ' '.$objDtstartAlternate->get(Zend_Date::TIMEZONE) : '');
                    $return .= ')</font>';
                }
                break;
            case 'email.invitation.date' :
                $return .= ( $objDtstart->toString(Warecorp_Date::DATE_MEDIUM) != $objDtend->toString(Warecorp_Date::DATE_MEDIUM) ) ? $objDtstart->toString(Warecorp_Date::DATE_MEDIUM).' - '.$objDtend->toString(Warecorp_Date::DATE_MEDIUM) : $objDtstart->toString(Warecorp_Date::DATE_MEDIUM);
                if ( !$this->isAllDay() && $objDtstartAlternate && $objDtstart->getTimezone() != $objDtstartAlternate->getTimezone() ) {
                    $return .= '(';
                    $return .= ( $objDtstartAlternate->toString(Warecorp_Date::DATE_MEDIUM) != $objDtendAlternate->toString(Warecorp_Date::DATE_MEDIUM) ) ? $objDtstartAlternate->toString(Warecorp_Date::DATE_MEDIUM).' - '.$objDtendAlternate->toString(Warecorp_Date::DATE_MEDIUM) : $objDtstartAlternate->toString(Warecorp_Date::DATE_MEDIUM);
                    $return .= ')';
                }
                break;
            case 'email.invitation.time' :
                //$return .= "*".$currentTimezone."*".$originalTimezone."*";
                if ( $this->isAllDay() ) $return .= '&#160;All Day';
                else $return .= $objDtstart->toString(Warecorp_Date::TIME_SHORT).' - '.$objDtend->toString(Warecorp_Date::TIME_SHORT).( ($this->isTimezoneExists() && Warecorp_ICal_Calendar_Cfg::getShowTimezoneAbbr()) ? ' '.$objDtstart->get(Zend_Date::TIMEZONE) : '' );
                if ( !$this->isAllDay() && $objDtstartAlternate && $objDtstart->getTimezone() != $objDtstartAlternate->getTimezone() ) {
                    $return .= '(';
                    if ( $this->isAllDay() ) $return .= '&#160;All Day';
                    else $return .= $objDtstartAlternate->toString(Warecorp_Date::TIME_SHORT).' - '.$objDtendAlternate->toString(Warecorp_Date::TIME_SHORT).( ($this->isTimezoneExists()) ? ' '.$objDtstartAlternate->get(Zend_Date::TIMEZONE) : '' );
                    $return .= ')';
                }
                break;
                
        }
        return $return;
    }
    /**
	 *
	 */
	public function loadById($eventId)
	{
        $memcache = Warecorp_Cache::getMemCache();

        $classname = get_class($this);
        $data = $memcache->load($classname.$eventId);

        //There is no cache. Load it from DB
        if (!$data) {
            $query = $this->DbConn->select();
            $query->from('calendar_events', array('*'));
            $query->where('event_id = ?', $eventId);
            $data = $this->DbConn->fetchRow($query);
            //Save it to memcache
            if ($data) $memcache->save($data, $classname.$data['event_id'], array(), Warecorp_Cache::LIFETIME_30DAYS);
        }
        
		if ( $data ) {
			$this->setId($data['event_id']);
			$this->setUid($data['event_uid']);
            $this->setRootId($data['event_root_id']);
			$this->setRecurrenceId($data['event_recurrence_id']);
			$this->setTitle($data['event_title']);
			$this->setDescription($data['event_description']);
			$this->setTimezone($data['event_timezone']);
			$this->original_timezone = $data['event_timezone'];
			$this->setDtstart($data['event_dtstart']);
			$this->setOriginalDtstart($data['event_dtstart']);
			$this->setDtend($data['event_dtend']);
			$this->setOriginalDtend($data['event_dtend']);
			$this->setAllDay($data['event_is_allday']);
			$this->setCreatorId($data['event_creator_id']);
			$this->setOwnerId($data['event_owner_id']);
			$this->setOwnerType($data['event_owner_type']);
			$this->setCreateTime($data['event_created']);
			$this->setPrivacy($data['event_privacy']);
            $this->setRefId($data['event_ref_id']);
            $this->setPictureId($data['event_picture_id']);
            $this->setContactName($data['event_contact_name']);            
            $this->setContactEmail($data['event_contact_email']);
            $this->setContactPhone($data['event_contact_phone']);
            $this->setMarkerGroupId($data['event_marker_group_id']);
            $this->setMaxRsvp($data['event_max_rsvp']);
            $this->setEventRequestFacilitator($data['event_request_facilitator']);
            $this->setEventIsPartOfRound($data['event_is_part_of_round']);
            /**
             *
             */
            if ( isset($data['event_http_context']) ) $this->setHttpContext($data['event_http_context']);

            if ( null !== $data['event_timezone'] ) $this->whithTimezone = true;
            else $this->whithTimezone = false;

			if ( null !== ($rruleId = Warecorp_ICal_Rrule::isEventRruleExist($this->getId())) ) {			
				$objRrule = new Warecorp_ICal_Rrule($rruleId, $this);
				$this->setRrule($objRrule);
			}

			/**
			 *	Event is exception for all future dates
			 */
            if ( null !== $this->getRefId() ) {
                $objRef = new Warecorp_ICal_Event_List_Reference($this);
                $rootId = $objRef->getRootId();
                $obRootEvent = new Warecorp_ICal_Event($rootId);
                $obRootEvent->mergeCopy($this);
                unset($obRootEvent);
                unset($objRef);
            } 
			/**
			 *	Event is exception for current date
			 */
			elseif ( null !== $this->getRecurrenceId() ) {
                $obRecEvent = new Warecorp_ICal_Event($this->getUid());
                $obRecEvent->mergeCopy($this);
                unset($obRecEvent);
            }
		}
	}

    /**
	 *
	 */
	public function save()
	{
        //clean memcache
        $memcache = Warecorp_Cache::getMemCache();

        $classname = get_class($this);
        $memcache->remove($classname.$this->getId());

		$data = array();
		$data['event_title']            = ( null !== $this->getTitle() ) ? $this->getTitle() : new Zend_Db_Expr('NULL');
		$data['event_description']      = ( null !== $this->getDescription() ) ? $this->getDescription() : new Zend_Db_Expr('NULL');
		$data['event_dtstart']          = $this->getDtstartValue();
        $data['event_dtstart_date']     = $this->getDtstart()->toString('yyyy-MM-dd HH:mm:ss');
		$data['event_dtend']            = $this->getDtendValue();
        $data['event_dtend_date']       = $this->getDtend()->toString('yyyy-MM-dd HH:mm:ss');
		$data['event_timezone']         = ( null === $this->getTimezone() ) ? new Zend_Db_Expr('NULL') : $this->getTimezone();
		$data['event_is_allday']        = ( $this->isAllDay() ) ? 1 : 0;
		$data['event_recurrence_id']    = ( $this->getRecurrenceId() ) ? $this->getRecurrenceId() : new Zend_Db_Expr('NULL');
		$data['event_creator_id']       = $this->getCreatorId();
		$data['event_owner_id']         = $this->getOwnerId();
		$data['event_owner_type']       = $this->getOwnerType();
		$data['event_created']          = new Zend_Db_Expr('NOW()');
		$data['event_privacy']          = $this->getPrivacy();
        $data['event_ref_id']           = ( null === $this->getRefId() ) ? new Zend_Db_Expr('NULL') : $this->getRefId();
        $data['event_picture_id']       = ( null === $this->getPictureId() ) ? new Zend_Db_Expr('NULL') : $this->getPictureId();
        $data['event_contact_name']     = ( null === $this->getContactName() ) ? new Zend_Db_Expr('NULL') : $this->getContactName();
        $data['event_contact_email']    = ( null === $this->getContactEmail() ) ? new Zend_Db_Expr('NULL') : $this->getContactEmail();
        $data['event_contact_phone']    = ( null === $this->getContactPhone() ) ? new Zend_Db_Expr('NULL') : $this->getContactPhone();
        $data['event_marker_group_id']  = ( null === $this->getMarkerGroupId() ) ? new Zend_Db_Expr('NULL') : $this->getMarkerGroupId();
        $data['event_max_rsvp']         = ( null === $this->getMaxRsvp() ) ? new Zend_Db_Expr('NULL') : $this->getMaxRsvp();
        $data['event_request_facilitator'] = ( null === $this->getEventRequestFacilitator() ) ? 0 : $this->getEventRequestFacilitator();
        $data['event_is_part_of_round'] = ( null === $this->getEventIsPartOfRound() ) ? 0 : $this->getEventIsPartOfRound();
		/**
		 *
		 */
        $data['event_http_context']     = $this->getHttpContext();

        if ( null === $this->getTimezone() || $this->getTimezone() instanceof Zend_Db_Expr ) $this->whithTimezone = false;
        else $this->whithTimezone = true;

		if ( null === $this->getId() ) {
			if ( null === $this->getUid() ) $data['event_uid'] = new Zend_Db_Expr('NULL');
			else $data['event_uid'] = $this->getUid();
            if ( null === $this->getRootId() ) $data['event_root_id'] = new Zend_Db_Expr('NULL');
            else $data['event_root_id'] = $this->getRootId();

			$this->DbConn->insert('calendar_events', $data);
			$this->setId($this->DbConn->lastInsertId());
						
			/**
			 * update uid and rootId
			 */
			if ( null === $this->getUid() ) {
				$where = $this->DbConn->quoteInto('event_id = ?', $this->getId());
				$data = array();
				$data['event_uid'] = $this->getId();
				$this->DbConn->update('calendar_events', $data, $where);
				$this->setUid($this->getId());
			}
            if ( null === $this->getRootId() ) {
                $where = $this->DbConn->quoteInto('event_id = ?', $this->getId());
                $data = array();
                $data['event_root_id'] = $this->getId();
                $this->DbConn->update('calendar_events', $data, $where);
                $this->setRootId($this->getId());
            }
			if ( null !== $this->getRrule() && $this->getRrule() instanceof Warecorp_ICal_Rrule ) {
				$this->getRrule()->setEventId($this->getId());
				$this->getRrule()->save();
			}
            /**
            * Если было созданно исключение на событие и данное событие было расшарено,
            * надо для этого исключения добавить связь шаринга
            */
            $objRootEvent = new Warecorp_ICal_Event($this->getRootId());
            if ( null !== $this->getRefId() || null !== $this->getRecurrenceId() ) {
                $refs = $objRootEvent->getSharing()->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getList();
                if ( sizeof($refs) != 0 ) {
                    foreach ( $refs as &$ref ) {
                        $this->getSharing()->add($ref, false);
                    }
                }
            }
            /**
             * Facebook Feed
             * try to post invitation message for facebook users invited to event on their wall
             */    
            if ( FACEBOOK_USED ) {
                $params = array(
                    'title' => htmlspecialchars($this->getTitle()), 
                    'orgname' => htmlspecialchars(SITE_NAME_AS_STRING)
                );
                $eventURL = $this->entityURL().'m/fb';
                $action_links[] = array(
                    'text' => 'View Event', 
                    'href' => $eventURL
                );
                $objMessage = Warecorp_Facebook_Feed::getStreamActionMessage(Warecorp_Facebook_Feed::STREAM_ACTION_MESSAGE_NEW_EVENT, $params);    
                Warecorp_Facebook_Feed::postStream($objMessage, null, $action_links);                
            }
		} else {
			$where = $this->DbConn->quoteInto('event_id = ?', $this->getId());
			$this->DbConn->update('calendar_events', $data, $where);
			if ( null !== $this->getRrule() && $this->getRrule() instanceof Warecorp_ICal_Rrule ) {
				$this->getRrule()->setEventId($this->getId());
				$this->getRrule()->save();
			}
		}

        $this->original_timezone = $this->getTimezone();
        $this->setOriginalDtstart( $this->getDtstartValue() );
        $this->setOriginalDtend( $this->getDtendValue() );          
		
        /**
        * Save event categories
        */
        $this->getCategories()->save();

        /**
        * Save Reminders
        */
        $this->getReminders()->save();

        /**
        *  Save Documents
        */
        $this->getDocuments()->save();

        /**
        *  Save Lists
        */
        $this->getLists()->save();

       /**
        *  Save venues
        *
        *  Edited by Roman Gabrusenok:
        *  In method Warecorp_ICal_Event_List_Venue::save() use method Warecorp_ICal_Event_List_Venue::deleteEventsAll() ???
        *  I use Warecorp_ICal_Event_List_Venue::add(Warecorp_Venue_Item $venue) before use Warecorp_ICal_Event_List_Venue::save
        *  for leave venue in event if it set.
        *
        *  Other words:
        *  When user change event host and current event have a venue,
        *  this venue will be delete from event, and event will be have no venue,
        *  event will have venue if readd venue to event.
        *
        *  Redmine bug #527
        */
        $objVenue = $this->getEventVenue();
        if   (null !== $objVenue && is_a($objVenue, 'Warecorp_Venue_Item')) {
            $venues = $this->getVenues();
            $venues->add($objVenue);
            $venues->save();
        }
        else $this->getVenues()->save();

        /**
        * Save system tags
        */
        $this->getTags()->setEvent($this)->buildSystemTags();
	}

	/**
	*
	*/
	public function delete()
	{
        /* Remove share to all family groups */
        Warecorp_Share_Entity::removeShare(null, $this->getId(), $this->EntityTypeId, true);

        /**
         * Remove Event from system Who Will list
         * @see https://secure.warecorp.com/redmine/issues/12525
         * @author Artem Sukharev
         */
        $objList = new Warecorp_List_List($this->getOwner());
        $list = $objList->getSystemWhoWillList();
        if ( $list ) {
            $record = $list->getRecordByRelatedEvent($this->getId());
            if ( $record ) $record->delete();
        }

		$where = $this->DbConn->quoteInto('event_id = ?', $this->getId());
		$this->DbConn->delete('calendar_events', $where);

        /**
         * delete tags
         */
        $this->getTags()->deleteTags();
        $this->getTags()->deleteSystemTags();
	}

	/**
	*
	*/
	public function clearCache()
	{
		$where = $this->DbConn->quoteInto('event_id = ?', $this->getId());
		$this->DbConn->delete('calendar_event_cache', $where);
        $where = $this->DbConn->quoteInto('event_root_id = ?', $this->getId());
        $this->DbConn->delete('calendar_event_cache', $where);

        $where = $this->DbConn->quoteInto('event_id = ?', $this->getId());
        $this->DbConn->delete('calendar_event_cache_dates', $where);
        $where = $this->DbConn->quoteInto('event_root_id = ?', $this->getId());
        $this->DbConn->delete('calendar_event_cache_dates', $where);

        $where = $this->DbConn->quoteInto('cache_event_id = ?', $this->getId());
        $this->DbConn->delete('calendar_event_reminder_cache', $where);
        $where = $this->DbConn->quoteInto('cache_event_root_id = ?', $this->getId());
        $this->DbConn->delete('calendar_event_reminder_cache', $where);
	}

	/**
	*
	*/
	public function mergeCopy(Warecorp_ICal_Event $objCopyEvent)
	{
		if ( null === $objCopyEvent->getTitle() )  $objCopyEvent->setTitle($this->getTitle());
		if ( null === $objCopyEvent->getDescription() )  $objCopyEvent->setDescription($this->getDescription());

        if ( null === $objCopyEvent->getInvite()->getId() ) {
            $objCopyEvent->getInvite()->loadByEventId($this->getInvite()->getEventId());
            $objCopyEvent->getAttendee()->setEventId($this->getAttendee()->getEventId());
        }

        /**
        * Категории должны мержиться от самого верхнего события
        * Шаринг должны мержиться от самого верхнего события
        */
        $objCopyEvent->getCategories()->setEvent($this->getRootEvent());
        $objCopyEvent->getSharing()->setEvent($this->getRootEvent());

        /**
        * Ремайндеры не мержаться, для каждой копии события будет свой ремайндер
        */
        /**
        * Документы не мержаться, для каждой копии события будет свои документы
        */

	}


    public function isExpired()
    {
        if ( null === $this->isExpired ) throw new Warecorp_ICal_Exception('isExpired property is not set');
        return (boolean) $this->isExpired;
    }

    public function setExpired($boolean)
    {
        $this->isExpired = (boolean) $boolean;
        return $this;
    }
    /**
    * +---------------------------------------------------------------
    * |
    * |     Event Copy Functions
    * |
    * +---------------------------------------------------------------
    */

    public function copy(Warecorp_User $objUser)
    {
        $objRootEvent = $this->getRootEvent();
        $objRootEvent->copyFull($objUser);
    }

    private function copyFull(Warecorp_User $objUser, $intRootId = null, $intRefId = null)
    {
        $objRootEventOrig = $this;
        $objRootEventCopy = $objRootEventOrig->copySimple($objUser, $intRootId, $intRefId);

        $this->copyExdates($objUser, $objRootEventOrig, $objRootEventCopy);
        $this->copyEventRecurrences($objUser, $objRootEventOrig, $objRootEventCopy);
        $this->copyEventRefs($objUser, $objRootEventOrig, $objRootEventCopy);
    }

    private function copySimple(Warecorp_User $objUser, $intRootId = null, $intRefId = null, $intUid = null)
    {
        $objEventCopy = new Warecorp_ICal_Event();
        $objEventCopy->setTitle($this->getTitle());
        $objEventCopy->setDescription($this->getDescription());

        /**
        * Copy Event Picture
        * Надо копировать изображение пользователю
        */
        $objEventCopy->setPictureId($this->getPictureId());

        /**
        * Copy Event Dates and Timezone
        */
        $objEventCopy->setDtstart($this->getDtstartValue());
        $objEventCopy->setDtend($this->getDtendValue());
        $objEventCopy->setTimezone($this->getTimezone());

        /**
        * Copy Event Rrule Object
        */
        if ( null !== $this->getRrule() ) {
            $objRruleCopy = $this->getRrule();
            $objRruleCopy->setId(null);
            $objRruleCopy->setEventId(null);
            $objEventCopy->setRrule($objRruleCopy);
        }

        /**
        * Save Event Privacy
        */
        $objEventCopy->setPrivacy($this->getPrivacy());

        /**
        * Define Event Creator and Owner
        */
        $objEventCopy->setCreatorId($objUser->getId());
        $objEventCopy->setOwnerId($objUser->getId());
        $objEventCopy->setOwnerType(Warecorp_ICal_Enum_OwnerType::USER);


        if ( null !== $intRootId ) {
            $objEventCopy->setRootId($intRootId);
        }
        if ( null != $intRefId ) {
            $objEventCopy->setRefId($intRefId);
        }
        if ( null !== $intUid ) {
            $objEventCopy->setUid($intUid);
            $objEventCopy->setRecurrenceId($this->getRecurrenceId());
        }

        /**
        * Copy Event Categories :
        */
        $objEventCategories = $objEventCopy->getCategories();
        $eventCategories = $this->getCategories()->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::PAIRS)->getList();
        if ( sizeof($eventCategories) != 0 ) {
            foreach ( $eventCategories as $value ) $objEventCategories->add($value);
        }

        /**
        * Copy Event Reminders
        */
        $lstReminders = $this->getReminders()->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getList();
        if ( sizeof($lstReminders) != 0 ) {
            foreach ( $lstReminders as &$objReminder ) {
                $objReminderCopy = new Warecorp_ICal_Reminder();
                $objReminderCopy->setDuration($objReminder->getDuration());
                $objReminderCopy->setEntireGuests( $objReminder->getEntireGuests() ? 1 : 0 );
                $objEventCopy->getReminders()->add($objReminderCopy);
            }
        }

        /**
        * Copy Event Documents
        */
        $lstDocuments = $this->getDocuments()->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getList();
        if ( sizeof($lstDocuments) != 0 ) {
            foreach ( $lstDocuments as &$objDocument ) {
                /**
                * Надо копировать пользователю документы
                */
                $objEventCopy->getDocuments()->add($objDocument);
            }
        }

        /**
        * Copy Event Lists
        */
        $lstLists = $this->getLists()->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getList();
        if ( sizeof($lstLists) != 0 ) {
            foreach ( $lstLists as &$objList ) {
                $objEventCopy->getLists()->add($objList);
            }
        }

        /**
        * Copy Event Venues
        */
        $eventVenue = $this->getEventVenue();
        if ( null !== $eventVenue && null !== $eventVenue->getId() ) {
            $objEventCopy->getVenues()->add($eventVenue);
        }


        $objEventCopy->save();

        /**
        * Copy Event Tags
        */
        $objEventCopy->getTags()->addTags($this->getTags()->getAsString());

        /**
        * Copy Invitations
        */
        $this->copyInvitation($objUser, $objEventCopy);

        /**
        * Build Reminders Cache :
        */
        $cache = new Warecorp_ICal_Reminder_Cache();
        $cache->build($objEventCopy);

        self::$copyIds[] = $objEventCopy->getId();
        return $objEventCopy;
    }

    private function copyInvitation(Warecorp_User $objUser, Warecorp_ICal_Event $objEventCopy)
    {
        $query = $this->DbConn->select();
        $query->from('calendar_event_invitations', array('invite_id'));
        $query->where('invite_event_id = ?', $this->getId());
        $invite = $this->DbConn->fetchOne($query);
        if ( $invite ) {
            $objInvite = new Warecorp_ICal_Invitation($invite);
            $objNewInvite = new Warecorp_ICal_Invitation();
            $objNewInvite->setEventId($objEventCopy->getId());
            $objNewInvite->setEvent($objEventCopy);
            $objNewInvite->setTo('');
            $objNewInvite->setSubject($objInvite->getSubject());
            $objNewInvite->setMessage($objInvite->getMessage());
            $objNewInvite->setFrom($objUser->getEmail());
            $objNewInvite->setDisplayListToGuest($objInvite->getDisplayListToGuest());
            $objNewInvite->setAllowGuestToInvite($objInvite->getAllowGuestToInvite());
            $objNewInvite->__save();
            $objNewInvite->__saveAttendee();
        }

    }

    private function copyEventRecurrences(Warecorp_User $objUser, Warecorp_ICal_Event $objEventOrig, Warecorp_ICal_Event $objEventCopy)
    {
        $query = $this->DbConn->select();
        $query->from('calendar_events', array('event_id'));
        $query->where('event_uid = ?', $objEventOrig->getId());
        $query->where('event_recurrence_id IS NOT NULL');
        $events = $this->DbConn->fetchCol($query);
        if ( sizeof($events) != 0 ) {
            foreach ( $events as &$event ) {
                $event = new Warecorp_ICal_Event($event);
                $event->copySimple($objUser, $objEventCopy->getRootId(), null, $objEventCopy->getId());
            }
        }
    }

    private function copyEventRefs(Warecorp_User $objUser, Warecorp_ICal_Event $objEventOrig, Warecorp_ICal_Event $objEventCopy)
    {
        $query = $this->DbConn->select();
        $query->from('calendar_events', array('event_id'));
        $query->where('event_ref_id = ?', $objEventOrig->getId());
        $query->where('event_ref_id IS NOT NULL');
        $events = $this->DbConn->fetchCol($query);
        if ( sizeof($events) != 0 ) {
            foreach ( $events as &$event ) {
                $event = new Warecorp_ICal_Event($event);
                $event->copyFull($objUser, $objEventCopy->getRootId(), $objEventCopy->getId());
            }
        }
    }

    private function copyExdates(Warecorp_User $objUser, Warecorp_ICal_Event $objEventOrig, Warecorp_ICal_Event $objEventCopy)
    {
        $query = $this->DbConn->select();
        $query->from('calendar_event_exdates', array('*'));
        $query->where('event_id = ?', $objEventOrig->getId());
        $exdates = $this->DbConn->fetchAll($query);
        if ( sizeof($exdates) != 0 ) {
            foreach ( $exdates as &$exdate ) {
                $objEventCopy->getExDates()->addExDate($exdate['date'], $exdate['type'], ($exdate['event_ref_id']) ? $exdate['event_ref_id'] : new Zend_Db_Expr('NULL')) ;
            }
        }

        $query = $this->DbConn->select();
        $query->from('calendar_event_exdates', array('*'));
        $query->where('event_id IN (?)', self::$copyIds);
        $query->where('event_ref_id IS NOT NULL');
        $query->where('event_ref_id = ?', $objEventOrig->getId());
        $exdates = $this->DbConn->fetchAll($query);
        if ( sizeof($exdates) != 0 ) {
            foreach ( $exdates as &$exdate ) {
                $data = array();
                $data['event_ref_id'] = $objEventCopy->getId();
                $where = $this->DbConn->quoteInto('event_id = ?', $exdate['event_id']);
                $where .= ' AND ' . $this->DbConn->quoteInto('event_ref_id = ?', $exdate['event_ref_id']);
                $where .= ' AND ' . $this->DbConn->quoteInto('date = ?', $exdate['date']);
                $where .= ' AND ' . $this->DbConn->quoteInto('type = ?', $exdate['type']);
                $this->DbConn->update('calendar_event_exdates', $data, $where);
            }
        }

    }
    
       /*
     +-----------------------------------
     |
     | iSearchFields Interface
     |
     +-----------------------------------
    */ 
    
    /**
    * return object
    * @return void object
    */
    public function entityObject()
    {
        return $this;
    }
    
    /**
    * return object id
    * @return int
    */
    public function entityObjectId() 
    {
        return $this->getId(); 
    }

    /**
    * return object type. possible values: simple, family, committies and blank string or null
    * @return string
    */
    public function entityObjectType()
    {
        return "";
    }

    /**
    * return owner type
    * possible values: group, user
    * @return string
    */
    public function entityOwnerType()
    {
        return $this->getOwnerType();
    }

    /**
    * return title for entity (like group name, username, photo or gallery title)
    * @return string
    */
    public function entityTitle()
    {
        return $this->getTitle();
    }

    /**
    * return headline for entity (like group headline, members first and last name, photo or gallery title,etc). 
    * for entities which didn't have headline will be returned entityTitle
    * @return string
    */
    public function entityHeadline()
    {
        return $this->getTitle();
    }
        
    /**
    * return description for entity (group description, user intro, gallery or photo description, etc.). 
    * for entities which didn't have headline will be returned entityTitle
    * @return string
    */
    public function entityDescription()
    {
        return $this->getDescription();
    }

    /**
    * return username of owner 
    * @return string
    */
    public function entityAuthor()
    {
        return $this->getCreator()->getLogin();
    }

    /**
    * return user_id of entity owner 
    * @return string
    */
    public function entityAuthorId()
    {
        return $this->getCreatorId();
    }

    /**
    * return picture URL (avatar, group picture, trumbnails, etc.) 
    * @return int
    */
    public function entityPicture()
    {
        return $this->getEventPicture();
    }
    
    /**
    * return creation date for all elements
    * @return string
    */
    public function entityCreationDate()
    {
        return $this->getCreateTime();
    }

    /**
    * return update date for all elements
    * @return string
    */
    public function entityUpdateDate()
    {
        return $this->getCreateTime();
    }

    /**
    * items count (members, posts, child groups, etc.)
    * @return int
    */
    public function entityItemsCount()
    {
        return 1;
    }
    
    /**
    * get category for entity (event type, list type, group category, etc)
    * possible values: string 
    * @return int
    */
    public function entityCategory()
    {
        return "";
    }

    /**
    * get category_id for entity (event type, list type, group category, etc)
    * possible values: int , null 
    * @return int
    */
    public function entityCategoryId()
    {
        return "";
    }

    /**
    * get country for entity (users, groups, events)
    * possible values: string
    * @return int
    */
    public function entityCountry()
    {
        if ($this->getEventVenue() === null || $this->getEventVenue()->getType() != "simple") return "";

        return $this->getEventVenue()->getCity()->getState()->getCountry()->name;
    }

    /**
    * get country_int for entity (users, groups, events)
    * possible values: int, null
    * @return int
    */
    public function entityCountryId()
    {
        if ($this->getEventVenue() === null || $this->getEventVenue()->getType() != "simple") return "";
       return $this->getEventVenue()->getCity()->getState()->getCountry()->id;
    }

    
    /**
    * get city for entity (users, groups, events)
    * possible values: string
    * @return int
    */
    public function entityCity()
    {
       if ($this->getEventVenue() === null || $this->getEventVenue()->getType() != "simple") return "";
       return $this->getEventVenue()->getCity()->name;
    }

    /**
    * get city_id for entity (users, groups, events)
    * possible values: int, null
    * @return int
    */
    public function entityCityId()
    {
        if ($this->getEventVenue() === null || $this->getEventVenue()->getType() != "simple") return "";
        return $this->getEventVenue()->getCity()->id;    
    }
    
    /**
    * get zip for entity (users, groups, events)
    * possible values: int, null
    * @return int
    */
    public function entityZIP()
    {                                                   
        if ($this->getEventVenue() === null || $this->getEventVenue()->getType() != "simple") return "";
        return $this->getEventVenue()->getZipcode();
    }
    
    /**
    * get state for entity (users, groups, events)
    * possible values: int, null
    * @return int
    */
    public function entityState()
    {                                               
        if ($this->getEventVenue() === null || $this->getEventVenue()->getType() != "simple") return "";
        return $this->getEventVenue()->getCity()->getState()->name;
    }

    /**
    * get state_id for entity (users, groups, events)
    * possible values: int, null
    * @return int
    */
    public function entityStateId()
    {
        if ($this->getEventVenue() === null || $this->getEventVenue()->getType() != "simple") return "";
        return $this->getEventVenue()->getCity()->getState()->id;
    }

    /**
    * path to video(video galleries)
    * possible values: string
    * @return int
    */
    public function entityVideo()
    {
        return "";
    }
    
    /**
    * comments count for entity
    * possible values: int
    * @return int
    */
    public function entityCommentsCount()
    {
        return null;
    }     
    
    public function entityURL()
    {
        $dtStart = $this->getDtstart();
        $return = $this->getOwner()->getGlobalPath('calendar.event.view').'id/'.$this->id.'/uid/'.$this->uid.'/year/'.$dtStart->toString('yyyy').'/month/'.$dtStart->toString('MM').'/day/'.$dtStart->toString('dd').'/';
        return $return;
    }

    static public function sortByDtstartAsc(Warecorp_ICal_Event $e1, Warecorp_ICal_Event $e2)
    {
        return strcmp($e1->getDtstart()->toString('yyyyMMdd HHmmss'), $e2->getDtstart()->toString('yyyyMMdd HHmmss'));
    }
    static public function sortByDtstartDesc(Warecorp_ICal_Event $e1, Warecorp_ICal_Event $e2)
    {
        return strcmp($e2->getDtstart()->toString('yyyyMMdd HHmmss'), $e1->getDtstart()->toString('yyyyMMdd HHmmss'));
    }
    static public function sortByTitleAsc(Warecorp_ICal_Event $e1, Warecorp_ICal_Event $e2)
    {
        return strcasecmp($e1->getTitle(), $e2->getTitle());
    }
    static public function sortByTitleDesc(Warecorp_ICal_Event $e1, Warecorp_ICal_Event $e2)
    {
        return strcasecmp($e2->getTitle(), $e1->getTitle());
    }
    static public function sortByVenueAsc(Warecorp_ICal_Event $e1, Warecorp_ICal_Event $e2)
    {
        $v1 = $e1->getVenues()->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getList();
        $v2 = $e2->getVenues()->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getList();
        return strcasecmp($v1[0]->getName(), $v2[0]->getName());
    }
    static public function sortByVenueDesc(Warecorp_ICal_Event $e1, Warecorp_ICal_Event $e2)
    {
        $v1 = $e1->getVenues()->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getList();
        $v2 = $e2->getVenues()->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getList();
        return strcasecmp($v2[0]->getName(), $v1[0]->getName());
    }
    
}
