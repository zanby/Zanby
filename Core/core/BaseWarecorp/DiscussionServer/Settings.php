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
 * @author Artem Sukharev
 * @version 1.0
 * @created 12-Jun-2007 13:02:56
 */
class BaseWarecorp_DiscussionServer_Settings
{
    private $db;
    private $id;
	private $groupId;
	private $group;
	private $postMode;
	private $allowDeleteOwn;
	private $allowEditOwn;
	private $discussionStyle;
	private $emailedRepliesMode;
	private $messageFooterMode;
	private $messageFooterContent;
	private $emailSubjectPrefix;


	function __construct($id = null)
	{
	    $this->db = Zend_Registry::get("DB");
	    if ( $id !== null ) $this->load($id);
	}

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
	}
	public function getGroupId()
	{
		return $this->groupId;
	}

	/**
	 *
	 * @param newVal
	 */
	public function setGroupId($newVal)
	{
		$this->groupId = $newVal;
	}

	public function getGroup()
	{
	    if ( $this->groupId === null ) throw new Zend_Exception("Group is not defined");
		return $this->groupId;
	}

	/**
	 *
	 * @param newVal
	 */
	public function setGroup($newVal)
	{
	    if ( $newVal instanceof Warecorp_DiscussionServer_iDiscussionGroup ) {
		  $this->group = $newVal;
	    }
	}
	public function getPostMode()
	{
		return $this->postMode;
	}

	/**
	 *
	 * @param newVal
	 */
	public function setPostMode($newVal)
	{
		$this->postMode = $newVal;
	}

	public function getAllowDeleteOwn()
	{
		return $this->allowDeleteOwn;
	}

	/**
	 *
	 * @param newVal
	 */
	public function setAllowDeleteOwn($newVal)
	{
		$this->allowDeleteOwn = $newVal;
	}

	public function getAllowEditOwn()
	{
		return $this->allowEditOwn;
	}

	/**
	 *
	 * @param newVal
	 */
	public function setAllowEditOwn($newVal)
	{
		$this->allowEditOwn = $newVal;
	}

	public function getDiscussionStyle()
	{
		return $this->discussionStyle;
	}

	/**
	 *
	 * @param newVal
	 */
	public function setDiscussionStyle($newVal)
	{
		$this->discussionStyle = $newVal;
	}

	public function getEmailedRepliesMode()
	{
		return $this->emailedRepliesMode;
	}

	/**
	 *
	 * @param newVal
	 */
	public function setEmailedRepliesMode($newVal)
	{
		$this->emailedRepliesMode = $newVal;
	}

	public function getMessageFooterMode()
	{
		return $this->messageFooterMode;
	}

	/**
	 *
	 * @param newVal
	 */
	public function setMessageFooterMode($newVal)
	{
		$this->messageFooterMode = $newVal;
	}

	public function getMessageFooterContent()
	{
		return $this->messageFooterContent;
	}

	/**
	 *
	 * @param newVal
	 */
	public function setMessageFooterContent($newVal)
	{
		$this->messageFooterContent = $newVal;
	}

	public function getEmailSubjectPrefix()
	{
		return $this->emailSubjectPrefix;
	}

	/**
	 *
	 * @param newVal
	 */
	public function setEmailSubjectPrefix($newVal)
	{
		$this->emailSubjectPrefix = $newVal;
	}

    private function load($setting_id)
	{
	    $query = $this->db->select();
	    $query->from('zanby_discussion__group_settings', '*')
	          ->where('setting_id = ?', $setting_id);
	    $setting = $this->db->fetchRow($query);
	    if ( $setting ) {
	        $this->setId($setting_id);
	        $this->setGroupId($setting['group_id']);
	        $this->setPostMode($setting['post_mode']);
	        $this->setAllowDeleteOwn($setting['allow_delete_own']);
	        $this->setAllowEditOwn($setting['allow_edit_own']);
	        $this->setDiscussionStyle($setting['discussion_style']);
	        $this->setEmailedRepliesMode($setting['emailed_replies_mode']);
	        $this->setMessageFooterMode($setting['message_footer_mode']);
	        $this->setMessageFooterContent($setting['message_footer_content']);
	        $this->setEmailSubjectPrefix($setting['email_subject_prefix']);
	    }
	}
	public function save()
	{
	    $data = array();
	    $data['group_id'] = $this->getGroupId();
	    if ( $this->getPostMode() !== null )               $data['post_mode'] = $this->getPostMode();
	    if ( $this->getAllowDeleteOwn() !== null )         $data['allow_delete_own'] = $this->getAllowDeleteOwn();
	    if ( $this->getAllowEditOwn() !== null )           $data['allow_edit_own'] = $this->getAllowEditOwn();
	    if ( $this->getDiscussionStyle() !== null )        $data['discussion_style'] = $this->getDiscussionStyle();
	    if ( $this->getEmailedRepliesMode() !== null )     $data['emailed_replies_mode'] = $this->getEmailedRepliesMode();
	    if ( $this->getMessageFooterMode() !== null )      $data['message_footer_mode'] = $this->getMessageFooterMode();
	    if ( $this->getMessageFooterContent() !== null )   $data['message_footer_content'] = $this->getMessageFooterContent();
	    if ( $this->getEmailSubjectPrefix() !== null )     $data['email_subject_prefix'] = $this->getEmailSubjectPrefix();

        $rows_affected = $this->db->insert('zanby_discussion__group_settings', $data);
        $this->setId($this->db->lastInsertId());
	}
	public function update()
	{
	    $data = array();
	    $data['group_id'] = $this->getGroupId();
	    if ( $this->getPostMode() !== null )               $data['post_mode'] = $this->getPostMode();
	    if ( $this->getAllowDeleteOwn() !== null )         $data['allow_delete_own'] = $this->getAllowDeleteOwn();
	    if ( $this->getAllowEditOwn() !== null )           $data['allow_edit_own'] = $this->getAllowEditOwn();
	    if ( $this->getDiscussionStyle() !== null )        $data['discussion_style'] = $this->getDiscussionStyle();
	    if ( $this->getEmailedRepliesMode() !== null )     $data['emailed_replies_mode'] = $this->getEmailedRepliesMode();
	    if ( $this->getMessageFooterMode() !== null )      $data['message_footer_mode'] = $this->getMessageFooterMode();
	    if ( $this->getMessageFooterContent() !== null )   $data['message_footer_content'] = $this->getMessageFooterContent();
	    if ( $this->getEmailSubjectPrefix() !== null )     $data['email_subject_prefix'] = $this->getEmailSubjectPrefix();

        $where = $this->db->quoteInto('setting_id = ?', $this->getId());
        $rows_affected = $this->db->update('zanby_discussion__group_settings', $data, $where);
	}
	
	public function delete()
	{
        $table = 'zanby_discussion__group_settings';
        $where = $this->db->quoteInto('setting_id = ?', $this->getId());
        $rows_affected = $this->db->delete($table, $where);
        
        $table = 'zanby_discussion__group_publishing';
        $where = array();
        $where[] = $this->db->quoteInto('group_id = ?', $this->getGroupId());
        $where[] = $this->db->quoteInto('sub_group_id = ?', $this->getGroupId());
        $where = join(' OR ', $where);
        $rows_affected = $this->db->delete($table, $where);
	}
	
    static public function findByGroupId($group_id)
    {
        $db = Zend_Registry::get("DB");
        $query = $db->select();
        $query->from('zanby_discussion__group_settings', 'setting_id')
              ->where('group_id = ?', $group_id);
        $setting = $db->fetchCol($query);
        if ( $setting ) {
            return new Warecorp_DiscussionServer_Settings($setting);
        } else {
            $setting = new Warecorp_DiscussionServer_Settings();
            $setting->setGroupId($group_id);
            $setting->save();
            $setting = new Warecorp_DiscussionServer_Settings($setting->getId());
            return $setting;
        }
    }
    /**
     * Устанавливает свойство публикации дискуссий группы в фамили группе
     * @param int $groupId
     * @param int $familyGroupId
     * @param int $mode - 1 - allow, 2 - deny
     */
    static public function setGroupPublish($groupId, $familyGroupId, $mode)
    {                 
        if ( !in_array($mode, array(1,2)) ) $mode = 1;
        $db = Zend_Registry::get("DB");
        $query = $db->select()
                    ->from('zanby_discussion__group_publishing', '*')
                    ->where('group_id = ?', $familyGroupId)
                    ->where('sub_group_id = ?', $groupId);
        $res = $db->fetchRow($query);
        if ( !$res ) {
            $data = array();
            $data['group_id']       = $familyGroupId;
            $data['sub_group_id']   = $groupId;
            $data['publish']        = $mode;
            $rows_affected = $db->insert('zanby_discussion__group_publishing', $data);
        } elseif ($res['publish'] != $mode) {
            $data = array();
            $data['publish']        = $mode;
    	    $where = $db->quoteInto('group_id = ?', $familyGroupId);
    	    $where .= ' AND ' . $db->quoteInto('sub_group_id = ?', $groupId);
            $rows_affected = $db->update('zanby_discussion__group_publishing', $data, $where);
        }
    }
    /**
     * Возвращает свойство публикации дискуссий группы в фамили группе
     * @param int $groupId
     * @param int $familyGroupId
     * @param int $mode
     */
    static public function getGroupPublish($groupId, $familyGroupId)
    {
        $db = Zend_Registry::get("DB");
        $query = $db->select()
                    ->from('zanby_discussion__group_publishing', 'publish')
                    ->where('group_id = ?', $familyGroupId)
                    ->where('sub_group_id = ?', $groupId);
        $res = $db->fetchOne($query);
        if ( !$res ) {
            $tmpGroup = Warecorp_Group_Factory::loadById($groupId, Warecorp_Group_Enum_GroupType::GROUP_TYPE_SIMPLE);
            $data = array();
            $data['group_id']       = $familyGroupId;
            $data['sub_group_id']   = $groupId;            
            $data['publish']        = $tmpGroup->isPrivate() ? 2 : 1;
            $rows_affected = $db->insert('zanby_discussion__group_publishing', $data);
            return ($data['publish'] == 1) ? true : false;
        }
        return ($res == 1) ? true : false;
    }
}
?>
