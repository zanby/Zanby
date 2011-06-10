{literal}
<script language="javascript">
    var editingComment = false;
    var editingCommentObj = false;
    
    function editComment(i){
        if (editingComment){
            editingCommentObj.innerHTML = editingComment;
        }
        editingCommentObj = document.getElementById('comment'+i);
        editingComment = editingCommentObj.innerHTML;
        editingCommentObj.innerHTML = "<form name=editcomment method=post><table width=100%>" +
            "<tr><td><textarea name=message style='width:100%'>" + editingComment + "</textarea></td></tr>" +
            "<tr><td><input type=button value='cancel' style='width:50%'><input type=submit value='save' style='width:50%'></td></tr></table></form>";
    }
</script>
{/literal}

<table width=100% border=0 cellpadding=1 cellspacing=0>
{foreach item=c from=$comments}
    <tr><td style="font-size:10px; color:#666;">{$c->user}</td><td class=r>{$c->creationDate}</td></tr>
    <tr><td colspan=2 id=comment{$c->id}>{$c->content}<td></tr>
    <tr><td colspan=2 class=r style="font-size:10px; border-bottom:1px solid #f00;">
    {if $c->user == $user->getLogin()}
       <a href="#" onclick="editComment({$c->id});">{t}edit{/t}</a> | 
       <a href="act/deletepost/">{t}delete{/t}</a>
    {/if}
    </td></tr>
{foreachelse}
    {t}no comments for this picture{/t}
{/foreach}
</table>

<b>{t}add new comment here{/t}</b>
<form name=addcomment method=post>
  <textarea name=message></textarea><br>
  <input type=submit value=comment>
</form>