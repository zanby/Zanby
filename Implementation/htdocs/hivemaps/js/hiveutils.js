(function($){
  
  function xml2js(xmlDoc, containerTag) {
    var output = new Array();
    var rawData = xmlDoc.getElementsByTagName(containerTag)[0];
    if (!rawData)
      return false;
    var i, j, oneRecord, oneObject;
    for (i = 0; i < rawData.childNodes.length; i++) {
      if (rawData.childNodes[i].nodeType == 1) {
        oneRecord = rawData.childNodes[i];
        oneObject = output[output.length] = new Object();
        for (j = 0; j < oneRecord.childNodes.length; j++) {
          if (oneRecord.childNodes[j].nodeType == 1 && oneRecord.childNodes[j].firstChild) {
            oneObject[oneRecord.childNodes[j].tagName] = oneRecord.childNodes[j].firstChild.nodeValue;    
          }
        }
      }
    }
    return output;
  }
  
  function getCountry(code) {
    return ISO3166.codes[code];
  }
  
  var HiveUtils = {
    xml2js: xml2js,
    getCountry: getCountry
  };
  
  if (!window.SOCIALHIVE)
    window.SOCIALHIVE = {};
  window.SOCIALHIVE.utils = HiveUtils || window.HiveUtils;
})(jQuery);