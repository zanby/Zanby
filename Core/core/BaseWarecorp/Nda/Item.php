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
*@package Warecorp_Nda
*/
class BaseWarecorp_Nda_Item
{
   /**
    * data present in calendar_event_nda_items table
    */
    private $id;
    private $name;
    private $path;
    private $description;
    private $timezone;
    private $stdate;
    private $etdate;
    private $createDate;
    private $status;
    private $banner;

   /**
    * other global data
    */
    private $_db;

   /**
    */
    public function __construct($ndaId = null) {
        if (null === ($this->_db = Zend_Registry::get('DB'))) throw new Warecorp_Exception('Database connection isn`t set');
        if ($ndaId != null) $this->loadById($ndaId);

       /** default value **/
        if ($ndaId === null) $this->setStatus('public');
    }

   /**
    *@param int $ndaId
    */
    public function loadById($ndaId)
    {
        if ( empty($ndaId)) throw new Warecorp_Exception('NDA load error. You must set NDA id.');

        $select = $this->_db->select();
        $select->from('calendar_event_nda_items', array('*'))
               ->where('nda_id = ?' , $ndaId);

        $this->_load($this->_db->fetchRow($select));
    }

   /**
    *@param string $ndaPath
    */
    public function loadByPath($ndaPath)
    {
        if ( empty ($ndaPath) ) throw new Warecorp_Exception('NDA load error. You must set NDA path.');

        $query = $this->_db->select();
        $query->from('calendar_event_nda_items', array('*'))
              ->where('nda_path = ?', $ndaPath);

        $this->_load($this->_db->fetchRow($query));
    }

    protected function _load($result)
    {
        if ($result) {
            $this->setId($result['nda_id'])
                 ->setName($result['nda_name'])
                 ->setPath($result['nda_path'])
                 ->setDescription($result['nda_description'])
                 ->setTimezone($result['nda_timezone'])
                 ->setSTdate($result['nda_stdate'])
                 ->setETdate($result['nda_etdate'])
                 ->setCreateDate($result['nda_create_date'])
                 ->setBanner($result['nda_banner'])
                 ->setStatus($result['nda_status']);
        }
    }

   /**
    */
    static public function isPathPresent($value)
    {
        if (null === ($db = Zend_Registry::get('DB'))) throw new Warecorp_Exception('Database connection isn`t set');

        $query = $db->select();
        $query->from('calendar_event_nda_items', array('count' => new Zend_Db_Expr('COUNT(*)')))
              ->where('nda_path = ?', $value);
        $result = $db->fetchRow($query);

        return (bool) $result['count'];
    }

   /**
    */
    public function save()
    {
        $data = array();
        $data['nda_name']        = (null !== $this->getName()) ? $this->getName() : new Zend_Db_Expr("NULL");
        $data['nda_path']        = (null !== $this->getPath()) ? $this->getPath() : new Zend_Db_Expr("NULL");
        $data['nda_description'] = (null !== $this->getDescription()) ? $this->getDescription() : new Zend_Db_Expr("NULL");
        $data['nda_timezone']    = (null !== $this->getTimezone()) ? $this->getTimezone() : new Zend_Db_Expr('NULL');
        $data['nda_stdate']      = $this->getSTDateValue();
        $data['nda_etdate']      = $this->getETDateValue();
        $data['nda_sdate']       = $this->getSTdate()->toString('yyyy-MM-dd HH:mm:ss');
        $data['nda_edate']       = $this->getETdate()->toString('yyyy-MM-dd HH:mm:ss');
        $data['nda_banner']      = (null != $this->getBanner()) ? $this->getBanner() : new Zend_Db_Expr("NULL");
        $data['nda_status']      = $this->getStatus();
        $data['nda_create_date'] = (null !== $this->getCreateDate()) ? $this->getCreateDate()->toString('yyyy-MM-dd HH:mm:ss') : new Zend_Db_Expr('NOW()');

        if ( $this->getId() !== null ) {
            $where = $this->_db->quoteInto('nda_id = ?', $this->getId());
            $this->_db->update('calendar_event_nda_items', $data, $where);
        }
        else {
            $data['nda_id'] = new Zend_Db_Expr('NULL');
            $this->_db->insert('calendar_event_nda_items', $data);
            $this->setId( $this->_db->lastInsertId() );
        }
    }

   /**
    */
    public function getId()
    {
        return $this->id;
    }

   /**
    */
    public function setId($value = null)
    {
        $this->id = $value;
        return $this;
    }

   /**
    */
    public function getName()
    {
        return $this->name;
    }

   /**
    */
    public function setName($value)
    {
        $this->name = $value;
        return $this;
    }

   /**
    */
    public function getPath()
    {
        return $this->path;
    }

   /**
    */
    public function setPath($value)
    {
        $this->path = $value;
        return $this;
    }

   /**
    */
    public function getDescription()
    {
        return $this->description;
    }

   /**
    */
    public function setDescription($value)
    {
        $this->description = $value;
        return $this;
    }

