var dataOptions = {};
dataOptions.current = {'plan':true,'contact':true,'supply':false,'identification':false,
    'supply':false,'final':true,'telco':false,'flagTelco':false,};
dataOptions.fields = {};

dataOptions.count = 0;

dataOptions.selectedState = false;

dataOptions.FlagConcession = true;

$(function() {
    $('#accordion').accordion({
        heightStyle: "content",
        beforeActivate: function( event, ui ) {
            $(ui.oldHeader).addClass('active');
            var n = dataOptions.count;
            checkValidation(dataOptions.count);
            var current = ui.newPanel.attr('id').split('-');
            var currentHeader = ui.newHeader.attr('id').split('-');
            var reac = (currentHeader[2] - 1) / 2;
            if (reac <  dataOptions.count) {
                $(ui.newHeader).removeClass('active')
            }
            var currentOldP = ui.oldPanel.attr('id').split('-');
            var addac = currentOldP[2] / 2;
            activeClassCheck(addac,ui.oldHeader);
            var checkheader = 1*n + 1 ;
            var checkpanel  = 2*n + 2 ;
            if (checkpanel < current[2] ) {
                return false;
            }
        },
        create: function( event, ui ) {
            $('#accordion h3').each(function(index) {
                if ($( this ).attr('id') == ui.header.attr('id')) {

                } else {

                }
                if ($( this ).attr('id') == 'ui-id-7') {
                    $('.identification').hide();
                }
                if ($( this ).attr('id') == 'ui-id-9') {
                    $('.concession').hide();
                }
                if ($( this ).attr('id') == 'ui-id-15') {
                    $('.MoveInDetails').hide();
                }
            });

        },
        activate: function( event, ui ) {
            //console.log('activate',event,ui);
        },
    });

    lead_lookup = function(id) {
        $.post('/customers/get_lead_fields', {lead_id: id}, function(data) {
            if (!data.first_name) {
                alert('Lead not found');
                $('#plan_lead_id').val('');
                return false;
            }
            var reg = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            var import_data = true;
            var overwrite = true;
            var campaign_id_arr2 = ["1", "76", "77"];
            if (data.telco_reference_number != '') {
                alert('This is an existing sale and cannot be overwritten, will import this data for ' + data.first_name);
                overwrite = false;
            }
            if (data.lead_type != 'Telco') {
                overwrite = false;
            }
            if (import_data) {
                if (data.title) {
                    $('#contact_title').val(data.title);
                    $('#contact_title').selectpicker('refresh');
                }
                $('#contact_first_name').val(data.first_name);
                $('#contact_last_name').val(data.last_name);
                if (data.dob) {
                    $('#datepickerDateOfBirth').val(data.dob);
                }
                if (data.mobile) {
                    $('#contact_mobile').val(data.mobile);
                }
                if (data.home_phone) {
                    $('#contact_home_phone').val(data.home_phone);
                }
                if (data.email) {
                    $('#contact_email').val(data.email);
                }
                if (data.fuel_type) {
                    $('.planfuel').val(data.fuel_type);
                    $('.planfuel').selectpicker('refresh');
                }
                if (data.elec_retailer || data.gas_retailer) {
                    if (data.elec_retailer && data.elec_retailer!= 'NA' && $('#plan_supplier').length > 0) {
                        $('#plan_supplier').val(data.elec_retailer);
                    }
                    if (data.gas_retailer && data.gas_retailer!= 'NA' && $('#plan_supplier').length > 0) {
                        $('#plan_supplier').val(data.gas_retailer);
                    }
                }
                if (data.customer_type) {
                    $('.customertype').val(data.customer_type);
                    $('.customertype').selectpicker('refresh');
                }
                if (data.looking_for) {
                    $('.lookingfor').val(data.looking_for);
                    $('.lookingfor').selectpicker('refresh');
                }
                if (data.state_supply && $('#plan_state').length > 0) {
                    $('#plan_state').val(data.state_supply);
                }
                if (data.secondary_title) {
                    $('#contact_secondary_title').val(data.secondary_title);
                }
                if (data.secondary_first_name) {
                    $('#contact_secondary_first_name').val(data.secondary_first_name);
                    $('#secodryCheckbox').prop('checked', true);
                    $('.secondary-contact').show();
                }
                if (data.secondary_last_name) {
                    $('#contact_secondary_last_name').val(data.secondary_last_name);
                }
                if (data.secondary_dob) {
                    $('#datepickerDateOfBirthSec').val(data.secondary_dob);
                }
                if (data.secondary_mobile) {
                    $('#contact_secondary_mobile').val(data.secondary_mobile);
                }
                if (data.secondary_phone) {
                    $('#contact_secondary_phone').val(data.secondary_phone);
                }
                if (data.sales_rep_name) {
                    if (data.sales_rep_name && data.sales_rep_name != 'Mr Pool') {
                        $('#sales_rep_name').val(data.sales_rep_name);
                    }
                }
                if (data.agent_id) {
                    $('#agent_id').val(data.agent_id);
                }
                if (data.campaign_source) {
                    $('#final_campaign_source').val(data.campaign_source);
                }
                if (data.content) {
                    $('#final_content').val(data.content);
                }
                if (data.lead_age) {
                    $('#final_lead_age').val(data.lead_age);
                }
                if (data.medium) {
                    $('#final_medium').val(data.medium);
                }
                if (data.howtheyfoundus) {
                    $('#final_howtheyfoundus').val(data.howtheyfoundus);
                }
                if (data.keyword) {
                    $('#final_keyword').val(data.keyword);
                }
                if (data.url) {
                    $('#final_url').val(data.url);
                }
                if (data.lead_campaign) {
                    $('#final_lead_campaign').val(data.lead_campaign);
                }
                if (data.campaign_ad_group) {
                    $('#final_campaign_ad_group').val(data.campaign_ad_group);
                }
                if (data.id) {
                    $('#final_id').val(data.id);
                }
                if (data.campaign_id && data.campaign_name) {
                    if ($.inArray(data.campaign_id, campaign_id_arr2) == -1) {
                        var option = "<option value=''>Please select</option>";
                        option += "<option value='76'>WTCM</option>";
                        option += "<option value='77'>MIC</option>";
                        option += "<option value='1'>EW Phone</option>";
                        option += "<option value='"+data.campaign_id+"'>"+data.campaign_name+"</option>";
                        $('#final_campaign_id').empty().append(option);
                    } else {
                        $('#final_campaign_id').val(data.campaign_id);
                    }
                    $('#final_campaign_id').selectpicker('refresh');
                    $('#final_campaign_name').val(data.campaign_name);
                }
                if (data.first_campaign) {
                    $('#final_first_campaign').val(data.first_campaign);
                }
                /*
                if (data.abn) {
                    $('#abn').val(data.abn);
                }
                if (data.trading_name) {
                    $('#trading_name').val(data.trading_name);
                }
                if (data.legal_name) {
                    $('#legal_name').val(data.legal_name);
                }
                if (data.business_type) {
                    $('#business_type').val(data.business_type);
                    $('#business_type').selectpicker('refresh');
                }
                */
                if (data.document_type) {
                    $('#document_type').val(data.document_type);
                    $('#document_type').selectpicker('refresh');
                }
                if (data.document_id) {
                    $('#document_id').val(data.document_id);
                }
                if (data.document_expiry) {
                    $('#identification_document_expiry').val(data.document_expiry);
                }
                if (data.document_state) {
                    $('#document_state').val(data.document_state);
                    $('#document_state').selectpicker('refresh');
                }
                if (data.document_country) {
                    $('#document_country').val(data.document_country);
                    $('#document_country').selectpicker('refresh');
                }
                
                if (data.suburb_supply) {
                    $("input[name='Supply[suburb][0]']").val(data.suburb_supply);
                }
                if (data.postcode_supply) {
                    $("input[name='Supply[postcode][0]']").val(data.postcode_supply);
                }
                if (data.state_supply) {
                    $(".selectSupplyState").val(data.state_supply);
                }
                if (data.no_street_number_supply) {
                    $("input[name='Supply[no_street_number][0]']").prop('checked');
                }
                if (data.street_number_supply) {
                    $("input[name='Supply[street_number][0]']").val(data.street_number_supply);
                }
                if (data.street_number_suffix_supply) {
                    $("input[name='Supply[street_number_suffix][0]']").val(data.street_number_suffix_supply);
                }
                if (data.street_name_supply) {
                    $("input[name='Supply[street_name][0]']").val(data.street_name_supply);
                }
                if (data.street_name_suffix_supply) {
                    $("input[name='Supply[street_name_suffix][0]']").val(data.street_name_suffix_supply);
                }
                if (data.street_type_supply) {
                    $("input[name='Supply[street_type][0]']").val(data.street_type_supply);
                }
                if (data.unit_supply) {
                    $("input[name='Supply[unit][0]']").val(data.unit_supply);
                }
                if (data.unit_type_supply) {
                    $(".supplyunittype").val(data.unit_type_supply);
                }
                if (data.lot_supply) {
                    $("input[name='Supply[lot][0]']").val(data.lot_supply);
                }
                if (data.floor_supply) {
                    $("input[name='Supply[floor][0]']").val(data.floor_supply);
                }
                if (data.floor_type_supply) {
                    $(".supplyfloortype").val(data.floor_type_supply);
                }
                if (data.building_name_supply) {
                    $("input[name='Supply[building_name][0]']").val(data.building_name_supply);
                }
                if (data.billing_address_different) {
                    $("input[name='Supply[billing_address_is_different]']").val(data.billing_address_different);
                    $('.billing_address_different').eq(1).addClass('select-btn');
                    checkSupplySecondary(1);
                    
                    if (data.suburb_billing) {
                        $("input[name='SupplySecondary[suburb]']").val(data.suburb_billing);
                    }
                    if (data.postcode_billing) {
                        $("input[name='SupplySecondary[postcode]']").val(data.postcode_billing);
                    }
                    if (data.state_billing) {
                        $(".selectSupplySecondaryState").val(data.state_billing);
                    }
                    if (data.no_street_number_billing) {
                        $("input[name='SupplySecondary[no_street_number]']").prop('checked');
                    }
                    if (data.street_number_billing) {
                        $("input[name='SupplySecondary[street_number]']").val(data.street_number_billing);
                    }
                    if (data.street_number_suffix_billing) {
                        $("input[name='SupplySecondary[street_number_suffix]']").val(data.street_number_suffix_billing);
                    }
                    if (data.street_name_billing) {
                        $("input[name='SupplySecondary[street_name]']").val(data.street_name_billing);
                    }
                    if (data.street_name_suffix_billing) {
                        $("input[name='SupplySecondary[street_name_suffix]']").val(data.street_name_suffix_billing);
                    }
                    if (data.street_type_billing) {
                        $("input[name='SupplySecondary[street_type]']").val(data.street_type_billing);
                    }
                    if (data.unit_billing) {
                        $("input[name='SupplySecondary[unit]']").val(data.unit_billing);
                    }
                    if (data.unit_type_billing) {
                        $(".supplysecondaryunittype").val(data.unit_type_billing);
                    }
                    if (data.lot_billing) {
                        $("input[name='SupplySecondary[lot]']").val(data.lot_billing);
                    }
                    if (data.floor_billing) {
                        $("input[name='SupplySecondary[floor]']").val(data.floor_billing);
                    }
                    if (data.floor_type_billing) {
                        $(".supplysecondaryfloortype").val(data.floor_type_billing);
                    }
                    if (data.building_name_billing) {
                        $("input[name='SupplySecondary[building_name]']").val(data.building_name_billing);
                    }
                    if (data.po_box_billing) {
                        $("input[name='SupplySecondary[po_box]']").val(data.po_box_billing);
                    }
                }
                
                if (data.telco_plan_type) {
                    $(".selectTelcoPlanType").val(data.telco_plan_type);
                    $('.selectTelcoPlanType').selectpicker('refresh');
                }
                if (data.telco_nbn_speed) {
                    $(".selectTelcoNBNSpeed").val(data.telco_nbn_speed);
                    $('.selectTelcoNBNSpeed').selectpicker('refresh');
                }
                if (data.telco_price) {
                    $(".selectTelcoPrice").val(data.telco_price);
                    $('.selectTelcoPrice').selectpicker('refresh');
                }
                if (data.telco_bundle_discount) {
                    $(".selectTelcoBundleDiscount").val(data.telco_bundle_discount);
                    $('.selectTelcoBundleDiscount').selectpicker('refresh');
                }
                if (data.telco_payment_type) {
                    $(".selectTelcoPaymentType").val(data.telco_payment_type);
                    $('.selectTelcoPaymentType').selectpicker('refresh');
                }
                if (data.telco_pay_submit) {
                    $(".selectTelcoPaySubmit").val(data.telco_pay_submit);
                    $('.selectTelcoPaySubmit').selectpicker('refresh');
                }
                if (data.telco_reference_number) {
                    $("#telco_pre_auth_submit").val(data.telco_reference_number);
                }
                if (data.telco_referred_agent_name) {
                    $("#telco_na").val(data.telco_referred_agent_name);
                }
                if (data.telco_retailer) {
                    $("#telco_retailer").val(data.telco_retailer);
                }
                if (data.referrer_lead_id) {
                    $("#referrer_lead_id").val(data.referrer_lead_id);
                } else {
                    $("#referrer_lead_id").prop('readonly', false);
                }
                
                /*
                if (data.renant_owner) {
                    $('.supplytenantowner:first').val(data.renant_owner);
                    if (data.renant_owner == 'Renter') {
                        $('.supplytenant:first').addClass('select-btn');
                    } else {
                        $('.supplyowner:first').addClass('select-btn');
                    }
                }
                */
                $('#lead_imported').val(1);
            } else {
                $('#lead_imported').val(-1);
            }
            if (!overwrite) {
                $('#plan_lead_id').val('');
            }
        });
    }
    
    $('#plan_lead_id').blur(function() {
        if ($(this).val()) {
            var id = $(this).val();
            lead_lookup(id);
        } else {
            $('#plan_lead_id').addClass('valid').removeClass('error');
        }
    });
    
    if ($('#plan_lead_id').val()) {
        var id = $('#plan_lead_id').val();
        lead_lookup(id);
    } else if ($('#referrer_lead_id').val()) {
        var id = $('#referrer_lead_id').val();
        lead_lookup(id);
    }

    $('select.selectpicker').change(function() {
        if ($(this).val() == '') {
            $(this).next().addClass('error').removeClass('valid');
        } else {
            $(this).next().addClass('valid').removeClass('error');
        }
    });

    $('.planretailer').change(function() {
        $('.identification').show();
        if ($(this).val() == 'Origin Energy' || $(this).val() == 'ERM') { // Hide Identification
            $('.identification').hide();
        }
        $('.identification_default_fields').hide();
        $('.identification_credit_check_fields').hide();
        $('.creditcheckdefaultmsg').hide();
        $('.creditcheckmomentummsg').hide();
        $('.creditchecklumomsg').hide();
        if ($(this).val() == 'Alinta Energy' || $(this).val() == 'Lumo Energy' || $(this).val() == 'Energy Australia' || $(this).val() == 'Momentum') {
            $('.identification_credit_check_fields').show();
            if ($(this).val() == 'Alinta Energy' && $('.customertype').val() == 'Residential' && $('.planstate').val() == 'VIC') {
                $('.identification_credit_check_fields').hide();
                $('.identification_default_fields').show();
            }
            if ($(this).val() == 'Momentum') {
                $('.creditcheckmomentummsg').show();
            } else if ($(this).val() == 'Lumo Energy') {
                $('.creditchecklumomsg').show();
            } else {
                $('.creditcheckdefaultmsg').show();
            }
        } else {
            $('.identification_default_fields').show();
        }
        if ($(this).val() == 'Lumo Energy') {
            $('.lumobilling').show();
            if ($('.planstate').val() == 'VIC' && ($('.planfuel').val() == 'Dual' || $('.planfuel').val() == 'Elec')) {
            }
            if ($('.planstate').val() == 'VIC' && ($('.planfuel').val() == 'Dual' || $('.planfuel').val() == 'Gas')) {
            }
            if ($('.planstate').val() == 'SA') {
            }
            if ($('.customertype').val() == 'Business') {
                $('.lumosmemsg').show();
            }
        }
        $('.aglbilling').hide();
        if ($(this).val() == 'AGL') {
            $('.aglbilling').show();
        }

        updateConcessionFields();
        updateSupplyFields();
    }).change();

    $('.customertype').change(function() {
        if ($(this).val() == 'Business') {
            if ($('.planretailer').val() == 'AGL' || $('.planretailer').val() == 'Alinta Energy') {
                $('.identification').hide(); // Hide Identification
            }
        } else {
            
        }
        updateConcessionFields();
    }).change();

    $('.lookingfor').change(function() {
        $('.MoveInDetails').hide();
        if ($(this).val() == 'Move In') {
            $('.MoveInDetails').show();
            return false;
        } else if ($(this).val() == 'Transfer') {
            return true;
        }
    }).change();

    $('.planfuel').change(function() {
        updateConcessionFields();
        updateSupplyFields();
       
        if ($(this).val() == 'Dual') {
            if ($('.lookingfor').val() == 'Move In') {
                
            }
        } else if ($(this).val() == 'Elec') {
            if ($('.lookingfor').val() == 'Move In') {
            }
        } else if ($(this).val() == 'Gas') {
            //$('.moveinunderstandandagree').hide();
            if ($('.lookingfor').val() == 'Move In') {
                
            }
        }
    }).change();

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
        "verifyDOB",
        function(value, element) {
            var nativeDateFormat = /^\d{4}-\d{2}-\d{2}$/; // mobile date field
            var datepickerDateFormat = /^\d{2}\/\d{2}\/\d{4}$/;
            if (nativeDateFormat.test(value)) {
                var dt = value.split("-");
                var birthYear = dt[0];
                var birthMonth = dt[1];
                var birthDay = dt[2];
            } else {
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

            if (parseInt(age) > 100 || parseInt(age) < 18) {
                return false;
            } else {
                return true;
            }
        },
        "Must be 18 years old or greater"
    );

    $('.signup_contact').validate({
        rules:{
            'Contact[first_name]' :{
                required:true,
                minlength:2
            },
            'Contact[last_name]' :{
                required:true,
                minlength:2
            },
            'Contact[dateofbirth]':{
                required:true,
                verifyDOB: true,
                //date:true
                //datecustom:true,
            },
            /*
            'Contact[email]' :{
                required:true,
                email:true,
                //verifyEmail:true
            },
            */
        },
        errorPlacement: function(error, element) { },
        //ignore: ".contactdetialinputstext",
    });

    $('.signup_telco_details').validate({
        rules:{
            "Telco[plan_type]":{
                required:true,
            },
        },
        errorPlacement: function(error, element) { },
        //ignore: ".contactdetialinputstext",
    });

    $('.signup_concession').validate({
        rules:{
            "Concession[household_require]":{
                required:true,
            },
        },
        errorPlacement: function(error, element) { },
        //ignore: ".contactdetialinputstext",
    });

    $('.signup_identification').validate({
        rules:{
            "Identification[document_type]" : {
                required:true,
                selectboxvalidation : true,
            },
            "Identification[document_expiry]":{
                required:true,
            },
            "Identification[document_state]":{
                required:true,
            },
            "Identification[document_country]":{
                required:true,
            },
            "Identification[name_on_card]":{
                required:true,
            },
            "Identification[card_number]":{
                required:true,
            },
            "Identification[card_start]":{
                required:true,
            },
            "Identification[card_expiry]":{
                required:true,
            },
        },
        errorPlacement: function(error, element) { },
        //ignore: ".contactdetialinputstext",
    });

    /*
	$('.signup_supply').validate({
		rules:{
			"Supply[suburb]":{
				required:true,
			},
			"Supply[postcode]":{
				required:true,
			},
			"Supply[state]":{
				required:true,
			},
			"Supply[renant_owner]":{
				required:true,
			},
			"Supply[nmi_acq_ret]":{
				required:true,
			},
			"Supply[mirn_acq_ret]":{
				required:true,
			},
			"SupplySecondary[suburb]":{
				required:true,
			},
			"SupplySecondary[postcode]":{
				required:true,
			},
			"SupplySecondary[state]":{
				required:true,
			},
		},
		errorPlacement: function(error, element) { },
		//ignore: ".contactdetialinputstext",
	});
	*/

    $('.signup_billinginfo').validate({
        rules:{
        },
        errorPlacement: function(error, element) { },
        //ignore: ".contactdetialinputstext",
    });

    $('.signup_moveindetail').validate({
        rules:{
            'MoveInDetail[connection_date]':{
                required:true,
            },
        },
        errorPlacement: function(error, element) { },
        //ignore: ".contactdetialinputstext",
    });

    $('.signup_final').validate({
        rules:{
            "Final[voice_verification_number]":{
                required:true,
            },
        },
        errorPlacement: function(error, element) { },
        //ignore: ".contactdetialinputstext",
    });

    $(document).on('click', '.checkbutton', function(e) {
        e.preventDefault();
        var dd = $(this).parent();
        $(dd).find('button').each(function() {
            $(this).removeClass('select-btn');
        });
        $(this).addClass('select-btn');
        $(dd).find('label').attr({'style':"color:#777777 !important"});
    });

    $('.lookup-btn').click(function() {
        var abn = $('#abn').val();
        window.open('http://abr.business.gov.au/SearchByABN.aspx?SearchText=' + abn,'windowname1','width=1024, height=768'); return false;
    });

    $('.movein-fees-btn').click(function() {
        window.open('https://docs.google.com/spreadsheets/d/1DcJrAGh9TgiCRO4rmy86e9YXl20z7_3fpL-Kbnt7SeY/edit#gid=0','windowname1','width=1024, height=768'); return false;
    });

    $('.noemail-btn').click(function(e) {
        e.preventDefault();
        $('#contact_email').val('no@email.com.au');
        if ($('.planretailer').val() == 'AGL' || $('.planretailer').val() == 'Powerdirect' || $('.planretailer').val() == 'Powerdirect and AGL') {

        } else if ($('.planretailer').val() == 'Energy Australia') {
            
        } else if ($('.planretailer').val() == 'Origin Energy') {
            
        } else if ($('.planretailer').val() == 'Lumo Energy') {
            
        } else if ($('.planretailer').val() == 'Sumo Power' && ($('.planstate').val() == 'VIC' || $('.planstate').val() == 'NSW')) {
            
        } else if ($('.planretailer').val() == 'Next Business Energy') {
            
        } else if ($('.planretailer').val() == 'Momentum') {
            
        }
    });
});

