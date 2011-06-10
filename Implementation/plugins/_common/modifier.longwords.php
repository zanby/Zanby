<?php
    function smarty_modifier_longwords($string, $length = 50, $separator = " ")
    {
        //if ( !preg_match("/\.tpl/",$string) && !preg_match("//") )
        $string = preg_replace("/((?:\S){".$length."})/miu","\\1".$separator."",$string);
        return $string;
    }
