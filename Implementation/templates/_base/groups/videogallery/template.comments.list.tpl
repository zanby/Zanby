<div class="prIndentBottom">
	<div class="">
		<div class="prInnerTop prText2">{t}Comments{/t}</div>
		{if $AccessManager->canViewCommentsGallery($gallery, $CurrentGroup, $user)}
		{foreach from=$comments item=comment}
		<div class="prClr3 prInnerSmallTop">
			<div class="prFloatLeft prIndentRightSmall"> <img title="" alt="" src="{$comment->getCreator()->getAvatar()->setWidth(30)->setHeight(30)->getImage()}" /> </div>
			<div class="prFloatLeft">{displaylogin href=$comment->getCreator()->getUserPath('profile') user=$comment->getCreator()} {t}says:{/t}
				<p id="commentContent{$comment->id}" class="prIndentTopSmall"> {$comment->content|longwordsimp:54|escape:"html"|nl2br} </p>
				<p id="commentActions{$comment->id}" class="prIndentTopSmall"> {$comment->creationDate|user_date_format:$user->getTimezone()}
					{if $AccessManager->canEditCommentGallery($gallery, $comment, $CurrentGroup, $user)}
					[&#160;<a href="#null" onclick="PGPLApplication.editComment({$comment->id}); return false;">{t}Edit{/t}</a>&#160;]&#160;
					{/if}
					{if $AccessManager->canDeleteCommentGallery($gallery, $comment, $CurrentGroup, $user)}
					[&#160;<a href="#null" onclick="PGPLApplication.showDeleteCommentPanel({$gallery->getId()}, {$video->getId()}, {$comment->id}); return false;"> {t}Delete{/t} </a>&#160;]
					{/if} </p>
			</div>
		</div>
		{if $AccessManager->canEditCommentGallery($gallery, $comment, $CurrentGroup, $user)}
		{*popup_item*}
		<div id="divErrorEdit_{$comment->id}" style="display:none;"></div>
		<div class="prInnerTop" id="commentEdit1{$comment->id}" style="display: none;">
			<textarea name="commentContentTextarea{$comment->id}" id="commentContentTextarea{$comment->id}" rows="8" class="">{$comment->content|escape:"html"}</textarea>
			<div>{t}2000 characters max{/t}</div>
			<div id="commentEdit2{$comment->id}" style="display: none;" class="prInnerSmallTop prTRight"> {t var="in_button"}Save Changes{/t}{linkbutton name=$in_button value="SaveChanges" onclick="clearErrors('divErrorEdit_"|cat:$comment->id|cat:"'); PGPLApplication.saveComment("|cat:$gallery->getId()|cat:", "|cat:$video->getId()|cat:"); return false;"}&nbsp;
				<span class="prIEVerticalAling">{t}or{/t} <a href="#" onclick="clearErrors('divErrorEdit_{$comment->id}'); PGPLApplication.cancelEditComment(); return false;">{t}Cancel{/t}</a></span></div>
		</div>
		{*popup_item*}
		{/if}
		
		{foreachelse}
		<p class="prInnerTop">{t}no comments for this video{/t}</p>
		{/foreach}
		{/if} </div>
	<div id="divErrorNew" style="display:none;"></div>
	{if $AccessManager->canPostCommentsGallery($gallery, $CurrentGroup, $user)}
	<div class="prInnerTop">
		<label for="newComment">{t}add new comment here{/t}</label>
		<div class="prInnerSmallTop">
			<textarea name="newComment" id="newComment" class=""></textarea>
		</div>
		<div>{t}2000 characters max{/t}</div>
		<!-- a href="#null">Some HTML is okay</a -->
		<div class="prInnerSmallTop prTRight"> {t var="in_button_2"}Add Comment{/t}{linkbutton name=$in_button_2 value="AddComment" onclick="clearErrors('divErrorNew'); PGPLApplication.addComment("|cat:$gallery->getId()|cat:", "|cat:$video->getId()|cat:"); return false;"} </div>
	</div>
	{/if} </div>
{literal}
<script type="text/javascript">
    function addError(message, div)
    {
        // div ==  divErrorNew  or  divErrorEdit_{coment`s id}
        try {
            if (document.getElementById(div) == 'undefined') return;
            else var container = document.getElementById(div);
        }
        catch (e) { return; }

        container.innerHTML += "<span style='color:red;font-weight:bold;'>ERROR: </span><span>"+message+"</span><br />";
        container.style.display = '';
    }

    function clearErrors(div)
    {
        try {
            if (document.getElementById(div) == 'undefined') return;
            else {
                document.getElementById(div).style.display = 'none';
                document.getElementById(div).innerHTML = '';
            }
        }
        catch (e) { return; }
    }
</script>
{/literal}