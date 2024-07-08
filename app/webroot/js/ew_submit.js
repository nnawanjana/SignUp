$(function() {
	$.tacnodes = [];
	$.currentTACsByRetailer = [];

	$("#SubmitBtn").click(function(){

		if (!($("input[name*='f_99']").prop("checked"))){
			return;
		}

		/***************
		** Okkey Sumiyoshi
		** 26/01/2016
		** To prevent users from clicking submit button
		** Temporarily being placed
		** This will need to be replaced with suggestions and design that Joel would provide
		** To be assigned to Sean		***/

        $.blockUI({ css: {
            border: 'none',
            padding: '15px',
            backgroundColor: '#000',
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            opacity: .5,
            color: '#fff'
        } });

		var form_json = {};
		form_json.planOptions = {};
		form_json.concessions = {};
		form_json.submission = {};
		form_json.qa_mode = false;

		for (var element in elementList){
			form_json.submission[elementList[element]] = $("input[name='" + elementList[element] + "']").val();
		}

		for (var selectElement in selectElementList){
			form_json.submission[selectElementList[selectElement]] = $("select[name='" + selectElementList[selectElement] + "']").val();
		}

		for (var radioElement in radioElementList.genericUI){
			var selected = $("input[type='radio'][name='" + radioElementList.genericUI[radioElement] + "']:checked");
			if (selected.length > 0) {
				form_json.submission[radioElementList.genericUI[radioElement]] = selected.val();
			}
		}

		for (var concession in concessionElementList){
			form_json.concessions[concessionElementList[concession]] = $("input[name*='" + concessionElementList[concession] + "']:checked").val();
		}


		/**
		**	Okkey Sumiyoshi
		**	02/03/2016
		**	the inclusion of Pre concession text in the snapshot
		*****/
		if (ui_instructions.preconcession_show_hide != ""){
			for (var concession in ui_instructions.preconcession_show_hide){
				if ($("input[name*='" + concession + "']").length){
					form_json.concessions[concession] = $("input[name*='" + concession + "']:checked").val();
				}
			}
		}


		for (var radioElement in radioElementList.concessions){
			var selected = $("input[type='radio'][name='" + radioElementList.concessions[radioElement] + "']:checked");
			if (selected.length > 0) {
				form_json.concessions[radioElementList.concessions[radioElement]] = selected.val();
			}
		}


		for (var planOption in planOptionsElementList){
			form_json.planOptions[planOptionsElementList[planOption]] = $("input[name='" + planOptionsElementList[planOption] + "']").val();
		}

		for (var planOptionSelect in planOptionSelectElementList){
			form_json.planOptions[planOptionSelectElementList[planOptionSelect]] = $("select[name='" + planOptionSelectElementList[planOptionSelect] + "']").val();
		}

		for (var radioElement in radioElementList.planOptions){
			var selected = $("input[type='radio'][name='" + radioElementList.planOptions[radioElement] + "']:checked");
			if (selected.length > 0) {
				form_json.planOptions[radioElementList.planOptions[radioElement]] = selected.val();
			}
		}

		var jqxhr = $.post( "/finalise", JSON.stringify(form_json), function(msg) {
            if (msg.outcomes == 'unsuccessful'){
				alert ("An unexpected error has occurred. It seems you may have already signed up. Please call us on 1300 359 779 and quote your Lead ID: " + msg.message  + ". We apologise for the inconvenience.");

				window.location.replace("https://"+compare_website_domain_name+"/about-you");

			} else {
				window.location.replace("/signup-complete");
			}
		})
		.fail(function() {
            window.location.replace("https://"+compare_website_domain_name+"/about-you");
		});
	})
});

$(document).ready(function() {
    var util = {
        utilArray:[],
        removeDuplicates:function(_collection){
        	var variables = [];
        	function evalateVariables(_evalSubject){
        		var flg = 0;

        		for (var i = 0; i < variables; i++){
        			if (variables[i] == _evalSubject){
        				flg = 1;
        			}
        		}
        		if (flg == 0){
        			variables.push(_evalSubject);
        		}
        		flg = 0;
        	}

        	for (var i = 0; i < _collection.length; i++){
        		evalateVariables(_collection[i].textContent);
        	}
        	return variables;

        },
        createRandomString:function( length ) {
        	var chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        	var output = '';

        	function rand( number ) {
        		if ( undefined === number ) {number = 100;}
        		return parseInt( Math.random() * number );
        	};

        	if ( undefined === length ) {length = 16;}

        	output += chars[ 10 + rand( chars.length - 10 ) ];
        	for ( var i = 1; i <= length - 1; i++ ) {
        		output += chars[ rand( chars.length ) ];
        	};
        	return output;
        }
    }
});
