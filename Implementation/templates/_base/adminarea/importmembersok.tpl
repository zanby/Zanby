{tab template="admin_subtabs" active='import_members'}
    {tabitem link="`$BASE_URL`/`$LOCALE`/adminarea/importmembers/" name="import_members"}{t}Import members{/t}{/tabitem}
{/tab}

<div class="prDropBoxInner">
	<h2>{t}{tparam value=$rec_succ}%s members created successfilly:{/t}</h2>
	{linkbutton name="Get result CSV file" link=$path_res} <br />
	<br />
	<h2>{t}{tparam value=$rec_added}%s members added to groups{/t}</h2>
	{t var="in_button"}Ok{/t}{linkbutton name=$in_button link=$admin->getAdminPath('importmembers/') } </div>
