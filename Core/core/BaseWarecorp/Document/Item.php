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
   *
   * @package    Warecorp_Document
   * @copyright  Copyright (c) 2006, 2008
   * @author Artem Sukharev
   */

  /**
   *
   *
   */
class BaseWarecorp_Document_Item extends Warecorp_Data_Entity implements  Warecorp_Global_iSearchFields
{
    private $id;
    private $ownerType;
    private $ownerId;
    private $creatorId;
    private $originalName;
    private $mimeType;
    private $description;
    private $creationDate;
    private $updateDate;
    private $private;
    private $isCheckOut;
    private $checkOutReason;
    private $checkOutUserId;
    private $checkOutUser;
    private $isLink;
    private $revisionId;

    private $share;

    private $fileSize = null;
    private $filePath = null;
    private $fileExt = null;
    private $iconImg = null;
    private $folderId;

    private $Owner = null;
    private $Creator = null;
    private $SharedGroups = null;
    private $SharedUsers = null;
    private $MimeType = null;
    private $AccessOwnerRoleName = 'Owner of document';

    /* If this attribute is set, do not update `creation_date` and
     * `update_date` in the table. */
    public $keepDates = null;

    /**
     * @return unknown
     */
    public function getIsLink()
    {
        return $this->isLink;
    }

    /**
     * @param unknown_type $isLink
     */
    public function setIsLink( $isLink )
    {
        $this->isLink = $isLink;
    }
    /**
     * @return unknown
     */
    public function getRevisionId()
    {
        return $this->revisionId;
    }

    /**
     * @param unknown_type $revisionId
     */
    public function setRevisionId( $revisionId )
    {
        $this->revisionId = $revisionId;
    }

    /**
     * Constructor.
     * @author Artem Sukharev
     */
    public function __construct($id = null)
    {
        parent::__construct('zanby_documents__items');

        $this->addField('id');
        $this->addField('owner_type',          'ownerType');
        $this->addField('owner_id',            'ownerId');
        $this->addField('creator_id',          'creatorId');
        $this->addField('original_name',       'originalName');
        $this->addField('mime_type',           'mimeType');
        $this->addField('description',         'description');
        $this->addField('creation_date',       'creationDate');
        $this->addField('update_date',         'updateDate');
        $this->addField('private',             'private');
        $this->addField('folder_id',           'folderId');
        $this->addField('is_check_out',        'isCheckOut');
        $this->addField('check_out_reason',    'checOutReason');
        $this->addField('check_out_user_id',   'checkOutUserId');
        $this->addField('is_link',             'isLink');
        $this->addField('revision_id',         'revisionId');

        if ($id !== null){
            $this->pkColName = 'id';
            $this->load($id);
        }
        /**
         * Detect size of document
         */
        if ( $this->getId() !== null && file_exists(DOC_ROOT.'/upload/documents/'.md5($this->getId()).'.file') ) {
            $this->setFileSize(filesize(DOC_ROOT.'/upload/documents/'.md5($this->getId()).'.file'));

            $this->setFileSize( ( $this->getFileSize() < 1024 )
                ? $this->getFileSize() . "b"
                : (( $this->getFileSize() < (1024 * 1024) )
                   ? sprintf("%01.0f", $this->getFileSize() / 1024) . "K"
                   : sprintf("%01.1f", $this->getFileSize() / (1024 * 1024)) . "M") );
            $this->setFilePath('/upload/documents/'.md5($this->getId()).'.file');
        }
        /**
         * Detect document icon
         */
        if ( $this->getId() ) {
            $this->setFileExt(Warecorp_File_Item::getFileExt($this->getOriginalName()));

            $AppTheme = (Zend_Registry::isRegistered('AppTheme')) ? Zend_Registry::get('AppTheme') : null;
            $extImagePath = ( ($AppTheme) ? $AppTheme->images_path.'/documents/files/' : DOC_ROOT.'/img/tree/files/' );
            $extImageUrl = ( ($AppTheme) ? $AppTheme->images.'/documents/files/' : BASE_URL.'/img/tree/files/' );

            if ( file_exists($extImagePath.strtolower($this->getFileExt()).".gif") ) {
                $this->setIconImg($extImageUrl.strtolower($this->getFileExt()).".gif");
            } else {
                $this->setIconImg($extImageUrl.'blank.gif');
            }
        }
    }
    public function getId()
    {
        return $this->id;
    }
    public function setId($newVal)
    {
        $this->id = $newVal;
        return $this;
    }
    public function getOwnerType()
    {
        return $this->ownerType;
    }
    public function setOwnerType($newVal)
    {
        $this->ownerType = $newVal;
        return $this;
    }
    public function getOwnerId()
    {
        return $this->ownerId;
    }
    public function setOwnerId($newVal)
    {
        $this->ownerId = $newVal;
        return $this;
    }
    public function getCreatorId()
    {
        return $this->creatorId;
    }
    public function setCreatorId($newVal)
    {
        $this->creatorId = $newVal;
        return $this;
    }
    public function getOriginalName()
    {
        return $this->originalName;
    }