$(document).ready(function() {
    $('form').submit(function() {
        if ($(this).attr('id') == 'signup_complete' && $('#final_form_checked').val() == 1) { // Signup Form
            $("#process_modal").modal('show');
            $('#signup_complete_submit').attr('disabled','disabled');
            //$('#signup_complete_submit').val('Re-Submit Form');
            var id = $('#id').val();
            var action = $('#action').val();
            var data = [];
            if ($('#plan_form_checked').val() == 1) {
                data.push($('.signup_plan').serialize());
            }
            if ($('#contact_form_checked').val() == 1) {
                data.push($('.signup_contact').serialize());
            }
            if ($('#telco_details_form_checked').val() == 1) {
                data.push($('.signup_telco_details').serialize());
            }
            if ($('#identification_form_checked').val() == 1) {
                data.push($('.signup_identification').serialize());
            }
            if ($('#concession_form_checked').val() == 1) {
                data.push($('.signup_concession').serialize());
            }
            if ($('#supply_form_checked').val() == 1) {
                data.push($('.signup_supply').serialize());
            }
            if ($('#billinginfo_form_checked').val() == 1) {
                data.push($('.signup_billinginfo').serialize());
            }
            if ($('#moveindetail_form_checked').val() == 1) {
                data.push($('.signup_moveindetail').serialize());
            }
            data.push($('.signup_final').serialize());
            $.post('/admin/customers/signup_telco/'+id, data.join('&'), function(data) {
                $("#process_modal").modal('hide');
                var lead_id_html = '';
                var lead_ids = data.split('-');
                $.each(lead_ids, function(i) {
                    lead_id_html += '<a href="https://lm.velocify.com/Web/LeadAddEdit.aspx?LeadId='+lead_ids[i]+'" target="_blank">'+lead_ids[i]+'</a> ';
                });
                $('#complete_dialog_lead_id').html(lead_id_html);
                $("#complete_modal").modal('show');
            });

        }  else if ($(this).attr('id') == 'view_lead') { // View form
            var lead_id = $('#view_lead_id').val();
            var mobile = $('#view_lead_mobile').val();
            if (!lead_id && !mobile) {
                alert('Velocify Lead ID or Phone Number is required');
                return false;
            }
            return true;
        } else if ($(this).attr('id') == 'update_lead') { // Fix Invalid Email form
            var lead_id = $('#update_lead_id').val();
            var email = $('#email').val();
            if (!lead_id || !email) {
                alert('Velocify Lead ID and Email are required');
                return false;
            }
            return true;
        } else if ($(this).attr('id') == 'update_telco_lead') { // Telco form
            var lead_id = $('#telco_lead_id').val();
            if (!lead_id) {
                alert('Telco Lead ID is required');
                return false;
            }
            return true;
        }
        return false;
    });
    $('.datepickerDateOfBirth').datepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
        startDate: '-100y',
        endDate: '-18y'
    }).on('changeDate', function (ev) {
        $('.signup_contact').validate().element(this);
    });
    $("#datepickerDateOfBirth").mask("99/99/9999",{ placeholder: "DD/MM/YYYY" });

    $('#identification_document_expiry').datepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
    }).on('changeDate', function (ev) {
        $('.signup_identification').validate().element(this);
    });
    $("#identification_document_expiry").mask("99/99/9999",{ placeholder: "DD/MM/YYYY" });

    $('#connection_date').datepicker({
        daysOfWeekDisabled: [0, 6],
        startDate: '+0d',
        format: "dd/mm/yyyy",
        autoclose: true,
    }).on('changeDate', function (ev) {
        $('.signup_moveindetail').validate().element(this);
    });
    $("#connection_date").mask("99/99/9999",{ placeholder: "DD/MM/YYYY" });

    $(document).on("keyup", '.street_type', function(event) {
        street_type_lookup(this);
    });
    $('#sales_rep_name').bind('keyup', function(){
        sales_rep_lookup();
    });
    $('.eicpdf').change(function() {
        var filename = $(this).val();
        if (filename) {
            window.open('https://tools.electricitywizard.com.au/pdf/'+filename);
        }
    });
    $('.add-a-gas-product').click(function() {
        $("#complete_modal").modal('hide');
        var id = $('#id').val();
        window.location.replace("https://tools.electricitywizard.com.au/v4/");
    });
    $('.signup-another-property').click(function() {
        $("#complete_modal").modal('hide');
        window.location.replace("https://tools.electricitywizard.com.au/v4/");
    });
    $('.no-thanks').click(function() {
        $("#complete_modal").modal('hide');
        window.location.replace("https://tools.electricitywizard.com.au/v4/");
    });

    $('#contact_email').blur(function() {
        var reg = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        var email = $('#contact_email').val();
        
        if (email && reg.test(email) && email != 'no@email.com.au') {
            if ($('.planretailer').val() == 'AGL') {
                
            } else if ($('.planretailer').val() == 'Powerdirect') {
                
            } else if ($('.planretailer').val() == 'Powerdirect and AGL') {
                
            } else if ($('.planretailer').val() == 'Origin Energy') {

            } else if ($('.planretailer').val() == 'Sumo Power') {
               
            } else if ($('.planretailer').val() == 'Energy Australia') {
               
            } else if ($('.planretailer').val() == 'ERM') {
                
            } else if ($('.planretailer').val() == 'Lumo Energy') {
                
            } else if ($('.planretailer').val() == 'Next Business Energy') {

            } else if ($('.planretailer').val() == 'Momentum') {
                
            }
        } else {
            if ($('.planretailer').val() == 'AGL' || $('.planretailer').val() == 'Powerdirect' || $('.planretailer').val() == 'Powerdirect and AGL') {
                
            } else if ($('.planretailer').val() == 'Energy Australia') {
                
            } else if ($('.planretailer').val() == 'Origin Energy') {
                
            } else if ($('.planretailer').val() == 'Lumo Energy') {
                
            } else if ($('.planretailer').val() == 'Sumo Power' && ($('.planstate').val() == 'VIC' || $('.planstate').val() == 'NSW')) {
                
            } else if ($('.planretailer').val() == 'Next Business Energy') {
                
            } else if ($('.planretailer').val() == 'Momentum') {
                
            }
        }
    });

    var html_btn_delete = '<a class="repeater-delete pull-right" href="javascript:void(0);"><i class="fa fa-fw fa-trash-o"></i></a>';
    var html_dragger = '<a class="repeater-dragger" href="javascript:void(0);"><i class="fa fa-fw fa-arrows-v"></i></a>';

    $('.repeater').each( function() {
        //$(this).children('.repeater-field').append(html_btn_delete);
        //$(this).children('.repeater-field').append(html_dragger);
        $(this).sortable( {
            'handle': '.repeater-dragger',
            'draggable': '.repeater-field',
        } );
        $(this).parents('form').bind('submit', function(e) {
            //jQuery(this).find('.repeater .repeater-field-tpl').remove();
        });
        updateSupplyFields();
    });

    $(document).on('click', '.repeater-delete', function(e) {
        e.preventDefault();
        $(this).parents('.repeater-field').fadeOut('fast', function() {
            $(this).remove();
        });
        return false;
    });

    $(document).on('click', '.repeater-add', function(e) {
        e.preventDefault();
        $tpl = $(this).parents('.repeater').children('.repeater-field-tpl');
        $(this).parents('.repeater').children('.repeater-field-tpl')
            .before('<div class="repeater-field">'
                + $tpl.html().replace(/\{\{index\}\}/ig, randomString())
                + html_btn_delete
                //+ html_dragger
                + '</div>'
            );
        updateSupplyFields();
        return false;
    });
});

