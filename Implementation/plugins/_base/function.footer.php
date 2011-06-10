<?php
    function smarty_function_footer($params, &$smarty)
    {
	Warecorp::addTranslation('/plugins/function.footer.php.xml');
    $theme = Zend_Registry::get('AppTheme');
		
	$out = '<a href="http://zanby.com/" class="znPoweredBy znFloatRight"><img src="{$theme->images}/decorators/uptake_zanby_powered.jpg" alt="" /></a><div class="znBottomMenu znBlueText">';
	$user = Zend_Registry::get('User');
	
	
	if (!$user->isAuthenticated()) {
		$out .= '
		<a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/">'.Warecorp::t('Home').'</a>  | 
		<a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/">'.Warecorp::t('Members').'</a>  |  
		<a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/registration/index/">'.Warecorp::t('Register').'</a>  |  
		<a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/login/">'.Warecorp::t('Login').'</a> <br />
		<a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/info/contactus/">'.Warecorp::t('Contact Us').'</a>  |  
		<a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/info/copyright/">'.Warecorp::t('Copyright').'</a>  |  
		<a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/info/privacy/">'.Warecorp::t('Privacy Policy').'</a>
		';
	} else {
		$out .= '
		<a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/">'.Warecorp::t('Home').'</a>  | 
		<a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/users/">'.Warecorp::t('Members').'</a>  |  
        <a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/index/documents/">'.Warecorp::t('Documents').'</a>  |  
		<a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/info/contactus/">'.Warecorp::t('Contact Us').'</a><br />
		<a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/info/copyright/">'.Warecorp::t('Copyright').'</a>  |  
		<a href="http://'.BASE_HTTP_HOST.'/'.LOCALE.'/info/privacy/">'.Warecorp::t('Privacy Policy').'</a>
		';
	}
	$out .= '</div>';
	return $out;
}
?>
