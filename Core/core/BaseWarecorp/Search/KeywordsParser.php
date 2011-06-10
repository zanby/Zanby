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
 * Search class
 * @package Warecorp_Search
 * @author Dmitry Kostikov
 */

class BaseWarecorp_Search_KeywordsParser
{
    protected static $searchPrefixArray = array('country', 'state', 'city', 'tag', 'category', 'age', 'where', 'when', 'list_type');
    private $keywords = null;
    private $results = array();
    private $keywordsArray = array();
    private $currentPosition = 0;
    private $searchMode = null;
    private $originalKeywords = "";


    public function __construct($keyword, $searchMode)
    {
        $this->keywords = strip_tags($keyword);
        $this->originalKeywords = strip_tags($keyword);
        $this->searchMode = $searchMode;
        $this->results['keywords'] = '';
    }

    public function getOriginalKeywords(){
        return $this->originalKeywords;
    }

    private function setCurrentPosition($position)
    {
        $this->currentPosition = $position;
    }

    private function getCurrentPosition()
    {
        return $this->currentPosition;
    }


    public function parseKeywordsToArray()
    {
        $this->keywords = preg_replace('/(?<=\w)\s*:\s*(?=([\'"]?)[\w\d,-][\w\d\s,-]*\1)/', ':', $this->keywords);
        $this->keywordsArray = explode(' ', $this->keywords);
        $keywordsArrayLength = count($this->keywordsArray);
        for ($index = 0; $index < $keywordsArrayLength; $index++ ){
            if ( strrpos( $this->keywordsArray[$index], ':') != 0 ){
                list($pattern, $value) = explode(':', $this->keywordsArray[$index]);
                $pattern = self::clearSingleQuote($pattern);
                if ( self::isPatternExists($pattern) ) {
                    $patternValue = $this->getValueForPattern($value, $index);
                    $patternValue = self::clearSingleQuote($patternValue); 
                    $this->processPattern($pattern, $patternValue);
                    $index = $this->getCurrentPosition();
                }
                else {
                    $this->results['keywords'] .=' '.$this->keywordsArray[$index];
                }
            }
            else{
                    $this->results['keywords'] .=' '.$this->keywordsArray[$index];
            }
        }
        return $this->results;
    }

    protected static function clearSingleQuote ($input) {
            return str_replace(array('"', "'"), '', $input);
    }
    
    private function processPattern( $pattern, $value )
    {
        $pattern = strtolower($pattern);
        if (!self::isPatternExists($pattern))
            return false;

        switch ($pattern)
        {
            case 'city':
                if ($this->searchMode == 'members'){
                    $classCity = self::findTopUsersCitiesByName($value);
                    if ($classCity !== null){
                        $this->results['city'] = array_keys($classCity);
                    }
                    else {
                        $this->results['city'] = 1000000;
                    }
                }
                else{
                    $classCity = self::findTopGroupsCitiesByName($value);
                    if ($classCity !== null){
                       $this->results['city'] = array_keys($classCity);
                    }
                    else {
                        $this->results['city'] = 1000000;
                    }
                }
                break;
            case 'state':
                $classState = Warecorp_Location_State::findByName($value);
                //var_dump($classState);
                if ($classState !== null){
                    $this->results['state'] = $classState->id;
                }
                else {
                    $this->results['state'] = 1000000;
                }
                break;
            case 'country':
                $classCountry = Warecorp_Location_Country::findByName($value);
                if ($classCountry !== null){
                    $this->results['country'] = $classCountry->id;
                }
                else {
                    $this->results['country'] = 1000000;
                }
                break;
            case 'age':
                if (strstr($value, "-") != 0){
                    $value = str_replace(' ', '', $value);
                    list($from, $to) = preg_split('/-/', $value, 2);
                    $this->results['age_from']   =   intval($from);
                    $this->results['age_to']     =   intval($to);
                } else {
                    $this->results['age_from']   =   intval($value);
                    $this->results['age_to']     =   intval($value);
                }

                if ($this->results['age_from'] > $this->results['age_to']) {
                    $tmp = $this->results['age_to'];
                    $this->results['age_to'] = $this->results['age_from'];
                    $this->results['age_from'] = $tmp;
                }

                break;
            case 'gender':
                if ($value == 'male'){
                    $this->results['gender'] = 'male';
                }
                elseif ($value == 'female'){
                    $this->results['gender'] = 'female';
                }
                else{
                    $this->results['gender'] = 1000000;
                }
                break;
            case 'where':
                    $this->results['where'] = array();
                    $where = explode(',', $value, 3); //  split string "city,state,country"
                    if ( !empty($where[2]) ) { // country
                        $country = Warecorp_Location_Country::findByName(trim($where[2], ' "\''));
                        if ( !$country || null == $country->id )
                            $country = null;
                        else
                            $this->results['where']['country'] = $country->id;
                    }
                    if ( !empty($where[1]) ) { //  state
                        if ( isset($country) && null !== $country )
                            $state = Warecorp_Location_State::findByName(trim($where[1], ' "\''), $country);
                        else
                            $state = Warecorp_Location_State::findByName(trim($where[1], ' "\''));
                        if ( !$state || null == $state->id )
                            $state = null;
                        else
                            $this->results['where']['state'] = $state->id;
                    }
                    if ( isset($state) && null !== $state )
                        $city = Warecorp_Location_City::findByName(trim($where[0], ' "\''), $state);
                    else
                        $city = Warecorp_Location_City::findByName(trim($where[0], ' "\''));

                    if ( $city && null != $city->id ) {
                        $this->results['where']['city'] = $city->id;
                    }

                    if ( empty($this->results['where']) ) {
                        $this->results['where'] = array(
                            'city'      => 1000000,
                            'state'     => 1000000,
                            'country'   => 1000000
                        );
                    }
                    //var_dump($this->results['where']);
                    unset($country, $state, $city);
                break;
            case 'when':
                    $this->results['when'] = $value;
                break;
            case 'category':
                switch ($this->searchMode){
                    case 'groups':
                    case 'members':
                        $currentCategory = Warecorp_Group_Category::findIdByName($value);
                        if ($currentCategory !== null){
                            $this->results['group_category'] = $currentCategory;
                            $this->results['category'] = $currentCategory;
                        }
                        else $this->results['category'] = 1000000;
                        break;
                    case 'events':
                        break;
                    case 'photos':
                    case 'videos':
                    case 'documents':
                    case 'lists':
                    case 'discussion':
                        $this->results['category'] = 1000000;
                        break;
                    default:
                        die('unknown search type');
                }
                $classCountry = Warecorp_Location_Country::findByName($value);
                if ($classCountry !== null){
                    $this->results['country'] = $classCountry->id;
                }
                break;
            case 'list_type':
                $this->results['list_type'] = 1000000;
                //if ( $this->searchMode === 'lists' ) {
                    $allListTypes = Warecorp_List_Item::getListTypesListAssoc( trim($value) );
                    if ( is_array($allListTypes) && sizeof($allListTypes) )
                        $this->results['list_type'] = array_keys($allListTypes);
                //}
                //else {
                //    $this->results['list_type'] = -1;
                //}
                break;
        }
    }

