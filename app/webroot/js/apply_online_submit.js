
$(function() {
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

/*
		var jqxhr =
*/
		var jqxhr = $.post( "/tools/pre_signup_text", submission, function(msg) {
			if (msg.status == true){
				//Display msg here
				$.confirm({
					text: msg.pre_text,
					confirm: function() {
						$.post( "/tools/routeOnlineSignUpProcess", submission, function(msg) {
							if (msg.status == "successful"){
								window.location.replace("/submissions/submissions/" + msg.submission_key);

							} else {
								alert ("Unexpected system error occurred.  Please call us for further processing your enquiry.  We apologise for this inconvenience");
							}
						})
					},
					cancel: function() {
						// nothing to do
					}
				});

			} else {
			//error handling here

			}
		})
	})
});
