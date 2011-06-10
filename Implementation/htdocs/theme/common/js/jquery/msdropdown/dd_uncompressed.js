/**
 * @name: dd.js
 * @description: customize dropdown
 * @author: Marghoob Suleman - http://www.marghoobsuleman.com/
 * @version: 1.5
 * @date: March 09, 2009
 * @released: March 31, 2009 {1.4}, March 30, 2009 {1.3}, March 22, 2009 {1.2}, March 20, 2009 {1.0}
 * @category: Javascript Custom Component
 * @copyright (c) 2009 Marghoob Suleman (powered by: http://www.giftlelo.com/)
 */
MSDropDown = {
	settings: {
				theme:'blue', //will use later
				autoHide:'off', //will use later
				offClass:'aOff',
				onClass:'aOn',
				maintainHeight:0,//will use later
				idpostfixmain:'_ms',
				idpostfixchild:'_child',
				idpostfixa:'_msa',
				idposttitlediv: '_divtitle',
				idposttitle: '_title',
				idhidden: '_input',
				showTitle:true,
				visibleRows:7, //will use later
				iconWithTitle:true
			  },
	styles: {
			ddclass:'msDropDown',
			childclass:'msDropDown_Child',
			arrow: '/theme/common/js/jquery/msdropdown/dd_arrow.gif',
			arrowclass: 'msArrow',
			titleclass: 'msDropdownTitle',
			disabled:'msDisabled',
			enabled:'msEnabled'
			},
	attributes: {action:"onfocus,onblur,onchange,onclick,ondblclick,onmousedown,onmouseup,onmouseover,onmousemove,onmouseout,onkeypress,onkeydown,onkeyup", prop:"size,multiple,disabled,tabindex"},
	selected: new Object(),
	zIndex:99,
	insideWindow:false,
	dp_array:new Object(),
	currentDiv:'',
	init: function(byID) {
		//this.selected = new Object();
		//this.dp_array = new Object();
		//make cutomdropdowns
		this.storeNmake(byID);
	},
	storeNmake: function(byID) {
			//storing datas
			var dps = this.getAllDropDown(byID);
			var total = dps.length;
			for(var iCount=0;iCount<total;iCount++) {
				var currentSelect = dps[iCount];
				if(currentSelect.id!=undefined && currentSelect.id.length>0) {
					//adding a custome event to refresh dropdown
					this.addNewEvents(currentSelect.id);
					var prop = new Array();
					var selectProp = this.getSelectProperties(currentSelect.id);
					prop["selectAttributes"] = selectProp.attributes;
					prop["selectAction"] = selectProp.action;
					//format dropdown
					var width = (this.dp_array[currentSelect.id]==undefined) ? $(currentSelect).width() : this.dp_array[currentSelect.id].width;
					var height = (this.dp_array[currentSelect.id]==undefined) ? $(currentSelect).height() : this.dp_array[currentSelect.id].height;
					$(currentSelect).css({width:width+'px'});
					var oOptions = $("#"+currentSelect.id +"> *");
					var totalOptions = oOptions.length;
					//internal counter
					for(var optionCount=0;optionCount<totalOptions;optionCount++) {
						var foundopt = false;
						   if(oOptions[optionCount].nodeName=="OPTION") {
								var currentOption = oOptions[optionCount];
								var values = this.getOptionsProperties(currentOption);
								prop.push(values);
						   } else if(oOptions[optionCount].nodeName=="OPTGROUP") {
							   foundopt = true;
							   var optinit = false;
							   var oCurrentOptGroup = oOptions[optionCount].childNodes;
							   for(var optoptioncount=0;optoptioncount<oCurrentOptGroup.length;optoptioncount++) {
									var currentOption = oCurrentOptGroup[optoptioncount];
									var values = this.getOptionsProperties(currentOption);
									if(values!=false) {
										if(optinit==false) {
											optinit = true;
											var opt = this.getOptGroupProperties(oOptions[optionCount]);
											values["optstart"] = opt;
										};
										prop.push(values);
									}
							   };
							   if(foundopt) {
								   //var last
								   prop[prop.length-1].optend = "end";
							   };

						   };
					};
					prop["id"] = currentSelect.id;
					prop["position"]= (this.dp_array[currentSelect.id]==undefined) ? $("#"+currentSelect.id).position() : this.dp_array[currentSelect.id].position;
					prop["width"]= width;
					prop["height"]= height;
					//store
					this.store(currentSelect.id, prop);
				} else {
					throw "An id is required!";
				};
			};
			//Make
			this.makeAdropDown(byID);
	},
	setOutOfVision: function(id) {
		$("#"+id).css({position:'absolute', left:'-5000px', top:'-5000px'});
	},
	makeAdropDown: function() {
		var alldps = this.getdps();
		var idMain = this.settings.idpostfixmain;
		var idChild = this.settings.idpostfixchild;
		var idhidden = this.settings.idhidden;
		var idA = this.settings.idpostfixa;
		var idtitlediv = this.settings.idposttitlediv;
		var idtitle = this.settings.idposttitle;
		var ddclass = this.styles.ddclass;
		var childclass = this.styles.childclass;
		var arrowclass = this.styles.arrowclass;
		var arrow = this.styles.arrow;
		var titleclass = this.styles.titleclass;
		var counter = 0;
		for(i in alldps) {
			var id = i;
			//hide original dropdown
			this.setOutOfVision(id);

			var values = alldps[i];
			var selectProp = values["selectAttributes"];
			var selectAction = values["selectAction"];
			//make a dropdown
			var position = values.position;
			var width = (values.width)+ 'px';
			var titlewidth = values.width  - 18 + 'px';
			var top = position.top + 'px'; //remove 100 when finalize
			var left = position.left + 'px';
			var dd_id = id+idMain; //+counter;
			if($("#"+dd_id).length>0)  { $("#"+dd_id).remove(); }
			var childid = dd_id+idChild;
			var childtitledivid = dd_id+idtitlediv;
			var titleid = dd_id+idtitle;
			var hiddeninput = dd_id+idhidden;
			var onchange = values.onchange;
			//alert(onchange)
			//make main holder //
			var childheight = '';
			//alert("values.length " + values.length)
			if(values.length>this.getVisibleRows()) childheight = '120';
			var ddhtml = "";
			var zIndex = this.zIndex--;
			var css = (selectProp["disabled"]==true) ? " " + this.styles.disabled : this.styles.enabled;
			ddhtml += "<div  id='"+dd_id+"' class='"+ddclass+"' style='position:relative;width:"+width+";z-Index:"+zIndex+"'>"; //main div
			if(selectProp["disabled"]==true) {
				ddhtml += "<div id='"+childtitledivid+"' class='"+css+"'><div class='"+arrowclass+"'><img src='"+arrow+"' border='0' align='right' /></div><div style='white-space:nowrap;width:"+titlewidth+"' class='"+titleclass+"' id='"+titleid+"'>Loading...</div><input style='text-indent:-400px;position:absolute; top:0; left:0; border:none; background:transparent;cursor:pointer;width:0px;heigh:0px;' type='text' value='' id='"+hiddeninput+"' name='"+hiddeninput+"' /></div>";//title div
			} else {
				ddhtml += "<div id='"+childtitledivid+"' class='"+css+"' onclick=\"MSDropDown.openDropDown('"+dd_id+"')\"><div class='"+arrowclass+"'><img src='"+arrow+"' border='0' align='right' /></div><div style='white-space:nowrap;width:"+titlewidth+"' class='"+titleclass+"' id='"+titleid+"'>Loading...</div><input style='position:absolute; top:0; left:0; border:none; background:transparent;cursor:pointer;width:0px;heigh:0px;' type='text' value='' id='"+hiddeninput+"' name='"+hiddeninput+"' /></div>";//title div
			};
			ddhtml += "<div id='"+childid+"' class='coloredScroll "+childclass+"' style='width:"+(values.width+2)+"px'>"; //child div
			var ahtml = "";
			//create a tag
			var sValue = "";
			var sImg = "";
			for(var aCount=0;aCount<values.length;aCount++) {
				var curretna = values[aCount];
				var aID = dd_id+"_a_"+aCount;
				var value = curretna.value;
				if(aCount==0){
					var selectedID =  aID;
				};
				var text = curretna.text;
				var selected = curretna.selected;
				var icon = curretna.icon;
				//get selected text
				if($("#"+id+" option:selected").text()==text) {
					sValue = text;
					selectedID = aID;
					var showIcon = this.getSetting("iconWithTitle");
					if(icon != undefined && showIcon==true) {
						sImg = "<img hspace='2' align='absMiddle' src='"+icon+"' />";
					};
				}
				var isDisabled = curretna.disabled;
				var img = "";
				var sTitle = (this.getShowTitle() == true) ? text : '';
				var innerStyle = (curretna.style!=undefined) ? curretna.style : '';
				if(curretna.optstart != undefined) {
					var optLabel = (curretna.optstart.label==undefined) ? '' : curretna.optstart.label;
					ahtml += "<div style='display:block;clear:both;'><span style='font-weight:bold;font-style:italic'>"+optLabel+"</span><div style='display:block;text-indent:10px;clear:both:'>";
				};
				if(icon != undefined) {img = "<img id='"+aID+"_icon' class='icon' align='left' src='"+icon+"' />";};
				if(isDisabled == undefined || isDisabled==false) {
					ahtml += "<a id='"+aID+"' title='"+sTitle+"' style='display:block;"+innerStyle+"'+ href='javascript:void(0);' value='"+(value)+"' onclick=\"MSDropDown.setSelected('"+dd_id+"', '"+text+"', '"+aID+"', '"+value+"', '"+icon+"')\">";//a tag start
				} else {
					ahtml += "<a id='"+aID+"' title='"+sTitle+"' style='cursor:pointer;filter:alpha(opacity=50);-moz-opacity:.50;opacity:.50;display:block;"+innerStyle+"' href='javascript:void(0);' value='"+(value)+"'>";//a tag start
				};
				ahtml += img + '<span>'+text+"</span></a>";//a tag end
				if(curretna.optend == "end") {
					ahtml += "</div></div>"; //opt group end
				};
				//ahtml += "<a id='"+aID+"' title='"+sTitle+"' style='display:block' href=\"javascript:void(0);MSDropDown.setSelected('"+dd_id+"','"+text+"','"+aID+"')\">"+img + '<span>'+text+"</span></a>" //a tag
			};
			sValue = (sValue=='') ? values[0].text : sValue;
			ddhtml += ahtml;
			ddhtml += "</div>"; //child div end
			ddhtml += "</div>" //main div end
			counter++;
			$("#" + id).after(ddhtml);
			//deafult opening
			if(selectProp["disabled"]==false) {
				//will do something
			} else {
				$("#"+dd_id).css({opacity:0.4});
			};
			//apply events
			this.applyEvents(dd_id, values, id);
			if(childheight!='') $("#"+childid).css({ overflowY:'scroll', overflowX:'hidden', height:childheight+'px'});
			//selected
			$("#"+titleid).html(sImg+sValue);
			this.manageSelection(id, selectedID);
			//i m not using this now;
			//this.setOutOfVision(hiddeninput);
		}

	},
	hasAction: function(prop, action) {
			var sAction = action;
			var selectAction = prop;
			for(var i in selectAction) {
				if(i.toString().toLowerCase()==sAction.toString().toLowerCase() && selectAction[i]==true) {
					return true;
				};
			};
			return false;
	},
	applyEvents: function(id, values, parent) {
		var sID = id;
		var parent_id = parent;
		var props = values;
		var selectProp = props["selectAttributes"];
		var selectAction = props["selectAction"];
		if(selectProp["disabled"]==false) {
			for(var i in selectAction) {
				if(selectAction[i]==true) {
					switch(i) {
						case 'onfocus':
						$("#"+sID).bind("focus", function(e) {$("#"+parent_id).focus();});
						break;
						case 'onblur':
						//has somewhere else;
						break;
						case 'onchange':
							//has somewhere else;
						break;
						case 'onclick':
						$("#"+sID).bind("click", function(e) {$("#"+parent_id).click();});
						break;
						case 'ondblclick':
						$("#"+sID).bind("dblclick", function(e) {$("#"+parent_id).dblclick();});
						break;
						case 'onmousedown':
						$("#"+sID).bind("mousedown", function(e) {$("#"+parent_id).mousedown();});
						break;
						case 'onmouseup':
						$("#"+sID).bind("mouseup", function(e) {$("#"+parent_id).mouseup();});
						break;
						case 'onmouseover':
						$("#"+sID).bind("mouseover", function(e) {$("#"+parent_id).mouseover();});
						break;
						case 'onmousemove':
						$("#"+sID).bind("mousemove", function(e) {$("#"+parent_id).mousemove();});
						break;
						case 'onmouseout':
						$("#"+sID).bind("mouseleave", function(e) {$("#"+parent_id).mouseout();});
						break;
						case 'onkeypress':
						$("#"+sID).bind("keypress", function(e) {$("#"+parent_id).keypress();});
						break;
						case 'onkeydown':
						$("#"+sID).bind("keydown", function(e) {$("#"+parent_id).keydown();});
						break;
						case 'onkeyup':
						$("#"+sID).bind("keyup", function(e) {$("#"+parent_id).keyup();});
						break;
					};
				};
			};
		};
	},
	addNewEvents: function(id) {
		document.getElementById(id).refresh = function(e) {
			MSDropDown.refresh(this.id);
		}
	},
	refresh: function(id) {
		MSDropDown.storeNmake("#"+id);
	},
	manageSelection: function(id, selected) {
		if(this.selected[id]==undefined) {
			this.selected[id] = {selected:selected, previous:selected};
		};
		this.selected[id].selected = selected;
		if(this.selected[id].previous != this.selected[id].selected) {
			$("#"+ this.selected[id].previous).removeClass('selected');
		};
		$("#"+ this.selected[id].selected).addClass('selected');
		this.selected[id].previous = this.selected[id].selected;
	},
	/**** manage selection ***/
	setSelected: function(id, value, aID, val, imgsrc) {
		var parentID = id.split("_")[0];
		this.selected[parentID].current = aID;
		var sID = id;
		var oPorop = prop;
		var targetDiv = sID+this.settings.idposttitle;//"_title";
		var hiddeninput = sID+this.settings.idhidden;
		var prop = this.getdps(parentID);

		$("#"+parentID + " option:selected").text(value.toString());
		//working here... (problem in ie if value is not defined)
		$("#"+parentID + " option:selected").val(val.toString());
		//check if there is any method;
		if($("#"+parentID).attr("onfocus")!=undefined) {
			$("#"+parentID).focus();
			$("#"+hiddeninput).focus();
		};
		if($("#"+parentID).attr("onchange")!=undefined) {
			$("#"+parentID).change();
		};
		$("#"+hiddeninput).val(value);
		var showIcon = this.getSetting("iconWithTitle");
		if(imgsrc.toString()!='undefined' && showIcon==true) {
			value = "<img hspace='2' align='absmiddle' src='"+imgsrc+"' />"+ value;
		}
		$("#"+targetDiv).html(value);
		this.manageSelection(parentID, aID);
		//alert(this.selected[parentID].current);
		this.closeDropDown();
	},
	openDropDown:function(id) {
		//$("#city").text = "Delhi";
		var prentDiv = id;
		var childDiv = id+="_child";
		if($("#"+childDiv).css("display")=="block") {
			MSDropDown.closeDropDown();
			return false;
		};
		var position = $("#"+prentDiv).position();
		var childPosTop = $("#"+prentDiv).height() + parseInt($("#"+prentDiv).css("padding-top")) + 'px';
		var parentWidth = parseInt($("#"+prentDiv).width());
		var childWidth = parseInt($("#"+childDiv).width());
		if(childWidth  < parentWidth) {
			$("#"+childDiv).css({width:$("#"+prentDiv).width()+'px'});
		}
		this.currentDiv = childDiv;
		$("#"+childDiv).css({position:'absolute', top:childPosTop, left:'-1px'});
		$("#"+childDiv).slideDown("fast");
		//$("#"+childDiv).show("fast");
		$("#"+childDiv).mouseover(function(e) {
									MSDropDown.setInsideWindow(true);
									});
		$("#"+childDiv).mouseout(function(e) {
									MSDropDown.setInsideWindow(false);
									});
		$(document).bind('mouseup', function(e) {
												if(MSDropDown.insideWindow==false) {
													$(document).unbind('mouseup');
													MSDropDown.closeDropDown();
												}
											 });
	},
	setInsideWindow: function(set) {
		this.insideWindow = set;
	},
	closeDropDown: function() {
		var curerntDiv = this.currentDiv;
		var parentID = curerntDiv.split("_")[0];
		var hiddeninput = curerntDiv+this.settings.idhidden;
		if($("#"+parentID).attr("onblur")!=undefined) {
			$("#"+parentID).focus();
			$("#"+hiddeninput).focus();
		};
		$("#"+this.currentDiv).slideUp("fast");
		//$("#"+this.currentDiv).hide("fast");
	},
	/* getter setter */
	store: function(id, prop) {
		this.dp_array[id] = prop;
	},
	getdps: function(byID) {
		return (byID==undefined) ? this.dp_array : this.dp_array[byID];
	},
	getAllDropDown: function(byID) {
		return (byID==undefined) ? $("body select") : $(byID);
	},
	showTitle: function(show) {
		this.settings.showTitle = show;
	},
	getShowTitle: function() {
		return this.settings.showTitle;
	},
	setVisibleRows: function(rows) {
		this.settings.visibleRows = rows;
	},
	getVisibleRows: function() {
		return this.settings.visibleRows;
	},
	getSelectProperties: function(id) {
		var currentSelect = id;
		var attributes = this.attributes.prop;
		var prop = new Object();
		//attributes
		var attribs = attributes.split(",");
		var total = attribs.length;
		prop.attributes = new Object();
		for(var iCount=0;iCount<total;iCount++) {
			var key = attribs[iCount].toString();
			var value = $("#"+currentSelect).attr(key);
			if(value!=undefined) {
				prop.attributes[key] = value;
			};
		};
		//actions
		attributes = this.attributes.action;
		attribs = attributes.split(",");
		total = attribs.length;
		prop.action = new Object();
		for(var iCount=0;iCount<total;iCount++) {
			var key = attribs[iCount].toString();
			var value = $("#"+currentSelect).attr(key);
			if(value!=undefined) {
				prop.action[key] = true;
			} else {
				prop.action[key] = false;
			};
		};
		return prop;
	},
	getOptionsProperties: function(option) {
		//returns : options, selected, icons
		var currentOption = option;
		if(currentOption.text!=undefined) {
			var prop = new Object();
			prop["text"] = currentOption.text;
			prop["value"] = (currentOption.value==undefined) ? currentOption.text : currentOption.value;
				var attribs = currentOption.attributes;
				var total = attribs.length;
				for(var iCount=0;iCount<total;iCount++) {
					var att = attribs[iCount];
					prop[att.nodeName] = att.nodeValue;
				};
			return prop;
		} else {
			return false;
		};
	},
	getOptGroupProperties: function(opt) {
		var oOpt = opt;
		var prop = new Object();
		prop["optstart"] = "start";
		var attribs = oOpt.attributes;
		var total = attribs.length;
		if(total>0) {
			for(var iCount=0;iCount<total;iCount++) {
				var att = attribs[iCount];
				prop[att.nodeName] = att.nodeValue;
			};
		};
		return prop;
	},
	showIconWithTitle: function(show) {
		this.settings.iconWithTitle = show;
	},
	getSetting: function(prop) {
		return this.settings[prop];
	}
}

//how to call
/*
$(document).ready(function(e) {
							try {
								MSDropDown.init();
							} catch(e) {
								alert(e);
							}
						   }
				  )
*/