rand = function( number ) {
    if ( undefined === number )
        number = 100;
    return parseInt( Math.random() * number );
};

randomString = function( length ) {
    if ( undefined === length )
        length = 16;
    chars = '0123456789abcdefghijklmnopqrstuvwxyz';
    output = '';
    output += chars[ 10 + rand( chars.length - 10 ) ];
    for ( var i = 1; i <= length - 1; i++ ) {
        output += chars[ rand( chars.length ) ];
    };
    return output;
};

window.OpenTabDetail = {
    'form_data_supply' : {'flag':false,'detail':'form_detail_supply','data':'form_data_supply'},
    'form_data_final': {'flag':false,'detail':'form_detail_final','data':'form_data_final'},
    'form_data_identification':{'flag':false,'detail':'form_detail_identification','data':'form_data_identification'},
    'form_data_contact':{'flag':true,'detail':'form_detail_contact','data':'form_data_contact'}
};

window.divFormdone = {
    'form_detail_supply' : false,
    'form_detail_final': false,
    'form_detail_identification': false,
    'form_detail_contact':true
};

updateConcessionFields = function() {
    /*
    $('.validconcessionhouseholdrequire').hide();
    if ($('.customertype').val() == 'Business' && ($('.planfuel').val() == 'Gas' || $('.planretailer').val() == 'Alinta Energy')) { // Hide Concession
        $('.concession').hide();
    } else {
        $('.concession').show();
        $('.validconcessionhouseholdrequire').show();
    }
    */
    $('.concession').show();
    $('.validconcessionhouseholdrequire').show();
}

