<div class="prInner prTCenter">
			 {t}{tparam value=$objEvent->getTitle()|escape:html}Are you sure you want to be a host to event %s ?{/t}
			<div class="prInnerTop co-buttons-pannel-pop">
				{t var='button_01'}Accept Request{/t}
				{linkbutton name=$button_01 link=$linkURL|cat:'accept/' } &#160;
				{t var='button_02'}Decline Request{/t}
				{linkbutton name=$button_02 link=$linkURL|cat:'decline/'}
			</div> 
		</div>