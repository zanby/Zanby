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
 * @package    Warecorp_Mail
 * @copyright  Copyright (c) 2006-2009
 */

/**
 *
 *
 */
class BaseWarecorp_Mail_Template extends Warecorp_Data_Entity {
    public static $_dbMailQueueTableName = 'zanby_mail__queue';

    public $idInQueue                   = null;
    public $id;
    public $templateKey;
    public $creatorId;
    public $changerId;
    public $createDate;
    public $changeDate;
    public $description;
    public $content;
    public $context;
    public $isFixed;
    public $isHidden;
    public $isDepricated;

    public $message;
    protected $deliverDate;
    private $onSendCallBackData        = array();
    private $beforeSendCallBackData    = array();

    protected $Creator                   = null;
    protected $Changer                   = null;
    protected $_SendToEmail              = true;             //  отправлять ли на мыло
    protected $_SendEmailTextPart        = false;            //  отправлять text часть мыла
    protected $_SendEmailHTMLPart        = true;             //  отправлять html часть мыла
    protected $_SendToPMB                = false;            //  отправлять ли в пмб

    protected $Sender                    = null;
    protected $SenderType                 = 1;
    protected $Recipients                = array();
    protected $RecipientIDs              = array();
    protected $Params                    = array();
    protected $headers                   = array();

    protected $httpContext;

    protected $_Attachments           =array();

    protected $_emailCharset = null; //useful for sending email only. defines charset to send message text. if null use default.

    /**
     * Constructor.
     * @param string $key - name of key for user load, range of id|template_key.
     * if null (default) - user data don't loading.
     * @param string $val - key value
     * @return void
     * @author Artem Sukharev
     */
    public function __construct($key = null, $val = null) {
        parent::__construct('zanby_mailtemplates__templates');

        $this->addField('id');
        $this->addField('template_key', 'templateKey');
        $this->addField('creator_id', 'creatorId');
        $this->addField('changer_id', 'changerId');
        $this->addField('creation_date','createDate');
        $this->addField('change_date','changeDate');
        $this->addField('description');
        $this->addField('content');
        $this->addField('context');
        $this->addField('is_fixed','isFixed');
        $this->addField('is_hidden','isHidden');
        $this->addField('is_depricated','isDepricated');

        if ($key == 'template_key' && $val !== null ) {
            $this->loadByTemplateKey($val);
        } elseif ( $key !== null && $val !== null ) {
            $this->pkColName = $key;
            $this->loadByPk($val);
        }
    }

    /**
     * @author Komarovski
     * Load mail template by key and context. If not template for current context specified - trying to load default template
     */
    public function loadByTemplateKey($pkValue) {
        if ( null === $pkValue ) return false;
        $sql = $this->_db->select()->from($this->tableName, '*')
            ->where('template_key =?', $pkValue)
            ->where('(context =? OR context = "")', HTTP_CONTEXT)
            ->order('context DESC')
            ->limit(1);

        $row = $this->_db->fetchRow($sql);

        if ($row) {
            $this->load($row);
        }
        return false;
    }

    /**
     * Set Creator for template
     * @return void
     * @author Artem Sukharev
     */
    public function setCreator() {
        $this->Creator = new Warecorp_User('id',$this->creatorId);
    }
    /**
     * Get Creator for template
     * @return obj - Warecorp_User
     * @author Artem Sukharev
     */
    public function getCreator() {
        if ( $this->Creator === null ) {
            $this->setCreator();
        }
        return $this->Creator;
    }
    /**
     * Set Changer for template
     * @author Artem Sukharev
     */
    public function setChanger() {
        $this->Changer = new Warecorp_User('id',$this->changerId);
    }
    /**
     * Get Changer for template
     * @return obj - Warecorp_User
     * @author Artem Sukharev
     */
    public function getChanger() {
        if ( $this->Changer === null ) {
            $this->setChanger();
        }
        return $this->Changer;
    }
    /**
     * Set Sender
     * @param obj $obj - Warecorp_Group_Standard || Warecorp_User
     * @return void
     * @author Artem Sukharev
     */
    public function setSender( $obj ) {
        if ( $obj instanceof Warecorp_Group_Standard || $obj instanceof Warecorp_User ) {
            $this->Sender = $obj;
            $this->SenderType = $obj->EntityTypeId;
        } else throw new Zend_Exception("Incorrect Sender Object!");
    }
    /**
     * Get Sender
     * @return obj $obj - Warecorp_Group_Standard || Warecorp_User
     * @author Artem Sukharev
     */
    public function getSender() {
        if ( $this->Sender === null ) throw new Zend_Exception("Sender is null!");
        return $this->Sender;
    }

    public function getSenderType() {
        return $this->getSender()->EntityTypeId;
    }

    /**
     * get http context for mail
     */
    public function getHttpContext() {
        return $this->httpContext;
    }

    /**
     * set http context for mail
     */
    public function setHttpContext($value) {
        $this->httpContext = $value;
        return $this;
    }

