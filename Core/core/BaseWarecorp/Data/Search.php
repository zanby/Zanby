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
 * @package    Warecorp_Data
 * @copyright  Copyright (c) 2006, 2008
 */

/**
 * Base class for working with sphinx indexer.
 *
 */

require_once(ENGINE_DIR.'/sphinxapi/sphinxapi.class.php');


class BaseWarecorp_Data_Search extends SphinxClient
{

    /**
    * list of indexes for searching.
    * @var string
    * @author Pianko
    */
    private $_searchIndexes = "*";

    /**
    * weight of indexes - array ( key - index name, value - index weight (integer) )
    * @var array
    * @author Pianko
    */
    private $_indexesWeights = array();

    /**
    * sphinx search mode
    * @var int
    * @author Pianko
    */
    private $_searchMode = SPH_MATCH_ANY;

    /**
    * sphinx sort mode
    * @var int
    * @author Pianko
    */
    private $_sortMode = SPH_SORT_EXTENDED;

    /**
    * sphinx rank mode
    * @var int
    * @author Pianko
    */
    private $_rankingMode = SPH_RANK_PROXIMITY_BM25;

    /**
    * maximum count of rows in result
    * @var int
    * @author Pianko
    */
    private $_resultLimit = 5000;

    /**
    * weight of fields - array ( key - field name, value - field weight (integer) )
    * @var array
    * @author Pianko
    */
    private $_fieldsWeight = array();

    /**
    * config file object
    * @var object Zend_Config
    * @author Pianko
    */
    private $_cfgIndexer = null;

    /**
    * sphinx result (with additional information)
    * @var array
    * @author Pianko
    */
    private $_result;

    /**
    * result as array ([id] => id)
    * @var array
    * @author Pianko
    */
    private $_resultPairs;

    /**
    * array contaned distance and weight
    * @var array
    * @author Pianko
    */
    private $_distanceWeight = null;

    /**
    * max actual distance (with weight great than 0)
    * @var int
    * @author Pianko
    */
    private $_distanceMax = null;

    /**
    * result with id, weight, distance, members count (key - ID)
    * @var array
    * @author Pianko
    */
    private $_resultIWDMC = null;

    /**
    * result with id, weight, distance, members count (key - ID)
    * @var array
    * @author Pianko
    */
    private $_resultIE = null;
    private $_resultILL = null;

    /**
    * result with id, weight, distance, members count (key - ID)
    * @var array
    * @author Pianko
    */
    private $_resultIWDLRD = null;

    /**
    * include & exclude documents with these ids
    * @var array
    * @author Konstantin Stepanov
    */
    private $_includedIdValues = null;
    private $_excludedIdValues = null;

    private $_searchDirection = null;

    private $_isLimitSet = false;

    private $_isGlobalMode = false;

    private $entityList = array (   "group",
                                    "user",
                                    "list",
                                    "photo",
                                    "video",
                                    "discussion",
                                    "venues",
                                    "event",
                                       "addressbook",
                                       "tags"
                                 );
    /**
    * class constructor
    * @author Pianko
    */

    public function setIsGlobalMode($mode)
    {
        $this->_isGlobalMode = $mode;
    }

    public function getIsGlobalMode()
    {
        return $this->_isGlobalMode;
    }


    public function __construct()
    {
        parent::__construct();
        $this->_cfgIndexer = Warecorp_Config_Loader::getInstance()->getCoreConfig('cfg.indexer.xml');
    }



    /**
    * reading data from config file and initialization variables of sphinx
    * @param int $entityType
    * @return void
    * @author Pianko
    */
    public function initGlobal()
    {
        $cfgInstance = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.instance.xml');

        if (isset($this->_cfgIndexer->conf->global_indexes)){
            $globalEntityList = explode(',',$this->_cfgIndexer->conf->global_indexes);
        }
        else {
            die('Please configure global search.');
        }

        foreach ($globalEntityList as $ent)
        {
            foreach ( $this->_cfgIndexer->indexes->{$ent}->{'index_weight'} as $_key => $value )
            {
                $this->_indexesWeights[$_key.$this->_cfgIndexer->conf->index_postfix] = intval($value);
                foreach ($this->_cfgIndexer->indexes->{$ent}->{'field_weight'} as $_key => $value )
                {
                    $this->_fieldsWeight[$_key] = intval($value);
                }
            }
        }

        $this->_searchIndexes = implode(", ", array_keys( $this->_indexesWeights ) );

        if ( isset($cfgInstance->search->host) && isset($cfgInstance->search->port) )
        {
            $this->SetServer($cfgInstance->search->host, intval($cfgInstance->search->port));
        }

        //$this->_resultLimit = 20;
        $this->SetFieldWeights  ( $this->_fieldsWeight 		);
        $this->SetIndexWeights	( $this->_indexesWeights 	);
        $this->SetRankingMode 	( $this->_rankingMode 		);
        $this->SetMatchMode   	( $this->_searchMode 		);
        $this->SetArrayResult   (false);
    }