   /**
    *alias for getOriginalName()
    **/
    public function getName()
    {
        return $this->getOriginalName();
    }
    public function setOriginalName($newVal)
    {
        $this->originalName = $newVal;
        return $this;
    }

   /**
    *alias for setOriginalName()
    **/
    public function setName($newVal)
    {
        return $this->setOriginalName($newVal);
    }
    public function getMimeType()
    {
        return $this->mimeType;
    }
    public function setMimeType($newVal)
    {
        $this->mimeType = $newVal;
        return $this;
    }
    public function getDescription()
    {
        return $this->description;
    }
    public function setDescription($newVal)
    {
        $this->description = $newVal;
        return $this;
    }
    public function getCreationDate()
    {
        return $this->creationDate;
    }
    public function setCreationDate($newVal)
    {
        $this->creationDate = $newVal;
        return $this;
    }
    public function getUpdateDate()
    {
        return $this->updateDate;
    }
    public function setUpdateDate($newVal)
    {
        $this->updateDate = $newVal;
        return $this;
    }
    public function getPrivate()
    {
        return $this->private;
    }
    public function setPrivate($newVal)
    {
        $this->private = $newVal;
        return $this;
    }
    public function getFileSize()
    {
        return $this->fileSize;
    }
    public function setFileSize($newVal)
    {
        $this->fileSize = $newVal;
        return $this;
    }
    public function getFilePath()
    {
        return $this->filePath;
    }
    public function setFilePath($newVal)
    {
        $this->filePath = $newVal;
        return $this;
    }
    public function getFileExt()
    {
        return $this->fileExt;
    }
    public function setFileExt($newVal)
    {
        $this->fileExt = $newVal;
        return $this;
    }
    public function getIconImg()
    {
        return $this->iconImg;
    }
    public function setIconImg($newVal)
    {
        $this->iconImg = $newVal;
        return $this;
    }
    public function getFolderId()
    {
        return $this->folderId;
    }
    public function setFolderId($newVal)
    {
        $this->folderId = $newVal;
        return $this;
    }
    public function getIsCheckOut()
    {
        return (boolean) $this->isCheckOut;
    }
    public function setIsCheckOut($newVal)
    {
        $this->isCheckOut = (boolean) $newVal;
        return $this;
    }
    public function getCheckOutReason()
    {
        return $this->checkOutReason;
    }
    public function setCheckOutReason($newVal)
    {
        $this->checkOutReason = $newVal;
        return $this;
    }
    public function getCheckOutUserId()
    {
        return $this->checkOutUserId;
    }
    public function setCheckOutUserId($newVal)
    {
        $this->checkOutUserId = $newVal;
        return $this;
    }
    public function getCheckOutUser()
    {
        if ( null === $this->checkOutUser ) $this->checkOutUser = new Warecorp_User('id', $this->getCheckOutUserId());
        return $this->checkOutUser;
    }
    public function getShare()
    {
        if ( null === $this->share ) {
            throw new Warecorp_Exception('Share property is not set');
        }
        return $this->share;
    }
    public function setShare($newValue)
    {
        $this->share = (boolean) $newValue;
    }
    /**
     * Set MimeType of document
     * @return void
     * @author Artem Sukharev
     */
    public function setMimeTypeByExt()
    {
        $dom = new DomDocument();
        $dom->load(RESOURCES_DIR."cfg.mimetypes.xml");
        $xp = new domXPath($dom);
        $titles = $xp->query('/config/mimetypes/mimetype[ext="'.strtolower($this->getFileExt()).'"]/mime');
        if ( $titles->length != 0 ) {
            $this->MimeType = $titles->item(0)->textContent;
        } else {
            $this->MimeType = "application/octet-stream";
        }
    }