    /**
     *
     */
    public function clearRecipients() {
        $this->Recipients = array();
        $this->RecipientIDs = array();
    }
    /**
     * Add new Recipient
     * @param obj $obj - Warecorp_Group_Standard || Warecorp_User
     * @return void
     * @author Artem Sukharev
     */
    public function addRecipient( $obj ) {
        $exist = false;
        if ($obj instanceof Warecorp_User) {
            $email = $obj->getEmail();
            foreach ( $this->getRecipients() as $recipient ) {
                if (!(empty($email) && $obj->getId()!==$recipient->getId()) && ($recipient instanceof Warecorp_User) && ($recipient->getEmail()==$email)) {
                    $exist = true;
                    break;
                }
            }
        } elseif ($obj instanceof Warecorp_Group_Standard) {
            $email = $obj->getGroupEmail();
            foreach ( $this->getRecipients() as $recipient ) {
                if (!(empty($email) && $obj->getId()!==$recipient->getId()) && ($recipient instanceof Warecorp_Group_Standard) && ($recipient->getGroupEmail()==$email)) {
                    $exist = true;
                    break;
                }
            }
        } else throw new Zend_Exception("Incorrect Recipient Object!");
        if(false===$exist) {
            $this->Recipients[]     = $obj;
            $this->RecipientIDs[]   = $obj->getId();
        }
    }

    /**
     * Add Recipients from string of recipient separated by `,` (allowed: login, email of registered user, email of unregistered user)
     * @param string $strEmails - string of recipient separated by `,`
     * @param array $excludeIDs - User IDs
     * @return void
     * @author Artem Sukharev
     */
    public function addUserRecipientsFormString( $strEmails, $excludeIDs = null ) {
        $excludeIDs = ( $excludeIDs === null || !is_array($excludeIDs) ) ? array() : $excludeIDs;

        $split = preg_split("/,|\n/im",$strEmails);
        if ( sizeof($split) != 0 ) {
            foreach ( $split as $ind => $email ) {
                if ( trim($email) != "" ) {
                    $_user = str_replace("\r","",trim($email));
                    if ( Warecorp_User::isUserExists('login', $_user) ) {
                        $User = new Warecorp_User('login', $_user);
                        if ( !in_array($User->getId(), $excludeIDs) ) $this->addRecipient($User);
                    } elseif ( Warecorp_User::isUserExists('email', $_user) ) {
                        $User = new Warecorp_User('email', $_user);
                        if ( !in_array($User->getId(), $excludeIDs) ) $this->addRecipient($User);
                    } elseif ( $this->isValidEmailAddress($_user) ) {
                        $User = new Warecorp_User();
                        $User->setFirstname('Guest');
                        $User->setEmail($_user);
                        $this->addRecipient($User);
                    }
                }
            }
        }
    }

    public static function validateUserRecipientsFormString( $strEmails, $excludeIDs = null ) {
        $returns = array();
        $returns['valid']['users']       = array();
        $returns['valid']['guests']      = array();
        $returns['invalid']['emails']    = array();
        $returns['invalid']['nicknames'] = array();

        $excludeIDs = ( $excludeIDs === null || !is_array($excludeIDs) ) ? array() : $excludeIDs;
        $excludeEmails = array();

        $split = preg_split("/,|\n/im",$strEmails);
        if ( sizeof($split) != 0 ) {
            foreach ( $split as $ind => $email ) {
                if ( trim($email) != "" ) {
                    $_user = str_replace("\r","",trim($email));
                    if ( false === strpos($_user, "@") ) {
                        if (Warecorp_User::isUserExists('login', $_user)) {
                            $User = new Warecorp_User('login', $_user);
                            if (!in_array($User->getId(), $excludeIDs) && !in_array($User->getEmail(), $excludeEmails)) {
                                $returns['valid']['users'][] = $User;
                                $excludeEmails[] = $User->getEmail();
                            }
                        } else {
                            $returns['invalid']['nicknames'][] = $_user;
                        }
                    } else {
                        if (Warecorp_Mail_Template::validateEmailAddress($_user) && Warecorp_User::isUserExists('email', $_user) ) {
                            $User = new Warecorp_User('email', $_user);
                            if (!in_array($User->getId(), $excludeIDs) && !in_array($User->getEmail(), $excludeEmails)) {
                                $returns['valid']['users'][] = $User;
                                $excludeEmails[] = $User->getEmail();
                            }
                        } elseif ( Warecorp_Mail_Template::validateEmailAddress($_user) ) {
                            if (!in_array($_user, $excludeEmails)) {
                                $User = new Warecorp_User();
                                $User->setFirstname('Guest');
                                $User->setLogin('Guest');
                                $User->setEmail($_user);
                                $returns['valid']['guests'][] = $User;
                                $excludeEmails[] = $_user;
                            }
                        } else {
                            $returns['invalid']['emails'][] = $_user;
                        }
                    }
                }
            }
        }
        return $returns;
    }

