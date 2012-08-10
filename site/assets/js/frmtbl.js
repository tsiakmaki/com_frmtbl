/**
 * 
 * using mootools - v1.12 is used in joomla 1.5.22 - for
 *  
 * (a) submit form (ajax request)
 * (b) edit form object (ajax request)
 * (c) delete table row (ajax request)
 * (d) validating form
 * 
 * @version		0.1 - Summer 2012
 *
 * @license		GNU General Public License
 * @author		m.tsiakmaki [at] gmail [dot] com
 */
window.addEvent("domready", function() { 
	
	
	// add autocomplete 	 
	var completer = new Autocompleter.Local('del_description', tokens, {
		'multiple': false,
		'minLength': 0,
		'overflow': true,
		'filterSubset': true,
		'zindex': 50000,
		'delay':0,
		'inheritWidth': false,
		'maxChoices': 1500
	});
	
	$('del_description').addEvent('onfocus', function() {
		completer.observer.onFired();
	});
	
	
	$$("a[id^=edit_deliverable_]").addEvent("click", function() {
		edit(this, 'editdeliverable', 'deliverable');
	});
	
	// committ the deliverable to the database (ajax), update the table with the deliverables
	$("deliverableformbutton").addEvent("click", function() {
		
		var t = $("deliverableform").getElements('input[name=title]');
		var tp = $('deliverabletitlediv').getElements('p');

		var d = $("deliverableform").getElements('input[name=description]');
		var dp = $('deliverabledescdiv').getElements('p');
		
		
		var isFormValid = true;
		if(t.getValue() == "") {
			tp.addClass('invalid');
			isFormValid = false;
		} else {
			tp.removeClass('invalid');
		}

		if(d.getValue() == "") {
			dp.addClass('invalid');
			isFormValid = false;
		} else {
			dp.removeClass('invalid');
		}

		if(!isFormValid) {
			return false;
		}
		
		new Ajax("index.php", {
  			data: $("deliverableform"), 
    		method: "post",
    		onComplete: function(response) {
    			
    			var resp = Json.evaluate(response);
    			
    			if(resp['error']) {
     				//@see FrmtblControllerDeliverable::savedeliverable
     				alert(resp['error'] + '\n' + resp['error_details']);
				}  else { 
					updateTable('deliverable', 'd', resp.deliverable_id, resp);
	    			// reset the form
	    			$('deliverableform').reset();
	    			// hidden variable remain, so reset the id (has value when form filled from edit)
					clearHiddenIds('deliverableform', 'deliverable_id');
				}
    		}
  		}).request();
	});

	
});



/**
 * Calls an ajax request that queries the database and updates the form values. 
 * 
 */
function edit(alinkElement, jtask, field_name) {
	var id = alinkElement.getProperty('rel');
	
	var postVars = '?option=com_frmtbl&format=raw&view=deliverable&controller=deliverable&task=' 
		+ jtask + '&' + field_name + '_id=' + id; 
	
	new Ajax('index.php' + postVars, 
			{	method: 'get',			
				onComplete: function(response) {
					var resp = Json.evaluate(response);
					
					if(resp['error'] != "") {
						// update the values in the form, after having taken values from database
						fillform(resp, field_name + 'form'); 
					} else {
						alert(resp['error'] + '\n' + resp['error_details']);
					}
				}
	}).request();
}

/**
 * Update the form given data
 * */
function fillform(data, formid) {
	var form = $(formid);
	// update form values
	for (var key in data) {
		var value = data[key];
		var elem = form.getElement("[name=" + key + "]");
		if(elem) {
			var type = elem.getProperty('type'); 
			var tag = elem.tagName.toLowerCase(); 
			if(tag == 'select') {
				// value is not the index! 
				// find the index of the returned value 
				var indx = 0; 
				var i = 0;
				elem.getChildren().each(function (ch) {
					if(ch.value == value) {
						indx = i;
					}
					i++;
				});
				elem.options[indx].selected = true; 
			} else if (tag == 'input') { 
				elem.value = value;
			}
		}
	}
}


function updateTable(object_name, tr_prefix, row_id , resp) {
	// the name of the table is the plural ('s') of the object name plus 'table'
	var table_name = object_name + "stable";
	var trElements = getTableRowForGivenObject(table_name, tr_prefix, row_id);
	
	// if length == 0, nothing found
	if (trElements.length == 1) {
		updateRowToTable(resp, trElements);
	} else {
		addRowToTable(object_name, table_name, tr_prefix, row_id, resp);
	}
}


