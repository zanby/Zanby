<link rel="stylesheet" type="text/css" href="{$AppTheme->css}/dnd.css" media="screen" />
<script src="{$JS_URL}/AKColorPicker.js" type="text/javascript"></script>
<script src="{$JS_URL}/AKLinePicker.js" type="text/javascript"></script>
<script src="{$JS_URL}/content_objects/theme.js" type="text/javascript"></script>

<script type="text/javascript" src="/js/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
{literal}
<script type="text/javascript">
		tinyMCE.init({
			mode : "textareas",
			theme : "zanby",
			editor_selector : "headlineEditor"
	});
</script>
{/literal}
<!-- ******** THEME EDITOR BEGIN ******** -->
<!--[if IE]>
<script type="text/javascript" src="/js/ieselectfix.js"></script>
<![endif]-->
<!-- setting box begin -->

	  
<!-- ********************************* -->
		<!-- left column begin -->
	<div class="prClr3">
		<div class="prThemeEditorLeftBlock">
			<ul class="prVerticalNav">
			  <li id="theme_defaults_button" class="active"><a onclick="hideAllAKPopups(); select_left_tab('theme_defaults_tab');return false;" href="#">{t}Content Block Defaults{/t}</a></li>
			  <li id="theme_background_button"><a onclick="hideAllAKPopups(); select_left_tab('theme_background_tab');return false;" href="#">{t}Background{/t}</a></li>
		  	</ul>
		</div>
		<!-- left column end -->

		<!-- === COLORS    ============================================================================================================= -->
		<div class="prThemeEditorRightBlock" id="theme_defaults_div">
						<h2>{t}Edit Content Block Defaults{/t}</h2>
						<p class="prInnerTop">{t}The content block defaults manage the starting formatting settings<br />of all your Drag and Drop content objects.{/t}</p>
					<div class="prFloatRight prIndentTopSmall">{t var="in_button"}Save Changes{/t}{linkbutton name=$in_button link="#" onclick="xajax_themeSave(ThemeApplication.getParamsDS(),ThemeApplication.clearOldLayoutCh()); ThemeApplication.makeBackupDefaults();"}</div>
         
			<div class="prClearer">
		  	<form class="prThemePanel" id="znbTheme-setting">
				
				<!-- slot -->
              	<h3 id="fillColorHeader" class="prInnerBottom">{t}Fill Color{/t}</h3>
				<div class="prInnerBottom">
					<input value="{$theme->fillColor}" id="fillColorIndicator" type="text" onkeyup="ThemeApplication.applyFillColor(this.value);return false;" class="prFloatLeft prIndentTopSmall" />
					<a id="fillColorSelector" class="prTheme-selectcolor prTheme-select prFloatLeft prIndentLeftSmall" href="#null" onclick="showAKColorPickerMCEStyle('12', ThemeApplication.fillColor, getElementPosition(this)[0], getElementPosition(this)[1] + 25, 'ThemeApplication.applyFillColor');">&nbsp;</a>
					<div class="prClearer"><input type="checkbox" id="FillColorTransparentCh" onclick="ThemeApplication.changeFillColorTransparent();" {if $theme->fillColorTransparent}checked="checked"{/if} class="prNoBorder" /><label for="FillColorTransparentCh">
					{t}Transparent{/t}</label></div>             
                	<div class="prInnerTop prIndentTop" id="zndTheme-live-simple6"></div>
                	 
				</div>
				<!-- /slot -->
				
				<!-- slot -->
              	<h3 class="prInnerTop prInnerBottom">{t}Headline Text{/t}</h3>
              	<div class="prInnerBottom">
                  	<select id="headlineTextStyleSelector" onchange="ThemeApplication.applyHeadlineTextStyle(this.value)">
                    	<option value="1"{if $theme->headlineTextFontFamily==1} selected="selected"{/if}>{t}Times New Roman{/t}</option>
                    	<option value="2"{if $theme->headlineTextFontFamily==2} selected="selected"{/if}>{t}Arial{/t}</option>
                    	<option value="3"{if $theme->headlineTextFontFamily==3} selected="selected"{/if}>{t}Tahoma{/t}</option>
                    	<option value="4"{if $theme->headlineTextFontFamily==4} selected="selected"{/if}>{t}Verdana{/t}</option>
                    	<option value="5"{if $theme->headlineTextFontFamily==5} selected="selected"{/if}>{t}Lucida Console{/t}</option>
                  	</select>
                  	<a class="prTheme-selectcolor prTheme-select" href="#null" onclick="showAKColorPickerMCEStyle('1', ThemeApplication.headlineTextColor, getElementPosition(this)[0], getElementPosition(this)[1] + 25, 'ThemeApplication.applyHeadlineTextColor');">&nbsp;</a>
                  	<p id="headlineTextColorIndicator" class="prIndentTopSmall" style="color:{$theme->headlineTextColor};">{t}Headline Text{/t}</p>
				</div>
              	<!-- /slot -->
              
              	<!-- slot -->
              	<h3 class="prInnerTop prInnerBottom">{t}Body Text{/t}</h3>
              	<div class="prInnerBottom">
                  	<select id="bodyTextStyleSelector" onchange="ThemeApplication.applyBodyTextStyle(this.value)">
                    	<option value="1"{if $theme->bodyTextFontFamily==1} selected="selected"{/if}>{t}Times New Roman{/t}</option>
                    	<option value="2"{if $theme->bodyTextFontFamily==2} selected="selected"{/if}>{t}Arial{/t}</option>
                    	<option value="3"{if $theme->bodyTextFontFamily==3} selected="selected"{/if}>{t}Tahoma{/t}</option>
                    	<option value="4"{if $theme->bodyTextFontFamily==4} selected="selected"{/if}>{t}Verdana{/t}</option>
                    	<option value="5"{if $theme->bodyTextFontFamily==5} selected="selected"{/if}>{t}Lucida Console{/t}</option>
                  	</select>
                  	<a class="prTheme-selectcolor prTheme-select" href="#null" onclick="showAKColorPickerMCEStyle('1', ThemeApplication.bodyTextColor, getElementPosition(this)[0], getElementPosition(this)[1] + 25, 'ThemeApplication.applyBodyTextColor');">&nbsp;</a>
                  	<p id="bodyTextColorIndicator" class="prIndentTopSmall" style="color:{$theme->bodyTextColor};">{t}Body Text{/t}</p>
				</div>
              	<!-- /slot -->

              	<!-- slot -->
              	<h3 class="prInnerTop prInnerBottom">{t}Accent Text{/t}</h3>
              	<div class="prInnerBottom">
                  	<select id="commentTextStyleSelector" onchange="ThemeApplication.applyCommentTextStyle(this.value)">
                   		<option value="1"{if $theme->commentFontFamily==1} selected="selected"{/if}>{t}Times New Roman{/t}</option>
                    	<option value="2"{if $theme->commentFontFamily==2} selected="selected"{/if}>{t}Arial{/t}</option>
                    	<option value="3"{if $theme->commentFontFamily==3} selected="selected"{/if}>{t}Tahoma{/t}</option>
                    	<option value="4"{if $theme->commentFontFamily==4} selected="selected"{/if}>{t}Verdana{/t}</option>
                    	<option value="5"{if $theme->commentFontFamily==5} selected="selected"{/if}>{t}Lucida Console{/t}</option>
                  	</select>
                  	<a class="prTheme-selectcolor prTheme-select" href="#null" onclick="showAKColorPickerMCEStyle('2', ThemeApplication.commentColor, getElementPosition(this)[0], getElementPosition(this)[1] + 25, 'ThemeApplication.applyCommentColor');">&nbsp;</a>
					<div class="prIndentTopSmall" id="commentColorIndicator" style="color:{$theme->commentColor};">{t}Accent Text{/t}</div>
				</div>
              	<!-- /slot -->

              	<!-- slot -->
              	<h3 class="prInnerTop prInnerBottom">{t}Section Header{/t}</h3>
              	<div class="prInnerBottom">
                  	<select id="headerTextStyleSelector" onchange="ThemeApplication.applyHeaderTextStyle(this.value)">
                     	<option value="1"{if $theme->headerFontFamily==1} selected="selected"{/if}>{t}Times New Roman{/t}</option>
                    	<option value="2"{if $theme->headerFontFamily==2} selected="selected"{/if}>{t}Arial{/t}</option>
                    	<option value="3"{if $theme->headerFontFamily==3} selected="selected"{/if}>{t}Tahoma{/t}</option>
                    	<option value="4"{if $theme->headerFontFamily==4} selected="selected"{/if}>{t}Verdana{/t}</option>
                    	<option value="5"{if $theme->headerFontFamily==5} selected="selected"{/if}>{t}Lucida Console{/t}</option>
                  	</select>
                  	<a class="prTheme-selectcolor prTheme-select" href="#null" onclick="showAKColorPickerMCEStyle('3', ThemeApplication.headerColor, getElementPosition(this)[0], getElementPosition(this)[1] + 25, 'ThemeApplication.applyHeaderColor');">&nbsp;</a>
                  	<h3 class="prIndentTopSmall" id="headerColorIndicator" style="color:{$theme->headerColor};">{t}Section Header{/t}</h3>
				</div>
              	<!-- /slot -->
              
              
             	<!-- slot -->
              	<h3 class="prInnerTop prInnerBottom">{t}Link Color{/t}</h3>
             	<div class="prInnerBottom">
					<a class="prTheme-viewcolor" id="linkColorIndicator1" style="background-color:{$theme->linkColor};">&nbsp;</a>
					<a class="prTheme-selectcolor prTheme-select" href="#null" onclick="showAKColorPickerMCEStyle('4', ThemeApplication.linkColor, getElementPosition(this)[0], getElementPosition(this)[1] + 25, 'ThemeApplication.applyLinkColor');">&nbsp;</a>
					<div class="prIndentTopSmall">
					<a href="#null" id="linkColorIndicator" style="color:{$theme->linkColor};">{t}Sample Link{/t}</a>
					</div>
				</div>
              	<!-- /slot -->
              
              
              	<!-- slot -->
              	<h3 class="prInnerTop prInnerBottom">{t}Outline{/t}</h3>
             	<div class="prInnerBottom">
					<a class="prTheme-viewcolor prFloatLeft prIndentRight" id="outlineColorIndicator1" style="background-color:{$theme->outlineColor};">&nbsp;</a>
					<a class="prTheme-selectcolor prTheme-select prFloatLeft prIndentRight" href="#null" onclick="showAKColorPickerMCEStyle('10', ThemeApplication.outlineColor, getElementPosition(this)[0], getElementPosition(this)[1] + 25, 'ThemeApplication.applyOutlineColor');">&nbsp;</a>
					<a class="prTheme-outline prTheme-viewcolor prFloatLeft" href="#null" onclick="showAKLinePicker(11, ThemeApplication.outlineStyle, getElementPosition(this)[0], getElementPosition(this)[1] + 30, 'ThemeApplication.applyOutlineStyle');">&nbsp;</a>
					<div id="outlineColorIndicator" class="prClearer prInnerTop" style="border-bottom-width:2px; border-left-width:0;border-right-width:0; border-top-width:0; border-bottom-color:{$theme->outlineColor}; border-bottom-style:{$theme->outlineStyle};"></div>
				</div>
              	<!-- /slot -->
              
            	</form>
            	<!-- setting box end -->
           
            	<!-- example box begin -->
            <div class="prFloatLeft prInnerTop prInnerLeft" style="width:50%" id="ddTarget2">
           		<!-- content object begin -->
            		<div id="outlineColorIndicator2">
               
						<div class="prCO-headline prCO-headline-view">
							<h4 class="prCO-title">{t}Title{/t}</h4>
						</div>
				                
						<!-- content object default headline -->
					   <h2 class="prInner">{t}Default Headline{/t}</h2>
						<!-- /content object default headline -->
						<!-- content object section -->
                
                    <!-- content section headline -->
                    <h3 class="prIndentLeft prClr2">
                        <span>{t}Section Header{/t}</span>
                    </h3>
			   			<!-- content section inner -->
			  		<div class="prInnerLeft prInnerRight">
			   		<p>
					<a href="#null">{t}Thread title go here{/t}</a><br />
                            {t}Mauris ut lorem a tortor 
                            tincidunt dignissim. Fusce
                            tempor dui at nulla. Praesent
                            fringilla. Cum sociis natoque
                            penatibus et nascetur
                            ridiculus mus... {/t}<a href="#null">{t}More{/t}&nbsp;&#187;</a>
					</p>
                    <!-- content object tabs -->
                    <div class="prSubNav prClr2 prIndentTop">
						<ul>
							<li class="active"><a href="#null">{t}Most Active{/t}</a></li>
							<li><a href="#null">{t}Most Recent{/t}</a></li>
						</ul>
					</div>	
                    
                    <!-- content object tabs -->
                   
                    <!-- content object tabs area -->
                    <div class="prInner">
                        <p>
                            <a href="#null">{t}Thread title go here{/t}</a><br />
                            <span class="prText4">{t}14 posts | Posted by{/t}</span><br />
                            <a href="#">{t}Groupname can be pretty long{/t}</a>
                            <span class="prText4">{t}Nov 14, 2006 at 2:04pm{/t}</span><br />
                        </p>
                        <p>
                            <a href="#null">{t}Thread title go here{/t}</a><br />
                            <span class="prText4">{t}14 posts | Posted by{/t}</span><br />
                            <a href="#">{t}Groupname can be pretty long{/t}</a>
                            <span class="prText4">{t}Nov 14, 2006 at 2:04pm{/t}</span><br />
                        </p>
                        <p>
                            <a href="#null">{t}Thread title go here{/t}</a><br />
                            <span class="prText4">{t}14 posts | Posted by{/t}</span><br />
                            <a href="#">{t}Groupname can be pretty long{/t}</a>
                            <span class="prText4">{t}Nov 14, 2006 at 2:04pm{/t}</span><br />
                        </p>
                        <p>
                            <a href="#null">{t}Thread title go here{/t}</a><br />
                            <span class="prText4">{t}14 posts | Posted by{/t}</span><br />
                            <a href="#">{t}Groupname can be pretty long{/t}</a>
                            <span class="prText4">{t}Nov 14, 2006 at 2:04pm{/t}</span><br />
                        </p>
                    </div><br />
                    <!-- /content object tabs area -->
                    </div>
               
                <!-- /content object section -->
            </div>
            <!-- /content object end -->
            <!-- **************************** -->
            </div>
            <!-- example box end -->
           
            <div class="prInnerTop prInnerBottom prClearer">
              <input class="prNoBorder" type="checkbox" id="clearOldLayout" /><label for="clearOldLayout"> {t}Apply changes to saved layout{/t}</label>
          	</div>
			<div class="prInnerTop prTRight prButtonPanel">
				{t var="in_button_2"}Save Changes{/t}{linkbutton name=$in_button_2 link="#" onclick="xajax_themeSave(ThemeApplication.getParamsDS(), ThemeApplication.clearOldLayoutCh());  ThemeApplication.makeBackupDefaults();"}&nbsp;
				{t var="in_button_3"}Clear Edits{/t}{linkbutton name=$in_button_3 link="#" onclick="ThemeApplication.restoreColors();"}			
			</div>       

          </div>
		  </div>  
         <!-- === /COLORS    ============================================================================================================= -->       
         
          <!-- === BACKGROUND ========================================================================================================= -->
    <div class="prThemeEditorRightBlock prClr3" id="theme_background_div" style="display:none;">

           			<h2 class="prFloatLeft">
						{if $CurrentGroup->getGroupType() == 'family'}
							{t}Edit the background of your Group Family{/t}
						{else}
							{t}Edit the background of your Group Summary Page{/t}
						{/if}
					</h2>
					{* <div class="prFloatRight prIndentTop">{t var="in_button_4"}Save Changes{/t}{linkbutton name=$in_button_4 link="#" onclick="xajax_themeSave(ThemeApplication.getParamsBS(),0);  ThemeApplication.makeBackupBackground();"}</div> *}

          	<div class="prClearer prInner prClr3">
            
            <!-- setting box begin -->
            <form id="znbTheme-setting" class="prThemePanel">
              <!-- slot -->
              <h3 class="prInnerBottom">{t}Background Color{/t}</h3>
              <div class="prInnerBottom prClr3">
                  <input value="{$theme->backgroundColor}" type="text" class="prFloatLeft prIndentTopSmall" name="edit[hex_code]" id="hex_code" onkeyup="ThemeApplication.applyBackgroundColor(this.value);return false;" />
                  <a class="prTheme-selectcolor prTheme-select prFloatLeft prIndentLeftSmall" href="#null" onclick="showAKColorPickerMCEStyle('1', ThemeApplication.backgroundColor, getElementPosition(this)[0], getElementPosition(this)[1] + 25, 'ThemeApplication.applyBackgroundColor');">&nbsp;</a>
              </div>
              <!-- /slot -->
              <!-- slot -->

              	<h3 class="prInnerTop prInnerBottom">{t}Background Image{/t}</h3>
             	<div class="prInnerBottom prClr3">
			  		<input value="{$theme->backgroundImage}" type="text" class="prFloatLeft prIndentTopSmall" id="backgroundImageIndicator" readonly="readonly"/>
                  	<a class="prTheme-selectimage prFloatLeft prTheme-select prIndentLeftSmall" href="#null"  onclick="ThemeApplication.showAddMenu(this); return false;" >&nbsp;</a>
                  	<div class="prClr2"></div>
                  
                  	<div style="display:{if $theme->backgroundUrl}block{else}none{/if}" id="avatarinfoblock">
		  		  		<input type="checkbox" id="backgroundTileCh" {if $theme->backgroundTile}checked="checked"{/if} onclick="ThemeApplication.changeBackgrouundTile();" class="prNoBorder" /> <label for="backgroundTileCh">{t}Tile{/t}</label>
						<div class="prInnerTop">
							<img class="prFloatLeft" src="{if $theme->backgroundImage}{$theme->backgroundUrl|replace:'_orig.':'_small.'}{else}{$AppTheme->images}/decorators/fakeImage.gif{/if}" id="backgroundImageIndicator2" />
							<div class="prFloatLeft prTip prInnerTop0"><span id="backgroundImageIndicator1">{$theme->backgroundImage}<br /></span>
								<a href="#null" onclick="ThemeApplication.bremoveBackgroundImage();">{t}remove image{/t}</a>
								<img src="{$AppTheme->images}/decorators/profile-marker.gif" onclick="ThemeApplication.removeBackgroundImage();" />
							</div>
						</div>
					</div>
				
				 	
			  	</div>
				<div style="display:{if $theme->backgroundUrl}none{else}block{/if}; zoom:1;" id="avatarinfoblock2">
						<span>{t}No background image{/t}</span>                 
				  	</div>
              <!-- /slot -->
            </form>
            <!-- setting box end -->
            
            <!-- example box begin -->
             <div class="prFloatLeft prInnerTop0" id="ddTarget2">            
	     		<div class="prTheme-bgexample">
	         		<div class="prTheme-bgexample-inner" id="layout_content" style="background-color: #cccccc;"></div>
	     		</div>            
            </div>
            <!-- example box end -->
           </div>
		   
			<div class="prInnerTop prTRight prInnerBottom">
				{t var="in_button_5"}Save Changes{/t}{linkbutton name=$in_button_5 link="#" onclick="xajax_themeSave(ThemeApplication.getParamsBS(), 0); ThemeApplication.makeBackupBackground();"}&nbsp;
				{t var="in_button_6"}Clear Edits{/t}{linkbutton name=$in_button_6 link="#" onclick="ThemeApplication.restoreBackground();"}
			
			</div>
     	</div>
    <!-- === BACKGROUND ============================================================================================================= -->
	</div>   
	 
	  <!-- ******** THEME EDITOR END ******** -->
 
        <!-- area under first row tabs end -->

<!-- ********************************* -->	  

<script type="text/javascript" src="/js/ThemeApplication.js" ></script>
  
<script type="text/javascript">
	YAHOO.util.Event.onDOMReady(function () {$smarty.ldelim} 
		ThemeApplication.init();
		ThemeApplication.loadVariables('{$themeVariables}') 
	{$smarty.rdelim} ); 
</script>

<div id="addMenuTarget" style="visibility:hidden; display:none;"></div>
<div id="addMenuPanel" style="visibility:hidden; display:none;">
    <div class="hd">
        <div class='tl'></div>
            <span id="addMenuPanelTitle">{t}Message{/t}</span>
        <div class='tr'></div>
    </div>
    <div class="bd" id="addMenuPanelContent"></div>
</div>   