<div class="prInnerTop" id="SWFUpload" style="display:none;">
	<table class="prForm">
		<col width="10%" />
		<col width="90%" />
		<tr>
			<td class="prTRight">{t}Upload Files:{/t}</td> 
			<td>
				<div id="flashUI1" class="prGrayBorder prInner prClr">
					<input type="hidden" id="files_box_height" name="files_box_height" value="98px">
					<div id="files_box" style="overflow:auto;">
						<fieldset class="flash" id="fsUploadProgress1">
						<legend style="display:none;"></legend>
						</fieldset>
					</div>
					<div class="prInnerSmall prClr3">
							<div class="prFloatLeft prIndentRight">
							{t var='button'}Choose Files{/t}
							{linkbutton id = "browse" name=$button onclick="upload1.selectFiles(); return false;"} </div>
							<div id="filesCount" class="prFloatLeft">{t}<strong>0</strong> Files{/t}</div>
							<div id="totalSize" class="prFloatLeft">{t}Total: <strong>0</strong> Kb{/t}</div>                                            
					</div>                                           
				</div>
			</td>
		</tr>        
	</table>
</div>