updateSupplyFields = function() {
    $('.moveinelectricitymeter').show();
    $('.moveingasmeter').show();
    $('.msatsisdifferent').hide();
    $('.aglsupply').hide();
    $('.moveinelecconnectionfeetype').show();
    if ($('.planfuel').val() == 'Elec') {
        $('.moveingasmeter').hide();
    } else if ($('.planfuel').val() == 'Gas') {
        $('.moveinelectricitymeter').hide();
        $('.moveinelecconnectionfeetype').hide();
    } else if ($('.planfuel').val() == 'Dual') {
        
    }
    if ($('.planretailer').val() == 'AGL') {
        $('.aglsupply').show();
    }
}

creditcheck = function(arg) {
    $('.identification_default_fields').show();
    if (arg) {
        $('.IdentificationCreditCheck').val(1);
        if ($('.planretailer').val() == 'Lumo Energy' || $('.planretailer').val() == 'Alinta Energy' || $('.planretailer').val() == 'Momentum' || $('.planretailer').val() == 'Energy Australia') {
            //$('.identification_credit_check_fields').hide();
            $('.creditcheckno').hide();
        }
    } else {
        $('.IdentificationCreditCheck').val(0);
        $('.identification_default_fields').hide();
        if ($('.planretailer').val() == 'Lumo Energy' || $('.planretailer').val() == 'Alinta Energy' || $('.planretailer').val() == 'Energy Australia' || $('.planretailer').val() == 'Momentum') {
            //$('.identification_credit_check_fields').show();
            $('.creditcheckno').show();
        }
    }
    $('.IdentificationCreditCheck').parent().find('label').attr({'style':"color:#777777 !important"});
}

