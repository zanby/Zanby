<?php
    function smarty_modifier_longwordsimp($string, $length = 50, $separator = " ")
    {
        $doubleLetters = '[A-Z wmшщШЩФф А-Я]';
        $halfletters = '[1lij!t|]';       
        if ($length == 0) return '';

        $returnStrNew = '';
        $cont = preg_match_all('/'.$doubleLetters.'/u', $string, $tmp);
        $halfs = preg_match_all('/'.$halfletters.'/u', $string, $tmp);
        $Len = preg_match_all('/./u', $string, $tmp);        
        $Lennew = $Len + $cont - floor($halfs / 2);
        if ($length > $Lennew) return $string;
        $chnum = floor($length * $Len / $Lennew);

        $string = preg_replace("/((?:\S){".$chnum."})/miu","\\1".$separator."",$string);  
        return $string;
    }
       