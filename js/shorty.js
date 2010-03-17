// Initialization script taken from the script.aculo.us lib by Thomas Fuchs

// Copyright (c) 2005 Thomas Fuchs (http://script.aculo.us, http://mir.aculo.us)
// 
// Permission is hereby granted, free of charge, to any person obtaining
// a copy of this software and associated documentation files (the
// "Software"), to deal in the Software without restriction, including
// without limitation the rights to use, copy, modify, merge, publish,
// distribute, sublicense, and/or sell copies of the Software, and to
// permit persons to whom the Software is furnished to do so, subject to
// the following conditions:
// 
// The above copyright notice and this permission notice shall be
// included in all copies or substantial portions of the Software.
//
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
// EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
// MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
// NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
// LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
// OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
// WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.


var Shorty = {
  Version: '0.6.4',
  require: function(libraryName) {
    // inserting via DOM fails in Safari 2.0, so brute force approach
    document.write('<script type="text/javascript" src="'+libraryName+'"></script>');
  },
  load: function() {
    if((typeof Prototype=='undefined') ||
      parseFloat(Prototype.Version.split(".")[0] + "." +
                 Prototype.Version.split(".")[1]) < 1.4)
      throw("Shorty requires the Prototype JavaScript framework >= 1.4.0");
    
    $A(document.getElementsByTagName("script")).findAll( function(s) {
      return (s.src && s.src.match(/shorty\.js(\?.*)?$/))
    }).each( function(s) {
      var path = s.src.replace(/shorty\.js(\?.*)?$/,'');
      var includes = s.src.match(/\?.*load=([a-z,]*)/);
      (includes ? includes[1] : 'nav,creator,administrator,prefEditor,actionDispatcher,buttonCollector,cookieCutter,sorttable,accordion,init').split(',').each(
/*      (includes ? includes[1] : 'nav,editButtons,cookieCutter,sorttable,accordion,init').split(',').each(*/
       function(include) { Shorty.require(path+include+'.js') });
    });
  }
}

Shorty.load();