    /**
    * reading data from config file and initialization variables of sphinx
    * @param int $entityType
    * @return void
    * @author Pianko
    */
    public function init($entityType = null)
    {
        $cfgInstance = Warecorp_Config_Loader::getInstance()->getAppConfig('cfg.instance.xml');

        if ( ($entityType == null) && !in_array($entityType, $this->entityList) ) throw new Exception("Undefined search direction (".$entityType.")");

        $this->_searchDirection = $entityType;

        if (!isset($this->_cfgIndexer->indexes->$entityType)) throw new Exception('Wrong config file cfg.indexer.xml');
        foreach ($this->_cfgIndexer->indexes->$entityType->__get('field_weight') as $_key => $value )
        {
            $this->_fieldsWeight[$_key] = intval($value);
        }

        foreach ($this->_cfgIndexer->indexes->$entityType->__get('index_weight') as $_key => $value )
        {
            $this->_indexesWeights[$_key.$this->_cfgIndexer->conf->index_postfix] = intval($value);
        }

        $this->_searchIndexes = implode(" ", array_keys( $this->_indexesWeights ) );

        if (isset($cfgInstance->search->host) && isset($cfgInstance->search->port))
        {
            $this->SetServer($cfgInstance->search->host, intval($cfgInstance->search->port));
        }

        if (isset($this->_cfgIndexer->indexes->$entityType->limit)) $this->_resultLimit = intval($this->_cfgIndexer->indexes->$entityType->limit);
        elseif (isset($cfgInstance->search->limit)) $this->_resultLimit = intval($cfgInstance->search->limit);

        $this->SetFieldWeights    ( $this->_fieldsWeight         );
        $this->SetIndexWeights    ( $this->_indexesWeights     );
        $this->SetRankingMode     ( $this->_rankingMode         );
        $this->SetMatchMode       ( $this->_searchMode         );
        $this->SetArrayResult     (false);
    }


    /**
    * set limits for query result list
    * @param int $offset
    * @param int $limit
    * @return void
    * @author Pianko
    */
    public function setLimit($rOffset = null, $rLimit = null)
    {
        if ($rOffset === null && $rLimit === null)
        {
            parent::SetLimits( 0, intval($this->_resultLimit),  intval($this->_resultLimit) );
        }
        else{
            parent::SetLimits( intval($rOffset), intval($rLimit),  intval($this->_resultLimit) );
        }
        $this->_isLimitSet = true;

    }


    public function filterResultById($value)
    {
        return (!$this->_includedIdValues || array_key_exists($value['id'], $this->_includedIdValues))
            && (!$this->_excludedIdValues || !array_key_exists($value['id'], $this->_excludedIdValues));
    }


    /**
    * processing sphinx query
    * @param string $q
    * @param string $index
    * @return void
    * @author Pianko
    */
    public function Query($q = "", $index = null)
    {
        if (!$this->_isLimitSet) {
            $this->setLimit();
        }

        if ( $index === null )
        {
            $this->_result = parent::Query(quotemeta($q), $this->_searchIndexes);
        }
        else
        {
            $this->_result = parent::Query(quotemeta($q), $index);
        }
        if ( $this->_result == false ){
            $this->_result = null;
            throw new Zend_Exception($this->GetLastError());
        } elseif (array_key_exists("matches", $this->_result)) {
            if ($this->_arrayresult)
            {
                if ($this->_includedIdValues || $this->_excludedIdValues)
                    $this->_result["matches"] = array_filter($this->_result["matches"], array( $this, "filterResultById" ));
            } else {
                if ($this->_includedIdValues)
                    $this->_result["matches"] = array_intersect_key($this->_result["matches"], $this->_includedIdValues);
                if ($this->_excludedIdValues)
                    $this->_result["matches"] = array_diff_key($this->_result["matches"], $this->_excludedIdValues);
            }
        }
    }


    /**
    * getting result as array like [id] => id
    * @return array
    * @author Pianko
    */
    public function getResultPairs()
    {
        $this->_resultPairs = array();
        if (!isset($this->_result['matches'])) return $this->_resultPairs;
        foreach ($this->_result['matches'] as $id => $value)
        {
            $this->_resultPairs[$id] = $id;
        }
        return $this->_resultPairs;
    }

    /**
    * getting result as sphinx return
    * @return array
    * @author Pianko
    */
    public function getResultSphinx()
    {
        return $this->_result;
    }

    /**
    * loading distance information from config file (cfg.weight.xml);
    * @param string $$distanceType
    * @return void
    * @author Pianko
    */
    private function loadDistanceWeight()
    {
        if ($this->_distanceWeight === null)
        {
            $cfgWeight = Warecorp_Config_Loader::getInstance()->getCoreConfig('cfg.weight.xml')->{$this->_searchDirection.'_distance'};

            foreach ($cfgWeight as $w) {
                $this->_distanceWeight[$w->distance*1000] = $w->weight;
                if (($this->_distanceMax < $w->distance) && ($w->weight != 0)) $this->_distanceMax = $w->distance;
            }
            krsort($this->_distanceWeight);
            unset($cfgWeight);
        }
    }

