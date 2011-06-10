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
 * @package    Warecorp_Photo
 * @copyright  Copyright (c) 2006, 2008
 */

class BaseWarecorp_Group_WebBadges_Item extends Warecorp_Data_Entity_Image
{
    private $id;
    private $groupId;
    private $description;

    public function __construct($id = null)
    {
        parent::__construct( '/upload/gallery_badge',
                             'zanby_groups__badge_galleries',
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

    public function getPhotoPath()
    {
        return $this->getPath();
    }
	
    public function getPhotoSrc()
    {
        return $this->getSrc();
    }

    /**
     * Возвращает путь к small изображения
     * @deleteme 
     * @return string
     */
    public function getSmall()
    {
        throw new Zend_Exception( "Method Warecorp_Group_WebBadges_Item::getSmall() is deprecated.");

        if ( $this->isExists() == true && file_exists(getcwd().$this->getPhotoSrc().'_small.jpg') ) {
            return $this->getPhotoSrc().'_small.jpg';
        } else {
            return UPLOAD_BASE_URL.'/images/photos/no_image.gif';
        }
    }

    /**
     * Возвращает путь к original изображения
     * @deleteme 
     * @return string
     */
    public function getOriginal()
    {
        throw new Zend_Exception( "Method Warecorp_Group_WebBadges_Item::getOriginal() is deprecated.");

        if ( $this->isExists() == true && file_exists(getcwd().$this->getPhotoSrc().'_orig.jpg') ) {
            return $this->getPhotoSrc().'_orig.jpg';
        } else {
            return UPLOAD_BASE_URL.'/images/photos/no_image.gif';
        }
    }

    /**
     * Проверяет определен ли объект
     * @deleteme 
     * @return bool
     * @todo move to Warecorp_Data_Entity (Dmitry Kostikov)
     */
    public function isExists()
    {
        throw new Zend_Exception( "Method Warecorp_Group_WebBadges_Item::getOriginal() is deprecated.");

        if ( $this->hetId() !== null ) return true;
        else return false;
    }

    /**
     * Проверяет, существует ли фото с указанным id [и для указанной галереи]
     *
     * @param int $gall_id
     * @return boolean
     */
    public static function isPhotoExists($id, $gall_id = null)
    {
        $db = Zend_Registry::get("DB");
        $select = $db->select()->from('zanby_galleries__photos',new Zend_Db_Expr('count(id)'))->where('id = ?', $id);
        if ( $gall_id !== null ) $select->where('gallery_id = ?', $gall_id);
        $res = $db->fetchOne($select);
        return (boolean) $res;
    }

    /**
     * return or create image with drawn name of family group
     * @param $groupId 	- Id of group for filename
     * @param $reDraw  	- if true, regenerate image
     * @param $groupName 	- text for badge 
     * @author Eugene Halauniou
     * @author Aleksei Gusev
     */
    public function getNamedBadge($groupId, $reDraw = false, $groupName = null){
        if ($reDraw || !file_exists(UPLOAD_BASE_PATH . '/upload/gallery_badge/badge_base_'.$groupId.'.jpg')){//recreate default badge

            $baseImage = self::getAppTheme()->images_path . '/upload/gallery_badge/badge_base.png';
            $im = imagecreatefrompng($baseImage);

            $textcolor = imagecolorallocate($im, 110, 211, 224);
			
			$font = self::getAppTheme()->fonts_path . '/arialbd.ttf';
			if ( !file_exists($font) ) 
				$font = self::getAppTheme()->common->fonts_path . '/arialbd.ttf';
            
            imagettftext($im, 12, 0, 95, 37, $textcolor, $font, $groupName);

            @imagejpeg($im, UPLOAD_BASE_PATH . '/upload/gallery_badge/badge_base_'.$groupId.'.jpg', 100);
            imagedestroy($im);
        }
        return UPLOAD_BASE_URL.'/upload/gallery_badge/badge_base_'.$groupId.'.jpg';
    }

}
?>
