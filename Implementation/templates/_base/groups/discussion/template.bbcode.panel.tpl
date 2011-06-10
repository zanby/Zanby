<div>
	<a href="#null" onClick="bbstyle(0)"><img src="{$AppTheme->images}/decorators/co/co-ep-bold.gif" title="Bold" alt="Bold" /></a>
	<a href="#null" onClick="bbstyle(2)"><img src="{$AppTheme->images}/decorators/co/co-ep-italic.gif" title="Italic" alt="Italic" /></a>
	<a href="#null" onClick="bbstyle(4)"><img src="{$AppTheme->images}/decorators/co/co-ep-underline.gif" title="Underline" alt="Underline" /></a>
	<a href="#null" onClick="bbstyle(6)"><img src="{$AppTheme->images}/decorators/co-ep-quote.gif" title="Quote" alt="Quote" /></a>
		<td nowrap="nowrap">&nbsp;</td>
</div>
<div class="prIndentTopSmall prIndentBottom">
			<select name="addbbcode18" onChange="bbfontstyle('[color=' + this.form.addbbcode18.options[this.form.addbbcode18.selectedIndex].value + ']', '[/color]'); this.form.addbbcode18.selectedIndex = 0;">
				<option style="color:black; background-color: #FFFFFF " value="#444444" class="genmed">{t}default{/t}</option>
				<option style="color:darkred; background-color: #DEE3E7" value="darkred" class="genmed">{t}darkred{/t}</option>
				<option style="color:red; background-color: #DEE3E7" value="red" class="genmed">{t}red{/t}</option>
				<option style="color:orange; background-color: #DEE3E7" value="orange" class="genmed">{t}orange{/t}</option>
				<option style="color:brown; background-color: #DEE3E7" value="brown" class="genmed">{t}brown{/t}</option>
				<option style="color:yellow; background-color: #DEE3E7" value="yellow" class="genmed">{t}yellow{/t}</option>
				<option style="color:green; background-color: #DEE3E7" value="green" class="genmed">{t}green{/t}</option>
				<option style="color:olive; background-color: #DEE3E7" value="olive" class="genmed">{t}olive{/t}</option>
				<option style="color:cyan; background-color: #DEE3E7" value="cyan" class="genmed">{t}cyan{/t}</option>
				<option style="color:blue; background-color: #DEE3E7" value="blue" class="genmed">{t}blue{/t}</option>
				<option style="color:darkblue; background-color: #DEE3E7" value="darkblue" class="genmed">{t}darkblue{/t}</option>
				<option style="color:indigo; background-color: #DEE3E7" value="indigo" class="genmed">{t}indigo{/t}</option>
				<option style="color:violet; background-color: #DEE3E7" value="violet" class="genmed">{t}violet{/t}</option>
				<option style="color:white; background-color: #DEE3E7" value="white" class="genmed">{t}white{/t}</option>
				<option style="color:black; background-color: #DEE3E7" value="black" class="genmed">{t}black{/t}</option>
			</select>
			<select name="addbbcode20" onChange="bbfontstyle('[size=' + this.form.addbbcode20.options[this.form.addbbcode20.selectedIndex].value + ']', '[/size]'); this.form.addbbcode20.selectedIndex = 0;">
				<option value="1" class="genmed">{t}small{/t}</option>
				<option value="2" selected class="genmed">{t}default{/t}</option>                    
				<option value="4" class="genmed">{t}big{/t}</option>
				<option  value="5" class="genmed">{t}very big{/t}</option>
			</select>
</div>