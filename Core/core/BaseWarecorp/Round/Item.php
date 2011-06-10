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

class BaseWarecorp_Round_Item extends Warecorp_Data_Entity
{
    private $roundId;
    private $roundName;
    private $roundDescription;
    private $roundStart;
    private $roundEnd;
    private $roundStartDate;
    private $roundEndDate;
    private $roundTimezone;
    private $roundIsActive;
    private $roundIsCurrent;
    private $roundDocumentId;
    private $document_;
    private $roundGroupId;
    private $surveyEmbedCode;
    private $roundSalsaCompletedField;
    private $roundSalsaHostedEventField;
    private $roundSalsaParticipatedField;
    private $surveyLastSyncDate;
    private $surveySyncClosed;    
    private $salsaLastSyncDate;
    private $salsaSyncClosed;

    private $participationArray;

    /**
     * @return string $_salsaLastSyncDate
     */
    public function getSalsaLastSyncDate ()
    {
        return $this->salsaLastSyncDate;
    }

	/**
     * @return string $_salsaSyncClosed
     */
    public function getSalsaSyncClosed ()
    {
        return $this->salsaSyncClosed;
    }

	/**
     * @param string $_salsaLastSyncDate
     */
    public function setSalsaLastSyncDate ($salsaLastSyncDate)
    {
        $this->salsaLastSyncDate = $salsaLastSyncDate;
    }

    public function setSalsaSyncClosed ($salsaSyncClosed)
    {
        $this->salsaSyncClosed = $salsaSyncClosed;
    }
    
	public function setRoundId ( $value ) {
        $this->roundId = $value;
        return $this;
    }
    public function getRoundId () {
        return $this->roundId;
    }
    public function setRoundName ( $value ) {
        $this->roundName = $value;
        return $this;
    }
    public function getRoundName () {
        return $this->roundName;
    }
    public function setRoundDescription ( $value ) {
        $this->roundDescription = $value;
        return $this;
    }
    public function getRoundDescription () {
        return $this->roundDescription;
    }
    public function setRoundStart ( $value ) {
        $this->roundStartDate = null;
        $this->roundStart = $value;
        return $this;
    }
    public function getRoundStart () {
        return $this->roundStart;
    }
    public function setRoundEnd ( $value ) {
        $this->roundEndDate = null;
        $this->roundEnd = $value;
        return $this;
    }
    public function getRoundEnd () {
        return $this->roundEnd;
    }
    public function getRoundStartDate () {
        if ( null === $this->roundStartDate ) {
            $tz = date_default_timezone_get();
            date_default_timezone_set( $this->getRoundTimezone() );
            if ( null !== $this->roundStart ) {
                $this->roundStartDate = new Zend_Date($this->roundStart, Zend_Date::ISO_8601);
            } else {
                $this->roundStartDate = new Zend_Date();
            }
            date_default_timezone_set($tz);
        }
        return $this->roundStartDate;
    }
    public function getRoundEndDate () {
        if ( null === $this->roundEndDate ) {
            $tz = date_default_timezone_get();
            date_default_timezone_set( $this->getRoundTimezone() );
            if ( null !== $this->roundEnd ) {
                $this->roundEndDate = new Zend_Date($this->roundEnd, Zend_Date::ISO_8601);
            } else {
                $this->roundEndDate = new Zend_Date();
            }
            date_default_timezone_set($tz);
        }

        return $this->roundEndDate;
    }
    public function setRoundTimezone ( $value ) {
        $this->roundTimezone = $value;
        return $this;
    }
    public function getRoundTimezone () {
        if ( null === $this->roundTimezone ) {
            $cfgSite = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.site.xml');
            $this->roundTimezone = $cfgSite->time_zone;
        }
        return $this->roundTimezone;
    }
    public function setRoundIsActive ( $value ) {
        $this->roundIsActive = $value;
        return $this;
    }
    public function getRoundIsActive () {
        return $this->roundIsActive;
    }
    public function setRoundIsCurrent ( $value ) {
        $this->roundIsCurrent = $value;
        return $this;
    }
    public function getRoundIsCurrent () {
        return $this->roundIsCurrent;
    }
    public function setRoundDocumentId ( $value ) {
        $this->document_ = null;
        $this->roundDocumentId = $value;
        return $this;
    }
    public function getRoundDocumentId () {
        return $this->roundDocumentId;
    }
    public function getDocument() {
        if ( $this->roundDocumentId && null === $this->document_ ) {
            $this->document_ = new Warecorp_Document_Item($this->roundDocumentId);
        }
        if ( !$this->document_->getId() ) return null;
        return $this->document_;
    }
    public function setRoundGroupId ( $value ) {
        $this->roundGroupId = $value;
        return $this;
    }
    public function getRoundGroupId () {
        return $this->roundGroupId;
    }
    public function setSurveyEmbedCode ( $value ) {
        $this->surveyEmbedCode = $value;
        return $this;
    }
    public function getSurveyEmbedCode () {
        return $this->surveyEmbedCode;
    }
    public function setRoundSalsaCompletedField ( $value ) {
        $this->roundSalsaCompletedField = $value;
        return $this;
    }
    public function getRoundSalsaCompletedField () {
        return $this->roundSalsaCompletedField;
    }
    public function setRoundSalsaHostedEventField ( $value ) {
        $this->roundSalsaHostedEventField = $value;
        return $this;
    }
    public function getRoundSalsaHostedEventField () {
        return $this->roundSalsaHostedEventField;
    }
    public function setRoundSalsaParticipatedField ( $value ) {
        $this->roundSalsaParticipatedField = $value;
        return $this;
    }
    public function getRoundSalsaParticipatedField () {
        return $this->roundSalsaParticipatedField;
    }
    public function setSurveyLastSyncDate ( $value ) {
        $this->surveyLastSyncDate = $value;
        return $this;
    }
    public function getSurveyLastSyncDate () {
        return $this->surveyLastSyncDate;
    }
    public function setSurveySyncClosed ( $value ) {
        $this->surveySyncClosed = $value;
        return $this;
    }
    public function getSurveySyncClosed () {
        return $this->surveySyncClosed;
    }

