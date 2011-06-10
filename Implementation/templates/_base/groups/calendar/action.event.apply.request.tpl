<div>
	 {t}Are you sure you want to be a host to event{/t} &quot;{$objEvent->getTitle()|escape:html}&quot;?
	<div class="prInnerTop">
		{t var="in_button"}Accept Request{/t}
		{linkbutton name=$in_button link=$linkURL|cat:'accept/' } &#160;
		{t var="in_button_2"}Decline Request{/t}
		{linkbutton name=$in_button_2 link=$linkURL|cat:'decline/'}
	</div> 
</div>