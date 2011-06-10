<h1>{t}Delete Group{/t}</h1>
<a href="#" onClick="xajax_privileges_deletegroup_hide('{$gid}'); return false;">{t}Hide{/t}</a>
<h2>{t}Delete group and all group data.{/t}</h2>

    <a id="GroupSettingsDeleteGroupAnchor"></a>
    <h4>{t}{tparam value=$currentGroup->getName()}You have permanently deleted the '%s' family group.{/t}</h4>
    <p>
        <a href="{$BASE_URL}">{t}{tparam value=$SITE_NAME_AS_STRING}Browse %s.{/t}</a>
	</p>
    <p>
       {t}{tparam value=$BASE_URL}{tparam value=$LOCALE} If you have a special question, please  <a href="%s/%s/info/contactus/">Contact Us</a>.{/t}
    </p>

