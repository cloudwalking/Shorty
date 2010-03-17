 /*

	(c) 2006 Pau Santesmasses  <firstname at lastname.net>
		 and Reed Morse <firstname.lastname at gmail.com>

*/

var actionDispatcher = {
  takeAction: function() {
    //console.log('widget: '+this.name+' value:'+this.value)
    switch(this.value.toLowerCase()){
// Admin
      case 'edit': 
        administrator.edit(this);
        break;
      case 'save':
        administrator.save(this);
        break;
      case 'delete':
        administrator.destroy(this)
        break;
      case 'cancel':
        administrator.cancel(this)
        break;
      case 'search':
        administrator.srch($F('searchTerm'));
        break;
      case 'clear':
        administrator.clr();
        break;
      case "12":
        prefEditor.setPref('perpage','12');
        forward("admin.php?force=12");
        break;
      case "48":
        prefEditor.setPref('perpage','48');
        forward("admin.php?force=48");
        break;
      case "all":
        prefEditor.setPref('perpage','all');
        forward("admin.php?force=all");
        break;
      case "longurl":
        if (this.checked) {
          prefEditor.setPref('longURL',1);
          administrator.showHostname();
        }
        else {
          prefEditor.setPref('longURL',0);
          administrator.hideHostname();
        }
        break;        
// Home
      case "get shorty!":
        switch (this.id) {
          case 'autoBtn':
            creator.getShorty('int','response1');
            break;
          case 'keywordBtn':
            creator.getShorty('desc','response2');
            break;
          case 'randomBtn':
            creator.getShorty('rand','response3');
            break;
          }
          break;
      case "random":
        creator.resetCheckboxes(this);
        if (this.checked) prefEditor.setPref('mode','random');
        else prefEditor.setPref('mode','');
        break;
      case "keywords":
        creator.resetCheckboxes(this);
        if (this.checked) prefEditor.setPref('mode','keywords');
        else prefEditor.setPref('mode','');
        break;
      case "auto":
        creator.resetCheckboxes(this);
        if (this.checked) prefEditor.setPref('mode','auto');
        else prefEditor.setPref('mode','');
        break;
// Prefs page
      case "save prefs":
        inputs = Form.getElements("userInfo");
        prefEditor.savePrefs(inputs);
        break;
      case "change":
        prefEditor.changePassword($('old').value,$('new1').value,$('new2').value);
        break;
// Forgot Password
      case "remind me":
        prefEditor.forgotPassword($('username').value);
        break;
    }
  }
}