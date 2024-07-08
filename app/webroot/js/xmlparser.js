

function loadXMLDoc(filename){
	var xhttp;
	if (window.XMLHttpRequest){
		xhttp = new XMLHttpRequest();
	}
	else{
		// code for IE5 and IE6
		xhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xhttp.open("GET",filename,false);
	xhttp.send();
	return xhttp.responseXML;
}

 function xmlToString(xmlData) { 

	var xmlString;
	//IE
	if (window.ActiveXObject){
		xmlString = xmlData.xml;
	}
	// code for Mozilla, Firefox, Opera, etc.
	else{
			var xs = new XMLSerializer();
		xmlString = xs.serializeToString(xmlData);
	}
	return xmlString;
}

function setRequiredFields(rows){
	for (var f = 0; f<rows.length; f++){
		var c = rows[f].getElementsByTagName("Cell");
		var d0 = c[0].getElementsByTagName("Data");
		var d1 = c[1].getElementsByTagName("Data");
		var d2 = c[2].getElementsByTagName("Data");
		var d3 = c[3].getElementsByTagName("Data");
		if (d3[0].textContent == "Required"){
			var fn = document.createElement("fieldName");
			var ft = document.createElement("fieldtype");
			var retailers = document.createElement("retailers");
			var required = document.createElement("required");
			required.setAttribute("id", f+1);
			fn.textContent = d0[0].textContent;
			ft.textContent = d1[0].textContent;
			retailers.textContent = (d2[0] != undefined)? d2[0].textContent:"all";

			required.appendChild(fn);
			required.appendChild(ft);
			required.appendChild(retailers);
			xmldoc.appendChild(required);
		}
	}
	r = xmldoc.getElementsByTagName("required");			
}


function setOptionalFields(rows){
	for (var f = 0; f<rows.length; f++){
		var c = rows[f].getElementsByTagName("Cell");
		var d0 = c[0].getElementsByTagName("Data");
		var d1 = c[1].getElementsByTagName("Data");
		var d2 = c[2].getElementsByTagName("Data");
		var d3 = c[3].getElementsByTagName("Data");
		var d4 = c[4].getElementsByTagName("Data");
		if ((d3[0].textContent == "Optional") && (d4[0].textContent == "Yes")){
			var fn = document.createElement("fieldName");
			var ft = document.createElement("fieldtype");
			var retailers = document.createElement("retailers");
			var required = document.createElement("optional");
			required.setAttribute("id", f+1);
			fn.textContent = d0[0].textContent;
			ft.textContent = d1[0].textContent;
			retailers.textContent = (d2[0] != undefined)? d2[0].textContent:"all";

			required.appendChild(fn);
			required.appendChild(ft);
			required.appendChild(retailers);
			xmldoc.appendChild(required);
		}
	}
	r = xmldoc.getElementsByTagName("optional");			
}		