function addRowToTable(object_name, table_name, tr_prefix, row_id, data) {
	var keys = new Array();
	var thElems = $(table_name).getElements("thead th").each(function (th) {
		var id = th.getAttribute('id');
		if(id != null) {
			keys.push(id); 
		}
	}); 	
	var tbody = $(table_name).getElement("tbody");
	
	var tr = new Element('tr', {
		id : 'tr_' + tr_prefix + "_" + row_id
	});
	
	
	// add data 
	var len=keys.length;
	for(var i=0; i<len; i++) {
		var key = keys[i];
		var value = data[key];
		
		if(key == 'cost') {
			value = formatCurrency(value);
		}
		
		// create tr element
		var td = new Element('td', {
			headers : key
		}).setText(value);
		
		if(key == 'cost') {
			var euro = document.createElement("span")
			euro.innerHTML = "&euro;";
			td.appendChild(euro);	
		}
		// 
		tr.appendChild(td);
	}

	// add the delete button
	var td_del = new Element('td');
	
	var link_del = new Element('a', {
		href : "#",
		events: {
	        click: function() {
	        	deleteObject(row_id, object_name, tr_prefix);
	        }
	    }
	}).setText("Delete");
	td_del.appendChild(link_del);
	tr.appendChild(td_del);
	
	// add edit button 
	var td_edit = document.createElement('td');
	var link_edit = new Element('a', {
	    href: '#',
	    id: 'edit_partner',
	    rel: row_id,
	    events: {
	        click: function() {
	        	edit(this, 'edit' + object_name, object_name);
	        }
	    }
	}).setText('Edit');
	td_edit.appendChild(link_edit);
	tr.appendChild(td_edit);
	
	// append new tr 
	tbody.appendChild(tr);
}


function updateRowToTable(row_data, trElements) {
	
	trElements[0].getChildren().each(function(td) {
		var headers = td.getAttribute('headers');
		if(headers != null) {
			if(headers =='cost') {
				//console.debug(headers);
				value = formatCurrency(row_data[headers]);
				var euro = document.createElement("span")
				euro.innerHTML = "&euro;";
				td.setHTML(value).appendChild(euro);	
			} else {
				td.setHTML(row_data[headers]);
			}
		}
	});

}


function deleteObject(object_id, object_name, object_prefix) {
	
	var msg = "Are you sure?";
	
	if (confirm(msg)) {
	
		var url = "index.php?option=com_frmtbl&controller=deliverable&task=delete" + object_name + "&format=raw&id=" + object_id;
		
		new Ajax(url, {
			method: "get",
			onComplete: function(response) {
				deleteRowFromTable(object_name  + "stable", "tr_" + object_prefix +"_" + object_id);
	    	}
		}).request();
	}
}



/*********** Common *************/

function confirmDelete(msg, delUrl) {
  if (confirm(msg)) {
    document.location = delUrl;
  }
}

function proposalIdIsValid(f) {
	return f.getElements('input[name=proposal_id]').getValue() > 0;
}

/** return the tr if table contains object, empty array[] otherwise */
function getTableRowForGivenObject(tablename, object_prefix, object_id) {
	return $$('#' + tablename + ' tr[id=tr_' + object_prefix + '_' + object_id + ']');
}

function deleteRowFromTable(table_id, row_id) {
	var tbl = document.getElementById(table_id);
	var tbody = tbl.getElementsByTagName("tbody")[0];
	for (var i = 0; i < tbody.rows.length; i++) {
		if(tbody.rows[i].getAttribute("id") == row_id) {
			tbl.deleteRow(i + 1);
		}
	}
}

function getSelectedText(form_id, selection_name) {
	
	var selected_opt_text = '';
	
	$(form_id).getElements('select[name='+selection_name+']').getElements('option').each(function(opts) {
		opts.each(function (opt) { 
			if(opt.selected) {
				selected_opt_text = opt.text;
			}
		}); 
	});

	return selected_opt_text;
}

/***
 * After reset, the hidden values remain. 
 * clearHiddenIds sets hidden id fields to empty str ""
 * @param formid
 */
function clearHiddenIds(formid, fieldname) {
	$(formid).getElement('input[name=' + fieldname + ']').value="";
}



function formatCurrency(num) {
	var n = num, 
		c = isNaN(c = Math.abs(c)) ? 2 : c, 
		d = d == undefined ? "," : d, 
		t = t == undefined ? "." : t, 
		s = n < 0 ? "-" : "", 
		i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", 
		j = (j = i.length) > 3 ? j % 3 : 0;
	return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
}
