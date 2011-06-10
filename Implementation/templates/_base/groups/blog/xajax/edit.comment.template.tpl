{*popup_item*}
{form from=$form}
<table class="prForm">
    <thead>
        <tr><th colspan="3" class="prNoPadding">
            {form_errors_summary}
        </th></tr>
    </thead>
    <tbody>
        <tr>
            <td>
                {form_textarea name="comment"|cat:$post->getId() id='commentContent'|cat:$post->getId() value=$post->getContent()|escape}
            </td>
        </tr>
        <tr>
            <td class="prTCenter">    
					{t var="in_button"}Save Changes{/t}            
                	{linkbutton name=$in_button link='#null' onclick=$oklLink}&nbsp;
					<span class="prIEVerticalAling">{t}or{/t} <a href="#null" onclick=$cancelLink>{t}Cancel{/t}</a></span>             
            </td>
        </tr>
    </tbody>
</table>
{/form}
{*popup_item*}