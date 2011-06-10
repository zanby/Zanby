{assign var='rrule_freq' value=$formParams.rrule_freq}

<link rel="stylesheet" type="text/css" href="{$AppTheme->css}/event_calendar.css" media="screen" />
<script type="text/javascript" src="/js/yui/calendar/calendar-min.js" ></script> 
<link rel="stylesheet" type="text/css" href="{$AppTheme->common->css}/tree.css" media="screen" />

{literal}
<script type="text/javascript">
	var CreateEventApp = null;
	if ( !CreateEventApp ) {
		CreateEventApp = function () {
			return {
				dtstartCalendar : null,
				dtstartCalendarPagedate : '{/literal}{$formParams.event_dtstart_calPagedate}{literal}',
				dtstartCalendarSelected : '{/literal}{$formParams.event_dtstart_calSelected}{literal}',
				rruleUntilCalendar : null,
				rruleUntilCalendarPagedate : '{/literal}{$formParams.rrule_until_date_calPagedate}{literal}',
				rruleUntilCalendarSelected : '{/literal}{$formParams.rrule_until_date_calSelected}{literal}',
				arrWeekdays : ["SU", "MO", "TU", "WE", "TH", "FR", "SA"],
				init : function () {
					/**
					*	Dtstart
					*/
        			CreateEventApp.dtstartCalendar = new YAHOO.widget.Calendar("dtstartCalendarContainer", 'dtstartCalendarContainerDIV',
						{
							iframe				:	true,
							hide_blank_weeks	:	true,
							start_weekday 		: 	1,
							close				: 	true,
							selected			: 	CreateEventApp.dtstartCalendarSelected,
							pagedate			:	CreateEventApp.dtstartCalendarPagedate,
							start_weekday		:   0
						}
					);
					CreateEventApp.dtstartCalendar.selectEvent.subscribe(CreateEventApp.onDtstartSelected, CreateEventApp.dtstartCalendar, true);
					CreateEventApp.dtstartCalendar.render();
					YAHOO.util.Event.addListener("dtstartCalendarContainerLink", "click", CreateEventApp.dtstartCalendar.show, CreateEventApp.dtstartCalendar, true);

					/**
					*	Until
					*/
        			CreateEventApp.rruleUntilCalendar = new YAHOO.widget.Calendar("rruleUntilCalendarDialogContainer", 'rruleUntilCalendarDialogContainerDIV',
						{
							iframe				:	true,
							hide_blank_weeks	:	true,
							start_weekday 		: 	1,
							close				: 	true,
							selected			: 	CreateEventApp.rruleUntilCalendarSelected,
							pagedate			:	CreateEventApp.rruleUntilCalendarPagedate,
							start_weekday		:   0
						}
					);
					CreateEventApp.rruleUntilCalendar.selectEvent.subscribe(CreateEventApp.onRruleUntilSelected, CreateEventApp.rruleUntilCalendar, true);
					CreateEventApp.rruleUntilCalendar.render();
					YAHOO.util.Event.addListener("rruleUntilCalendarDialogContainerLink", "click", CreateEventApp.rruleUntilCalendar.show, CreateEventApp.rruleUntilCalendar, true);
					YAHOO.util.Event.addListener("rruleUntilCalendarDialogContainerLink", "click", CreateEventApp.onRruleUntilClick);
					/**
					*
					*/
					YAHOO.util.Event.addListener("event_dtstart_date_Year", "change", CreateEventApp.onDtstartDateChanged);
					YAHOO.util.Event.addListener("event_dtstart_date_Month", "change", CreateEventApp.onDtstartDateChanged);
					YAHOO.util.Event.addListener("event_dtstart_date_Day", "change", CreateEventApp.onDtstartDateChanged);

					YAHOO.util.Event.addListener("rrule_until_date_date_Year", "change", CreateEventApp.onRruleUntilChanged);
					YAHOO.util.Event.addListener("rrule_until_date_date_Month", "change", CreateEventApp.onRruleUntilChanged);
					YAHOO.util.Event.addListener("rrule_until_date_date_Day", "change", CreateEventApp.onRruleUntilChanged);

					YAHOO.util.Event.addListener("rrule_until_date_date_Year", "click", CreateEventApp.onRruleUntilClick);
					YAHOO.util.Event.addListener("rrule_until_date_date_Month", "click", CreateEventApp.onRruleUntilClick);
					YAHOO.util.Event.addListener("rrule_until_date_date_Day", "click", CreateEventApp.onRruleUntilClick);

					YAHOO.util.Event.addListener("rrule_until_count", "click", CreateEventApp.onRruleUntilCountClick);

					YAHOO.util.Event.addListener("event_reminder_1", "click", CreateEventApp.onReminderOptionsClick);
					YAHOO.util.Event.addListener("event_reminder_2", "click", CreateEventApp.onReminderOptionsClick);

				},
				onDtstartSelected : function (type, args, obj) {
					var event_dtstart_date_Year = YAHOO.util.Dom.get('event_dtstart_date_Year');
					var event_dtstart_date_Month = YAHOO.util.Dom.get('event_dtstart_date_Month');
					var event_dtstart_date_Day = YAHOO.util.Dom.get('event_dtstart_date_Day');

					var selected = args[0];
					var oDate = this._toDate(selected[0]);

					for ( var i = 0; i < event_dtstart_date_Year.options.length; i++ ) {
						if ( event_dtstart_date_Year.options[i].value == oDate.getFullYear()) event_dtstart_date_Year.options[i].selected = true;
					}
					event_dtstart_date_Day.options.selectedIndex = parseInt(oDate.getDate(), 10) - 1;
					event_dtstart_date_Month.options.selectedIndex = parseInt(oDate.getMonth(), 10);

					var rrule_yearly_bymonth1 = YAHOO.util.Dom.get('rrule_yearly_bymonth1');
					var rrule_yearly_bymonth2 = YAHOO.util.Dom.get('rrule_yearly_bymonth2');
					rrule_yearly_bymonth1.options.selectedIndex = parseInt(oDate.getMonth(), 10);
					rrule_yearly_bymonth2.options.selectedIndex = parseInt(oDate.getMonth(), 10);

					var rrule_monthly_bymonthday1 = YAHOO.util.Dom.get('rrule_monthly_bymonthday1');
					var rrule_yearly_bymonthday1 = YAHOO.util.Dom.get('rrule_yearly_bymonthday1');
					rrule_monthly_bymonthday1.value = parseInt(oDate.getDate(), 10);
					rrule_yearly_bymonthday1.value = parseInt(oDate.getDate(), 10);


					var rrule_monthly_byday2 = YAHOO.util.Dom.get('rrule_monthly_byday2');
					var rrule_yearly_byday2 = YAHOO.util.Dom.get('rrule_yearly_byday2');
					for ( var i = 0; i < rrule_monthly_byday2.options.length; i++ ) {
						if ( rrule_monthly_byday2.options[i].value == CreateEventApp.arrWeekdays[oDate.getDay()]) rrule_monthly_byday2.options[i].selected = true;
					}
					for ( var i = 0; i < rrule_yearly_byday2.options.length; i++ ) {
						if ( rrule_yearly_byday2.options[i].value == CreateEventApp.arrWeekdays[oDate.getDay()]) rrule_yearly_byday2.options[i].selected = true;
					}
					var WEEKLY_BY_WEEKDAY = YAHOO.util.Dom.get('WEEKLY_BY_WEEKDAY');
					var inputs = WEEKLY_BY_WEEKDAY.getElementsByTagName('input');
					for ( var i = 0; i < inputs.length; i++ ) {
						if ( inputs[i].value == CreateEventApp.arrWeekdays[oDate.getDay()]) inputs[i].checked = true;
						else inputs[i].checked = false;
					}

					obj.hide();
				},
				onDtstartDateChanged : function () {
					var event_dtstart_date_Year = YAHOO.util.Dom.get('event_dtstart_date_Year');
					var event_dtstart_date_Month = YAHOO.util.Dom.get('event_dtstart_date_Month');
					var event_dtstart_date_Day = YAHOO.util.Dom.get('event_dtstart_date_Day');
					event_dtstart_date_Year = event_dtstart_date_Year.options[event_dtstart_date_Year.selectedIndex].value;
					event_dtstart_date_Month = event_dtstart_date_Month.options[event_dtstart_date_Month.selectedIndex].value;
					event_dtstart_date_Day = event_dtstart_date_Day.options[event_dtstart_date_Day.selectedIndex].value;

					var daysInMonth = CreateEventApp.daysInMonth(event_dtstart_date_Year, event_dtstart_date_Month);
					if ( event_dtstart_date_Day > daysInMonth ) {
						YAHOO.util.Dom.get('event_dtstart_date_Day').options.selectedIndex = daysInMonth - 1;
						event_dtstart_date_Day = daysInMonth;
					}

					CreateEventApp.dtstartCalendar.setYear(event_dtstart_date_Year);
					CreateEventApp.dtstartCalendar.setMonth(parseInt(event_dtstart_date_Month, 10)-1);
					CreateEventApp.dtstartCalendar.select(event_dtstart_date_Month+'/'+event_dtstart_date_Day+'/'+event_dtstart_date_Year);
					CreateEventApp.dtstartCalendar.render();

					var rrule_yearly_bymonth1 = YAHOO.util.Dom.get('rrule_yearly_bymonth1');
					var rrule_yearly_bymonth2 = YAHOO.util.Dom.get('rrule_yearly_bymonth2');
					rrule_yearly_bymonth1.options.selectedIndex = parseInt(event_dtstart_date_Month, 10)-1;
					rrule_yearly_bymonth2.options.selectedIndex = parseInt(event_dtstart_date_Month, 10)-1;

					var rrule_monthly_bymonthday1 = YAHOO.util.Dom.get('rrule_monthly_bymonthday1');
					var rrule_yearly_bymonthday1 = YAHOO.util.Dom.get('rrule_yearly_bymonthday1');
					rrule_monthly_bymonthday1.value = parseInt(event_dtstart_date_Day, 10);
					rrule_yearly_bymonthday1.value = parseInt(event_dtstart_date_Day, 10);


					var oDate = new Date(event_dtstart_date_Year, parseInt(event_dtstart_date_Month, 10)-1, event_dtstart_date_Day);
					var rrule_monthly_byday2 = YAHOO.util.Dom.get('rrule_monthly_byday2');
					var rrule_yearly_byday2 = YAHOO.util.Dom.get('rrule_yearly_byday2');
					for ( var i = 0; i < rrule_monthly_byday2.options.length; i++ ) {
						if ( rrule_monthly_byday2.options[i].value == CreateEventApp.arrWeekdays[oDate.getDay()]) rrule_monthly_byday2.options[i].selected = true;
					}
					for ( var i = 0; i < rrule_yearly_byday2.options.length; i++ ) {
						if ( rrule_yearly_byday2.options[i].value == CreateEventApp.arrWeekdays[oDate.getDay()]) rrule_yearly_byday2.options[i].selected = true;
					}
					var WEEKLY_BY_WEEKDAY = YAHOO.util.Dom.get('WEEKLY_BY_WEEKDAY');
					var inputs = WEEKLY_BY_WEEKDAY.getElementsByTagName('input');
					for ( var i = 0; i < inputs.length; i++ ) {
						if ( inputs[i].value == CreateEventApp.arrWeekdays[oDate.getDay()]) inputs[i].checked = true;
						else inputs[i].checked = false;
					}

				},
				onRruleUntilSelected : function (type, args, obj) {
					var rrule_until_date_date_Year = YAHOO.util.Dom.get('rrule_until_date_date_Year');
					var rrule_until_date_date_Month = YAHOO.util.Dom.get('rrule_until_date_date_Month');
					var rrule_until_date_date_Day = YAHOO.util.Dom.get('rrule_until_date_date_Day');

					var selected = args[0];
					var oDate = this._toDate(selected[0]);

					for ( var i = 0; i < rrule_until_date_date_Year.options.length; i++ ) {
						if ( rrule_until_date_date_Year.options[i].value == oDate.getFullYear()) rrule_until_date_date_Year.options[i].selected = true;
					}
					rrule_until_date_date_Day.options.selectedIndex = parseInt(parseInt(oDate.getDate()) - 1);
					rrule_until_date_date_Month.options.selectedIndex = parseInt(oDate.getMonth());

					YAHOO.util.Dom.get('rrule_until_option_3').checked = true;

					obj.hide();
				},
				onRruleUntilChanged : function () {
					var rrule_until_date_date_Year = YAHOO.util.Dom.get('rrule_until_date_date_Year');
					var rrule_until_date_date_Month = YAHOO.util.Dom.get('rrule_until_date_date_Month');
					var rrule_until_date_date_Day = YAHOO.util.Dom.get('rrule_until_date_date_Day');
					rrule_until_date_date_Year = rrule_until_date_date_Year.options[rrule_until_date_date_Year.selectedIndex].value;
					rrule_until_date_date_Month = rrule_until_date_date_Month.options[rrule_until_date_date_Month.selectedIndex].value;
					rrule_until_date_date_Day = rrule_until_date_date_Day.options[rrule_until_date_date_Day.selectedIndex].value;

					var daysInMonth = CreateEventApp.daysInMonth(rrule_until_date_date_Year, rrule_until_date_date_Month);
					if ( rrule_until_date_date_Day > daysInMonth ) {
						YAHOO.util.Dom.get('rrule_until_date_date_Day').options.selectedIndex = daysInMonth - 1;
						rrule_until_date_date_Day = daysInMonth;
					}
					CreateEventApp.rruleUntilCalendar.setYear(rrule_until_date_date_Year);
					CreateEventApp.rruleUntilCalendar.setMonth(parseInt(rrule_until_date_date_Month, 10)-1);
					CreateEventApp.rruleUntilCalendar.select(rrule_until_date_date_Month+'/'+rrule_until_date_date_Day+'/'+rrule_until_date_date_Year);
					CreateEventApp.rruleUntilCalendar.render();

					YAHOO.util.Dom.get('rrule_until_option_3').checked = true;
				},
				onRruleUntilClick : function () {
					YAHOO.util.Dom.get('rrule_until_option_3').checked = true;
				},
				onRruleUntilCountClick : function () {
					YAHOO.util.Dom.get('rrule_until_option_2').checked = true;
				},
				onReminderOptionsClick : function () {
					YAHOO.util.Dom.get('event_reminder_mode_2').checked = true;
				},
				daysInMonth : function(year,month){
    				var D=new Date(year, month-1, 1, 12);
					return parseInt((-Date.parse(D)+D.setMonth(D.getMonth()+1)+36e5)/864e5);
				},
				/**
				*
				*/
				changeRruleType : function (obj) {
					CreateEventApp.hideAllcalendarDialogs();
					CreateEventApp.hideAllRrule();					
					if ( obj.value ) {
						var area = YAHOO.util.Dom.get('RRULE_' + obj.value + '_OPTIONS');
						if ( !area ) return;
						area.style.display = '';
						if ( obj.value != 'NONE' ) {
							YAHOO.util.Dom.get('RRULE_UNTIL_OPTIONS').style.display 	= '';
						}
					}
				},
				hideAllcalendarDialogs : function() {
					CreateEventApp.dtstartCalendar.hide();
					CreateEventApp.rruleUntilCalendar.hide();
				},
				hideAllRrule : function () {
					YAHOO.util.Dom.get('RRULE_DAILY_OPTIONS').style.display 	= 'none';
					YAHOO.util.Dom.get('RRULE_WEEKLY_OPTIONS').style.display 	= 'none';
					YAHOO.util.Dom.get('RRULE_MONTHLY_OPTIONS').style.display 	= 'none';
					YAHOO.util.Dom.get('RRULE_YEARLY_OPTIONS').style.display 	= 'none';
					YAHOO.util.Dom.get('RRULE_UNTIL_OPTIONS').style.display 	= 'none';
				},
				onAllDayClick : function () {
					var obj = YAHOO.util.Dom.get("event_is_allday");
					if ( true == obj.checked ) {
						YAHOO.util.Dom.get("event_time_hour").disabled 			= true;
						YAHOO.util.Dom.get("event_time_minute").disabled 		= true;
						YAHOO.util.Dom.get("event_duration_minute").disabled 	= true;
						YAHOO.util.Dom.get("event_duration_hour").disabled 		= true;
						YAHOO.util.Dom.get("event_timezone_mode").disabled 		= true;
						YAHOO.util.Dom.get("event_timezone").disabled 			= true;
					} else {
						YAHOO.util.Dom.get("event_time_hour").disabled 			= false;
						YAHOO.util.Dom.get("event_time_minute").disabled 		= false;
						YAHOO.util.Dom.get("event_duration_minute").disabled 	= false;
						YAHOO.util.Dom.get("event_duration_hour").disabled 		= false;
						YAHOO.util.Dom.get("event_timezone_mode").disabled 		= false;
						YAHOO.util.Dom.get("event_timezone").disabled 			= false;
					}
				},
				checkFreq : function () {
					YAHOO.util.Dom.get('rrule_freq_' + '{/literal}{$rrule_freq}{literal}').checked = true;
				},
				onTimezoneCheck : function () {
					var obj = YAHOO.util.Dom.get("event_timezone_mode");
					if ( obj.checked == true ) {
						YAHOO.util.Dom.get("event_timezone_DIV").style.display = '';
					} else {
						YAHOO.util.Dom.get("event_timezone_DIV").style.display = 'none';
					}
				},
				onTabClick : function(name) {
					if ( YAHOO.util.Dom.get(name).value == 0 ) YAHOO.util.Dom.get(name).value = 1;
					else YAHOO.util.Dom.get(name).value = 0;
				},
				getInviteListObjects : function () {
					var eventObjectsColl = YAHOO.util.Dom.getElementsByClassName('events-object-list-hidden', 'input');
					var eventObjectsValues = new Array();
					if ( eventObjectsColl && eventObjectsColl.length ) {
						for (var i = 0; i < eventObjectsColl.length; i++ ) {
							eventObjectsValues[eventObjectsValues.length] = eventObjectsColl[i].value;
						}
					}
					return eventObjectsValues;
				},
				getInviteGroupObjects : function () {
					var eventObjectsColl = YAHOO.util.Dom.getElementsByClassName('events-object-group-hidden', 'input');
					var eventObjectsValues = new Array();
					if ( eventObjectsColl && eventObjectsColl.length ) {
						for (var i = 0; i < eventObjectsColl.length; i++ ) {
							eventObjectsValues[eventObjectsValues.length] = eventObjectsColl[i].value;
						}
					}
					return eventObjectsValues;
				}
			}
		}();
	};
	YAHOO.util.Event.onDOMReady(CreateEventApp.init);
	YAHOO.util.Event.onDOMReady(CreateEventApp.onAllDayClick);
	YAHOO.util.Event.onDOMReady(CreateEventApp.onTimezoneCheck);
	YAHOO.util.Event.onDOMReady(CreateEventApp.checkFreq);

	function view_option(name)
	{
	    var current_select = document.getElementById(name + '_block').value;
	    if (current_select == 1) {
            document.getElementById(name + '_show').style.display = '';
            document.getElementById(name + '_hide').style.display = 'none';
            document.getElementById(name + '_tab').style.display = 'none';
            document.getElementById(name + '_block').value = 0;
	    } else {
            document.getElementById(name + '_show').style.display = 'none';
            document.getElementById(name + '_hide').style.display = '';
            document.getElementById(name + '_tab').style.display = '';
            document.getElementById(name + '_block').value = 1;
	    }
	    return false;
	}
	function changeto(part){
		var active = document.getElementById("venue_active").value;
		if (active != part){
			document.getElementById(part + '_show').style.display = '';
			document.getElementById(active + '_show').style.display = 'none';
			document.getElementById("venue_active").value = part;
		}
	}
	function changevenueto(block){
		changeto('add');
		var view_block = document.getElementById('venue_type').value;
		document.getElementById(view_block + '_venue_block').style.display = 'none';
		document.getElementById('venue_type').value = block;
		document.getElementById(block + '_venue_block').style.display = 'block';
		if ( block == 'no' ) {
		    document.getElementById('venueId').value = '';
		} else {
            document.getElementById('no_venue_block').style.display = 'none';
        }
	}

	function getFindSearches()
    {
        var search = new Array();
        search['find_keywords']   = document.getElementById('find_keywords').value;
        search['find_category']   = document.getElementById('find_category').value;
        search['find_createdBy']  = document.getElementById('find_createdBy').value;
        search['find_where']      = document.getElementById('find_where').value;
        search['find_page']       = document.getElementById('find_page').value;
        return search;
    }

    function setFindSearches(a,v)
    {
        document.getElementById('p').value = 1;
        if (a == 'c') {
            document.getElementById('l').value = 'all';
        }
        document.getElementById(a).value = v;
    }

    function getKeyCode(e)
    {
        if (window.event) return e.keyCode;
        if (e.which) return e.which;
    }

    function searchByEnter(event)
    {
        if (getKeyCode(event) == 13) xajax_findaVenue(getFindSearches());
    }

    function addInspectButtons()
    {
        YAHOO.util.Event.addListener("find_keywords", "keypress", searchByEnter);
        YAHOO.util.Event.addListener("find_where",    "keypress", searchByEnter);
    }

