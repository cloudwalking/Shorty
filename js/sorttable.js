/*Copyright (c) 1997-date Stuart Langridge

Permission is hereby granted, free of charge, to any person obtaining a copy of this software 
and associated documentation files (the "Software"), to deal in the Software without restriction, 
including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, 
and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, 
subject to the following conditions:

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT 
NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, 
WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE 
SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

// This code originally comes from:
// http://www.kryogenix.org/code/browser/sorttable/

// 2004-12-03: Modifications by mcramer@pbs.org: http://blog.webkist.com/archives/000043.html
// 2005-01-07: Modifications by Anthony.Garrett@aods.org (tested IE 6.0.28, Opera 7.54, Firefox 1.0)
//             [Problem: Firefox 1.0 won't display the span style for the up and down arrows.]
// 2005-01-11: Anthony.Garrett@aods.org Fixed small bug:
//             Error occurred when clicking on column header link just to the right of the text.
// 2005-01-11: mcramer@pbs.org integrated AG's fixes and added support for <select> sorting.
// 2005-01-13: mcramer@pbs.org: Caching optimizations. Should be faster on big tables.
// 2005-04-29: mcramer@pbs.org: Style fix, "nosort" stuff, and don't link empty headers.
// 2005-10-11: pausantesmasses@gmail.com : Added alternate row support from http://www.almosteffortless.com/files/sorttable.js

addEvent(window, "load", sortables_init);

var SORT_COLUMN_INDEX;

// Set this array up for any language you choose.  
// You can use any strings you like for the months as long as the array matches the data.
var monthName = new Array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');

function sortables_init() {
    // Find all tables with class sortable and make them sortable
    if (!document.getElementsByTagName)
    return;
    tbls = document.getElementsByTagName("table");
    for (ti = 0; ti < tbls.length; ti++) {
        thisTbl = tbls[ti];
        if (((' ' + thisTbl.className + ' ').indexOf(" sortable ") != -1) && (thisTbl.id)) {
            ts_makeSortable(thisTbl);
        }
    }
}

function ts_makeSortable(table) {
    var firstRow;
    for (var i = 0; i < table.rows.length; i++) {
        firstRow = table.rows[i];
        if (firstRow.cells && firstRow.cells[0].nodeName == "TD") {
            firstRow = table.rows[i - 1];
            break;
        }
    }

    if (!firstRow)
    return;

    // IE 6.0.28  Resets checkbox values to their initial state on sort.
    // BUG (see http://www.quirksmode.org/bugreports/archives/2004/10/moving_checkbox.html)
    //     solution 1: Don't use a crappy browser like IE.
    //     solution 2: Store the checkbox state and restore after move.
    // --> solution 3: Set defaultChecked to the value of checked each time the checkbox is changed.

    // AG Avoid IE bug that uses "defaultChecked" when it should use "checked" on move of element
    var inputs = document.getElementsByTagName("input");
    for (var i = 0; i < inputs.length; i++) {
        if (inputs[i].type.toLowerCase() == 'checkbox')
        addEvent(inputs[i], "change", ts_persistCheckbox);
    }

    // We have a first row: assume it's the header, and make its contents clickable links
    for (var i = 0; i < firstRow.cells.length; i++) {
        var cell = firstRow.cells[i];
        if(cell.childNodes.length > 0 && cell.className.indexOf("nosort") == -1) {
            var link = document.createElement("a");
            link.href = "#";
            link.style.color = "inherit";
            link.className = "sortheader";  // AG Added (makes the styling work)
            addEvent(link, "click", ts_resortTable);
            var l = cell.childNodes.length;
            while (cell.childNodes.length > 0) {
                link.appendChild(cell.childNodes[0]);
            }
            var span = document.createElement("span");
            span.className = "sortarrow";
            if (i==0)
            span.innerHTML = (i == 0)? '▼':'';
            link.appendChild(span);
            cell.appendChild(link);
        }
    }
}

function ts_getInnerText(el) {
    if (typeof el == "string" || typeof el == "undefined")
    return el;

    if(el.ts_allText)
    return el.ts_allText;

    var str = new Array();
    var cs = el.childNodes;

    for (var i = 0; i < cs.length; i++) {
        switch (cs[i].nodeType) {
            case 1: // ELEMENT_NODE
            if(cs[i].tagName.toLowerCase() == 'input') {
                if(cs[i].type.toLowerCase() == 'text')
                str.push(cs[i].value)
                else if(cs[i].type.toLowerCase() == 'checkbox')
                str.push(cs[i].checked)
                else
                str.push(ts_getInnerText(cs[i]));
            }
            else if(cs[i].tagName.toLowerCase() == 'select') {
                str.push(cs[i].options[cs[i].selectedIndex].value);
                } else {
                    str.push(ts_getInnerText(cs[i]));
                }
                break;
                case 3: // TEXT_NODE
                str.push(cs[i].nodeValue);
                break;
        }
    }
        // Save the extracted text for later. This costs the client RAM,
        // but saves major CPU when the cell contents are particularly
        // complex.
        return el.ts_allText = str.join(" ");
}

function ts_expireCache(el) {
    var cs = el.childNodes;
    if(typeof el.ts_allText == "undefined")
    return false

    for (var i = 0; i < cs.length; i++) {
        if(cs[i].nodeType == 1) { // ELEMENT_NODE
            // We need to expire the cache if the element
            // contains any form-type nodes, just in case
            // the user changes their value.
            if(cs[i].tagName.toLowerCase() == 'input')
            return delete el.ts_allText
            else if(cs[i].tagName.toLowerCase() == 'select')
            return delete el.ts_allText
            else if(ts_expireCache(cs[i])) 
            return delete el.ts_allText
        }
    }
    return false
}

function ts_persistCheckbox(event) {
    // AG - Avoids IE bug
    var chkbox = event.currentTarget ? event.currentTarget : event.srcElement;
    chkbox.defaultChecked = chkbox.checked;
    return true;
}

function ts_resortTable(event) {
    var lnk = event.currentTarget ? event.currentTarget : event.srcElement;
    //added support for selecting the proper th --requires prototype.js
    var tr = lnk.parentNode.parentNode;
    var th = lnk.parentNode;
    var ths = tr.childNodes;
    var ths =$A(ths)
    ths.each(function(node){
        node.className = '';
        })
        th.className += (th.className == '')? 'selected': ' selected';
        
        // AG - IE doesn't support "currentTarget", must use "srcElement" instead.
        // get the span
        var span;
        if(lnk.tagName && lnk.tagName.toLowerCase() == 'span')
        span = lnk;
        else {
            for (var ci=0; ci<lnk.childNodes.length; ci++) {
                if(lnk.childNodes[ci].tagName && lnk.childNodes[ci].tagName.toLowerCase() == 'span')
                span = lnk.childNodes[ci];
            }
        }
        var td = lnk.parentNode;
        while(td.tagName != 'TD' && td.tagName != 'TH')
        td = td.parentNode;

        var column = td.cellIndex;
        var table = getParent(td, 'TABLE');

        var nonHeaderIndex;
        for (nonHeaderIndex = 0; nonHeaderIndex < table.rows.length; nonHeaderIndex++) {
            if(table.rows[nonHeaderIndex].cells &&
                table.rows[nonHeaderIndex].cells[0].nodeName == "TD") {
                    break;
                }
            }

            // If 0, the table has no rows. If >= table.rows.length, it has no data.
            if(nonHeaderIndex == 0 || nonHeaderIndex >= table.rows.length)
            return;

            // Work out a type for the column
            var itm = ts_getInnerText(table.rows[nonHeaderIndex].cells[column]);

            var dateregex = new RegExp("^\\d\\d[\\/-](\\d\\d|" +
            monthName.join("|") +
            ')[\\/-]\\d\\d(\\d\\d)?$', "i")
            // A date in dd/mm/yy format.
            if (itm.match(dateregex))
            sortfn = ts_sort_date

            // Currency, like $10.34
            else if (itm.match(/^[£Û$]/))
            sortfn = ts_sort_currency // AG Added Euro

            // 234.123
            else if (itm.match(/^[\d\.]+$/))
            sortfn = ts_sort_numeric

            // Everything else.
            else
            sortfn = ts_sort_caseinsensitive

            SORT_COLUMN_INDEX = column;

            var newRows = new Array();
            var bottomRows = new Array();
            for (var j=nonHeaderIndex; j<table.rows.length; j++) {
                if (table.rows[j].className.indexOf('sortbottom') == -1)
                newRows.push(table.rows[j])
                else
                bottomRows.push(table.rows[j]);
            }

            newRows.sort(sortfn);

            if (span.getAttribute("sortdir") == 'down') {
                ARROW = '▲';
                newRows.reverse();
                span.setAttribute('sortdir', 'up');
                } else {
                    ARROW = '▼';
                    span.setAttribute('sortdir', 'down');
                }
                // Assign updated classes to the rows when the sort's finished
                for (i=0;i<newRows.length;i++) { 
                    if (newRows[i].className.indexOf('sortbottom') == -1) {
                        if (i%2 == 1) newRows[i].className='evenrow';
                        else newRows[i].className='oddrow';
                    }
                }
                // We appendChild rows that already exist to the tbody, so it moves them rather 
                // than creating new ones don't do sortbottom rows
                for (var i=0; i<newRows.length; i++) {
                    table.tBodies[0].appendChild(newRows[i]);
                    // We've sorted the list already, so we need to make
                    // sure any cells with changable values are expired.
                    ts_expireCache(newRows[i].cells[SORT_COLUMN_INDEX]);
                }

                // do sortbottom rows only
                for (i = 0; i < bottomRows.length; i++) {
                    table.tBodies[0].appendChild(bottomRows[i]);
                }

                // Delete any other arrows there may be showing
                var allspans = document.getElementsByTagName("span");
                for (var ci = 0; ci < allspans.length; ci++) {
                    if (allspans[ci].className == 'sortarrow') {
                        if (getParent(allspans[ci], "table") == getParent(lnk, "table")) {  // in the same table as us?
                            allspans[ci].innerHTML = '&nbsp;&nbsp;';
                        }
                    }
                }
                span.innerHTML = ARROW;
                event.preventDefault();
            }

function getParent(el, pTagName) {
    if (el == null)
    return null;
    else if (el.nodeType == 1 && el.tagName.toLowerCase() == pTagName.toLowerCase())    // Gecko bug, supposed to be uppercase
    return el;
    else
    return getParent(el.parentNode, pTagName);
}

function ts_sort_date(a, b) {
    // y2k notes: Two digit years less than 50 are treated as 20XX, 
    // greater than 50 are treated as 19XX.

    aa = ts_getInnerText(a.cells[SORT_COLUMN_INDEX]);
    bb = ts_getInnerText(b.cells[SORT_COLUMN_INDEX]);

    aaBits = aa.split(/\/|-/);
    for (i = 0; i < monthName.length; i++) { // AG Convert Alpha month to two digit month
        if (monthName[i].toLowerCase() == aaBits[1].toLowerCase()) {
            aa = aaBits[0] + '/' + (i < 9 ? '0' : '') + (i + 1) + '/' + aaBits[2];
            break;
        }
    }

    if (aa.length == 10) {
        dt1 = aa.substr(6, 4) + aa.substr(3, 2) + aa.substr(0, 2);
        } else {
            yr = aa.substr(6, 2);
            dt1 = (parseInt(yr) < 50 ? '20' : '19') + yr + aa.substr(3, 2) + aa.substr(0, 2);
        }

        bbBits = bb.split(/\/|-/);
        for (i = 0; i < monthName.length; i++) { // AG: Convert Alpha month to two digit month
            if (monthName[i].toLowerCase() == bbBits[1].toLowerCase()) {
                bb = bbBits[0] + '/' + (i < 9 ? '0' : '') + (i + 1) + '/' + bbBits[2];
                break;
            }
        }

        if (bb.length == 10) {
            dt2 = bb.substr(6, 4) + bb.substr(3, 2) + bb.substr(0, 2);
            } else {
                yr = bb.substr(6, 2);
                dt2 = (parseInt(yr) < 50 ? '20' : '19') + yr + bb.substr(3, 2) + bb.substr(0, 2);
            }

            if (dt1 == dt2)
            return 0;
            if (dt1 < dt2)
            return -1;
            return 1;
        }

        function ts_sort_currency(a, b) {
            aa = ts_getInnerText(a.cells[SORT_COLUMN_INDEX]).replace(/[^0-9.]/g, '');
            bb = ts_getInnerText(b.cells[SORT_COLUMN_INDEX]).replace(/[^0-9.]/g, '');
            return parseFloat(aa) - parseFloat(bb);
        }

        function ts_sort_numeric(a, b) {
            aa = parseFloat(ts_getInnerText(a.cells[SORT_COLUMN_INDEX]));
            if (isNaN(aa))
            aa = 0;
            bb = parseFloat(ts_getInnerText(b.cells[SORT_COLUMN_INDEX]));
            if (isNaN(bb))
            bb = 0;
            return aa - bb;
        }

        function ts_sort_caseinsensitive(a, b) {
            aa = ts_getInnerText(a.cells[SORT_COLUMN_INDEX]).toLowerCase();
            bb = ts_getInnerText(b.cells[SORT_COLUMN_INDEX]).toLowerCase();
            if (aa == bb)
            return 0;
            if (aa < bb)
            return -1;
            return 1;
        }

        function ts_sort_default(a, b) {
            aa = ts_getInnerText(a.cells[SORT_COLUMN_INDEX]);
            bb = ts_getInnerText(b.cells[SORT_COLUMN_INDEX]);
            if (aa == bb)
            return 0;
            if (aa < bb)
            return -1;
            return 1;
        }

 function addEvent(elm, evType, fn, useCapture) {
// addEvent cross-browser event handling for IE5+,  NS6 and Mozilla
// By Scott Andrew
    if (elm.addEventListener) {
        elm.addEventListener(evType, fn, useCapture);
        return true;
    } else if (elm.attachEvent) {
        var r = elm.attachEvent("on" + evType, fn);
        return r;
    } else {
        alert("Handler could not be added");
    }
}

                                // Suggested by MT Jordan:
                                // Posted by liorean at http://codingforums.com
                                // IE5 triggers runtime error w/o push function
                                // Shortened by mcramer@pbs.org

                                if (typeof Array.prototype.push == 'undefined') {
                                    Array.prototype.push = function() {
                                        var b = this.length;
                                        for(var i=0; i<arguments.length; i++ ) {
                                            this[b + i] = arguments[i];
                                        }
                                        return this.length
                                    }
                                }

