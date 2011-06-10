function toggle(_obj) {
	if (_obj.style.display == '') {
		_obj.style.display = 'none';
	} else {
		_obj.style.display = '';
	}
}

function toggleByClassName(_obj, _className) {

	if (_obj.innerHTML == 'Show') {
		_obj.innerHTML = 'Hide';
		_obj.className = 'prArrow-down';
	} else {
		_obj.innerHTML = 'Show';
		_obj.className = 'prArrow';
	}	
	
	//var _divs = _obj.parentNode.getElementsByTagName('div');
	var _divs = _obj.parentNode.parentNode.parentNode.getElementsByTagName('div');
	for (var i=0; i<_divs.length; i++) {
		if (_divs[i].className == _className) {        
			toggle(_divs[i]);
			break;
		}	
	}

} 

function adjustTabs(_id) {
	var _tabs = document.getElementById(_id);
	if (_tabs) {
		var _lis = 	_tabs.getElementsByTagName('li');
		if (_lis[2].className.indexOf('active') < 0) {
			_lis[2].className = 'noborder';
		}
		for (var i=3; i < _lis.length; i++) {
			if ( (_lis[i-1].className == 'active') && (_lis[i].className != 'last')) {
				_lis[i].className += ' noborder';
			}
		}
	}

}

function leftMenuInit(_id) {
	var _menu = document.getElementById(_id);	
	if (_menu) {
		var _lis = _menu.getElementsByTagName('li');
		for (var i=0; i < _lis.length; i++) {

			_lis[i].onmouseover = function () {
				if (this.className == 'znbFirstItem')	{
					this.className = 'znbHoverFirst';	
					return;
				}
				if (this.className == 'znbLastItem')	{
					this.className = 'znbHoverLast';	
					return;
				}
				if (this.className == 'znbChosen')	{
					return;
				}
				this.className = 'znbHover';
			}	

			_lis[i].onmouseout = function () {
				if (this.className == 'znbHoverFirst')	{
					this.className = 'znbFirstItem';	
					return;
				}
				if (this.className == 'znbHoverLast')	{
					this.className = 'znbLastItem';	
					return;
				}				
				if (this.className == 'znbChosen')	{
					return;
				}
				this.className = '';
			}	
			
			_lis[i].onclick = function () {
					window.location.href = this.getElementsByTagName('a')[0].href;
			}

			
		}
	}
}

function initMessagesMenu(_id) {
	var _menu = document.getElementById(_id);

	if (_menu) {
		var _items = _menu.getElementsByTagName('li');
		
		var _activeIndex = 0;
		for (var i=0; i < _items.length; i++) {
			if (_items[i].className == 'active') {
				_activeIndex = i;
				break;
			}	
		}
		
		if (_activeIndex == 0) {
			_items[0].className = 'firstActive';
			_items[1].className = 'second';
			_items[2].className = '';
			_items[3].className = '';
			_items[4].className = 'last';
			
		} else if (_activeIndex == 1) {
			_items[0].className = 'first';
			_items[1].className = 'secondActive';
			_items[2].className = '';
			_items[3].className = '';
			_items[4].className = 'last';			
			
		} else if (_activeIndex == 2) {
			_items[0].className = 'first';
			_items[1].className = 'second';
			_items[2].className = 'active';
			_items[3].className = '';
			_items[4].className = 'last';			
			
		} else if (_activeIndex == 3) {
			_items[0].className = 'first';
			_items[1].className = 'second';
			_items[2].className = '';
			_items[3].className = 'active';
			_items[4].className = 'last';		
		
		} else {
			_items[0].className = 'first';
			_items[1].className = 'second';
			_items[2].className = '';
			_items[3].className = '';
			_items[4].className = 'lastActive';		
		}
	}
}