    public static function validateRecipientsFormString(Warecorp_User $objUser, $strEmails, $excludeUserIDs = null, $excludeGroupIDs = null ) {
        $returns = array();
        $returns['isValid'] = true;
        $returns['valid']['users']              = array();
        $returns['valid']['guests']             = array();
        $returns['valid']['groups']             = array();
        $returns['valid']['contactLists']       = array();
        $returns['valid']['fbusers']            = array();

        $returns['invalid']['userEmails']       = array();
        $returns['invalid']['userNames']        = array();
        $returns['invalid']['groupEmails']      = array();
        $returns['invalid']['groupNames']       = array();
        $returns['invalid']['groupAccess']      = array();
        $returns['invalid']['contactListNames'] = array();

        $returns['info']['users']               = array();
        $returns['info']['guests']              = array();
        $returns['info']['groups']              = array();
        $returns['info']['contactLists']        = array();
        $returns['info']['fbusers']             = array();

        $excludeUserIDs         = ( $excludeUserIDs === null || !is_array($excludeUserIDs) ) ? array() : $excludeUserIDs;
        $excludeGroupIDs        = ( $excludeGroupIDs === null || !is_array($excludeGroupIDs) ) ? array() : $excludeGroupIDs;
        $excludeUserEmails      = array();
        $excludeGgroupEmails    = array();

        $split = preg_split("/,|\n/im",$strEmails);
        if ( sizeof($split) != 0 ) {
            foreach ( $split as $ind => $email ) {
                if ( trim($email) != "" ) {
                    $_name = str_replace("\r","",trim($email));
                    /**
                     * Typed string is not email address
                     */
                    if ( false === strpos($_name, "@") ) {
                        /**
                         * Type : Groupname[group]
                         */
                        if ( preg_match('/^(.*?)\[group\]$/i', $_name, $_match) ) {
                            $objGroup = Warecorp_Group_Factory::loadByName($_match[1]);
                            if ( null != $objGroup->getId() ) {
                                if ( !in_array($objGroup->getId(), $excludeGroupIDs) && !in_array($objGroup->getGroupEmail(), $excludeGgroupEmails) ) {
                                /**
                                 * If user is group member
                                 */
                                    if ( $objGroup->getMembers()->isMemberExistsAndApproved($objUser->getId()) ) {
                                        $returns['valid']['groups'][]   = $objGroup;
                                        $returns['info']['groups'][]    = $objGroup->getId();
                                        $excludeGgroupEmails[]          = $objGroup->getGroupEmail();
                                    } else {
                                        $returns['invalid']['groupAccess'][] = $objGroup->getName();
                                        $returns['isValid'] = false;
                                    }
                                }
                            } else {
                                $returns['invalid']['groupNames'][] = $_match[1];
                                $returns['isValid'] = false;
                            }
                        }
                        /**
                         * Type : Listname[list]
                         */
                        elseif ( preg_match('/^(.*?)\[list\]$/i', $_name, $_match) ) {
                            if ( false != ($listId = Warecorp_User_Addressbook_ContactList::isContactListExist($objUser->getId(), $_match[1])) ) {
                                $contactList = new Warecorp_User_Addressbook_ContactList(false, 'id', $listId);
                                $returns['valid']['contactLists'][]     = $contactList;
                                $returns['info']['contactLists'][]      = $contactList->getContactListId();
                            } else {
                                $returns['invalid']['contactListNames'][] = $_match[1];
                                $returns['isValid'] = false;
                            }
                        }
                        /**
                         * Type : Username
                         */
                        else {
                            if ( Warecorp_User::isUserExists('login', $_name) ) {
                                $User = new Warecorp_User('login', $_name);
                                if ( !in_array($User->getId(), $excludeUserIDs) && !in_array($User->getEmail(), $excludeUserEmails) ) {
                                    $returns['valid']['users'][]    = $User;
                                    $returns['info']['users'][]     = $User->getId();
                                    $excludeUserEmails[] = $User->getEmail();
                                }
                            } else {
                                $returns['invalid']['userNames'][] = $_name;
                                $returns['isValid'] = false;
                            }
                        }
                    }
                    /**
                     * Typed string is email address
                     */
                    else {
                        if ( Warecorp_Mail_Template::validateEmailAddress($_name) ) {
                            if ( preg_match('/@'.DOMAIN_FOR_GROUP_EMAIL.'$/i', $_name) ) {
                                $list = new Warecorp_Group_List();
                                if ( null !== ($objGroup = $list->findByEmail($_name)) ) {
                                    if ( !in_array($objGroup->getId(), $excludeGroupIDs) && !in_array($objGroup->getGroupEmail(), $excludeGgroupEmails) ) {
                                        /**
                                         * If user is group member
                                         */
                                        if ( $objGroup->getMembers()->isMemberExistsAndApproved($objUser->getId()) ) {
                                            $returns['valid']['groups'][]   = $objGroup;
                                            $returns['info']['groups'][]    = $objGroup->getId();
                                            $excludeGgroupEmails[] = $objGroup->getGroupEmail();
                                        } else {
                                            $returns['invalid']['groupAccess'][] = $objGroup->getName();
                                            $returns['isValid'] = false;
                                        }
                                    }
                                } else {
                                    $returns['invalid']['groupEmails'] = $_name;
                                    $returns['isValid'] = false;
                                }
                            } else {
                                if ( Warecorp_User::isUserExists('email', $_name) ) {
                                    $User = new Warecorp_User('email', $_name);
                                    if ( null !== $User->getId() ) {
                                        if ( !in_array($User->getId(), $excludeUserIDs) && !in_array($User->getEmail(), $excludeUserEmails) ) {
                                            $returns['valid']['users'][]    = $User;
                                            $returns['info']['users'][]     = $User->getId();
                                            $excludeUserEmails[] = $User->getEmail();
                                        }
                                    } else {
                                        $returns['invalid']['userEmails'][] = $_name;
                                    }
                                } else {
                                    if (!in_array($_name, $excludeUserEmails)) {
                                        $User = new Warecorp_User();
                                        $User->setFirstname('Guest');
                                        $User->setLogin('Guest');
                                        $User->setEmail($_name);
                                        $returns['valid']['guests'][]   = $User;
                                        $returns['info']['guests'][]    = $_name;
                                        $excludeUserEmails[] = $_name;
                                    }
                                }
                            }
                        } else {
                            $returns['invalid']['userEmails'][] = $_name;
                        }
                    }
                }
            }
        }
        return $returns;
    }