    /**
     * Constructor.
     * @author Artem Sukharev
     */
    public function __construct($id = null) {
        parent::__construct('zanby_rounds');

        $this->addField('round_id', 'roundId');
        $this->addField('round_name', 'roundName');
        $this->addField('round_description', 'roundDescription');
        $this->addField('round_start', 'roundStart');
        $this->addField('round_end', 'roundEnd');
        $this->addField('round_timezone', 'roundTimezone');
        $this->addField('round_is_active', 'roundIsActive');
        $this->addField('round_is_current', 'roundIsCurrent');
        $this->addField('round_document_id', 'roundDocumentId');
        $this->addField('round_group_id', 'roundGroupId');
        $this->addField('survey_embed_code', 'surveyEmbedCode');
        $this->addField('round_salsa_completed_field', 'roundSalsaCompletedField');
        $this->addField('round_salsa_hosted_event_field', 'roundSalsaHostedEventField');
        $this->addField('round_salsa_participated_field', 'roundSalsaParticipatedField');
        $this->addField('survey_last_sync_date', 'surveyLastSyncDate');
        $this->addField('survey_sync_closed', 'surveySyncClosed');
        $this->addField('salsa_last_sync_date', 'salsaLastSyncDate');
        $this->addField('salsa_sync_closed', 'salsaSyncClosed');

        if ($id !== null){
            $this->pkColName = 'round_id';
            $this->load($id);
        }
    }

    static public function getPastRoundsWithParticipation($group,$user) {
        if ( !$group ) throw new Exception('Incorrect group ID');

        if ($group instanceof Warecorp_Group_Base) $group = $group->getId();

        if ( !$user ) throw new Exception('Incorrect user ID');

        if ($user instanceof Warecorp_User) $user = $user->getId();

        $dbConn = Zend_Registry::get("DB");
        $query = $dbConn->select()->from('zanby_rounds')->join('zanby_users__accounts','id = '.$user,'id')->joinLeft('zanby_rounds__participation','zanby_rounds__participation.round_id = zanby_rounds.round_id AND id = zanby_rounds__participation.user_id ','status')->order('zanby_rounds.round_id DESC');
        $query->where('round_group_id = ?', $group)->where('user_id = ? or user_id IS NULL', $user)->where('round_is_current = 0');
		return $dbConn->fetchAll($query,array(),Zend_Db::FETCH_OBJ);
    }