   /**
    */
    public function getTimezone()
    {
        return $this->timezone;
    }

   /**
    */
    public function setTimezone($value)
    {
        $this->timezone = $value;
        return $this;
    }

   /**
    */
    public function getSTDateValue()
    {
        return $this->stdate;
    }

   /**
    */
    public function getSTDate()
    {
        if ( null === $this->stdate ) throw new Warecorp_Exception('NDA start date is not set.');
        $tz = ($this->getTimezone()) ? $this->getTimezone() : 'UTC';
        $defaultTimeZone = date_default_timezone_get();
        date_default_timezone_set($tz);
        $date = new Zend_Date($this->stdate, Zend_Date::ISO_8601, 'en_US');
        date_default_timezone_set($defaultTimeZone);
        return $date;
    }

   /**
    */
    public function setSTDate($value)
    {
        if (!is_string($value)) throw new Warecorp_Exception('stdate must be string');
        $this->stdate = $value;
        return $this;
    }

   /**
    */
    public function getETDateValue()
    {
        return $this->etdate;
    }

   /**
    */
    public function getETDate()
    {
        if ( null === $this->etdate ) throw new Warecorp_Exception('NDA end date is not set.');
        $tz = ($this->getTimezone()) ? $this->getTimezone() : 'UTC';
        $defaultTimeZone = date_default_timezone_get();
        date_default_timezone_set($tz);
        $date = new Zend_Date($this->etdate, Zend_Date::ISO_8601, 'en_US');
        date_default_timezone_set($defaultTimeZone);
        return $date;
    }

   /**
    */
    public function setETdate($value)
    {
        if (!is_string($value)) throw new Warecorp_Exception('etdate must be string');
        $this->etdate = $value;
        return $this;
    }

   /**
    */
    public function getCreateDate()
    {
        $tz = ($this->getTimezone() !== null) ? $this->getTimezone() : 'UTC';
        $defaultTimeZone = date_default_timezone_get();
        date_default_timezone_set($tz);
        $date = new Zend_Date($this->createDate, Zend_Date::ISO_8601, 'en_US');
        date_default_timezone_set($defaultTimeZone);

        return $date;
    }

   /**
    */
    public function getStatus()
    {
        return $this->status;
    }

   /**
    */
    public function setStatus($value)
    {
        if (!Warecorp_Nda_Enum_Status::inEnum($value)) throw new Warecorp_Exception('NDA status incorrect');
        $this->status = $value;
        return $this;
    }

   /**
    */
    public function setCreateDate($value)
    {
        $this->createDate = $value;
        return $this;
    }

   /**
    */
    public function converTZ($objectDate, $timezone)
    {
        $objConvertedDate = clone $objDate;
        $objConvertedDate->setTimezone($timezone);
        return $objConvertedDate;
    }

   /**
    * @return Warecorp_Nda_Event_List
    */
    public function getEvents()
    {
        return new Warecorp_Nda_Event_List($this);
    }

   /**
    * @return self
    */
    public function setBanner($fileName)
    {
        $this->banner = $fileName;
        return $this;
    }

   /**
    */
    public function getBanner()
    {
        return $this->banner;
    }

   /**
    *@return void
    */
    public function addEvent(Warecorp_ICal_Event $event)
    {
        if (!$event->getId()) throw new Warecorp_Exception('Please before save Event');
        if (!$this->getId())  throw new Warecorp_Exception('Please before save NDA');

        $query = $this->_db->select();
        $query->from("calendar_event_nda_relations", array('count' => new Zend_Db_Expr('COUNT(*)')))
              ->where("nda_id = ?", $this->getId())
              ->where("event_id = ?", $event->getId());
        $result = $this->_db->fetchRow($query);

        if (!$result['count']) {
            $data = array(
                "nda_id" => $this->getId(),
                "event_id" => $event->getId()
            );
            $query = $this->_db->insert("calendar_event_nda_relations", $data);
        }
    }

   /**
    *@return void
    */
    public static function removeEvent(Warecorp_ICal_Event $event)
    {
        if (null === ($db = Zend_Registry::get('DB'))) throw new Warecorp_Exception('Database connection isn`t set');
        if (!$event->getId()) throw new Warecorp_Exception('Please before save Event');

        $where = $db->quoteInto("event_id = ?", $event->getId());
        $db->delete("calendar_event_nda_relations", $where);
    }

   /**
    *@return Warecorp_Nda_Item|null
    */
    public static function hasEvent(Warecorp_ICal_Event $event)
    {
        if (null === ($db = Zend_Registry::get('DB'))) throw new Warecorp_Exception('Database connection isn`t set');
        if (!$event->getId()) throw new Warecorp_Exception('Please before save Event');

        $query = $db->select();
        $query->from("calendar_event_nda_relations", array("nda_id"))
              ->where("event_id = ?", $event->getId());
        $result = $db->fetchRow($query);

        if ($result && $result['nda_id']) {
            return new Warecorp_Nda_Item($result['nda_id']);
        }

        return null;
    }
}