checkSupplySecondary = function(arg) {
    if (arg) {
        $('.secondary-supply').show();
        $('.BillingAddressDifferent').val(1);
    } else {
        $('.secondary-supply').hide();
        $('.BillingAddressDifferent').val(0);
        $('.secondary-supply .secondary_input').val('');
        $('.secondary-supply .secondary_input').removeClass('valid');
        $('.secondary-supply .secondary_input').removeClass('error');
    }
}

checkboxhide = function(arg) {
    if ($(arg).is(":checked")) { $(arg).parent().next().hide(); } else { $(arg).parent().next().show(); }
}

ebilling = function(arg) {
}

confirmannualgas = function(arg) {
    if (arg == 1) {
        $('.confirmannualgasno').hide();
    } else {
        $('.confirmannualgasno').show();
    }
}

visualinspectionconfirm = function(arg) {
    var selection1 = '<select class="movein_visual_inspection selectpicker show-menu-arrow" name="MoveInDetail[visual_inspection]">';
    selection1 += '<option value="">Select</option>';
    selection1 += '<option value="Visual Inspection required 8am - 1pm: Customer on premises">Visual Inspection required 8am - 1pm: Customer on premises</option>';
    selection1 += '<option value="Visual Inspection required 1pm - 6pm: Customer on premises">Visual Inspection required 1pm - 6pm: Customer on premises</option>';
    selection1 += '</select>';
    var selection2 = '<select class="movein_visual_inspection selectpicker show-menu-arrow" name="MoveInDetail[visual_inspection]">';
    selection2 += '<option value="">Select</option>';
    selection2 += '<option value="Visual Inspection required 8am - 1pm: Key left in meter box">Visual Inspection required 8am - 1pm: Key left in meter box</option>';
    selection2 += '<option value="Visual Inspection required 1pm - 6pm: Key left in meter box">Visual Inspection required 1pm - 6pm: Key left in meter box</option>';
    selection2 += '</select>';
    if (arg == 1) {
        $('.visualinspectionconfirmyes').show();
        $('.visualinspectionconfirmno').hide();
        $('.MoveInDetailVisualInspectionconConfirm').val(1);
        $('.movein_visual_inspection_selection').html(selection1);
    } else {
        $('.visualinspectionconfirmyes').hide();
        $('.visualinspectionconfirmno').show();
        $('.MoveInDetailVisualInspectionconConfirm').val(0);
        $('.movein_visual_inspection_selection').html(selection2);
    }
    $('.movein_visual_inspection').selectpicker('refresh');
    $('.visualinspectionconfirmdetails').show();
}

