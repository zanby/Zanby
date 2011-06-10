<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Include the {@link shared.make_timestamp.php} plugin
 */
require_once $smarty->_get_plugin_filepath('shared','make_timestamp');

function smarty_modifier_date_locale($string, $format="DATE_LONG")
{

    $mod = Warecorp_Date::getFormat($format);
    $date = new Zend_Date(smarty_make_timestamp($string));
    return $date->toString($mod);
}

/* vim: set expandtab: */

?>
