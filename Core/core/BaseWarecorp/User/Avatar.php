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
 * @package    Warecorp_User
 * @copyright  Copyright (c) 2006
 */

require_once(WARECORP_DIR.'Interface/iAvatar.php');

class BaseWarecorp_User_Avatar extends Warecorp_Data_Entity_Image implements Warecorp_Interface_iAvatar
{
    private $id;
    private $userId;
    private $byDefault;

    /**
     * @todo $border always is 1 - no processing while new avatar created.
     * @author Halauniou Eugene
     */

    /**
     * Constructor.
     */
    public function __construct($value = null)
    {
        parent::__construct( '/upload/user_avatars/',
                             'zanby_users__avatars',
                             array( 'id'        => 'id',
                                    'user_id'   => 'userId',
                                    'bydefault' => 'bydefault'));
        if ($value === 0) {
        	$this->id = 0;
        } else {
	        $this->load($value);
        }
    }

    protected function getBasename(){
        return md5($this->userId.$this->id);
    }

    /**
     * get Id property
     * @author halauniou eugene
     */
    public function getId()
    {
        return (int)$this->id;
    }

    /**
     * set Id property
     * @author halauniou eugene
     */
    public function setId($newValue)
    {
        $this->id = $newValue;
        return $this;
    }

    /**
     * get  userId property
     * @author halauniou eugene
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * set  userId property
     * @author halauniou eugene
     */
    public function setUserId($newValue)
    {
        $this->userId = $newValue;
        return $this;
    }


    /**
     * get  ByDefault property for retrieve thumbnail
     * @author halauniou eugene
     */
    public function getByDefault()
    {
        return $this->byDefault;
    }
    /**
     * set  ByDefault property for retrieve thumbnail
     * @author halauniou eugene
     */
    public function setByDefault($newValue)
    {
        $this->byDefault = $newValue;
        return $this;
    }

    /**
     * Проверяет определен ли объект
     * @return bool
     */
    public function isExists()
    {
        if ( $this->getId() !== null ) return true;
        else return false;
    }
    /**
     * Возвращает путь к small аватар
     * @return string
     */
    public function getSmall()
    {
        return $this->setWidth(48)->setHeight(48)->setBorder(1)->getImage();
    }
    /**
     * Возвращает путь к medium аватар
     * @return string
     */
    public function getMedium()
    {
        return $this->setWidth(175)->setHeight(215)->setBorder(1)->getImage();
    }

    /**
     * Возвращает путь к big аватар
     * @return string
     */
    public function getBig()
    {
        return $this->getImage();
    }
}