    /**
     *
     * @param int $groupID
     * @return Warecorp_Round_Item
     */
    static public function getCurrentRound( $group ) {
        
        if ( !$group ) throw new Exception('Incorrect group ID');

        if ($group instanceof Warecorp_Group_Base) $group = $group->getId();
        if (Warecorp::checkHttpContext('zccf')) {
            $dbConn = Zend_Registry::get("DB");
            $query = $dbConn->select();
            $query->from('zanby_rounds', array('round_id'));
            $query->where('round_is_current = ?', 1);
            $query->where('round_group_id = ?', $group);
            $roundId = $dbConn->fetchOne($query);
            if ( $roundId ) return new Warecorp_Round_Item($roundId);
        }
        $objRound = new Warecorp_Round_Item();
        $objRound->setRoundGroupId($group);
        return $objRound;
    }
    static public function getRoundsToSync( $group, $seconds = 1 ) {
        if ( !$group ) throw new Exception('Incorrect group ID');

        if ($group instanceof Warecorp_Group_Base) $group = $group->getId();

        $tz = date_default_timezone_get();
        date_default_timezone_set('UTC');
        $objDateNow = new Zend_Date();
        date_default_timezone_set($tz);
        $objDateFrom = clone $objDateNow;        
        $objDateFrom->sub($seconds, Zend_Date::SECOND);
        
        $dbConn = Zend_Registry::get("DB");
        $query = $dbConn->select();
		$query->from('zanby_rounds');
        $query->where('round_group_id = ?', $group);
        $query->where('survey_sync_closed = ?', 0);
        $query->where('(survey_last_sync_date IS NULL OR survey_last_sync_date <= ?)', $objDateFrom->toString('yyyy-MM-dd hh:mm:ss'));

		$rounds = $dbConn->fetchAll($query);
        if ( sizeof($rounds) == 0 ) return array();
        foreach ( $rounds as &$round ) $round = new Warecorp_Round_Item($round);

        return $rounds;
    }
    
    
    /**
     * Get round list for salsa sync
     * @param unknown_type $group
     * @param unknown_type $seconds
     * @throws Exception
     * @return multitype:|null
     */
    static public function getRoundsToSalsaSync( $group, $seconds = 1 ) 
    {
        if ( !$group ) throw new Exception('Incorrect group ID');
        if ($group instanceof Warecorp_Group_Base) $group = $group->getId();

        $tz = date_default_timezone_get();
        date_default_timezone_set('UTC');
        $objDateNow = new Zend_Date();
        $objDateNow->setTimezone('UTC');
        date_default_timezone_set($tz);
        $objDateFrom = clone $objDateNow;        
        $objDateFrom->sub($seconds, Zend_Date::SECOND);
        
        $dbConn = Zend_Registry::get("DB");
        $query = $dbConn->select();
		$query->from('zanby_rounds');
        $query->where('round_group_id = ?', $group);
        $query->where('salsa_sync_closed = ?', 0);
        $query->where('(salsa_last_sync_date IS NULL OR salsa_last_sync_date <= ?)', $objDateFrom->toString('yyyy-MM-dd HH:mm:ss'));

		$rounds = $dbConn->fetchAll($query);
        if ( sizeof($rounds) == 0 ) return array();
        foreach ( $rounds as &$round ) $round = new Warecorp_Round_Item($round);

        return $rounds;
    }
    
    
    public function getSurveyUidFromCode() {
        if ( !$this->getSurveyEmbedCode() ) return null;
        preg_match_all("/:\/\/www.surveygizmo.com\/s3\/js\/(\d*)\/([a-zA-Z0-9]*)\?/mi", $this->getSurveyEmbedCode(), $match);

        if ( !isset($match[1][0]) ) return null;
        return (int)$match[1][0];
    }
    public function appendParamsToSurveyCode( $params ) {
        if ( !$this->getSurveyEmbedCode() ) return false;
        preg_match_all("/:\/\/www.surveygizmo.com\/s3\/js\/(?:\d*)\/(?:[a-zA-Z0-9]*)\?/mi", $this->getSurveyEmbedCode(), $match);
        if ( !isset($match[0][0]) ) return false;

        $subURL = '';
        foreach ( $params as $key => $value ) $subURL .= $key.'='.urlencode($value)."&";
        $code = preg_replace("/:\/\/www.surveygizmo.com\/s3\/js\/(?:\d*)\/(?:[a-zA-Z0-9]*)\?/mi", "$0".$subURL, $this->getSurveyEmbedCode());

        $this->setSurveyEmbedCode($code);
        return true;
    }
    public function getFieldWithRequest( Zend_Controller_Request_Abstract $objRequest, $fieldName, $defaultValue ) {
        if ( $objRequest->has($fieldName) ) return $objRequest->getParam($fieldName);

        if ( $this->getRoundId() && isset($this->record[$fieldName]) ) {
            $propertyName = $this->record[$fieldName];
            return $this->getProperty($propertyName);
        }

        return $defaultValue;
    }

    /**
     *
     * @param Warecorp_User $user
     * @param int $status
     * @return Warecorp_Round_Item
     */
    public function saveParticipation(Warecorp_User $user, $status = 1) {
        if (!$user || !$user->getId() || !$this->getRoundId()) {
            return $this;
        }
        $dbConn = Zend_Registry::get("DB");
        
        $user_id = $dbConn->fetchOne($dbConn->select()->from('zanby_rounds__participation','user_id')->where('round_id = ?',$this->getRoundId())->where('user_id = ?',$user->getId()));
        if ($user_id) {
            $dbConn->update('zanby_rounds__participation',array('status'=>$status),'user_id = '.$user->getId().' AND round_id = '.$this->getRoundId());
        }else{
            $dbConn->insert('zanby_rounds__participation',array('user_id'=>$user->getId(),'round_id'=>$this->getRoundId(),'status'=>$status));
        }

        return $this;
    }

    /**
     *
     * @param Warecorp_User $user
     * @return boolean
     */
    public function getParticipation(Warecorp_User $user) {
        if (!$user || !$user->getId() || !$this->getRoundId()) {
            return 0;
        }
        $dbConn = Zend_Registry::get("DB");
        $result = $dbConn->fetchRow($dbConn->select()->from('zanby_rounds__participation')->where('round_id = ?',$this->getRoundId())->where('user_id = ?',$user->getId()));

        $return = isset($result['status']) ? $result['status'] : 0;
        
        return $return;
    }

}
