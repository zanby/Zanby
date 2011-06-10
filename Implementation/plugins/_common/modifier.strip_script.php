<?php
function smarty_modifier_strip_script($string)
{
    return preg_replace('/<script.*?(<\/script>|$)/mi', '', $string);
}