    /**
     * Return MimeType of document
     * @return string
     * @author Artem Sukharev
     */
    public function getMimeTypeByExt()
    {
        if ( $this->MimeType === null ) {
            $this->setMimeTypeByExt();
        }
        return $this->MimeType;
    }

    /**
     * Проверяет, существует ли документ с указанными параметрами
     * @param int $id
     * @return bool
     * @author Artem Sukharev
     */
    public static function isDocumentExists($id)
    {
        $db = Zend_Registry::get("DB");
        $select = $db->select();
        $select->from('zanby_documents__items', 'id')
        ->where('id = ?', $id);
        $res = $db->fetchOne($select);
        return (bool) $res;
    }

    /**
     * Return owner of document
     * @return Warecorp_User or Warecorp_Group_Base object
     * @author Artem Sukharev
     */
    public function getOwner()
    {
        if ( $this->Owner === null ) {
            if ( $this->ownerType == 'user' ) {
                $this->Owner = new Warecorp_User("id", $this->getOwnerId());
            } elseif ( $this->ownerType == 'group' ) {
                $this->Owner = Warecorp_Group_Factory::loadById($this->getOwnerId());
            }
        }
        return $this->Owner;
    }

    /**
     * Set Creator of document
     * @return void
     * @author Artem Sukharev
     */
    public function setCreator()
    {
        $this->Creator = new Warecorp_User('id', $this->getCreatorId());
    }

    /**
     * Return Creator of document
     * @return Warecorp_User or Warecorp_Group_Simple object
     * @author Artem Sukharev
     */
    public function getCreator()
    {
        if ( $this->Creator === null ) {
            $this->setCreator();
        }
        return $this->Creator;
    }

    /**
     * return is document private
     * @author Artem Sukharev
     * @return bool
     */
    public function isPrivate()
    {
        return (bool) $this->getPrivate();
    }

    /**
     * Save or update Document
     * @return void
     * @author Vitaly Targonsky
     * @author Aleksei Gusev
     */
    public function save()
    {
        if ( !$this->keepDates ) {
            if ( $this->getId() ) {
                $this->setUpdateDate( new Zend_Db_Expr( 'NOW()' ) );
            } else {
                $this->setCreationDate( new Zend_Db_Expr( 'NOW()' ) );
                $this->setUpdateDate( new Zend_Db_Expr( 'NOW()' ) );
            }
        }

        if ( $this->getFolderId() ) {
            $folder = new Warecorp_Document_FolderItem( $this->getFolderId() );
            $folder->save();
            unset( $folder );
        }
        parent::save();
    }

