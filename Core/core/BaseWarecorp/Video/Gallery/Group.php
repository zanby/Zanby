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
 * @package Warecorp_Video_Gallery
 * @author Yury Zolotarsky
 * @version 1.0
 */
class BaseWarecorp_Video_Gallery_Group extends Warecorp_Video_Gallery_Abstract
{

    public function delete()
    {
        $videos = $this->getVideos()->getList();
        if ( sizeof($videos) != 0 ) {
            foreach ( $videos as $video ) $video->delete();
        }

        $where = $this->_db->quoteInto('gallery_id = ?', $this->getId());
        $this->_db->delete(self::$_dbImportTableName, $where);

        $where = $this->_db->quoteInto('related_gallery_id = ?', $this->getId());
        $this->_db->delete(self::$_dbImportTableName, $where);
                
        $where = $this->_db->quoteInto('gallery_id = ?', $this->getId());
        $this->_db->delete(self::$_dbUserViewsTableName, $where);

        $where = $this->_db->quoteInto('gallery_id = ?', $this->getId());
        $this->_db->delete(self::$_dbWatchingTableName, $where);

        $where = $this->_db->quoteInto('gallery_id = ?', $this->getId());
        $this->_db->delete(self::$_dbShareTableName, $where);

/*        $where = $this->_db->quoteInto('gallery_id = ?', $this->getId());
        $this->_db->delete(self::$_dbShareHistoryTableName, $where);*/  

        parent::delete();
    }
}
?>
