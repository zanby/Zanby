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
 * @author Artem Sukharev
 */
class BaseWarecorp_Form_Validation
{
    /**
     * Takes an UTF-8 string and returns an array of ints representing the
     * Unicode characters. Astral planes are supported ie. the ints in the
     * output can be > 0xFFFF. Occurrances of the BOM are ignored. Surrogates
     * are not allowed.
     *
     * Returns false if the input string isn't a valid UTF-8 octet sequence.
     */
    static private function utf8ToUnicode($str)
    {
      $mState = 0;     // cached expected number of octets after the current octet
                       // until the beginning of the next UTF8 character sequence
      $mUcs4  = 0;     // cached Unicode character
      $mBytes = 1;     // cached expected number of octets in the current sequence

      $out = array();

      $len = strlen($str);
      for($i = 0; $i < $len; $i++) {
        $in = ord($str{$i});
        if (0 == $mState) {
          // When mState is zero we expect either a US-ASCII character or a
          // multi-octet sequence.
          if (0 == (0x80 & ($in))) {
            // US-ASCII, pass straight through.
            $out[] = $in;
            $mBytes = 1;
          } else if (0xC0 == (0xE0 & ($in))) {
            // First octet of 2 octet sequence
            $mUcs4 = ($in);
            $mUcs4 = ($mUcs4 & 0x1F) << 6;
            $mState = 1;
            $mBytes = 2;
          } else if (0xE0 == (0xF0 & ($in))) {
            // First octet of 3 octet sequence
            $mUcs4 = ($in);
            $mUcs4 = ($mUcs4 & 0x0F) << 12;
            $mState = 2;
            $mBytes = 3;
          } else if (0xF0 == (0xF8 & ($in))) {
            // First octet of 4 octet sequence
            $mUcs4 = ($in);
            $mUcs4 = ($mUcs4 & 0x07) << 18;
            $mState = 3;
            $mBytes = 4;
          } else if (0xF8 == (0xFC & ($in))) {
            /* First octet of 5 octet sequence.
             *
             * This is illegal because the encoded codepoint must be either
             * (a) not the shortest form or
             * (b) outside the Unicode range of 0-0x10FFFF.
             * Rather than trying to resynchronize, we will carry on until the end
             * of the sequence and let the later error handling code catch it.
             */
            $mUcs4 = ($in);
            $mUcs4 = ($mUcs4 & 0x03) << 24;
            $mState = 4;
            $mBytes = 5;
          } else if (0xFC == (0xFE & ($in))) {
            // First octet of 6 octet sequence, see comments for 5 octet sequence.
            $mUcs4 = ($in);
            $mUcs4 = ($mUcs4 & 1) << 30;
            $mState = 5;
            $mBytes = 6;
          } else {
            /* Current octet is neither in the US-ASCII range nor a legal first
             * octet of a multi-octet sequence.
             */
            return false;
          }
        } else {
          // When mState is non-zero, we expect a continuation of the multi-octet
          // sequence
          if (0x80 == (0xC0 & ($in))) {
            // Legal continuation.
            $shift = ($mState - 1) * 6;
            $tmp = $in;
            $tmp = ($tmp & 0x0000003F) << $shift;
            $mUcs4 |= $tmp;

            if (0 == --$mState) {
              /* End of the multi-octet sequence. mUcs4 now contains the final
               * Unicode codepoint to be output
               *
               * Check for illegal sequences and codepoints.
               */

              // From Unicode 3.1, non-shortest form is illegal
              if (((2 == $mBytes) && ($mUcs4 < 0x0080)) ||
                  ((3 == $mBytes) && ($mUcs4 < 0x0800)) ||
                  ((4 == $mBytes) && ($mUcs4 < 0x10000)) ||
                  (4 < $mBytes) ||
                  // From Unicode 3.2, surrogate characters are illegal
                  (($mUcs4 & 0xFFFFF800) == 0xD800) ||
                  // Codepoints outside the Unicode range are illegal
                  ($mUcs4 > 0x10FFFF)) {
                return false;
              }
              if (0xFEFF != $mUcs4) {
                // BOM is legal but we don't want to output it
                $out[] = $mUcs4;
              }
              //initialize UTF8 cache
              $mState = 0;
              $mUcs4  = 0;
              $mBytes = 1;
            }
          } else {
            /* ((0xC0 & (*in) != 0x80) && (mState != 0))
             *
             * Incomplete multi-octet sequence.
             */
            return false;
          }
        }
      }
      return $out;
    }