    protected static function isStringQuoted($string)
    {
        return ((strpos($string,"'") === 0 && strripos( $string, "'" ) === strlen($string)-1 )
        || (strpos($string,'"') === 0 && strripos( $string, '"' ) === strlen($string)-1 ));
    }

    private function getValueForPattern($value, $currentPosition)
    {
        $parsedValue = $value;
        $index = $currentPosition;
        if ( (strpos($value,"'") !== 0 && strpos($value,'"') !== 0) || self::isStringQuoted($value)) {
            $this->setCurrentPosition($index);
            return self::removeQuote($value);
        }
        else{
            for ( $index = $currentPosition+1; $index < count($this->keywordsArray); $index++ ){
                $parsedValue.=' '.$this->keywordsArray[$index];
                if ( strripos( $this->keywordsArray[$index], "'" ) == ( strlen($this->keywordsArray[$index])-1 )
                || strripos( $this->keywordsArray[$index], '"' ) == ( strlen($this->keywordsArray[$index])-1 ) ) {
                    $this->setCurrentPosition($index);
                    break;
                }
            }
        }
        $this->setCurrentPosition($index);
        return self::removeQuote($parsedValue);
    }

    static private function isPatternExists($pattern)
    {
        $pattern = strtolower($pattern);
        if (in_array($pattern, self::$searchPrefixArray)){
            return true;
        }
        else{
            return false;
        }
    }

    static public function removeQuote($input)
    {
        if ( self::isStringQuoted($input) ){
            return substr($input, 1 , -1);
        }
        return $input;
    }

        /**
     * @return Warecorp_Location_City
     * @author Artem Sukharev
     */
    static private function findTopUsersCitiesByName($cityName)
    {
        $dbConn = Zend_Registry::get('DB');
        $query = $dbConn->select()->from('view_users__top_locations', '*');
        $query->where('city_name = ?', $cityName);
        $result = $dbConn->fetchPairs($query);
        if ( !$result ) return null;
        return $result;
    }

    static private function findTopGroupsCitiesByName($cityName)
    {
        $dbConn = Zend_Registry::get('DB');
        $query = $dbConn->select()->from('view_groups__top_locations', '*');
        $query->where('city_name = ?', $cityName);
        $result = $dbConn->fetchPairs($query);
        if ( !$result ) return null;
        return $result;
    }

    static public function isSearchAvailable($enity_type, $params) {
        //array('country', 'state', 'city', 'tag', 'category', 'age', 'where', 'when', 'list_type');
        //var_dump($params);
        $params = array_keys($params);
        switch ($enity_type)
        {
            case 'groups':
                if ( count( array_intersect( $params, array('age_from', 'age_to', 'when', 'list_type') ) ) ){
                    return false;
                }
                break;
            case 'users':
                if ( count( array_intersect( $params, array('when', 'list_type' ) ) ) ){
                    return false;
                }
                break;
            case 'events':
                if ( count( array_intersect( $params, array('age_from', 'age_to', 'list_type') ) ) ){
                    return false;
                }
                break;
            case 'lists':
                if ( count( array_intersect( $params, array('country', 'category', 'state', 'city', 'category', 'age_from', 'age_to', 'where', 'when') ) ) ){
                    return false;
                }
                break;
            case 'documents':
            case 'photos':
            case 'videos':
            case 'discussions':
                if ( count( array_intersect( $params, array('country',  'category', 'state', 'city', 'category', 'age_from', 'age_to', 'where', 'when', 'list_type') ) ) ){
                    return false;
                }
                break;
            default:
                return false;
        }
        return true;
    }
}