    /**
    * return weight for distance value
    * @param int $distance
    * @param string $distanceType
    * @return array
    * @author Pianko
    */
    private function getWeightByDistance($distance)
    {
        $this->loadDistanceWeight();
        foreach ($this->_distanceWeight as $dist => $weight)
        {
            if ($dist < $distance) return $weight;
        }
        return 0;
    }

    /**
    * return result as array (id, distance, weight, members count)
    * @param string $distanceType
    * @return array
    * @author Pianko
    */
    public function getResultIWDMC()
    {
        $this->_resultIWDMC = array();
        if ( !isset($this->_result['matches']) ) return $this->_resultIWDMC;

        foreach ($this->_result['matches'] as $id => $value) {
            $distance = isset($value['attrs']['@geodist'])?$value['attrs']['@geodist']:0;
            $this->_resultIWDMC[$id]['distance'] = $distance;
            $this->_resultIWDMC[$id]['id'] = $id;
            $this->_resultIWDMC[$id]['weight'] = $value['weight'] + $this->getWeightByDistance($distance);
            $this->_resultIWDMC[$id]['members_cnt'] = $value['attrs']['member_count'];
        }
        return $this->_resultIWDMC;
    }

    /**
    * return result as array [id] => weight
    * @param string $distanceType
    * @return array
    * @author Pianko
    */
    public function getResultIW()
    {
        $this->_resultIWDMC = array();
        if ( !isset($this->_result['matches']) ) return $this->_resultIWDMC;

        foreach ($this->_result['matches'] as $id => $value) {
            $this->_resultIWDMC[$id] = $value['weight'];
        }
        return $this->_resultIWDMC;
    }

    /**
    * return result as array [id] => weight
    * @param string $distanceType
    * @return array
    * @author Pianko
    */
    public function getResultILL()
    {
        $this->_resultILL = array();
        if ( !isset($this->_result['matches']) ) return $this->_resultILL;
        //print_r($this->_result['matches']);
        foreach ($this->_result['matches'] as $id => $value) {
            $this->_resultILL[$id] = array( 'lat' => rad2deg($value['attrs']['latitude']), 'lng' => rad2deg($value['attrs']['longitude']));
        }
        return $this->_resultILL;
    }

    /**
    * return result as array [id] => entity_id
    * @return array
    * @author Pianko
    */
    public function getResultIE()
    {
        $this->_resultIE = array();
        if ( !isset($this->_result['matches']) ) return $this->_resultIE;

        foreach ($this->_result['matches'] as $id => $value) {
            $this->_resultIE[$id] = $value['attrs']['entity_id'];
        }
        return $this->_resultIE;
    }

    /**
    * return result as array (id, distance, weight, login, reg_date)
    * @param string $distanceType
    * @return array
    * @author Pianko
    */
    public function getResultIWDLRD()
    {
        $this->_resultIWDLRD = array();
        if ( !isset($this->_result['matches']) ) return $this->_resultIWDLRD;

        foreach ($this->_result['matches'] as $id => $value) {
            $distance = isset($value['attrs']['@geodist'])?$value['attrs']['@geodist']:0;
            $this->_resultIWDLRD[$id]['distance'] = $distance;
            $this->_resultIWDLRD[$id]['id'] = $id;
            $this->_resultIWDLRD[$id]['weight'] = $value['weight'] + $this->getWeightByDistance($distance);
            $this->_resultIWDLRD[$id]['register_date'] = $value['attrs']['register_date'];
            $this->_resultIWDLRD[$id]['login'] = $value['attrs']['login'];
        }
        return $this->_resultIWDLRD;
    }

    /**
    * processing sphinx query
    * @param string $q
    * @param string $index
    * @return void
    * @author Pianko
    */
    public function SetSort($order)
    {
        parent::SetSortMode($this->_sortMode, $order);
    }

    /**
    * set geo anchor with filters & range filter
    * @param string $latattr
    * @param string $longattr
    * @param float $latitude
    * @param float $longitude
    * @param float $distance
    * @return void
    * @author Konstantin Stepanov
    */
    public function SetFilterGeo($latattr, $longattr, $latitude, $longitude, $distance = 500000.0)
    {
        // Set geo anchor to count distance from
        parent::SetGeoAnchor($latattr, $longattr, $latitude, $longitude);
        // Set distance filter - TODO maybe move mindistance to params (if really needed)?
        parent::SetFilterFloatRange('@geodist', 0.0, (float)$distance);
        // Set filters to remove empty coords from search results
        parent::SetFilterFloatRange($latattr, 0.0, 0.0, true);
        parent::SetFilterFloatRange($longattr, 0.0, 0.0, true);
    }

    /**
    * set document id filter
    * @param array id values to filter
    * @param bool exclude ids from results
    * @return void
    * @author Konstantin Stepanov
    */
    public function SetIDFilter($values, $exclude = false)
    {
        if (!is_array($values)) $values = array( $values );
        if ($exclude)
        {
            $this->_excludedIdValues = array_flip($values);
        } else
        {
            $this->_includedIdValues = array_flip($values);
            $this->SetIDRange( min($values), max($values) );
        }
    }


    public function resetResultById($id)
    {
        unset($this->_result['matches'][$id]);
    }


}
