<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * assemble filepath of requested plugin
 *
 * @param string $type
 * @param string $name
 * @return string|false
 */
function smarty_core_assemble_plugin_filepath($params, &$smarty)
{
    static $_filepaths_cache = array();

    $_plugin_filename = $params['type'] . '.' . $params['name'] . '.php';
    if (isset($_filepaths_cache[$_plugin_filename])) {
        return $_filepaths_cache[$_plugin_filename];
    }
    $_return = false;

    /**
    * add new functionality to automatic select plugin file according to languages settings
    * @author Artem Sukharev
    */
    if ( defined('CORE_SMARTY_PLUGINS_DIR') ) {
        $smarty_plugins_dir = array(SMARTY_PLUGINS_DIR, CORE_SMARTY_PLUGINS_DIR, PRODUCT_SMARTY_PLUGINS_DIR, COMMON_SMARTY_PLUGINS_DIR);    
    } else {
        $smarty_plugins_dir = array(SMARTY_PLUGINS_DIR, PRODUCT_SMARTY_PLUGINS_DIR, COMMON_SMARTY_PLUGINS_DIR);    
    }

    /*
    if ( defined('LOCALE') ) {
        $smarty_plugins_dir = array(SMARTY_PLUGINS_DIR.LOCALE.'/', SMARTY_PLUGINS_DIR, PRODUCT_SMARTY_PLUGINS_DIR.LOCALE.'/', PRODUCT_SMARTY_PLUGINS_DIR, COMMON_SMARTY_PLUGINS_DIR);
    } else {
        $smarty_plugins_dir = array(SMARTY_PLUGINS_DIR, PRODUCT_SMARTY_PLUGINS_DIR, COMMON_SMARTY_PLUGINS_DIR);    
    }
    */
    foreach ((array)$smarty_plugins_dir as $_plugin_dir) {
        
    //foreach ((array)$smarty->plugins_dir as $_plugin_dir) {

        $_plugin_filepath = $_plugin_dir . DIRECTORY_SEPARATOR . $_plugin_filename;

        // see if path is relative
        if (!preg_match("/^([\/\\\\]|[a-zA-Z]:[\/\\\\])/", $_plugin_dir)) {
            $_relative_paths[] = $_plugin_dir;
            // relative path, see if it is in the SMARTY_DIR
            if (@is_readable(SMARTY_DIR . $_plugin_filepath)) {
                $_return = SMARTY_DIR . $_plugin_filepath;
                break;
            }
        }
        // try relative to cwd (or absolute)
        if (@is_readable($_plugin_filepath)) {
            $_return = $_plugin_filepath;
            break;
        }
    }

    if($_return === false) {
        // still not found, try PHP include_path
        if(isset($_relative_paths)) {
            foreach ((array)$_relative_paths as $_plugin_dir) {

                $_plugin_filepath = $_plugin_dir . DIRECTORY_SEPARATOR . $_plugin_filename;

                $_params = array('file_path' => $_plugin_filepath);
                require_once(SMARTY_CORE_DIR . 'core.get_include_path.php');
                if(smarty_core_get_include_path($_params, $smarty)) {
                    $_return = $_params['new_file_path'];
                    break;
                }
            }
        }
    }
    $_filepaths_cache[$_plugin_filename] = $_return;
    return $_return;
}

/* vim: set expandtab: */

?>
