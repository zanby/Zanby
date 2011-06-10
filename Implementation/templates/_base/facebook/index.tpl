{literal}
<script type="text/javascript">
    FB.init("cc8183ad998bdabc348447d33821bda9", "/xd_receiver.htm");    
    $(function(){
        $('#lnkShowDialog').bind('click', function(){
        	FB.Connect.showFeedDialog(128946482484, '');
        })
		/*
		$('#lnkShowDialog').bind('click', function(){
			FB.Connect.streamPublish();
			FB.Connect.showFeedDialog(128946482484, '');
		})
		*/
    })
</script>
{/literal}
<a href="#" id="lnkShowDialog">{t}Show{/t}</a>