    /**
     * Check is string valid email address
     * @param string $value
     * @return bool
     * @author Ivan Meleshko
     */
    public static function validateEmailAddress($value) {
        $regex = '/^((\"[^\"\f\n\r\t\v\b]+\")|([\w\!\#\$\%\&\'\*\+\-\~\/\^\`\|\{\}]+(\.[\w\!\#\$\%\&\'\*\+\-\~\/\^\`\|\{\}]+)*))@((\[(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))\])|(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))|((([A-Za-z0-9\-])+\.)+[A-Za-z\-]+))$/';
        if ( preg_match($regex, $value) ) {
            if (function_exists('checkdnsrr')) {
                $tokens = explode('@', $value);
                if (!(checkdnsrr($tokens[1], 'MX') || checkdnsrr($tokens[1], 'A'))) {
                    return false;
                }
            }
        } else {
            return false;
        }
        return true;
    }


    /**
     * Check is string valid email address
     * @param string $value
     * @return bool
     */
    private function isValidEmailAddress($value) {
        $regex = '/^((\"[^\"\f\n\r\t\v\b]+\")|([\w\!\#\$\%\&\'\*\+\-\~\/\^\`\|\{\}]+(\.[\w\!\#\$\%\&\'\*\+\-\~\/\^\`\|\{\}]+)*))@((\[(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))\])|(((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9]))\.((25[0-5])|(2[0-4][0-9])|([0-1]?[0-9]?[0-9])))|((([A-Za-z0-9\-])+\.)+[A-Za-z\-]+))$/';
        if ( preg_match($regex, $value) ) {
            if (function_exists('checkdnsrr')) {
                $tokens = explode('@', $value);
                if (!(checkdnsrr($tokens[1], 'MX') || checkdnsrr($tokens[1], 'A'))) {
                    return false;
                }
            }
        } else {
            return false;
        }
        return true;
    }

    /**
     * This method checks only match the pattern of email group email
     * @author Eugene Kirdzei
     */
    public static function isThisGroupEmail ( $value ) {
        if ( self::validateEmailAddress( $value ) ) {
            $tokens = explode('@', $value);
            if ( $tokens[1] !== DOMAIN_FOR_GROUP_EMAIL ) {
                return false;
            } else {
                $group = Warecorp_Group_Factory::loadByPath( $tokens[0] );
                return $group->getId();
            }
        } else {
            return false;
        }
        return true;
    }

    /**
     * Get Recipients
     * @return array of objects (Warecorp_Group_Standard || Warecorp_User)
     * @author Artem Sukharev
     */
    public function getRecipients() {
        return $this->Recipients;
    }
    /**
     * Get Recipients IDs
     * @return array of int
     * @author Artem Sukharev
     */
    public function getRecipientIDs() {
        return $this->RecipientIDs;
    }
    /**
     * Add param for smarty template
     * @param string $key
     * @param string $value
     * @author Artem Sukharev
     */
    public function addParam( $key, $value ) {
        $this->Params[$key] = $value;
    }
    /**
     * Return all params for smarty template
     * @return array
     * @author Artem Sukharev
     */
    public function getParams() {
        return $this->Params;
    }

    /**
     * add aditional headers to email
     */
    public function setHeader($name, $value) {
        $this->headers[$name] = $value;
    }

    public function getHeaders() {
        return $this->headers;
    }
    
    /**
     * Enter description here...
     * @param bool $bool
     * @author Artem Sukharev
     */
    public function sendToEmail( $bool ) {
        $this->_SendToEmail = (bool) $bool;
    }
    /**
     * Enter description here...
     * @param bool $bool
     * @author Artem Sukharev
     */
    public function sendEmailTextPart( $bool ) {
        $this->_SendEmailTextPart = (bool) $bool;
    }
    /**
     * Enter description here...
     * @param bool $bool
     * @author Artem Sukharev
     */
    public function sendEmailHTMLPart( $bool ) {
        $this->_SendEmailHTMLPart = (bool) $bool;
    }
    /**
     * Enter description here...
     * @param bool $bool
     * @author Artem Sukharev
     */
    public function sendToPMB( $bool ) {
        $this->_SendToPMB = (bool) $bool;
    }

    public function getDeliverDate() {
        return $this->deliverDate;
    }

    public function setDeliverDate($newVal, $timezone = 'UTC') {
        $defaultTimeZone = date_default_timezone_get();

        date_default_timezone_set($timezone);
        $objNowDate = new Zend_Date($newVal, Zend_Date::ISO_8601);
        $objNowDate->setTimezone( 'UTC' );
        $this->deliverDate = $objNowDate->toString('yyyy-MM-dd HH:mm:ss');

        date_default_timezone_set($defaultTimeZone);
        return $this;
    }

    protected function putToQueue() {
        $serObj = base64_encode(serialize($this));
        $data['object'] = $serObj;
        if ( null !== $this->getHttpContext() ) {
            $data['context'] = $this->getHttpContext();
        } else {
            $data['context'] = HTTP_CONTEXT;
        }
        $data['status'] = 'pending';
        if ($this->getDeliverDate() !== null) {

            $data['deliver_date'] = $this->getDeliverDate();
        }
        $this->_db->insert(self::$_dbMailQueueTableName, $data);
        $this->idInQueue = $this->_db->lastInsertId();
    }

