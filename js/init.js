 /*

	(c) 2006 Pau Santesmasses  <firstname at lastname.net>
		 and Reed Morse <firstname.lastname at gmail.com>

*/

window.onload = init;

function init() {
  menu = new nav("menu");
  new Ajax.Updater("board","login.php",{evalScripts:true});
}

function forward(page) {
  new Ajax.Updater("board",page,{evalScripts:true});
}

function login() {
  var username = $('username').value;
  var password = $('password').value;
  var args = 'username='+username+'&password='+ password;
  new Ajax.Updater('board',
                   'oven.php',
                   {method: 'post',
                    parameters: args,
                    evalScripts: true}
  );
}



/*prints to firebug    --->  USE console.log() */ 
/*function printfire()
{
  if (document.createEvent)
  {
    printfire.args = arguments;
    var ev = document.createEvent("Events");
    ev.initEvent("printfire", false, true);
    dispatchEvent(ev);
  }
  }*/
