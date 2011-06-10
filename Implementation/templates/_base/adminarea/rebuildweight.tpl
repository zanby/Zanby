
    <div class="znbToggledGroupsWrapper" style="position: absolute; width:330px; left:50%; margin-left: -165px;">
        <div class="znbToggled" style="width:330px; text-align: center;">
            <h1>{t}Rebuild weigth{/t}</h1>
            <div class="znbClear"><span/></div>
            {if $mode == 'complete'}
	            <p style="font-size:13px;">{t}Process completed successfully{/t}</p>
            {else}
            <p style="font-size:13px;"> {t}Process may take several minutes. <br /> 
            Press button bellow to start.{/t}
            </p>
            <div class="znbClear" style="height: 10px;"><span/></div>
            {t var="in_button"}Rebuild{/t}{linkbutton style="margin-left:120px;" name=$in_button link="`$BASE_URL`/`$LOCALE`/adminarea/rebuildweight/mode/start/"}
            {/if}
            <div class="znbClear" style="height: 10px;"><span/></div>
        </div>
    </div>