    /**
     * Takes an array of ints representing the Unicode characters and returns
     * a UTF-8 string. Astral planes are supported ie. the ints in the
     * input can be > 0xFFFF. Occurrances of the BOM are ignored. Surrogates
     * are not allowed.
     *
     * Returns false if the input array contains ints that represent
     * surrogates or are outside the Unicode range.
     */
    static private function unicodeToUtf8($arr)
    {
      $dest = '';
      foreach ($arr as $src) {
        if($src < 0) {
          return false;
        } else if ( $src <= 0x007f) {
          $dest .= chr($src);
        } else if ($src <= 0x07ff) {
          $dest .= chr(0xc0 | ($src >> 6));
          $dest .= chr(0x80 | ($src & 0x003f));
        } else if($src == 0xFEFF) {
          // nop -- zap the BOM
        } else if ($src >= 0xD800 && $src <= 0xDFFF) {
          // found a surrogate
          return false;
        } else if ($src <= 0xffff) {
          $dest .= chr(0xe0 | ($src >> 12));
          $dest .= chr(0x80 | (($src >> 6) & 0x003f));
          $dest .= chr(0x80 | ($src & 0x003f));
        } else if ($src <= 0x10ffff) {
          $dest .= chr(0xf0 | ($src >> 18));
          $dest .= chr(0x80 | (($src >> 12) & 0x3f));
          $dest .= chr(0x80 | (($src >> 6) & 0x3f));
          $dest .= chr(0x80 | ($src & 0x3f));
        } else {
          // out of range
          return false;
        }
      }
      return $dest;
    }

    /**
     * isValidName  
     * 
     * @param string $name 
     * @static
     * @access public
     * @return bool
     */
    static public function isInvalidName($parameters)
    {
        mb_internal_encoding('utf-8');
        list($key, $name) = each($parameters);

        $name       = mb_strtolower($name);
        $first_char = mb_substr($name, 0, 1);

        $first_char_valid   = true;
        $name_valid         = true;

        $info_name = count_chars($name, 1);
        $info_char = count_chars($first_char, 1);

        $name_codes      = (self::utf8ToUnicode($name));
        $first_char_code = (self::utf8ToUnicode($first_char));

        if ( !$name_codes || !$first_char_code )
            return true;

        list($key, $charcode) = each($first_char_code);
        if ( $charcode < 96 || $charcode >= 123 && $charcode <= 127 ) {
            return true;
        }

        foreach ( $name_codes as $letter_number => $charcode ) {
            if ( !( $charcode == 32                                                             ||
                    $charcode >= 40 && $charcode <= 57 && $charcode != 47 && $charcode != 42    ||
                    $charcode >= 97 && $charcode <= 122                                         ||
                    $charcode > 127 )
            ) {
                return true;
            }
        }

        return false;
    }

	static public function calc_period($date_start, $date_finish)
	{
        $st = explode('-', date('d-m-Y', $date_start));
        $fin = explode('-', date('d-m-Y-H-i-s', $date_finish));
        $st[5]=0;
        $st[4]=0;
        $st[3]=0;
        if (($seconds = $fin[5] - $st[5]) < 0) {
                $fin[4]--;
                $minutes += 60;
        }

        if (($minutes = $fin[4] - $st[4]) < 0) {
                $fin[3]--;
                $minutes += 60;
        }

        if (($hours = $fin[3] - $st[3]) < 0) {
                $fin[0]--;
                $hours += 24;
        }

        if (($days = $fin[0] - $st[0]) < 0) {
                $fin[1]--;
                $days += date('t', mktime(1, 0, 0, $fin[1], $fin[0], $fin[2]));
        }

        if (($months = $fin[1] - $st[1]) < 0) {
                $fin[2]--;
                $months += 12;
        }

        $years = $fin[2] - $st[2];

        return $years;
	}

