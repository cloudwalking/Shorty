 /*

	(c) 2006 Pau Santesmasses  <firstname at lastname.net>
		 and Reed Morse <firstname.lastname at gmail.com>

*/

var administrator = {
  editing:false,
  init: function () {
    Element.hide('search-spinner');
    Element.hide('clearBtn');
    new Form.Element.Observer('searchTerm', 
                               0.5,
                               this.srch)
  },
  srch: function(element,value) {
     Element.hide('searchBtn');
     $('clearBtn').style.display = "inline";
     new Ajax.Updater('shortyList',
                       'search.php', 
                       {asynchronous:true, 
                        evalScripts:true,
/*                        onCreate: $('spinner').style.display="inline",*/
                        onLoading:function(request) {
/*                             Element.show('spinner');*/
                             $('search-spinner').style.display="inline";
                        },
                        onSuccess:function(request) {
                            Element.hide('search-spinner');
                            $('totalResultsYay').innerHTML = "<p>Displaying Shorty search results</p>";
                        },
                        parameters:'query=' + escape(value)})
  },
  edit: function(caller) {
    //if(this.editing) return false;
    Element.toggle(caller);
    Element.toggle(caller.nextSibling.nextSibling);
    caller.nextSibling.style.display='inline';
    caller.nextSibling.nextSibling.nextSibling.style.display='inline';
    this.editing = true; 
    new Ajax.Updater(caller.parentNode.parentNode,
                    'edit.php?id='+ caller.name,
                    {insertion:Insertion.After}
                    );
  },
  save: function(caller) {
    newURL = caller.parentNode.parentNode.nextSibling.nextSibling.firstChild.firstChild.value;
    Element.remove(caller.parentNode.parentNode.nextSibling.nextSibling);
    var args = 'newURL='+newURL;
    new Ajax.Request('save.php?id='+caller.name,
                    {method: 'post',
                    parameters: args,
                    onLoading: $('main-spinner').style.display="block",
                    onSuccess: function () {
                                  Element.toggle(caller.previousSibling);
                                  Element.toggle(caller);
                                  Element.toggle(caller.nextSibling);
                                  Element.toggle(caller.nextSibling.nextSibling);
                                  Element.hide('main-spinner');
                               }
                    });
    this.editing = false;
  },
  destroy: function(caller) {
    if(this.editing) instance.save(caller);
    new Ajax.Request('delete.php?id='+caller.name,
                     {onLoading: $('main-spinner').style.display="block",
                     onComplete: function(){
                                    Element.remove(caller.parentNode.parentNode);
                                    Element.hide('main-spinner');
                                  }
                     });
  },
  cancel: function(caller) {
    Element.toggle(caller.previousSibling.previousSibling.previousSibling);
    Element.toggle(caller.previousSibling.previousSibling);
    Element.toggle(caller.previousSibling);
    Element.toggle(caller);
    Element.remove(caller.parentNode.parentNode.nextSibling.nextSibling);
    this.editing = false;
  },
  showHostname: function() {
    this.hostnames = $A(document.getElementsByClassName('hostname'));
    this.hostnames.each(function(hostname) {
                          $(hostname).style.display="inline";
                        });
  },
  hideHostname: function() {
    this.hostnames = $A(document.getElementsByClassName('hostname'));
    this.hostnames.each(function(hostname) {
                          Element.hide(hostname);
                        });
  },
  clr: function() {
    new Ajax.Updater('board',
                     'admin.php',
                     {method: 'post',
                     evalScripts: true,
                     onLoading: $('search-spinner').style.display="inline",
                     onSuccess: function(){
                       Element.show('searchBtn')
                       Element.hide('clearBtn')                       
                     }
                     });
  
  }
}