elec_connection_fee_type = function(arg, arg1) {
    $('.moveinsdfisa').hide();
    if (arg) {
        $(arg1).parent().find('input:hidden').val('SDFI');
        if ($('.planstate').val() == 'SA') {
            $('.moveinsdfisa').show();
        }
    } else {
        $(arg1).parent().find('input:hidden').val('Normal');
    }
}

meterhazard = function(arg) {
    if (arg == 1) {
        if ($('.planretailer').val() == 'Momentum') {
            $('.moveinhazardsmomentum').show();
        } else {
            $('.moveinhazards').show();
        }
    } else {
        if ($('.planretailer').val() == 'Momentum') {
            $('.moveinhazardsmomentum').hide();
        } else {
            $('.moveinhazards').hide();
        }
    }
}

sumopowerunderstandform = function(arg) {
    if (arg) {
        $('.sumopowerunderstandformno').hide();
    } else {
        $('.sumopowerunderstandformno').show();
    }
}

openTab = function(arg,arg1) {
    for(key in window.OpenTabDetail )
    {
        if (window.OpenTabDetail[key].data == arg1) {
            window.OpenTabDetail[key].flag = true;
            $("."+window.OpenTabDetail[key].data).slideDown();
            $(arg).slideUp();
        } else {
            window.OpenTabDetail[key].flag = false;
            $("."+window.OpenTabDetail[key].data).slideUp();
            if ($("."+window.OpenTabDetail[key].detail).hasClass('doneform')) {
                $("."+window.OpenTabDetail[key].detail).slideDown();
            } else {
                $("."+window.OpenTabDetail[key].detail).slideUp();
            }
        }
    }
}

checkselectbox = function(arg) {
    if ($(arg).val() == '' ) {
        $(arg).addClass('error').removeClass('valid');
        return false;
    }
    $(arg).addClass('valid').removeClass('error');
    return true;
}

checkPlanFields = function (arg) {
    flag = true;
    if (!checkselectbox('.planstate')) {
        flag = false;
    }
    if (!checkselectbox('.planretailer')) {
        flag = false;
    }
    if (!checkselectbox('.customertype')) {
        flag = false;
    }
    if (!checkselectbox('.lookingfor')) {
        flag = false;
    }
    if (!checkselectbox('.planfuel')) {
        flag = false;
    }
    if ($('.metertype').is(":visible")) {
        if (!checkselectbox('.selectmetertype')) {
            flag = false;
        }
    }
    if (!checkselectbox('.selectpropertytype')) {
        flag = false;
    }
    if (arg === undefined ) {
        arg = false;
    }
    if (arg) {
        return flag;
    }
    if (flag) {
        $('#plan_form_checked').val(1);
        dataOptions.count = 1;
        $('.contactdetialinputstext').removeClass('error').removeClass('valid');
        $('#accordion').accordion({active:1});
        return true;
    } else {
        $('#plan_form_checked').val(0);
        return false;
    }
}

checkContactFields = function (arg) {
    flag = true;
    if (!checkYesNoValidation('.contact_authorised')) {
        flag = false;
    }
    if (!checkselectbox('.selectcontacttitle')) {
        flag = false;
    }
    if (!$('.signup_contact').valid()) {
        flag = false;
    }
    if ($('#contact_mobile').val() == '' && $('#contact_home_phone').val() == '') {
        $('#contact_mobile').addClass('error').removeClass('valid');
        $('#contact_home_phone').addClass('error').removeClass('valid');
        flag = false;
    }  else {
        if ($('#contact_mobile').val()) {
            if (!(/^(04)\d{8}$/.test($('#contact_mobile').val()))) {
                $('#contact_mobile').addClass('error').removeClass('valid');
                flag = false;
            } else {
                $('#contact_mobile').addClass('valid').removeClass('error');
            }
            if ($('#contact_home_phone').val() == '') {
                $('#contact_home_phone').removeClass('error');
            }
        }
        if ($('#contact_home_phone').val()) {
            if (!(/^(02|03|07|08)\d{8}$/.test($('#contact_home_phone').val()))) {
                $('#contact_home_phone').addClass('error').removeClass('valid');
                flag = false;
            } else {
                $('#contact_home_phone').addClass('valid').removeClass('error');
            }
            if ($('#contact_mobile').val() == '') {
                $('#contact_mobile').removeClass('error');
            }
        }
    }
    
    var reg = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if ($('.planretailer').val() == 'Powershop' || $('#plan_product_name').val() == 'Smile Power Plus' || $('.planretailer').val() == 'Next Business Energy') {
        var email = $('#contact_email').val();
        if (!email || !reg.test(email) || email == 'no@email.com.au') {
            $('#contact_email').addClass('error').removeClass('valid');
            flag = false;
        } else {
            $('#contact_email').addClass('valid').removeClass('error');
        }
    } else {
        var email = $('#contact_email').val();
        if (!email || !reg.test(email)) {
            $('#contact_email').addClass('error').removeClass('valid');
            flag = false;
        } else {
            $('#contact_email').addClass('valid').removeClass('error');
        }
    }
    if (arg === undefined ) {
        arg = false;
    }
    if (arg) {
        return flag;
    }
    if (flag) {
        $('#contact_form_checked').val(1);
        dataOptions.count = 2;
        $('#accordion').accordion({active:2});
        return true;
    } else {
        $('#contact_form_checked').val(0);
        return false;
    }
}

