<?php
    function smarty_function_menu_language_tools($params, &$smarty)
    {    
		Warecorp::addTranslation('/plugins/function.menu_language_tools.php.xml');
		$theme = Zend_Registry::get('AppTheme');
		
        $objUser    = Zend_Registry::get('User'); 
        $URL        = BASE_URL.'/'.LOCALE;
		
		$lst = Warecorp::getLocalesNamesList();
		unset($lst['rss']);
		
		$output = array();
		if ( sizeof($lst) > 1 ) {			
			$output[] = "<ul class='prTopNav'>";
			$output[] = "<li class='prDropDown'>";   
			$output[] = "<a href='javascript:void(0);'> ".Warecorp::t($lst[LOCALE])."</a>"; 
			unset($lst[LOCALE]);
			$output[] = "<ul class='prTopSubNav left'>";
			foreach ( $lst as $key => $value ) {
				$output[] = '<li class="prCustomDropDoun"><a href="#" ref="lang_link" locale="'.$key.'">'.Warecorp::t($value).'</a></li>';
			}
			$output[] = "</ul>";			
			$output[] = "</li>";   
			$output[] = "</ul>";
			
			$url = Warecorp::getCrossDomainUrl(array('controller' => 'index', 'action' => 'changelanguage'));
			$output[] = "<script>$(function(){ $('a[ref=\"lang_link\"]').unbind().bind('click', function () {";
			$output[] = "$.post('".$url."', {setlocale: $(this).attr('locale')}, function(data) { xajax.processResponse(data); });";
			$output[] = "}); })</script>";
		}
		$output = join('', $output);
        return $output;
    }