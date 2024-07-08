
function progressHandlingFunction(e){
    if(e.lengthComputable){
        $('progress').attr({value:e.loaded,max:e.total});
    }
}

$(function() {

	var result_set = [];


	
	
	

	
	function mapFilterOptions(){
		var submission = {};
		submission['Pay_On_Time'] = true;
		submission['Guaranteed'] = true;
		submission['Direct_Debit'] = true;
		submission['Dual_Fuel'] = true;
		submission['Bonus'] = true;
		submission['Prepay'] = true;
		submission['No_Term'] = true;
		submission['12_Months'] = true;
		submission['24_Months'] = true;
		submission['36_Months'] = true;
		submission['BPAY'] = true;
		submission['Credit_Card'] = true;
		submission['EasiPay'] = true;
		submission['Online'] = true;
		submission['Centrepay'] = true;
		submission['Cash'] = true;
		submission['Cheque'] = true;
		submission['POST_billpay'] = true;
		submission['Pay_By_Phone'] = true;
		submission['AMEX'] = true;
	
		return 	submission;
	
	}
	
	
	function mapCompareData(_data){
	
		var aboutyou_var = {};
		var product_option_var = {};
		var personal_information_var = {};
		var compare_json = {};
	
		aboutyou_var['postcode'] = (typeof _data.postcode == 'object')? "":_data.postcode;
		aboutyou_var['state'] = (typeof _data.state == 'object')? "":_data.state;
		aboutyou_var['suburb'] = (typeof _data.state == 'object')? "":_data.suburb;
		aboutyou_var['plan_type'] = (typeof _data.plan_type == 'object')? "":_data.plan_type;
		aboutyou_var['customer_type'] = (typeof _data.customer_type == 'object')? "":_data.customer_type;
		aboutyou_var['looking_for'] = (typeof _data.looking_for == 'object')? "":_data.looking_for;
		aboutyou_var['move_in_date'] = (typeof _data.move_in_date == 'object')? "":_data.move_in_date;
		aboutyou_var['solar_facilitation'] = (typeof _data.solar_facilitation == 'object')? "":_data.solar_facilitation;
		aboutyou_var['energy_dependency'] = (typeof _data.energy_dependency == 'object')? "":_data.energy_dependency;
		aboutyou_var['elec_recent_bill'] = (typeof _data.elec_recent_bill == 'object')? "":_data.elec_recent_bill;
		aboutyou_var['gas_recent_bill'] = (typeof _data.gas_recent_bill == 'object')? "":_data.gas_recent_bill;
		aboutyou_var['elec_supplier'] = (typeof _data.elec_supplier == 'object')? "":_data.elec_supplier;
		aboutyou_var['elec_spend'] = (typeof _data.elec_spend == 'object')? "":_data.elec_spend;
		aboutyou_var['elec_season'] = (typeof _data.elec_season == 'object')? "":_data.elec_season;
		aboutyou_var['elec_meter_type'] = (typeof _data.elec_meter_type == 'object')? "":_data.elec_meter_type;
		aboutyou_var['elec_billing_days'] = (typeof _data.elec_billing_days == 'object')? "":_data.elec_billing_days;
		aboutyou_var['singlerate_peak'] = (typeof _data.singlerate_peak == 'object')? "":_data.singlerate_peak;
		aboutyou_var['gas_supplier'] = (typeof _data.gas_supplier == 'object')? "":_data.gas_supplier;
		aboutyou_var['gas_billing_days'] = (typeof _data.gas_billing_days == 'object')? "":_data.gas_billing_days;
		aboutyou_var['gas_billing_start'] = (typeof _data.gas_billing_start == 'object')? "":_data.gas_billing_start;
		aboutyou_var['gas_spend'] = (typeof _data.gas_spend == 'object')? "":_data.gas_spend;
		aboutyou_var['gas_peak'] = (typeof _data.gas_peak == 'object')? "":_data.gas_peak;
		aboutyou_var['gas_off_peak'] = (typeof _data.gas_off_peak == 'object')? "":_data.gas_off_peak;
		aboutyou_var['elec_usage_level'] = (typeof _data.elec_usage_level == 'object')? "":_data.elec_usage_level;
		aboutyou_var['gas_usage_level'] = (typeof _data.gas_usage_level == 'object')? "":_data.gas_usage_level;
		aboutyou_var['terms_conditions'] = (typeof _data.terms_conditions == 'object')? "":_data.terms_conditions;
		
		compare_json.aboutyou_var = aboutyou_var;
		
		product_option_var['pay_on_time_discount'] = (typeof _data.pay_on_time_discount == 'object')? "":_data.pay_on_time_discount; 
		product_option_var['direct_debit_discount'] = (typeof _data.direct_debit_discount == 'object')? "":_data.direct_debit_discount;
		product_option_var['no_contract_plan'] = (typeof _data.no_contract_plan == 'object')? "":_data.no_contract_plan; 
		product_option_var['rate_freeze'] = (typeof _data.rate_freeze == 'object')? "":_data.rate_freeze; 
		product_option_var['bill_smoothing'] = (typeof _data.bill_smoothing == 'object')? "":_data.bill_smoothing; 
		product_option_var['online_account_management'] = (typeof _data.online_account_management == 'object')? "":_data.online_account_management;
		product_option_var['energy_monitoring_tools'] = (typeof _data.energy_monitoring_tools == 'object')? "":_data.energy_monitoring_tools; 
		product_option_var['membership_reward_programs'] = (typeof _data.membership_reward_programs == 'object')? "":_data.membership_reward_programs; 
		product_option_var['renewable_energy'] = (typeof _data.renewable_energy == 'object')? "":_data.renewable_energy; 
		product_option_var['bonus_discount'] = (typeof _data.bonus_discount == 'object')? "":_data.bonus_discount; 
		product_option_var['prepay_discount'] = (typeof _data.prepay_discount == 'object')? "":_data.prepay_discount; 
		product_option_var['dual_fuel_discount'] = 'NO'; 
		product_option_var['sort_by'] = ''; 
		
		compare_json.product_option_var = product_option_var;
		
		personal_information_var['name'] = (typeof _data.name == 'object')? "":_data.name;
		personal_information_var['mobile'] = (typeof _data.mobile == 'object')? "":_data.mobile;
		personal_information_var['phone'] = (typeof _data.phone == 'object')? "":_data.phone;
		personal_information_var['email'] = (typeof _data.email == 'object')? "":_data.email;
		
		compare_json.personal_information_var = personal_information_var;
		
		console.log(compare_json);
		return compare_json;
	}
	
	function mapSignUpData(_data){

		var form_json = {};
		form_json.planOptions = {};
		form_json.concessions = {};
		form_json.submission = {};

		form_json.submission['f_60'] = (typeof _data.f_60 == 'object')? "":_data.f_60;
		form_json.submission['f_61'] = (typeof _data.f_61 == 'object')? "":_data.f_61;
		form_json.submission['f_62'] = (typeof _data.f_62 == 'object')? "":_data.f_62;
		form_json.submission['f_97'] = (typeof _data.f_97 == 'object')? "":_data.f_97;
		form_json.submission['f_63'] = (typeof _data.f_63 == 'object')? "":_data.f_63;
		form_json.submission['f_64'] = (typeof _data.f_64 == 'object')? "":_data.f_64;
		form_json.submission['f_65'] = (typeof _data.f_65 == 'object')? "":_data.f_65;
		form_json.submission['f_66'] = (typeof _data.f_66 == 'object')? "":_data.f_66;
		form_json.concessions['f_100'] = (typeof _data.f_100 == 'object')? "":_data.f_100;
	
		form_json.submission['f_43'] = (typeof _data.f_43 == 'object')? "":_data.f_43;
		form_json.submission['f_44'] = (typeof _data.f_44 == 'object')? "":_data.f_44;
		form_json.submission['f_45'] = (typeof _data.f_45 == 'object')? "":_data.f_45;
		form_json.submission['f_46'] = (typeof _data.f_46 == 'object')? "":_data.f_46;
		form_json.submission['f_47'] = (typeof _data.f_47 == 'object')? "":_data.f_47;
		form_json.submission['f_48'] = (typeof _data.f_48 == 'object')? "":_data.f_48;
		form_json.submission['f_49'] = (typeof _data.f_49 == 'object')? "":_data.f_49;
		form_json.submission['f_56'] = (typeof _data.f_56 == 'object')? "":_data.f_56;
		form_json.submission['f_57'] = (typeof _data.f_57 == 'object')? "":_data.f_57;
		form_json.submission['f_58'] = (typeof _data.f_58 == 'object')? "":_data.f_58;
		form_json.submission['f_59'] = (typeof _data.f_59 == 'object')? "":_data.f_59;
		form_json.submission['f_67'] = (typeof _data.f_67 == 'object')? "":_data.f_67;
		form_json.submission['f_68'] = (typeof _data.f_68 == 'object')? "":_data.f_68;
		form_json.submission['f_69'] = (typeof _data.f_69 == 'object')? "":_data.f_69;
		form_json.submission['f_70'] = (typeof _data.f_70 == 'object')? "":_data.f_70;
		form_json.submission['f_71'] = (typeof _data.f_71 == 'object')? "":_data.f_71;
		form_json.submission['f_72'] = (typeof _data.f_72 == 'object')? "":_data.f_72;
		form_json.submission['f_73'] = (typeof _data.f_73 == 'object')? "":_data.f_73;
		form_json.submission['f_74'] = (typeof _data.f_74 == 'object')? "":_data.f_74;
		form_json.submission['f_75'] = (typeof _data.f_75 == 'object')? "":_data.f_75;
		form_json.submission['f_76'] = (typeof _data.f_76 == 'object')? "":_data.f_76;
		form_json.submission['f_94'] = (typeof _data.f_94 == 'object')? "":_data.f_94;
		form_json.submission['f_95'] = (typeof _data.f_95 == 'object')? "":_data.f_95;
		form_json.submission['f_96'] = (typeof _data.f_96 == 'object')? "":_data.f_96;
		form_json.submission['f_98'] = (typeof _data.f_98 == 'object')? "":_data.f_98;
		form_json.submission['f_77'] = (typeof _data.f_77 == 'object')? "":_data.f_77;
		form_json.submission['f_78'] = (typeof _data.f_78 == 'object')? "":_data.f_78;
		form_json.submission['f_82'] = (typeof _data.f_82 == 'object')? "":_data.f_82;
		form_json.submission['f_83'] = (typeof _data.f_83 == 'object')? "":_data.f_83;
		form_json.submission['f_84'] = (typeof _data.f_84 == 'object')? "":_data.f_84;
		form_json.submission['f_85'] = (typeof _data.f_85 == 'object')? "":_data.f_85;
		form_json.submission['f_86'] = (typeof _data.f_86 == 'object')? "":_data.f_86;
		form_json.submission['f_87'] = (typeof _data.f_87 == 'object')? "":_data.f_87;
		form_json.submission['f_99'] = (typeof _data.f_99 == 'object')? "":_data.f_99;
		form_json.submission['f_100'] = (typeof _data.f_100 == 'object')? "":_data.f_100;
		
		form_json.planOptions['p_01'] = (typeof _data.p_01 == 'object')? "":_data.p_01;
		form_json.planOptions['p_02'] = (typeof _data.p_02 == 'object')? "":_data.p_02;
		form_json.planOptions['p_03'] = (typeof _data.p_03 == 'object')? "":_data.p_03;
		form_json.planOptions['p_04'] = (typeof _data.p_04 == 'object')? "":_data.p_04;
		form_json.planOptions['p_05'] = (typeof _data.p_05 == 'object')? "":_data.p_05;
		form_json.planOptions['p_06'] = (typeof _data.p_06 == 'object')? "":_data.p_06;
		form_json.planOptions['p_07'] = (typeof _data.p_07 == 'object')? "":_data.p_07;
		form_json.planOptions['p_08'] = (typeof _data.p_08 == 'object')? "":_data.p_08;
		form_json.planOptions['p_09'] = (typeof _data.p_09 == 'object')? "":_data.p_09;
		form_json.planOptions['p_10'] = (typeof _data.p_10 == 'object')? "":_data.p_10;
		form_json.planOptions['p_11'] = (typeof _data.p_11 == 'object')? "":_data.p_11;
		form_json.planOptions['p_13'] = (typeof _data.p_11 == 'object')? "":_data.p_13;
		return form_json;
	
	}
	
	
	function executeAvailabilityTestCase(submitting_data, index){
		var aboutyou = $.post( "/about-you", submitting_data.aboutyou_var, function(msg) {
			console.log("about you::" + index);
			var product_options = $.post( "/product-options", submitting_data.product_option_var, function(msg) {
				console.log("product-options::" + index);
				var personal_information = $.post( "/personal-information", submitting_data.personal_information_var, function(msg) {
					console.log("personal-information::" + index);
					//console.log(msg);
					var QA_data = {};
					QA_data.type = "compare";
					QA_data.compare = {};
					QA_data.compare.qadata = {};
					QA_data.compare.qadata.aboutyou = submitting_data.aboutyou_var;
					QA_data.compare.qadata.productoption = submitting_data.product_option_var;
					QA_data.compare.qadata.personalinformation = submitting_data.personal_information_var;
					QA_data.compare.outcome = msg;
					QA_data.compare.qaconfig = qa_config;
					var jqxhr = $.post( "/qualityassurance/QA_Submit", JSON.stringify(QA_data), function(msg) {
						var obj = JSON.parse(msg.RESULT);
						result_set.push(obj);
						
						index++;
						if (index < total_num_of_test){
							
							var submitting_data = mapCompareData(g.row[index]);
							executeAvailabilityTestCase(submitting_data, index);
												
						} else {
							var form_json = {};
							form_json.results = result_set;
							window.location.replace("/QA_Assessment?results=" + JSON.stringify(form_json));									
						}
					})
				})
			})			
		})						
	}
	
	function executeSignupTestCase(submitting_data, form_json, submission, commandName, index){
			var aboutyou = $.post( "/about-you", submitting_data.aboutyou_var, function(msg_x) {
				var product_options = $.post( "/product-options", submitting_data.product_option_var, function(msg_x) {
			
					var personal_information = $.post( "/personal-information", submitting_data.personal_information_var, function(msg_x) {

						$.post( "/tools/routeOnlineSignUpProcess", submission, function(msg) {
							if (msg.status == "successful"){
						
								form_json['submission_id'] = msg.submission_key;
								$.get("/submissions/submissions/"+ msg.submission_key, "", function(response) {

								$.post( "/submissions/finalise", JSON.stringify(form_json), function(msg) {
									var lead_type_var = msg.savedIDs.lead_type;
									var snapshot = {};
									snapshot.comapre_image_id = msg.compare_snapshot_id;
									snapshot.signup_image_id = msg.savedIDs.submission;
									snapshot.signup_concession_image_id = msg.savedIDs.concession;
									snapshot.signup_planOptions_image_id = msg.savedIDs.planOptions;
									snapshot.signup_tac_image_id = msg.savedIDs.tac;

									var QA_data = {};
									QA_data.type = commandName;
									QA_data.compare = {};
									QA_data.compare.qadata = {};
									QA_data.compare.qadata.aboutyou = submitting_data.aboutyou_var;
									QA_data.compare.qadata.productoption = submitting_data.product_option_var;
									QA_data.compare.qadata.personalinformation = submitting_data.personal_information_var;
									QA_data.compare.qadata.submission = form_json.submission;
									QA_data.compare.qadata.planOptions = form_json.planOptions;
									QA_data.compare.qadata.concessions = form_json.concessions;
									QA_data.compare.snapshot = snapshot;

									var json_string = JSON.stringify(msg.outcomes.submitted);
									json_string = json_string.replace(/&/g, "and")
									//console.log(json_string);

									var submitObj = JSON.parse(json_string);
									//console.log(submitObj);

									QA_data.compare.outcome = submitObj;
									QA_data.compare.qaconfig = qa_config;
									var jqxhr = $.post( "/qualityassurance/QA_Submit", JSON.stringify(QA_data), function(msg) {
										var obj = JSON.parse(msg.RESULT);
										result_set.push(obj);

										index++;
										if (index >= total_num_of_test){

											var form_json = {};

											form_json.results = result_set;
											form_json.lead_type = lead_type_var;

			
											if (commandName == 'velocify'){
												window.location.replace("/QA_Assess_Velocify_Post?results=" + JSON.stringify(form_json));
											}
											if (commandName == 'signup'){
												window.location.replace("/QA_Assess_Velocify_Request?results=" + JSON.stringify(form_json));
											}
											if (commandName == 'snapshot'){
												window.location.replace("/QA_Assess_Snapshot?results=" + JSON.stringify(form_json));
											}

										} else {



											var plan_type_id_var = parseInt(g.row[index].plan_type_id);

											var submitting_data = mapCompareData(g.row[index]);
											var form_json = mapSignUpData(g.row[index]);
											form_json['qa_mode'] = true;

											var submission = mapFilterOptions();
											submission['planid'] = plan_type_id_var;

											executeSignupTestCase(submitting_data, form_json, submission, commandName, index);
										}
									})
								})



								});


							}
						})
					})					
				})			
			})
	}
	
	
	var total_num_of_test = 0;
	
	$('.ajaxFileUpload_test').click(function(){
		var formData = new FormData($('form')[0]);
		$.ajax({
			url: 'upload.php',  //Server script to process data
			type: 'POST',
			xhr: function() {  // Custom XMLHttpRequest
				var myXhr = $.ajaxSettings.xhr();
				if(myXhr.upload){ // Check if upload property exists
					//myXhr.upload.addEventListener('progress',progressHandlingFunction, false); // For handling the progress of the upload
				}
				return myXhr;
			},
			//Ajax events
			beforeSend: function(){},
			success: function(){console.log("AA")},
			error: errorHandler,
			// Form data
			data: formData,
			//Options to tell jQuery not to process data or worry about content-type.
			cache: false,
			contentType: false,
			processData: false
		});
	});

	//test for the service availability
	$('.loadVar').click(function(){
	
		var count = g.row.length;

        $.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        } }); 		
        
        total_num_of_test = g.row.length;
        var index = total_num_of_test - g.row.length;

		var submitting_data = mapCompareData(g.row[index]);
		executeAvailabilityTestCase(submitting_data, index);
				        
        
	});
	//
	
	$('.velocifyPostVar').click(function(){
		var commandName = $(this).attr("name");
		
		var count = g.row.length;
		
        $.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        } }); 		
		
		total_num_of_test = g.row.length;
		var index = total_num_of_test - g.row.length;
		
		var plan_type_id_var = parseInt(g.row[index].plan_type_id);

		var submitting_data = mapCompareData(g.row[index]);
		var form_json = mapSignUpData(g.row[index]);
		form_json['qa_mode'] = true;

		var submission = mapFilterOptions();
		submission['planid'] = plan_type_id_var;

		executeSignupTestCase(submitting_data, form_json, submission, commandName, index);										
		 

	});
		
	
	
	
	//shared between Velocify Request String and Snapshot
	$('.loadSignUpVar').click(function(){
		var commandName = $(this).attr("name");
		
		var count = g.row.length;
		
        $.blockUI({ css: { 
            border: 'none', 
            padding: '15px', 
            backgroundColor: '#000', 
            '-webkit-border-radius': '10px', 
            '-moz-border-radius': '10px', 
            opacity: .5, 
            color: '#fff' 
        } }); 		
		
		total_num_of_test = g.row.length;
		var index = total_num_of_test - g.row.length;
		
		var plan_type_id_var = parseInt(g.row[index].plan_type_id);

		var submitting_data = mapCompareData(g.row[index]);
		var form_json = mapSignUpData(g.row[index]);
		form_json['qa_mode'] = true;

		var submission = mapFilterOptions();
		submission['planid'] = plan_type_id_var;

		executeSignupTestCase(submitting_data, form_json, submission, commandName, index);										
		 

	});
	
	$(".applyOnline").click(function(){
		var submission = {};
		submission['Pay_On_Time'] = ($("#Pay_On_Time").attr('checked') == 'checked')? true:false;
		submission['Guaranteed'] = ($("#Guaranteed").attr('checked') == 'checked')? true:false;
		submission['Direct_Debit'] = ($("#Direct_Debit").attr('checked') == 'checked')? true:false;
		submission['Dual_Fuel'] = ($("#Dual_Fuel").attr('checked') == 'checked')? true:false;
		submission['Bonus'] = ($("#Bonus").attr('checked') == 'checked')? true:false;
		submission['Prepay'] = ($("#Prepay").attr('checked') == 'checked')? true:false;
		submission['No_Term'] = ($("#No_Term").attr('checked') == 'checked')? true:false;
		submission['12_Months'] = ($("#12_Months").attr('checked') == 'checked')? true:false;
		submission['24_Months'] = ($("#24_Months").attr('checked') == 'checked')? true:false;
		submission['36_Months'] = ($("#36_Months").attr('checked') == 'checked')? true:false;
		submission['BPAY'] = ($("#BPAY").attr('checked') == 'checked')? true:false;
		submission['Credit_Card'] = ($("#Credit_Card").attr('checked') == 'checked')? true:false;
		submission['EasiPay'] = ($("#EasiPay").attr('checked') == 'checked')? true:false;
		submission['Online'] = ($("#Online").attr('checked') == 'checked')? true:false;
		submission['Centrepay'] = ($("#Centrepay").attr('checked') == 'checked')? true:false;
		submission['Cash'] = ($("#Cash").attr('checked') == 'checked')? true:false;
		submission['Cheque'] = ($("#Cheque").attr('checked') == 'checked')? true:false;
		submission['POST_billpay'] = ($("#POST_billpay").attr('checked') == 'checked')? true:false;
		submission['Pay_By_Phone'] = ($("#Pay_By_Phone").attr('checked') == 'checked')? true:false;
		submission['AMEX'] = ($("#AMEX").attr('checked') == 'checked')? true:false;
		submission['planid'] = $(this).attr('id');
			
	})
});

		



