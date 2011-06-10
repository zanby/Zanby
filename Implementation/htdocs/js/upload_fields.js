function show_more()
{
  var browser_name = navigator.appName;

  fields=document.getElementById('fields_table').getElementsByTagName('tr');

  for (i=0; i<fields.length; i++) {

    if ( (fields[i].title=="file_field") && (fields[i].style.display=="none") ) {

        if (navigator.userAgent.indexOf ("Opera") != -1) {
             fields[i].style.display="table-row";
        }
        else if ( (browser_name == 'Microsoft Internet Explorer')) {
             fields[i].style.display="block";
        }
        else {
             fields[i].style.display="table-row";
        };

        break;
     };

  }
  return false;
}

//@author Komarovski
function show_more_advanced(avatarsLeft)
{
  var browser_name = navigator.appName;

  fields=document.getElementById('fields_table').getElementsByTagName('tr');

  var flag = 0;
  
  for (i=0; i<fields.length; i++) {

    if ( (fields[i].title=="file_field") && (fields[i].style.display=="none") && avatarsLeft > i) {
		
		flag = i+1;
        
		if (navigator.userAgent.indexOf ("Opera") != -1) {
             fields[i].style.display="table-row";
        }
        else if ( (browser_name == 'Microsoft Internet Explorer')) {
             fields[i].style.display="block";
        }
        else {
             fields[i].style.display="table-row";
        };

        break;
     };

  }
  
  if (!flag || flag >= avatarsLeft) {
	  document.getElementById('more_avatars_link').style.display="none";
  }
  
  return false;
}