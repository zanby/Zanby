<div id="SWFUpload" style="display:none;">
    <div id="flashUI1">
		<input type="hidden" id="files_box_height" name="files_box_height" value="38px">
		<div id="files_box" style="overflow:auto;">
            <fieldset class="flash" id="fsUploadProgress1">
                <legend></legend>
            </fieldset>
		</div>
		<div class="prClr2 prInnerSmall">
			<div class="prFloatLeft">
			{t var="in_button"}Browse Files{/t}
            {linkbutton name=$in_button onclick="upload1.selectFiles(); return false;"}
			</div>         
        </div>                     
    </div>                     
</div>
