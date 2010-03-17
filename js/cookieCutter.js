 /*

	(c) 2006 Pau Santesmasses  <firstname at lastname.net>
		 and Reed Morse <firstname.lastname at gmail.com>

*/

function setCookie(name,value,days) {
  if(typeof(days) == "undefined") days = 7;
  var today = new Date;
  today.setTime(today.getTime() + days * 24 * 3600000);
  var expires = "; expires=" + today.toGMTString();
  galeta = name + "=" + value + expires;
  document.cookie = galeta;
  //alert(document.cookie)
}

function getCookie(name) {
  var start = document.cookie.indexOf(name);
  if(start == -1) return ""; 
  var op = document.cookie.indexOf("=", start);
  var end = document.cookie.indexOf(";", start);
  if(end == -1) end = document.cookie.length;
  return document.cookie.substring(op+1, end);
}
