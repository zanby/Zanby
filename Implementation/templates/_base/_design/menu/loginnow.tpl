<div class="znWidgetInner3 znTColor4">
<div class="znTeaser znRegister znWidget1"><div class="znTeaser-inner"><div class="znTeaser-inner2"><div class="znTeaser-inner3">
        	<label for="login">{t}Username{/t}</label>
            {form from=$formLogin id="loginForm"}
    	    {form_errors_summary}       
           	<div class="znWidgetInner4">
				{form_text name="login" class="znWidthPer90"}            	
			</div>
           	<div class="prInnerTop">
            	<label for="password">{t}Password{/t}</label>
			</div>
            <div class="znWidgetInner4">
				{form_password name="password" class="znWidth110"}							           	
			</div>
           	<div class="znIndentTop znSmallText">
            	<a href="http://{$BASE_HTTP_HOST}/{$LOCALE}/users/restore/">{t}Forgot Password?{/t}</a>
			</div>
   			<div class="prInnerTop">
				<a class="znButton"><span>			
				<input type="submit" value="Login" class="submitfix" /></span></a>				           
           </div>	
    		<div class="prInnerTop">
	            <input type="checkbox" class="znNoBorder" name="rememberme" id="rememberme" value="1" checked="checked" />
				<label for="rememberme" class="znPointer">{t}Remember Me{/t}</label>              
         	</div>
            	
        {/form}
</div></div></div></div>   
</div>
