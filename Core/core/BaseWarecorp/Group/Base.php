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
 * @package    Warecorp_Group_Simple
 * @copyright  Copyright (c) 2007
 */

/**
 * Base Groups class
 *
 */

require_once(WARECORP_DIR.'DiscussionServer/iDiscussionGroup.php');

class BaseWarecorp_Group_Base extends Warecorp_Data_Entity implements Warecorp_DiscussionServer_iDiscussionGroup
{
    protected $id;
    protected $name;
    protected $description;
    protected $groupType;
    protected $categoryId;
    protected $zipcode;
    protected $cityId;
    protected $createDate;
    protected $artifacts;
    protected $discussions;
    protected $groupUID;
    protected $importedGroup;
    protected $mainGroupUID = NULL;
    protected $contextRegionalFlag;  /// @author Roman Gabrusenok
    protected $mapMarker;
    protected $discussionsCount = 0 ;
    protected $topicsCount = 0;
    protected $postsCount = 0;
    private $profile = null;

    /**
     * @return string|null
     */
    public function getMapMarkerHash()
    {
        return $this->mapMarker;
    }

    function getProfile()
    {
        if ($this->profile === null){        
            $this->profile = Warecorp_Group_Profile_Factory::getProfile($this->id);
        }
        return $this->profile;
    }
    
    /**
     * @param string $filename
     * @return self
     */
    public function setMapMarkerHash( $filename )
    {
        if ( strlen($filename) )
            $filename = substr($filename, 0, 230);  //  DB fiel VARCHAR(255)
        $this->mapMarker = $filename;
        return $this;
    }

    /**
     *  @return Warecorp_Group_Marker_Item
     */
    public function getMarker()
    {
        return new Warecorp_Group_Marker_Item($this);
    }

    public function getContextRegionalFlag()
    {
        return $this->contextRegionalFlag;
    }

    public function getCongressionalDistrict()
    {
        $district = $this->getContextRegionalFlag();
        if (strstr($district, 'z1sky_simple_') || strstr($district, 'simple_') || strstr($district, 'z1sky_district_')){
            $district = str_replace('z1sky_simple_', '', $district);
        	$district = str_replace('simple_', '', $district);
            $district = str_replace('z1sky_district_', '', $district);
            return $district;
        }
        else{
            return null;
        }
    }
    
    /**
     * @author Komarovski
     */
    public function isCongressionalDistrict() {
    	return (strstr($this->getContextRegionalFlag(), 'z1sky_district_'))?true:false;
    }

    public function setContextRegionalFlag($regionalFlag)
    {
        $this->contextRegionalFlag = $regionalFlag;
        return $this;
    }

    public function setId($newVal)
    {
    	$this->id = (int) $newVal;
    	return $this;
    }
    public function getId()
    {
    	return $this->id;
    }
    public function setGroupUID($newVal)
    {
        $this->groupUID = $newVal;
        return $this;
    }
    public function getGroupUID()
    {
        return $this->groupUID;
    }

    public function setMainGroupUID($newVal)
    {
        $this->mainGroupUID = $newVal;
        return $this;
    }
    public function getMainGroupUID()
    {
        return $this->mainGroupUID;
    }


    public function setName($newVal)
    {
    	$this->name = $newVal;
    	return $this;
    }
    public function getName()
    {
    	return $this->name;
    }
    public function setDescription($newVal)
    {
    	$this->description = $newVal;
    	return $this;
    }
    public function getDescription()
    {
    	return $this->description;
    }
    public function setGroupType($newVal)
    {
    	$this->groupType = $newVal;
    	return $this;
    }
    public function getGroupType()
    {
    	return $this->groupType;
    }
    public function setCategoryId($newVal)
    {
    	$this->categoryId = $newVal;
    	return $this;
    }
    public function getCategoryId()
    {
    	return $this->categoryId;
    }
    public function setZipcode($newVal)
    {
    	$this->zipcode = $newVal;
    	return $this;
    }
    public function getZipcode()
    {
    	return $this->zipcode;
    }
    public function setCityId($newVal)
    {
    	$this->cityId = (int) $newVal;
    	return $this;
    }
    public function getCityId()
    {
    	return $this->cityId;
    }
    public function setCreateDate($newVal)
    {
        $this->createDate = $newVal;
        return $this;
    }
    public function getCreateDate()
    {
        return $this->createDate;
    }
    public function setImportedGroup($newVal)
    {
        $this->importedGroup = $newVal;
        return $this;
    }
    public function getImportedGroup()
    {
        return $this->importedGroup;
    }
    /**
     * Get Members List object for group
     * @return Warecorp_Group_Members_Abstract
     * @author Artem Sukharev
     */
    public function getMembers()
    {
    	return Warecorp_Group_Members_Factory::create($this);
    }

    /**
     * Get Members Locations List object
     * @return Warecorp_Group_Location_Member_AbstractList
     * @author Artem Sukharev
     */
    public function getLocations()
    {
        return Warecorp_Group_Location_Member_Factory::create($this);
    }

    /**
     * get Artifacts List object for group
     * @return Warecorp_Artifacts
     * @author Artem Sukharev
     */
    public function getArtifacts()
    {
        if ( $this->artifacts === null ) $this->artifacts = new Warecorp_Artifacts($this);
        return $this->artifacts;
    }

    /**
     * get tags list object
     * @return Warecorp_Group_Tag_List
     * @author Artem Sukharev
     */
    public function getTags()
    {
    	return new Warecorp_Group_Tag_List($this->getId());
    }

    /**
     * get group discussions count
     * @return int
     */
    public function getDiscussionsCount()
    {
        return $this->discussionsCount;
    }

    /**
     * get summary topics count
     * @return int
     */
    public function getTopicsCount()
    {
        return $this->topicsCount;
    }

    /**
     * get summary group posts count
     * @return int
     */
    public function getPostsCount()
    {
        return $this->postsCount;
    }

    /**
     * Constructor
     *
     */
	public function __construct($tableName = false)
	{
	    $tableName = !empty($tableName) ? $tableName : 'zanby_groups__items';
	    parent::__construct($tableName, array(
	        'id'                    => 'id',
	        'name'                  => 'name',
	        'description'           => 'description',
	        'type'                  => 'groupType',
	        'category_id'           => 'categoryId',
	        'zipcode'               => 'zipcode',
	        'city_id'               => 'cityId',
	        'creation_date'         => 'createDate',
            'groupuid'              => 'groupUID',
	        'maingroupuid'          => 'mainGroupUID',
            'imported_group'        => 'importedGroup',
            'context_regional_flag' => 'contextRegionalFlag',
            'map_marker'            => 'mapMarker',
            'discussions_count'     => 'discussionsCount',
            'topics_count'          => 'topicsCount',
            'posts_count'           => 'postsCount'
	    ));
       // $this->profile = Warecorp_Group_Profile_Factory::getProfile($this->getId());
	}
   
    public function save(){
        parent::save();
        
        if ($this->profile !== null){
            $this->profile->setId($this->getId());
            $this->profile->save();
            $this->profile = null;
        }
    }
	/**
	 * delete group from system
	 * @author Artem Sukharev
	 */
    // FIXME delete events and venues
    // FIXME delete documents and folder
    public function delete()
    {

    	/**
    	 * delete family relations (this group -> family)
    	 */
    	if ( $this instanceof Warecorp_Group_Standard ) {
            $familyGroups = $this->getFamilyGroups()->setStatus(Warecorp_Group_Enum_GroupStatus::GROUP_STATUS_BOTH)->getList();
            if ( sizeof($familyGroups) != 0 ) {
                foreach ( $familyGroups as $family )  $family->getGroups()->removeGroup($this->getId());
            }
    	}
    	
        /**
         * delete group relations
         */
        $table = 'zanby_groups__relations';
        $where = $this->_db->quoteInto('parent_group_id = ?', $this->getId());
        $where .= ' OR '.$this->_db->quoteInto('child_group_id = ?', $this->getId());
        $rows_affected = $this->_db->delete($table, $where);

        /**
         * delete members
         */
        $table = 'zanby_groups__members';
        $where = $this->_db->quoteInto('group_id = ?', $this->getId());
        $rows_affected = $this->_db->delete($table, $where);

        /**
    	 * delete documents and folder
    	 */
        $documentObj = new Warecorp_Document_List($this);
        $documents = $documentObj->setShowShared(true)->getList();
        foreach($documents as $document) {
                $document->delete();
        }
        $foldersObj = new Warecorp_Document_FolderList($this);
        $folders = $foldersObj->getList();
        foreach($folders as $folder) {
            $folder->deleteFolderRecursively();
        }

    	/**
    	 * delete photo galleries
    	 */
        if ( $this instanceof Warecorp_Group_Standard ) {
        	/**
        	 * remove own galleries
        	 */
            $galleriesList = $this->getGalleries()
                                  ->setSharingMode(Warecorp_Photo_Enum_SharingMode::OWN)
                                  ->setWatchingMode(Warecorp_Photo_Enum_WatchingMode::OWN)
                                  ->getList();
            if ( sizeof($galleriesList) != 0 ) {
                foreach ( $galleriesList as $gallery ) $gallery->delete();
            }
            /**
             * remove sharing relations
             */
            $galleriesList = $this->getGalleries()
                                  ->setSharingMode(Warecorp_Photo_Enum_SharingMode::SHARED)
                                  ->setWatchingMode(Warecorp_Photo_Enum_WatchingMode::OWN)
                                  ->getList();
            if ( sizeof($galleriesList) != 0 ) {
                foreach ( $galleriesList as $gallery ) $gallery->unshare($this);
            }
        }

        /**
         * delete video collections
         */
        if ( $this instanceof Warecorp_Group_Standard ) {
            /**
             * remove own galleries
             */
            $galleriesList = $this->getVideoGalleries()
                                  ->setSharingMode(Warecorp_Photo_Enum_SharingMode::OWN)
                                  ->setWatchingMode(Warecorp_Photo_Enum_WatchingMode::OWN)
                                  ->getList();
            if ( sizeof($galleriesList) != 0 ) {
                foreach ( $galleriesList as $gallery ) $gallery->delete();
            }
            /**
             * remove sharing relations
             */
            $galleriesList = $this->getVideoGalleries()
                                  ->setSharingMode(Warecorp_Photo_Enum_SharingMode::SHARED)
                                  ->setWatchingMode(Warecorp_Photo_Enum_WatchingMode::OWN)
                                  ->getList();
            if ( sizeof($galleriesList) != 0 ) {
                foreach ( $galleriesList as $gallery ) $gallery->unshare($this);
            }
        }

    	/**
    	 * delete lists
    	 */
        $listsList = new Warecorp_List_List($this);
        foreach ($listsList->getList() as $list) {
        	$list->delete();
        }
        $listsList->unshareAllListsFromGroup($this->getId());

    	/**
    	 * delete events
    	 * @todo venues
    	 * event__reminder   - CASCADE
         * event__exceptions - CASCADE
         * event__attendance - CASCADE
    	 */
        $objEvents = new Warecorp_ICal_Event_List_Standard();
        $objEvents->setTimezone('UTC');
        $objEvents->setOwnerIdFilter($this->getId());
        $objEvents->setOwnerTypeFilter(Warecorp_ICal_Enum_OwnerType::GROUP);
        $objEvents->setPrivacyFilter(array(0,1));
        $objEvents->setSharingFilter(array(0));
        $objEvents->setCurrentEventFilter(true);
        $objEvents->setExpiredEventFilter(true);
        $arrEvents = $objEvents->setFetchMode(Warecorp_ICal_List_Enum_FetchMode::OBJECT)->getList();
        if ( sizeof($arrEvents) != 0 ) {
            foreach ( $arrEvents as $objEvent ) $objEvent->delete();
        }

    	/**
    	 * delete discussions
    	 */
        $dSettings = Warecorp_DiscussionServer_Settings::findByGroupId($this->getId());
        if ( $dSettings->getId() ) $dSettings->delete();

        $subsciptionObj = new Warecorp_DiscussionServer_GroupSubscriptionList();
        $subsciption = $subsciptionObj->getByGroup($this->getId());
        if ( sizeof($subsciption) != 0 ) {
            foreach ( $subsciption as $subscr ) $subscr->delete();
        }

        $this->deleteDiscussionGroup();

    	/**
    	 * delete avatars
    	 */
        $avatarList = new Warecorp_Group_Avatar_List($this->getId());
        $avatars = $avatarList->getList();
        if ( sizeof($avatars) != 0 ) {
            foreach ( $avatars as $avatar ) $avatar->delete();
        }

        /**
         * remove relations from users addressbook
         */
        $table = 'zanby_addressbook__contacts';
        $where = array();
        $where[] = $this->_db->quoteInto('classname = ?', 'Warecorp_User_Addressbook_Group');
        $where[] = $this->_db->quoteInto('entity_id = ?', $this->getId());
        $where = join(' AND ', $where);
        $rows_affected = $this->_db->delete($table, $where);

        /**
         * remove summary page (DDPage) content
         */
        $table = 'zanby_dd__pages';
        $where = array();
        $where[] = $this->_db->quoteInto('entity_type_id = ?', $this->EntityTypeId);
        $where[] = $this->_db->quoteInto('entity_id = ?', $this->getId());
        $where = join(' AND ', $where);
        $rows_affected = $this->_db->delete($table, $where);

        /**
         * remove hierarchy components
         */

        //  remove own tree
		$h = Warecorp_Group_Hierarchy_Factory::create();
		$h->setGroupId($this->getId());
		$r = $h->getHierarchyList();
		if ( sizeof($r) != 0 ) {
            foreach ( $r as $hierarchy ) $hierarchy->removeHierarchy($hierarchy->getId());
		}

        //  update tree
        //  удаляем все записи в таблице дерева, в которые входит удаляемая группа
        $query = $this->_db->select();
        $query->from('zanby_groups__hierarchy_tree', 'id');
        $query->where('type = ?', 'item');
        $query->where('group_id = ?', $this->getId());
        $res = $this->_db->fetchCol($query);
        if ( sizeof($res) != 0 ) {
        	foreach ( $res as $itemId ) $h->removeItem($itemId);
        }

        //  relations
        $table = 'zanby_groups__hierarchy_relation';
        $where = $this->_db->quoteInto('group_id = ?', $this->getId());
        $rows_affected = $this->_db->delete($table, $where);

    	/**
    	 * invoke Data_Entity::delete()
         * delete badge - CASCADE
         * delete brand - CASCADE
         * delete publish settings - CASCADE
         * delete group avatars - CASCADE deleting
         * delete join attemps - CASCADE
         * delete group privileges - CASCADE
    	 */
         
        //-----------------------------Remove profile-------------------------------------------------------------------
        if ($this->profile !== null){
            $this->profile->delete();
            $this->profile = null;
        }
        
        parent::delete();


        $memcache = Warecorp_Cache::getMemCache();
        $memcache->remove('g'.$this->getId());

        /**
         * run stored procedure
         */
//        $exec = $this->_db->prepare("CALL refresh_family_relations()");
//        $exec->execute();
    }