</script>
{/literal}
{if $FACEBOOK_USED}
    {literal}
        <script type="text/javascript">//<![CDATA[ 
            {/literal}{assign_adv var="url_oninvite_friends_toevent" value="array('controller' => 'facebook', 'action' => 'invitefriendstoevent')"}{literal}
            FBCfg.url_oninvite_friends_toevent = '{/literal}{$Warecorp->getCrossDomainUrl($url_oninvite_friends_toevent)}{literal}';
            {/literal}{assign_adv var="url_onremove_from_eventinvite" value="array('controller' => 'facebook', 'action' => 'removefromeventinvite')"}{literal}
            FBCfg.url_onremove_from_eventinvite = '{/literal}{$Warecorp->getCrossDomainUrl($url_onremove_from_eventinvite)}{literal}';
        //]]></script>
    {/literal}
{/if}
<!-- -->
{* PAGE CONTENT START *}
{form from=$form enctype="multipart/form-data" id="createEventForm"}
{form_hidden name="form_submit" value="1"}
<div class="prInnerSmall prClr3">

	{form_errors_summary}
    <table class="prForm" id="calendar-add-event-block">
    <col width="25%" />
    <col width="65%" />
   	<col width="10%" />
    <thead>
        <tr><th colspan="3" class="prTLeft prText5">
			{t}Fields marked with asterisk <span class="prMarkRequired">*</span> are required{/t}
        </th></tr>
    </thead>
    <tbody>
        <tr>
            <td class="prTRight"><span class="prMarkRequired">*</span> <label for="event_title"> {t}Event Title:{/t}</label></td>
            <td>{form_text name="event_title" value=$objCopyEvent->getTitle()|escape:html}</td>
    		<td></td>
        </tr>
        <tr>
            <td class="prTRight"><label for="event_description">{t}Notes:{/t}</label></td>
            <td>{form_textarea name="event_description" id="eventDescription" value=$objCopyEvent->getDescription()|escape:html rows="5"}</td>
		<td></td>
        </tr>
		<tr{if $viewMode != 'ROW'} style="display:none;"{/if}>
            <td class="prTRight"><label for="event_tags">{t}Tags:{/t}</label></td>
            <td>{form_text name="event_tags" value=$formParams.event_tags|escape:html}</td>
    		<td></td>
        </tr>
        <tr>
            <td class="prTRight"><span class="prMarkRequired">*</span> <label for="event_dtstart_date_Month"> {t}Date:{/t}</label></td>
            <td>
            	<div id="startDateContainer" class="prFloatLeft prDateWidth">
    					{form_select_date start_year="-20" end_year="+20" prefix="date_" field_array="event_dtstart" time=$formParams.event_dtstart->toString('yyyy-MM-dd')"}
    			</div>
    			<div class="prFloatLeft"><a href="#null" id="dtstartCalendarContainerLink"><img src="{$AppTheme->images}/decorators/event/icon-calendar.gif" alt="" /></a></div>
				<div id="dtstartCalendarContainerDIV" class="prClr2"></div>
            </td>
    		<td></td>
        </tr>
        <tr>
            <td class="prTRight"><span class="prMarkRequired">*</span> <label for="event_time_hour"> {t}Event Time:{/t}</label></td>
            <td>
    			<label for="event_time_hour" class="prEventTimeLabel">{t}Starts at{/t} </label>
                {form_select name="event_time_hour" id="event_time_hour" selected=$formParams.event_dtstart->toString('H') options=$hours class="prEventTime"}
                {form_select name="event_time_minute" id="event_time_minute" selected=$formParams.event_dtstart->toString('mm') options=$minutes class="prEventTime"}
				<br clear="all"/>
    			<div class="prIndentTop prClr3">
    	            <label for="event_duration_hour" class="prEventTimeLabel">{t}Duration{/t}</label>
                    {form_select name="event_duration_hour" id="event_duration_hour" selected=$formParams.event_duration_hour options=$dur_hours class="prEventTime"}
                    <span>{form_select name="event_duration_minute" id="event_duration_minute" selected=$formParams.event_duration_minute options=$dur_minutes class="prEventTime"}</span>
    			</div>
                <div class="prIndentTop">
                    {form_checkbox name="event_is_allday" id="event_is_allday" checked=$formParams.event_is_allday value="1" onclick="CreateEventApp.onAllDayClick();"}
                    <label for="event_is_allday" class="">{t}This is an all day event{/t}</label>
                </div>
            </td>
    		<td></td>
        </tr>
        <tr>
            <td class="prTRight"><label for="event_timezone_mode">Event Time Zone:</label></td>
            <td>
    			<div class="prFloatLeft">{form_checkbox name='event_timezone_mode' id='event_timezone_mode' value='1' checked=$formParams.event_timezone_mode onclick="CreateEventApp.onTimezoneCheck();"}</div>
    			<div id="event_timezone_DIV"{if $formParams.event_timezone_mode != 1} style="display:none;"{/if} class="prIndentLeftSmall prFloatLeft">{form_select name="event_timezone" id="event_timezone" selected=$formParams.event_timezone options=$timezones}</div>
    		</td>
    		<td></td>
        </tr>
        <tr{if $viewMode != 'ROW'} style="display:none;"{/if}>
            <td class="prTRight"><span class="prMarkRequired">*</span> <label for="event_event_type_1"> Event Type:</label></td>
            <td>
            	{form_select name="event_event_type_1" id="event_event_type_1" selected=$formParams.event_event_type_1 options=$event_types class="prEventType"}
                {form_select name="event_event_type_2" id="event_event_type_2" selected=$formParams.event_event_type_2 options=$event_types class="prEventType"}
                {form_select name="event_event_type_3" id="event_event_type_3" selected=$formParams.event_event_type_3 options=$event_types class="prEventType"}
                <div class="prTip prClearer">{t}Select at least one{/t}</div>
             </td>
    		 <td></td>
        </tr>
		<tr>
            <td class="prTRight"><label>{t}Event Photo:{/t}</label></td>
            <td class="prClr2">
    			<div id="EventPictureBlock"{if !$objCopyEvent->getPictureId()} style="display:none"{/if}>
    				{if $objCopyEvent->getPictureId()}
    					<img src="{$objCopyEvent->getEventPicture()->setWidth(75)->setHeight(75)->getImage($user)}" class="prFloatLeft prIndentRightSmall" id="EventImageObj"/>
    				{else}
    					<img src="{$AppTheme->images}/decorators/event/no_event_image.jpg" class="prFloatLeft" id="EventImageObj" />
    				{/if}
    				<div class="prFloatLeft">
    					<a href="#" onclick="xajax_doAttachPhoto(YAHOO.util.Dom.get('event_picture_id').value);return false;">+  {t}Attach Picture{/t}</a>
    					<div class="prIndentTop"><a href="#" onclick="xajax_doAttachPhotoDelete();return false;">{t}Delete Picture{/t}</a></div>
    				</div>
    			</div>
    			<div id="EventPictureBlockNONE"{if $objCopyEvent->getPictureId()} style="display:none"{/if} class="prClr2">
    				<img src="{$AppTheme->images}/decorators/event/no_event_image.jpg" class="prFloatLeft prIndentRightSmall" />
    				<div class="prFloatLeft">
    					<a href="#null" onclick="xajax_doAttachPhoto(YAHOO.util.Dom.get('event_picture_id').value);return false;">+  {t}Attach Picture{/t}</a>
    				</div>
    			</div>
    			{form_hidden name="event_picture_id" id="event_picture_id" value=$objCopyEvent->getPictureId()|default:0}
           	</td>
    		<td></td>
        </tr>
    </tbody>
    </table>
</div>
<script type="text/javascript" src="/js/tinymceBlog/tiny_mce.js"></script>
<script type="text/javascript" src="/js/tinymceBlog/tiny_mce_frontevent_init.js"></script>
<script>
{literal}
  tinyMCE.execCommand('mceAddControl', true, 'eventDescription');
{/literal}
</script>
<div class="prInnerLeft prInnerRight">
    <h3 class="prInnerSmallTop">{t}Event Tools{/t}</h3>
</div>

<!-- invitations -->

{TitlePane id='CreateEventInvitations' showContent=1}
		{TitlePane_Title}{t}Invitations{/t}{/TitlePane_Title}
		{TitlePane_Note}<div class="prHeaderHelper">{t}{tparam value=$SITE_NAME_AS_STRING}Invite %s friends and outside contacts to your event{/t}</div>{/TitlePane_Note}
		{TitlePane_Toggle type='show'}{t}Show{/t}{/TitlePane_Toggle}
		{TitlePane_Toggle type='hide'}{t}Hide{/t}{/TitlePane_Toggle}
		{TitlePane_Content}
                    <table class="prForm" id="invite-guests-options-full">
                        <col width="25%" />
						<col width="65%" />
						<col width="10%" />
                        <tbody>
                            <tr>
                               <td class="prTRight">
                                   {t}From:{/t}
                               </td>
                               <td>
                                    <span>{$currentUser->getEmail()|strip_script}</span>
                                    {form_hidden name="event_invitations_from" value=$currentUser->getId()}
                               </td>
							   <td></td>
                            </tr>
                            <tr>
                               <td class="prTRight">
                                   <label for="inv_emails">To:</label>
                               </td>
                               <td>
                                    <p>{t}{tparam value=$SITE_NAME_AS_STRING}Enter an email address or a %s username, separated by a comma.{/t}</p>
                                    {if FACEBOOK_USED}
                                        <div class="prInnerSmallTop">
                                            <a href="javascript:void(0)" onclick="FBApplication.oninvite_friends_toevent(); return false;">
                                                {t}Invite Friend from Facebook{/t}
                                            </a>
                                            <img alt="" src="{$AppTheme->images}/decorators/icons/icoFB_small.gif" class="prIndentTop" />
                                        </div>
                                    {/if}

                                    <a href="javascript:void(0)" onclick="xajax_addFromAddressbook(); return false;">{t}Insert addresses from Address Book{/t}</a>
                                    <div style="{if !$formParams.event_invitations_lists}display:none{/if}" id="EventInviteListsObjects">
                                        {include file="users/calendar/action.event.template.contact.list.tpl"}
                                    </div>
                                    <div style="{if !$formParams.event_invitations_groups}display:none{/if}" id="EventInviteGroupsObjects">
                                        {include file="users/calendar/action.event.template.contact.group.tpl"}
                                    </div>
                                    <div class="prIndentTopSmall">{form_textarea id="inv_emails" name="event_invitations_emails" value=$formParams.event_invitations_emails|escape:html}</div>
                                    <div class="prTip">{t}{tparam value=$SITE_NAME_AS_STRING}{tparam value=$SITE_NAME_AS_STRING}These email addresses will be stored in your %s address book<br />for future use. %s will not use them for marketing purposes.{/t}</div>
                               </td>
							   <td></td>
                            </tr>
                            {if FACEBOOK_USED}
                            <tr class="prInnerTop" style="{if !$formParams.event_invitations_fbfriends}display:none{/if}" id="EventInviteFBFriendsObjects">
                                {include file="facebook/invitefriends.template.invited.tpl"}
                            </tr>
                            {/if}
                            <tr>
                               <td class="prTRight">
                                   <label for="event_invitations_subject">{t}Subject:{/t}</label>
                               </td>
                               <td>
                                   {form_text name="event_invitations_subject" value=$formParams.event_invitations_subject|escape:html}
                               </td>
							   <td></td>
                            </tr>
                            <tr>
                               <td class="prTRight">
                                   <label for="event_invitations_message">{t}Message:{/t}</label>
                               </td>
                               <td>
                                    {form_textarea name="event_invitations_message" value=$formParams.event_invitations_message|escape:html rows="9"}
                                    <div class="prIndentTopSmall">
									{form_checkbox name="event_allow_guests_invitation" id="event_allow_guests_invitation" value="1" checked=$formParams.event_allow_guests_invitation}<label for="event_allow_guests_invitation" class="">{t}Allow guests to invite other people{/t}</label>
									</div>
									<div class="prIndentTopSmall">
                                    {form_checkbox name="event_display_guests" id="event_display_guests" value="1" checked=$formParams.event_display_guests}<label for="event_display_guests">{t}Display Guest List to Invited Guests{/t}</label>
									</div>
                                    <div class="prTip">{t}If unchecked, only you will be able to view the guest list{/t}</div>
                                    {form_checkbox name="is_anybody_join" id="is_anybody_join" value="1" checked=$formParams.is_anybody_join}
                                    <span><label for="is_anybody_join">{t}Allow anyone to attend this event{/t}</label></span><br />    
                                    <div class="prTip">{t}If checked, anonymous users will be able to RSVP to this event{/t}</div>
                                    
                                    {form_checkbox name="receive_no_rsvp_email" id="receive_no_rsvp_email" value="1" checked=$formParams.receive_no_rsvp_email}
                                    <span><label for="receive_no_rsvp_email">{t}Receive no email when users RSVP to event{/t}</label></span><br />    
                               </td>
							   <td></td>
                            </tr>
                        </tbody>
                    </table>
                    {form_hidden name="show_invitation_block" id="show_invitation_block" value=$formParams.show_invitation_block}
                    <!-- /form container -->
                 {/TitlePane_Content}
		<!-- /invitations -->
	{/TitlePane}
<!-- /invitations -->

<!-- venue -->
{TitlePane id='CreateEventVenue'}
		{TitlePane_Title}{t}Venue Details{/t}{/TitlePane_Title}
		{TitlePane_Note}{t}<div class="prHeaderHelper">Add, find and save venues</div>{/t}{/TitlePane_Note}
		{TitlePane_Toggle type='show'}{t}Show{/t}{/TitlePane_Toggle}
		{TitlePane_Toggle type='hide'}{t}Hide{/t}{/TitlePane_Toggle}
		{TitlePane_Content}
			<div class="prInnerSmall">
                <div class="prToggleArea">
                    {include file="users/calendar/venues.tpl"}
                    {form_hidden name="show_venues_block" id="show_venues_block" value=$formParams.show_venues_block}
                    <input type="hidden" id="venue_active" name="venue_active" value="add" />
                </div>
			</div>
		{/TitlePane_Content}
	{/TitlePane}
<!-- /venue -->

<!-- repeating -->	
	<div {if $viewMode != 'ROW' && !$editFutureDates} style="display:none;"{/if}>
	{TitlePane id='CreateEventRepeating'}
		{TitlePane_Title}{t}Repeating{/t}{/TitlePane_Title}
		{TitlePane_Note}<div class="prHeaderHelper">{t}Configure a series of event{/t}</div>{/TitlePane_Note}
		{TitlePane_Toggle type='show'}{t}Show{/t}{/TitlePane_Toggle}
		{TitlePane_Toggle type='hide'}{t}Hide{/t}{/TitlePane_Toggle}
		{TitlePane_Content}
			{include file="users/calendar/event.form.repeating.tpl"}
		{/TitlePane_Content}
	{/TitlePane}
	</div>
 <!-- repeating -->

<!-- reminder -->
{TitlePane id='CreateEventReminders'}
	{TitlePane_Title}{t}Reminders{/t}{/TitlePane_Title}
	{TitlePane_Note}<div class="prHeaderHelper">{t}Schedule event reminders for your guests{/t}</div>{/TitlePane_Note}
	{TitlePane_Toggle type='show'}{t}Show{/t}{/TitlePane_Toggle}
	{TitlePane_Toggle type='hide'}{t}Hide{/t}{/TitlePane_Toggle}
	{TitlePane_Content}
		{include file="users/calendar/event.form.reminder.tpl"}
	{/TitlePane_Content}
{/TitlePane}
<!-- reminder -->

<!-- privacy -->
<div{if $viewMode != 'ROW'} style="display:none;"{/if}>
 {TitlePane id='CreateEventPrivacy'}
	{TitlePane_Title}{t}Privacy{/t}{/TitlePane_Title}
	{TitlePane_Note}<div class="prHeaderHelper">{t}Determine now your event displays on your calendar{/t}</div>{/TitlePane_Note}
	{TitlePane_Toggle type='show'}{t}Show{/t}{/TitlePane_Toggle}
	{TitlePane_Toggle type='hide'}{t}Hide{/t}{/TitlePane_Toggle}
	{TitlePane_Content}
		{include file="users/calendar/event.form.privacy.tpl"}
	{/TitlePane_Content}
{/TitlePane}
</div>
<!-- privacy -->

<!-- documents -->
 {TitlePane id='CreateEventDocuments'}
	{TitlePane_Title}{t}Event Documents{/t}{/TitlePane_Title}
	{TitlePane_Note}<div class="prHeaderHelper">{t}Associate Documents or Document Archives (.zip) with your event{/t}</div>{/TitlePane_Note}
	{TitlePane_Toggle type='show'}{t}Show{/t}{/TitlePane_Toggle}
	{TitlePane_Toggle type='hide'}{t}Hide{/t}{/TitlePane_Toggle}
	{TitlePane_Content}
		{include file="users/calendar/event.form.document.tpl"}
	{/TitlePane_Content}
{/TitlePane}
<!-- documents -->

<!-- list -->
 {TitlePane id='CreateEventLists'}
	{TitlePane_Title}{t}Lists{/t}{/TitlePane_Title}
	{TitlePane_Note}<div class="prHeaderHelper">{t}Attach a list to your event{/t}</div>{/TitlePane_Note}
	{TitlePane_Toggle type='show'}{t}Show{/t}{/TitlePane_Toggle}
	{TitlePane_Toggle type='hide'}{t}Hide{/t}{/TitlePane_Toggle}
	{TitlePane_Content}
		{include file="users/calendar/event.form.list.tpl"}
	{/TitlePane_Content}
{/TitlePane}
<!-- list -->

<div class="prInnerSmallTop prTRight">
	{t var='button'}Save Event{/t}
    {linkbutton name=$button onclick="document.forms['form_add_event'].submit(); return false;" html=$html htmlPosition="right"}

    <!-- Checkbox was plase to hidden DIV -->
    <div style="display:none;visible:hidden;">
        {form_checkbox name="notify_to_guest" id="notify_to_guest" value="true" checked="checked"}
        <label for="notify_to_guest" class=""> {t}Notify Guests of Changes{/t}</label>
    </div>
</div>

{/form}

{* PAGE CONTENT END *}

