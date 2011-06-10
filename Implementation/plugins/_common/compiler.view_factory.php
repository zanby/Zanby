<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty {assign} compiler function plugin
 *
 * Type:     compiler function<br>
 * Name:     assign<br>
 * Purpose:  assign a value to a template variable
 * @link http://smarty.php.net/manual/en/language.custom.functions.php#LANGUAGE.FUNCTION.ASSIGN {assign}
 *       (Smarty online manual)
 * @author Monte Ohrt <monte at ohrt dot com> (initial author)
 * @auther messju mohr <messju at lammfellpuschen dot de> (conversion to compiler function)
 * @param string containing var-attribute and value-attribute
 * @param Smarty_Compiler
 */
function smarty_compiler_view_factory($tag_attrs, &$compiler)
{
    /**
    *  required fields: 
    *   - entity - required for naming of plugin
    *   - object - object for displaying
    *   - view - required for naming of plugin 
    */
    
    $_params = $compiler->_parse_attrs($tag_attrs);
    
    $entity = null;
    $view  = null;
    
    if (!isset($_params['entity'])) {
        $compiler->_syntax_error("entity not set", E_USER_WARNING);      
    }
    else{
        $entity = str_replace("'", "", $_params['entity']);
    }
    
    if (!isset($_params['view'])) {
        $view = 'default';
    }
    else{
        $view   = str_replace("'", "", $_params['view']);
    }
    
    if (!isset($_params['object']) || $_params['object'] === NULL) {
        $compiler->_syntax_error("object not set", E_USER_WARNING);
        return;
    }
                   
    $output = '';             
    $compiler->_compile_custom_tag('template_'.$entity."_".$view, $tag_attrs, '', $output);
    $output = str_replace('<?php','',$output);
    $output = str_replace('<?','',$output);
    $output = str_replace('?>','',$output);
    return $output;
}
