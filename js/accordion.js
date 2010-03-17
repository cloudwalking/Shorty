 /*

	(c) 2006 Pau Santesmasses  <firstname at lastname.net>
		 and Reed Morse <firstname.lastname at gmail.com>

*/

function accordion(id) {
  this.element = document.getElementById(id); if (!this.element) return false;  
  this.ul = this.element.getElementsByTagName("ul")[0];
  this.tabs = this.ul.getElementsByTagName("li");
  this.tabContent = this.getTabContent();
  this.bind();
  this.setPrefs();
}

accordion.prototype.getTabContent = function() {
  tabContent= new Array();    
  this.divs = this.element.getElementsByTagName("div");
  for(var i = 0; i < this.divs.length; i++) {
    if (/tabContent/i.test(this.divs[i].className)) {
      tabContent.push(this.divs[i]);                        
    }
  }
  return tabContent;
}

accordion.prototype.bind = function() {    
  var o = this;
  for(var i = 0; i < this.tabs.length; i++) {
    this.tabs[i].onclick = function() { o.open(this); return false; };                        
  }
}

accordion.prototype.setPrefs = function() {
  //this.pref = getCookie('method');
  this.pref = $('selectedTab').value;
  if (!this.pref || this.pref=='') {
    defaultTab = $('autoTab');
    this.open(defaultTab);
    return false;
  }
  tab = $(this.pref+'Tab');
  checkbox = $(this.pref+'Checkbox');
  this.open(tab);
  checkbox.setAttribute('checked',true);
}

accordion.prototype.open = function(caller) { 
  for(var i = 0; i < this.tabs.length; i++) {
    var tab = this.tabs[i]; 
    if (tab == caller) {  
      this.collapse();
      tab.className = "selected"
      this.tabContent[i].style.display = "block";
    }
  }
}

accordion.prototype.collapse = function() {
  for(var i = 0; i < this.tabs.length; i++) {    
    this.tabs[i].className = "";
    this.tabContent[i].style.display = "none";       
  }                                                                
}