    public function getGroupPath( $action = null, $withslash = true )
    {
        return '';
    }

    public function getOwnerPath( $action = null, $withslash = true )
    {
        return $this->getGroupPath($action, $withslash);
    }

	/*
     +-----------------------------------
     |
     | iDiscussionServer Interface
     |
     +-----------------------------------
    */

    /**
     * return ID of current group
     * @return int
     * @author Artem Sukharev
     */
	public function getDiscussionGroupId(){
	   return $this->id;
	}

    /**
     * return name of group
     * @return string
     * @author Artem Sukharev
     */
	public function getDiscussionGroupName(){
	   return $this->name;
	}

    /**
     * return link to group home page
     * @return string
     * @author Artem Sukharev
     */
	public function getDiscussionGroupHomePageLink(){
	   return $this->getGroupPath('summary');
	}

    /**
     * return type of current group
     * @return string
     * @author Artem Sukharev
     */
	public function getDiscussionGroupType(){
	   return $this->groupType;
	}

    /**
     * return Warecorp_DiscussionServer_DiscussionList object for current group
     * @return object Warecorp_DiscussionServer_DiscussionList
     * @author Artem Sukharev
     */
    public function getDiscussionGroupDiscussions()
    {
        if ( $this->discussions === null ) {
            $this->discussions = new Warecorp_DiscussionServer_DiscussionList();
        }
        return $this->discussions;
    }

    /**
     * return list on groups
     * @return array of Warecorp_DiscussionServer_iDiscussionGroup
     * @author Artem Sukharev
     */
    public function getDiscussionGroupList()
    {
    }


    /**
     * return Warecorp_DiscussionServer_iModerator object
     * Перекрывается в классе Warecorp_Group_Standard
     * @return object Warecorp_DiscussionServer_iModerator
     * @author Artem Sukharev
     */
    public function getDiscussionGroupHost()
    {
        return null;
    }

    /**
     * return Warecorp_DiscussionServer_AccessManager object () [singleton]
     * @return obj Warecorp_DiscussionServer_AccessManager
     * @author Artem Sukharev
     */
    public function getDiscussionAccessManager(){
        return Warecorp_DiscussionServer_AccessManager_Factory::create();
    }

    /**
     * return Warecorp_DiscussionServer_Settings object
     * @return obj Warecorp_DiscussionServer_Settings
     * @author Artem Sukharev
     */
    public function getDiscussionGroupSettings()
    {
        $dSettings = Warecorp_DiscussionServer_Settings::findByGroupId($this->getDiscussionGroupId());
        $dSettings->setGroup($this);
        return $dSettings;
    }

    /**
     * delete discussion server artifacts for group
     * @author Artem Sukharev
     * @todo implement it
     */
    public function deleteDiscussionGroup()
    {
        $glist = new Warecorp_DiscussionServer_DiscussionList();
        $groupDiscussions = $glist->findByGroupId($this->id);
        if ( sizeof($groupDiscussions) != 0 ) {
            foreach ( $groupDiscussions as $discussion ) $discussion->delete(true);
        }
    }

    /**
     * create default discussion for group
     * Overridden in Warecorp_Group_Standard
     * @author Artem Sukharev
     */
    public function createMainDiscussion() {}

    /**
     * update default discussion for group
     * Overridden in Warecorp_Group_Standard
     * @author Artem Sukharev
     */
    public function updateMainDiscussion(){}

    /**
     * return main discussion for group
     * @return Warecorp_DiscussionServer_Discussion
     */
    public function getGroupMainDiscussion()
    {
        $discussionList = new Warecorp_DiscussionServer_DiscussionList();
        return $discussionList->findMainByGroupId($this->getId());
    }

    /*
     +-----------------------------------
     |
     | iSender Interface
     |
     +-----------------------------------
    */

    public function getSenderId()
    {
        return $this->id;
    }
    public function getSenderType()
    {
        return "group";
    }
    public function getSenderDisplayName()
    {
        return $this->name;
    }

    /*
     +-----------------------------------
     |
     | iRecipient Interface
     |
     +-----------------------------------
    */

    public function getRecipientId()
    {
        return $this->id;
    }
    public function getRecipientType()
    {
        return "group";
    }
    public function getRecipientDisplayName()
    {
        return $this->name;
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
        return $this->groupType;
    }

    /**
    * return owner type
    * possible values: group, user
    * @return string
    */
    public function entityOwnerType()
    {
        return "group";
    }

    /**
    * return title for entity (like group name, username, photo or gallery title)
    * @return string
    */
    public function entityTitle()
    {
        return $this->getName();
    }

    /**
    * return headline for entity (like group headline, members first and last name, photo or gallery title,etc).
    * for entities which didn't have headline will be returned entityTitle
    * @return string
    */
    public function entityHeadline()
    {
        return "";
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
        return "";
    }

    /**
    * return user_id of entity owner
    * @return string
    */
    public function entityAuthorId()
    {
        return null;
    }

    /**
    * return picture URL (avatar, group picture, trumbnails, etc.)
    * @return int
    */
    public function entityPicture()
    {
        return "";
    }

    /**
    * return creation date for all elements
    * @return string
    */
    public function entityCreationDate()
    {
        return $this->getCreateDate();
    }

    /**
    * return update date for all elements
    * @return string
    */
    public function entityUpdateDate()
    {
        return "";
    }

    /**
    * items count (members, posts, child groups, etc.)
    * @return int
    */
    public function entityItemsCount()
    {
        return null;
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
        return $this->getCategoryId();
    }

    /**
    * get country for entity (users, groups, events)
    * possible values: string
    * @return int
    */
    public function entityCountry()
    {
        return "";
    }

    /**
    * get country_int for entity (users, groups, events)
    * possible values: int, null
    * @return int
    */
    public function entityCountryId()
    {
        return null;
    }


    /**
    * get city for entity (users, groups, events)
    * possible values: string
    * @return int
    */
    public function entityCity()
    {
        return "";
    }

    /**
    * get city_id for entity (users, groups, events)
    * possible values: int, null
    * @return int
    */
    public function entityCityId()
    {
        return $this->getCityId();
    }

    /**
    * get zip for entity (users, groups, events)
    * possible values: int, null
    * @return int
    */
    public function entityZIP()
    {
        return $this->getZipcode();
    }

    /**
    * get state for entity (users, groups, events)
    * possible values: int, null
    * @return int
    */
    public function entityState()
    {
        return "";
    }

