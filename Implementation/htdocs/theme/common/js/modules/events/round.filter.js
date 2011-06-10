var EventRoundFilterApp = null;
if ( !EventRoundFilterApp ) {
    EventRoundFilterApp = function () {
        return {
            dtstartCalendar : null,
            dtstartCalendarPagedate : CFGEventRoundFilterApp.dtstartCalendarPagedate,
            dtstartCalendarSelected : CFGEventRoundFilterApp.dtstartCalendarSelected,
            dtendCalendar : null,
            dtendCalendarPagedate : CFGEventRoundFilterApp.dtendCalendarPagedate,
            dtendCalendarSelected : CFGEventRoundFilterApp.dtendCalendarSelected,
            init : function () {
                if ( document.getElementById('dtstartCalendarContainerDIV') ) {
                    EventRoundFilterApp.dtstartCalendar = new YAHOO.widget.Calendar("dtstartCalendarContainer", 'dtstartCalendarContainerDIV',
                        {
                            iframe				:	true,
                            hide_blank_weeks	:	true,
                            start_weekday 		: 	1,
                            close				: 	true,
                            selected			: 	EventRoundFilterApp.dtstartCalendarSelected,
                            pagedate			:	EventRoundFilterApp.dtstartCalendarPagedate,
                            start_weekday		:   0
                        }
                    );
                    EventRoundFilterApp.dtstartCalendar.selectEvent.subscribe(EventRoundFilterApp.onDtstartSelected, EventRoundFilterApp.dtstartCalendar, true);
                    EventRoundFilterApp.dtstartCalendar.render();
                    YAHOO.util.Event.addListener("dtstartCalendarContainerLink", "click", EventRoundFilterApp.onDtstartClick, EventRoundFilterApp.dtstartCalendar, true);
                }

                if ( document.getElementById('dtendCalendarContainerDIV') ) {
                    EventRoundFilterApp.dtendCalendar = new YAHOO.widget.Calendar("dtendCalendarContainer", 'dtendCalendarContainerDIV',
                        {
                            iframe				:	true,
                            hide_blank_weeks	:	true,
                            start_weekday 		: 	1,
                            close				: 	true,
                            selected			: 	EventRoundFilterApp.dtendCalendarSelected,
                            pagedate			:	EventRoundFilterApp.dtendCalendarPagedate,
                            start_weekday		:   0
                        }
                    );
                    EventRoundFilterApp.dtendCalendar.selectEvent.subscribe(EventRoundFilterApp.onDtendSelected, EventRoundFilterApp.dtendCalendar, true);
                    EventRoundFilterApp.dtendCalendar.render();
                    YAHOO.util.Event.addListener("dtendCalendarContainerLink", "click", EventRoundFilterApp.onDtendClick, EventRoundFilterApp.dtendCalendar, true);
                }


                $('#round_filter_mode1').bind('click', function(){
                    $('#__formRoundEventFilterMode').val(1);
                    $('#formRoundEventFilter').submit();
                });
                $('#round_filter_mode2').bind('click', function(){
                    $('#__formRoundEventFilterMode').val(2);
                    $('#formRoundEventFilter').submit();
                });
                $('#round_filter_mode5').bind('click', function(){
                    $('#__formRoundEventFilterMode').val(5);
                    if ($(this).hasClass('bold')) $("#__formRoundEventFilterModeType").val(0);
                    else $("#__formRoundEventFilterModeType").val(1);
                    $(this).toggleClass('bold');
                    $('#formRoundEventFilter').submit();
                });
                $('#round_filter_mode6').bind('click', function(){
                    $('#__formRoundEventFilterMode').val(6);
                    if ($(this).hasClass('bold')) $("#__formRoundEventFilterModeType").val(0);
                    else $("#__formRoundEventFilterModeType").val(1);
                    $(this).toggleClass('bold');
                    $('#formRoundEventFilter').submit();
                });
                $('#round_filter_mode7').bind('click', function(){
                    $('#__formRoundEventFilterMode').val(7);
                    if ($(this).hasClass('bold')) $("#__formRoundEventFilterModeType").val(0);
                    else $("#__formRoundEventFilterModeType").val(1);
                    $(this).toggleClass('bold');
                    $('#formRoundEventFilter').submit();
                });
                $('#round_filter_mode8').bind('click', function(){
                    $('#__formRoundEventFilterMode').val(8);
                    if ($(this).hasClass('bold')) $("#__formRoundEventFilterModeType").val(0);
                    else $("#__formRoundEventFilterModeType").val(1);
                    $(this).toggleClass('bold');
                    $('#formRoundEventFilter').submit();
                });

            },
            onDtstartClick : function () {
                EventRoundFilterApp.hideAllcalendarDialogs();
                EventRoundFilterApp.dtstartCalendar.show();
                $('#dtstartCalendarContainerDIV').css('margin-left', '0px');
            },
            onDtstartSelected : function (type, args, obj) {
                var selected = args[0];
                var oDate = this._toDate(selected[0]);
                var strDate = 
                    ((oDate.getMonth() + 1) < 10 ? '0'+ (oDate.getMonth() + 1) : (oDate.getMonth() + 1)) + '/' +
                    (oDate.getDate() < 10 ? '0' + oDate.getDate() : oDate.getDate() ) + '/' +
                    oDate.getFullYear();
                $('#round_filter_start').val(strDate);
                obj.hide();
                $('#__formRoundEventFilterMode').val(3);
                $('#formRoundEventFilter').submit();
            },
            onDtendClick : function () {
                EventRoundFilterApp.hideAllcalendarDialogs();
                EventRoundFilterApp.dtendCalendar.show();
                $('#dtendCalendarContainerDIV').css('margin-left', '0px');
            },
            onDtendSelected : function (type, args, obj) {
                var selected = args[0];
                var oDate = this._toDate(selected[0]);
                var strDate =
                    ((oDate.getMonth() + 1) < 10 ? '0'+ (oDate.getMonth() + 1) : (oDate.getMonth() + 1)) + '/' +
                    (oDate.getDate() < 10 ? '0' + oDate.getDate() : oDate.getDate() ) + '/' +
                    oDate.getFullYear();
                $('#round_filter_end').val(strDate);
                obj.hide();
                $('#__formRoundEventFilterMode').val(4);
                $('#formRoundEventFilter').submit();
            },
            hideAllcalendarDialogs : function() {
                EventRoundFilterApp.dtstartCalendar.hide();
                EventRoundFilterApp.dtendCalendar.hide();
            }
        }
    }();
};
$(function(){ EventRoundFilterApp.init(); })