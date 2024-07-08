$(function() {
	$(window).scroll(function() {
    	if ($(document).scrollTop() > 0){
			$('#main-header').addClass('scroll-header');
		}else{
			$('#main-header').removeClass('scroll-header');
		}
	});
		
	$('#section_SecondaryContact').hide();
	$('#setction_ConcessionCard').hide();
	$('#section_PropertySecondayContact').hide();
	$('#section_SecondaryContact_OtherNumber').hide();

	$('#accordionWrapper').accordion({
		heightStyle: "content",
		beforeActivate: function( event, ui ) {

			var newIndex = $.accordionControler.getIndex($(ui.newHeader).attr("id"));
			var oldIndex = $.accordionControler.getIndex($(ui.oldHeader).attr("id"));

			if (newIndex > oldIndex){
				//forward move
				//Prevent the click event on h3 to open subsequent panels without completing the current form
				if (!$.accordionControler.veifyFormCompletion($(ui.oldHeader).attr("id"))){
					return false;
				}
			}

			if (oldIndex > newIndex){
				//backward move
				//allowing the event to open panels that have been already completed
				if (!$.accordionControler.veifyFormCompletion($(ui.newHeader).attr("id"))){
					return false;
				}
			}
		},
		create: function( event, ui ) {
		},
		activate: function( event, ui ) {
		},
	});

	$('select.selectpicker').change(function() {
		if ($(this).val() == '') {
			$(this).next().addClass('error').removeClass('valid');
		} else {
			$(this).next().addClass('valid').removeClass('error');
		}
	});

	 /***********
	 * 16/10/2015
	 * Okkey Sumiyoshi
	 * this is event is attached to "Continue" button to verify all fields contained in the section complete
	 * Upon verified completion, it will open the next section by triggering the accordion through name attribute via functions found in
	 * the accordion controller 'jq.AccordionController.js'
	 * Each Continue button is bootstrapped to this function through name attribute
	 * This arrangement is facilitated in order to enable revisions on previously opened sections that are in the close state
	 * This function may need to be redundant if the design allowed the previously completed section left open
	 */
	$(".triggerValidation").click(function(event){
        event.preventDefault();
		var flg = 0;
		function validateSelect(arg) {
			if ($(arg).val() == '' ) {
				$(arg).addClass('error').removeClass('valid');
				return false;
			}
			$(arg).addClass('valid').removeClass('error');
			return true;
		}
        var validation = {
            "PlanExplanation":{
            	"@class": "signup_plan",
            	"@rules":function(){
            		return true;
            	}
            },
            "AccountDetails": {
            	"@class": "signup_contact",
            	"@rules":function(){
            		var title = $("select[name*='f_43']").val();
            		var flg = 0;
            		if (title == ""){
            			$("div.selecttitle").addClass( "error" );
            			flg++;
            		}
            		if (flg > 0){
            			return false;
            		} else {
            			return true;
            		}
            	}
            },
            "PersonalDetails": {
            	"@class": "signup_personal",
            	"@rules":function(){
            		var flg = 0;
            		if ($("select[name*='f_56']").val() == ""){
                        if ($( "div.selectPersonalDetails_documentType" ).is(":visible")){
                            $("div.selectPersonalDetails_documentType").addClass( "error" );
                            flg++;
                        }

            		};
            		if ($("select[name*='f_59']").val() == ""){
                        if ($( "div.selectPersonalDetails_documentState" ).is(":visible")){
                            $("div.selectPersonalDetails_documentState").addClass( "error" );
                            flg++;
                        }

            		};
            		if ($("select[name*='f_60']").val() == ""){
            			$("div.selectPersonalDetails_documentCountry").addClass( "error" );
            			flg++;

            		};
					if ($("input[name*='f_97']:radio:checked").val()){
						if ($("input[name*='f_97']:radio:checked").val().toUpperCase() == "YES"){

							if (($("select[name*='f_61']").val() == "")&&($("div.selectPersonalDetails_concessionCardIssuer").is(":visible"))){
								$("div.selectPersonalDetails_concessionCardIssuer").addClass( "error" );
								flg++;
							};

							var f_100 = $("input[name*='f_100']").get(0);
							var node_x = $("input[name*='f_100']").parent().parent().parent().prev().get(0);
							if (f_100.getAttribute("type").toUpperCase() == "CHECKBOX"){
								if (!$("input[name*='f_100']").prop("checked")){
									$(node_x.getElementsByTagName('label')[0]).addClass("error_signupContract");
									flg++;
								} else {
									$(node_x.getElementsByTagName('label')[0]).removeClass("error_signupContract");
								}
							}

							if (f_100.getAttribute("type").toUpperCase() == "RADIO"){
								if ($("input[name*='f_100']:radio:checked").val()){
									if ($("input[name*='f_100']:radio:checked").val().toUpperCase() != "YES"){
										$(node_x.getElementsByTagName('label')[0]).addClass("error_signupContract");
										flg++;
									} else {
										$(node_x.getElementsByTagName('label')[0]).removeClass("error_signupContract");
									}
								}
							}

							if (($("select[name*='f_62']" ).val() == "")&&($("div.selectPersonalDetails_concessionCardType").is(":visible"))){
								$("div.selectPersonalDetails_concessionCardType").addClass( "error" );
								flg++;
							};

							/**
							**	Okkey Sumiyoshi
							**	02/03/2016
							**	Data validatino for pre-concession questions
							*****************/
							if (ui_instructions.preconcession_show_hide != ""){
								for (var i in ui_instructions.preconcession_show_hide){
									if (($("input[name*='" + i + "']").length > 0) && ($("input[name*='" + i + "']").is(":visible"))){
										var pre_concession_value = $("input[name*='" + i + "']:radio:checked").val();
										if (pre_concession_value==undefined){
											$('#concession_lbl_' + i).addClass( "error" );
											flg++;
										}
									}
								}
							}
						}
					}

            		if (flg > 0){
            			return false;
            		} else {
            			return true;
            		}

            	}
            },
            "PropertyDetails": {
            	"@class": "signup_supply",
            	"@rules":function(){
            		var flg = 0;
            		if (!$('.address_fields').is(':visible')) {
            			if ($("input[name*='your_address']").val() == "") {
            				$("input[name*='your_address']").addClass('error').removeClass('valid');
                            flg++;
                        }
                        else if ($("input[name*='f_71']").val() == "" || $("input[name*='f_72']").val() == "" || $("input[name*='f_73']").val() == "") {
            				$("input[name*='your_address']").addClass('error').removeClass('valid');
                            flg++;
                        }
            		} else {
            			$("input[name*='your_address']").addClass('valid').removeClass('error');
            		}
            		if ($( "select[name*='f_76']" ).val() == ""){
            			$("div.selectSupplyState").addClass( "error" );
            			flg++;
            		};
                    if ($( "select[name*='f_96']" ).val() == ""){
            			$("div.selectProperty_rentown").addClass( "error" );
            			flg++;
            		};
            		if ($("#poBoxCheckBx").is(":checked")){
            			if ($( "select[name*='f_87']" ).val() == ""){
            				$("div.selectSupplySecondaryState").addClass( "error" );
            				flg++;
            			};
            		}
            		if (flg > 0){
            			return false;
            		} else {
            			return true;
            		}
            	}
            },
            "PlanOptions": {
            	"@class": "signup_options",
            	"@rules":function(){
            		var flg = 0;
            		if ($("input[name*='p_04']:radio:checked").val()){
            			if ($("input[name*='p_04']:radio:checked").val().toUpperCase() == "YES"){
            				if ($("input[name*='p_06']").val() == ""){
            					$("input[name*='p_06']").addClass("error_signupContract");
            					flg++;
            				} else {
            					$("input[name*='p_06']").removeClass("error_signupContract");
            				}
            			}
            		}
					var required_radio = ['p_01','p_02','p_03','p_07','p_11','p_13', 'p_16', 'p_17', 'p_19', 'p_22'];
					var required_select = ['p_09'];
					var required_textInput = ['p_10'];


					for (var j = 0; j<required_radio.length; j++){
						if (($("input[name*='" + required_radio[j] + "']").length )&&($("input[name*='" + required_radio[j] + "']").is(":visible"))) {
							var node_x = $("input[name*='" + required_radio[j] + "']").parent().parent().parent().prev().get(0);
							if (!$("input[name*='" + required_radio[j] + "']").is(':checked')){
								$(node_x.getElementsByTagName('label')[0]).addClass("error_signupContract");
								flg++;
							} else {
								$(node_x.getElementsByTagName('label')[0]).removeClass("error_signupContract");
							}
						}

					}
					for (var j = 0; j<required_select.length; j++){
						if ($("select[name*='" + required_select[j] + "']").length ) {
							var node_x = $("select[name*='" + required_select[j] + "']").parent().parent().prev().get(0);
							if ($("select[name*='" + required_select[j] + "']").val() == ""){
								$(node_x.getElementsByTagName('label')[0]).addClass("error_signupContract");
								flg++;
							} else {
								$(node_x.getElementsByTagName('label')[0]).removeClass("error_signupContract");
							}
						}
					}
					for (var j = 0; j<required_textInput.length; j++){
						if ($("input[name*='" + required_textInput[j] + "']").length ) {
							var node_x = $("input[name*='" + required_textInput[j] + "']").parent().parent().prev().get(0);
							if ($("input[name*='" + required_textInput[j] + "']").val() == ""){
								$(node_x.getElementsByTagName('label')[0]).addClass("error_signupContract");
								flg++;
							} else {
								$(node_x.getElementsByTagName('label')[0]).removeClass("error_signupContract");
							}
						}
					}

					if (($("input[name*='p_19']").length )&&($("input[name*='p_19']").is(":visible"))) {
						if($("input[name*='p_19']:checked").val() == "No"){
							flg++;
						}
					}

            		if (flg > 0){
            			return false;
            		} else {
            			return true;
            		}
            	}
            },
            "TACDetails": {
            	"@class": "signup_tac",
            	"@rules":function(){
            		return true;
            	}
            }
        }

		/** Verify that the event is invoked from one of "Continue" button */
		if (validation[$(this).attr("name")] == undefined) return;

		/** */
		var f_47_value = $("input[name*='f_47']").val().replace(/[()]|\s|-/g, '');
		$("input[name*='f_47']").val(f_47_value);
		var f_48_value = $("input[name*='f_48']").val().replace(/[()]|\s|-/g, '');
		$("input[name*='f_48']").val(f_48_value);
		var f_55_value = $("input[name*='f_55']").val().replace(/[()]|\s|-/g, '');
		$("input[name*='f_55']").val(f_55_value);

		/** Verify all fields in the section meet the given first level validation criteria **/
		flg += ($("." + validation[$(this).attr("name")]["@class"]).valid())? 0:1;


		/** Verify all fields in the section meet the given second level validation criteria **/
		flg += (validation[$(this).attr("name")]["@rules"]())? 0:1;


		if (flg > 0){
			//an incomplete form as false returned from the validator

		} else {
			//complete the form in the panel.  Mark the form as complete
			$.accordionControler.updateFormCompletion($(this).attr("name"), true);

			//Alinta's secret question, which is delivered in select element is too large to fit in 4 column width
			//A solution implemented here is to change the class attribute value of div container

			if (parseInt($.accordionControler.getNextPanel($(this).attr("name"))) == 4){

				if (ui_instructions.retailer == 'Alinta Energy'){
					var layout_exception_select = $('.selectsecretquestion').parent().parent();
					var layout_exception_label = $(layout_exception_select).prev();
					$(layout_exception_label).attr('class','col-lg-12');
					$(layout_exception_select).attr('class','col-lg-12');
					//Alinta's secret answer
					$('.planoptionLbl_p_10').parent().parent().attr('class','col-lg-12');
					$("input[name*='p_10']").parent().parent().attr('class','col-lg-12');
				}
			}
			//then open the next panel
			$('#accordionWrapper').accordion({autoHeight:false,animated:false,active:$.accordionControler.getNextPanel($(this).attr("name"))});

			var wrap = $(this).parents('.ui-accordion-content');
			$('html,body').scrollTop(wrap.offset().top);
		}

		return false;
	});

	$.validator.addMethod(
        "verifyEmail",
        function(value, element) {
	        var status = false;
            $.ajax({
                type: "POST",
                url: "/tools/check_email",
                data: "email="+value,
                dataType:"json",
                async: false,
                success: function(data) {
                    status = data.status;
                }
            });
            return status;
        },
        "Email is invalid"
    );

    $.validator.addMethod(
        "verifyPhone",
        function(value, element) {
	        if (value) {
    	        var value = value.replace(/[()]|\s|-/g, '');
                if ((value.length != 8 && value.length != 10) || !(/^(?!.*(\d)\1{4})\d{8,10}$/.test(value))) {
                    return false;
                } else if (!(/^(02|03|07|08)\d{8}$/.test(value)) && !(/^(1300|1800)\d{6}$/.test(value))) {
				    return false;
			    }
	        }
	        return true;
        },
        "Phone number is invalid"
    );

    $.validator.addMethod(
        "verifyMobile",
        function(value, element) {
	        if (value) {
    	        var value = value.replace(/[()]|\s|-/g, '');
                if (value.length != 10 || !(/^(04)\d{8}$/.test(value))) {
                    return false;
                }
	        }
	        return true;
        },
        "Mobile number is invalid"
    );

    $.validator.addMethod(
        "verifyMedicare",
        function(value, element) {
        	if (value){

        		if($("select[name*='f_56']").val() == "MED"){
        			var res = value.match(/^(\d{8})(\d)/g);

					//replacing space
					var medicareNo = value.replace(/\s/g, '');
					var flg = 0;

					flg += (isNaN(parseInt(medicareNo)))? 1:0;
					flg += (parseInt(medicareNo) < 19999999999)? 1:0;
					flg += (parseInt(medicareNo) > 69999999999)? 1:0;

					if (flg == 0){
					return true;
					}
					else {
					return false;
					}

        		} else {
        			return true;
        		}

        	} else {
        		return true;
        	}
        },
        "Invalid medicare number"
    );

     $.validator.addMethod(
        "verifyNMI",
        function(value, element) {
        	if (value){
				var nmi = $("input[name*='f_94']").val().replace(/[()]|\s|-/g, '');
				var flg = 0;
				if (nmi.length > 0) {
					if (nmi.length != 11) {
						flg++;
					}
					else {
						var total = 0;
						for ( var i = 0; i < 10; i++ ) {
							var str = nmi[i].charCodeAt(0).toString();
							if (i%2 == 0) {
								for ( var j = 0; j < str.length; j++ ) {
									total += eval(str[j]);
								}
							}
							else {
								var str2 = (str*2).toString();
								for ( var k = 0; k < str2.length; k++ ) {
									total += eval(str2[k]);
								}
							}
						}
						total += eval(nmi[10]);
						if (Math.round(total / 10) * 10 != total) {
						    flg++;
						}
					}
				}
				if (flg > 0){
					return false;
				} else {
					return true;
				}
        	} else {
        		return true;
        	}

        },
        "Invalid NMI number"
    );

    $.validator.addMethod(
        "verifyDOB",
        function(value, element) {
            var nativeDateFormat = /^\d{4}-\d{2}-\d{2}$/; // mobile date field
            var datepickerDateFormat = /^\d{2}\/\d{2}\/\d{4}$/;
            if (nativeDateFormat.test(value)) {
                var dt = value.split("-");
                var birthYear = dt[0];
                var birthMonth = dt[1];
                var birthDay = dt[2];
            }
            else {
                var dt = value.split("/");
                var birthYear = dt[2];
                var birthMonth = dt[1];
                var birthDay = dt[0];
            }

			var today = new Date();
    		var age = today.getFullYear() - birthYear;
			var month = parseInt(today.getMonth()) + 1;
   			var m = month - birthMonth;
			if (m < 0 || ((m == 0) && (today.getDate() < birthDay))) {
				age--;
			}

        	if (parseInt(age) < 18){
        		return false;
        	} else {
        		return true;
        	}
        },
        "To complete the online sign-up, an applicant must be 18 years old or greater"
    );

    $.validator.addMethod(
        "verifyDocExpiry",
        function(value, element) {
        	var dt = value.split("/");
			var today = new Date();
    		var expiryDate = new Date(dt[2],dt[1],dt[0]);
    		if (expiryDate > today){
    			return true;
    		} else {
    			return false;
    		}
        },
        "Document Expiry date must be in future time"
    );

    $.validator.addMethod(
        "secondaryContactFields",
        function(value, element) {

        	if ($('#secondaryContactCheckBx').is(":checked")){

				if (value) {
					if (value.length < 1) {
						return false;
					}
				} else {
					return false;
				}

        	} else {
        		return true;
        	}
        },
        "This field is required"
    );
    $.validator.addMethod(
        "titleFields",
        function(value, element) {
			var title = $("input[name*='f_43']").val();
			if (title == ""){
				return false;
			} else {
				return true;
			}
        },
        "This field is required"
    );

    $.validator.addMethod(
        "concessionExpiryDate",
        function(value, element) {
			// compare two dates
			var concession_start_dt = $("input[name*='f_65']").val();
			var concession_end_dt = $("input[name*='f_66']").val();

			if ((concession_start_dt != "") && (concession_end_dt != "")){
				var start_dt = concession_start_dt.split("/");
				var end_dt = concession_end_dt.split("/");

				var startDate = new Date(start_dt[2],start_dt[1],start_dt[0]);
				var endDate = new Date(end_dt[2],end_dt[1],end_dt[0]);


				if (endDate.getTime() > startDate.getTime()){
					$("label[for='" + $(element).attr("name") + "'][class='error']").remove();
					return true;
				} else {
					return false;
				}
			}
        },
        "Expiry date can not be earlier than start date"
    );

    $.validator.addMethod(
        "concessionStartDate",
        function(value, element) {
			// compare two dates
			var concession_start_dt = $("input[name*='f_65']").val();

			if (concession_start_dt != ""){
				var start_dt = concession_start_dt.split("/");

				var startDate = new Date(start_dt[2],start_dt[1],start_dt[0]);
				var todayDate = new Date();
				todayDate.setMonth(todayDate.getMonth() + 1);


				if (todayDate.getTime() > startDate.getTime()){
					$("label[for='" + $(element).attr("name") + "'][class='error']").remove();

					return true;
				} else {
					return false;
				}
			}
        },
        "Start date must be earlier than today"
    );

    $.validator.addMethod(
        "concessionCheck",
        function(value, element) {
			if ($("input[name*='f_100']:radio:checked").val()){
				if ($("input[name*='f_100']:radio:checked").val().toUpperCase() == "NO"){
					return false;
				} else {
					return true;
				}
			}
        },
        "Your agreement is required"
    );

    $.validator.addMethod(
        "planOptionAuthorisation",
        function(value, element) {
			// compare two dates
			if ($("input[name*='p_08']:radio:checked").val()){
				if ($("input[name*='p_08']:radio:checked").val().toUpperCase() == "NO"){
					return false;
				} else {
					return true;
				}
			}
        },
        "Authorisation is required"
    );
    $.validator.addMethod(
        "LegalNameValidator",
        function(value, element) {
			var r_nonalphaNumeric = /[^a-zA-Z\s\-]/;
			var r_digit = /\d+/;
			var s = value;
			flg = 0;
			if (s.match(r_digit) != null){
				flg++;
			}

			if (s.match(r_nonalphaNumeric) != null){
				flg++;
			}

			if (flg > 0){
				return false;
			} else {
				return true;
			}
        },
        "Invalid name"
    );
	$('.signup_contact').validate({
		errorClass:"error_signupContract",
		rules:{
			'f_43' :{
				titleFields:true
			},
			'f_44' :{
				required:true,
				minlength:2,
				LegalNameValidator:true
			},
			'f_45' :{
				required:true,
				minlength:2,
				LegalNameValidator:true
			},
			'f_46':{
				required:true,
				verifyDOB:true
			},
			'f_47' :{
				required:true,
				digits: true,
				maxlength:10,
				minlength:10,
				verifyMobile:true
			},
			'f_48' :{
				digits: true,
				maxlength:10,
				minlength:8,
				verifyPhone:true
			},
			'f_49' :{
				required:true,
				email:true
			},
			'f_51' :{
				required:true,
				minlength:2
			},
			'f_52' :{
				required:true,
				minlength:2
			},
			'f_53':{
				required:true,
				verifyDOB:true
			},
			'f_54' :{
				required:true,
				digits: true,
				maxlength:10,
				minlength:10,
				verifyMobile:true
			},
			'f_55' :{
				required:true,
				digits: true,
				maxlength:10,
				minlength:8,
				verifyPhone:true
			}
		}
	});

	$('.signup_personal').validate({
		rules:{
			'f_56' :{
				required:true
			},
			'f_57' :{
				required:true,
				verifyMedicare:true
			},
			'f_58' :{
				required:true
			},
			'f_59' :{
				required:true
			},
			'f_59' :{
				required:true
			},
			'f_60' :{
				required:true
			},
			'f_61' :{
				required:true
			},
			'f_62' :{
				required:true
			},
			'f_63' :{
				required:true,
				lettersonly:true
			},
			'f_64' :{
				required:true
			},
			'f_65' :{
				required:true,
				concessionStartDate:true
			},
			'f_66' :{
				required:true,
				concessionExpiryDate:true
			},
			'f_97' :{
				required:true
			},
			'f_100' :{
    			required:true
			}
		}
	});

	$('.signup_plan').validate({});

	$('.signup_supply').validate({
		rules:{
			'f_67' :{
				minlength:1
			},
			'f_71' :{
				required:true
			},
			'f_72' :{
				required:true
			},
			'f_73' :{
				required:true
			},
			'f_74' :{
				required:true
			},
			'f_75' :{
				required:true,
				minlength:4
			},
			'f_85' :{
				required:true
			},
			'f_86' :{
				required:true,
				minlength:4
			},
			'f_77' :{
				required:true
			},
			'f_78' :{
				minlength:1
			},
			'f_82' :{
				required:true
			},
			'f_83' :{
				required:true
			},
			'f_84' :{
				required:true
			},
			'f_98' :{
				required:true
			},
			'f_94' :{
				verifyNMI:true
			}
		}
	});

	$('.signup_options').validate({
		rules:{
			'p_08' :{
				required:true,
				planOptionAuthorisation:true
			}
		}
	});

	$('.signup_tac').validate({
		rules:{
			'f_99' :{
				required:true
			}
		}
	});


	$("select[name*='f_56']").change(function() {
	  if ($(this).val() == "PP"){
	  	$("select[name*='f_60']").parent().show();
	  } else {
	  	$("select[name*='f_60']").parent().hide();
	  }
	});


	$(".checkbutton").click(function() {
		var dd = $(this).parent();
		$(dd).find('button').each(function(value) {
			$(this).removeClass('select-btn');
		});
		$(this).addClass('select-btn');
		$(dd).find('label').attr({'style':"color:#777777 !important"});
	});

	$('.qa').click(function(){
		QA_FieldValidation();
	});

	$('.report-issue').click(function(){
		$('#issue_summary').val('');
		$('#issue_description').val('');
		$('.issue_report_board').show();
		$('.submission_modal').show();
		$('.confirm').hide();
	});

	$('.field-validation-submit').click(function(){
		if ($('#issue_summary').val() == ''){
			return false;
		}
		if ($('#issue_description').val() == ''){
			return false;
		}
		var submission = {};
		submission.URL = window.location.href;

		var htmlString = $('html').html();
		$.post( "/qualityassurance/QA_Invoke_FieldValidation", JSON.stringify(submission), function(msg) {

			var QA_data = {};
			QA_data.compare = {};
			QA_data.compare.qadata = {};
			QA_data.compare.qaconfig = {};
			QA_data.compare.qaconfig.url = submission.URL;
			QA_data.compare.outcome = htmlString;
			QA_data.compare.qaconfig.TEST_UNIT_ID = msg.TEST_UNIT_ID;
			QA_data.type = "fieldvalidation";


			var jqxhr = $.post( "/qualityassurance/QA_Submit", JSON.stringify(QA_data), function(msg) {
				var obj = JSON.parse(msg.RESULT);


				var qa_param = {};
				qa_param.CASE_ID = msg.QA_CASE_ID;
				qa_param.RESULT_ID = msg.QA_RESULT_ID;
				qa_param.ISSUE_TITLE = $('#issue_summary').val();
				qa_param.ISSUE_DESCRIPTION = $('#issue_description').val();

				$('.submission_modal').show();
				$('.confirm').hide();

				var result = $.post( "/qualityassurance/QA_Register_Issue", JSON.stringify(qa_param), function(msg) {

					$('.submission_modal').hide();
					$('.confirm').show();
					$('.issue_confirm_txt').empty();
					$('.issue_confirm_txt').text("The issue is successfully registered with the id number:" + msg.QA_ISSUE_ID);

				});
			});
		});
	});


	$("input[name*='p_17']").click(function(){
		if($("input[name*='p_17']:checked").val() == "Yes"){
			if ($("input[name*='p_19']").length){
				$("input[name*='p_19']").parent().parent().show();
				$("#planoptionLbl_p_19").parent().parent().show();



			}
		} else {
			if ($("input[name*='p_19']").length){
				$("input[name*='p_19']").prop('checked', false);
				$("input[name*='p_19']").parent().parent().hide();
				$("#planoptionLbl_p_19").parent().parent().hide();
				$("#planoptionLbl_p_20").parent().parent().hide();
			}

		}


	});
});
var tags = [];
$(document).ready(function() {

	/************
	** Distributing values collected from the previous sequence to respective control elements
	*/
		try {

			$('.column-to-expand').parent().parent().attr('class','col-lg-12');

			for (var x in ui_instructions.pre_select.input){
				$("input[name*='" + x + "']").val(ui_instructions.pre_select.input[x]);
			}

			for (var x in ui_instructions.pre_select.select){
				$("select[name*='" + x + "']").find('option').each( function() {
					var $this = $(this);
					if ($this.text() == ui_instructions.pre_select.select[x]) {
						$this.attr('selected','selected');
						return false;
					}
				});
			}

			for (var x = 0; x < ui_instructions.hide_show.length; x++){
				for (var r in ui_instructions.hide_show[x]){
					if (ui_instructions.hide_show[x][r] == "hide") {
						$("input[name*='" + r + "']").parent().hide();
						/****
                        *  For NMI field, we need to hide its parent div too
                        **/
                        if (r == 'f_94') {
    					    $("input[name*='" + r + "']").parent().parent().hide();
					    }
					}
					if (ui_instructions.hide_show[x][r] == "hide") {
						$("select[name*='" + r + "']").parent().hide();
					}
				}
			}

			/****
			*  One of UI requirements is to display estimated cost for only applicable fuel.
			*  Both Electricity and Gas estimates are displayed when the fuel type of Duel
			**/
			if (ui_instructions.display_cost_estimate == "Elec"){
				$("#gas_cost_estimate").remove();
				$("#elec_cost_estimate").removeClass("col-lg-6");
				$("#elec_cost_estimate").addClass("col-lg-12");
			}

			if (ui_instructions.display_cost_estimate == "Gas"){
				$("#elec_cost_estimate").remove();
				$("#gas_cost_estimate").removeClass("col-lg-6");
				$("#gas_cost_estimate").addClass("col-lg-12");
			}

			/*******************
			**  Requirements - to hide plan options that are attributed to the contextual dependency to other plan options
			*/
			for (var x in ui_instructions.planOptions_show_hide){
				if (ui_instructions.planOptions_show_hide[x] == "hide"){
					$("input[name*='" + x + "']").parent().parent().hide();
				}

				$("#planoptionLbl_" + x).parent().parent().hide();
			}

			/*******************
			**  Initialise the display of pre-concession text of which display is dictated by retailers
			**	Okkey Sumiyoshi
			**	29/02/2016
			*/
			for (var x in ui_instructions.preconcession_show_hide){
				if (ui_instructions.preconcession_show_hide[x] == "hide"){
					if ($("input[name*='" + x + "']").length > 0){
						$("input[name*='" + x + "']").parent().parent().parent().hide();
					}
					if ($("." + x).length > 0){
						$("." + x).parent().parent().hide();
					}
				}
			}

			/*******************
			**  Requirements - to display only concession card types that are applicable for the state chosen
			*/
			$(".selectPersonalDetails_concessionCardType option").each(function(){
				if (iterateRemoveList($(this).val())){
					$(this).remove();
				}

				function iterateRemoveList(_type){
					var flg = 0;
					for (var i in consession_removed){
						if (i == _type){
							flg = 1;
						}
					}
					return flg;
				}
			});;

		}
		catch(err) {

		}

	/***************
	** Requirement: To show driving license as only the acceptable document type for proving personal identification for Sumo Power
	*/
	if (ui_instructions['retailer'] == "Sumo Power"){
		$("select[name*='f_56'] option").filter(function(i, e) {
			if ($(e).val() != "DRV"){
				$(e).remove();
			}
		});
	}

	/***************
	** Requirement: To make state and postcode value to be readonly.  Should this be updated, a warning message will be displayed.
	*/
	$("input[name*='f_75']").prop('readonly', true);
	$("select[name*='f_76']").prop('disabled',true);


	/************
	** Street type initialisation
	*/
	$("input[name*='f_73']").autocomplete({
    	autoFocus: true,
        minLength:1,
		source: function( request, response ) {
			$.ajax({
				url: "/submissions/street_type",
				dataType: "jsonp",
				type: "GET",
				contentType: "application/json; charset=utf-8",
				data: {term:$("input[name*='f_73']").val()},
				success: function( data ) {

    				if (data.items.length > 0) {
                        response( $.map( data.items, function( item ){
					    	return {
					    		label: item.name,
					    		value: item.name
					    	}
					    }));
    				} else {
                        $("input[name*='f_73']").val('');
                        $("input[name*='f_73']").addClass('error').removeClass('valid');
    				}
				}
			});
		},
		change: function ( event, ui ) {
            if (!ui.item) {
                $("input[name*='f_73']").val('');
                $("input[name*='f_73']").addClass('error').removeClass('valid');
            }
        },
        select: function( event, ui ) {
            $("input[name*='f_73']").addClass('valid').removeClass('error');
        }
	});
	$("input[name*='f_84']").autocomplete({
    	autoFocus: true,
        minLength:1,
		source: function( request, response ) {
			$.ajax({
				url: "/submissions/street_type",
				dataType: "jsonp",
				type: "GET",
				contentType: "application/json; charset=utf-8",
				data: {term:$("input[name*='f_84']").val()},
				success: function( data ) {

    				if (data.items.length > 0) {
                        response( $.map( data.items, function( item ){
					    	return {
					    		label: item.name,
					    		value: item.name
					    	}
					    }));
    				} else {
                        $("input[name*='f_84']").val('');
                        $("input[name*='f_84']").addClass('error').removeClass('valid');
    				}
				}
			});
		},
		change: function ( event, ui ) {
            if (!ui.item) {
                $("input[name*='f_84']").val('');
                $("input[name*='f_84']").addClass('error').removeClass('valid');
            }
        },
        select: function( event, ui ) {
            $("input[name*='f_84']").addClass('valid').removeClass('error');
        }
	});

	/************
	** Full address enquiry
	*/
    $(document).on("keyup", '.full_address', function(event) {
		var addressString = $(this).val();
		addressString = addressString.replace(' ', '+');
        inivioValidator(addressString);
    });

	$('form').submit(function() {
		return false;
	});

	$.fn.accordionControler = function( options ) {
		var opts = $.extend( {}, $.fn.accordionControler.defaults, options );
		return true;
	};

	$.fn.accordionControler.defaults = {};

	$("input[name*='f_97']").click(function() {

		$("input[name*='f_63']").val($("input[name*='f_44']").val() + " " + $("input[name*='f_45']").val());

		/**
		**	Okkey Sumiyoshi
		**	29/02/2016
		**	A control for displaying pre-concession text
		*********/

		if (ui_instructions.preconcession_show_hide == ""){
			radioBtnTriggeredAccordion($(this).get(0), "setction_ConcessionCard");
		} else {
			if($("input[name*='f_97']:checked").val() == "Yes"){
				if (ui_instructions.retailer == "Momentum"){
    				if ($('.c_transition_01').length > 0 || $('.c_transition_02').length > 0) {
        				$('.c_transition_01').parent().parent().show();
        				$('.c_transition_02').parent().parent().show();
        				$('.c_transition_01').each(function(){
        					if ($(this).attr("class").indexOf('yellow-bg') > -1){
        						$(this).css('background-color','#f6f6b1');
        						$(this).css('padding','18px');
        					}
        					if ($(this).attr("class").indexOf('red-bg') > -1){
        						$(this).css('background-color','#fbdbd0');
        						$(this).css('padding','18px');
        					}
        				});
        				$('.c_transition_02').each(function(){
        					if ($(this).attr("class").indexOf('yellow-bg') > -1){
        						$(this).css('background-color','#f6f6b1');
        						$(this).css('padding','18px');
        					}
        					if ($(this).attr("class").indexOf('red-bg') > -1){
        						$(this).css('background-color','#fbdbd0');
        						$(this).css('padding','18px');
        					}
        				});
    					$("input[name*='transition_02']").parent().parent().parent().show();
    				} else {
        				radioBtnTriggeredAccordion($(this).get(0), "setction_ConcessionCard");
    				}
				} else if ((ui_instructions.retailer == "AGL")||(ui_instructions.retailer == "Powerdirect")){
    				if ($('.c_transition_06').length > 0 || $('.c_transition_07').length > 0 || $('.c_transition_09').length > 0) {
        				$('.c_transition_06').parent().parent().show();
        				$('.c_transition_07').parent().parent().show();
        				$('.c_transition_09').parent().parent().show();
        				$('.c_transition_06').each(function(){
        					if ($(this).attr("class").indexOf('yellow-bg') > -1){
        						$(this).css('background-color','#f6f6b1');
        						$(this).css('padding','18px');
        					}
        					if ($(this).attr("class").indexOf('red-bg') > -1){
        						$(this).css('background-color','#fbdbd0');
        						$(this).css('padding','18px');
        					}
        				});
        				$("input[name*='transition_07']").parent().parent().parent().show();
    					$("input[name*='transition_09']").parent().parent().parent().show();
    				} else {
        				radioBtnTriggeredAccordion($(this).get(0), "setction_ConcessionCard");
    				}
				}
			} else {
				radioBtnTriggeredAccordion($(this).get(0), "setction_ConcessionCard");
				for (var i in ui_instructions.preconcession_show_hide){

					$('#concession_lbl_' + i).parent().parent().hide();
					$("input[name*='" + i + "']").parent().parent().parent().hide();
					$("input[name*='" + i + "']").prop('checked', false);
					if (ui_instructions.retailer == "Momentum"){
    					$('.c_transition_01').parent().parent().hide();
    					$('.c_transition_02').parent().parent().hide();
    					$("input[name*='transition_02']").parent().parent().parent().hide();
    				} else if ((ui_instructions.retailer == "AGL")||(ui_instructions.retailer == "Powerdirect")){
        				$('.c_transition_06').parent().parent().hide();
                        $('.c_transition_07').parent().parent().hide();
                        $('.c_transition_09').parent().parent().hide();
    					$("input[name*='transition_07']").parent().parent().parent().hide();
    					$("input[name*='transition_09']").parent().parent().parent().show();
    				}
				}
			}
		}
	});

	$("input[name*='f_98']").click(function() {
		radioBtnTriggeredAccordion($(this).get(0), "section_PropertySecondayContact");
	});
	$("input[name*='p_04']").click(function() {
    	$("#planoptionLbl_p_05").parent().parent().hide();
        $("#planoptionLbl_p_06").parent().parent().hide();
        $("input[name*='p_06']").parent().parent().hide();
        if ($(this).is(":checked")) {
            if ($(this).val().toUpperCase() == "YES") {
                $("#planoptionLbl_p_05").parent().parent().show();
                $("#planoptionLbl_p_06").parent().parent().show();
                $("input[name*='p_06']").parent().parent().show();
            }
        }
    });

	$("input[name*='f_46']").datepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
        startDate: '-100y',
        endDate: '-18y'
    }).on('changeDate', function (ev) {
    	$(this).validate().element(this);
    });
	$("input[name*='f_53']").datepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
        startDate: '-100y',
        endDate: '-18y'
    }).on('changeDate', function (ev) {
    	$(this).validate().element(this);
    });
    $("input[name*='f_58']").datepicker({
        format: "dd/mm/yyyy",
        autoclose: true
    }).on('changeDate', function (ev) {
    	$(this).validate().element(this);
    });
    $("input[name*='f_65']").datepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
    }).on('changeDate', function (ev) {
    	$("label[for='f_65'][class='error']").remove();
    	$(this).validate().element(this);
    });
    $("input[name*='f_66']").datepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
    }).on('changeDate', function (ev) {
    	$("label[for='f_66'][class='error']").remove();
    	$(this).validate().element(this);
    });

	//Concession
	$("input[name*='concession_radio']").click(function(){
		if($(this).val() == "Yes"){
			$("input[name*='f_63']").val(
				$("input[name*='f_44']").val() + " " + $("input[name*='f_45']").val()
			);
		}
	});

	/**
    * Enter address manually
    */
	$(document).on("click", '.address-fields-show', function(event) {
        $(".address_fields").removeClass('hide');
        $("input[name*='your_address']").val('');
        $("input[name*='your_address']").addClass('valid').removeClass('error');
        $(".full_address_error").addClass('hide');
        $("input[name*='your_address']").autocomplete('close');
    });

    timeoutEvent();

});