    /**
    * get state_id for entity (users, groups, events)
    * possible values: int, null
    * @return int
    */
    public function entityStateId()
    {
        return null;
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
                          
    public function sendThank( $objRecipient )
    {
        if ( $this->getGroupType() == 'family' ) $keyTemplate = 'CREATE_NEW_GROUP_FAMILY_THANK';
        else $keyTemplate = 'CREATE_NEW_GROUP_THANK';        
        
        /* SOAP: MailSrv */
        $msrvRecipients = new Warecorp_SOAP_Type_Recipients();
        $msrvSended = false;
                
        /* SOAP: MailSrv */
        if ( Warecorp::isMailServerUsed() && Warecorp::isMailServerTemplateRegistered( $keyTemplate ) ) {
                       
            /* SOAP: MailSrv */       
            try { $client = Warecorp::getMailServerClient(); }
            catch ( Exception $e ) { $client = null; }   
            
            if ( $client ) {                
                $recipient = new Warecorp_SOAP_Type_Recipient();
                $recipient->setEmail( $objRecipient->getEmail() );
                $recipient->setName( $objRecipient->getFirstname().' '.$objRecipient->getLastname() );
                $recipient->setLocale( null );
                $recipient->addParam('CCFID', Warecorp::getCCFID($objRecipient));
                $recipient->addParam( 'recipient_full_name', $objRecipient->getFirstname().' '.$objRecipient->getLastname() );
                $recipient->addParam( 'SITE_LINK_UNSUBSCRIBE', $objRecipient->getUserPath('settings') );
                $msrvRecipients->addRecipient($recipient);
                
                try { 
                    $campaignUID = $client->createCampaign();                        
                    $request = $client->setSender($campaignUID, $this->getGroupEmail(), $this->getName());
                    //$request = $client->setSender($campaignUID, 'messages-noreply@bounce.'.DOMAIN_FOR_EMAIL, 'Group Memberships');
                    $request = $client->setTemplate($campaignUID, $keyTemplate, HTTP_CONTEXT); /* CREATE_NEW_GROUP_FAMILY_THANK or CREATE_NEW_GROUP_THANK */
                    
                    /* add headers */
                    $client->addHeader($campaignUID, 'Sender', '"'.$this->getName().'" <'.$this->getGroupEmail().'>');
                    $client->addHeader($campaignUID, 'Reply-To', '"'.$this->getName().'" <'.$this->getGroupEmail().'>');
                    //$client->addHeader($campaignUID, 'Sender', '"Group Memberships" <messages-noreply@bounce.'.DOMAIN_FOR_EMAIL.'>');
                    //$client->addHeader($campaignUID, 'Reply-To', '"Group Memberships" <messages-noreply@bounce.'.DOMAIN_FOR_EMAIL.'>');
                    
                    /* add params */
                    $params = new Warecorp_SOAP_Type_Params();
                    $params->loadDefaultCampaignParams();
                    $params->addParam( 'group_name', $this->getName() );
                    $params->addParam( 'url_summary', $this->getGroupPath('summary') );
                    $params->addParam( 'url_settings', $this->getGroupPath('settings') );
                    $params->addParam( 'url_members', $this->getGroupPath('members') );
                    $params->addParam( 'url_membersAddStep1', $this->getGroupPath('membersAddStep1') );
                    $params->addParam( 'url_brandgallery', $this->getGroupPath('brandgallery') );
                    $params->addParam( 'url_hierarchy', $this->getGroupPath('hierarchy') );
                    $request = $client->addParams($campaignUID, $params);
                                       
                    $request = $client->addRecipients($campaignUID, $msrvRecipients);
                    $request = $client->startCampaign($campaignUID);
                    
                    $msrvSended = true;
                } catch ( Exception $e ) { $msrvSended = false; }
            }
        }

        /**
         * TODO : MAILSRV_REMOVE : Remove it when transfer to mailsrv will be done
         * if emails haven't been sended by SOAP: MailSrv, send it 
         */
        if ( !$msrvSended ) {
            /* Send email to host - CREATE_NEW_GROUP_FAMILY_THANK */
            $mail = new Warecorp_Mail_Template('template_key', $keyTemplate);
            $mail->setSender($this);
            $mail->setHeader('Sender', '"'.htmlspecialchars($this->getName()).'" <'.$this->getGroupEmail().'>');
            $mail->setHeader('Reply-To', '"'.htmlspecialchars($this->getName()).'" <'.$this->getGroupEmail().'>');
            $mail->addRecipient($objRecipient);
            $mail->addParam('Group', $this);
            $mail->sendToPMB(false);
            $mail->send();
        }
    }

    public function sendFamilyInvitation( $objSender, $lstGroupRecipients, $Subject, $Message )
    {        
        /* SOAP: MailSrv */
        $msrvRecipients = new Warecorp_SOAP_Type_Recipients();
        $pmbRecipients = array();
        $msrvSended = false;
                
        /* SOAP: MailSrv */
        if ( Warecorp::isMailServerUsed() && Warecorp::isMailServerTemplateRegistered( 'FAMILY_INVITATION_TO_GROUP_FROM_HOST' ) ) {
                       
            /* SOAP: MailSrv */       
            try { $client = Warecorp::getMailServerClient(); }
            catch ( Exception $e ) { $client = null; }   
            
            if ( $client ) {   

                if ( sizeof($lstGroupRecipients) != 0 ) {
                    foreach ( $lstGroupRecipients as $group ) {
                        $objRecipient = $group->getHost();
                        $recipient = new Warecorp_SOAP_Type_Recipient();
                        $recipient->setEmail( $objRecipient->getEmail() );
                        $recipient->setName( $objRecipient->getFirstname().' '.$objRecipient->getLastname() );
                        $recipient->setLocale( null );
                        $recipient->addParam('CCFID', Warecorp::getCCFID($objRecipient));
                        $recipient->addParam( 'recipient_full_name', $objRecipient->getFirstname().' '.$objRecipient->getLastname() );
                        $recipient->addParam( 'SITE_LINK_UNSUBSCRIBE', $objRecipient->getUserPath('settings') );
                        $recipient->addParam( 'invited_group_name', $group->getName() );
                        $recipient->addParam( 'url_inviteconfirm', $this->getGroupPath('inviteconfirm/id/'.$group->getId()) );                        
                        $msrvRecipients->addRecipient($recipient);
                        $pmbRecipients[] = $objRecipient->getId() ? $objRecipient->getId() : $objRecipient->getEmail();
                    }
                }
                
                try { 
                    $campaignUID = $client->createCampaign();                        
                    $request = $client->setSender($campaignUID, 'groupfamilies@'.DOMAIN_FOR_EMAIL, SITE_NAME_AS_STRING.' Group Family Memberships');
                    //$request = $client->setSender($campaignUID, 'messages-noreply@bounce.'.DOMAIN_FOR_EMAIL, 'Group Memberships');
                    $request = $client->setTemplate($campaignUID, 'FAMILY_INVITATION_TO_GROUP_FROM_HOST', HTTP_CONTEXT); /* FAMILY_INVITATION_TO_GROUP_FROM_HOST */
                                        
                    /* add params */
                    $params = new Warecorp_SOAP_Type_Params();
                    $params->loadDefaultCampaignParams();
                    $params->addParam( 'group_host_login', $this->getHost()->getLogin() );
                    $params->addParam( 'group_name', $this->getName() );
                    $params->addParam( 'group_description_plain', $this->getDescription() );
                    $params->addParam( 'group_description_html', nl2br(htmlspecialchars($this->getDescription())) );
                    $params->addParam( 'groups_count', $this->getGroups()->getCount() );
                    $params->addParam( 'members_count', $this->getMembers()->getCount() );
                    $params->addParam( 'url_group_summary', $this->getGroupPath('summary') );
                    $params->addParam( 'message_subject', $Subject );
                    $params->addParam( 'message_body_plain', $Message );
                    $params->addParam( 'message_body_html', nl2br(htmlspecialchars($Message)) );
                    $request = $client->addParams($campaignUID, $params);
                                       
                    /* add callback to mailsrv campaign to sent PMB message */
                    $objCallback = new Warecorp_SOAP_Type_Callback();
                    $objCallback->setType( Warecorp_SOAP_Type_Callback::TYPE_RECIPIENTS );
                    $objCallback->setWsdl( BASE_URL.'/wsdl.php?t=service' );
                    $objCallback->setAction( 'callbackAddPMBMessage' );
                    $callbackUID = $client->addCallback($campaignUID, $objCallback);
        
                    $pmbRecipients = ( null === $pmbRecipients || !is_array($pmbRecipients) ) ? array() : $pmbRecipients;
                    $client->addCallbackParam($callbackUID, 'mailsrv:pmb_subject', null);
                    $client->addCallbackParam($callbackUID, 'mailsrv:pmb_message', null);
                    $client->addCallbackParam($callbackUID, 'sender_id', $objSender->getId());
                    $client->addCallbackParam($callbackUID, 'sender_type', ($objSender instanceof Warecorp_User) ? 'user' : 'group');
                    $client->addCallbackParam($callbackUID, 'recipients', join(';', $pmbRecipients) );
                    unset( $pmbRecipients );
                                        
                    $request = $client->addRecipients($campaignUID, $msrvRecipients);
                    $request = $client->startCampaign($campaignUID);
                    
                    $msrvSended = true;
                } catch ( Exception $e ) { $msrvSended = false; }
            }
        }

        /**
         * TODO : MAILSRV_REMOVE : Remove it when transfer to mailsrv will be done
         * if emails haven't been sended by SOAP: MailSrv, send it 
         */
        if ( !$msrvSended ) {
            $mail = new Warecorp_Mail_Template('template_key', 'FAMILY_INVITATION_TO_GROUP_FROM_HOST');
            $mail->setSender($objSender);
            $mail->addParam('Group', $this);
            $mail->addParam('Host', $this->getHost());
            $mail->addParam('MessageSubject', $Subject);
            $mail->addParam('MessageBody', $Message);
            $mail->sendToPMB(true);
            foreach($lstGroupRecipients as $recipient) {
                $mail->clearRecipients();
                $mail->addRecipient($recipient->getHost());
                $mail->addParam('InvitedGroup', $recipient);
                $mail->send();
            }
        }
    }
    
    public function sendGroupJoinNewMember( $objSender, $objNewMember, $Subject, $Message, $SendAndJoin )
    {      
        if ( $this->getJoinNotifyMode() != 1 ) return true;
              
        /* SOAP: MailSrv */
        $msrvRecipients = new Warecorp_SOAP_Type_Recipients();
        $pmbRecipients = array();
        $msrvSended = false;
                
        /* SOAP: MailSrv */
        if ( Warecorp::isMailServerUsed() && Warecorp::isMailServerTemplateRegistered( 'GROUP_JOIN_NEW_MEMBER_IS_JOINED' ) ) {
                       
            /* SOAP: MailSrv */       
            try { $client = Warecorp::getMailServerClient(); }
            catch ( Exception $e ) { $client = null; }   
            
            if ( $client ) {   

                $objRecipient = $this->getHost();
                $recipient = new Warecorp_SOAP_Type_Recipient();
                $recipient->setEmail( $objRecipient->getEmail() );
                $recipient->setName( $objRecipient->getFirstname().' '.$objRecipient->getLastname() );
                $recipient->setLocale( null );
                $recipient->addParam('CCFID', Warecorp::getCCFID($objRecipient));
                $recipient->addParam( 'recipient_full_name', $objRecipient->getFirstname().' '.$objRecipient->getLastname() );
                $recipient->addParam( 'SITE_LINK_UNSUBSCRIBE', $objRecipient->getUserPath('settings') );
                $msrvRecipients->addRecipient($recipient);
                $pmbRecipients[] = $objRecipient->getId() ? $objRecipient->getId() : $objRecipient->getEmail();
                
                try { 
                    $campaignUID = $client->createCampaign();                        
                    $request = $client->setSender($campaignUID, $this->getGroupEmail(), $this->getName());
                    //$request = $client->setSender($campaignUID, 'messages-noreply@bounce.'.DOMAIN_FOR_EMAIL, 'Group Memberships');
                    $request = $client->setTemplate($campaignUID, 'GROUP_JOIN_NEW_MEMBER_IS_JOINED', HTTP_CONTEXT); /* GROUP_JOIN_NEW_MEMBER_IS_JOINED */
                                        
                    /* add params */
                    $params = new Warecorp_SOAP_Type_Params();
                    $params->loadDefaultCampaignParams();
                    $params->addParam( 'group_name', $this->getName() );
                    $params->addParam( 'new_member_login', $objNewMember->getLogin() );
                    $params->addParam( 'new_member_city', $objNewMember->getCity()->name );
                    $params->addParam( 'new_member_country', $objNewMember->getCountry()->name );
                    $params->addParam( 'url_new_member_profile', $objNewMember->getUserPath('profile') );
                    $params->addParam( 'message_subject', $Subject );
                    $params->addParam( 'message_content_plain', $Message );
                    $params->addParam( 'message_content_html', nl2br(htmlspecialchars($Message)) );
                    $request = $client->addParams($campaignUID, $params);

                    /* add headers */
                    $client->addHeader($campaignUID, 'Sender', '"'.$this->getName().'" <'.$this->getGroupEmail().'>');
                    $client->addHeader($campaignUID, 'Reply-To', '"'.$this->getName().'" <'.$this->getGroupEmail().'>');
                    //$client->addHeader($campaignUID, 'Sender', '"Group Memberships" <messages-noreply@bounce.'.DOMAIN_FOR_EMAIL.'>');
                    //$client->addHeader($campaignUID, 'Reply-To', '"Group Memberships" <messages-noreply@bounce.'.DOMAIN_FOR_EMAIL.'>');
                                        
                    /* add callback to mailsrv campaign to sent PMB message */
                    $objCallback = new Warecorp_SOAP_Type_Callback();
                    $objCallback->setType( Warecorp_SOAP_Type_Callback::TYPE_RECIPIENTS );
                    $objCallback->setWsdl( BASE_URL.'/wsdl.php?t=service' );
                    $objCallback->setAction( 'callbackAddPMBMessage' );
                    $callbackUID = $client->addCallback($campaignUID, $objCallback);
        
                    $pmbRecipients = ( null === $pmbRecipients || !is_array($pmbRecipients) ) ? array() : $pmbRecipients;
                    $client->addCallbackParam($callbackUID, 'mailsrv:pmb_subject', null);
                    $client->addCallbackParam($callbackUID, 'mailsrv:pmb_message', null);
                    $client->addCallbackParam($callbackUID, 'sender_id', $objSender->getId());
                    $client->addCallbackParam($callbackUID, 'sender_type', ($objSender instanceof Warecorp_User) ? 'user' : 'group');
                    $client->addCallbackParam($callbackUID, 'recipients', join(';', $pmbRecipients) );
                    unset( $pmbRecipients );
                                        
                    $request = $client->addRecipients($campaignUID, $msrvRecipients);
                    $request = $client->startCampaign($campaignUID);
                    
                    $msrvSended = true;
                } catch ( Exception $e ) { $msrvSended = false; }
            }
        }

        /**
         * TODO : MAILSRV_REMOVE : Remove it when transfer to mailsrv will be done
         * if emails haven't been sended by SOAP: MailSrv, send it 
         */
        if ( !$msrvSended ) {
            $mail = new Warecorp_Mail_Template('template_key', 'GROUP_JOIN_NEW_MEMBER_IS_JOINED');
            $mail->setHeader('Sender', '"'.htmlspecialchars($this->getName()).'" <'.$this->getGroupEmail().'>');
            $mail->setHeader('Reply-To', '"'.htmlspecialchars($this->getName()).'" <'.$this->getGroupEmail().'>');
            $mail->setSender($objSender);
            $mail->addRecipient($this->getHost());
            $mail->addParam('Group', $this);
            if ( $SendAndJoin ) {
              $mail->addParam('MessageSubject', $Subject);
              $mail->addParam('MessageBoby', $Message);
            }
            $mail->addParam('NewMember', $objNewMember);
            $mail->sendToPMB(true);
            $mail->send();
        }
    }
    
    public function sendGroupJoinRequest( $objSender, $objNewMember, $Subject, $Message )
    {        
        /* SOAP: MailSrv */
        $msrvRecipients = new Warecorp_SOAP_Type_Recipients();
        $pmbRecipients = array();
        $msrvSended = false;
                
        /* SOAP: MailSrv */
        if ( Warecorp::isMailServerUsed() && Warecorp::isMailServerTemplateRegistered( 'GROUP_JOIN_REQUEST_APPROVE_USER_TO_HOST' ) ) {
                       
            /* SOAP: MailSrv */       
            try { $client = Warecorp::getMailServerClient(); }
            catch ( Exception $e ) { $client = null; }   
            
            if ( $client ) {   

                $objRecipient = $this->getHost();
                $recipient = new Warecorp_SOAP_Type_Recipient();
                $recipient->setEmail( $objRecipient->getEmail() );
                $recipient->setName( $objRecipient->getFirstname().' '.$objRecipient->getLastname() );
                $recipient->setLocale( null );
                $recipient->addParam('CCFID', Warecorp::getCCFID($objRecipient));
                $recipient->addParam( 'recipient_full_name', $objRecipient->getFirstname().' '.$objRecipient->getLastname() );
                $recipient->addParam( 'SITE_LINK_UNSUBSCRIBE', $objRecipient->getUserPath('settings') );
                $msrvRecipients->addRecipient($recipient);
                $pmbRecipients[] = $objRecipient->getId() ? $objRecipient->getId() : $objRecipient->getEmail();
                
                try { 
                    $campaignUID = $client->createCampaign();                        
                    $request = $client->setSender($campaignUID, $this->getGroupEmail(), $this->getName());
                    //$request = $client->setSender($campaignUID, 'messages-noreply@bounce.'.DOMAIN_FOR_EMAIL, 'Group Memberships');
                    $request = $client->setTemplate($campaignUID, 'GROUP_JOIN_REQUEST_APPROVE_USER_TO_HOST', HTTP_CONTEXT); /* GROUP_JOIN_REQUEST_APPROVE_USER_TO_HOST */
                                        
                    /* add params */
                    $params = new Warecorp_SOAP_Type_Params();
                    $params->loadDefaultCampaignParams();
                    $params->addParam( 'group_name', $this->getName() );
                    $params->addParam( 'url_group_pending_members', $this->getGroupPath('members/mode/pending') );
                    $params->addParam( 'message_subject', $Subject );
                    $params->addParam( 'message_content_plain', $Message );
                    $params->addParam( 'message_content_html', nl2br(htmlspecialchars($Message)) );
                    $params->addParam( 'new_member_full_name', $objNewMember->getFirstname().' '.$objNewMember->getLastname() );
                    $params->addParam( 'new_member_email', $objNewMember->getEmail() );
                    $params->addParam( 'new_member_login', $objNewMember->getLogin() );
                    $request = $client->addParams($campaignUID, $params);

                    /* add headers */
                    $client->addHeader($campaignUID, 'Sender', '"'.$this->getName().'" <'.$this->getGroupEmail().'>');
                    $client->addHeader($campaignUID, 'Reply-To', '"'.$this->getName().'" <'.$this->getGroupEmail().'>');
                    //$client->addHeader($campaignUID, 'Sender', '"Group Memberships" <messages-noreply@bounce.'.DOMAIN_FOR_EMAIL.'>');
                    //$client->addHeader($campaignUID, 'Reply-To', '"Group Memberships" <messages-noreply@bounce.'.DOMAIN_FOR_EMAIL.'>');
                                        
                    /* add callback to mailsrv campaign to sent PMB message */
                    $objCallback = new Warecorp_SOAP_Type_Callback();
                    $objCallback->setType( Warecorp_SOAP_Type_Callback::TYPE_RECIPIENTS );
                    $objCallback->setWsdl( BASE_URL.'/wsdl.php?t=service' );
                    $objCallback->setAction( 'callbackAddPMBMessage' );
                    $callbackUID = $client->addCallback($campaignUID, $objCallback);
        
                    $pmbRecipients = ( null === $pmbRecipients || !is_array($pmbRecipients) ) ? array() : $pmbRecipients;
                    $client->addCallbackParam($callbackUID, 'mailsrv:pmb_subject', null);
                    $client->addCallbackParam($callbackUID, 'mailsrv:pmb_message', null);
                    $client->addCallbackParam($callbackUID, 'sender_id', $objSender->getId());
                    $client->addCallbackParam($callbackUID, 'sender_type', ($objSender instanceof Warecorp_User) ? 'user' : 'group');
                    $client->addCallbackParam($callbackUID, 'recipients', join(';', $pmbRecipients) );
                    
                    /**
                     * add callback param as onSend method
                     * if 'onSend_addJoinRequest' parameter is present sooap method add request to this message 
                     * @author Artem Sukharev
                     */
                    $onSend_addJoinRequest = array();
                    $onSend_addJoinRequest['group'] = $this->getId();
                    $onSend_addJoinRequest['user'] = $objNewMember->getId();
                    $client->addCallbackParam($callbackUID, 'onSend_addJoinRequest', Zend_Json::encode($onSend_addJoinRequest) );
                    
                    unset( $pmbRecipients );
                    
                    $request = $client->addRecipients($campaignUID, $msrvRecipients);
                    $request = $client->startCampaign($campaignUID);
                    
                    $msrvSended = true;
                } catch ( Exception $e ) { $msrvSended = false; }
            }
        }

        /**
         * TODO : MAILSRV_REMOVE : Remove it when transfer to mailsrv will be done
         * if emails haven't been sended by SOAP: MailSrv, send it 
         */
        if ( !$msrvSended ) {
            //  Send message to host about new member
            $mail = new Warecorp_Mail_Template('template_key', 'GROUP_JOIN_REQUEST_APPROVE_USER_TO_HOST');
            $mail->setHeader('Sender', '"'.htmlspecialchars($this->getName()).'" <'.$this->getGroupEmail().'>');
            $mail->setHeader('Reply-To', '"'.htmlspecialchars($this->getName()).'" <'.$this->getGroupEmail().'>');
            $mail->setSender($objSender);
            $mail->addRecipient($this->getHost());
            $mail->addParam('Group', $this);
            $mail->addParam('NewMember', $objNewMember);
            $mail->addParam('MessageSubject', $Subject);
            $mail->addParam('MessageBody', $Message);
            if ($this->getJoinNotifyMode() == 0) $mail->sendToEmail(false);
            $mail->sendToPMB(true);
            $mail->setCallBackData(array(
                   'controller' => 'Groups',
                   'action'     => 'addJoinRequestAction',
                   'params'     => array('group' => $this, 'user' => $objNewMember)
            ));
            $mail->send();
        }
    }
    
    public function sendFamilyJoinNewGroup( $objSender, $lstGroupRecipients, $Subject = '', $Message = '' )
    {        
        /* SOAP: MailSrv */
        $msrvRecipients = new Warecorp_SOAP_Type_Recipients();
        $pmbRecipients = array();
        $msrvSended = false;
                
        /* SOAP: MailSrv */
        if ( Warecorp::isMailServerUsed() && Warecorp::isMailServerTemplateRegistered( 'FAMILY_JOIN_NEW_GROUPS_IS_JOINED' ) ) {
                       
            /* SOAP: MailSrv */       
            try { $client = Warecorp::getMailServerClient(); }
            catch ( Exception $e ) { $client = null; }   
            
            if ( $client ) {   

                $objRecipient = $this->getHost();
                $recipient = new Warecorp_SOAP_Type_Recipient();
                $recipient->setEmail( $objRecipient->getEmail() );
                $recipient->setName( $objRecipient->getFirstname().' '.$objRecipient->getLastname() );
                $recipient->setLocale( null );
                $recipient->addParam('CCFID', Warecorp::getCCFID($objRecipient));
                $recipient->addParam( 'recipient_full_name', $objRecipient->getFirstname().' '.$objRecipient->getLastname() );
                $recipient->addParam( 'SITE_LINK_UNSUBSCRIBE', $objRecipient->getUserPath('settings') );
                $msrvRecipients->addRecipient($recipient);
                $pmbRecipients[] = $objRecipient->getId() ? $objRecipient->getId() : $objRecipient->getEmail();
                
                $lst_group_details_plain = array();
                $lst_group_details_html = array();
                if ( sizeof($lstGroupRecipients) != 0 ) {
                    foreach ( $lstGroupRecipients as $group ) {
                        $lst_group_details_plain[] = 
                            'Group Name: '.$group->getName()."\n".
                            'Host: '.$group->getHost()->getLogin()."\n".
                            'Group Home Page: '.$group->getGroupPath('summary')."\n";
                        $lst_group_details_html[] = 
                            'Group Name: '.$group->getName()." <br />".
                            'Host: '.$group->getHost()->getLogin()." <br />".
                            'Group Home Page: <a href="'.$group->getGroupPath('summary').'">'.$group->getGroupPath('summary')."</a> <br />";
                    }
                }
                $lst_group_details_plain = join("\n", $lst_group_details_plain);
                $lst_group_details_html = join("<br />", $lst_group_details_html);
                
                try { 
                    $campaignUID = $client->createCampaign();                        
                    $request = $client->setSender($campaignUID, $this->getGroupEmail(), $this->getName());
                    //$request = $client->setSender($campaignUID, 'messages-noreply@bounce.'.DOMAIN_FOR_EMAIL, 'Group Memberships');
                    $request = $client->setTemplate($campaignUID, 'FAMILY_JOIN_NEW_GROUPS_IS_JOINED', HTTP_CONTEXT); /* FAMILY_JOIN_NEW_GROUPS_IS_JOINED */
                                        
                    /* add params */
                    $params = new Warecorp_SOAP_Type_Params();
                    $params->loadDefaultCampaignParams();
                    $params->addParam( 'group_name', $this->getName() );
                    $params->addParam( 'groups_count', $this->getGroups()->getCount() );
                    $params->addParam( 'members_count', $this->getMembers()->getCount() );
                    $params->addParam( 'lst_group_details_plain', $lst_group_details_plain );
                    $params->addParam( 'lst_group_details_html', $lst_group_details_html );
                    $params->addParam( 'message_subject', $Subject );
                    $params->addParam( 'message_content_plain', $Message );
                    $params->addParam( 'message_content_html', nl2br(htmlspecialchars($Message)) );
                    $request = $client->addParams($campaignUID, $params);

                    /* add headers */
                    $client->addHeader($campaignUID, 'Sender', '"'.$this->getName().'" <'.$this->getGroupEmail().'>');
                    $client->addHeader($campaignUID, 'Reply-To', '"'.$this->getName().'" <'.$this->getGroupEmail().'>');
                    //$client->addHeader($campaignUID, 'Sender', '"Group Memberships" <messages-noreply@bounce.'.DOMAIN_FOR_EMAIL.'>');
                    //$client->addHeader($campaignUID, 'Reply-To', '"Group Memberships" <messages-noreply@bounce.'.DOMAIN_FOR_EMAIL.'>');
                                        
                    /* add callback to mailsrv campaign to sent PMB message */
                    $objCallback = new Warecorp_SOAP_Type_Callback();
                    $objCallback->setType( Warecorp_SOAP_Type_Callback::TYPE_RECIPIENTS );
                    $objCallback->setWsdl( BASE_URL.'/wsdl.php?t=service' );
                    $objCallback->setAction( 'callbackAddPMBMessage' );
                    $callbackUID = $client->addCallback($campaignUID, $objCallback);
        
                    $pmbRecipients = ( null === $pmbRecipients || !is_array($pmbRecipients) ) ? array() : $pmbRecipients;
                    $client->addCallbackParam($callbackUID, 'mailsrv:pmb_subject', null);
                    $client->addCallbackParam($callbackUID, 'mailsrv:pmb_message', null);
                    $client->addCallbackParam($callbackUID, 'sender_id', $objSender->getId());
                    $client->addCallbackParam($callbackUID, 'sender_type', ($objSender instanceof Warecorp_User) ? 'user' : 'group');
                    $client->addCallbackParam($callbackUID, 'recipients', join(';', $pmbRecipients) );
                    unset( $pmbRecipients );
                                        
                    $request = $client->addRecipients($campaignUID, $msrvRecipients);
                    $request = $client->startCampaign($campaignUID);
                    
                    $msrvSended = true;
                } catch ( Exception $e ) { $msrvSended = false; }
            }
        }

        /**
         * TODO : MAILSRV_REMOVE : Remove it when transfer to mailsrv will be done
         * if emails haven't been sended by SOAP: MailSrv, send it 
         */
        if ( !$msrvSended ) {
            //  Send message to host about new member
            $mail = new Warecorp_Mail_Template('template_key', 'FAMILY_JOIN_NEW_GROUPS_IS_JOINED');
            $mail->setHeader('Sender', '"'.htmlspecialchars($this->getName()).'" <'.$this->getGroupEmail().'>');
            $mail->setHeader('Reply-To', '"'.htmlspecialchars($this->getName()).'" <'.$this->getGroupEmail().'>');
            $mail->setSender($this);
            $mail->addRecipient($this->getHost());
            $mail->addParam('Group', $this);

            if ( $Subject && $Message ) {
                $mail->addParam('MessageSubject', $Subject);
                $mail->addParam('MessageBody', $Message);
                $mail->addParam('addMessage',1);
            } else { $mail->addParam('addMessage',0); }
            
            $mail->addParam('Host', $this->getHost());
            $mail->addParam('Groups', $lstGroupRecipients);
            $mail->sendToPMB(true);
            $mail->send();
        }
    }
    
    public function sendFamilyJoinRequest( $objSender, $lstUserRecipients, $lstGroupRecipients, $Subject = '', $Message = '' )
    {        
        /* SOAP: MailSrv */
        $msrvRecipients = new Warecorp_SOAP_Type_Recipients();
        $pmbRecipients = array();
        $msrvSended = false;
                
        /* SOAP: MailSrv */
        if ( Warecorp::isMailServerUsed() && Warecorp::isMailServerTemplateRegistered( 'FAMILY_JOIN_REQUEST_APPROVE_GROUP_TO_HOST' ) ) {
                       
            /* SOAP: MailSrv */       
            try { $client = Warecorp::getMailServerClient(); }
            catch ( Exception $e ) { $client = null; }   
            
            if ( $client ) {   

                if ( sizeof($lstUserRecipients) != 0 ) {
                    foreach ( $lstUserRecipients as $objRecipient ) {
                        $recipient = new Warecorp_SOAP_Type_Recipient();
                        $recipient->setEmail( $objRecipient->getEmail() );
                        $recipient->setName( $objRecipient->getFirstname().' '.$objRecipient->getLastname() );
                        $recipient->setLocale( null );
                        $recipient->addParam('CCFID', Warecorp::getCCFID($objRecipient));
                        $recipient->addParam( 'recipient_full_name', $objRecipient->getFirstname().' '.$objRecipient->getLastname() );
                        $recipient->addParam( 'SITE_LINK_UNSUBSCRIBE', $objRecipient->getUserPath('settings') );
                        $msrvRecipients->addRecipient($recipient);
                        $pmbRecipients[] = $objRecipient->getId() ? $objRecipient->getId() : $objRecipient->getEmail();
                    }
                }
                
                $lstGroupRecipientIds = array();
                $lst_group_details_plain = array();
                $lst_group_details_html = array();
                if ( sizeof($lstGroupRecipients) != 0 ) {
                    foreach ( $lstGroupRecipients as $group ) {
                        $lstGroupRecipientIds[] = $group->getId();
                        $lst_group_details_plain[] = 
                            'Group Name: '.$group->getName()."\n".
                            'Host: '.$group->getHost()->getLogin()."\n".
                            'Group Home Page: '.$group->getGroupPath('summary')."\n";
                        $lst_group_details_html[] = 
                            'Group Name: '.$group->getName()." <br />".
                            'Host: '.$group->getHost()->getLogin()." <br />".
                            'Group Home Page: <a href="'.$group->getGroupPath('summary').'">'.$group->getGroupPath('summary')."</a> <br />";
                    }
                }
                $lst_group_details_plain = join("\n", $lst_group_details_plain);
                $lst_group_details_html = join("<br />", $lst_group_details_html);
                                
                $user_notes_plain = '';
                $user_notes_html = '';
                if ( $Subject && $Message ) {
                    $user_notes_plain = 'Notes from host'."\n".'Subject: '.$Subject."\n".$Message;
                    $user_notes_html = 'Notes from host'."<br />".'Subject: '.$Subject."<br />". nl2br(htmlspecialchars($Message));
                }
                
                try { 
                    $campaignUID = $client->createCampaign();                        
                    $request = $client->setSender($campaignUID, $this->getGroupEmail(), $this->getName());
                    //$request = $client->setSender($campaignUID, 'messages-noreply@bounce.'.DOMAIN_FOR_EMAIL, 'Group Memberships');
                    $request = $client->setTemplate($campaignUID, 'FAMILY_JOIN_REQUEST_APPROVE_GROUP_TO_HOST', HTTP_CONTEXT); /* FAMILY_JOIN_REQUEST_APPROVE_GROUP_TO_HOST */
                                        
                    /* add params */
                    $params = new Warecorp_SOAP_Type_Params();
                    $params->loadDefaultCampaignParams();
                    $params->addParam( 'group_name', $this->getName() );
                    $params->addParam( 'lst_group_details_plain', $lst_group_details_plain );
                    $params->addParam( 'lst_group_details_html', $lst_group_details_html );
                    $params->addParam( 'user_notes_plain', $user_notes_plain );
                    $params->addParam( 'user_notes_html', $user_notes_html );
                    $params->addParam( 'url_pending_members', $this->getGroupPath('members/mode/pending') );
                    $request = $client->addParams($campaignUID, $params);

                    /* add headers */
                    $client->addHeader($campaignUID, 'Sender', '"'.$this->getName().'" <'.$this->getGroupEmail().'>');
                    $client->addHeader($campaignUID, 'Reply-To', '"'.$this->getName().'" <'.$this->getGroupEmail().'>');
                    //$client->addHeader($campaignUID, 'Sender', '"Group Memberships" <messages-noreply@bounce.'.DOMAIN_FOR_EMAIL.'>');
                    //$client->addHeader($campaignUID, 'Reply-To', '"Group Memberships" <messages-noreply@bounce.'.DOMAIN_FOR_EMAIL.'>');
                                        
                    /* add callback to mailsrv campaign to sent PMB message */
                    $objCallback = new Warecorp_SOAP_Type_Callback();
                    $objCallback->setType( Warecorp_SOAP_Type_Callback::TYPE_RECIPIENTS );
                    $objCallback->setWsdl( BASE_URL.'/wsdl.php?t=service' );
                    $objCallback->setAction( 'callbackAddPMBMessage' );
                    $callbackUID = $client->addCallback($campaignUID, $objCallback);
        
                    $pmbRecipients = ( null === $pmbRecipients || !is_array($pmbRecipients) ) ? array() : $pmbRecipients;
                    $client->addCallbackParam($callbackUID, 'mailsrv:pmb_subject', null);
                    $client->addCallbackParam($callbackUID, 'mailsrv:pmb_message', null);
                    $client->addCallbackParam($callbackUID, 'sender_id', $objSender->getId());
                    $client->addCallbackParam($callbackUID, 'sender_type', ($objSender instanceof Warecorp_User) ? 'user' : 'group');
                    $client->addCallbackParam($callbackUID, 'recipients', join(';', $pmbRecipients) );
                    
                    /**
                     * add callback param as onSend method
                     * if 'onSend_addJoinFamilyRequest' parameter is present sooap method add request to this message 
                     * @author Artem Sukharev
                     */
                    $onSend_addJoinFamilyRequest = array();
                    $onSend_addJoinFamilyRequest['group'] = $this->getId();
                    $onSend_addJoinFamilyRequest['related_groups'] = $lstGroupRecipientIds;
                    $client->addCallbackParam($callbackUID, 'onSend_addJoinFamilyRequest', Zend_Json::encode($onSend_addJoinFamilyRequest) );
                    
                    unset( $pmbRecipients );
                    
                    $request = $client->addRecipients($campaignUID, $msrvRecipients);
                    $request = $client->startCampaign($campaignUID);
                    
                    $msrvSended = true;
                } catch ( Exception $e ) { $msrvSended = false; }
            }
        }

        /**
         * TODO : MAILSRV_REMOVE : Remove it when transfer to mailsrv will be done
         * if emails haven't been sended by SOAP: MailSrv, send it 
         */
        if ( !$msrvSended ) {
            /* Send message to host about new member */
            $mail = new Warecorp_Mail_Template('template_key', 'FAMILY_JOIN_REQUEST_APPROVE_GROUP_TO_HOST');
            $mail->setHeader('Sender', '"'.htmlspecialchars($this->getName()).'" <'.$this->getGroupEmail().'>');
            $mail->setHeader('Reply-To', '"'.htmlspecialchars($this->getName()).'" <'.$this->getGroupEmail().'>');
            $mail->setSender($objSender);
            /* to Family owner */

            if ( sizeof($lstUserRecipients) != 0 ) {
                foreach ( $lstUserRecipients as $rec ) $mail->addRecipient($rec);
            }
            
            $mail->addParam('Group', $this);
            $mail->addParam('Host', $this->getHost());
            $mail->addParam('Groups', $lstGroupRecipients);
            if ( $Subject && $Message ) {
                $mail->addParam('MessageSubject', $Subject);
                $mail->addParam('MessageBody', $Message);
                $mail->addParam('addMessage',1);
            } else {
                $mail->addParam('addMessage',0);
            }

            $mail->sendToPMB(true);
            $mail->setCallBackData(array(
                   'controller' => 'Groups',
                   'action'     => 'addJoinFamilyRequestAction',
                   'params'     => array('group' => $this, 'groups' => $lstGroupRecipients)
            ));
            $mail->send();
        }
    }
    
    public function sendInviteMembers( $objSender, $currUser, $arrEmails, $Subject, $Message  )
    {        
        /* SOAP: MailSrv */
        $msrvRecipients = new Warecorp_SOAP_Type_Recipients();
        $pmbRecipients = array();
        $msrvSended = false;
                
        /* SOAP: MailSrv */
        if ( Warecorp::isMailServerUsed() && Warecorp::isMailServerTemplateRegistered( 'GROUP_INVITATION_TO_MEMBER_FORM_HOST' ) ) {
                       
            /* SOAP: MailSrv */       
            try { $client = Warecorp::getMailServerClient(); }
            catch ( Exception $e ) { $client = null; }   
            
            if ( $client ) {   

                $lstRecipients = Warecorp_User::getUsersFromEmailsString( $arrEmails );
                if ( sizeof($lstRecipients) != 0 ) {
                    foreach ( $lstRecipients as $objRecipient ) {                        
                        if ( $objRecipient->getEmail() != $currUser->getEmail() ) {
                            $recipient = new Warecorp_SOAP_Type_Recipient();
                            $recipient->setEmail( $objRecipient->getEmail() );
                            $recipient->setName( $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : null );
                            $recipient->setLocale( null );
                            $recipient->addParam('CCFID', Warecorp::getCCFID($objRecipient));
                            $recipient->addParam( 'recipient_full_name', $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : '' );
                            $recipient->addParam( 'SITE_LINK_UNSUBSCRIBE', $objRecipient->getUserPath('settings') );
                            $msrvRecipients->addRecipient($recipient);
                            $pmbRecipients[] = $objRecipient->getId() ? $objRecipient->getId() : $objRecipient->getEmail();
                        }
                    }
                }
                
                try { 
                    $campaignUID = $client->createCampaign();                        
                    $request = $client->setSender($campaignUID, 
                        $objSender instanceof Warecorp_User ? $objSender->getEmail() : $objSender->getGroupEmail(), 
                        $objSender instanceof Warecorp_User ? $objSender->getFirstname().' '.$objSender->getLastname() : $objSender->getName());
                    //$request = $client->setSender($campaignUID, 'messages-noreply@bounce.'.DOMAIN_FOR_EMAIL, 'Group Memberships');
                    $request = $client->setTemplate($campaignUID, 'GROUP_INVITATION_TO_MEMBER_FORM_HOST', HTTP_CONTEXT); /* GROUP_INVITATION_TO_MEMBER_FORM_HOST */
                                        
                    /* add params */
                    $params = new Warecorp_SOAP_Type_Params();
                    $params->loadDefaultCampaignParams();
                    $params->addParam( 'group_name', $this->getName() );
                    $params->addParam( 'group_headline', $this->getHeadline() );
                    $params->addParam( 'group_description_plain', $this->getDescription() );
                    $params->addParam( 'group_description_html', nl2br(htmlspecialchars($this->getDescription())) );
                    $params->addParam( 'group_host_login', $this->getHost() ? $this->getHost()->getLogin() : 'Host' );
                    $params->addParam( 'url_joingroup', $this->getGroupPath('joingroup') );
                    $params->addParam( 'message_subject', $Subject );
                    $params->addParam( 'message_content_plain', $Message );
                    $params->addParam( 'message_content_html', nl2br(htmlspecialchars($Message)) );
                    $params->addParam( 'join_code', $this->getJoinMode() == 2 ? Warecorp::t('You can join using this code: %s', $this->getJoinCode()) : '' );
                    $request = $client->addParams($campaignUID, $params);

                    /* add headers */
                    //$client->addHeader($campaignUID, 'Sender', '"'.$this->getName().'" <'.$this->getGroupEmail().'>');
                    //$client->addHeader($campaignUID, 'Reply-To', '"'.$this->getName().'" <'.$this->getGroupEmail().'>');
                    //$client->addHeader($campaignUID, 'Sender', '"Group Memberships" <messages-noreply@bounce.'.DOMAIN_FOR_EMAIL.'>');
                    //$client->addHeader($campaignUID, 'Reply-To', '"Group Memberships" <messages-noreply@bounce.'.DOMAIN_FOR_EMAIL.'>');
                    
                    /* add callback to mailsrv campaign to sent PMB message */
                    $objCallback = new Warecorp_SOAP_Type_Callback();
                    $objCallback->setType( Warecorp_SOAP_Type_Callback::TYPE_RECIPIENTS );
                    $objCallback->setWsdl( BASE_URL.'/wsdl.php?t=service' );
                    $objCallback->setAction( 'callbackAddPMBMessage' );
                    $callbackUID = $client->addCallback($campaignUID, $objCallback);
        
                    $pmbRecipients = ( null === $pmbRecipients || !is_array($pmbRecipients) ) ? array() : $pmbRecipients;
                    $client->addCallbackParam($callbackUID, 'mailsrv:pmb_subject', null);
                    $client->addCallbackParam($callbackUID, 'mailsrv:pmb_message', null);
                    $client->addCallbackParam($callbackUID, 'sender_id', $objSender->getId());
                    $client->addCallbackParam($callbackUID, 'sender_type', ($objSender instanceof Warecorp_User) ? 'user' : 'group');
                    $client->addCallbackParam($callbackUID, 'recipients', join(';', $pmbRecipients) );
                    
                    unset( $pmbRecipients );
                    
                    $request = $client->addRecipients($campaignUID, $msrvRecipients);
                    $request = $client->startCampaign($campaignUID);
                    
                    $msrvSended = true;
                } catch ( Exception $e ) { $msrvSended = false; }
            }
        }

        /**
         * TODO : MAILSRV_REMOVE : Remove it when transfer to mailsrv will be done
         * if emails haven't been sended by SOAP: MailSrv, send it 
         */
        if ( !$msrvSended ) {
            //  Send message to member invitee list
            $mail = new Warecorp_Mail_Template('template_key', 'GROUP_INVITATION_TO_MEMBER_FORM_HOST');
            $mail->setSender( $objSender );
            $mail->addUserRecipientsFormString($arrEmails, array($currUser->getId()));
            $mail->addParam('Group', $this);
            $mail->addParam('code', ( $this->getJoinMode() == 2 ) ? $this->getJoinCode() : '');
            $mail->addParam('Subject', $Subject);
            $mail->addParam('Message', $Message);
            $mail->sendToPMB(true);
            $mail->send();
        }
    }

    public function sendMemberResignFromGroup( $objSender, $objMember  )
    {        
        /* SOAP: MailSrv */
        $msrvRecipients = new Warecorp_SOAP_Type_Recipients();
        $pmbRecipients = array();
        $msrvSended = false;
                
        /* SOAP: MailSrv */
        if ( Warecorp::isMailServerUsed() && Warecorp::isMailServerTemplateRegistered( 'RESIGN_MEMBER_FROM_GROUP_TO_HOST' ) ) {
                       
            /* SOAP: MailSrv */       
            try { $client = Warecorp::getMailServerClient(); }
            catch ( Exception $e ) { $client = null; }   
            
            if ( $client ) {   

                $objRecipient = $this->getHost();
                $recipient = new Warecorp_SOAP_Type_Recipient();
                $recipient->setEmail( $objRecipient->getEmail() );
                $recipient->setName( $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : null );
                $recipient->setLocale( null );
                $recipient->addParam('CCFID', Warecorp::getCCFID($objRecipient));
                $recipient->addParam( 'recipient_full_name', $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : '' );
                $recipient->addParam( 'SITE_LINK_UNSUBSCRIBE', $objRecipient->getUserPath('settings') );
                $msrvRecipients->addRecipient($recipient);
                $pmbRecipients[] = $objRecipient->getId() ? $objRecipient->getId() : $objRecipient->getEmail();
                
                try { 
                    $campaignUID = $client->createCampaign();                        
                    $request = $client->setSender($campaignUID, $this->getGroupEmail(), $this->getName());
                    //$request = $client->setSender($campaignUID, 'messages-noreply@bounce.'.DOMAIN_FOR_EMAIL, 'Group Memberships');
                    $request = $client->setTemplate($campaignUID, 'RESIGN_MEMBER_FROM_GROUP_TO_HOST', HTTP_CONTEXT); /* RESIGN_MEMBER_FROM_GROUP_TO_HOST */
                                        
                    /* add params */
                    $params = new Warecorp_SOAP_Type_Params();
                    $params->loadDefaultCampaignParams();
                    $params->addParam( 'group_name', $this->getName() );
                    $params->addParam( 'member_login', $objMember->getLogin() );
                    $request = $client->addParams($campaignUID, $params);

                    /* add headers */
                    $client->addHeader($campaignUID, 'Sender', '"'.$this->getName().'" <'.$this->getGroupEmail().'>');
                    $client->addHeader($campaignUID, 'Reply-To', '"'.$this->getName().'" <'.$this->getGroupEmail().'>');
                    //$client->addHeader($campaignUID, 'Sender', '"Group Memberships" <messages-noreply@bounce.'.DOMAIN_FOR_EMAIL.'>');
                    //$client->addHeader($campaignUID, 'Reply-To', '"Group Memberships" <messages-noreply@bounce.'.DOMAIN_FOR_EMAIL.'>');
                    
                    /* add callback to mailsrv campaign to sent PMB message */
                    $objCallback = new Warecorp_SOAP_Type_Callback();
                    $objCallback->setType( Warecorp_SOAP_Type_Callback::TYPE_RECIPIENTS );
                    $objCallback->setWsdl( BASE_URL.'/wsdl.php?t=service' );
                    $objCallback->setAction( 'callbackAddPMBMessage' );
                    $callbackUID = $client->addCallback($campaignUID, $objCallback);
        
                    $pmbRecipients = ( null === $pmbRecipients || !is_array($pmbRecipients) ) ? array() : $pmbRecipients;
                    $client->addCallbackParam($callbackUID, 'mailsrv:pmb_subject', null);
                    $client->addCallbackParam($callbackUID, 'mailsrv:pmb_message', null);
                    $client->addCallbackParam($callbackUID, 'sender_id', $objSender->getId());
                    $client->addCallbackParam($callbackUID, 'sender_type', ($objSender instanceof Warecorp_User) ? 'user' : 'group');
                    $client->addCallbackParam($callbackUID, 'recipients', join(';', $pmbRecipients) );
                    
                    unset( $pmbRecipients );
                    
                    $request = $client->addRecipients($campaignUID, $msrvRecipients);
                    $request = $client->startCampaign($campaignUID);
                    
                    $msrvSended = true;
                } catch ( Exception $e ) { $msrvSended = false; }
            }
        }

        /**
         * TODO : MAILSRV_REMOVE : Remove it when transfer to mailsrv will be done
         * if emails haven't been sended by SOAP: MailSrv, send it 
         */
        if ( !$msrvSended ) {
            $mail = new Warecorp_Mail_Template('template_key', 'RESIGN_MEMBER_FROM_GROUP_TO_HOST');
            $mail->setSender($objSender);
            $mail->addRecipient($this->getHost());
            $mail->addParam('Group', $this);
            $mail->addParam('Member', $objMember);
            $mail->send();
        }
    }
    
    public function sendResignRequestNewHost( $objSender, $objRecipient, $AccessCode, $Subject, $Message  )
    {        
        /* SOAP: MailSrv */
        $msrvRecipients = new Warecorp_SOAP_Type_Recipients();
        $pmbRecipients = array();
        $msrvSended = false;
                
        /* SOAP: MailSrv */
        if ( Warecorp::isMailServerUsed() && Warecorp::isMailServerTemplateRegistered( 'RESIGN_REQUEST_NEW_HOST' ) ) {
                       
            /* SOAP: MailSrv */       
            try { $client = Warecorp::getMailServerClient(); }
            catch ( Exception $e ) { $client = null; }   
            
            if ( $client ) {   

                $recipient = new Warecorp_SOAP_Type_Recipient();
                $recipient->setEmail( $objRecipient->getEmail() );
                $recipient->setName( $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : null );
                $recipient->setLocale( null );
                $recipient->addParam('CCFID', Warecorp::getCCFID($objRecipient));
                $recipient->addParam( 'recipient_full_name', $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : '' );
                $recipient->addParam( 'SITE_LINK_UNSUBSCRIBE', $objRecipient->getUserPath('settings') );
                $msrvRecipients->addRecipient($recipient);
                $pmbRecipients[] = $objRecipient->getId() ? $objRecipient->getId() : $objRecipient->getEmail();
                
                try { 
                    $campaignUID = $client->createCampaign();                        
                    $request = $client->setSender($campaignUID, $this->getGroupEmail(), $this->getName());
                    //$request = $client->setSender($campaignUID, 'messages-noreply@bounce.'.DOMAIN_FOR_EMAIL, 'Group Memberships');
                    $request = $client->setTemplate($campaignUID, 'RESIGN_REQUEST_NEW_HOST', HTTP_CONTEXT); /* RESIGN_REQUEST_NEW_HOST */
                                        
                    /* add params */
                    $params = new Warecorp_SOAP_Type_Params();
                    $params->loadDefaultCampaignParams();
                    $params->addParam( 'group_name', $this->getName() );
                    $params->addParam( 'group_host_login', $this->getHost()->getLogin() );
                    $params->addParam( 'message_subject', $Subject );
                    $params->addParam( 'message_content', $Message );
                    $params->addParam( 'message_content_plain', $Message );
                    $params->addParam( 'message_content_html', nl2br(htmlspecialchars($Message)) );
                    $params->addParam( 'url_setnewhost_with_code', $this->getGroupPath('setnewhost/access_code/'.$AccessCode) );
                    $request = $client->addParams($campaignUID, $params);

                    /* add headers */
                    $client->addHeader($campaignUID, 'Sender', '"'.$this->getName().'" <'.$this->getGroupEmail().'>');
                    $client->addHeader($campaignUID, 'Reply-To', '"'.$this->getName().'" <'.$this->getGroupEmail().'>');
                    //$client->addHeader($campaignUID, 'Sender', '"Group Memberships" <messages-noreply@bounce.'.DOMAIN_FOR_EMAIL.'>');
                    //$client->addHeader($campaignUID, 'Reply-To', '"Group Memberships" <messages-noreply@bounce.'.DOMAIN_FOR_EMAIL.'>');
                    
                    /* add callback to mailsrv campaign to sent PMB message */
                    $objCallback = new Warecorp_SOAP_Type_Callback();
                    $objCallback->setType( Warecorp_SOAP_Type_Callback::TYPE_RECIPIENTS );
                    $objCallback->setWsdl( BASE_URL.'/wsdl.php?t=service' );
                    $objCallback->setAction( 'callbackAddPMBMessage' );
                    $callbackUID = $client->addCallback($campaignUID, $objCallback);
        
                    $pmbRecipients = ( null === $pmbRecipients || !is_array($pmbRecipients) ) ? array() : $pmbRecipients;
                    $client->addCallbackParam($callbackUID, 'mailsrv:pmb_subject', null);
                    $client->addCallbackParam($callbackUID, 'mailsrv:pmb_message', null);
                    $client->addCallbackParam($callbackUID, 'sender_id', $objSender->getId());
                    $client->addCallbackParam($callbackUID, 'sender_type', ($objSender instanceof Warecorp_User) ? 'user' : 'group');
                    $client->addCallbackParam($callbackUID, 'recipients', join(';', $pmbRecipients) );
                    
                    unset( $pmbRecipients );
                    
                    $request = $client->addRecipients($campaignUID, $msrvRecipients);
                    $request = $client->startCampaign($campaignUID);
                    
                    $msrvSended = true;
                } catch ( Exception $e ) { $msrvSended = false; }
            }
        }

        /**
         * TODO : MAILSRV_REMOVE : Remove it when transfer to mailsrv will be done
         * if emails haven't been sended by SOAP: MailSrv, send it 
         */
        if ( !$msrvSended ) {
            $mail = new Warecorp_Mail_Template('template_key', 'RESIGN_REQUEST_NEW_HOST');
            $mail->setSender($objSender);
            $mail->addRecipient($objRecipient);
            $mail->addParam('Group', $this);
            $mail->addParam('AccessCode', $AccessCode);
            $mail->addParam('message_subject', $Subject);
            $mail->addParam('message_body', $Message);
            $mail->sendToPMB(true);
            $mail->send();
        }
    }
    
    public function sendResignThankNewHost( $objSender, $objRecipient )
    {        
        /* SOAP: MailSrv */
        $msrvRecipients = new Warecorp_SOAP_Type_Recipients();
        $pmbRecipients = array();
        $msrvSended = false;
                
        /* SOAP: MailSrv */
        if ( Warecorp::isMailServerUsed() && Warecorp::isMailServerTemplateRegistered( 'RESIGN_THANK_NEW_HOST' ) ) {
                       
            /* SOAP: MailSrv */       
            try { $client = Warecorp::getMailServerClient(); }
            catch ( Exception $e ) { $client = null; }   
            
            if ( $client ) {   

                $recipient = new Warecorp_SOAP_Type_Recipient();
                $recipient->setEmail( $objRecipient->getEmail() );
                $recipient->setName( $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : null );
                $recipient->setLocale( null );
                $recipient->addParam('CCFID', Warecorp::getCCFID($objRecipient));
                $recipient->addParam( 'recipient_full_name', $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : '' );
                $recipient->addParam( 'SITE_LINK_UNSUBSCRIBE', $objRecipient->getUserPath('settings') );
                $msrvRecipients->addRecipient($recipient);
                $pmbRecipients[] = $objRecipient->getId() ? $objRecipient->getId() : $objRecipient->getEmail();
                
                try { 
                    $campaignUID = $client->createCampaign();                        
                    $request = $client->setSender($campaignUID, $this->getGroupEmail(), $this->getName());
                    //$request = $client->setSender($campaignUID, 'messages-noreply@bounce.'.DOMAIN_FOR_EMAIL, 'Group Memberships');
                    $request = $client->setTemplate($campaignUID, 'RESIGN_THANK_NEW_HOST', HTTP_CONTEXT); /* RESIGN_THANK_NEW_HOST */
                                        
                    /* add params */
                    $params = new Warecorp_SOAP_Type_Params();
                    $params->loadDefaultCampaignParams();
                    $params->addParam( 'group_name', $this->getName() );
                    $params->addParam( 'url_group_summary', $this->getGroupPath('summary') );
                    $request = $client->addParams($campaignUID, $params);

                    /* add headers */
                    $client->addHeader($campaignUID, 'Sender', '"'.$this->getName().'" <'.$this->getGroupEmail().'>');
                    $client->addHeader($campaignUID, 'Reply-To', '"'.$this->getName().'" <'.$this->getGroupEmail().'>');
                    //$client->addHeader($campaignUID, 'Sender', '"Group Memberships" <messages-noreply@bounce.'.DOMAIN_FOR_EMAIL.'>');
                    //$client->addHeader($campaignUID, 'Reply-To', '"Group Memberships" <messages-noreply@bounce.'.DOMAIN_FOR_EMAIL.'>');
                    
                    /* add callback to mailsrv campaign to sent PMB message */
                    $objCallback = new Warecorp_SOAP_Type_Callback();
                    $objCallback->setType( Warecorp_SOAP_Type_Callback::TYPE_RECIPIENTS );
                    $objCallback->setWsdl( BASE_URL.'/wsdl.php?t=service' );
                    $objCallback->setAction( 'callbackAddPMBMessage' );
                    $callbackUID = $client->addCallback($campaignUID, $objCallback);
        
                    $pmbRecipients = ( null === $pmbRecipients || !is_array($pmbRecipients) ) ? array() : $pmbRecipients;
                    $client->addCallbackParam($callbackUID, 'mailsrv:pmb_subject', null);
                    $client->addCallbackParam($callbackUID, 'mailsrv:pmb_message', null);
                    $client->addCallbackParam($callbackUID, 'sender_id', $objSender->getId());
                    $client->addCallbackParam($callbackUID, 'sender_type', ($objSender instanceof Warecorp_User) ? 'user' : 'group');
                    $client->addCallbackParam($callbackUID, 'recipients', join(';', $pmbRecipients) );
                    unset( $pmbRecipients );
                    
                    $request = $client->addRecipients($campaignUID, $msrvRecipients);
                    $request = $client->startCampaign($campaignUID);
                    
                    $msrvSended = true;
                } catch ( Exception $e ) { $msrvSended = false; }
            }
        }

        /**
         * TODO : MAILSRV_REMOVE : Remove it when transfer to mailsrv will be done
         * if emails haven't been sended by SOAP: MailSrv, send it 
         */
        if ( !$msrvSended ) {
            /* send email to old host */
            $mail = new Warecorp_Mail_Template('template_key', 'RESIGN_THANK_NEW_HOST');
            $mail->setSender($objSender);
            $mail->addRecipient($objRecipient);
            $mail->addParam('Group', $this);
            $mail->sendToPMB(true);
            $mail->send();
        }
    }
    
    public function sendNewDataIsUploaded( $objUser, $chObject, $Section, $Action, $isPlural = false, $Items = array(), $Type = null )
    {
        if ( !$this->getPrivileges()->getSendEmail() ) return true;
        
        /*        
        $User      //  required
        $chObject  //  required        
        $section   //  required                
        $action    //  default "CHANGES"
        $isPlural  //  default false
        $items     //  new or changed elements
        $type      //  need for Documents section, use:  FILE or FOLDER, default NULL
        */        
                      
        $Section = strtoupper($Section);
        $Action = isset($Action) ? strtoupper($Action) : 'CHANGES';
        $Type = strtoupper($Type);
        $isPlural = isset($isPlural) ? $isPlural : false;
        
        if ( $Section == 'FILE' && null === $Type ) throw new Exception('"type" parameter is required for documents.');
        
        $default_subject = Warecorp::t('You have changes in %s', $this->getName());        
        $subject = '';
        $message_plain = '';
        $message_html = '';
        
        $url_group = $this->getGroupPath('summary');
        $url_user = $objUser->getUserPath('profile');
        
        switch ( $Section ) {
            case 'PHOTO' :
                $url_gallery = $this->getGroupPath('galleryedit/gallery/'.$chObject->getId());
                
                if ( $Action == 'NEW' ) {
                    $subject = Warecorp::t( 'You have new gallery %s in group %s', array($chObject->getTitle(), $this->getName()) );
                    $message_html = Warecorp::t( 'You have new gallery <a href="%s">%s</a> in group <a href="%s">%s</a>.', array($url_gallery, $chObject->getTitle(), $url_group, $this->getName()) ) . '<br />';
                    $message_html .= Warecorp::t( 'Gallery is added by <a href="%s">%s</a>.', array($url_user, $objUser->getLogin()) ) . '<br />'; 
                } elseif ( $Action == 'CHANGES' ) {
                    $subject = Warecorp::t( 'Gallery %s is changed in group %s', array($chObject->getTitle(), $this->getName()) );
                    $message_html = Warecorp::t( 'Photos in gallery <a href="%s">%s</a> of group <a href="%s">%s</a> are changed by <a href="%s">%s</a>.', array($url_gallery, $chObject->getTitle(), $url_group, $this->getName(), $url_user, $objUser->getLogin()) ) . '<br />';
                } elseif ( $Action == 'DELETE' ) {
                    $subject = Warecorp::t( 'Gallery %s is deleted in group %s', array($chObject->getTitle(), $this->getName()) );
                    $message_html = Warecorp::t( 'Gallery %s in group <a href="%s">%s</a> has been deleted by <a href="%s">%s</a>.', array($chObject->getTitle(), $url_group, $this->getName(), $url_user, $objUser->getLogin()) ) . '<br />';
                } else $subject = $default_subject;
                
                if ( is_array($Items) && sizeof($Items) != 0 ) {
                    $message_html .= Warecorp::t('Photos:') . '<br />';
                    foreach ( $Items as $index => $item ) {
                        $message_html .= ($index+1).'. '.$item . '<br />';
                    }
                }
                break;
            case 'VIDEO' :
                if ( $isPlural ) {
                    $videos = $chObject->getVideos()->getList();
                    if ( sizeof($videos) != 0 ) $url_gallery = $this->getGroupPath('videogalleryView/id/'.$videos[0]->getId());
                    else $url_gallery = $this->getGroupPath('videogalleryView/id/'.$chObject->getId());
                } else {
                    $url_gallery = $this->getGroupPath('videogalleryView/id/'.$chObject->getId());
                }
                
                if ( $Action == 'NEW' ) {
                    if ( $isPlural ) {
                        $subject = Warecorp::t( 'You have new Videos in %s', array($this->getName()) );
                        $message_html = Warecorp::t( 'You have new Videos in <a href="%s">%s</a>.', array($url_group, $this->getName()) ) . '<br />';
                        $message_html .= '<a href="'.$url_gallery.'">'.$chObject->getTitle().'</a>' . '<br />';
                        $message_html .= Warecorp::t( 'Videos are added by <a href="%s">%s</a>.', array($url_user, $objUser->getLogin()) ) . '<br />';
                    } else {
                        $subject = Warecorp::t( 'You have new Video in %s', array($this->getName()) );
                        $message_html = Warecorp::t( 'You have new Video in <a href="%s">%s</a>.', array($url_group, $this->getName()) ) . '<br />';
                        $message_html .= '<a href="'.$url_gallery.'">'.$chObject->getTitle().'</a>' . '<br />';
                        $message_html .= Warecorp::t( 'Videos are added by <a href="%s">%s</a>.', array($url_user, $objUser->getLogin()) ) . '<br />';
                    }
                } elseif ( $Action == 'CHANGES' ) {
                    if ( $isPlural ) {
                        $subject = Warecorp::t( 'Videos are changed in %s', array($this->getName()) );
                        $message_html = Warecorp::t( 'Videos are changed in <a href="%s">%s</a>.', array($url_group, $this->getName()) ) . '<br />';
                        $message_html .= '<a href="'.$url_gallery.'">'.$chObject->getTitle().'</a>' . '<br />';
                        $message_html .= Warecorp::t( 'Videos are changed by <a href="%s">%s</a>.', array($url_user, $objUser->getLogin()) ) . '<br />';
                    } else {
                        $subject = Warecorp::t( 'Video is changed in %s', array($this->getName()) );
                        $message_html = Warecorp::t( 'Video is changed in <a href="%s">%s</a>.', array($url_group, $this->getName()) ) . '<br />';
                        $message_html .= '<a href="'.$url_gallery.'">'.$chObject->getTitle().'</a>' . '<br />';
                        $message_html .= Warecorp::t( 'Videos is changed by <a href="%s">%s</a>.', array($url_user, $objUser->getLogin()) ) . '<br />';
                    }
                } elseif ( $Action == 'DELETE' ) {
                    if ( $isPlural ) {
                        $subject = Warecorp::t( 'Videos are deleted in %s', array($this->getName()) );
                        $message_html = Warecorp::t( 'Videos are deleted in <a href="%s">%s</a> by <a href="%s">%s</a>.', array($url_group, $this->getName(), $url_user, $objUser->getLogin()) ) . '<br />';
                    } else {
                        $subject = Warecorp::t( 'Video is deleted in %s', array($this->getName()) );
                        $message_html = Warecorp::t( 'Video is deleted in <a href="%s">%s</a> by <a href="%s">%s</a>', array($url_group, $this->getName(), $url_user, $objUser->getLogin()) ) . '<br />';
                    }
                } else $subject = $default_subject;
                
                if ( !SINGLEVIDEOMODE && is_array($Items) && sizeof($Items) != 0 ) {
                    $message_html .= Warecorp::t('Videos:') . '<br />';
                    foreach ( $Items as $index => $item ) {
                        $message_html .= ($index+1).'. '.$item . '<br />';
                    }
                } elseif ( $Action == 'DELETE' ) {
                    $message_html .= Warecorp::t('Videos:') . '<br />';
                    $message_html .= $chObject->getTitle() . '<br />';
                }
                break;
            case 'EVENT' :
                $url_event = $chObject->entityURL();
                $event_date = $chObject->displayDate('email.invitation.date', $objUser->getId(), $objUser->getTimezone());
                $event_time = $chObject->displayDate('email.invitation.time', $objUser->getId(), $objUser->getTimezone());
                
                if ( $Action == 'NEW' ) {
                    if ( $isPlural ) {
                        $subject = Warecorp::t( 'You have new Events in %s', array($this->getName()) );
                        $message_html = Warecorp::t( 'You have new Events in <a href="%s">%s</a>.', array($url_group, $this->getName()) ) . '<br />';
                        $message_html .= Warecorp::t( 'Events are added by <a href="%s">%s</a>.', array($url_user, $objUser->getLogin()) ) . '<br />';
                    } else {
                        $subject = Warecorp::t( 'You have new Event in %s', array($this->getName()) );
                        if ( Warecorp::checkHttpContext('zccf')) {
                            $message_html = Warecorp::t( 'You have new Event <a href="%s">%s</a> on %s from %s in <a href="%s">%s</a>.', array($url_event, $chObject->getTitle(), $event_date, $event_time, $url_group, $this->getName()) ) . '<br />';
                            $message_html .= Warecorp::t( 'Event is added by <a href="%s">%s</a>.', array($url_user, $objUser->getLogin()) ) . '<br />';
                        }else{
                            $message_html = Warecorp::t( 'You have new Event <a href="%s">%s</a> in <a href="%s">%s</a>.', array($url_event, $chObject->getTitle(), $url_group, $this->getName()) ) . '<br />';
                            $message_html .= Warecorp::t( 'Event is added by <a href="%s">%s</a>.', array($url_user, $objUser->getLogin()) ) . '<br />';
                        }
                    }
                } elseif ( $Action == 'CHANGES' ) {
                    if ( $isPlural ) {
                        $subject = Warecorp::t( 'Events are changed in %s', array($this->getName()) );
                        $message_html = Warecorp::t( 'Events are changed in <a href="%s">%s</a> by <a href="%s">%s</a>.', array($url_group, $this->getName(), $url_user, $objUser->getLogin()) ) . '<br />';
                    } else {
                        $subject = Warecorp::t( 'Event is changed in %s', array($this->getName()) );
                        if ( Warecorp::checkHttpContext('zccf')) {
                            $message_html = Warecorp::t( 'Event <a href="%s">%s</a> on %s from %s is changed in <a href="%s">%s</a> by <a href="%s">%s</a>.', array($url_event, $chObject->getTitle(), $event_date, $event_time, $url_group, $this->getName(), $url_user, $objUser->getLogin()) ) . '<br />';
                        }else{
                            $message_html = Warecorp::t( 'Event <a href="%s">%s</a> is changed in <a href="%s">%s</a> by <a href="%s">%s</a>.', array($url_event, $chObject->getTitle(), $url_group, $this->getName(), $url_user, $objUser->getLogin()) ) . '<br />';
                        }
                    }
                } elseif ( $Action == 'DELETE' ) {
                    if ( $isPlural ) {
                        $subject = Warecorp::t( 'Events are deleted in %s', array($this->getName()) );
                        $message_html = Warecorp::t( 'Events are deleted in <a href="%s">%s</a> by <a href="%s">%s</a>.', array($url_group, $this->getName(), $url_user, $objUser->getLogin()) ) . '<br />';
                    } else {
                        $subject = Warecorp::t( 'Event is deleted in %s', array($this->getName()) );
                        if ( Warecorp::checkHttpContext('zccf')) {
                            $message_html = Warecorp::t( 'Event %s on %s from %s is deleted in <a href="%s">%s</a> by <a href="%s">%s</a>.', array($chObject->getTitle(), $event_date, $event_time, $url_group, $this->getName(), $url_user, $objUser->getLogin()) ) . '<br />';
                        }else{
                            $message_html = Warecorp::t( 'Event %s is deleted in <a href="%s">%s</a> by <a href="%s">%s</a>.', array($chObject->getTitle(), $event_date, $this->getName(), $url_user, $objUser->getLogin()) ) . '<br />';
                        }
                    }
                } else $subject = $default_subject;
                
                if ( is_array($Items) && sizeof($Items) != 0 ) {
                    $message_html .= Warecorp::t('Events:') . '<br />';
                    foreach ( $Items as $index => $item ) {
                        $message_html .= ($index+1).'. '.$item . '<br />';
                    }
                }
                break;
            case 'FILE' :
                $url_file = $this->getGroupPath('documents');
                
                if ( $Action == 'NEW' ) {
                    if ( $Type == 'FOLDER' ) {
                        if ( $isPlural ) {
                            $subject = Warecorp::t( 'You have new Folders in %s', array($this->getName()) );
                            $message_html = Warecorp::t( 'You have new Folders in <a href="%s">%s</a>.', array($url_group, $this->getName()) ) . '<br />';
                            $message_html .= Warecorp::t( 'Folders are addedd by <a href="%s">%s</a>.', array($url_user, $objUser->getLogin()) ) . '<br />';
                        } else {
                            $subject = Warecorp::t( 'You have new Folder in %s', array($this->getName()) );
                            $message_html = Warecorp::t( 'You have new Folder <a href="%s">%s</a> in <a href="%s">%s</a>.', array($url_file, $chObject->getName(), $url_group, $this->getName()) ) . '<br />';
                            $message_html .= Warecorp::t( 'Folder is addedd by <a href="%s">%s</a>.', array($url_user, $objUser->getLogin()) ) . '<br />';
                        }
                    } else {
                        if ( $isPlural ) {
                            $subject = Warecorp::t( 'You have new Files in %s', array($this->getName()) );
                            $message_html = Warecorp::t( 'You have new Files in <a href="%s">%s</a>.', array($url_group, $this->getName()) ) . '<br />';
                            $message_html .= Warecorp::t( 'Files are addedd by <a href="%s">%s</a>.', array($url_user, $objUser->getLogin()) ) . '<br />';
                        } else {
                            $subject = Warecorp::t( 'You have new File in %s', array($this->getName()) );
                            $message_html = Warecorp::t( 'You have new File <a href="%s">%s</a> in <a href="%s">%s</a>.', array($url_file, $chObject->getName(), $url_group, $this->getName()) ) . '<br />';
                            $message_html .= Warecorp::t( 'File is addedd by <a href="%s">%s</a>.', array($url_user, $objUser->getLogin()) ) . '<br />';
                        }
                    }
                } elseif ( $Action == 'CHANGES' ) {
                    if ( $Type == 'FOLDER' ) {
                        if ( $isPlural ) {
                            $subject = Warecorp::t( 'Folders are changed in %s', array($this->getName()) );
                            $message_html = Warecorp::t( 'Folders are changed in <a href="%s">%s</a> by <a href="%s">%s</a>.', array($url_group, $this->getName(), $url_user, $objUser->getLogin()) ) . '<br />';
                        } else {
                            $subject = Warecorp::t( 'Folder is changed in %s', array($this->getName()) );
                            $message_html = Warecorp::t( 'Folder <a href="%s">%s</a> is changed in <a href="%s">%s</a> by <a href="%s">%s</a>.', array($url_file, $chObject->getName(), $url_group, $this->getName(), $url_user, $objUser->getLogin()) ) . '<br />';
                        }
                    } else {
                        if ( $isPlural ) {
                            $subject = Warecorp::t( 'Files are changed in %s', array($this->getName()) );
                            $message_html = Warecorp::t( 'Files are changed in <a href="%s">%s</a> by <a href="%s">%s</a>.', array($url_group, $this->getName(), $url_user, $objUser->getLogin()) ) . '<br />';
                        } else {
                            $subject = Warecorp::t( 'File is changed in %s', array($this->getName()) );
                            $message_html = Warecorp::t( 'File <a href="%s">%s</a> is changed in <a href="%s">%s</a> by <a href="%s">%s</a>.', array($url_file, $chObject->getName(), $url_group, $this->getName(), $url_user, $objUser->getLogin()) ) . '<br />';
                        }
                    }
                } elseif ( $Action == 'DELETE' ) {
                    if ( $Type == 'FOLDER' ) {
                        if ( $isPlural ) {
                            $subject = Warecorp::t( 'Folders are deleted in %s', array($this->getName()) );
                            $message_html = Warecorp::t( 'Folders are deleted in <a href="%s">%s</a> by <a href="%s">%s</a>.', array($url_group, $this->getName(), $url_user, $objUser->getLogin()) ) . '<br />';
                        } else {
                            $subject = Warecorp::t( 'Folder is deleted in %s', array($this->getName()) );
                            $message_html = Warecorp::t( 'Folder %s is deleted in <a href="%s">%s</a> by <a href="%s">%s</a>.', array($chObject->getName(), $url_group, $this->getName(), $url_user, $objUser->getLogin()) ) . '<br />';
                        }
                    } else {
                        if ( $isPlural ) {
                            $subject = Warecorp::t( 'Files are deleted in %s', array($this->getName()) );
                            $message_html = Warecorp::t( 'Files are deleted in <a href="%s">%s</a> by <a href="%s">%s</a>.', array($url_group, $this->getName(), $url_user, $objUser->getLogin()) ) . '<br />';
                        } else {
                            $subject = Warecorp::t( 'File is deleted in %s', array($this->getName()) );
                            $message_html = Warecorp::t( 'File %s is deleted in <a href="%s">%s</a> by <a href="%s">%s</a>.', array($chObject->getName(), $url_group, $this->getName(), $url_user, $objUser->getLogin()) ) . '<br />';
                        }
                    }
                } else $subject = $default_subject;
                break;
            case 'LISTS' :
                $url_list = $this->getGroupPath('listsview/listid/'.$chObject->getId());
                
                if ( $Action == 'NEW' ) {
                    $subject = Warecorp::t( 'You have new List in %s', array($this->getName()) );
                    $message_html = Warecorp::t( 'You have new List <a href="%s">%s</a> in <a href="%s">%s</a>.', array($url_list, $chObject->getTitle(), $url_group, $this->getName()) ) . '<br />';
                    $message_html .= Warecorp::t( 'List is added by <a href="%s">%s</a>.', array($url_user, $objUser->getLogin()) ) . '<br />';
                } elseif ( $Action == 'CHANGES' ) {
                    $subject = Warecorp::t( 'List %s is changed in %s', array($chObject->getTitle(), $this->getName()) );
                    $message_html = Warecorp::t( 'List <a href="%s">%s</a> is changed in <a href="%s">%s</a> by <a href="%s">%s</a>.', array($url_list, $chObject->getTitle(), $url_group, $this->getName(), $url_user, $objUser->getLogin()) ) . '<br />';
                } elseif ( $Action == 'DELETE' ) {
                    $subject = Warecorp::t( 'List is deleted in %s', array($this->getName()) );
                    $message_html = Warecorp::t( 'List %s is deleted in <a href="%s">%s</a> by <a href="%s">%s</a>.', array($chObject->getTitle(), $url_group, $this->getName(), $url_user, $objUser->getLogin()) ) . '<br />';
                } else $subject = $default_subject;
                
                if ( is_array($Items) && sizeof($Items) != 0 ) {
                    $message_html .= Warecorp::t('Lists:') . '<br />';
                    foreach ( $Items as $index => $item ) {
                        $message_html .= ($index+1).'. '.$item . '<br />';
                    }
                }
                break;
            default : 
                $subject = $default_subject;
        }
            
        $message_plain = !empty($message_plain) ? $message_plain : $subject;
        $message_html = !empty($message_html) ? $message_html : $subject;
        
        
        /* SOAP: MailSrv */
        $msrvRecipients = new Warecorp_SOAP_Type_Recipients();
        $pmbRecipients = array();
        $msrvSended = false;
                
        /* SOAP: MailSrv */
        if ( Warecorp::isMailServerUsed() && Warecorp::isMailServerTemplateRegistered( 'GROUP_NEW_DATA_IS_UPLOADED' ) ) {
                       
            /* SOAP: MailSrv */       
            try { $client = Warecorp::getMailServerClient(); }
            catch ( Exception $e ) { $client = null; }   
            
            if ( $client ) {   
                $objRecipient = $this->getHost();
                $recipient = new Warecorp_SOAP_Type_Recipient();
                $recipient->setEmail( $objRecipient->getEmail() );
                $recipient->setName( $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : null );
                $recipient->setLocale( null );
                $recipient->addParam('CCFID', Warecorp::getCCFID($objRecipient));
                $recipient->addParam( 'recipient_full_name', $objRecipient->getId() ? $objRecipient->getFirstname().' '.$objRecipient->getLastname() : '' );
                $recipient->addParam( 'SITE_LINK_UNSUBSCRIBE', $objRecipient->getUserPath('settings') );
                $msrvRecipients->addRecipient($recipient);
                $pmbRecipients[] = $objRecipient->getId() ? $objRecipient->getId() : $objRecipient->getEmail();
                
                try { 
                    $objSender = $this;
                    
                    $campaignUID = $client->createCampaign();                        
                    $request = $client->setSender($campaignUID, $this->getGroupEmail(), $this->getName());
                    //$request = $client->setSender($campaignUID, 'messages-noreply@bounce.'.DOMAIN_FOR_EMAIL, 'Group Memberships');
                    $request = $client->setTemplate($campaignUID, 'GROUP_NEW_DATA_IS_UPLOADED', HTTP_CONTEXT); /* GROUP_NEW_DATA_IS_UPLOADED */
                                        
                    /* add params */
                    $params = new Warecorp_SOAP_Type_Params();
                    $params->loadDefaultCampaignParams();
                    $params->addParam( 'subject', $subject );
                    $params->addParam( 'message_plain', $message_plain );
                    $params->addParam( 'message_html', $message_html );
                    $request = $client->addParams($campaignUID, $params);

                    /* add headers */
                    $client->addHeader($campaignUID, 'Sender', '"'.$this->getName().'" <'.$this->getGroupEmail().'>');
                    $client->addHeader($campaignUID, 'Reply-To', '"'.$this->getName().'" <'.$this->getGroupEmail().'>');
                    //$client->addHeader($campaignUID, 'Sender', '"Group Memberships" <messages-noreply@bounce.'.DOMAIN_FOR_EMAIL.'>');
                    //$client->addHeader($campaignUID, 'Reply-To', '"Group Memberships" <messages-noreply@bounce.'.DOMAIN_FOR_EMAIL.'>');
                    
                    /* add callback to mailsrv campaign to sent PMB message */
                    $objCallback = new Warecorp_SOAP_Type_Callback();
                    $objCallback->setType( Warecorp_SOAP_Type_Callback::TYPE_RECIPIENTS );
                    $objCallback->setWsdl( BASE_URL.'/wsdl.php?t=service' );
                    $objCallback->setAction( 'callbackAddPMBMessage' );
                    $callbackUID = $client->addCallback($campaignUID, $objCallback);
        
                    $pmbRecipients = ( null === $pmbRecipients || !is_array($pmbRecipients) ) ? array() : $pmbRecipients;
                    $client->addCallbackParam($callbackUID, 'mailsrv:pmb_subject', null);
                    $client->addCallbackParam($callbackUID, 'mailsrv:pmb_message', null);
                    $client->addCallbackParam($callbackUID, 'sender_id', $objSender->getId());
                    $client->addCallbackParam($callbackUID, 'sender_type', ($objSender instanceof Warecorp_User) ? 'user' : 'group');
                    $client->addCallbackParam($callbackUID, 'recipients', join(';', $pmbRecipients) );
                    unset( $pmbRecipients );
                    
                    $request = $client->addRecipients($campaignUID, $msrvRecipients);
                    $request = $client->startCampaign($campaignUID);
                    
                    $msrvSended = true;
                } catch ( Exception $e ) { $msrvSended = false; }
            }
        }

        /**
         * TODO : MAILSRV_REMOVE : Remove it when transfer to mailsrv will be done
         * if emails haven't been sended by SOAP: MailSrv, send it 
         */
        if ( !$msrvSended ) {
            $mail = new Warecorp_Mail_Template( 'template_key', 'GROUP_NEW_DATA_IS_UPLOADED' );
            $mail->setHeader( 'Sender', '"' . htmlspecialchars( $this->getName() ) . '" <' . $this->getGroupEmail() . '>' );
            $mail->setHeader( 'Reply-To', '"' . htmlspecialchars( $this->getName() ) . '" <' . $this->getGroupEmail() . '>' );
            $mail->setSender( $this );
            $mail->addRecipient( $this->getHost() );
            $mail->addParam( 'Group', $this );
            $mail->addParam( 'action', $Action );
            $mail->addParam( 'section', $Section );
            $mail->addParam( 'chObject', $chObject );
            $mail->addParam( 'User', $objUser );
            $mail->addParam( 'isPlural', $isPlural );
            $mail->addParam( 'items', $Items );
            $mail->addParam( 'type', $Type );
            $mail->sendToPMB( true );
            $mail->send();
        }
    }
}