    public static function getFirstFromQueue() {
        $_db = Zend_Registry::get('DB');

        $defaultTimeZone = date_default_timezone_get();
        date_default_timezone_set('UTC');
        $now = new Zend_Date();
        $nowStr = $now->toString('yyyy-MM-dd HH:mm:ss');
        date_default_timezone_set($defaultTimeZone);

        $lockQuery = 'SELECT GET_LOCK("'.HTTP_CONTEXT.'lock", 1) from zanby_mail__queue where context="'.HTTP_CONTEXT.'"';
        $query = $_db->select()->from(self::$_dbMailQueueTableName, array('id', 'object'))
            ->where('context = ?', HTTP_CONTEXT)
            ->where('(deliver_date is null or deliver_date <= ?)', $nowStr)
            ->where('status = ?', 'pending')
            ->limitPage(1,5);
        //$query = "select id, object from ".self::$_dbMailQueueTableName;

        $lockOk = $_db->fetchOne($lockQuery);
        if ($lockOk === '0') return -2;
        if (!$lockOk) return -1;
        $row = $_db->fetchPairs($query);
        if (!empty($row)) {
            return $row;
        } else return false;
    }

    public function setStatusInQueue($status) {
        if (!empty($this->idInQueue)) {
            $field['status'] = $status;
            $where = $this->_db->quoteInto('id = ?', $this->idInQueue);
            $this->_db->update(self::$_dbMailQueueTableName, $field, $where);
        }
    }

    public function deleteFromQueue() {
        if (!empty($this->idInQueue)) {
            $where[] = $this->_db->quoteInto('id = ?', $this->idInQueue);
            $this->_db->delete(self::$_dbMailQueueTableName, $where);
        }
    //$this->_db->query('select RELEASE_LOCK("'.HTTP_CONTEXT.'lock")');
    }

    public static function releaseLock() {
        $_db = Zend_Registry::get('DB');
        $_db->query('select RELEASE_LOCK("'.HTTP_CONTEXT.'lock")');
    }
   