    /**
     * Delete document and all related objects
     * @return void
     * @author Artem Sukharev
     */
    public function delete()
    {
        $this->deleteTags();

        /* delete revisions */
        $objRevisionList = new Warecorp_Document_Revision_List();
        $objRevisionList->setDocumentId($this->getId());
        $listRevisions = $objRevisionList->getList();

        if ( sizeof($listRevisions) != 0 ) {
            foreach ( $listRevisions as $_revision ) {
                if ( file_exists(DOC_ROOT.$_revision->getFilePath()) && is_file(DOC_ROOT.$_revision->getFilePath()) ) {
                    @unlink(DOC_ROOT.$_revision->getFilePath());
                }
                $_revision->delete();
            }
        }
        $folder = str_replace('.file', '', $this->getFilePath());
        if ( file_exists(DOC_ROOT.$folder) ) @rmdir(DOC_ROOT.$folder);

        $sharedGroups = $this->getSharedGroups();
        foreach ( $sharedGroups as $group ) {
            $this->unshareDocument('group', $group->getId());
        }
        $sharedUsers = $this->getSharedUsers();
        foreach ( $sharedUsers as $user ) {
            $this->unshareDocument('user', $user->getId());
        }
        Warecorp_Share_Entity::removeShare(null, $this->getId(), $this->EntityTypeId, true);
        if ( file_exists(DOC_ROOT.$this->getFilePath()) && is_file(DOC_ROOT.$this->getFilePath()) ) {
            @unlink(DOC_ROOT.$this->getFilePath());
        }

        parent::delete();
    }

    /**
     * move document in new space
     * @param int $ownerId
     * @param string $ownerType
     * @param int $folderId
     * @author Artem Sukharev
     */
    public function move($ownerId, $ownerType, $folderId)
    {
        $this->setOwnerId($ownerId)
        ->setOwnerType($ownerType)
        ->setFolderId($folderId);
        $this->save();
    }

    /**
     * Check owner for document
     * @param string $owner_type - user|group
     * @param int $owner_id
     * @return bool
     * @author Artem Sukharev
     */
    public function isOwner($owner_type, $owner_id)
    {
        if ( $owner_type == $this->getOwnerType() && $owner_id == $this->getOwner()->getId() ) {
            return true;
        }
        return false;
    }

    /**
     * Check if document already shared to group or user
     * @param int $document_id
     * @param string $owner_type - 'user' | 'group'
     * @param int $owner_id
     * @return bool
     * @author Artem Sukharev
     */
    public static function isDocumentShared($document_id, $owner_type, $owner_id) {
        $db = Zend_Registry::get("DB");
        $select = $db->select();
        $select->from("zanby_documents__sharing", new Zend_Db_Expr("count(*)"))->where('owner_type = ?', $owner_type)->where('owner_id = ?', $owner_id)->where('document_id = ?', $document_id);
        $res = $db->fetchOne($select);
        return (bool) $res;
    }

    /**
     * Share document to group or user
     * @param string $owner_type - 'user' | 'group'
     * @param int $owner_id
     * @param boolean $shareFamilyAllGroups
     * @return void
     * @author Artem Sukharev
     */
    public function shareDocument($owner_type, $owner_id, $shareFamilyAllGroups = false)
    {
        if ( $shareFamilyAllGroups ) {
            if ( !Warecorp_Share_Entity::isShareExists($owner_id, $this->getId(), $this->EntityTypeId) ) {
                Warecorp_Share_Entity::addShare($owner_id, $this->getId(), $this->EntityTypeId);
            }
        }
        elseif ( !Warecorp_Document_Item::isDocumentShared($this->getId(), $owner_type, $owner_id) ) {
            $this->_db->insert('zanby_documents__sharing', array(
               'owner_type' => $owner_type,
               'owner_id' => $owner_id,
               'document_id' => $this->getId()
           ));
        }
    }

    /**
     * Unshare document from group or user
     * @param string $owner_type - 'user' | 'group'
     * @param int $owner_id
     * @param boolean $allFamilySharing
     * @return void
     * @author Artem Sukharev
     * @todo replace this select with correct command from future versions of ZF
     */
    public function unshareDocument($owner_type, $owner_id, $allFamilySharing = false)
    {
        if ( $allFamilySharing ) {
            Warecorp_Share_Entity::removeShare($owner_id, $this->getId(), $this->EntityTypeId, true);
        }
        else {
            $this->_db->delete('zanby_documents__sharing',
                $this->_db->quoteInto('document_id = ?', $this->getId()).
                $this->_db->quoteInto(' AND owner_id = ?', $owner_id).
                $this->_db->quoteInto(' AND owner_type = ?', $owner_type));
        }
    }

