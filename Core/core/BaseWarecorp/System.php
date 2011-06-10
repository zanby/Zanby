<?
/**
 * Warecorp FRAMEWORK
 *
 * @package    Warecorp_System
 * @copyright  Copyright (c) 2006
 * @author Halauniou Yauhen
 */

/**
 *
 *
 */
class BaseWarecorp_System
{
	/**
	 * return version of database
	 * @return string
	 */
	public static function getDbVersion()
	{
	    $db = Zend_Registry::get("DB");
		$sql = $db->select()->from('zanby_system__settings', 'keyvalue')->where('keyname = "db_version"');
		$version = $db->fetchOne($sql);
        return $version;
	}
}
?>
