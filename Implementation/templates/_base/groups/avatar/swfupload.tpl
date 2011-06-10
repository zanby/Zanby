<div class="prInnerTop" id="SWFUpload" style="display:none;">
	 <table class="prForm">
		<col width="27%" />
		<col width="48%" />
		<col width="25%" />
		<tr>
			<td class="prTRight">{t}<strong>Upload Files</strong>:{/t}</td> 
			<td>
				<div id="flashUI1" class="prClr">
					<input type="hidden" id="files_box_height" name="files_box_height" value="">
						<div id="files_box" class="prRelative">
							<fieldset class="flash prInnerTop prInnerLeft" id="fsUploadProgress1">
								<legend style="display:none;"></legend>
							</fieldset>
						</div>
					<div class="prInnerSmall prClr3">
						<div class="prFloatLeft prInnerLeft">{t var="in_button"}Choose Files{/t}{linkbutton id = "browse" name=$in_button onclick="upload1.selectFiles(); return false;"}</div>
						<div id="filesCount" class="prFloatLeft prInner">{t}<strong>0</strong> Files{/t}</div>
						<div id="totalSize" class="prFloatLeft prInner">{t}Total: <strong>0</strong> Kb{/t}</div>                                                               
					</div>
				</div>                    
			</td>
			<td/>
		</tr>        
	</table>     
</div>