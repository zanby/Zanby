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


class BaseWarecorp_Group_BrandPhoto_Item extends Warecorp_Data_Entity_Image
{
	private $id;
	private $groupId;
	private $description;
    
    private $Group = null;

	/**
     * Constructor.
     *
     */
	public function __construct($id = null)
	{
		parent::__construct( '/upload/gallery_brand',
                             'zanby_groups__brand_galleries',
                             array( 'id'          => 'id',
                                    'group_id'    => 'groupId',
                                    'description' => 'description'));
		if ($id !== null){
			$this->pkColName = 'id';
			$this->loadByPk($id);
		}
	}

    protected function getBasename(){
        return md5( $this->getId());
    }
    
	/**
     * Set badge id
     *
     * @param int $newVal
     * @return self
     * @author Eugene Kirdzei
     */
	public function setId($newVal)
	{
		$this->id = $newVal;
		return $this;
	}

	/**
     * Return bage id
     *
     * @return int
     * @author Eugene Kirzdei
     */
	public function getId()
	{
		return $this->id;
	}

	/**
     * Set group id
     *
     * @param int $newVal
     * @return self
     * @author Eugene Kirdzei
     */
	public function setGroupId($newVal)
	{
		$this->groupId = $newVal;
		return $this;
	}

	/**
     * Return bage id
     *
     * @return int
     * @author Eugene Kirzdei
     */
	public function getGroupId()
	{
		return $this->groupId;
	}
    
    /**                         
     * @author Alexander Komarovski
     */
    public function getGroup()
    {
        if ((null === $this->Group && !empty($this->groupId)) || (null !== $this->Group && $this->Group->getId() != $this->groupId) ) {
            $this->Group = Warecorp_Group_Factory::loadById($this->groupId);
        }
        
        return $this->Group;
    }
    

	/**
     * Set description
     *
     * @param string $newVal
     * @return self
     * @author Eugene Kirdzei
     */
	public function setDescription($newVal)
	{
		$this->description = $newVal;
		return $this;
	}

	/**
     * Return description
     *
     * @return string
     * @author Eugene Kirzdei
     */
	public function getDescription()
	{
		return $this->description;
	}

	/**
     * Set path to photo
     *
     * @param string $newVal
     * @return self
     * @author Eugene Kirdzei
     */
	public function setPhotoPath()
	{
        throw new Zend_Exception( "Deprecation warning: Warecorp_Group_BrandPhoto_Item::setPhotoPath() is deprecated.");

		if ( $this->id !== null ) {
			$this->photoPath = UPLOAD_BASE_PATH.'/upload/gallery_brand/'.md5($this->getId());
		}
		return $this;
	}

    /**
     * @author Alexander Komarovski
     */
	public function setPhotoSrc()
	{
        throw new Zend_Exception( "Deprecation warning: Warecorp_Group_BrandPhoto_Item::setPhotoSrc() is deprecated.");

		if ( $this->id !== null ) {
			$this->photoSrc = UPLOAD_BASE_URL.'/upload/gallery_brand/'.md5($this->getId());
		}
		return $this;
	}
	
    public function getPhotoPath()
    {
        return $this->getPath();
    }
	
    public function getPhotoSrc()
    {
        return $this->getSrc();
    }
	
	/**
     * Проверяет определен ли объект
     * @return bool
     * @todo move to Warecorp_Data_Entity (Dmitry Kostikov)
     */
	public function isExists()
	{
		if ( $this->id !== null ) return true;
		else return false;
	}

	/**
	 * Проверяет, существует ли фото с указанным id [и для указанной группы]
	 *
	 * @param int $gall_id
	 * @return boolean
	 */
	public static function isPhotoExists($id, $group_id = null)
	{
	    $db = Zend_Registry::get("DB");
        $select = $db->select()->from('zanby_groups__brand_galleries',new Zend_Db_Expr('count(id)'))->where('id = ?', $id);
        if ( $group_id !== null ) $select->where('group_id = ?', $group_id);
        $res = $db->fetchOne($select);
        return (boolean) $res;
	}
}
?>