    /**
     * Send message to pmb or/and email
     * @see $this->setSender()
     * @see $this->addRecipient()
     * @author Artem Sukharev
     * @todo не работает в случае, если sender - группа, т.к. в базе есть кей на пользователя - уточнить у Alexey Loshkarev
     * @todo рассмотреть возможность использования типа maillist
     */
    public function send($toQueue = true) {
        if ( USE_MAIL_QUEUE ) {
            if ($toQueue) { $this->putToQueue(); return; }
        }

        if ($this->beforeSend() === false) return;
        $returnEmailParams = false;
        if ( sizeof($this->getRecipients()) == 0 ) return $returnEmailParams;

        if ( true == $this->_SendToEmail) {
            require_once(ENGINE_DIR.'/htmlMimeMail5/htmlMimeMail5.php');
            $mail = new htmlMimeMail5();
            $mail->setTextCharset("UTF-8");
            $mail->setHTMLCharset("UTF-8");
            $mail->setHeadCharset("UTF-8");

            if ( sizeof($this->headers) != 0 ) {
                foreach ( $this->headers as $_hName => $_hValue ) $mail->setHeader($_hName, $_hValue);
            }

            $cfgSite = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.site.xml');
            $cfgInstance = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.instance.xml');

            if ( isset($cfgInstance->smtp_method) && $cfgInstance->smtp_method == 'smtp' ) {
                $timeout                = ( isset($cfgInstance->smtp_timeout) ) ? $cfgInstance->smtp_timeout : 5;
                $socket_set_timeout     = ( isset($cfgInstance->socket_set_timeout) ) ? $cfgInstance->socket_set_timeout : 5;
                $mail->setSMTPParams($cfgInstance->smtp_host, $cfgInstance->smtp_port, null, null, null, null, $timeout, $socket_set_timeout);
                $send_method = 'smtp';
            } else {
                $send_method = 'mail';
            //                $cfgInstance->smtp_method = 'mail';
            }
        }

        require_once 'Smarty/Smarty.class.php';
        $smarty = new Smarty();
        $smarty->compile_dir = APP_VAR_DIR.'/_compiled/site/';
        $smarty->register_resource( "tpl", array( $this, "get_source", "get_timestamp", "get_secure", "get_trusted"));

        $smarty->assign('BASE_HTTP_HOST',               BASE_HTTP_HOST);
        $smarty->assign('BASE_URL',                     BASE_URL);
        $smarty->assign('BASE_URL_SECURE',              BASE_URL_SECURE);
        $smarty->assign('SITE_NAME_AS_STRING',          SITE_NAME_AS_STRING);
        $smarty->assign('SITE_NAME_AS_DOMAIN',          SITE_NAME_AS_DOMAIN);
        $smarty->assign('SITE_NAME_AS_FULL_DOMAIN',     SITE_NAME_AS_FULL_DOMAIN);
        $smarty->assign('DOMAIN_FOR_EMAIL',             DOMAIN_FOR_EMAIL);
        $smarty->assign('DOMAIN_FOR_GROUP_EMAIL',       DOMAIN_FOR_GROUP_EMAIL);

        /**
         * Usually LOCALE is defined when php is running from command line and Warecorp class is not initialized
         * else : php is running from CGI so Warecorp class is initialized. Using its locale.
         */
        if ( !defined('LOCALE') ) {
            if ( Warecorp::$locale && Warecorp::$locale != 'LOCALE' ) define('LOCALE', Warecorp::$locale);                
            else define('LOCALE', 'en');
        }
        $smarty->assign('LOCALE', LOCALE);

        if ( sizeof($this->getParams()) != 0 ) $smarty->assign($this->getParams());

        $sender = $this->getSender();
        if ( $sender instanceof Warecorp_User )                         $sender->Type = 'user';
        elseif ( $sender instanceof Warecorp_Group_Standard )           $sender->Type = 'group';
        else throw new Zend_Exception('Incorrect Sender Type');

        foreach ( $this->getRecipients() as $recipient ) {
            if ( $recipient instanceof Warecorp_User )                  $recipient->Type = 'user';
            elseif ( $recipient instanceof Warecorp_Group_Standard )    $recipient->Type = 'group';
            else throw new Zend_Exception('Incorrect Recipient Type!');

            $smarty->clear_assign('recipient');
            $smarty->assign('recipient',    $recipient);
            $smarty->assign('sender',       $sender);

            $smarty->assign('PrivacyLink', 'http://'.BASE_HTTP_HOST.'/'.LOCALE.'/info/privacy/');
            $smarty->assign('SenderLink', '');          //  FIXME заменить на верный
            if ( $recipient instanceof Warecorp_User ) {
                $smarty->assign('UnsubscribeLink', $recipient->getUserPath('settings/'));
            } else {
                $smarty->assign('UnsubscribeLink', $recipient->getGroupPath('settings/visible/deletegroup'));
            }

            $smarty->fetch('tpl:'.$this->templateKey);

            $tmpReturnEmailParams = array();
            if ( isset($smarty->_smarty_vars['capture']['_mail_text_part_']) )    $tmpReturnEmailParams['text']         = $smarty->_smarty_vars['capture']['_mail_text_part_'];
            if ( isset($smarty->_smarty_vars['capture']['_mail_html_part_']) )    $tmpReturnEmailParams['html']         = $smarty->_smarty_vars['capture']['_mail_html_part_'];
            if ( isset($smarty->_smarty_vars['capture']['_pmb_part_']) )          $tmpReturnEmailParams['pmb']          = $smarty->_smarty_vars['capture']['_pmb_part_'];
            if ( isset($smarty->_smarty_vars['capture']['_discussion_part_']) )   $tmpReturnEmailParams['discussion']   = $smarty->_smarty_vars['capture']['_discussion_part_'];

			/*
			 * Send message to pmb
			 */
            if ( true == $this->_SendToPMB ) {
                if ( $recipient->getId() !== null) {    //  сообщение в PMB только зарегестрированным пользователям (или группам)
                    $pmb_subject = trim($smarty->_smarty_vars['capture']['_subject_']);
                    $pmb_subject = str_replace(array("\n", "\r"), "", $pmb_subject);                    
                    $message = new Warecorp_Message_Standard();
                    $message->setSenderId($sender->getId());
                    $message->setSenderType($sender->EntityTypeId);
                    
                    $message->setOwnerId($recipient->getId());                    
                    $message->setFolder(Warecorp_Message_eFolders::INBOX);
                    $message->setIsRead(0);
                    $message->setSubject($pmb_subject);
                    $string = $message->arrayToString($this->getRecipientIDs());
                    $message->setRecipientsListFromStringId($string);                    
                    $message->setBody($smarty->_smarty_vars['capture']['_pmb_part_']);
                    $message->save(false);
                    $this->message = $message->getId();
                }
            }
			/*
			 * Send message to email
			 */
            if ( true == $this->_SendToEmail) {
            /**
             * Set To :
             */
                if ( $recipient instanceof Warecorp_User ) {
                    if ( null !== $recipient->getId() ) {
                        $to = $recipient->getEmail();
                    //$to = '"'.$recipient->getFirstName().' '.$recipient->getLastName().'" <'.$recipient->getEmail().'>';
                    } else {
                        $to = $recipient->getEmail();
                    }
                } elseif ( $recipient instanceof Warecorp_Group_Standard )      $to = $recipient->getGroupEmail();
                else throw new Zend_Exception('Incorrect Recipient Type!');
                /**
                 * Set From :
                 */
                $from = trim($smarty->_smarty_vars['capture']['_from_']);
                $from = str_replace(array("\n", "\r"), "", $from);
                if (isset($smarty->_smarty_vars['capture']['_reply_'])) {
                    $reply = trim($smarty->_smarty_vars['capture']['_reply_']);
                    $reply = str_replace(array("\n", "\r"), "", $reply);
                    if ( !isset($this->headers['Reply-To']) ) $mail->setHeader('Reply-To', $reply);
                } else {
                    if ( !isset($this->headers['Reply-To']) ) $mail->setHeader('Reply-To', $from);
                }
                /**
                 * Set Subject :
                 */
                $subject    = trim($smarty->_smarty_vars['capture']['_subject_']);
                $subject    = str_replace(array("\n", "\r"), "", $subject);

                $mail->setText('');
                $mail->setHTML('');
                if ( !isset($this->headers['From']) ) $mail->setFrom($from);
                $mail->setSubject($subject);

                /**
                 * Build Message Date
                 */
                $defaultTimezone = date_default_timezone_get();
                if ( $sender instanceof Warecorp_User ) {
                    date_default_timezone_set($sender->getTimezone());
                } else {
                    date_default_timezone_set('UTC');
                }
                $objDateNow = new Zend_Date();
                $mail->setHeader('Date', $objDateNow->get(Zend_Date::RFC_2822));
                date_default_timezone_set($defaultTimezone);
                /**
                 * Sender and Reply-To
                 */
                if ( !isset($this->headers['Sender']) ) $mail->setHeader('Sender', $from);
                //if ( !isset($this->headers['From']) ) $mail->setHeader('From', $from); else $mail->setHeader('From', $this->headers['From']);
                //if ( !isset($this->headers['Reply-To']) ) $mail->setHeader('Reply-To', $reply);


                //set additional headers
                if (isset($smarty->_smarty_vars['capture']['_to_']))
                //$mail->setHeader('To', $smarty->_smarty_vars['capture']['_to_']);
                    $to = trim($smarty->_smarty_vars['capture']['_to_']);


                if (isset($smarty->_smarty_vars['capture']['_date_'])) $mail->setHeader('Date', $smarty->_smarty_vars['capture']['_date_']);

                if ( empty($smarty->_smarty_vars['capture']['_mail_html_part_']) ) {
                    if ($this->_SendEmailHTMLPart == true || $this->_SendEmailTextPart == true) {
                        $this->_SendEmailHTMLPart = false;
                        $this->_SendEmailTextPart = true;
                    }
                }
                if ( true == $this->_SendEmailTextPart ) $mail->setText($smarty->_smarty_vars['capture']['_mail_text_part_']);
                if ( true == $this->_SendEmailHTMLPart ) $mail->setHTML($smarty->_smarty_vars['capture']['_mail_html_part_']);

                if ( count($this->_Attachments) ) {
                    foreach ($this->_Attachments as $attach) {
                        $mail->addAttachment(new stringAttachment(file_get_contents(ATTACHMENT_DIR.md5($attach->id).'.file'), $attach->originalName, $attach->mimeType) );
                    }
                }

                $tmpReturnEmailParams['to']         = $to;
                $tmpReturnEmailParams['from']       = $from;
                $tmpReturnEmailParams['subject']    = $subject;

                //  @todo убрать проверку, это чтобы не отправлялись реальные мыла в нет

                if ($cfgInstance->smtp_method == 'smtp') {
                    $mail->send(array($to), $cfgInstance->smtp_method, true);
                } else {
                //$to = 'yury.zolotarsky@warecorp.com';
                    $to = preg_replace("/@(.*?)$/mi","@testing.zanby.buick",$to);
                    $mail->send(array($to), $cfgInstance->smtp_method, true);
                }
                // Log
                //************************
                if ( isset($cfgInstance->logEmails) && 'on' == strtolower($cfgInstance->logEmails) ) {
                    if ( !file_exists(APP_VAR_DIR.DIRECTORY_SEPARATOR.'logs'.DIRECTORY_SEPARATOR.'mails_log') ) { mkdir(APP_VAR_DIR.DIRECTORY_SEPARATOR.'logs'.DIRECTORY_SEPARATOR.'mails_log'); chmod(APP_VAR_DIR.DIRECTORY_SEPARATOR.'logs'.DIRECTORY_SEPARATOR.'mails_log', 0777); }
                    $filename = APP_VAR_DIR.DIRECTORY_SEPARATOR.'logs'.DIRECTORY_SEPARATOR.'mails_log/mail_log_'.time().'_'.$this->templateKey.'_'.$to.'.txt';
                    $handle = fopen($filename, 'a');
                    if ( $handle ) fwrite($handle, $mail->getRFC822(array($to), $cfgInstance->smtp_method, true));
                    fclose($handle);
                    chmod($filename, 0777);
                }
            //************************
            //  End Log
            }
            $returnEmailParams[] = $tmpReturnEmailParams;
        }

        //return $returnEmailParams;
        $this->onSend($returnEmailParams);
        return $returnEmailParams;
    }