checkTelcoDetailsFields =  function(arg) {
    var flag = true;
    if (!$('.signup_telco_details').valid()) {
        flag = false;
    }
    
    if (!checkselectbox('.selectTelcoPlanType')) {
        flag = false;
    }
    if (!checkselectbox('.selectTelcoNBNSpeed')) {
        flag = false;
    }
    if (!checkselectbox('.selectTelcoPrice')) {
        flag = false;
    }
    if (!checkselectbox('.selectTelcoNBNSpeed')) {
        flag = false;
    }
    if (!checkselectbox('.selectTelcoPaymentType')) {
        flag = false;
    }
    if ($('#telco_pay_and_submit').val() == '') {
        $('#telco_pay_and_submit').addClass('error').removeClass('valid');
        flag = false;
    } else {
        $('#telco_pay_and_submit').addClass('valid').removeClass('error');
    }
    if (!(/^\d+$/.test($('#telco_pre_auth_submit').val()))) {
        $('#telco_pre_auth_submit').addClass('error').removeClass('valid');
        flag = false;
    } else {
        $('#telco_pre_auth_submit').addClass('valid').removeClass('error');
    }
    if ($('#telco_na').val() == '') {
        $('#telco_na').addClass('error').removeClass('valid');
        flag = false;
    } else {
        $('#telco_na').addClass('valid').removeClass('error');
    }
    if ($('#telco_retailer').val() == '') {
        $('#telco_retailer').addClass('error').removeClass('valid');
        flag = false;
    } else {
        $('#telco_retailer').addClass('valid').removeClass('error');
    }
    
    if (arg === undefined ) {
        arg = false;
    }
    if (arg) {
        return flag;
    }
    if (flag) {
        $('#telco_details_form_checked').val(1);
        if ($('.planretailer').val() == 'Origin Energy' || $('.planretailer').val() == 'ERM' || ($('.planretailer').val() == 'AGL' && $('.customertype').val() == 'Business') || ($('.planretailer').val() == 'Alinta Energy' && $('.customertype').val() == 'Business') || ($('.planretailer').val() == 'Next Business Energy' && $('#business_type').val() != 'Sole Trader')) { // Hide Identification
            /*
			if ($('.customertype').val() == 'Business' && ($('.planfuel').val() == 'Gas' || $('.planretailer').val() == 'Alinta Energy')) { // Hide Concession 
				dataOptions.count = 5; 
				$('#accordion').accordion({active:5});
			} else {
				dataOptions.count = 4; 
				$('#accordion').accordion({active:4});
			}
			*/
            dataOptions.count = 4;
            $('#accordion').accordion({active:4});
        } else {
            dataOptions.count = 3;
            $('#accordion').accordion({active:3});
        }
    } else {
        $('#telco_details_form_checked').val(0);
    }
    return flag;
}

checkIdentificationFields = function(arg) {
    var flag = true;
    if (!$('.signup_identification').valid()) {
        flag = false;
    }
    if ($('.identification_default_fields').is(':visible')) {
        if (!checkselectbox('.selectIdentificationDocumentType')) {
            flag = false;
        }
        if (!checkselectbox('.selectIdentificationDS')) {
            flag = false;
        }
        if (!checkselectbox('.selectIdentificationDC')) {
            flag = false;
        }
        if ($('#document_type').val() == 'MED') {
            if (!/^\d+$/.test($('#document_id').val()) || $('#document_id').val().length != 11) {
                $('#document_id').addClass('error').removeClass('valid');
                flag = false;
            } else {
                $('#document_id').addClass('valid').removeClass('error');
            }
        } else {
            if ($('#document_id').val().length < 5) {
                $('#document_id').addClass('error').removeClass('valid');
                flag = false;
            } else {
                $('#document_id').addClass('valid').removeClass('error');
            }
        }
    }
    if ($('.identification_credit_check_fields').is(':visible')) {
        if (!checkYesNoValidation('.credit_check')) {
            flag = false;
        }
    }

    if (arg === undefined ) {
        arg = false;
    }
    if (arg) {
        return flag;
    }
    if (flag) {
        $('#identification_form_checked').val(1);
        /*
        if ($('.customertype').val() == 'Business' && ($('.planfuel').val() == 'Gas' || $('.planretailer').val() == 'Alinta Energy')) { // Hide Concession
            dataOptions.count = 5;
            $('#accordion').accordion({active:5});
        } else {
            dataOptions.count = 4;
            $('#accordion').accordion({active:4});
        }
        */
        dataOptions.count = 4;
        $('#accordion').accordion({active:4});
    } else {
        $('#identification_form_checked').val(0);
    }
    return flag;
}

checkConcessionFields = function(arg) {
    var flag = true;
    if (!$('.signup_concession').valid()) {
        flag = false;
    }
    if ($('.validconcessionhouseholdrequire').is(':visible')) {
        if (!checkYesNoValidation('.concession_household_require')) {
            flag = false;
        }
    }
    if (arg === undefined ) {
        arg = false;
    }
    if (arg) {
        return flag;
    }
    if (flag) {
        $('#concession_form_checked').val(1);
        dataOptions.count = 5;
        $('#accordion').accordion({active:5});
    } else {
        $('#concession_form_checked').val(0);
    }
    return flag;
}

checkSupplyFields = function(arg) {
    var flag = true;
    /*
    if (!$('.signup_supply').valid()) {
        flag = false;
    }
    */
    /*
    $('.nmisupply').each(function() {
        if ($(this).is(':visible')) {
            var nmi = $(this).find('.mni_field').val();
            //if (!(/^([A-Z]{2}|[0-9]{2})\d{8}$/.test(nmi))) {
            if (nmi.length != 11) {
                $(this).find('.mni_field').addClass('error').removeClass('valid');
                flag = false;
            } else {
                $(this).find('.mni_field').addClass('valid').removeClass('error');
            }
        }
    });
    $('.mirnsupply').each(function() {
        if ($(this).is(':visible')) {
            var mirn = $(this).find('.mirn_field').val();
            //if (!(/^(50|52|53|54)\d{8,9}$/.test(mirn))) {
            if (!(/^\d{10,11}$/.test(mirn))) {
                $(this).find('.mirn_field').addClass('error').removeClass('valid');
                flag = false;
            } else {
                $(this).find('.mirn_field').addClass('valid').removeClass('error');
            }
        }
    });
    */
    if (!checkselectbox('.selectSupplyState')) {
        flag = false;
    }
    if ($('.supplyunit').val()) {
        if ($('.supplyunit').parents().eq(2).find('.supplyunittype').val() == '') {
            $('.supplyunit').parents().eq(2).find('.supplyunittype').parent().find('label').attr({'style':"color:#F00 !important"});
            flag = false;
        } else {
            $('.supplyunit').parents().eq(2).find('.supplyunittype').parent().find('label').attr({'style':"color:#777777 !important"});
        }
    }
    if (arg === undefined ) {
        arg = false;
    }
    if (arg) {
        return flag;
    }
    if (flag) {
        $('#supply_form_checked').val(1);
        dataOptions.count = 6;
        $('#accordion').accordion({active:6});
    } else {
        $('#supply_form_checked').val(0);
    }
    return flag;
}

checkBillingInformation = function(arg) {
    var flag = true;
    if (!$(".signup_billinginfo").valid()) {
        flag = false;
    }
    if (!checkselectbox('.residentialduration')) {
        flag = false;
    }
      
    if (arg === undefined ) {
        arg = false;
    }
    if (arg) {
        return flag;
    }
    if (flag) {
        $('#billinginfo_form_checked').val(1);
        if ($('.lookingfor').val() == 'Move In') {
            dataOptions.count = 7
            $('#accordion').accordion({active:7});
        } else {
            dataOptions.count = 8
            $('#accordion').accordion({active:8});
        }
    } else {
        $('#billinginfo_form_checked').val(0);
    }
    return flag;
}

