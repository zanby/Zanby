
	$(function(){
		popup_window.init();
	})

	var popup_window = null;
    if ( !popup_window ) {
    	popup_window = function () {
            return {
            	_targetId               : '_popup_window_',
                _target                 : null,
                _content_targetId       : '_popup_window_',
                _content_target         : null, /* div to apply dynamic content */
                _title                  : null,
                _content                : null,
                _height                 : null,
                _width                  : null,
                _modal                  : null,
                _close_by_click_around  : true,
                //_parsetarget          : null,
                _reload                 : null,
                _fixed                  : null,
                _close_by_esc           : true, //  default ESC close popup
                init : function () {
                    if ( !document.getElementById(this._content_targetId) ) {
                    	this._content_target = document.createElement('DIV');
                    	this._content_target.id = "_popup_window_";
                    	this._content_target.style.display = 'none';
                        document.body.appendChild(this._content_target);
                    } else {
                    	this._content_target = document.getElementById(this._content_targetId);
                    }
                },
                target : function () {
                	if ( arguments.length != 0 ) {
                		if ( !document.getElementById(arguments[0]) ) {
                			//alert('Incorrect target. Element isn\'t exists');
                			return;
                		}
                		this._targetId = arguments[0];
                		this._target = document.getElementById(this._targetId);
                        this._content = null;
                	}
                	else return this._target;
                },
                /*
                parsetarget : function () {
                	if ( arguments.length != 0 ) this._parsetarget = arguments[0];
                	else return this._parsetarget;
                },
                */
                title : function () {
                	if ( arguments.length != 0 ) this._title = arguments[0];
                	else return this._title;
                },
                content : function () {
                	if ( arguments.length != 0 ) {
                        this._content = arguments[0];
                        this._target = null;
                	} else return this._content;
                },
                height : function () {
                	if ( arguments.length != 0 ) this._height = arguments[0];
                	else return this._height;
                },
                width : function () {
                	if ( arguments.length != 0 ) this._width = arguments[0];
                	else return this._width;
                },
                modal : function () {
                	if ( arguments.length != 0 ) this._modal = arguments[0];
                	else return this._modal;
                },
                isreload : function () {
                	if ( arguments.length != 0 ) this._reload = arguments[0];
                	else return this._reload;
                },
                closeByClickAround : function () {
                    if ( arguments.length == 0 ) return this._close_by_click_around;
                    if ( arguments[0] ) this._close_by_click_around = true;
                    else                this._close_by_click_around = false;
                },
                closeByEsc : function () {
                    if ( arguments.length == 0 ) return this._close_by_esc;
                    if ( arguments[0] ) this._close_by_esc = true;
                    else                this._close_by_esc = false;
                },
                fixed : function () {
                    if ( arguments.length == 0 ) return this._fixed;
                    if ( arguments[0] ) this._fixed = true;
                    else                this._fixed = false;
                },
                /**
                 *
                 */
                open : function () {

                	var property = {};
                    if ( arguments.length != 0 ) var property = arguments[0];

                    /* init properties */
                    if ( property.width )   this.width(property.width);
                    if ( property.height )  this.width(property.height);

                    /* open popup */

            		if ( this.content() ) {
            			this._content_target.innerHTML = '';
            			this._content_target.innerHTML = this.content();
                        url = '#TB_inline?&inlineId='+this._content_targetId;
            		} else if ( this.target() ) {
                        url = '#TB_inline?&inlineId='+this._targetId;
                        /* try to find title */
                        if ($('#' + this._targetId).attr('title')) {
                            this.title($('#' + this._targetId).attr('title'));
                            $('#' + this._targetId).removeAttr('title');
                        }
                    } else {
                        alert('Content or target must be defined');
                        return;
                    }

                    /* create url */
                    if ( this.width() )     url += '&width='+this.width();
                    if ( this.height() )    url += '&height='+this.height();
                    if ( this.modal() !== null )     url += '&modal='+((this.modal()) ? 'true' : 'false');
                    if ( this.isreload() )  url += '&reload=true';

                    tb_show(this.title(), url);

                    /* */
                    $("#TB_ajaxContent #closePopupWindow").bind("click", function(){ popup_window.close(); return false; });

                    if      ( this.closeByClickAround() === true )  $("#TB_overlay").bind('click', function(){popup_window.close()});
                    else if ( this.closeByClickAround() === false ) $("#TB_overlay").unbind('click', function(){popup_window.close()});
                    
                    if ( typeof(startTranslateMode) != "undefined" ) startTranslateMode();
                },
                reload : function() {
                	this.isreload(true);
                	this.open();
                },
                close : function () {
                	window.onresize = null;
                	tb_remove();
                }
            }
        }();
    };
