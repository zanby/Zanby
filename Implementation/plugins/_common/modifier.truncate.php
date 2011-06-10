<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty truncate modifier plugin
 *
 * Type:     modifier<br>
 * Name:     truncate<br>
 * Purpose:  Truncate a string to a certain length if necessary,
 *           optionally splitting in the middle of a word, and
 *           appending the $etc string or inserting $etc into the middle.
 * @link http://smarty.php.net/manual/en/language.modifier.truncate.php
 *          truncate (Smarty online manual)
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param string
 * @param integer
 * @param string
 * @param boolean
 * @param boolean
 * @return string
 */
//function smarty_modifier_truncate($string, $length = 80, $etc = '...',
//                                  $break_words = false, $middle = false)
//{
//    if ($length == 0)
//        return '';
//
//    if (strlen($string) > $length) {
//        $length -= strlen($etc);
//        if (!$break_words && !$middle) {
//            $string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length+1));
//        }
//        if(!$middle) {
//            return substr($string, 0, $length).$etc;
//        } else {
//            return substr($string, 0, $length/2) . $etc . substr($string, -$length/2);
//        }
//    } else {
//        return $string;
//    }
//}
/* vim: set expandtab: */


//replaced with code from smarty_truncateUTF8 by Eugene Halauniou

function smarty_modifier_truncate($string, $length = 80, $etc = '...',
                                  $break_words = false, $middle = false)
{
	$doubleLetters = '[A-Z wmшщШЩФф]';
    if ($length == 0) return '';

    $returnStrNew = '';
    $cont = preg_match_all('/'.$doubleLetters.'/u', $string, $tmp);
    $Len = _strlen($string) + $cont;
    
    //if (utf8_strlen($string) > $length) {
    if ($Len > $length) {
        $length -= _strlen($etc);
        if (!$break_words && !$middle) {
            $string = preg_replace('/\s+?(\S+)?$/u', '', _substr($string, 0, $length+1));
        }
        if(!$middle) {
        	$returnStr = _substr($string, 0, $length);
        	$returnStrLen = _strlen($returnStr);
        	$returnStrNew = '';
        	for ( $i = 0; $i < $returnStrLen; $i++ ) {
                $returnStrNew .= _substr($returnStr, $i, 1);
                if ( preg_match('/'.$doubleLetters.'/u', _substr($returnStr, $i, 1)) ) {
                	$returnStrLen--;
                }
        	}
        	$returnStrNew .= $etc;
        } else {
            $returnStr1 = _substr($string, 0, $length/2);
            $returnStrLen1 = _strlen($returnStr1);
            $returnStrNew1 = '';
            for ( $i = 0; $i < $returnStrLen1; $i++ ) {
                $returnStrNew1 .= _substr($returnStr1, $i, 1);
                if ( preg_match('/'.$doubleLetters.'/u', _substr($returnStr1, $i, 1)) ) {
                    $returnStrLen1--;
                }
            }
            $returnStr2 = _substr($string, -$length/2);
            $returnStrLen2 = _strlen($returnStr2);
            $returnStrNew2 = '';
            for ( $i = 0; $i < $returnStrLen2; $i++ ) {
                $returnStrNew2 .= _substr($returnStr2, $i, 1);
                if ( preg_match('/'.$doubleLetters.'/u', _substr($returnStr2, $i, 1)) ) {
                    $returnStrLen2--;
                }
            }
            $returnStrNew = $returnStrNew1.$etc.$returnStrNew2;
        }
        return $returnStrNew;
    } else {
        return $string;
    }
}

function _strlen($s)
{
    return preg_match_all('/./u', $s, $tmp);
}

function _substr($s, $offset, $len = 'all')
{
    if ($offset < 0) $offset = _strlen($s) + $offset;
    if ( $len != 'all' ) {
       if ( $len < 0 ) $len = _strlen($s) - $offset + $len;
       $xlen = _strlen($s) - $offset;
       $len = ( $len > $xlen ) ? $xlen : $len;
       preg_match('/^.{' . $offset . '}(.{0,'.$len.'})/us', $s, $tmp);
    } else {
       preg_match('/^.{' . $offset . '}(.*)/us', $s, $tmp);
    }
    return (isset($tmp[1])) ? $tmp[1] : false;
}

?>