    public function setCallBackData($data) {
        if (empty($data['controller']) || empty($data['action'])) return $this;
        $this->onSendCallBackData = $data;
        return $this;
    }

    public function getCallBackData() {
        return $this->onSendCallBackData;
    }

    protected function onSend($returnEmailParams) {
        if (empty($this->onSendCallBackData['controller']) || empty($this->onSendCallBackData['action'])) return $returnEmailParams;
        if (empty($this->onSendCallBackData['params'])) $this->onSendCallBackData['params'] = array();
        require_once(MODULES_DIR.DIRECTORY_SEPARATOR.$this->onSendCallBackData['controller'].'Controller.php');
        call_user_func(array($this->onSendCallBackData['controller'].'Controller', $this->onSendCallBackData['action']), $this, $returnEmailParams, $this->onSendCallBackData['params']);
    }

    public function setBeforeSendCallBackData($data) {
        if (empty($data['controller']) || empty($data['action'])) return $this;
        $this->beforeSendCallBackData = $data;
        return $this;
    }

    public function getBeforeSendCallBackData() {
        return $this->beforeSendCallBackData;
    }

    protected function beforeSend() {
        if (empty($this->beforeSendCallBackData['controller']) || empty($this->beforeSendCallBackData['action'])) return true;
        if (empty($this->beforeSendCallBackData['params'])) $this->beforeSendCallBackData['params'] = array();
        require_once(MODULES_DIR.'/'.strtolower($this->beforeSendCallBackData['controller']).'/'.$this->beforeSendCallBackData['controller'].'Controller.php');
        return call_user_func(array($this->beforeSendCallBackData['controller'].'Controller', $this->beforeSendCallBackData['action']), &$this, $this->beforeSendCallBackData['params']);
    }
    /**
     * Function for smarty resource
     * @param string $tpl_name
     * @param string $tpl_source
     * @param obj $smarty
     * @return bool
     * @see Smarty documentation
     * @author Artem Sukharev
     */
    public function get_source($tpl_name, &$tpl_source, &$smarty) {
        $tpl_source = $this->content;
        return true;
    }
    /**
     * Function for smarty resource
     * @param string $tpl_name
     * @param string $tpl_timestamp
     * @param obj $smarty
     * @return bool
     * @see Smarty documentation
     * @author Artem Sukharev
     */
    public function get_timestamp($tpl_name, &$tpl_timestamp, &$smarty) {
    // @todo изменить на дату
    //	    if ( preg_match_all('/^([0-9]{4})-([0-9]{2})-([0-9]{2})\s([0-9]{2}):([0-9]{2}):([0-9]{2})$/mi', $this->changeDate, $match) ) {
    //	       $tpl_timestamp = gmmktime($match[4][0], $match[5][0], $match[6][0], $match[2][0], $match[3][0], $match[1][0]);
    //	    } else {
    //	       $tpl_timestamp = time();
    //	    }
        $tpl_timestamp = time();
        return true;
    }
    /**
     * Function for smarty resource
     * @param string $tpl_name
     * @param obj $smarty
     * @return bool
     * @see Smarty documentation
     * @author Artem Sukharev
     */
    public function get_secure($tpl_name, &$smarty) {
        return true;
    }
    /**
     * Function for smarty resource
     * @param string $tpl_name
     * @param obj $smarty
     * @see Smarty documentation
     * @author Artem Sukharev
     */
    public function get_trusted($tpl_name, &$smarty) {
    }
    /**
     * Send template-based message to PMB
     * @param string $templateFile - message template (with trailing '.tpl')
     * @param enum('user','group','maillist') $senderType
     * @param integer $senderId
     * @param array $recipients - recipients IDs list
     * @param array $params - parameters for template
     * @param boolean $saveSent - is there need to save copy in sender's "sent" folder
     * @author Alexey Loshkarev
     *
     * @deprecated see $this->send()
     */
    public static function sendPrivateMessage($templateFile, $senderType, $senderId, $recipientType = false, $recipients, $params, $saveSent = false) {
        /*
		if ( is_array($recipients) && count($recipients) ) {

			// @todo or inherits Warecorp_View_Smarty ?

			$smarty = new Smarty();
			$smarty->compile_dir        = DOC_ROOT.'/../var/_compiled/site/';
			$smarty->template_dir       = DOC_ROOT.'/../templates/_messages/';

			foreach($params as $key=>$value) {
				$smarty->assign($key, $value);
			}

			$first = true;
			foreach($recipients as $recipientId) {
				$message = new Warecorp_Message();
				$message->senderType        = $senderType;
				$message->senderId          = $senderId;
				// @todo - change to apropriate constant (with translation)
				$message->title             = "Zanby message";
				$message->isRead            = 0;
				$message->isReply           = 0;
				$message->isDelete          = 0;
				$message->isDraft           = 0;

				$message->recipientsList = $recipients;
				$message->recipientId = $recipientId;

				$message->message = $smarty->fetch($templateFile);
				// @todo - exchange with NOW()
				$message->date = strftime('%Y-%m-%d %H:%M:%S', time());

				// this code must be run only once per sending
				if ( $first && $saveSent ) {
					$messageOut = clone $message;
					$messageOut->recipientId = NULL;
					$messageOut->save();
					$first = false;
				}
				$message->save();
			}
			return $message->id;
		}
		else {
			return false;
		}
        */
    }

