<div id="instructions" class="">
	<div class="prClr2">
		<h2 class="prFloatLeft">{t}Outlook{/t}</h2>
		<div class="fl-right"><a href="#null" onclick="closeInstructions(); return false;"></a></div>
	</div>
	<div class="prInnerTop prInnerBottom prClr2">	
			<h2 class="prTCenter">{t}We won't email anyone without <br /> your permission!{/t}</h2>
			{if $file_type == 'outlook'}
				<div class="prInnerTop prInnerBottom">
					<div class="prInnerTop prInnerBottom">{t}To export a CSV or tab-delimited text file from Outlook:{/t}</div>
					<ol class="prIndentLeft">
					   <li>{t}Open Outlook{/t}</li>
					   <li>{t}Select "Import and Export" from the File menu{/t}</li>
					   <li>{t}When the wizard opens, select "Export to a file" and click "Next"{/t}</li>
					   <li>{t}Select "Comma separated values (Windows)" and click "Next"{/t}</li>
					   <li>{t}Select the Contacts folder you would like to export and click "Next"{/t}</li>
					   <li>{t}Choose a filename and a place to save the file (for instance, "Contacts.csv" on the Desktop), then click "Next"{/t}</li>
					   <li>{t}Confirm what you are exporting: make sure the checkbox next to "Export..."  is checked and click "Finish"{/t}</li>
					</ol>
				</div>
			{elseif $file_type == 'outlook_express'}
				<h4 class="prInnerTop">{t}Outlook Express{/t}</h4>
			{elseif $file_type == 'windows'}
				<h4 class="prInnerTop">{t}Windows Address Book{/t}</h4>
			{elseif $file_type == 'thunderbird'}
				<h4 class="prInnerTop">{t}Thunderbird{/t}</h4>
			{elseif $file_type == 'palm_desktop'}
				<h4 class="prInnerTop">{t}Palm Desktop{/t}</h4>
			{elseif $file_type == 'palm_desktop_vcard'}
				<h4 class="prInnerTop">{t}Palm Desktop (vCard){/t}</h4>
			{elseif $file_type == 'entourage'}
				<h4 class="prInnerTop">{t}Entourage{/t}</h4>
			{elseif $file_type == 'mac'}
				<h4 class="prInnerTop">{t}Mac OS X Address Book{/t}</h4>
			{else}
				<h4 class="prInnerTop">{t}Other{/t}</h4>
			{/if}
				{t}{tparam value=$SITE_NAME_AS_STRING}To upload your file to %s,  hit "Browse" below and select the file you created.{/t}
		<div class="prInnerTop prInnerBottom">
			<ul>
				{if $file_type!='outlook'}<li><a href="#null" onclick="xajax_addressbook_instruction('outlook');">{t}Outlook{/t}</a></li>{/if}
				{if $file_type!='outlook_express'}<li><a href="#null" onclick="xajax_addressbook_instruction('outlook_express');">{t}Outlook Express{/t}</a></li>{/if}
				{if $file_type!='windows'}<li><a href="#null" onclick="xajax_addressbook_instruction('windows');">{t}Windows Address Book{/t}</a></li>{/if}
				{if $file_type!='thunderbird'}<li><a href="#null" onclick="xajax_addressbook_instruction('thunderbird');">{t}Thunderbird{/t}</a></li>{/if}
				{if $file_type!='palm_desktop'}<li><a href="#null" onclick="xajax_addressbook_instruction('palm_desktop');">{t}Palm Desktop{/t}</a></li>{/if}
				{if $file_type!='palm_desktop_vcard'}<li><a href="#null" onclick="xajax_addressbook_instruction('palm_desktop_vcard');">{t}Palm Desktop (vCard){/t}</a><li>{/if}
				{if $file_type!='entourage'}<li><a href="#null" onclick="xajax_addressbook_instruction('entourage');">{t}Entourage{/t}</a></li>{/if}
				{if $file_type!='mac'}<li><a href="#null" onclick="xajax_addressbook_instruction('mac');">{t}Mac OS X Address Book{/t}</a></li>{/if}
				{if $file_type!='other'}<li><a href="#null" onclick="xajax_addressbook_instruction('other');">{t}Other{/t}</a></li>{/if}
			</ul>
		</div>
</div>
</div>