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

class BaseWarecorp_Round_Survey extends Warecorp_Data_Entity
{
    private $surveyId;
    private $surveyRedirectUrl;
    private $surveyGroupId;

    public function setSurveyId ( $value ) {
        $this->surveyId = $value;
        return $this;
    }
    public function getSurveyId () {
        return $this->surveyId;
    }
    public function setSurveyRedirectUrl ( $value ) {
        $this->surveyRedirectUrl = $value;
        return $this;
    }
    public function getSurveyRedirectUrl () {
        return $this->surveyRedirectUrl;
    }
    public function setSurveyGroupId ( $value ) {
        $this->surveyGroupId = $value;
        return $this;
    }
    public function getSurveyGroupId () {
        return $this->surveyGroupId;
    }

    /**
     * Constructor.
     * @author Artem Sukharev
     */
    public function __construct($id = null) {
        parent::__construct('zanby_rounds__survey');

        $this->addField('survey_id', 'surveyId');
        $this->addField('survey_redirect_url', 'surveyRedirectUrl');
        $this->addField('survey_group_id', 'surveyGroupId');

        if ($id !== null){
            $this->pkColName = 'survey_id';
            $this->load($id);
        }
    }
    static public function getSurvey($groupID) {
        if ( !$groupID ) throw new Exception('Incorrect group ID');

        $dbConn = Zend_Registry::get("DB");
        $query = $dbConn->select();
		$query->from('zanby_rounds__survey', array('survey_id'));
        $query->where('survey_group_id = ?', $groupID);
		$surveyId = $dbConn->fetchOne($query);
        if ( $surveyId ) return new Warecorp_Round_Survey($surveyId);

        $objSurvey = new Warecorp_Round_Survey();
        $objSurvey->setSurveyGroupId($groupID);
        return $objSurvey;
    }
    public function getFieldWithRequest( Zend_Controller_Request_Abstract $objRequest, $fieldName, $defaultValue ) {
        if ( $objRequest->has($fieldName) ) return $objRequest->getParam($fieldName);

        if ( $this->getSurveyId() && isset($this->record[$fieldName]) ) {
            $propertyName = $this->record[$fieldName];
            return $this->getProperty($propertyName);
        }

        return $defaultValue;
    }
    public function checkRedirectUrl() {
        if (null === $this->getSurveyRedirectUrl() || '' === trim($this->getSurveyRedirectUrl())) return false;
        $handle   = curl_init($this->getSurveyRedirectUrl());
        if (false == $handle) return false;

        curl_setopt($handle, CURLOPT_HEADER, true);
        curl_setopt($handle, CURLOPT_NOBODY, true);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_TIMEOUT, 10);

        $connectable = curl_exec($handle);
        $info = curl_getinfo($handle,CURLINFO_HTTP_CODE);
        curl_close($handle);
        return $connectable && $info != 404;
    }
}
