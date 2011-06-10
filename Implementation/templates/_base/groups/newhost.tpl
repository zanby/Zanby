<div class="prDropBoxInner">
    <div class="prText2 prTCenter">
        {t}{tparam value=$group->getName()}Are you agree to be the host of %s group?{/t}
    </div>
	<div class="prTCenter prInner"> 
        {t var="in_button"}Yes{/t}{linkbutton name=$in_button link=$group->getGroupPath('setnewhost')|cat:"yes/1/"} &#160;
        {t var="in_button_2"}No{/t}{linkbutton name=$in_button_2 link=$group->getGroupPath('setnewhost')|cat:"no/1/"} 
    </div>
</div>