{literal}
<script type="text/javascript">

function showAddlinkForm()
{
    var addContactMain = document.getElementById('formAddContact');
    if (addContactMain) {
        addContactMain.style.display = 'block';
    }
    
    var addList = document.getElementById('formAddMaillist');
    if (addList) {
        addList.style.display = 'none';
    }
}

function toggleContactMore()
{
    
    var linkMore = document.getElementById('linkMore');
    var linkCollapse = document.getElementById('linkCollapse');
    var advancedFields = document.getElementById('advancedFields');

    if (linkMore && linkCollapse) {
        if (linkMore.style.display != 'none') {
            linkMore.style.display = 'none';
            linkCollapse.style.display = 'block';
            document.getElementById('expand_id').value = 1;
            advancedFields.style.display = 'block';
        } else {
            linkMore.style.display = 'block';
            linkCollapse.style.display = 'none';
            document.getElementById('expand_id').value = 0;
            advancedFields.style.display = 'none';
        }
    }
}

function showAddlistForm()
{

    var addContactMain = document.getElementById('formAddContact');
    if (addContactMain) {
        addContactMain.style.display = 'none';
    }
    
    var addList = document.getElementById('formAddMaillist');
    if (addList) {
        addList.style.display = 'block';
    }
  
}

</script>
{/literal}

<div id="formAddContact"{if $add_list_tab} style="display:none"{/if}>
{tab}
    {tabitem onclick="showAddlinkForm(); return false;" active="1"}{t}Add Contact{/t}{/tabitem}
    {tabitem onclick="showAddlistForm(); return false;"}{t}Add List{/t}{/tabitem}
{/tab}
    {form from=$formAddContact}
    {form_hidden name="contact[id]" value=$contact.id}
    {form_hidden name="contact[expand]" value=$contact.expand|default:0 id="expand_id"}
    {form_errors_summary}
    <table border="0" width="215" cellspacing="5" cellpadding="0">
      <tr>
          <td width="50%">
            {t}First Name{/t}<br />
            {form_text name="contact[firstName]" value=$contact.firstName style="width:100%;"}
          </td>
          <td width="50%">
            {t}Last Name{/t}<br />
            {form_text name="contact[lastName]" value=$contact.lastName style="width:100%;"}
          </td>
      </tr>
      <tr>
          <td colspan="2">
            {t}Email Address{/t}<br />
            {form_text name="contact[email]" value=$contact.email style="width:100%;"}
          </td>
      </tr>
    </table>
    <div style="text-align:right; margin:5px;">
        <div id="linkMore"{if $contact.expand} style="display:none;"{/if}><a href="#" onclick="toggleContactMore();return false;">{t}More{/t} &gt;&gt;</a></div>
        <div id="linkCollapse"{if !$contact.expand} style="display:none;"{/if}><a href="#" onclick="toggleContactMore();return false;">{t}Collapse{/t} &lt;&lt;</a></div>
    </div>        
    <table border="0" width="215" cellspacing="5" cellpadding="0" id="advancedFields"{if !$contact.expand} style="display:none;"{/if}>
      <tr>
        <td>
            {t}Secondary Email Address{/t}<br />
            {form_text name="contact[email2]" value=$contact.email2 style="width:100%;"}
        </td>
      </tr>
      <tr>
        <td>
            <b><i>{t}Phone{/t}</i></b><br />
        </td>
      </tr>
      <tr>
        <td>
            {t}Home{/t}<br />
            {form_text name="contact[phoneHome]" value=$contact.phoneHome style="width:100%;"}
        </td>
      </tr>
      <tr>
        <td>
            {t}Business{/t}<br />
            {form_text name="contact[phoneBusiness]" value=$contact.phoneBusiness style="width:100%;"}
        </td>
      </tr>
      <tr>
        <td>
            {t}Mobile{/t}<br />
            {form_text name="contact[phoneMobile]" value=$contact.phoneMobile style="width:100%;"}
        </td>
      </tr>
      <tr>
        <td>
            <b><i>{t}Address{/t}</i></b><br />
        </td>
      </tr>
      <tr>
        <td>
            {t}Street{/t}<br />
            {form_text name="contact[street]" value=$contact.street style="width:100%;"}
        </td>
      </tr>
      <tr>
        <td>
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td width="50%">{t}City{/t}</td>
                    <td width="15%">{t}State{/t}</td>
                    <td width="35%">{t}Zip{/t}</td>
                </tr>
                <tr>
                    <td style="padding-right:5px;">{form_text name="contact[city]" value=$contact.city style="width:100%;"}</td>
                    <td style="padding-right:5px;">{form_text name="contact[state]" value=$contact.state style="width:100%;"}</td>
                    <td>{form_text name="contact[zip]" value=$contact.zip style="width:100%;"}</td>
                </tr>
            </table>
        </td>
      </tr>
      <tr>
        <td>
            {t}Notes{/t}<br />
            {form_textarea  name="contact[notes]" value=$contact.notes style="width:100%;"}
        </td>
      </tr>
      <tr>
        <td align="right">
        </td>
      </tr>
    </table>
    <div style="text-align:right;margin:5px;">
	{t var='button_01'}Add New Contact{/t}
	{form_submit value=$button_01 name="submit"}
	</div>
    {/form}
</div>

<div id="formAddMaillist"{if !$add_list_tab} style="display:none"{/if}>
{tab}
    {tabitem onclick="showAddlinkForm(); return false;"}{t}Add Contact{/t}{/tabitem}
    {tabitem onclick="showAddlistForm(); return false;" active="1"}{t}Add List{/t}{/tabitem}
{/tab}
    {form from=$formAddMaillist}
    {form_errors_summary}
    <table border="0" width="215">
        <tr>
            <td>
                {t}Name of the list{/t}<br />
                {form_text name="item[name]" value="" style="width:100%;"}
            </td>
        </tr>
        <tr>
            <td>
                {t}Contacts in list{/t}<br />
                {form_text name="item[contacts]" value="" style="width:100%;"}
            </td>
        </tr>
        <tr>
            <td>
                {t}Associate list with this group (optional){/t}<br />
                {form_select name="item[groupId]" options=$addMaillistGroups style="width:100%;"}
            </td>
        </tr>
        <tr>
            <td align="right">
				{t var='button_02'}Add New List{/t}
                {form_submit value=$button_02 name="item[submit]"}
            </td>
        </tr>
    </table>
    {/form}
</div>


<br /><br />
<hr />
<br />
<div>
{* your groups block *}
   {t}Your Groups:{/t}<br /><br />
    {foreach item=g from=$groups}
        <a href="{$g->getGroupPath()}{$LOCALE}/summary/">{$g->getName()|escape}</a><br />
    {foreachelse}
        {t}You have no groups yet{/t}<br />
    {/foreach}
    <br />
</div>
{* your maillists block *}
<div>
    {t}Your Mailing Lists:{/t}<br /><br />
    {foreach item=m from=$maillists}
        <a href="{$m->user->getUserPath('addressbook/maillist')}{$m->id}/">{$m->name|escape}</a><br />
    {foreachelse}
        {t}You have no maillists yet{/t}<br />
    {/foreach}
    <br />
</div>
<hr />
<a href="/en/addressbook/import/1/">{t}Import{/t}</a>