    /**
     * Set shared groups for document
     * @return void
     * @author Artem Sukharev
     */
    public function setSharedGroups()
    {
        $select = $this->_db->select();
        $select->from('zanby_documents__sharing', 'owner_id')
            ->where('document_id = ?', $this->getId())
            ->where('owner_type = ?', 'group')
            ->join('zanby_groups__items','id = owner_id'); //FIXME можно будет убрать, когда при удалении группы будут аншариться все контенты.
        $groups = $this->_db->fetchCol($select);
        $outGroups = array();
        foreach ( $groups as &$group) {
            $group = Warecorp_Group_Factory::loadById($group);
            if ( $group && $group->getId() )
                $outGroups[$group->getId()] = $group;
        }
        $this->SharedGroups = $outGroups;
    }

    /**
     * Get shared groups for document
     * @return array of Warecorp_Group_Simple
     * @author Artem Sukharev
     */
    public function getSharedGroups()
    {
        if ( $this->SharedGroups === null ) {
            $this->setSharedGroups();
        }
        return $this->SharedGroups;
    }

    /**
     * Set shared users for document
     * @return void
     * @author Artem Sukharev
     */
    public function setSharedUsers()
    {
        $select = $this->_db->select();
        $select->from('zanby_documents__sharing', 'owner_id')
        ->where('document_id = ?', $this->getId())
        ->where('owner_type = ?', 'user');
        $users = $this->_db->fetchCol($select);
        foreach ( $users as &$user) $user = new Warecorp_User('id', $user);
        $this->SharedUsers = $users;
    }

    /**
     * Get shared users for document
     * @return array of Warecorp_Group_Simple
     * @author Artem Sukharev
     */
    public function getSharedUsers()
    {
        if ( $this->SharedUsers === null ) {
            $this->setSharedUsers();
        }
        return $this->SharedUsers;
    }

    /**
     * return path to icon by document extension
     * @param string $extension
     * @param string $prefix
     * @return string
     * @author Artem Sukharev
     */
    public static function getImageFileNameByExtension($extension, $prefix = '')
    {
        /*
         * @author Artem Sukharev
         * all documnets icons must be in theme folder
         *
        if (file_exists(DOC_ROOT.'/img/tree/files/'.$extension.'_'.$prefix.'.gif')) {
            return BASE_URL.'/img/tree/files/'.$extension.'_'.$prefix.'.gif';
        }
        return BASE_URL.'/img/tree/files/blank_'.$prefix.'.gif';
        */

        $AppTheme = (Zend_Registry::isRegistered('AppTheme')) ? Zend_Registry::get('AppTheme') : null;
        $extImagePath = ( ($AppTheme) ? $AppTheme->images_path.'/documents/files/' : DOC_ROOT.'/img/tree/files/' );
        $extImageUrl = ( ($AppTheme) ? $AppTheme->images.'/documents/files/' : BASE_URL.'/img/tree/files/' );

        if ( file_exists($extImagePath.$extension.'_'.$prefix.".gif") ) {
            return $extImageUrl.$extension.'_'.$prefix.".gif";
        } else {
            return $extImageUrl.'blank_'.$prefix.".gif";
        }

    }

    public function checkIn()
    {
        $data = array();
        $data['is_check_out'] = 0;
        $data['check_out_user_id'] = new Zend_Db_Expr('NULL');
        $data['check_out_reason'] = '';
        $where = $this->_db->quoteInto('id = ?', $this->getId());
        $this->_db->update('zanby_documents__items', $data, $where);
        $this->setIsCheckOut(false);
        return $this;
    }

    public function checkOut( $creatorId, $reason = '' )
    {
        $data = array();
        $data['is_check_out'] = 1;
        $data['check_out_user_id'] = $creatorId;
        $data['check_out_reason'] = $reason;
        $where = $this->_db->quoteInto('id = ?', $this->getId());
        $this->_db->update('zanby_documents__items', $data, $where);
        $this->setIsCheckOut(true);
        return $this;
    }

