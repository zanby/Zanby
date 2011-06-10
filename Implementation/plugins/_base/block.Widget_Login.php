<?php
function smarty_block_Widget_Login($params, $content, &$smarty)
{
    $objUser = Zend_Registry::get('User');
    $context = Warecorp::isContext($smarty, $objUser, $objCurrentUser, $objGroup);
    $URL     = BASE_URL.'/'.LOCALE;
    $SITE_NAME_AS_STRING = SITE_NAME_AS_STRING;
	$Warecorp = new Warecorp();
    /**
     * End tag
     */

    if ( $content !== null && $context != 'search') {
        $formUrl = Warecorp::getCrossDomainUrl(array(
            'controller'    => 'ajax',
            'action'        => 'loginAjax'
        ));
        $output = '';
        if ( (!$objUser || null === $objUser->getId())  &&
            Warecorp::$controllerName != 'info'         &&
            Warecorp::$controllerName != 'users'        &&
            Warecorp::$actionName     != 'restore'
        ) {
			$fbLoginButton = '';
			$fbLoginButtonJs = '';
			if ( FACEBOOK_USED ) {
				$fbLoginButton = '<div class="prIndentTop"><fb:login-button onlogin="FBApplication.onlogin_ready();"size="large" length="short"></fb:login-button></div>';
				$fbLoginButtonJs = '<script type="text/javascript">FBCfg.url_onlogin_ready = "'.BASE_URL.'/'.LOCALE.'/facebook/processlogin/";</script>';
			}
            $output = <<<EOD
                <script language="javascript">
                    function sendCredentail(){
                        var callback = {success: function(oResponse){
                            xajax.processResponse(oResponse.responseXML);
                        }}
                        var oForm = YAHOO.util.Dom.get('ajaxLoginSubmitForm');
                        YAHOO.util.Connect.setForm(oForm);
                        var cObj = YAHOO.util.Connect.asyncRequest('POST', $('#ajaxLoginSubmitForm').attr('action'), callback);
                        return false;
                    }
                    $(function(){
                        $('#btnAjaxLoginSubmit').unbind('click').bind('click', sendCredentail)
                    });
                    $(function(){
                        $('#btnAjaxLoginSubmitDecor').unbind('click').bind('click', sendCredentail)
                    });
                </script>
				{$fbLoginButtonJs}
                <div class="prLoginBlock">
                    <div class="prLoginBlockInner">
                        <p class="prInnerBottom prInnerTop">{$Warecorp->t('Please join us!')}</p>
                        <a href="{$URL}/registration/index/">{$Warecorp->t('Sign Up for %s now!', array($SITE_NAME_AS_STRING))}</a>
                    </div>
                    <div class="prLoginBlockInner prInnerSmallTop">
                        <div class="prForms">
							{$fbLoginButton}
                            <form id="ajaxLoginSubmitForm" name="ajaxLoginSubmitForm" method="POST" action="{$formUrl}">
                                <div>
                                    <label for="login">{$Warecorp->t('Username:')}</label><br />
                                    <input name="login" id="login" style="width:122px;" type="text" />
                                    <div id="err_login"></div>
                                </div>
                                <div class="prIndentTopSmall">
                                    <label for="password">{$Warecorp->t('Password:')}</label><br />
                                    <input name="password" id="password" style="width:122px;" type="password" />
                                    <div id="err_password"></div>
                                </div>
                                <div class="prIndentTop">
                                    <input name="rememberme" id="rememberme" type="checkbox" class="prNoBorder" /> <label for="rememberme">{$Warecorp->t('Remember me')}</label>
                                </div>
                                <div class="prIndentTop">
									<input type="submit" id="btnAjaxLoginSubmit" value="Sign In" class="prSubmit" />
                                    <a href="#" class="prButton" style="cursor:pointer;" style id="btnAjaxLoginSubmitDecor"><span>{$Warecorp->t('Sign In')}</span></a>
                                </div>
                                <div class="prIndentTop">
                                    <a href="{$URL}/registration/index/">{$Warecorp->t('Sign up now!', array($SITE_NAME_AS_STRING))}</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
EOD;
        }
        return $output.$content;
    }
}