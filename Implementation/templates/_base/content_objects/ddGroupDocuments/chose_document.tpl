{*popup_item*}
<div style="width: 350px;">
    <!-- popup content -->
    <div style="margin-left:20px; font-size:12px;">{t}Group Documents{/t}</div>
    <div style="margin-left:20px; height:200px; overflow:auto;" id="{$tree_div_id}"> </div>
    <!-- /popup content -->
    <!-- content object buttons pannel -->
        
    <div class="clear" style="height:10px;"><span /></div>
    
    <!-- popup content -->
    <div style="margin-left:20px; font-size:12px;">{t}My Documents{/t}</div>
    <div style="margin-left:20px; height:200px; overflow:auto;" id="{$tree_div_id_user}"> </div>
    <!-- /popup content -->
	<!-- content object buttons pannel -->
	<div class="co-buttons-pannel" style="background:none; border:0px;">
		<div style="padding-top: 10px;">
			<!-- - half of buttons group width for cenral alignment -10px for poups -->
			<a href="#" onclick="popup_window.close(); return false;" name="Cancel" style="float: left; margin-left: 0px; display: inline;">{t}Cancel{/t}</a>
		</div>
	</div>
	<!-- /content object buttons pannel -->
</div>
{*popup_item*}