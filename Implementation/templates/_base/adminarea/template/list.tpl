{*
<div class="prTRight">
{t var="in_button"}Create New Template{/t}{linkbutton name=$in_button link=$admin->getAdminPath('newtemplate/')}
</div>
*}


<div class="prTLeft">
       <div class="prIndentLeftLarge">
			<a class="prButton"
			 id="importAll"
             href="{$admin->getAdminPath('importTemplates')}/"
            
            ><span>Import All from file</span></a>

        &nbsp;
        
			<a class="prButton"
			 id="exportAll"
             href="{$admin->getAdminPath('exportTemplates')}/"
            
            ><span>Export all into file</span></a>
        
    </div>
</div>

<!-- result begin -->
	<table cellspacing="0" cellpadding="0" class="prResult">
	    <thead>
	        <tr>
	            <th class="prTLeft">{t}Template{/t}</th>
	            <th width="10%" class="prTLeft">{t}Created{/t}</th>
	            <th width="10%" class="prTLeft">{t}Edited{/t}</th>
	            <th class="prTLeft">&nbsp;</th>
	        </tr>
	    </thead>
	        {foreach item=template key=tkey from=$templatesList}
				<tr>
	                <td class="prTLeft">
						{$template.description}&nbsp;
	                </td>
	                <td class="prTLeft">
	                    {$template.creation_date|date_locale:'DATE_MEDIUM'}
	                </td>
	                <td class="prTLeft">
	                    {$template.change_date|date_locale:'DATE_MEDIUM'}
	                </td>	                
					<td><a href="{$admin->getAdminPath('templates/uid/')}{$template.uid}/">{t}Edit{/t}</a>&nbsp;&nbsp;<a href="{$admin->getAdminPath('removeTemplate/uid/')}{$template.uid}/" onclick="return confirm('Are you sure to delete \'{$template.uid}\'?');">{t}Delete{/t}</a>&nbsp;&nbsp;<a href="{$admin->getAdminPath('importTemplate/uid/')}{$template.uid}/">{t}Import{/t}</a>&nbsp;&nbsp;<a href="{$admin->getAdminPath('exportTemplate/uid/')}{$template.uid}/">{t}Export{/t}</a>
	                </td>
	            </tr>
	        {/foreach}
	</table>
<!-- result end -->