    public function getLastRevision()
    {
        $objRevisionsList = new Warecorp_Document_Revision_List();
        $objRevisionsList->setDocumentId($this->getId());
        $revision = $objRevisionsList->getLastRevision();
        return $revision;
    }

    /**
     * Used in view side only for build dynamic menu
     * @return boolean
     */
    public function canBeCheckOut()
    {
        if ( $this->getIsLink() )       return false;
        if ( $this->getIsCheckOut() )   return false;
        if ( $this->getShare() )        return false;
        return true;
    }

    public function canBeCheckIn()
    {
        if ( $this->getIsLink() )       return false;
        if ( !$this->getIsCheckOut() )  return false;
        if ( $this->getShare() )        return false;
        return true;
    }

    /**
     * Used in view side only for build dynamic menu
     * @return boolean
     */
    public function canBeCancelCheckOut()
    {
        if ( $this->getIsLink() )       return false;
        if ( !$this->getIsCheckOut() )  return false;
        if ( $this->getShare() )        return false;
        return true;
    }

    /**
     * Used in view side only for build dynamic menu
     * @return boolean
     */
    public function canBeRevision()
    {
        if ( $this->getIsLink() )       return false;
        if ( $this->getShare() )        return false;
        return true;
    }

    /**
     * Used in view side only for build dynamic menu
     * @return boolean
     */
    public function canBeShared($context, $owner, $user_id)
    {
        if ( $this->getShare() ) return false;
        if ( Warecorp_Document_AccessManager_Factory::create()->canManageOwnerDocuments($context, $owner, $user_id) ) return true;

        return false;
    }

    /**
     * Used in view side only for build dynamic menu
     * @return boolean
     */
    public function canBeUnShared($context, $user_id)
    {
        if ( $this->getShare() ) {
            if ( Warecorp_Document_AccessManager_Factory::create()->canUnshareDocument($this, $context, $user_id) ) {
                return true;
            }
        }
        return false;
    }

    public function canBeEdit()
    {

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
        return $this->getOriginalName();
    }

    /**
    * return headline for entity (like group headline, members first and last name, photo or gallery title,etc).
    * for entities which didn't have headline will be returned entityTitle
    * @return string
    */
    public function entityHeadline()
    {
        return $this->getOriginalName();
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
        return $this->getIconImg();
    }

    /**
    * return creation date for all elements
    * @return string
    */
    public function entityCreationDate()
    {
        return $this->getCreationDate();
    }

    /**
    * return update date for all elements
    * @return string
    */
    public function entityUpdateDate()
    {
        return $this->getCreationDate();
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
        return $this->getCreator()->getCountry()->name;
    }

    /**
    * get country_int for entity (users, groups, events)
    * possible values: int, null
    * @return int
    */
    public function entityCountryId()
    {
        return $this->getCreator()->getCountry()->id;
    }


    /**
    * get city for entity (users, groups, events)
    * possible values: string
    * @return int
    */
    public function entityCity()
    {
        return $this->getCreator()->getCity()->name;
    }

    /**
    * get city_id for entity (users, groups, events)
    * possible values: int, null
    * @return int
    */
    public function entityCityId()
    {
        return $this->getCreator()->getCityId();
    }

    /**
    * get zip for entity (users, groups, events)
    * possible values: int, null
    * @return int
    */
    public function entityZIP()
    {
        return $this->getCreator()->getZipcode();
    }

    /**
    * get state for entity (users, groups, events)
    * possible values: int, null
    * @return int
    */
    public function entityState()
    {
        return $this->getCreator()->getState()->name;
    }

    /**
    * get state_id for entity (users, groups, events)
    * possible values: int, null
    * @return int
    */
    public function entityStateId()
    {
        return $this->getCreator()->getState()->id;
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
            return $this->getOwner()->getGlobalPath('docget').'docid/'.$this->getId().'/';
    }

}
