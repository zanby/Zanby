{literal}
<script type="text/javascript">//<![CDATA[
    FBCfg.url_onlogin_ready = '{/literal}{$goUrl}{literal}';
    $(function(){
        FB.ensureInit(function() {
            /*
            FB.ConnectState.prototype = {
              connected: 1,
              userNotLoggedIn: 2,
              appNotAuthorized: 3
            }
            */
            FB.Connect.get_status().waitUntilReady(function(status) {       
                if ( status == FB.ConnectState.connected ) {
                	$("#failureMessageBox").hide();
                    $("#successMessageBox").show();
                    document.location.href = '{/literal}{$goUrl}{literal}';
                } else if ( status == FB.ConnectState.appNotAuthorized ) {
                    FB.Connect.requireSession(function() { 
                    	$("#failureMessageBox").hide();
                    	$("#successMessageBox").show();                    
                    	document.location.href = '{/literal}{$goUrl}{literal}';	 
                    }, function(){
                    	$("#successMessageBox").hide();
                    	$("#failureMessageBox").show();
                    });                	
                } else {
                	FB.Connect.requireSession(function() {
                		$("#failureMessageBox").hide();
                		$("#successMessageBox").show();
                		document.location.href = '{/literal}{$goUrl}{literal}';
                	}, function(){
                		$("#successMessageBox").hide();
                		$("#failureMessageBox").show();
                    });                	
                }
            });
        });
    })
//]]></script>
{/literal}

<div class="prInner">   
    {* PAGE CONTENT START *}
        
        <div class="prTCenter" id="successMessageBox" style="display: none;">
            <div class="prInnerTop">
				{t}You will be redirected to view event page in 10 seconds...{/t} <br />
				{t}If you are not automatically taken to view event page,
				please click on the hyperlink :{/t}
			</div>
			<div class="prInnerTop">	
				<a href="{$goUrl}">View Event</a>
            </div>                            
            <div class="prInnerTop"><a href="{$BASE_URL}">{t}{tparam value=$SITE_NAME_AS_STRING}Browse %s{/t}</a></div>
            <div class="prInnerTop">{t}{tparam value=$BASE_URL}{tparam value=$LOCALE}If you have a special question or suggestion, please email <a href="%s/%s/info/contactus/">Contact Us</a>{/t}</div>
        </div>
        
        <div class="prTCenter" id="failureMessageBox" style="display: none;">
            <div class="prInnerTop">
                {t}You are not connected with the Facebook.{/t} <br />
                {t}To view event details please connect with Facebook using the Facebook Connect button.{/t}
            </div>            
            <div class="prInnerTop">
                <fb:login-button onlogin="FBApplication.onlogin_ready();"  size="medium" length="long"></fb:login-button>
            </div>
            <div class="prInnerTop"><a href="{$BASE_URL}">{t}{tparam value=$SITE_NAME_AS_STRING}Browse %s{/t}</a></div>
            <div class="prInnerTop">{t}{tparam value=$BASE_URL}{tparam value=$LOCALE}If you have a special question or suggestion, please email <a href="%s/%s/info/contactus/">Contact Us</a>{/t}</div>
        </div>

{* PAGE CONTENT END *}
</div>