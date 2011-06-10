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

class BaseWarecorp_DiscussionServer_User_Settings
{
    private $db;
    private $settingsId;
    private $userId;
    
    private $topicOrder;
    private $topicPerPage;
    private $recenttopicPerPage;
    private $searchPerPage;
    private $blogPerPage;
    private $blogCommentsPerPage;
    
    /**
    * @desc 
    */
    public function setSettingsId($value)
    {
        $this->settingsId = $value;
        return $this;
    }
    /**
    * @desc 
    */
    public function getSettingsId()
    {
        return $this->settingsId;
    }
    /**
    * @desc 
    */
    public function setUserId($value)
    {
        $this->userId = $value;
        return $this;
    }
    /**
    * @desc 
    */
    public function getUserId()
    {
        if ( null === $this->userId ) throw new Warecorp_Exception('UserID has not set');
        return $this->userId;
    }
    /**
    * @desc 
    */
    public function setTopicOrder($value)
    {
        if ( !in_array($value, array(1,2)) ) throw new Warecorp_Exception('Incorrect Topic Order type');
        $this->topicOrder = $value;
        return $this;
    }
    /**
    * @desc 
    */
    public function getTopicOrder()
    {
        return $this->topicOrder;
    }
    /**
    * @desc 
    */
    public function setTopicPerPage($value)
    {   
        $this->topicPerPage = $value;
        return $this;
    }
    /**
    * @desc 
    */
    public function getTopicPerPage()
    {
        return $this->topicPerPage;
    }
    /**
    * @desc 
    */
    public function setRecentTopicPerPage($value)
    {   
        $this->recenttopicPerPage = $value;
        return $this;
    }
    /**
    * @desc 
    */
    public function getRecentTopicPerPage()
    {
        return $this->recenttopicPerPage;
    }
    /**
    * @desc 
    */
    public function setSearchPerPage($value)
    {   
        $this->searchPerPage = $value;
        return $this;
    }
    /**
    * @desc 
    */
    public function getSearchPerPage()
    {
        return $this->searchPerPage;
    }
    /**
    * @desc 
    */
    public function setBlogPerPage($value)
    {   
        $this->blogPerPage = $value;
        return $this;
    }
    /**
    * @desc 
    */
    public function getBlogPerPage()
    {
        return $this->blogPerPage;
    }
    /**
    * @desc 
    */
    public function setBlogCommentsPerPage($value)
    {   
        $this->blogCommentsPerPage = $value;
        return $this;
    }
    /**
    * @desc 
    */
    public function getBlogCommentsPerPage()
    {
        return $this->blogCommentsPerPage;
    }
    /**
    * @desc 
    */
    public function __construct($userId)
    {
        $this->db = Zend_Registry::get('DB');
        $this->setUserId($userId);
        $this->load();  
    }
    /**
    * @desc 
    */
    public function load()
    {        
        $query = $this->db->select();
        $query->from('zanby_discussion__user_settings', array('*'));
        $query->where('user_id = ?', $this->getUserId());
        $result = $this->db->fetchRow($query);
        
        if ( $result ) {
            $this->setSettingsId($result['setting_id']);
            $this->setTopicOrder($result['topic_order']);
            $this->setTopicPerPage($result['topic_per_page']);
            $this->setRecentTopicPerPage($result['recenttopic_per_page']);
            $this->setSearchPerPage($result['search_per_page']);
            $this->setBlogPerPage($result['blog_per_page']);
            $this->setBlogCommentsPerPage($result['blog_comments_per_page']);
        } else {
            $this->setTopicOrder(2);
            $this->setTopicPerPage(10);
            $this->setRecentTopicPerPage(10);
            $this->setSearchPerPage(10);
            $this->setBlogPerPage(10);
            $this->setBlogCommentsPerPage(10);
        }
    }
    /**
    * @desc 
    */
    public function save()
    {
        $data = array();
        $data['user_id']                = $this->getUserId();
        $data['topic_order']            = $this->getTopicOrder();
        $data['topic_per_page']         = $this->getTopicPerPage();
        $data['recenttopic_per_page']   = $this->getRecentTopicPerPage();
        $data['search_per_page']        = $this->getSearchPerPage();
        $data['blog_per_page']          = $this->getBlogPerPage();
        $data['blog_comments_per_page'] = $this->getBlogCommentsPerPage();
        
        if ( null === $this->getSettingsId() ) {
            $this->db->insert('zanby_discussion__user_settings', $data);
            $this->setSettingsId($this->db->lastInsertId());
        } else {
            $this->db->update('zanby_discussion__user_settings', $data, $this->db->quoteInto('setting_id = ?', $this->getSettingsId()));
        }
        return true;
    }
}