    /**
     * callback function for email already existing validation
     *
     * @param string $email
     * @return boolean  true if error
     * @author Vitaly Targonsky
     */
    static public function isUserEmailExist($email)
    {
        $_db = Zend_Registry::get('DB');
        $where = $_db->quoteInto('email=?', $email);
        $query = $_db->select()->from('zanby_users__accounts', 'id')->where($where);
        return $_db->fetchOne($query) ? true : false;
    }
    /**
     * callback function for login info validation
     *
     * @param array $hash
     * @return boolean  true if error
     * @author Vitaly Targonsky
     */
    static public function isUserExist($hash)
    {
    	if ( !isset($hash['name']) || !isset($hash['pass']) ) return false;
        $db = Zend_Registry::get("DB");
        $query = $db->select();
        $query->from("zanby_users__accounts", "id")
              ->where("login = ?", $hash['name'])
              ->where("pass = ?", md5($hash['pass']))
              ->where("status IN (?)", $hash['status']);
        $res = $db->fetchOne($query);
        return $res ? false : true;
    }

    static public function isDistrictInvalid( $district )
    {
        $db       = Zend_Registry::get('DB');
        $state    = substr($district['district'], 0, 2);
        $district = substr($district['district'], 2);
        $result   = $db->fetchOne('SELECT `zipcode` FROM `z1sky_users__districts` WHERE `state` = '.$db->quote($state).' AND `district` = '.$db->quote($district).' LIMIT 1');

        return ( $result ) ? false : true;
    }

    /**
     *
     */
    static public function isDateRequered($params)
    {
        if ( !trim($params['year']) || !trim($params['month']) || !trim($params['day']) ) {
            return true;
        }
        return false;
    }

    /**
 	* callback function for age validation
 	*
 	* @param array $birthday
 	* @return boolean true if error
 	*/
    static public function isAgeValid($birthday)
    {
        $age = self::calc_period(strtotime($birthday['date_Day'].'-'.$birthday['date_Month'].'-'.$birthday['date_Year']),strtotime('now'));
/*    	$age = (strtotime('now') - strtotime($birthday['date_Year'].'-'.$birthday['date_Month'].'-'.$birthday['date_Day']));
    	$age /= 3600; // age in hours;
    	$age /= 24;   // age in days;
    	$age /= 365;  // age in years;*/
    	return ($age < 13);
	}

    /**
    * callback function for age validation
    *
    * @param array $birthday
    * @return boolean true if error
    */
    static public function isAge18Valid($birthday)
    {
        $age = self::calc_period(strtotime($birthday['date_Day'].'-'.$birthday['date_Month'].'-'.$birthday['date_Year']),strtotime('now'));
/*      $age = (strtotime('now') - strtotime($birthday['date_Year'].'-'.$birthday['date_Month'].'-'.$birthday['date_Day']));
        $age /= 3600; // age in hours;
        $age /= 24;   // age in days;
        $age /= 365;  // age in years;*/
        return ($age < 18);
    }

	/**
	 * Callback function for age validation
	 *
	 * @param array $params (birthday and minAge)
 	 * @return boolean  true if error
	 *
 	 */
	static public function isMinimumAge($params) {
		$birthday = $params['birthday'];
        $age = self::calc_period(strtotime($birthday['date_Day'].'-'.$birthday['date_Month'].'-'.$birthday['date_Year']),strtotime('now'));
        return ($age < $params['minAge']);
	}
	/**
 	* callback function for login already existing validation
 	*
 	* @param string $login
 	* @return boolean  true if error
 	*/
	static public function isLoginExist($login)
	{
	    $_db = Zend_Registry::get('DB');
    	$where = $_db->quoteInto('login=?', $login);
    	$sql = $_db->select()->from('zanby_users__accounts', 'id')->where($where);
    	return $_db->fetchOne($sql) ? true : false;
	}
	/**
 	* callback function for captcha verification code validation
 	*
 	* @param array $Values
 	* @return boolean  true if error
 	* @author Yury Zolotarsky
 	*/

