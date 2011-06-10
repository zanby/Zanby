<?php

function smarty_modifier_utf8truncate($string, $length = 80, $etc = '...',
                                  $break_words = false, $middle = false)
{
	$doubleLetters = '[A-Z wmшщШЩФф А-Я]';
    if ($length == 0) return '';

    $returnStrNew = '';
    $cont = preg_match_all('/'.$doubleLetters.'/u', $string, $tmp);
    $Len = utf8_strlen($string) + $cont;
    
    //if (utf8_strlen($string) > $length) {
    if ($Len > $length) {
        $length -= utf8_strlen($etc);
        if (!$break_words && !$middle) {
            $string = preg_replace('/\s+?(\S+)?$/u', '', utf8_substr($string, 0, $length+1));
        }
        if(!$middle) {
        	$returnStr = utf8_substr($string, 0, $length);
        	$returnStrLen = utf8_strlen($returnStr);
        	$returnStrNew = '';
        	for ( $i = 0; $i < $returnStrLen; $i++ ) {
                $returnStrNew .= utf8_substr($returnStr, $i, 1);
                if ( preg_match('/'.$doubleLetters.'/u', utf8_substr($returnStr, $i, 1)) ) {
                	$returnStrLen--;
                }
        	}
        	$returnStrNew .= $etc;
        } else {
            $returnStr1 = utf8_substr($string, 0, $length/2);
            $returnStrLen1 = utf8_strlen($returnStr1);
            $returnStrNew1 = '';
            for ( $i = 0; $i < $returnStrLen1; $i++ ) {
                $returnStrNew1 .= utf8_substr($returnStr1, $i, 1);
                if ( preg_match('/'.$doubleLetters.'/u', utf8_substr($returnStr1, $i, 1)) ) {
                    $returnStrLen1--;
                }
            }
            $returnStr2 = utf8_substr($string, -$length/2);
            $returnStrLen2 = utf8_strlen($returnStr2);
            $returnStrNew2 = '';
            for ( $i = 0; $i < $returnStrLen2; $i++ ) {
                $returnStrNew2 .= utf8_substr($returnStr2, $i, 1);
                if ( preg_match('/'.$doubleLetters.'/u', utf8_substr($returnStr2, $i, 1)) ) {
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

function utf8_strlen($s)
{
    return preg_match_all('/./u', $s, $tmp);
}

function utf8_substr($s, $offset, $len = 'all')
{
    if ($offset < 0) $offset = utf8_strlen($s) + $offset;
    if ( $len != 'all' ) {
       if ( $len < 0 ) $len = utf8_strlen($s) - $offset + $len;
       $xlen = utf8_strlen($s) - $offset;
       $len = ( $len > $xlen ) ? $xlen : $len;
       preg_match('/^.{' . $offset . '}(.{0,'.$len.'})/us', $s, $tmp);
    } else {
       preg_match('/^.{' . $offset . '}(.*)/us', $s, $tmp);
    }
    return (isset($tmp[1])) ? $tmp[1] : false;
}

/* vim: set expandtab: */

?>