var util = {
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

function checkContactSecondary(value) {
	if ($(value).is(":checked")){
		$('#secondary-contact').accordion('option',{collapsible: false, active:true});
	} else {$('#secondary-contact').accordion('option',{collapsible: true, active:false});}
}

function getInputs(evt, _targetSection){
	if ($(evt).is(":checked")){
		$("#" + _targetSection).show();
	} else {
		$("#" + _targetSection).hide();
		var target = $("#" + _targetSection).get(0);
		var inputs = target.getElementsByTagName("input");
		for (var i = 0; i<inputs.length; i++){
			$(inputs[i]).val("");
		}
	}
}

function radioBtnTriggeredAccordion(evt, _targetSection){
	if ($(evt).is(":checked")){
		if ($(evt).val().toUpperCase() == "YES"){
			$("#" + _targetSection).show();
		}
		if ($(evt).val().toUpperCase() == "NO"){
		$("#" + _targetSection).hide();
			var target = $("#" + _targetSection).get(0);
			var inputs = target.getElementsByTagName("input");
			for (var i = 0; i<inputs.length; i++){
				$(inputs[i]).val("");
			}
		}
	}
}

function POBOXDisplayControls(evt){
	if ($(evt).is(":checked")){
		$('.col_pobox').show();
		$('.col_units').hide();
		$('.col_streetNum').hide();
		$('.col_streetType').hide();
		$('.col_streetName').hide();
	} else {
		$('.col_pobox').hide();
		$('.col_units').show();
		$('.col_streetNum').show();
		$('.col_streetType').show();
		$('.col_streetName').show();
	}
}

function PlanOptionsChainReactor(_evt, _value, _ref){
	if ($(_evt).is(":checked")){
		if ($(_evt).val().toUpperCase() == _value.toUpperCase()){
			$("input[name*='" + _ref + "']").parent().parent().parent().show();
			$("#planoptionLbl_" + _ref).parent().parent().show();
			$('.column-to-expand').parent().parent().attr('class','col-lg-12');
			$('.yellow-bg').css('background-color','#f6f6b1');
			$('.yellow-bg').css('padding','18px');
			$('.red-bg').css('background-color','#fbdbd0');
			$('.red-bg').css('padding','18px');


		}
		if ($(_evt).val().toUpperCase() != _value.toUpperCase()){
			$("input[name*='" + _ref + "']").parent().parent().parent().hide();
			$("input[name*='" + _ref + "']").prop('checked', false);
			$("#planoptionLbl_" + _ref).parent().parent().hide();
			$("#planoptionLbl_" + _ref + " .yellow-bg").css('padding','0');
			$("#planoptionLbl_" + _ref + ".red-bg").css('padding','0');
		}
	}
}

function preConcessionChainReactor(_evt, _value, _ref){
	var val_obj = JSON.parse(_value);
	var source_element = _evt;

	if ($(_evt).is(":checked")){
		for (var i = 0; i<val_obj.length; i++){
			if ($(_evt).val().toUpperCase() == val_obj[i].invocation.toUpperCase()){

				applyReverseEffectsToUnselectedElements(val_obj[i].invocation);

				var targets = val_obj[i].target.split(",");
				var event = val_obj[i].event;
				if (event.toUpperCase() == "SHOW"){
					for (var x = 0; x<targets.length; x++){
						var class_name = targets[x].replace(" ", "");
						$('.c_' + class_name).parent().parent().show();
						$("input[class*='c_" + class_name + "']").parent().parent().parent().show();
						$('.c_' + class_name).each(function(){
							if ($(this).attr("class").indexOf('yellow-bg') > -1){
								$(this).css('background-color','#f6f6b1');
								$(this).css('padding','18px');
							}
							if ($(this).attr("class").indexOf('red-bg') > -1){
								$(this).css('background-color','#fbdbd0');
								$(this).css('padding','18px');
							}
						});
					}
				}
				if (event.toUpperCase() == "FUNCTION"){
					window[val_obj[i].target]($("input[name*='f_97']").get(0), "setction_ConcessionCard");
					applyReverseEffectsToUnselectedElements(val_obj[i].invocation);
				} else {
					$("#setction_ConcessionCard").hide();
				}

			}
		}
	}

	function applyReverseEffectsToUnselectedElements(x_invocation){
		var outcome = new Array();

		//reversing the element properties within the scope
		for (var i = 0; i<val_obj.length; i++){
			if (val_obj[i].invocation.toUpperCase() != x_invocation.toUpperCase()){
				var targets = val_obj[i].target.split(",");
				for (var x = 0; x<targets.length; x++){
					var class_name = targets[x].replace(" ", "");
					outcome.push(class_name);
				}
			}
		}

		for (var g = 0; g<outcome.length; g++){
			$('.c_' + outcome[g]).parent().parent().hide();
			$("input[class*='c_" + outcome[g] + "']").parent().parent().parent().hide();
		}

		var source_element_name = $(source_element).attr('name');

		var keyInputName = "";
		var position = 0;
		var cursouraddress = 1;

		if (preConcessionQuestionOrder != ''){
			for (var i = 0; i<preConcessionQuestionOrder.length; i++){
			position++;
				for (var a in preConcessionQuestionOrder[i]){
					if (a == "id"){
						if (preConcessionQuestionOrder[i]['id'] == source_element_name){
							cursouraddress = position;
							keyInputName = 	preConcessionQuestionOrder[i]['name'];
						}
					}
				}
			}
		}

		var recount = 0;

		if (preConcessionQuestionOrder != ''){
			for (var i = 0; i<preConcessionQuestionOrder.length; i++){
				recount++;
				if (recount > cursouraddress){
					$("input[name*='transition_" + preConcessionQuestionOrder[i].id + "']").parent().parent().parent().hide();
					$(".c_"+preConcessionQuestionOrder[i].name).parent().parent().hide();
				}
			}
		}

	}
}

function inivioValidator(_addressString){
    $("input[name*='your_address']").autocomplete({
        autoFocus: true,
        minLength:1,
    	source: function( request, response ) {
    		$.ajax({
    			url: "https://services.inivio.com.au/autocomplete/address/address?",
    			dataType: "jsonp",
    			type: "GET",
    			contentType: "application/json; charset=utf-8",
    			data: {address:_addressString, as:"json", addressformat: "natural" },
    			success: function( data ) {

    				if (data.Result !== null) {
                        response( $.map( data.Result, function( address ){
    				    	return {
        				    	label: address,
    				    		value: address
    				    	}
    				    }));
    				} else {
        				$("input[name*='your_address']").addClass('error').removeClass('valid');
    				}
    			},
    		});
    	},
    	change: function ( event, ui ) {
            if (!ui.item) {
                $("input[name*='your_address']").val('');
                $("input[name*='your_address']").addClass('error').removeClass('valid');
            }
        },
    	select: function( event, ui ) {
            if (typeof ui.item != 'undefined') { // selected an address
                $.ajax({
    		    	url: "https://services.inivio.com.au/autocomplete/address/AddressFields?",
    		    	dataType: "jsonp",
    		    	type: "GET",
    		    	contentType: "application/json; charset=utf-8",
    		    	data: {address:ui.item.value, as:"json" },
    		    	success: function( data ) {

    		    		$(".address_fields").addClass('hide');
    		    		if (ui_instructions.pre_select.input.f_75 == data.Result.Postcode) {
        	    			var address_block = ui.item.value.split(',');
                            var sources = address_block[0].split(" ");
                            var street_type = sources[sources.length - 1];
                            $("input[name*='f_67']").val(data.Result.Unit);
                            $("input[name*='f_71']").val(data.Result.StreetNumber);
                            $("input[name*='f_72']").val(data.Result.Street);
                            $("input[name*='f_73']").val(street_type);
                            $("input[name*='f_74']").val(data.Result.Suburb);
                            $("input[name*='f_75']").val(data.Result.Postcode);
                            $("input[name*='f_76']").val(data.Result.State);
                            $("input[name*='f_76']").selectpicker('refresh');
                            $(".full_address_error").addClass('hide');
                            $("input[name*='your_address']").addClass('valid').removeClass('error');
    		    		} else {
        	    			$(".full_address_error").removeClass('hide');
        	    			$("input[name*='your_address']").val('');
        	    			$("input[name*='your_address']").addClass('error').removeClass('valid');
    		    		}
    		    	}
    		    });
        	}
        },
        open: function (event, ui) {
            $('.ui-autocomplete').append("<li class='ui-menu-item address-fields-show'>I can't find my address above. Let me enter it manually</li>"); // enter manually option
        }
    });
    $("input[name*='your_address']").attr('autocomplete', 'off'); // disable chrome autofill, overwrite jquery ui autocomplete
}

/*******
** Requirement: Need a function that will be fired through setInterval
** Problem:  user was unable to submit a form within 30 minutes
** Solution:
** st 15 minutes point after the page was first loaded, a dialogue box appears informing the user 15 minutes left to complete the form
** at 30 minutes after the page was first loaded, the function timeoutEvent will be invoked
***/
function timeoutEvent(){
	/*****
	** Requirement: Display a dialogue box informing a user to
	**
	****/

	if (l_id == ""){
		return;
	}



	var data = {};
	data.leadid = l_id; /* l_id is a local variable printed at the page load by php */

    var isPaused = false;
    var countdown = 30 * 60 * 1000; // 30 minutes
    var timerId = setInterval(function() {
        if (!isPaused) {
            countdown -= 1000;
        }
        var min = Math.floor(countdown / (60 * 1000));
        var sec = Math.floor((countdown - (min * 60 * 1000)) / 1000);
        if (countdown <= 0) {
            isPaused = true;
            $.confirm({
		        text: "We can see you've taken over 30 minutes to complete the form. Do you need some more time?",
		        confirmButton: 'Yes, I need another 30 minutes',
		        cancelButton: 'No',
		        confirm: function(button) {
    	    	    countdown = 30 * 60 * 1000;
    	    	    isPaused = false;
		        },
		        cancel: function(button) {
    	    	    $.post( "/submissions/forceTimeOut", data, function(msg) {
                        if (msg.status == 'complete') {
                            window.location.replace("https://"+compare_website_domain_name+"/about-you");
                        } else {
                            window.location.replace("https://"+compare_website_domain_name+"/about-you");
                        }
                    });
		        	clearInterval(timerId);
		        }
		    });
        } else if (countdown == 15 * 60 * 1000) {
            isPaused = false;
            $.confirm({
		        text: 'This is a friendly reminder that you have another 15 minutes to complete the signup form',
		        confirmButton: 'OK',
		        cancelButtonClass: 'hide',
		        confirm: function(button) {
    	    	    isPaused = false;
		        },
		        cancel: function(button) {
    	    	    isPaused = false;
		        }
		    });
        } else if (countdown == 5 * 60 * 1000) {
            isPaused = false;
            $.confirm({
		        text: 'This is a friendly reminder that you have another 5 minutes to complete the signup form',
		        confirmButton: 'OK',
		        cancelButtonClass: 'hide',
		        confirm: function(button) {
    	    	    isPaused = false;
		        },
		        cancel: function(button) {
    	    	    isPaused = false;
		        }
		    });
        }
    }, 1000);
}
