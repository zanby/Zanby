{tab template="admin_subtabs" active='import_group'}
    {tabitem link="`$BASE_URL`/`$LOCALE`/adminarea/importgroups/" name="import_group"}{t}Import groups{/t}{/tabitem}
{/tab}

<div class="prDropBoxInner">
	<h2>{t}{tparam value=$rec_succ}%s groups created successfilly:{/t}</h2>
{t var="in_button"}Get result CSV file{/t}{linkbutton name=$in_button link=$path_res} <br /><br />
{t var="in_button_2"}Ok{/t}{linkbutton name=$in_button_2 link=$admin->getAdminPath('importgroups/')}
</div>


