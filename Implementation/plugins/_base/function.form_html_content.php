<?php

/**
 * @author Alexander Komarovski
 */
function smarty_function_form_html_content($params, &$smarty)
{
	Warecorp::addTranslation('/plugins/function.form_html_content.php.xml');
    // form object verify
    if (!isset($smarty->_tpl_vars['wf_form_object'])) return Warecorp::t('Form object not found.');
    $form = $smarty->_tpl_vars['wf_form_object'];

    // element name verify
    if (!isset($params['name'])) return Warecorp::t('Name for field not found.');

    // element default value
    $value = isset($form->_defaults[$params['name']]) ? $form->_defaults[$params['name']] : '';
    $value = isset($params['value']) ? $params['value'] : $value;

    $rules = $form->getRules();
    if (!empty($rules)){
        if (isset($rules[$params['name']])){
            foreach ($rules[$params['name']] as $k => $rule){
                if ($rule['error'] == 1) {
                    $params['class'] = $params['class'].' prFormErrors';
                }
            }
        }
    }
    
    $content = '';
    $timestamp = md5(rand());
    $contentDivId = "EBC_{$timestamp}";
    
    $tinymceInserted = $smarty->get_template_vars("tinymceInserted");
    if(empty($tinymceInserted))
    {
        $content .= "<script type='text/javascript'>";
        $content .= "  var head = document.getElementsByTagName('head')[0];";
        $content .= "  var script = document.createElement('script');";
        $content .= "  script.type= 'text/javascript';";
        $content .= "  script.src = '".JS_URL."/tinymceBlog/tiny_mce.js';";
        $content .= "  head.appendChild(script);";
        $content .= "</script>\n";
        $smarty->assign("tinymceInserted", true);
    }
    
    $content .= "<div id='" . $contentDivId . "' style='height: 100px; border: 1px solid #A9A9A9; cursor: text;' onclick='xajax_cms_showBlockEditPopupJS(\"$contentDivId\", 1,1,1); return false;'";
    foreach ($params as $k => &$v) if ('value' != $k) $content .= ' '.$k.'="'.$v.'"';
    $content .= '>'.$value.'</div>';
    $content .= "<input type='hidden' id='" . $contentDivId . "_hidden'";
    foreach ($params as $k => &$v) if ('value' != $k) $content .= ' '.$k.'="'.$v.'"';
    $content .= ' />';

    
    
    return $content;
}