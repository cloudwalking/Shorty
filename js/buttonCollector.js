 /*

	(c) 2006 Pau Santesmasses  <firstname at lastname.net>
		 and Reed Morse <firstname.lastname at gmail.com>

*/

var buttonCollector = {
  init: function(id) {
    editing = null;
    this.container = $(id);
    this.inputs = this.container.getElementsByTagName('input');
    this.inputs = $A(this.inputs);
    this.selects = this.container.getElementsByTagName('select');
    this.selects = $A(this.selects);
    this.inputs.each(function(input){
/*                       console.log(input.type)*/
      
                       if(input.type == 'submit' || input.type == 'button' ||  input.type == 'checkbox' || input.type == 'radio') {
                           Event.observe(input, 
                                        'click', 
                                         actionDispatcher.takeAction);
                       }
                       if(input.type == 'select' ) {
                           Event.observe(input,
                                        'change',
                                         actionDispatcher.takeAction);
                       }
                     })
  }
}