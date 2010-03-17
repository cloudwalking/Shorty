 /*

	(c) 2006 Pau Santesmasses  <firstname at lastname.net>
		 and Reed Morse <firstname.lastname at gmail.com>

*/

var creator = {
  init: function() {
    new Form.Element.Observer('int-url', 
                               0.5,
                               this.previewKeyword)
  },
  previewKeyword: function(element,value) {
    new Ajax.Updater('namespace',
                     'previewKeyword.php',
                     { method: 'get',
                     parameters:'url=' + escape(value),
                     onLoading: $('main-spinner').style.display="inline",
                     onComplete: Element.hide('main-spinner')
                     });
  },
  getShorty: function(method,target) {
    var link = $F(method + '-url');
    var url  = "create.php?descriptor="+ $F('descriptor') +
               "&key-1="  + $F('key1') +
               "&key-2="  + $F('key2') +
               "&key-3="  + $F('key3') +
               "&key-4="  + $F('key4') +
               "&method=" + method;
              
    var args = "args="+link;
    new Ajax.Updater(target,
                     url,
                     { method: 'post',
                     parameters: args,
                     onLoading: $('main-spinner').style.display="inline",
                     onComplete: Element.hide('main-spinner')
                     });
  },
  resetCheckboxes: function(caller) {
    checkboxes = $('keywordsCheckbox','autoCheckbox','randomCheckbox');
    $A(checkboxes).each(function(checkbox){
                          if (checkbox != caller) checkbox.checked = false;
                        });
  }
}