checkMoveInDetails = function(arg) {
    var flag = true;
    if (!$(".signup_moveindetail").valid()) {
        flag = false
    }
    
    if ($('#connection_date').val() == '') {
        $('#connection_date').addClass('error').removeClass('valid');
        flag = false;
    } else {
        $('#connection_date').addClass('valid').removeClass('error');
    }

    if (arg === undefined ) {
        arg = false;
    }
    if (arg) {
        return flag;
    }
    if (flag) {
        $('#moveindetail_form_checked').val(1);
        dataOptions.count = 8
        $('#accordion').accordion({active:8});
    } else {
        $('#moveindetail_form_checked').val(0);
    }
    return flag;
}

checkFinalFields = function(arg) {
    var flag = true;
    if (!$('.signup_final').valid()) {
        flag = false
    }
    if ($('#sales_rep_name').val() == '' || $('#sales_rep_name').val() == 'Mr Pool') {
        $('#sales_rep_name').addClass('error').removeClass('valid');
        flag = false;
    }
    if (!checkselectbox('#final_campaign_id')) {
        flag = false;
    }
    if ($('#final_voice_verification_number').val() == '') {
        $('#final_voice_verification_number').addClass('error').removeClass('valid');
        flag = false;
    } else {
        $('#final_voice_verification_number').addClass('valid').removeClass('error');
    }

    if (arg === undefined ) {
        arg = false;
    }
    if (arg) {
        return flag;
    }
    if (flag) {
        $('#final_form_checked').val(1);
        dataOptions.count = 9
        if ($(".checkFinalFields").parent().parent().parent().parent().prev().hasClass('final')) {
            $(".checkFinalFields").parent().parent().parent().parent().slideUp();
            $(".checkFinalFields").parent().parent().parent().parent().prev()
                .removeClass('ui-accordion-header-active').removeClass('ui-state-active')
                .addClass('ui-accordion-header').addClass('ui-state-default').addClass('active');
        }
    } else {
        $('#final_form_checked').val(0);
    }
    return flag;
}

checkValidation = function(arg) {
    switch(arg) {
        case 0:
            break;
        case 1:
            break;
        case 2:
            break;
        default:
    }
}

concessionFormValidation = function() {
    $('.SA').addClass('display_hide');
    $('.QLD').addClass('display_hide');
    $('.VIC').addClass('display_hide');
    $('.NSW').addClass('display_hide');
    $('.Momentum').addClass('display_hide');
    $('.SumoPower').addClass('display_hide');
    $('.concession-card-fields').hide();

    var state = $('.planstate').val();

    if (state) {
        $('.'+state).removeClass('display_hide');
    }

    if ($('.planretailer').val() == 'Momentum' && state != 'SA') {
        $('.Momentum').removeClass('display_hide');
        dataOptions.FlagConcession = true;
    } else if ($('.planretailer').val() == 'Sumo Power') {
        if (state == 'VIC' || state == 'NSW') {
            $('.SumoPower').removeClass('display_hide');
        }
    } else {
        if (state == 'ACT') {
            dataOptions.FlagConcession = true;
            act_clear();
        }
        else if (state == 'SA') {
            dataOptions.FlagConcession = true;
            sa_clear();
        }
        else if (state == 'QLD') {
            dataOptions.FlagConcession = false;
            qld_clear();
        }
        else if (state == 'VIC') {
            dataOptions.FlagConcession = false;
            vic_clear();
        }
        else if (state == 'NSW') {
            dataOptions.FlagConcession = false;
            nsw_clear();
        }
    }
}

checkYesNoValidation = function(arg) {
    valid = false;
    $(arg).each(function(value) {
        if ($(this).hasClass('select-btn')) {
            if ($(this).text() == 'Yes') {
                $(this).parent().find('input:hidden').val(1);
            } else if ($(this).text() == 'No') {
                $(this).parent().find('input:hidden').val(0);
            }
            valid = true;
            if ($(this).parent().hasClass('required-yes') && $(this).text() == 'No') {
                valid = false;
            }
            if ($(this).parent().hasClass('required-no') && $(this).text() == 'Yes') {
                valid = false;
            }
        }
    });

    if (!valid) {
        $(arg).parent().find('label').attr({'style':"color:#F00 !important"});
    } else {
        $(arg).parent().find('label').attr({'style':"color:#777777 !important"});
    }

    return valid;
}

activeClassCheck = function(arg,arg1) {
    switch(arg) {
        case 1:
            if (!checkPlanFields(true)) {
                $(arg1).removeClass('active');
            }
            break;
        case 2:
            if (!checkContactFields(true)) {
                $(arg1).removeClass('active');
            }
            break;
        case 3:
            if (!checkTelcoDetailsFields(true)) {
                $(arg1).removeClass('active');
            }
            break;
        case 4:
            if (!checkIdentificationFields(true)) {
                $(arg1).removeClass('active');
            }
            break;
        case 5:
            if (!checkConcessionFields(true)) {
                $(arg1).removeClass('active');
            }
            break;
        case 6:
            if (!checkSupplyFields(true)) {
                $(arg1).removeClass('active');
            }
            break;
        case 7:
            if (!checkBillingInformation(true)) {
                $(arg1).removeClass('active');
            }
            break;
        case 8:
            if (!checkMoveInDetails(true)) {
                $(arg1).removeClass('active');
            }
            break;
        case 9:
            if (!checkFinalFields(true)) {
                $(arg1).removeClass('active');
            }
            break;
        case 10:
            if (!checkPlanFields(true)) {
                $(arg1).removeClass('active');
            }
            break;
        case 11:
            break;
        default:

    }
}

street_type_lookup = function(el) {
    if ($(el).val() == "") {
        return;
    }
    $(el).autocomplete({
        source: function( request, response ) {
            $.ajax({
                url: "/customers/street_type",
                dataType: "jsonp",
                type: "GET",
                contentType: "application/json; charset=utf-8",
                data: {term:$(el).val()},
                success: function( data ) {
                    response( $.map( data.items, function( item ) {
                        return {
                            label: item.name,
                            value: item.name
                        }
                    }));
                }
            });
        },
        delay:5,
        minLength: 1,
        select: function( event, ui ) {
        },
        change: function (event, ui) {
            if (ui.item == null || ui.item == undefined) {
                $(el).val('');
            }
        }
    });
}

sales_rep_lookup = function() {
    if ($('#sales_rep_name').val() == "") {
        return;
    }
    $('#sales_rep_name').autocomplete({
        source: function( request, response ) {
            $.ajax({
                url: "/customers/sales_rep",
                dataType: "jsonp",
                type: "GET",
                contentType: "application/json; charset=utf-8",
                data: {term:$('#sales_rep_name').val()},
                success: function( data ) {
                    response( $.map( data.items, function( item ) {
                        return {
                            label: item.name,
                            value: item.name,
                            email: item.email
                        }
                    }));
                }
            });
        },
        delay:5,
        minLength: 1,
        select: function( event, ui ) {
            $('#sales_rep_email').val(ui.item.email);
        },
        change: function (event, ui) {
            if (ui.item == null || ui.item == undefined) {
                $('#sales_rep_name').val('');
            }
        }
    });
}