    /**
     * Send template-based message to email
     * @param string $templateFile - message template (with trailing '.tpl')
     * @param enum('user','group','maillist') $senderType
     * @param integer $senderId
     * @param array $recipients - recipients emails
     * @param array $params - parameters for template
     * @param boolean $saveSent - is there need to save copy in sender's "sent" folder
     * @author Alexey Loshkarev
     * @todo - send 1 message for each email (default) or 1 message to all (cc, bcc) ?
     * @todo - can group/maillist mail something? I think yes
     * @todo - content/type? now - text/html
     *
     * @deprecated see $this->send()
     */
    public static function sendEmail($templateFile, $senderType, $senderId, $recipients, $params, $saveSent) {
        /*
		// 98% copy of sendPriveMessage()
		if ((is_array($recipients)) && (count($recipients))) {
			// @todo or inherits Warecorp_View_Smarty ?
			$smarty                 = new Smarty();
			$smarty->template_dir   = DOC_ROOT.'/../templates/_messages/';
			$smarty->compile_dir    = DOC_ROOT.'/../var/_compiled/site/';
			foreach($params as $key=>$value) {
				$smarty->assign($key, $value);
			}
			$first = true;
			foreach($recipients as $recipientId) {
				$message = new Warecorp_Message();
				$message->senderType = $senderType;
				$message->senderId = $senderId;
				// @todo - change to apropriate constant (with translation)
				$message->title = "Zanby message";
				$message->isRead = 0;
				$message->isReply = 0;
				$message->isDelete = 0;
				$message->isDraft = 0;

				$message->recipientsList = $recipients;
				$message->recipientId = $recipientId;

				$message->message = $smarty->fetch($templateFile);
				//dump($message->message);
				// @todo - exchange with NOW()
				$message->date = strftime('%Y-%m-%d %H:%M:%S', time());

				if (($first) && ($saveSent)) {
					$messageOut = clone $message;
					$messageOut->recipientId = NULL;
					$messageOut->save();
					$first = false;
				}
				//$message->save();
			}
			return $message->id;
		} else {
			return false;
		}
        */
    }
    /**
     * set attachments
     *
     * @param $attachments - array of Warecorp_Data_AttachmentFile
     */

    public function setAttachments($attachments) {
        $this->_Attachments = $attachments;
    }

    /**
     * Set new character set for sending email message text.
     *
     * @param string $newCharacterSet new value of character set.
     * Use null to send message in default character set.
     */
    public function setEmailCharset($newCharacterSet) {
        $this->_emailCharset = $newCharacterSet;
    }
}
