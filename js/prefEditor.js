 /*

	(c) 2006 Pau Santesmasses  <firstname at lastname.net>
		 and Reed Morse <firstname.lastname at gmail.com>

*/

var prefEditor = {
  savePrefs: function(prefs) {
    $('main-spinner').style.display="block";
    prefs = $A(prefs);
    i = 0;
    prefs.each(function(pref){
      if (pref.type != 'submit') prefEditor.setPref(pref.name,pref.value);
      i++;
      done = (i == prefs.length )? true : false;
    })
  },
  setPref: function(pref,value){
    var url = 'setprefs.php?';
    var args = 'pref=' + pref + '&value=' + value;
    new Ajax.Request(url,
                    {method: 'post',
                     parameters: args,
                     onSuccess:function(){if (done) Element.hide('main-spinner') }
                     }
                    );
  },
  changePassword: function(old,new1,new2){
	var url = 'setprefs.php?';
	var args = 'old=' + old + '&new1=' + new1 + '&new2=' + new2;
	new Ajax.Request(url,
                  {method: 'post',
                   parameters: args,
                   onLoading: $('main-spinner').style.display="block",
                   onSuccess:Element.hide('main-spinner')
                  });
  },
  forgotPassword: function(username){
    var url = 'forgot.php?sent=yes&username=' + username;
    new Ajax.Updater("board",url,{evalScripts:true});
  }
}