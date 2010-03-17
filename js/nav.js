 /*

	(c) 2006 Pau Santesmasses  <firstname at lastname.net>
		 and Reed Morse <firstname.lastname at gmail.com>

*/

function nav(id) {
    this.element = $(id); if (!this.element) return false;  
    this.menu = this.element.getElementsByTagName("ul")[0];
    this.menuOptions = this.menu.getElementsByTagName("li");
    this.menuAnchors = this.menu.getElementsByTagName("a");
    this.init();
}

nav.prototype.init = function() {    
    var thisInstance = this;
    hrefs = this.targetHref;
    for(var i = 0; i < this.menuAnchors.length; i++) {
        this.menuAnchors[i].onclick = function() { 
            if (this.className == 'selected') return false;
            thisInstance.deselect();
            thisInstance.select(this);
            new Ajax.Updater('board',this.href,{evalScripts: true});
            return false; 
        }                        
    }
}

nav.prototype.deselect = function() {
    for(var i = 0; i < this.menuAnchors.length; i++) {
        this.menuAnchors[i].className = "";
    }                        
}

nav.prototype.select = function(option) {
    option.className = "selected";
}

nav.prototype.unfold = function(option) {
  if (this.folded){
    option = $(option)
    anchor = option.firstChild;
    for(var i = 0; i < this.menuAnchors.length; i++) {
       this.menuOptions[i].style.display="block";
    }                        
    $('loginOption').style.display='none';
    this.deselect();
    this.select(anchor);
    this.folded = false;
  }
}

nav.prototype.fold = function() {
  if (!this.folded){
    for(var i = 0; i < this.menuAnchors.length; i++) {
       this.menuOptions[i].style.display="none";
    }                        
    $('loginOption').style.display='block';
    this.deselect();
    this.select(this.menuAnchors[0]);
    this.folded = true;
  }
}

nav.prototype.folded = true;

