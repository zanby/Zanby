var ifbinfobox
var ifbinfobox = "ifbinfobox";
var ifbURL;
var ifbnon = '';
var ifbthisURL;
ifbthisURL= window.location.href;

document.write('<div id="ifbinfobox" class="ifbinfobox">Version = ' + ifbversion );
document.write(' &nbsp; <a href="https://www.warecorp.com/redmine/projects/' + ifbproject + '/issues/new" target="new">Enter a bug</a><br /	><hr>Search Redmine: <form name="ifbke" method="get" id="ifbke" class="ifbke"><input name="ifbkeyword" type="text" value="keyword"  onkeypress="if (event.keyCode == 13)ifbGoSearch();" onclick="this.value=ifbnon;"/><a href="" onClick="ifbGoSearch(); return false;">Go</a></form> ');
//document.write(' <hr> <a href="https://www.warecorp.com/bugzilla/buglist.cgi?query_format=advanced&short_desc_type=allwordssubstr&short_desc=&product=' + ifbproject + '&long_desc_type=allwordssubstr&long_desc=&bug_file_loc_type=allwordssubstr&bug_file_loc='  +  ifbthisURL + '&status_whiteboard_type=allwordssubstr&status_whiteboard=&keywords_type=allwords&keywords=&deadlinefrom=&deadlineto=&bug_status=NEW&bug_status=ASSIGNED&bug_status=REOPENED&bug_status=RESOLVED&emailassigned_to1=1&emailreporter1=1&emailtype1=substring&email1=&emailassigned_to2=1&emailreporter2=1&emailqa_contact2=1&emailcc2=1&emailtype2=substring&email2=&bugidtype=include&bug_id=&votes=&chfieldfrom=&chfieldto=Now&chfieldvalue=&cmdtype=doit&order=Reuse+same+sort+as+last+time&field0-0-0=noop&type0-0-0=noop&value0-0-0=" target="new">Check for bugs logged on this URL</a> ');
document.write(' <div id="ifbclosebtn" class="ifbclosebtn" onclick="javascript:ifbhidediv(ifbinfobox)" >Close</div></div>');
  
document.write('<div id="ifbinfoboxswitch" onclick="javascript:ifbshowdiv(ifbinfobox);" class="ifbinfoboxswitch">&nbsp;</div>');

function ifbGoSearch() {
if (document.ifbke.ifbkeyword.value == 'keyword') { alert('Please enter a value'); }
else {
ifbURL = "https://www.warecorp.com/redmine/search/index/" + ifbproject + "?q=" + document.ifbke.ifbkeyword.value;
window.open(ifbURL);
}
}

function ifbhidediv(id) {
	//safe function to hide an element with a specified id
	if (document.getElementById) { // DOM3 = IE5, NS6
		document.getElementById(id).style.display = 'none';
	}
	else {
		if (document.layers) { // Netscape 4
			document.id.display = 'none';
		}
		else { // IE 4
			document.all.id.style.display = 'none';
		}
	}
}

function ifbshowdiv(id) {
	//safe function to show an element with a specified id
	if (document.getElementById) { // DOM3 = IE5, NS6
		document.getElementById(id).style.display = 'block';
	}
	else {
		if (document.layers) { // Netscape 4
			document.id.display = 'block';
		}
		else { // IE 4
			document.all.id.style.display = 'block';
		}
	}
}