	static public function isCaptchaCodeNotValid($Values)
	{
		global $CAPTCHA_CONFIG;
		if ($Values === '') return true;

		$captcha = new b2evo_captcha($CAPTCHA_CONFIG);
		return ($captcha->validate_submit($Values['key'], $Values['userkey'])===0) ? true: false;
	}

    /**
 	* callback function for login validation exclude user ids
 	*
 	* @param array $params (login and excludeIds)
 	* @return boolean  true if error
 	* @author Yury Zolotarsky
 	*/
    static public function isNewLoginExist($params)
	{
	    $_db = Zend_Registry::get('DB');
    	$where = $_db->quoteInto('login=?', $params['login']);
    	$sql = $_db->select()->from('zanby_users__accounts', 'id')->where($where);
    	if (isset($params['excludeIds'])) {
           $sql->where($_db->quoteInto('id NOT IN (?)', $params['excludeIds']));
    	}
    	return $_db->fetchOne($sql) ? true : false;
	}

    /**
 	* callback function for email validation exclude user ids
 	*
 	* @param array $params (login and excludeIds)
 	* @return boolean  true if error
 	* @author Yury Zolotarsky
 	*/

    static public function isNewUserEmailExist($params)
    {
        $_db = Zend_Registry::get('DB');
        $where = $_db->quoteInto('email=?', $params['email']);
        $query = $_db->select()->from('zanby_users__accounts', 'id')->where($where);
       	if (isset($params['excludeIds'])) {
           $query->where($_db->quoteInto('id NOT IN (?)', $params['excludeIds']));
    	}
        return $_db->fetchOne($query) ? true : false;
    }

    /**
 	* callback function for Group Name already exist validation exclude group ids
 	*
 	* @param array $params (login and excludeIds)
 	* @return boolean  true if error
 	* @author Yury Zolotarsky
 	*/
    static public function isNewGroupExist($params)
    {
      	$_db = Zend_Registry::get('DB');

    	$where = $_db->quoteInto('name=?', $params['gname']);
    	$sql = $_db->select()->from('zanby_groups__items', 'id')->where($where);
       	if (isset($params['excludeIds'])) {
           $sql->where($_db->quoteInto('id NOT IN (?)', $params['excludeIds']));
    	}
    	return $_db->fetchOne($sql) ? true : false;
	}
    /**
 	* callback function for Group Name already exist validation exclude group ids
 	*
 	* @param array $params (key, value, excludeIds)
 	* @return boolean  true if error
 	* @author Vitaly Targonsky
 	*/
    static public function isGroupExist($params)
    {
        if ( empty($params['exclude']) ) $params['exclude'] = null;
        return Warecorp_Group_Standard::isGroupExists($params['key'], $params['value'], $params['exclude']);
	}
    /**
 	* callback function for validate join code
 	*
 	* @param array $params (group_id, join_code)
 	* @return boolean  true if error
 	* @author Vitaly Targonsky
 	*/
    static public function isJoinCodeValid($params)
    {
        if (!isset($params['group_id']) || !isset($params['join_code'])) return true;
        $_db = Zend_Registry::get('DB');
        $query = $_db->select();
        $query->from('zanby_groups__items', 'id')
                ->where('id = ?', $params['group_id'])
                ->where('BINARY join_code = ?', $params['join_code']);
        $res = $_db->fetchCol($query);
        return !((bool) $res);
	}

