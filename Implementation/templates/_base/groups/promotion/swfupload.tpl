<div class="prInnerTop" id="SWFUpload" style="display:none;">
	<table class="prForm">
		<col width="12%" />
		<col width="88%" />
		<tr>
			<td class="prTRight"><label>{t}Upload Files:{/t}</label></td>
			<td>
            <div id="flashUI1">
                <input type="hidden" id="files_box_height" name="files_box_height" value="37px">
                <div id="files_box" style="overflow:auto;">
                    <fieldset class="flash" id="fsUploadProgress1">
                    <legend style="display:none;"></legend>
                    </fieldset>
                </div>
                <div class="prClr3 prInner prGrayBorder">
					<div class="prFloatLeft prIndentRight">
					{t var="in_button"}Choose Files{/t}
					{linkbutton id = "browse" name=$in_button onclick="upload1.selectFiles(); return false;"}</div>
					<div id="filesCount" class="prFloatLeft"><strong>0</strong> {t}Files{/t}</div>
					<div id="totalSize" class="prFloatLeft">{t}Total:{/t} <strong>0</strong> {t}Kb{/t}</div>
				</div>                     
            </div>            
        	</td>
    	</tr>        
	</table>
</div>
