var currentComment = 0;
function reset_order_select()
{
    document.getElementById("order_id").selectedIndex = 0;
}

function confirmDeleteComment(commentId)
{
    YAHOO.util.Dom.get('deleteCommentId').value = commentId;

    popup_window.content(YAHOO.util.Dom.get('deleteCommentPanel').innerHTML); 
    popup_window.title('Please confirm');
    popup_window.width(500); 
    popup_window.height(350);
    popup_window.open(); 
}

function confirmDeleteRecord(recordId)
{
    YAHOO.util.Dom.get('deleteRecordId').value = recordId;
    
    popup_window.content(YAHOO.util.Dom.get('deleteRecordPanel').innerHTML); 
    popup_window.title('Please confirm');
    popup_window.width(500); 
    popup_window.height(350);
    popup_window.open(); 
}

function editComment(commentId)
{
    YAHOO.util.Dom.get('commentBody'+commentId).style.display='none';
    YAHOO.util.Dom.get('commentEdit'+commentId).style.display='block';
    if (currentComment != 0) editCommentCancel(currentComment);
    currentComment = commentId;
}

function editCommentCancel(commentId)
{
    YAHOO.util.Dom.get('commentBody'+commentId).style.display='block';
    YAHOO.util.Dom.get('commentEdit'+commentId).style.display='none';
    currentComment = 0;
}

function saveComment(commentId)
{
    if ( commentId.trim() == '') {
        if ( YAHOO.util.Dom.get('commentNew').value.trim() == '' ) return false;
        xajax_list_view_append_comment(xajax.getFormValues('formComment')); 
    } else {
        if ( YAHOO.util.Dom.get('commentContent'+commentId).value.trim() == '' ) return false;
        xajax_list_view_save_comment(commentId, YAHOO.util.Dom.get('commentContent'+commentId).value);
    }
}