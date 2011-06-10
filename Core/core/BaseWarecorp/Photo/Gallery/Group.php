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
 * Warecorp FRAMEWORK
 * @package Warecorp_Photo_Gallery
 * @author Artem Sukharev
 * @version 1.0
 */
class BaseWarecorp_Photo_Gallery_Group extends Warecorp_Photo_Gallery_Abstract
{
    /**
     * delete gallery and all photos + sharing
     * @return void
     * @author Artem Sukharev
     */
    /**
     * delete gallery and all photos + sharing
     * @return void
     * @author Artem Sukharev
     */
    public function delete()
    {
        /**
         * remove photos
         */
        $photos = $this->getPhotos()->getList();
        if ( sizeof($photos) != 0 ) {
            foreach ( $photos as $photo ) $photo->delete();
        }
        /**
         * remove gallery views history
         */
        $where = $this->_db->quoteInto('gallery_id = ?', $this->getId());
        $this->_db->delete(self::$_dbUserViewsTableName, $where);
        /**
         * remove gallery import history
         */
        $where = $this->_db->quoteInto('gallery_id = ?', $this->getId());
        $this->_db->delete(self::$_dbImportTableName, $where);
        /**
         * remove gallery watching
         */
        $where = $this->_db->quoteInto('gallery_id = ?', $this->getId());
        $this->_db->delete(self::$_dbWatchingTableName, $where);
        /**
         * remove gallery sharing
         */
        $where = $this->_db->quoteInto('gallery_id = ?', $this->getId());
        $this->_db->delete(self::$_dbShareTableName, $where);
        /**
         * Remove share to all Family's Groups
         */
        Warecorp_Share_Entity::removeShare(null, $this->getId(), $this->EntityTypeId, true);
        /**
         * remove sharing history
         */
        $where = $this->_db->quoteInto('gallery_id = ?', $this->getId());
        $this->_db->delete(self::$_dbShareHistoryTableName, $where);
        /**
         * invoke parent method
         */
        parent::delete();
    }
}
?>