    /**
     * callback function : check is city required
     * @author Artem Sukharev
     */
    static public function isCityRequired($params)
    {
        if ( $params['countryId'] ) {
            if ( $params['countryId'] == 1 || $params['countryId'] == 38 ) {
                return false;
            } else {
                if ( trim($params['city']) == '' ) return true;
            }
        } else return false;
    }

    /**
     *
     */
    static public function isStateExist($params)
    {
        if (empty($params['countryId']) || empty($params['stateId'])) return false;
        $country = Warecorp_Location_Country::create($params['countryId']);
        $states = $country->getStatesListAssocWithCodes(false);
        if (array_key_exists($params['stateId'], $states)) return false;
        return true;
    }

    /**
     * Check city from autocomplete
     * Format of city : 'City Name, State Name'
     * @author Artem Sukharev
     */
    static public function isCityInvalid($params)
    {
        if ( $params['countryId'] ) {
            if ( $params['countryId'] == 1 || $params['countryId'] == 38 ) {
                return false;
            } else {
                if ( trim($params['city']) == '' ) return true;
                $country = Warecorp_Location_Country::create($params['countryId']);
                return !$country->checkCityFromAC($params['city']);

            }
        } else return false;
    }
    /**
     *
     */
    static public function isCityOrCustomValid($params)
    {
        if ( $params['countryId'] && $params['countryId'] != 1 && $params['countryId'] != 38 ) {
	        if ( !$params['objUserCity'] && !$params['city_correct'] ) {
	            return true;
	        }
        } else return false;
    }
    /**
     * callback function : check is zipcode required
     * @author Artem Sukharev
     */
    static public function isZipcodeRequired($params)
    {
        if ( $params['countryId'] ) {
            if ( $params['countryId'] == 1 || $params['countryId'] == 38 ) {
                if ( trim($params['zipcode']) == '' ) return true;
            } else {
                return false;
            }
        } else return false;
    }

    /**
     * Check zipcode from autocomplete
     * Format of zipcode : 'zipcode, State Name' OR zipcode only
     * @author Artem Sukharev
     */
    static public function isZipcodeInvalid($params)
    {
        if ( $params['countryId'] ) {
            if ( $params['countryId'] == 1 || $params['countryId'] == 38 ) {
                $country = Warecorp_Location_Country::create($params['countryId']);
                return !( $country->checkZipcodeFromAC($params['zipcode']) || $country->checkZipcode($params['zipcode']) );
            } else {
                return false;
            }
        } else return false;
    }

    /**
     * Check zipcode without autocomplete
     * Format of zipcode : zipcode only (without State Name)
     * @author Artem Sukharev
     */
    static public function isCleanZipcodeInvalid($params)
    {
        if ( $params['countryId'] ) {
            if ( $params['countryId'] == 1 || $params['countryId'] == 38 ) {
                $country = Warecorp_Location_Country::create($params['countryId']);
                return !( $country->checkZipcode($params['zipcode']) );
            } else {
                return false;
            }
        } else return false;
    }

    static public function isDateValid( $strDate = null )
    {
        if ( null !== $strDate && '' != $strDate ) {
            $oDate = new Zend_Date( $strDate, Zend_Date::ISO_8601 );
            return  ( $oDate->toString('yyyyMMddTHHmmss') !== $strDate );
        } else return false;
    }

    static public function isFolderNameValid( $name = "" )
    {
        if(empty($name)) return false;
        if (!preg_match('/^[^().\/\*\^\?#;:!@$%+=,\"\'><~\[\]{}]+$/', $name))return false;
        return true;
    }

    static public function isSurveyCodeInvalid( $code ) {
        if ( trim($code) == '' ) return true;
        if ( !preg_match_all("/:\/\/www.surveygizmo.com\/s3\/js\/(\d*)\/([a-zA-Z0-9]*)\?/mi", $code, $match) ) return true;
        if ( !preg_match("/document\.write/mi", $code) ) return true;
        if ( !preg_match("/<script/mi", $code) ) return true;

        return false;
    }
}
