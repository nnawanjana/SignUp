var dataOptions = {};
dataOptions.current = {'plan':true,'contact':true,'supply':false,'identification':false,
    'supply':false,'final':true,'bussiness':false,'flagBussiness':false,};
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
                if ($( this ).attr('id') == 'ui-id-5') {
                    $('.business_details').hide();
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

    lead_lookup = function() {
        $.post('/customers/get_lead_fields', {lead_id: $('#plan_lead_id').val()}, function(data) {
            if (!data.first_name) {
                alert('Lead not found');
                $('#plan_lead_id').val('');
                return false;
            }
            var reg = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            var import_data = true;
            var overwrite = true;
            var exclude_status = ["102", "76", "137", "164", "130", "170", "197", "200", "212", "254", "286", "298", "97", "69", "64", "12", "285", "165", "131", "58", "50", "171", "62", "92", "61", "44", "191", "213", "258", "294", "10", "20", "51", "81", "77"];
            var campaign_id_arr = ["47", "62", "66", "67", "68"];
            var campaign_id_arr2 = ["1", "76", "77"];
            if ($.inArray(data.campaign_id, campaign_id_arr) != -1) {
                if ($.inArray(data.status, exclude_status) == -1 && data.sale_completion_date == '') {
                    alert('This is a recontracting lead and will be updated upon form completion');
                } else {
                    alert('This is an existing sale and cannot be overwritten, will import this data for ' + data.first_name);
                    overwrite = false;
                }
            } else {
                if (data.sale_completion_date != '') {
                    alert('This is an existing sale and cannot be overwritten, will import this data for ' + data.first_name);
                    overwrite = false;
                }
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
                    $('.agl_email_bill').hide();
                    $('.pd_email_bill').hide();
                    $('.pd_agl_email_bill').hide();
                    $('.origin_email_bill').hide();
                    $('.EA_email_bill').hide();
                    $('.EAnoemail').hide();
                    $('.erm_email_bill').hide();
                    $('.lumo_email_bill').hide();
                    $('.receivebillsbypost').hide();
                    $('.originemailbillyes').hide();
                    $('.originemailbillno').hide();
                    $('.originemailbillno2').hide();
                    $('.lumoemailbillno').hide();
                    $('.e_billing').show();
                    $('.sumovalidemail').hide();
                    $('.sumonoemail').hide();
                    $('.paperbill').hide();
                    $('.electronic').hide();
                    $('.welcomepackpost').hide();
                    $('.aglnoemail').hide();
                    $('.aglnoemailall').hide();
                    $('.actewaglnoemail').hide();
                    $('.nextbusinessnoemail').hide();
                    $('.momentumnoemail').hide();
                    $('.receive_an_email').hide();
                    $('.receive_no_email').hide();
                    $('.e_billing_momentum').hide();
                    $('.bluenrg_email_bill').hide();
                    $('.bluenrgnoemail').hide();
                    $('.simplyenergywelcomepack').hide();
                    $('.simplyenergynoemail').hide();
                    if (data.email && reg.test(data.email) && data.email != 'no@email.com.au') {
                        if ($('.planretailer').val() != 'Alinta Energy' && $('.planretailer').val() != 'ERM') {
                            $('.e_billing').hide();
                        }
                        if ($('.planretailer').val() == 'AGL') {
                            $('.agl_email_bill').show();
                        } else if ($('.planretailer').val() == 'Powerdirect') {
                            $('.pd_email_bill').show();
                        } else if ($('.planretailer').val() == 'Powerdirect and AGL') {
                            $('.pd_agl_email_bill').show();
                        } else if ($('.planretailer').val() == 'Origin Energy') {
                            if ($('#plan_product_name').val().indexOf('Origin Max Saver') === -1) {
                                $('.origin_email_bill').show();
                            }
                            if ($('.ContactOriginEmailBill').val() == 0) {
                                if ($('.planstate').val() != 'NSW' && $('.planstate').val() != 'VIC') {
                                    $('.originemailbillno').show();
                                } else {
                                    if ($('#plan_product_name').val().indexOf('Origin Max Saver') === -1) {
                                        $('.originemailbillno2').show();
                                    }
                                }
                            }
                        } else if ($('.planretailer').val() == 'Sumo Power') {
                            $('.sumovalidemail').show();
                        } else if ($('.planretailer').val() == 'Energy Australia') {
                            $('.EA_email_bill').show();
                        } else if ($('.planretailer').val() == 'ERM') {
                            $('.erm_email_bill').show();
                            $('.e_billing').hide();
                        } else if ($('.planretailer').val() == 'Lumo Energy') {
                            if (($('#plan_product_name').val().indexOf('Lumo Value') !== -1 || $('#plan_product_name').val().indexOf('Lumo Movers') !== -1) && $('.planstate').val() == 'VIC') {
                                $('.lumo_email_bill').show();
                            } else if ($('#plan_product_name').val().indexOf('Lumo Basic') !== -1) {
                                $('.lumo_email_bill').show();
                            } else {
                                $('.lumo_email_bill').hide();
                            }
                        } else if ($('.planretailer').val() == 'Next Business Energy') {

                        } else if ($('.planretailer').val() == 'Momentum') {
                            if ($('#plan_product_name').val().indexOf('Bill Boss') === -1) {
                                $('.e_billing_momentum').show();
                            }
                        } else if ($('.planretailer').val() == 'Blue NRG') {
                            $('.bluenrg_email_bill').show();
                        } else if ($('.planretailer').val() == 'Simply Energy') {
                            $('.simplyenergywelcomepack').show();
                            if ($('.planstate').val() != 'NSW') {
                                $('.simplyenergynoemail').hide();
                            }
                        }

                        if ($('.planretailer').val() == 'AGL' || $('.planretailer').val() == 'Powerdirect' || $('.planretailer').val() == 'Powerdirect and AGL' || $('.planretailer').val() == 'OVO Energy') {
                            $('.electronic').show();
                        }

                        $('.receive_an_email').show();
                    } else {
                        $('.e_billing').hide();
                        if ($('.planretailer').val() == 'AGL' || $('.planretailer').val() == 'Powerdirect' || $('.planretailer').val() == 'Powerdirect and AGL') {
                            if ($('.planstate').val() != 'NSW') {
                                $('.aglnoemail').show();
                            }
                            $('.aglnoemailall').show();
                        } else if ($('.planretailer').val() == 'Energy Australia') {
                            $('.EAnoemail').show();
                        } else if ($('.planretailer').val() == 'Origin Energy') {
                            if ($('.planstate').val() != 'NSW' && $('.planstate').val() != 'VIC') {
                                $('.originemailbillno').show();
                            } else {
                                if ($('#plan_product_name').val().indexOf('Origin Max Saver') === -1) {
                                    $('.originemailbillno2').show();
                                }
                            }
                            $('.welcomepackpost').show();
                        } else if ($('.planretailer').val() == 'Lumo Energy') {
                            if (!$('.lumo_email_bill_sa').is(":visible")) {
                                $('.lumoemailbillno').show();
                            }
                        } else if ($('.planretailer').val() == 'Sumo Power' && ($('.planstate').val() == 'VIC' || $('.planstate').val() == 'NSW')) {
                            $('.sumonoemail').show();
                            if ($('.planstate').val() == 'VIC') {
                                $('.paperbill').show();
                            }
                        } else if ($('.planretailer').val() == 'Next Business Energy') {
                            $('.nextbusinessnoemail').show();
                        } else if ($('.planretailer').val() == 'Momentum') {
                            $('.momentumnoemail').show();
                        } else if ($('.planretailer').val() == 'ActewAGL' && $('.planstate').val() != 'VIC' && $('.planstate').val() != 'SA') {
                            if ($('.actewaglnoemail').length > 0) {
                                $('.actewaglnoemail').show();
                            }
                        } else if ($('.planretailer').val() == 'Blue NRG') {
                            $('.bluenrgnoemail').show();
                        } else if ($('.planretailer').val() == 'Simply Energy') {
                            $('.simplyenergywelcomepack').hide();
                            if ($('.planstate').val() != 'NSW') {
                                $('.simplyenergynoemail').show();
                            }
                        }

                        $('.receive_no_email').show();
                    }
                }
                if (data.secondary_title) {
                    $('#contact_secondary_title').val(data.secondary_title);
                    $('#contact_secondary_title').selectpicker('refresh');
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
                    $('#final_campaign_id').val(data.campaign_id);
                    $('#final_campaign_name').val(data.campaign_name);
                }
                if (data.first_campaign) {
                    $('#final_first_campaign').val(data.first_campaign);
                }
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
                if (data.document_type) {
                    $('#document_type').val(data.document_type);
                    $('#document_type').selectpicker('refresh');
                }
                if (data.document_id) {
                    $('#document_id').val(data.document_id);
                }
                if (data.document_expiry) {
                    var identification_document_expiry_array = data.document_expiry.split("/");
                    var identification_document_expiry = identification_document_expiry_array[1] + '/' + identification_document_expiry_array[0] + '/' + identification_document_expiry_array[2];
                    $('#identification_document_expiry').val(identification_document_expiry);
                }
                if (data.document_state) {
                    $('#document_state').val(data.document_state);
                    $('#document_state').selectpicker('refresh');
                }
                if (data.document_country) {
                    $('#document_country').val(data.document_country);
                    $('#document_country').selectpicker('refresh');
                }
                /*
                if (data.tenant_owner) {
                    $('.supplytenantowner:first').val(data.tenant_owner);
                    if (data.tenant_owner == 'Renter') {
                        $('.supplytenant:first').addClass('select-btn');
                    } else {
                        $('.supplyowner:first').addClass('select-btn');
                    }
                }
                */
                if (data.company_industry) {
                    $('#company_industry').val(data.company_industry);
                    $('#company_industry').selectpicker('refresh');
                }
                if (data.vid) {
                    $('#final_vid').val(data.vid);
                }
                if (data.call_medium) {
                    $('#final_call_medium').val(data.call_medium);
                }
                alert('Data has been imported for ' + data.first_name);
                $('#lead_imported').val(1);
            } else {
                alert('OK, the data has NOT been imported');
                $('#lead_imported').val(-1);
            }
            if (!overwrite) {
                $('#plan_lead_id').val('');
            }
        });
    }

    $('#plan_lead_id').blur(function(){
        if ($(this).val()) {
            lead_lookup();
        } else {
            $('#plan_lead_id').addClass('valid').removeClass('error');
        }
    });
    if ($('#plan_lead_id').val()) {
        lead_lookup();
    }

    $('#update_lead_id').blur(function(){
        var lead_id = $(this).val();
        if (lead_id) {
            $.post('/customers/get_lead_fields', {lead_id: lead_id}, function(data) {
                if (!data.first_name) {
                    alert('Lead not found');
                    $('#update_lead_id').val('');
                    return false;
                }
                if (data.email) {
                    $('#email').val(data.email);
                }
                if (data.campaign_id) {
                    $('#campaign_id').val(data.campaign_id);
                }
            });
        }
    });

    $('select.selectpicker').change(function() {
        if ($(this).val() == '') {
            $(this).next().addClass('error').removeClass('valid');
        } else {
            $(this).next().addClass('valid').removeClass('error');
        }
    });

    $('.planstate').change(function() {
        $('.moveinnmistatus').hide();
        if ($('.planfuel').val() != 'Gas') {
            $('.moveinnmistatus').show();
        }
    }).change();

    $('.planretailer').change(function() {
        $('.identification').show();
        if ($(this).val() == 'Origin Energy' || $(this).val() == 'ERM') { // Hide Identification
            $('.identification').hide();
        }
        if ($('.planretailer').val() == 'Blue NRG' || $('#business_type').val() == 'Private' && $('.customertype').val() == 'Business') {
            $('.identification').hide(); // Hide Identification
        }
        $('.identification_default_fields').hide();
        $('.identification_credit_check_fields').hide();
        $('.agreetorates').hide();
        $('.lumobilling').hide();
        $('.lumovicmsg').hide();
        $('.lumovicmsg2').hide();
        $('.lumosamsg').hide();
        $('.lumosmemsg').hide();
        $('.creditcheckdefaultmsg').hide();
        $('.creditcheckmomentummsg').hide();
        $('.creditchecklumomsg').hide();
        $('.creditcheckalintamsg').hide();
    	$('.creditcheckaglmsg').hide();
        if ($(this).val() == 'Alinta Energy' || $(this).val() == 'Lumo Energy' || $(this).val() == 'Energy Australia' || $(this).val() == 'Momentum' || $(this).val() == 'AGL' || $(this).val() == 'Powerdirect') {
            $('.identification_credit_check_fields').show();
            if ($(this).val() == 'Alinta Energy') {
                $('.identification_credit_check_fields').show();
                $('.creditcheckalintamsg').show();
            } else if ($(this).val() == 'Momentum') {
                $('.creditcheckmomentummsg').show();
            } else if ($(this).val() == 'Lumo Energy') {
                $('.creditchecklumomsg').show();
            } else if ($(this).val() == 'AGL' || $(this).val() == 'Powerdirect') {
                $('.creditcheckaglmsg').show();
            } else {
                $('.creditcheckdefaultmsg').show();
            }
        } else {
            $('.identification_default_fields').show();
        }
        if ($(this).val() == 'Lumo Energy') {
            $('.lumobilling').show();
            $('.agreetorates').show();
            if ($('.planstate').val() == 'VIC' && ($('.planfuel').val() == 'Dual' || $('.planfuel').val() == 'Elec')) {
                $('.lumovicmsg').show();
            }
            if ($('.planstate').val() == 'VIC' && ($('.planfuel').val() == 'Dual' || $('.planfuel').val() == 'Gas')) {
                $('.lumovicmsg2').show();
            }
            if ($('.planstate').val() == 'SA') {
                $('.lumosamsg').show();
            }
            if ($('.customertype').val() == 'Business') {
                $('.lumosmemsg').show();
            }
            $('.lumo_email_bill_sa').hide();
            if (($('#plan_product_name').val().indexOf('Lumo Plus') !== -1 || $('#plan_product_name').val().indexOf('Lumo Movers') !== -1) && $('.planstate').val() == 'SA') {
                $('.lumo_email_bill_sa').show();
            }
        }

        $('.originpayataustraliapost').hide();
        if ($(this).val() == 'Origin Energy') {
            $('.agreetorates').show();
            if ($('.planstate').val() == 'VIC') {
                $('.originpayataustraliapost').show();
            }
        }

        $('.billingallowactewagluse').hide();
        if ($(this).val() == 'ActewAGL' && ($('.planstate').val() == 'ACT' || $('.planstate').val() == 'NSW')) {
            $('.billingallowactewagluse').show();
        }

        $('.consumptiondata').hide();
        $('.finalquoteid').hide();
        $('.howlonglivedmomentum').hide();
        if ($(this).val() == 'Momentum') {
            $('.consumptiondata').show();
            //$('.finalquoteid').show();
            if ($('.lookingfor').val() == 'Transfer') {
                $('.howlonglivedmomentum').show();
            }
        }
        $('.aglbilling').hide();
        if ($(this).val() == 'AGL') {
            $('.aglbilling').show();
        }
        $('.ermbilling').hide();
        if ($(this).val() == 'ERM') {
            $('.ermbilling').show();
        }
        $('.marketing_opt_out').hide();
        if ($(this).val() == 'Lumo Energy') {
            $('.marketing_opt_out').show();
        }

        $('.powershoptoken').hide();
        $('.billing_powershop_fields').hide();
        $('.billing_EA_fields').hide();
        $('.EA_VIC_DUAL').hide();
        $('.EA_VIC_ELEC').hide();
        $('.EA_VIC_GAS').hide();
        $('.moveinalterations').hide();
        $('.billing_default_fields').hide();
        if ($(this).val() == 'Powershop') {
            $('.powershoptoken').show();
            if ($('.planstate').val() == 'VIC' || $('.planstate').val() == 'SA') {
                $('.billing_powershop_fields').show();
            }
        } else if ($(this).val() == 'Energy Australia') {
            $('.billing_EA_fields').show();
            $('.eabillinghearfees').show();
            $('.eabillingmsg').show();
            if ($('.planstate').val() == 'VIC' && ($('.planfuel').val() == 'Dual' || $('.planfuel').val() == 'Elec')) {
                $('.moveinalterations').show();
            }
            if ($('.planstate').val() == 'VIC') {
                if ($('.planfuel').val() == 'Dual') {
                    $('.EA_VIC_DUAL').show();
                } else if ($('.planfuel').val() == 'Elec') {
                    $('.EA_VIC_ELEC').show();
                } else if ($('.planfuel').val() == 'Gas') {
                    $('.EA_VIC_GAS').show();
                }
            }
        } else {
            $('.billing_default_fields').show();
        }
        $('.e_billing').show();
        if ($(this).val() != 'Alinta Energy' && $(this).val() != 'ERM') {
            $('.e_billing').hide();
        }

        $('.alintabilling').hide();
        if ($(this).val() == 'Alinta Energy') {
            $('.alintabilling').show();
        }

        $('.sumobilling').hide();
        $('.sumomarketingoptout').hide();
        $('.sumoaustraliapost').hide();
        if ($(this).val() == 'Sumo Power' && ($('.planstate').val() == 'VIC' || $('.planstate').val() == 'NSW')) {
            $('.sumobilling').show();
            $('.sumomarketingoptout').show();
            $('.sumoaustraliapost').show();
        }
        $('.create-telco-lead').hide();
        $('.create-telco-form').hide();
        if ($(this).val() == 'Sumo Power') {
            $('.create-telco-lead').show();
            $('.create-telco-form').show();
        }

        $('.electronic').hide();
        $('.add-a-secondary-contact').show();
        if ($(this).val() == 'AGL' || $(this).val() == 'Powerdirect' || $(this).val() == 'Powerdirect and AGL' || $(this).val() == 'OVO Energy') {
            var reg = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            var email = $('#contact_email').val();
            if (email && reg.test(email) && email != 'no@email.com.au') {
                $('.electronic').show();
            }
            $('.add-a-secondary-contact').hide();
        }

        $('.nextbusinessebillingno').hide();
        if ($(this).val() == 'Next Business Energy') {
            $('.nextbusinessebillingno').show();
        }

        $('.basic_plan_info_agl').hide();
        $('.basic_plan_info_agl2').hide();
        if ($(this).val() == 'AGL' && $('.planstate').val() != 'VIC') {
            $('.basic_plan_info_agl').show();
        }
        if ($(this).val() == 'AGL' && $('.planstate').val() == 'VIC') {
            $('.basic_plan_info_agl2').show();
        }
        $('.basic_plan_info_powerdirect').hide();
        $('.basic_plan_info_powerdirect2').hide();
        if ($(this).val() == 'Powerdirect' && $('.planstate').val() != 'VIC') {
            $('.basic_plan_info_powerdirect').show();
        }
        if ($(this).val() == 'Powerdirect' && $('.planstate').val() == 'VIC') {
            $('.basic_plan_info_powerdirect2').show();
        }

        updateConcessionFields();
        updateSupplyFields();
    }).change();

    $('.customertype').change(function() {
        $('.business_details').hide();
        $('.businesssupply').hide();
        $('.validconcessioncard').hide();
        $('.company-position').hide();
        $('.alinta_secondary_account').hide();
        if ($(this).val() == 'Business') {
            $('.business_details').show();
            $('.businesssupply').show();
            $('.ValidConcession').val(1);
            if ($('.planretailer').val() == 'Alinta Energy') {
                if ($('#business_type').val() != 'Sole Trader') {
                    $('.identification').hide(); // Hide Identification
                }
            }
            if ($('.planretailer').val() == 'Blue NRG' || $('#business_type').val() == 'Private') {
                $('.identification').hide(); // Hide Identification
            }
            if ($('.planretailer').val() == 'Momentum' || $('.planretailer').val() == 'Next Business Energy') {
                $('.company-position').show();
            }
        } else {
            $('.validconcessioncard').show();
            $('.ValidConcession').val(0);

        }
        if ($('.planretailer').val() == 'Alinta Energy') {
            $('.alinta_secondary_account').show();
        }
        updateConcessionFields();
    }).change();

    $('.lookingfor').change(function() {
        $('.MoveInDetails').hide();
        $('.contactauthorised').hide();
        $('.sumomovein').hide();
        $('.lumocoolingoffperiod').hide();
        $('.popup_movein_text').hide();
        $('.moveincancellationfee').hide();
        $('.moveinanyhazards').hide();
        $('.alintamovein').hide();
        $('.alintamoveinvic').hide();
        if ($(this).val() == 'Move In') {
            $('.MoveInDetails').show();
            if ($('.planretailer').val() != 'Next Business Energy' && $('.planretailer').val() != 'ActewAGL') {
                $('.moveinanyhazards').show();
            }
            if ($('.planretailer').val() == 'Sumo Power' && ($('.planstate').val() == 'VIC' || $('.planstate').val() == 'NSW')) {
                $('.sumomovein').show();
            }
            $('.popup_movein_text').show();
            if ($('.planstate').val() == 'SA' && $('.planretailer').val() == 'Origin Energy') {
                $('.moveincancellationfee').show();
            }
            if ($('.planretailer').val() == 'Alinta Energy') {
                if ($('.planstate').val() == 'VIC') {
                    $('.alintamoveinvic').show();
                } else {
                    $('.alintamovein').show();
                }
            }

            return false;
        } else if ($(this).val() == 'Transfer') {
            $('.contactauthorised').show();

            if ($('.planretailer').val() == 'Lumo Energy') {
                $('.lumocoolingoffperiod').show();
            }

            return true;
        }
    }).change();

    $('.planfuel').change(function() {
        updateConcessionFields();
        updateSupplyFields();
        $('.fee_advised_dual').hide();
        $('.fee_advised_dual_vic').hide();
        $('.fee_advised_elec').hide();
        $('.fee_advised_elec_vic').hide();
        $('.fee_advised_gas').hide();
        $('.moveinmainswitchoff').hide();
        $('.moveinclearaccessgas').hide();
        $('.moveinclearaccessdual').hide();
        $('.tariffs').hide();
        $('.solarpanels').hide();
        $('.metertype').hide();
        $('.gas_momentum_invoice').hide();
        $('.gas_take_days').hide();
        $('.gas_help').hide();
        if ($(this).val() == 'Dual') {
            $('.tariffs').show();
            $('.solarpanels').show();
            if ($('.lookingfor').val() == 'Move In') {
                if ($('.planretailer').val() == 'Momentum') {
                    $('.gas_momentum_invoice').show();
                }
                if ($('.planretailer').val() != 'Momentum') {
                    $('.gas_take_days').show();
                    $('.gas_help').show();
                }
                if ($('.planstate').val() == 'VIC') {
                    $('.fee_advised_dual_vic').show();
                } else {
                    $('.fee_advised_dual').show();
                }

                $('.moveinclearaccessdual').show();
            }
            if ($('.planretailer').val() == 'Momentum') {
                $('.metertype').show();
            }
        } else if ($(this).val() == 'Elec') {
            $('.tariffs').show();
            $('.solarpanels').show();
            if ($('.lookingfor').val() == 'Move In') {
                if ($('.planstate').val() == 'VIC') {
                    $('.fee_advised_elec_vic').show();
                } else {
                    $('.fee_advised_elec').show();
                }
            }
            if ($('.planretailer').val() == 'Momentum') {
                $('.metertype').show();
            }
        } else if ($(this).val() == 'Gas') {
            //$('.moveinunderstandandagree').hide();
            if ($('.lookingfor').val() == 'Move In') {
                $('.fee_advised_gas').show();
                if ($('.planretailer').val() == 'Momentum') {
                    $('.gas_momentum_invoice').show();
                } else {
                    $('.gas_take_days').show();
                    $('.gas_help').show();
                }
                $('.moveinclearaccessgas').show();
            }
        }
    }).change();

    $('.gas_help').click(function() {
        $('.gas_help_text').removeClass('display_hide');
    });

    $('.movein_nmi_status').change(function() {
        $('.moveinvisualinspection').hide();
        $('.moveinmainswitchoff').hide();
        $('.moveinundersandmainswitchoff').hide();
        $('.moveinunderstandclearaccessvicsa').hide();
        $('.moveinunderstandclearaccessactnsw').hide();
        $('.moveinunderstandclearaccessactnsw2').hide();
        $('.moveinundersandmainswitchofforiginnsw').hide();
        $('.moveinundersandmainswitchoffactnsw').hide();
        $('.moveinundersandmainswitchoffactnsw2').hide();
        $('.moveinclearaccess').hide();
        $('.moveindeenergisedcontact').hide();
        $('.moveindeenergisedcontact2').hide();
        $('.moveindeenergisedcontact3').hide();
        $('.moveinpowershopvic').hide();
        $('.moveindeenergisedremainonsite').hide();
        $('.moveinpoweronmomentum').hide();
        $('.moveinconfirmremainonsite').hide();
        $('.moveinworksplanned').hide();
        $('.moveinwontcharged').hide();
        if ($('.planstate').val() == 'QLD' && $(this).val() == 'De-energised (D)') {
            $('.moveinvisualinspection').show();
        }
        if ($('.planfuel').val() == 'Dual' || $('.planfuel').val() == 'Elec') {
            if ($('.planstate').val() == 'QLD' && $(this).val() == 'De-energised (D)') {
                $('.moveinmainswitchoff').show();
            }
            if (($('.planstate').val() == 'VIC' || $('.planstate').val() == 'SA') && $(this).val() == 'De-energised (D)') {
                $('.moveinundersandmainswitchoff').show();
            }
            if (($('.planstate').val() == 'VIC' || $('.planstate').val() == 'SA') && $(this).val() == 'De-energised (D)') {
                $('.moveinunderstandclearaccessvicsa').show();
            }
            if (($('.planretailer').val() != 'AGL' && $('.planretailer').val() != 'Powerdirect' && $('.planretailer').val() != 'Powerdirect and AGL' && $('.planretailer').val() != 'Origin Energy' && $('.planretailer').val() != 'Energy Australia' && $('.planretailer').val() != 'Sumo Power') && ($('.planstate').val() == 'NSW' || $('.planstate').val() == 'ACT') && $(this).val() == 'De-energised (D)') {
                $('.moveinunderstandclearaccessactnsw').show();
            }
            if (($('.planretailer').val() == 'AGL' || $('.planretailer').val() == 'Powerdirect' || $('.planretailer').val() == 'Powerdirect and AGL') && ($('.planstate').val() == 'NSW' || $('.planstate').val() == 'ACT') && $(this).val() == 'De-energised (D)') {
                if ($('.moveinunderstandclearaccessactnsw2').length > 0) {
                    $('.moveinunderstandclearaccessactnsw2').show();
                }
            }
            if (($('.planretailer').val() == 'Origin Energy' || $('.planretailer').val() == 'AGL' || $('.planretailer').val() == 'Powerdirect' || $('.planretailer').val() == 'Powerdirect and AGL') && $('.planstate').val() == 'NSW' && $(this).val() == 'De-energised (D)') {
                $('.moveinconfirmremainonsite').show();
            }
            if ($('.planretailer').val() == 'Origin Energy' && $('.planstate').val() == 'NSW' && $(this).val() == 'De-energised (D)') {
                if ($('.moveinundersandmainswitchofforiginnsw').length > 0) {
                    $('.moveinundersandmainswitchofforiginnsw').show();
                }
            }
            if ($('.planretailer').val() == 'Origin Energy' && $('.planstate').val() == 'QLD' && $(this).val() == 'De-energised (D)') {
                $('.moveinwontcharged').show();
            }
            if (($('.planretailer').val() != 'AGL' && $('.planretailer').val() != 'Powerdirect' && $('.planretailer').val() != 'Powerdirect and AGL' && $('.planretailer').val() != 'Origin Energy') && ($('.planstate').val() == 'ACT' || $('.planstate').val() == 'NSW') && $('.movein_nmi_status').val() == 'De-energised (D)') {
                if ($('.moveinundersandmainswitchoffactnsw').length > 0) {
                    $('.moveinundersandmainswitchoffactnsw').show();
                }
            }
            if (($('.planretailer').val() != 'AGL' && $('.planretailer').val() != 'Powerdirect' && $('.planretailer').val() != 'Powerdirect and AGL' && $('.planretailer').val() != 'Origin Energy') && ($('.planstate').val() == 'ACT' || $('.planstate').val() == 'NSW') && $('.movein_nmi_status').val() == 'De-energised (D)') {
                if ($('.moveinundersandmainswitchoffactnsw2').length > 0) {
                    $('.moveinundersandmainswitchoffactnsw2').show();
                }
            }
            if (($('.planretailer').val() == 'AGL' || $('.planretailer').val() == 'Powerdirect' || $('.planretailer').val() == 'Powerdirect and AGL') && ($('.planstate').val() == 'ACT' || $('.planstate').val() == 'NSW') && $('.movein_nmi_status').val() == 'De-energised (D)') {
                if ($('.moveinundersandmainswitchoffactnsw3').length > 0) {
                    $('.moveinundersandmainswitchoffactnsw3').show();
                }
            }
            if ($('.moveinworksplanned').length > 0) {
                $('.moveinworksplanned').show();
            }
            if ($(this).val() == 'Active (A)') {
                $('.moveinclearaccess').show();
                if ($('.planstate').val() == 'QLD') {
                    $('.moveindeenergisedcontact').show();
                }
                if (($('.planstate').val() == 'ACT' || $('.planstate').val() == 'VIC' || $('.planstate').val() == 'NSW' || $('.planstate').val() == 'SA') && $('.planretailer').val() == 'Origin Energy') {
                    if ($('.moveindeenergisedcontact3').length > 0) {
                        $('.moveindeenergisedcontact3').show();
                    }
                }
                if (($('.planstate').val() == 'ACT' || $('.planstate').val() == 'VIC' || $('.planstate').val() == 'NSW' || $('.planstate').val() == 'SA') && $('.planretailer').val() != 'Origin Energy') {
                    if ($('.moveindeenergisedcontact2').length > 0) {
                        $('.moveindeenergisedcontact2').show();
                    }
                }
                if (($('.planstate').val() == 'ACT' || $('.planstate').val() == 'NSW') && ($('.planretailer').val() == 'AGL' || $('.planretailer').val() == 'Powerdirect' || $('.planretailer').val() == 'Powerdirect and AGL' || $('.planretailer').val() == 'Origin Energy')) {
                    if ($('.moveindeenergisedremainonsite').length > 0) {
                        $('.moveindeenergisedremainonsite').show();
                    }
                }
                if ($('.planretailer').val() == 'Momentum') {
                    $('.moveinpoweronmomentum').show();
                }
            }
            if ($('.planstate').val() == 'VIC' && $('.planretailer').val() == 'Powershop') {
                $('.moveinpowershopvic').show();
            }
        }
    }).change();

    $('#business_type').change(function() {
        if ($('.planretailer').val() == 'Next Business Energy' && $(this).val()) {
            if ($(this).val() != 'Sole Trader') {
                if ($('.customertype').val() == 'Business') {
                    dataOptions.count = 2;
                    $('#accordion').accordion({active:2});
                } else {
                    dataOptions.count = 4;
                    $('#accordion').accordion({active:4});
                }
                $('.identification').hide();
            } else {
                if ($('.customertype').val() == 'Business') {
                    $('.identification').show();
                }
            }
        }
        if ($('.planretailer').val() == 'Alinta Energy' && $(this).val()) {
            if ($(this).val() == 'Trust') {
                $('.trustee').show();
            } else {
                $('.trustee').hide();
            }
        }
        if ($('.planretailer').val() == 'Blue NRG' && $(this).val()) {
            if ($(this).val() == 'Private') {
                if ($('.customertype').val() == 'Business') {
                    dataOptions.count = 2;
                    $('#accordion').accordion({active:2});
                    $('.identification').hide();
                }
            } else {
                if ($('.customertype').val() == 'Business') {
                    $('.identification').show();
                }
            }
        }
        if ($('.planretailer').val() == 'Alinta Energy' && $(this).val()) {
            if ($(this).val() != 'Sole Trader') {
                if ($('.customertype').val() == 'Business') {
                    dataOptions.count = 2;
                    $('#accordion').accordion({active:2});
                    $('.identification').hide();
                }
            } else {
                if ($('.customertype').val() == 'Business') {
                    $('.identification').show();
                }
            }
        }
        if ($('.planretailer').val() == 'AGL') {
            $('.identification').show();
        }
    }).change();

    $('#concession_machine_type2').change(function() {
        if ($(this).val() == 'G. Other') {
            $('.machinetypeother').show();
        } else {
            $('.machinetypeother').hide();
        }
    }).change();

    $('#concession_machine_type3').change(function() {
        if ($(this).val() == 'Other') {
            $('.machinetypeother').show();
        } else {
            $('.machinetypeother').hide();
        }
    }).change();

    $('#document_type').change(function() {
        if ($(this).val() == 'DRV') {
            $('.document_driver_license_card_number_field').show();
            $('.document_state_field').show();
        } else {
            $('.document_driver_license_card_number_field').hide();
            $('.document_state_field').hide();
        }

        if ($(this).val() == 'PP') {
            $('.document_country_field').show();
        } else {
            $('.document_country_field').hide();
        }

        if ($(this).val() == 'MED') {
            $('.document_medicare_colour_field').show();
        } else {
            $('.document_medicare_colour_field').hide();
        }
    }).change();

    $('.selectpropertytype').change(function() {
        if ($(this).val() == 'Multi Site' && $('.planretailer').val() == 'Alinta Energy') {
            $('.multisite').show();
        } else {
            $('.multisite').hide();
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

    $.validator.addMethod(
        "futureDate",
        function(value, element) {
            if (value) {
                var now = new Date();
                var dt = value.split("/");
                var year = dt[2];
                var month = dt[1];
                var day = dt[0];
                var d = new Date();
                var myDate = new Date(year+"-"+month+"-"+day);
                return myDate > now;
            }
            return true;
        },
        "Mobile number is invalid"
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
            'Secondary[first_name]' :{
                required:true,
                minlength:2
            },
            'Secondary[last_name]' :{
                required:true,
                minlength:2
            },
            'Secondary[dateofbirth]':{
                required:true,
                verifyDOB: true,
                //date:true,
                //datecustom:true,
            },
        },
        errorPlacement: function(error, element) { },
        //ignore: ".contactdetialinputstext",
    });

    $('.signup_business_details').validate({
        rules:{
            "Business[abn]":{
                required:true,
            },
            "Business[trading_name]":{
                required:true,
            },
            "Business[legal_name]":{
                required:true,
            },
        },
        errorPlacement: function(error, element) { },
        //ignore: ".contactdetialinputstext",
    });

    $('.signup_concession').validate({
        rules:{
            "Concession[life_support_user_full_name]":{
                required:true,
            },
            "Concession[life_support_user_title]":{
                required:true,
            },
            "Concession[life_support_user_first_name]":{
                required:true,
            },
            "Concession[life_support_user_last_name]":{
                required:true,
            },
            "Concession[machine_type]":{
                required:true,
            },
            "Concession[machine_type2]":{
                required:true,
            },
            "Concession[machine_type3]":{
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
                required: true,
                futureDate: true,
            },
            "Identification[card_number]":{
                required: true,
            },
            "Identification[card_start]":{
                required: true,
            },
            "Identification[card_expiry]":{
                required: true,
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
			"Supply[tenant_owner]":{
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
            'MoveInDetail[date]':{
                required:true,
            },
            'MoveInDetail[previous_street]':{
                required:true,
            },
            'MoveInDetail[previous_suburb]':{
                required:true,
            },
            'MoveInDetail[previous_state]':{
                required:true,
            },
            'MoveInDetail[previous_postcode]':{
                required:true,
            },
        },
        errorPlacement: function(error, element) { },
        //ignore: ".contactdetialinputstext",
    });

    $('.signup_final').validate({
        rules:{
            "Final[quote_id]":{
                required:true,
            },
            "Final[eic]":{
                required:true,
            },
            "Final[powershop_token]":{
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

    $('#movein_special').click(function() {
        if ($(this).is(":checked")) {
            $('.movein_special_checked').removeClass('display_hide');
        } else {
            $('.movein_special_checked').addClass('display_hide');
        }
    });

    $('#movein_special_span').click(function() {
        if ($('#movein_special').is(":checked")) {
            $('#movein_special').prop('checked', false);
            $('.movein_special_checked').addClass('display_hide');
        } else {
            $('#movein_special').prop('checked', true);
            $('.movein_special_checked').removeClass('display_hide');
        }
    });

    $('.lookup-btn').click(function() {
        var abn = $('#abn').val();
        window.open('http://abr.business.gov.au/SearchByABN.aspx?SearchText=' + abn,'windowname1','width=1024, height=768'); return false;
    });

    $('.movein-fees-btn').click(function() {
        window.open('http://public.compareconnectsave.com.au','windowname1','width=1024, height=768'); return false;
    });

    $('.noemail-btn').click(function(e) {
        e.preventDefault();
        $('.e_billing').hide();
        $('.agl_email_bill').hide();
        $('.pd_email_bill').hide();
        $('.pd_agl_email_bill').hide();
        $('.origin_email_bill').hide();
        $('.EA_email_bill').hide();
        $('.erm_email_bill').hide();
        $('.lumo_email_bill').hide();
        $('.receivebillsbypost').hide();
        $('.originemailbillno').hide();
        $('.originemailbillno2').hide();
        $('.welcomepackpost').hide();
        $('.aglnoemail').hide();
        $('.aglnoemailall').hide();
        $('.actewaglnoemail').hide();
        $('.EAnoemail').hide();
        $('.nextbusinessnoemail').hide();
        $('.momentumnoemail').hide();
        $('.electronic').hide();
        $('.lumoemailbillno').hide();
        $('.sumovalidemail').hide();
        $('.sumonoemail').hide();
        $('.paperbill').hide();
        $('.receive_an_email').hide();
        $('.receive_no_email').show();
        $('.e_billing_momentum').hide();
        $('.bluenrg_email_bill').hide();
        $('.bluenrgnoemail').hide();
        $('.simplyenergywelcomepack').hide();
        $('.simplyenergynoemail').hide();
        $('#contact_email').val('no@email.com.au');
        if ($('.planretailer').val() == 'AGL' || $('.planretailer').val() == 'Powerdirect' || $('.planretailer').val() == 'Powerdirect and AGL') {
            if ($('.planstate').val() != 'NSW') {
                $('.aglnoemail').show();
            }
            $('.aglnoemailall').show();
        } else if ($('.planretailer').val() == 'Energy Australia') {
            $('.EAnoemail').show();
        } else if ($('.planretailer').val() == 'Origin Energy') {
            if ($('.planstate').val() != 'NSW' && $('.planstate').val() != 'VIC') {
                $('.originemailbillno').show();
            } else {
                if ($('#plan_product_name').val().indexOf('Origin Max Saver') === -1) {
                    $('.originemailbillno2').show();
                }
            }
            $('.welcomepackpost').show();
        } else if ($('.planretailer').val() == 'Lumo Energy') {
            if (!$('.lumo_email_bill_sa').is(":visible")) {
                $('.lumoemailbillno').show();
            }
        } else if ($('.planretailer').val() == 'Sumo Power' && ($('.planstate').val() == 'VIC' || $('.planstate').val() == 'NSW')) {
            $('.sumonoemail').show();
            if ($('.planstate').val() == 'VIC') {
                $('.paperbill').show();
            }
        } else if ($('.planretailer').val() == 'Next Business Energy') {
            $('.nextbusinessnoemail').show();
        } else if ($('.planretailer').val() == 'Momentum') {
            $('.momentumnoemail').show();
        } else if ($('.planretailer').val() == 'ActewAGL' && $('.planstate').val() != 'VIC' && $('.planstate').val() != 'SA') {
            if ($('.actewaglnoemail').length > 0) {
                $('.actewaglnoemail').show();
            }
            if ($('.actewagl_email_bill').length > 0) {
                $('.actewagl_email_bill').hide();
            }
        } else if ($('.planretailer').val() == 'Blue NRG') {
            $('.bluenrgnoemail').show();
        } else if ($('.planretailer').val() == 'Simply Energy') {
            $('.simplyenergywelcomepack').hide();
            if ($('.planstate').val() != 'NSW') {
                $('.simplyenergynoemail').show();
            }
        }
    });

    $('.meterexchange-btn').click(function(e) {
        $('.meterexchangetext').show();
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
            if ($('#business_details_form_checked').val() == 1) {
                data.push($('.signup_business_details').serialize());
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
            $.post('/admin/customers/signup/'+id, data.join('&'), function(data) {
                $("#process_modal").modal('hide');

                // Remove DD pop up for plans except 'The One Plan'
                if ($('.planretailer').val() == 'OVO Energy' && $('#plan_product_name').val() === 'The One Plan') {
                    $('#ovo_lead_id').val(data.lead_id);
                    $('#ovo_lead_datetime').val(data.lead_datetime);
                    $('#ovo_cb_lead_id').val(data.cb_lead_id);

                    if (data.cb_lead_id) {
                        var cb_lead_id_html = 'CB Lead Id: ';
                        var cb_lead_id = data.cb_lead_id.split('-');
                        $.each(cb_lead_id, function(i) {
                            cb_lead_id_html += cb_lead_id[i];
                        });
                        cb_lead_id_html = cb_lead_id_html + "<br><br>";
                        $('#complete_dialog_cb_lead_id_ovo').html(cb_lead_id_html);
                    }

                    $('#complete_modal_ovo').modal('show');

                } else {
                    var lead_id_html = '';
                    var lead_ids = data.lead_id.split('-');
                    $.each(lead_ids, function(i) {
                        //lead_id_html += '<a href="https://lm.prod.velocify.com/Web/LeadAddEdit.aspx?LeadId='+lead_ids[i]+'" target="_blank">'+lead_ids[i]+'</a> ';
                        lead_id_html += lead_ids[i];
                    });
                    $('#complete_dialog_lead_id').html(lead_id_html);
                    if (data.cb_lead_id) {
                        var cb_lead_id_html = 'CB Lead Id: ';
                        var cb_lead_id = data.cb_lead_id.split('-');
                        $.each(cb_lead_id, function(i) {
                            cb_lead_id_html += cb_lead_id[i];
                        });
                        $('#complete_dialog_cb_lead_id').html(cb_lead_id_html);
                    }

                    $('.create-telco-lead').hide();
                    $('.create-telco-form').hide();

                    $('#complete_modal').modal('show');
                }

            }, "json");

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

    $(document).on('click', '.create-telco-lead', function(e) {
        e.preventDefault();
        $("#complete_modal").modal('hide');
        $("#process_modal").modal('show');
        $('#signup_complete_submit').attr('disabled','disabled');
        //$('#signup_complete_submit').val('Re-Submit Form');
        $('#final_telco_lead').val(1);
        var id = $('#id').val();
        var action = $('#action').val();
        var data = [];
        if ($('#plan_form_checked').val() == 1) {
            data.push($('.signup_plan').serialize());
        }
        if ($('#contact_form_checked').val() == 1) {
            data.push($('.signup_contact').serialize());
        }
        if ($('#business_details_form_checked').val() == 1) {
            data.push($('.signup_business_details').serialize());
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
        $.post('/admin/customers/signup/'+id, data.join('&'), function(data) {
            $("#process_modal").modal('hide');
            var lead_id_html = '';
            var lead_ids = data.lead_id.split('-');
            $.each(lead_ids, function(i) {
                lead_id_html += '<a href="https://lm.prod.velocify.com/Web/LeadAddEdit.aspx?LeadId='+lead_ids[i]+'" target="_blank">'+lead_ids[i]+'</a> ';
            });
            $('#complete_dialog_lead_id').html(lead_id_html);
            if (data.cb_lead_id) {
                var cb_lead_id_html = 'CB Lead Id: ';
                var cb_lead_id = data.cb_lead_id.split('-');
                $.each(cb_lead_id, function(i) {
                    cb_lead_id_html += cb_lead_id[i];
                });
                $('#complete_dialog_cb_lead_id').html(cb_lead_id_html);
            }

            $('.create-telco-lead').hide();
            $('.create-telco-form').hide();

            $("#complete_modal").modal('show');
        }, "json");
    });

    $('.ovo-payment-details-yes').click(function() {
        $("#complete_modal_ovo").modal('hide');
        window.location.replace("http://check.compareconnectsave.com.au/v4/");
    });
    $('.ovo-payment-details-no').click(function() {
        var r = confirm("Are you sure?");
        if (r) {
            $("#complete_modal_ovo").modal('hide');
            var ovo_lead_id = $('#ovo_lead_id').val();
            var ovo_lead_datetime = $('#ovo_lead_datetime').val();
            var campaign_id = $('#final_campaign_id').val();
            var ovo_cb_lead_id = $('#ovo_cb_lead_id').val();
            var url = "https://brokers.ovoenergy.com.au/?correlation_id="+ovo_lead_id+"_"+ovo_lead_datetime+"&api_key=AKwdGVUz4v9poEGn8jMAX6PoaaAm6f8aaPE8yDbC&broker_id=voucherstore&redirect_uri=https://signup.compareconnectsave.com.au/admin/customers/save_ovo_payment_token/"+campaign_id+"/"+ovo_lead_id+"/"+ovo_cb_lead_id;
            $.post('/admin/customers/add_lead_action', { lead_id: ovo_lead_id, note: 'Agent Redirect to OVO Payment Portal' }, function(data) {
                window.location.href = url;
            }, "json");
        }
    });

    $(document).on('click', '.create-telco-form', function(e) {
        e.preventDefault();
        var id = $('#id').val();
        window.location.replace('/admin/customers/signup_telco/'+id);
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
    $('.datepickerDateOfBirthSec').datepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
        startDate: '-100y',
        endDate: '-18y'
    }).on('changeDate', function (ev) {
        $('.signup_contact').validate().element(this);
    });
    $("#datepickerDateOfBirthSec").mask("99/99/9999",{ placeholder: "DD/MM/YYYY" });

    $('#identification_document_expiry').datepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
        startDate: '+1d',
    }).on('changeDate', function (ev) {
        $('.signup_identification').validate().element(this);
    });
    $("#identification_document_expiry").mask("99/99/9999",{ placeholder: "DD/MM/YYYY" });

    $('#concession_card_start').datepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
    }).on('changeDate', function (ev) {
        $('.signup_concession').validate().element(this);
    });
    $("#concession_card_start").mask("99/99/9999",{ placeholder: "DD/MM/YYYY" });

    $('#concession_card_expiry').datepicker({
        format: "dd/mm/yyyy",
        autoclose: true,
    }).on('changeDate', function (ev) {
        $('.signup_concession').validate().element(this);
    });
    $("#concession_card_expiry").mask("99/99/9999",{ placeholder: "DD/MM/YYYY" });

    $('#move_in_date').datepicker({
        daysOfWeekDisabled: [0, 6],
        startDate: '+0d',
        format: "dd/mm/yyyy",
        autoclose: true,
    }).on('changeDate', function (ev) {
        $('.signup_moveindetail').validate().element(this);
    });
    $("#move_in_date").mask("99/99/9999",{ placeholder: "DD/MM/YYYY" });

    $(document).on("keyup", '.street_type', function(event) {
        street_type_lookup(this);
    });
    $('#sales_rep_name').bind('keyup', function(){
        sales_rep_lookup();
    });
    $('#trustee_company_type').bind('keyup', function(){
        trustee_company_type_lookup();
    });
    $('.eicpdf').change(function(){
        var filename = $(this).val();
        if (filename) {
            window.open('http://check.compareconnectsave.com.au/pdf/'+filename);
        }
    });
    $('.add-a-gas-product').click(function() {
        $("#complete_modal").modal('hide');
        var id = $('#id').val();
        window.location.replace("http://check.compareconnectsave.com.au/v4/");
    });
    $('.signup-another-property').click(function() {
        $("#complete_modal").modal('hide');
        window.location.replace("http://check.compareconnectsave.com.au/v4/");
    });
    $('.no-thanks').click(function() {
        $("#complete_modal").modal('hide');
        window.location.replace("http://check.compareconnectsave.com.au/v4/");
    });

    $('#contact_email').blur(function() {
        var reg = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        var email = $('#contact_email').val();
        $('.aglnoemail').hide();
        $('.aglnoemailall').hide();
        $('.actewaglnoemail').hide();
        $('.EAnoemail').hide();
        $('.agl_email_bill').hide();
        $('.pd_email_bill').hide();
        $('.pd_agl_email_bill').hide();
        $('.origin_email_bill').hide();
        $('.EA_email_bill').hide();
        $('.erm_email_bill').hide();
        $('.lumo_email_bill').hide();
        $('.receivebillsbypost').hide();
        $('.originemailbillno').hide();
        $('.originemailbillno2').hide();
        $('.lumoemailbillno').hide();
        $('.e_billing').show();
        $('.sumovalidemail').hide();
        $('.sumonoemail').hide();
        $('.paperbill').hide();
        $('.electronic').hide();
        $('.welcomepackpost').hide();
        $('.nextbusinessnoemail').hide();
        $('.momentumnoemail').hide();
        $('.receive_an_email').hide();
        $('.receive_no_email').hide();
        $('.actewagl_email_bill').hide();
        $('.e_billing_momentum').hide();
        $('.bluenrg_email_bill').hide();
        $('.bluenrgnoemail').hide();
        $('.simplyenergywelcomepack').hide();
        $('.simplyenergynoemail').hide();
        if (email && reg.test(email) && email != 'no@email.com.au') {
            if ($('.planretailer').val() != 'Alinta Energy' && $('.planretailer').val() != 'ERM') {
                $('.e_billing').hide();
            }
            if ($('.planretailer').val() == 'AGL') {
                $('.agl_email_bill').show();
            } else if ($('.planretailer').val() == 'Powerdirect') {
                $('.pd_email_bill').show();
            } else if ($('.planretailer').val() == 'Powerdirect and AGL') {
                $('.pd_agl_email_bill').show();
            } else if ($('.planretailer').val() == 'Origin Energy') {
                if ($('#plan_product_name').val().indexOf('Origin Max Saver') === -1) {
                    $('.origin_email_bill').show();
                }
                if ($('.ContactOriginEmailBill').val() == 0) {
                    if ($('.planstate').val() != 'NSW' && $('.planstate').val() != 'VIC') {
                        $('.originemailbillno').show();
                    } else {
                        if ($('#plan_product_name').val().indexOf('Origin Max Saver') === -1) {
                            $('.originemailbillno2').show();
                        }
                    }
                }
            } else if ($('.planretailer').val() == 'Sumo Power') {
                $('.sumovalidemail').show();
            } else if ($('.planretailer').val() == 'Energy Australia') {
                $('.EA_email_bill').show();
            } else if ($('.planretailer').val() == 'ERM') {
                $('.erm_email_bill').show();
                $('.e_billing').hide();
            } else if ($('.planretailer').val() == 'Lumo Energy') {
                if (($('#plan_product_name').val().indexOf('Lumo Value') !== -1 || $('#plan_product_name').val().indexOf('Lumo Movers') !== -1) && $('.planstate').val() == 'VIC') {
                    $('.lumo_email_bill').show();
                } else if ($('#plan_product_name').val().indexOf('Lumo Basic') !== -1) {
                    $('.lumo_email_bill').show();
                } else {
                    $('.lumo_email_bill').hide();
                }
            } else if ($('.planretailer').val() == 'Next Business Energy') {

            } else if ($('.planretailer').val() == 'Momentum') {
                if ($('#plan_product_name').val().indexOf('Bill Boss') === -1) {
                    $('.e_billing_momentum').show();
                }
            } else if ($('.planretailer').val() == 'Blue NRG') {
                $('.bluenrg_email_bill').show();
            } else if ($('.planretailer').val() == 'Simply Energy') {
                $('.simplyenergywelcomepack').show();
                if ($('.planstate').val() != 'NSW') {
                    $('.simplyenergynoemail').hide();
                }
            }

            if ($('.planretailer').val() == 'AGL' || $('.planretailer').val() == 'Powerdirect' || $('.planretailer').val() == 'Powerdirect and AGL'  || $('.planretailer').val() == 'OVO Energy') {
                $('.electronic').show();
            }

            if ($('.planretailer').val() == 'ActewAGL' && $('.planstate').val() != 'VIC' && $('.planstate').val() != 'SA') {
                if ($('.actewagl_email_bill').length > 0) {
                    $('.actewagl_email_bill').show();
                }
            }

            $('.receive_an_email').show();
        } else {
            $('.e_billing').hide();
            if ($('.planretailer').val() == 'AGL' || $('.planretailer').val() == 'Powerdirect' || $('.planretailer').val() == 'Powerdirect and AGL') {
                if ($('.planstate').val() != 'NSW') {
                    $('.aglnoemail').show();
                }
                $('.aglnoemailall').show();
            } else if ($('.planretailer').val() == 'Energy Australia') {
                $('.EAnoemail').show();
            } else if ($('.planretailer').val() == 'Origin Energy') {
                if ($('.planstate').val() != 'NSW' && $('.planstate').val() != 'VIC') {
                    $('.originemailbillno').show();
                } else {
                    if ($('#plan_product_name').val().indexOf('Origin Max Saver') === -1) {
                        $('.originemailbillno2').show();
                    }
                }
                $('.welcomepackpost').show();
            } else if ($('.planretailer').val() == 'Lumo Energy') {
                if (!$('.lumo_email_bill_sa').is(":visible")) {
                    $('.lumoemailbillno').show();
                }
            } else if ($('.planretailer').val() == 'Sumo Power' && ($('.planstate').val() == 'VIC' || $('.planstate').val() == 'NSW')) {
                $('.sumonoemail').show();
                if ($('.planstate').val() == 'VIC') {
                    $('.paperbill').show();
                }
            } else if ($('.planretailer').val() == 'Next Business Energy') {
                $('.nextbusinessnoemail').show();
            } else if ($('.planretailer').val() == 'Momentum') {
                $('.momentumnoemail').show();
            } else if ($('.planretailer').val() == 'ActewAGL' && $('.planstate').val() != 'VIC' && $('.planstate').val() != 'SA') {
                if ($('.actewaglnoemail').length > 0) {
                    $('.actewaglnoemail').show();
                }
            } else if ($('.planretailer').val() == 'Blue NRG') {
                $('.bluenrgnoemail').show();
            } else if ($('.planretailer').val() == 'Simply Energy') {
                $('.simplyenergywelcomepack').hide();
                if ($('.planstate').val() != 'NSW') {
                    $('.simplyenergynoemail').show();
                }
            }

            $('.receive_no_email').show();
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

    $(document).on('click', '.supplynmiacqretbtn', function() {
        checkRetention();
    });
    $(document).on('click', '.supplymirnacqretbtn', function() {
        checkRetention();
    });

    $(document).on('click', '.supplytenantownerbtn', function() {
        /*
        $('.storage-solution').hide();
        $('.storage-solar-solution').hide();
        if ($('.planfuel').val() != 'Gas') {
            if ($(this).parent().find('input:hidden').val() == 'Renter') {
                if ($('.customertype').val() == 'Residential' || $('.customertype').val() == 'Business') {
                    if ($('.PlanSolarPanels').val() == 1) {
                        $('.storage-solution').show();
                    }
                }
                if ($('.customertype').val() == 'Business') {
                    if ($('.PlanSolarPanels').val() == 0) {
                        $('.storage-solar-solution').show();
                    }
                }
            } else if ($(this).parent().find('input:hidden').val() == 'Owner') {
                if ($('.customertype').val() == 'Residential' || $('.customertype').val() == 'Business') {
                    if ($('.PlanSolarPanels').val() == 1) {
                        $('.storage-solution').show();
                    } else {
                        $('.storage-solar-solution').show();
                    }
                }
            }
        }
        */
    });

    $.post('/customers/get_rates', { id: $('#id').val() }, function(response) {
        $('.table-rate' ).html(response.html);
    }, "json");

    $.post('/customers/get_rates/1', { id: $('#id').val() }, function(response) {
        $('.table-rate-solar' ).html(response.html);
    }, "json");

    $.post('/customers/get_move_in_info', { id: $('#id').val() }, function(response) {
        //$('.table-move-in-info' ).html(response.html);
    }, "json");
});

checkRetention = function() {

    $('.understand_transfer_retention').hide();
    $('.understand_transfer_retention_sold').hide();
    $('.understand_transfer_retention_retain').hide();

    $('.understand_acquisition_nextbusiness').hide();

    $('.popup_transfer_acquisition_text').hide();

    $('.supplynmiacqret').each(function() {
        if ($(this).val() == 'Retention') {
            if ($('.lookingfor').val() == 'Transfer') {
                if ($.inArray($('.planretailer').val(), ['AGL', 'Energy Australia', 'Powerdirect', 'Powerdirect and AGL', 'Origin Energy', 'Next Business Energy', 'ActewAGL']) != -1) {
                    $(this).parents().eq(2).find('.understand_transfer_retention').show();
                    //$(this).parents().eq(2).find('.understand_transfer_retention_sold').show();
                } else {
                    $(this).parents().eq(2).find('.understand_transfer_retention_retain').show();
                }
            }
        } else if ($(this).val() == 'Acquisition') {
            if ($('.lookingfor').val() == 'Transfer') {
                $('.popup_transfer_acquisition_text').show();
            }
            if ($('.planretailer').val() == 'Next Business Energy' && $('.planfuel').val() == 'Elec' && $('.customertype').val() == 'Business' && $('.planstate').val() != 'QLD') {
                $('.understand_acquisition_nextbusiness').show();
            }
        }
    });
    $('.supplymirnacqret').each(function() {
        if ($(this).val() == 'Retention') {
            if ($('.lookingfor').val() == 'Transfer') {
                if ($.inArray($('.planretailer').val(), ['AGL', 'Energy Australia', 'Powerdirect', 'Powerdirect and AGL', 'Origin Energy', 'Next Business Energy', 'ActewAGL']) != -1) {
                    $(this).parents().eq(2).find('.understand_transfer_retention').show();
                    //$(this).parents().eq(2).find('.understand_transfer_retention_sold').show();
                } else {
                    $(this).parents().eq(2).find('.understand_transfer_retention_retain').show();
                }
            }
        } else if ($(this).val() == 'Acquisition') {
            if ($('.lookingfor').val() == 'Transfer') {
                $('.popup_transfer_acquisition_text').show();
            }
        }
    });
};

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

checkContactSecondary = function(value,formclass) {
    if (formclass === undefined ) {
        formclass = '.global_note';
    }
    $('.secondarycontactno').hide();
    if ($(value).is(":checked")) {
        if ($('.planretailer').val() == 'AGL' || $('.planretailer').val() == 'Powerdirect') {
            $('.secondary-contact').hide();
            $('.secondarycontactno').show();
        } else {
            $('.secondary-contact').show();
            $('.datepickerDateOfBirthSec').datepicker('show');
        }
    } else {
        $('.secondary-contact').hide();
        $( formclass + ' .secondary_input').val('');
        $( formclass + ' .secondary_input').removeClass('valid');
        $( formclass + ' .secondary_input').removeClass('error');
        $('.datepickerDateOfBirthSec').datepicker('hide');
        //$( formclass + ' .job_address_input').removeAttr('readonly');
        //$( formclass + ' .job_address_input').val('');
    }
}

checkContactSecondarySpan = function(value,formclass) {
    if (formclass === undefined ) {
        formclass = '.global_note';
    }
    $('.secondarycontactno').hide();
    if ($(value).is(":checked")) {
        $(value).prop('checked', false);
        $('.secondary-contact').hide();
    } else {
        if ($('.planretailer').val() == 'AGL' || $('.planretailer').val() == 'Powerdirect') {
            $(value).prop('checked', false);
            $('.secondary-contact').hide();
            $('.secondarycontactno').show();
        } else {
            $(value).prop('checked', true);
            $('.secondary-contact').show();
        }
    }
}

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

agl_email_bill = function(arg) {
    $('.receivebillsbypost').hide();
    $('.checkspam').hide();
    if (arg) {
        $('.ContactAglEmailBill').val(1);
        $('.checkspam').show();
    } else {
        $('.ContactAglEmailBill').val(0);
        if ($('.planretailer').val() == 'AGL' || $('.planretailer').val() == 'Powerdirect' || $('.planretailer').val() == 'Powerdirect and AGL') {
            if ($('.planstate').val() != 'NSW') {
                $('.receivebillsbypost').show();
            }
        }
    }
}

pd_email_bill = function(arg) {
    $('.receivebillsbypost').hide();
    $('.checkspam').hide();
    if (arg) {
        $('.ContactPowerdirectEmailBill').val(1);
        $('.checkspam').show();
    } else {
        $('.ContactPowerdirectEmailBill').val(0);
        if ($('.planretailer').val() == 'AGL' || $('.planretailer').val() == 'Powerdirect' || $('.planretailer').val() == 'Powerdirect and AGL') {
            if ($('.planstate').val() != 'NSW') {
                $('.receivebillsbypost').show();
            }
        }
    }
}

pd_agl_email_bill = function(arg) {
    $('.receivebillsbypost').hide();
    $('.checkspam').hide();
    if (arg) {
        $('.ContactPowerdirectAGLEmailBill').val(1);
        $('.checkspam').show();
    } else {
        $('.ContactPowerdirectAGLEmailBill').val(0);
        if ($('.planretailer').val() == 'AGL' || $('.planretailer').val() == 'Powerdirect' || $('.planretailer').val() == 'Powerdirect and AGL') {
            if ($('.planstate').val() != 'NSW') {
                $('.receivebillsbypost').show();
            }
        }
    }
}

receive_bills_by_post = function(arg) {
    if (arg) {
        $('.checkspam').hide();
    } else {
        $('.checkspam').show();
    }
}

origin_email_bill = function(arg) {
    if (arg) {
        $('.ContactOriginEmailBill').val(1);
        $('.originemailbillyes').show();
        $('.originemailbillno').hide();
        $('.originemailbillno2').hide();
    } else {
        $('.ContactOriginEmailBill').val(0);
        $('.originemailbillyes').hide();
        if ($('.planstate').val() != 'NSW' && $('.planstate').val() != 'VIC') {
            $('.originemailbillno').show();
        } else {
            if ($('#plan_product_name').val().indexOf('Origin Max Saver') === -1) {
                $('.originemailbillno2').show();
            }
        }
    }
}

simplyenergy_email_bill = function(arg) {
    $('.simplyenergynoemail').hide();
    if (arg) {
        $('.contact_welcome_pack_simply_energy').val(1);

    } else {
        $('.contact_welcome_pack_simply_energy').val(0);
        if ($('.planstate').val() != 'NSW') {
            $('.simplyenergynoemail').show();
        }
    }
}

show_concession_fields = function(arg) {
    $('.concession-card-fields').hide();
    $('.qldretaileryes').hide();
    if (arg) {
        if ($('.planstate').val() == 'QLD') {
            $('.qldretaileryes').show();
        }
        $('.concession-card-fields').show();
    } else {
        $('.concession-card-fields').hide();
    }
}


bluenrg_email_bill = function(arg) {
    $('.bluenrg_postal_invoices').hide();
    if (arg) {
        $('.ContactBlueNrgEmailBill').val(1);

    } else {
        $('.ContactBlueNrgEmailBill').val(0);
        if ($('.planstate').val() == 'VIC') {
            $('.bluenrg_postal_invoices').show();
        }
    }
}

EA_email_bill = function(arg) {
    if (arg) {
        $('.ContactEAEmailBill').val(1);
    } else {
        $('.ContactEAEmailBill').val(0);
    }
}

erm_email_bill = function(arg) {
    if (arg) {
        $('.ContactERMEmailBill').val(1);
    } else {
        $('.ContactERMEmailBill').val(0);
    }
}

lumo_email_invoices = function(arg) {
    if (arg) {
        $('.ContactLumoEmailInvoices').val(1);
    } else {
        $('.ContactLumoEmailInvoices').val(0);
    }
}

alinta_secondary_account = function(arg) {
    if (arg) {
        $('.ContactAlintaSecondaryAccount').val(1);
        $('.secondary_contact_method').show();
    } else {
        $('.ContactAlintaSecondaryAccount').val(0);
        $('.secondary_contact_method').hide();
    }
}

updateSupplyFields = function() {
    $('.nmisupply').hide();
    $('.mirnsupply').hide();
    $('.mirnaddress').hide();
    $('.msatsaddress').hide();
    $('.moveinelectricitymeter').show();
    $('.moveingasmeter').show();
    $('.mirnisdifferent').hide();
    $('.msatsisdifferent').hide();
    $('.aglsupply').hide();
    $('.nmiacqret').hide();
    $('.mirnacqret').hide();
    $('.moveinelecconnectionfeetype').show();
    if ($('.planfuel').val() == 'Elec') {
        $('.nmisupply').show();
        $('.moveingasmeter').hide();
        $('.msatsisdifferent').show();
        $('.nmiacqret').show();
    } else if ($('.planfuel').val() == 'Gas') {
        $('.mirnsupply').show();
        $('.moveinelectricitymeter').hide();
        $('.moveinelecconnectionfeetype').hide();
        $('.mirnisdifferent').show();
        $('.mirnacqret').show();
    } else if ($('.planfuel').val() == 'Dual') {
        $('.nmisupply').show();
        $('.mirnsupply').show();
        $('.mirnisdifferent').show();
        $('.msatsisdifferent').show();
        $('.nmiacqret').show();
        $('.mirnacqret').show();
    }
    if ($('.planretailer').val() == 'AGL') {
        $('.aglsupply').show();
    }
}

HouseholdRequire = function(arg) {
    $('.originhouseholdrequireyes').hide();
    $('.momentumhouseholdrequireyes').hide();
    $('.simplyenergyhouseholdrequireyes').hide();
    $('.alintahouseholdrequireyes').hide();
    $('.EAhouseholdrequireyes').hide();
    $('.householdrequireyes').hide();
    $('.lifesupportuserfullname').hide();
    $('.machinetype').hide();
    $('.machinetype2').hide();
    $('.machinerunby').hide();
    $('.sendlifesupportapplicationform').hide();
    if (arg) {
        $('.ConcessionHouseholdRequire').val(1);
        if ($('.planretailer').val() == 'Origin Energy' && $('.planfuel').val() != 'Dual') {
            $('.originhouseholdrequireyes').show();
        }
        if ($('.planretailer').val() == 'Momentum') {
            $('.momentumhouseholdrequireyes').show();
        }
        if ($('.planretailer').val() == 'Energy Australia') {
            $('.EAhouseholdrequireyes').show();
        }
        if ($('.planretailer').val() == 'Alinta Energy') {
            $('.alintahouseholdrequireyes').show();
        }
        if ($('.planretailer').val() == 'Lumo Energy') {
            if ($('.planstate').val() == 'VIC') {
                $('.sendlifesupportapplicationform').show();
            } else if ($('.planstate').val() == 'SA') {
                $('.sendlifesupportapplicationform').show();
            } else if ($('.planstate').val() == 'QLD') {
            }

            $('.householdrequireyes').show();
            $('.lifesupportuserfullname').show();
            $('.machinetype').show();
            $('.machinerunby').show();
        }
        if ($('.planretailer').val() == 'AGL' || $('.planretailer').val() == 'Powerdirect' || $('.planretailer').val() == 'Powerdirect and AGL') {
            $('.householdrequireyes').show();
            $('.lifesupportuserfullname').show();
            $('.machinetype2').show();
            $('.machinerunby').show();
        }
        if ($('.planretailer').val() == 'Simply Energy') {
            $('.simplyenergyhouseholdrequireyes').show();
        }
    } else {
        $('.ConcessionHouseholdRequire').val(0);
    }
    $('.ConcessionHouseholdRequire').parent().find('label').attr({'style':"color:#777777 !important"});
}

machine_run_by = function(arg) {
    $('.aglpowerdirectlifesupport').hide();
    if (arg) {
        if (arg == 1) {
            var fuel_type = 'Elec';
        } else if (arg == 2) {
            var fuel_type = 'Gas';
        } else if (arg == 3) {
            var fuel_type = 'Dual';
        }
        if ($('.planretailer').val() == 'AGL' || $('.planretailer').val() == 'Powerdirect' || $('.planretailer').val() == 'Powerdirect and AGL') {
            if ($('.planfuel').val() == 'Elec' && fuel_type == 'Gas') {
                $('.aglpowerdirectlifesupporttext').text('Gas');
                $('.aglpowerdirectlifesupport').show();
            }
            if ($('.planfuel').val() == 'Gas' && fuel_type == 'Elec') {
                $('.aglpowerdirectlifesupporttext').text('Elec');
                $('.aglpowerdirectlifesupport').show();
            }
        }
        $('.ConcessionMachineRunBy').val(arg);
    } else {
        $('.ConcessionMachineRunBy').val(0);
    }
}

creditcheck = function(arg) {
    $('.identification_default_fields').show();
    if ($('.planretailer').val() == 'AGL') {
        //$('#identification_credit_check_content').removeClass('required-yes');
    } else {
        //$('#identification_credit_check_content').addClass('required-yes');
    }
    if (arg) {
        $('.IdentificationCreditCheck').val(1);
        if ($('.planretailer').val() == 'Lumo Energy' || $('.planretailer').val() == 'Alinta Energy' || $('.planretailer').val() == 'Momentum' || $('.planretailer').val() == 'Energy Australia') {
            //$('.identification_credit_check_fields').hide();
            $('.creditcheckno').hide();
        }
        if ($('.planretailer').val() == 'AGL') {
            if ($('.customertype').val() == 'Business' && $('#business_type').val() != 'Sole Trader') {
                $('.identification_default_fields').hide();
            }
        }
    } else {
        $('.IdentificationCreditCheck').val(0);
        $('.identification_default_fields').hide();
        if ($('.planretailer').val() == 'Lumo Energy' || $('.planretailer').val() == 'Alinta Energy' || $('.planretailer').val() == 'Energy Australia' || $('.planretailer').val() == 'Momentum') {
            //$('.identification_credit_check_fields').show();
            $('.creditcheckno').show();
        }
    	if ( $('.planretailer').val() == 'Powerdirect') {
            $('.identification_default_fields').show();
            $('.creditcheckno').hide();
        }
        if ($('.planretailer').val() == 'AGL') {
            if ($('.customertype').val() == 'Business' && $('#business_type').val() != 'Sole Trader') {
                $('.identification_default_fields').hide();
            } else {
                $('.identification_default_fields').show();
            }
            $('.creditcheckno').hide();
        }
    }
    $('.IdentificationCreditCheck').parent().find('label').attr({'style':"color:#777777 !important"});
}

concessionCard = function(arg) {
    if (arg) {
        $('.buttonyes').addClass('select-btn');
        $('.buttonno').removeClass('select-btn');
        $('.ValidConcessionCard').val(1);

        $('.concession-all').removeClass('display_hide');

        concessionFormValidation();

        if ($('.planretailer').val() == 'Alinta Energy' && $('.customertype').val() == 'Residential' && $('.planstate').val() != 'SA') {
            $('.concession-card-fields').show();
        }
        if ($('.planretailer').val() == 'Momentum' && $('.customertype').val() == 'Residential' && $('.planstate').val() != 'SA') {
            $('.concession-card-fields').show();
        }
        if ($('.planretailer').val() == 'ActewAGL' && $('.customertype').val() == 'Residential' && $('.planstate').val() != 'SA') {
            $('.concession-card-fields').show();
        }
        if ($('.planretailer').val() == 'Powershop' && $('.customertype').val() == 'Residential' && $('.planstate').val() != 'SA') {
            $('.concession-card-fields').show();
        }
        if ($('.planretailer').val() == 'Origin Energy' && $('.customertype').val() == 'Residential' && $('.planstate').val() != 'SA') {
            $('.concession-card-fields').show();
        }

        dataOptions.FlagConcession = false;
    } else {
        $('.buttonno').addClass('select-btn');
        $('.buttonyes').removeClass('select-btn');
        $('.ValidConcessionCard').val(0);

        if ($('.planretailer').val() == 'Momentum' && $('.customertype').val() == 'Residential') {
            $('.concession-card-fields').hide();
        }
        if ($('.planretailer').val() == 'Alinta Energy' && $('.customertype').val() == 'Residential') {
            $('.concession-card-fields').hide();
        }
        if ($('.planretailer').val() == 'ActewAGL' && $('.customertype').val() == 'Residential') {
            $('.concession-card-fields').hide();
        }
        if ($('.planretailer').val() == 'Origin Energy' && $('.customertype').val() == 'Residential' && $('.planstate').val() != 'SA') {
            $('.concession-card-fields').hide();
        }

        $('.concession-all').addClass('display_hide');

        dataOptions.FlagConcession = true;
    }
    $('.ValidConcessionCard').parent().find('label').attr({'style':"color:#777777 !important"});
}

tenantowner = function(arg, arg1) {
    if (arg) {
        $(arg1).parent().find('input:hidden').val('Owner');
    } else {
        $(arg1).parent().find('input:hidden').val('Renter');
    }
}

nmiacqret = function(arg, arg1) {
    if (arg) {
        $(arg1).parent().find('input:hidden').val('Retention');
    } else {
        $(arg1).parent().find('input:hidden').val('Acquisition');
    }
}

mirnacqret = function(arg, arg1) {
    if (arg) {
        $(arg1).parent().find('input:hidden').val('Retention');
    } else {
        $(arg1).parent().find('input:hidden').val('Acquisition');
    }
}

checkSupplyMIRNAddress = function(arg) {
    if ($(arg).is(':checked')) {
        $(arg).parents().eq(1).next('.mirnaddress').show();
    } else {
        $(arg).parents().eq(1).next('.mirnaddress').hide();
    }
}

checkSupplyMIRNAddressSpan = function(arg) {
    if ($(arg).parents().eq(1).find('input:checkbox').is(':checked')) {
        $(arg).parents().eq(1).find('input:checkbox').prop('checked', false);
        $(arg).parents().eq(1).next('.mirnaddress').hide();
    } else {
        $(arg).parents().eq(1).find('input:checkbox').prop('checked', true);
        $(arg).parents().eq(1).next('.mirnaddress').show();
    }
}

checkSupplyMSATSAddress = function(arg) {
    if ($(arg).is(':checked')) {
        $(arg).parents().eq(1).next('.msatsaddress').show();
    } else {
        $(arg).parents().eq(1).next('.msatsaddress').hide();
    }
}

checkSupplyMSATSAddressSpan = function(arg) {
    if ($(arg).parents().eq(1).find('input:checkbox').is(':checked')) {
        $(arg).parents().eq(1).find('input:checkbox').prop('checked', false);
        $(arg).parents().eq(1).next('.msatsaddress').hide();
    } else {
        $(arg).parents().eq(1).find('input:checkbox').prop('checked', true);
        $(arg).parents().eq(1).next('.msatsaddress').show();
    }
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

act_retailer = function(arg,arg1) {
    $('.actgetdetailno').addClass('display_hide');
    $('.actretaileryes').addClass('display_hide');
    $('.actretaileryesalinta').addClass('display_hide');
    $('.actretaileryesagl').addClass('display_hide');
    if (arg) {
        $('.act_retailerbuttonno').removeClass('select-btn');
        $('.act_retailerbuttonyes').addClass('select-btn');
        $('.act_retailerno').hide();
        $('.ConcessionActRetailer').val(1);
        if ($('.planretailer').val() != 'Alinta Energy' && $('.planretailer').val() != 'Origin Energy') {
            $('.concession-card-fields').show();
        }
        if ($('.planretailer').val() == 'Origin Energy') {
            $('.actretaileryes').removeClass('display_hide');
        }
        if ($('.planretailer').val() == 'Alinta Energy') {
            $('.actretaileryesalinta').removeClass('display_hide');
        }
        if ($('.planretailer').val() == 'AGL' || $('.planretailer').val() == 'Powerdirect' || $('.planretailer').val() == 'Powerdirect and AGL') {
            $('.actretaileryesagl').removeClass('display_hide');
            $('.actretailernoagl').addClass('display_hide');
        } else {
            $('.act_retailerno').addClass('display_hide');
        }
        dataOptions.FlagConcession = false;
    } else {
        $('.act_retailerbuttonno').addClass('select-btn');
        $('.act_retailerbuttonyes').removeClass('select-btn');
        $('.ConcessionActRetailer').val(0);
        $('.concession-card-fields').hide();
        $('.actcustomerdetialno').removeClass('select-btn');
        $('.actcustomerdetialyes').removeClass('select-btn');
        if ($('.planretailer').val() == 'Alinta Energy') {
            $('.actretaileryesalinta').addClass('display_hide');
        }
        if ($('.planretailer').val() == 'AGL' || $('.planretailer').val() == 'Powerdirect' || $('.planretailer').val() == 'Powerdirect and AGL') {
            $('.actretaileryesagl').addClass('display_hide');
            $('.actretailernoagl').removeClass('display_hide');
        } else {
            $('.act_retailerno').removeClass('display_hide');
        }
        $('.ConcessionVicCustomerdetial').val(0);
        dataOptions.FlagConcession = true;
    }
}

actresidence = function(arg) {
    if (arg) {
        $('.actresidenceno').hide();
    } else {
        $('.actresidenceno').show();
    }
}

actgetdetail = function(arg) {
    if (arg) {
        $('.actcustomerdetialno').removeClass('select-btn');
        $('.actcustomerdetialyes').addClass('select-btn');
        $('.ConcessionActCustomerdetial').val(1);
        $('.concession-card-fields').show();
        dataOptions.FlagConcession = true;
    } else {
        $('.actcustomerdetialno').addClass('select-btn');
        $('.actcustomerdetialyes').removeClass('select-btn');
        $('.ConcessionActCustomerdetial').val(0);
        $('.concession-card-fields').hide();
        dataOptions.FlagConcession = true;
    }
}

vic_retailer = function(arg,arg1) {
    $('.vicretaileryes').addClass('display_hide');
    $('.vic_retailerno').addClass('display_hide');
    $('.vicretaileryes').addClass('display_hide');
    $('.vicretaileryesagl').addClass('display_hide');
    $('.vicretailernoagl').addClass('display_hide');
    if (arg) {
        $('.vic_retailerbuttonno').removeClass('select-btn');
        $('.vic_retailerbuttonyes').addClass('select-btn');
        $('.ConcessionVicRetailer').val(1);
        if ($('.planretailer').val() == 'Origin Energy') {
            $('.vicretaileryes').removeClass('display_hide');
        }
        if (!$('.viclumomsg').is(":visible") && $('.planretailer').val() != 'Alinta Energy' && $('.planretailer').val() != 'Powershop') {
            $('.concession-card-fields').show();
        }
        if ($('.planretailer').val() == 'AGL' || $('.planretailer').val() == 'Powerdirect' || $('.planretailer').val() == 'Powerdirect and AGL') {
            $('.vicretaileryesagl').removeClass('display_hide');
            $('.vicretailernoagl').addClass('display_hide');
        } else {
            $('.vic_retailerno').addClass('display_hide');
        }
        dataOptions.FlagConcession = false;
    } else {
        $('.vic_retailerbuttonno').addClass('select-btn');
        $('.vic_retailerbuttonyes').removeClass('select-btn');
        $('.ConcessionVicRetailer').val(0);
        $('.viccustomerdetialno').removeClass('select-btn');
        $('.viccustomerdetialyes').removeClass('select-btn');
        if ($('.planretailer').val() == 'Origin Energy') {
            $('.vicretaileryes').addClass('display_hide');
        }
        if ($('.planretailer').val() == 'AGL' || $('.planretailer').val() == 'Powerdirect' || $('.planretailer').val() == 'Powerdirect and AGL') {
            $('.vicretaileryesagl').addClass('display_hide');
            $('.vicretailernoagl').removeClass('display_hide');
        } else {
            $('.vic_retailerno').removeClass('display_hide');
        }
        $('.ConcessionVicCustomerdetial').val(0);
        $('.concession-card-fields').hide();
        dataOptions.FlagConcession = true;
    }
}

vicresidence = function(arg) {
    $('.viccustomerdetialno').removeClass('select-btn');
    $('.viccustomerdetialyes').removeClass('select-btn');
    if (arg) {
        $('.vicresidenceno').hide();
    } else {
        $('.vicresidenceno').show();
    }
}

vicgetdetail = function(arg) {
    if (arg) {
        $('.viccustomerdetialno').removeClass('select-btn');
        $('.viccustomerdetialyes').addClass('select-btn');
        $('.ConcessionVicCustomerdetial').val(1);
        $('.concession-card-fields').show();
        dataOptions.FlagConcession = true;
    } else {
        $('.viccustomerdetialno').addClass('select-btn');
        $('.viccustomerdetialyes').removeClass('select-btn');
        $('.ConcessionVicCustomerdetial').val(0);
        $('.concession-card-fields').hide();
        dataOptions.FlagConcession = true;
    }
}

vicdisclose = function(arg) {
    if (arg) {
        $('.ConcessionVicDisclose').val(1);
        $('.concession-card-fields').show();
        $('.vic_discloseyes').removeClass('display_hide');
        $('.vic_discloseno').addClass('display_hide');
        dataOptions.FlagConcession = true;
    } else {
        $('.ConcessionVicDisclose').val(0);
        $('.concession-card-fields').hide();
        $('.vic_discloseyes').addClass('display_hide');
        $('.vic_discloseno').removeClass('display_hide');
        dataOptions.FlagConcession = true;
    }
}

nsw_retailer = function(arg,arg1) {
    $('.nswretaileryes').addClass('display_hide');
    $('.nswretaileryesalinta').addClass('display_hide');
    $('.nswretaileryesagl').addClass('display_hide');
    $('.nswretailernoagl').addClass('display_hide');
    if (arg) {
        $('.nsw_retailerbuttonno').removeClass('select-btn');
        $('.nsw_retailerbuttonyes').addClass('select-btn');
        $('.ConcessionNswRetailer').val(1);
        if ($('.planretailer').val() == 'Origin Energy') {
            $('.nswretaileryes').removeClass('display_hide');
        }
        if ($('.planretailer').val() != 'Alinta Energy' && $('.planretailer').val() != 'Powershop') {
            $('.concession-card-fields').show();
        }
        if ($('.planretailer').val() == 'Alinta Energy') {
            $('.nswretaileryesalinta').removeClass('display_hide');
        }
        if ($('.planretailer').val() == 'AGL' || $('.planretailer').val() == 'Powerdirect' || $('.planretailer').val() == 'Powerdirect and AGL') {
            $('.nswretaileryesagl').removeClass('display_hide');
            $('.nswretailernoagl').addClass('display_hide');
        } else {
            $('.nsw_retailerno').addClass('display_hide');
        }
        dataOptions.FlagConcession = false;
    } else {
        $('.nsw_retailerbuttonno').addClass('select-btn');
        $('.nsw_retailerbuttonyes').removeClass('select-btn');
        $('.ConcessionNswRetailer').val(0);
        $('.nswcustomerdetialno').removeClass('select-btn');
        $('.nswcustomerdetialyes').removeClass('select-btn');
        if ($('.planretailer').val() == 'Origin Energy') {
            $('.nswretaileryes').addClass('display_hide');
        }
        if ($('.planretailer').val() == 'Alinta Energy') {
            $('.nswretaileryesalinta').addClass('display_hide');
        }
        if ($('.planretailer').val() == 'AGL' || $('.planretailer').val() == 'Powerdirect' || $('.planretailer').val() == 'Powerdirect and AGL') {
            $('.nswretaileryesagl').addClass('display_hide');
            $('.nswretailernoagl').removeClass('display_hide');
        } else {
            $('.nsw_retailerno').removeClass('display_hide');
        }
        $('.ConcessionNswCustomerdetial').val(0);
        $('.concession-card-fields').hide();
        dataOptions.FlagConcession = true;
    }
}

nswresidence = function(arg) {
    if (arg) {
        $('.nswresidenceno').hide();
    } else {
        $('.nswresidenceno').show();
    }
}

nswgetdetail = function(arg) {
    if (arg) {
        $('.nswcustomerdetialno').removeClass('select-btn');
        $('.nswcustomerdetialyes').addClass('select-btn');
        $('.ConcessionNswCustomerdetial').val(1);
        $('.concession-card-fields').show();
        dataOptions.FlagConcession = true;
    } else {
        $('.nswcustomerdetialno').addClass('select-btn');
        $('.nswcustomerdetialyes').removeClass('select-btn');
        $('.ConcessionNswCustomerdetial').val(0);
        $('.concession-card-fields').hide();
        dataOptions.FlagConcession = true;
    }
}

nswdisclose = function(arg) {
    if (arg) {
        $('.ConcessionNswDisclose').val(1);
        $('.concession-card-fields').show();
        $('.nsw_discloseyes').removeClass('display_hide');
        $('.nsw_discloseno').addClass('display_hide');
        dataOptions.FlagConcession = true;
    } else {
        $('.ConcessionNswDisclose').val(0);
        $('.concession-card-fields').hide();
        $('.nsw_discloseyes').addClass('display_hide');
        $('.nsw_discloseno').removeClass('display_hide');
        dataOptions.FlagConcession = true;
    }
}

qld_retailer = function(arg) {
    $('.qldresidencebuttonno').removeClass('select-btn');
    $('.qldresidencebuttonyes').removeClass('select-btn');
    $('.qldretaileryes').addClass('display_hide');
    $('.qldretaileryesalinta').addClass('display_hide');
    $('.qldretailernoagl').addClass('display_hide');
    $('.qldconfirmprincipal').addClass('display_hide');
    $('.qldconfirmspouse').addClass('display_hide');
    $('.qldresidenceno').addClass('display_hide');
    if (arg) {
        $('.qldretailerbuttonno').removeClass('select-btn');
        $('.qldretailerbuttonyes').addClass('select-btn');
        $('.ConcessionQldRetailer').val(1);
        if ($('.planretailer').val() == 'Alinta Energy' || $('.planretailer').val() == 'Origin Energy') {
            $('.qldretailerno').addClass('display_hide');
        }
        if ($('.planretailer').val() == 'Alinta Energy') {
            $('.qldretaileryesalinta').removeClass('display_hide');
        }
        if ($('.planretailer').val() == 'Origin Energy') {
            $('.concession-card-fields').show();
        }
        if ($('.planretailer').val() == 'AGL' || $('.planretailer').val() == 'Powerdirect' || $('.planretailer').val() == 'Powerdirect and AGL') {
            $('.qldretaileryes').removeClass('display_hide');
            $('.qldretailernoagl').addClass('display_hide');
        } else {
            $('.qldretailerno').addClass('display_hide');
        }
        dataOptions.FlagConcession = false;
    } else {
        $('.qldretailerbuttonno').addClass('select-btn');
        $('.qldretailerbuttonyes').removeClass('select-btn');
        $('.ConcessionQldRetailer').val(0);
        if ($('.planretailer').val() == 'Alinta Energy' || $('.planretailer').val() == 'Origin Energy') {
            $('.qldretailerno').removeClass('display_hide');
        }
        if ($('.planretailer').val() == 'Alinta Energy') {
            $('.qldretaileryesalinta').addClass('display_hide');
        }
        if ($('.planretailer').val() == 'Origin Energy') {
            $('.concession-card-fields').hide();
        }
        if ($('.planretailer').val() == 'AGL' || $('.planretailer').val() == 'Powerdirect' || $('.planretailer').val() == 'Powerdirect and AGL') {
            $('.qldretaileryes').addClass('display_hide');
            $('.qldretailernoagl').removeClass('display_hide');
        } else {
            $('.qldretailerno').removeClass('display_hide');
        }

        dataOptions.FlagConcession = true;
    }
}

qldresidence = function(arg) {
    $('.qldretailernoagl').addClass('display_hide');
    $('.qldcustomerdetialno').removeClass('select-btn');
    $('.qldcustomerdetialyes').removeClass('select-btn');
    $('.concession-card-fields').hide();
    if (arg) {
        $('.qldresidencebuttonno').removeClass('select-btn');
        $('.qldresidencebuttonyes').addClass('select-btn');
        $('.qldresidenceno').addClass('display_hide');
        $('.ConcessionQldResidence').val(1);

        dataOptions.FlagConcession = false;
    } else {
        $('.qldresidencebuttonno').addClass('select-btn');
        $('.qldresidencebuttonyes').removeClass('select-btn');
        $('.qldresidenceno').removeClass('display_hide');

        $('.ConcessionQldResidence').val(0);

        dataOptions.FlagConcession = true;
    }
}

qldgetdetail = function(arg) {
    $('.concession-card-fields').hide();
    $('.qldretailernoagl').addClass('display_hide');
    if (arg) {
        $('.qldcustomerdetialno').removeClass('select-btn');
        $('.qldcustomerdetialyes').addClass('select-btn');
        $('.ConcessionQldCustomerdetial').val(1);
        if ($('.planretailer').val() == 'Origin Energy') {
            $('.qldconfirmspouse').removeClass('display_hide');
            //$('.concession-card-fields').show();
        }
        dataOptions.FlagConcession = true;
    } else {
        $('.qldcustomerdetialno').addClass('select-btn');
        $('.qldcustomerdetialyes').removeClass('select-btn');
        $('.ConcessionQldCustomerdetial').val(0);
        if ($('.planretailer').val() == 'Origin Energy') {
            $('.qldconfirmspouse').addClass('display_hide');
        }
        dataOptions.FlagConcession = true;
    }
}

qldnotspouse = function(arg) {
    if (arg) {

    } else {
        if ($('.planretailer').val() == 'Origin Energy') {
            $('.concession-card-fields').show();

        }
    }
}

qld_confirmspouse = function(arg) {
    $('.concession-card-fields').hide();
    $('.qldretailernoagl').addClass('display_hide');
    if (arg) {
        $('.qldconfirmspouseno').removeClass('select-btn');
        $('.qldconfirmspouseyes').addClass('select-btn');
        $('.ConcessionQldConfirmSpouse').val(1);
        if ($('.planretailer').val() == 'Origin Energy') {
            $('.concession-card-fields').show();
        }
        dataOptions.FlagConcession = true;
    } else {
        $('.qldconfirmspouseno').addClass('select-btn');
        $('.qldconfirmspouseyes').removeClass('select-btn');
        $('.ConcessionQldConfirmSpouse').val(0);
        dataOptions.FlagConcession = true;
    }
}

qldaddconcession = function(arg) {
    $('.qldaddconcessionno').removeClass('select-btn');
    $('.qldaddconcessionyes').removeClass('select-btn');
    $('.concession-card-fields').hide();
    $('.qldretailernoagl').addClass('display_hide');
    if (arg) {
        $('.qldaddconcessionyes').addClass('select-btn');
        $('.collectanduse').removeClass('display_hide');
        $('.understandandauthorise').removeClass('display_hide');
        $('.concession-card-fields').show();

        $('.ConcessionQldAddconcession').val(1);
        dataOptions.FlagConcession = true;
    } else {
        $('.qldaddconcessionno').addClass('select-btn');
        $('.collectanduse').addClass('display_hide');
        $('.understandandauthorise').addClass('display_hide');
        $('.concession-card-fields').hide();

        $('.ConcessionQldAddconcession').val(0);
        dataOptions.FlagConcession = true;
    }
}

act_clear = function () {
    $('.actcustomerdetialno').removeClass('select-btn');
    $('.actcustomerdetialyes').removeClass('select-btn');
    $('.act_retailerbuttonno').removeClass('select-btn');
    $('.act_retailerbuttonyes').removeClass('select-btn');
    $('.act_retailerno').hide();
    $('.actretailer').show();
    $('.actretaileryes').addClass('display_hide');
}

vic_clear = function () {
    $('.viccustomerdetialno').removeClass('select-btn');
    $('.viccustomerdetialyes').removeClass('select-btn');
    $('.vic_retailerbuttonno').removeClass('select-btn');
    $('.vic_retailerbuttonyes').removeClass('select-btn');
    $('.vic_retailerno').addClass('display_hide');
    $('.vicaglmsg').hide();
    $('.viceamsg').hide();
    $('.vicdefaultmsg').hide();
    $('.vicretailer').hide();
    $('.viclumomsg').hide();
    $('.vicpowershop').hide();
    $('.vicretaileryes').addClass('display_hide');
    $('.vicretaileryesagl').addClass('display_hide');
    if ($('.customertype').val() == 'Residential') {
        if ($('.planretailer').val() == 'AGL' || $('.planretailer').val() == 'Powerdirect' || $('.planretailer').val() == 'Powerdirect and AGL') {
            $('.vicaglmsg').show();
            $('.vicretailer').show();
        } else if ($('.planretailer').val() == 'Energy Australia') {
            $('.viceamsg').show();
        } else if ($('.planretailer').val() == 'Lumo Energy') {
            $('.viclumomsg').show();
        }
    } else {
        if ($('.planretailer').val() == 'Lumo Energy') {
            $('.viclumomsg').show();
        } else {
            $('.vicdefaultmsg').show();
            $('.vicretailer').show();
        }
    }
    if ($('.planretailer').val() == 'Powershop') {
        $('.vicpowershop').show();
    }
}

nsw_clear = function () {
    $('.nswcustomerdetialno').removeClass('select-btn');
    $('.nswcustomerdetialyes').removeClass('select-btn');
    $('.nsw_retailerbuttonno').removeClass('select-btn');
    $('.nsw_retailerbuttonyes').removeClass('select-btn');
    $('.nswaglmsg').hide();
    $('.nsweamsg').hide();
    $('.nswdefaultmsg').hide();
    $('.nswretailer').hide();
    $('.nswlumomsg').hide();
    $('.nswpowershop').hide();
    if ($('.customertype').val() == 'Residential') {
        $('.nswdefault').show();
        if ($('.planretailer').val() == 'AGL' || $('.planretailer').val() == 'Powerdirect') {
            $('.nswaglmsg').show();
            $('.nswretailer').show();
        } else if ($('.planretailer').val() == 'Energy Australia') {
            $('.nsweamsg').show();
        } else if ($('.planretailer').val() == 'Lumo Energy') {
            $('.nswlumomsg').show();
        }
    } else {
        if ($('.planretailer').val() == 'Lumo Energy') {
            $('.nswlumomsg').show();
        } else {
            $('.nswdefaultmsg').show();
            $('.nswretailer').show();
        }
    }
    if ($('.planretailer').val() == 'Powershop') {
        $('.nswpowershop').show();
    }
    $('.nswretaileryes').addClass('display_hide');
    $('.nswretaileryesalinta').addClass('display_hide');
    $('.nswretaileryesagl').addClass('display_hide');
}

qld_clear = function () {
    $('.qldresidencebuttonno').removeClass('select-btn');
    $('.qldresidencebuttonyes').removeClass('select-btn');
    $('.qldconfirmspouse').addClass('display_hide');
    $('.qldresidenceno').addClass('display_hide');
    $('.concession-card-fields').hide();
    $('.qldretailerbuttonno').removeClass('select-btn');
    $('.qldretailerbuttonyes').removeClass('select-btn');
    $('.qldretailerno').addClass('display_hide');
    $('.qldaglmsg').hide();
    $('.qldeamsg').hide();
    $('.qlddefaultmsg').hide();
    $('.qldpowershop').hide();
    if ($('.customertype').val() == 'Residential') {
        if ($('.planretailer').val() == 'AGL' || $('.planretailer').val() == 'Powerdirect' || $('.planretailer').val() == 'Powerdirect and AGL') {
            $('.qldaglmsg').show();
        } else if ($('.planretailer').val() == 'Energy Australia') {
            $('.qldeamsg').show();
        } else {
            $('.qlddefaultmsg').show();
        }
    } else {
        $('.qlddefaultmsg').show();
    }

    if ($('.planretailer').val() == 'Powershop') {
        $('.qldpowershop').show();
    }
}

sa_clear = function() {
    $('.contactdepartmentmsg').hide();
    $('.contacthotlinemsg').hide();
    $('.viadepartmentmsg').hide();
    $('.sapowershop').hide();
    if ($('.customertype').val() == 'Residential') {
        if ($('.planretailer').val() != 'AGL' && $('.planretailer').val() != 'Powerdirect' && $('.planretailer').val() != 'Powerdirect and AGL' && $('.planretailer').val() != 'ERM' && $('.planretailer').val() != 'Next Business Energy' && $('.planretailer').val() != 'Origin Energy' && $('.planretailer').val() != 'Alinta Energy') {
            $('.contactdepartmentmsg').show();
        }
        if ($('.planretailer').val() == 'Origin Energy') {
            $('.contacthotlinemsg').show();
        }
        if ($('.planretailer').val() == 'AGL' || $('.planretailer').val() == 'Powerdirect' || $('.planretailer').val() == 'Powerdirect and AGL') {
            $('.viadepartmentmsg').show();
        }
    }
    if ($('.planretailer').val() == 'Powershop') {
        $('.sapowershop').show();
    }
}

ebilling = function(arg) {
    $('.ebillingemail').hide();
    $('.momentumebillingno').hide();
    if (arg) {
        var email = $('#contact_email').val();
        if (!email || email == 'no@email.com.au') {
            $('.ebillingemail').show();
        }
    } else {
        if ($('.planretailer').val() == 'Sumo Power') {
        } else if ($('.planretailer').val() == 'Origin Energy') {

        }
        if ($('#plan_product_name').val() == 'Smile Power Plus') {
            $('#e_billing_field').addClass('required-yes');
        } else {
            $('#e_billing_field').removeClass('required-yes');
        }
    }
}

ebillingEA = function(arg) {
    $('.ebillingemail').hide();
    if (arg) {
        var email = $('#contact_email').val();
        if (!email || email == 'no@email.com.au') {
            $('.ebillingemail').show();
        }
    }
}

origin_lpg_property = function(arg) {
    $('.lpg_available').hide();
    $('.origin_lpg_property').val(0);
    if (arg) {
        $('.origin_lpg_property').val(1);
        var state = $('.planstate').val();
        var postcode = $('#plan_postcode').val();
        var suburb = $('#plan_suburb').val();
        $("#process_modal").modal('show');
        $.ajax({
            type: "POST",
            url: "/customers/origin_lpg_lookup",
            data: {state: state, postcode: postcode, suburb: suburb},
            dataType:"json",
            async: false,
            success: function(data) {
                $("#process_modal").modal('hide');
                if (data.exchange_lead_type) {
                    if (data.exchange_lead_type == 'Authorised dealer Lead' || data.exchange_lead_type == 'Origin Lead') {
                        $('.lpg_not_available').hide();
                        $('.lpg_available').show();
                    } else {
                        $('.lpg_not_available').show();
                        $('.lpg_available').hide();
                        $('#lpg_date').val('');
                    }
                } else {
                    $('.lpg_not_available').show();
                    $('.lpg_available').hide();
                    $('#lpg_date').val('');
                }
            }
        });
    }
}

hearfeeschargesnow = function(arg) {
    $('.hearfeeschargesnowyes').hide();
    if (arg) {
        $('.hearfeeschargesnowyes').show();
    }
}

how_many_people = function(arg) {
    $('.PlanHowManyPeople').val(arg);
}

how_many_bedrooms = function(arg) {
    $('.PlanHowManyBedrooms').val(arg);
}

appliance_usage_level = function(arg) {
    $('.PlanApplianceUsageLevel').val(arg);
}

how_often_at_home = function(arg) {
    $('.PlanHowOftenAtHome').val(arg);
    var high = 0;
    if ($('.PlanHowManyPeople').val() == 3) {
        high++;
    }
    if ($('.PlanHowManyBedrooms').val() == 3) {
        high++;
    }
    if ($('.PlanApplianceUsageLevel').val() == 3) {
        high++;
    }
    if ($('.PlanHowOftenAtHome').val() == 3) {
        high++;
    }
    $('.notqualify').hide();
    if (high < 3) {
        $('.notqualify').show();
    }
}

planreconfirmrates = function(arg) {
    $('.agreetorates').hide();
    $('.sumoratesyes').hide();
    $('.sumoratesno').hide();
    $('.rates_measured').hide();
    if (arg) {
        $('.reconfirmratesyes').show();
        $('.agreetorates').show();
        if ($('.planretailer').val() == 'Sumo Power' && $('.planstate').val() == 'VIC') {
            $('.sumoratesyes').show();
        }
        if ($('.planretailer').val() != 'Lumo Energy') {
            $('.rates_measured').show();
        }
    } else {
        $('.reconfirmratesyes').hide();
        if ($('.planretailer').val() == 'Sumo Power' && $('.planstate').val() == 'VIC') {
            $('.sumoratesno').show();
        }
    }
}

planelectricitydisconnected = function(arg) {
    if (arg) {
        $('.electricitydisconnectedyes').show();
    } else {
        $('.electricitydisconnectedyes').hide();
    }
}

plansolarpanels = function(arg) {
    $('.solarpanelsyes').hide();
    $('.aglpd-solar').hide();
    $('.origin-solar').hide();
    $('.momentum-solar').hide();
    if (arg) {
        if ($('.planretailer').val() == 'Origin Energy') {
            $('.origin-solar').show();
        } else if ($('.planretailer').val() == 'Momentum') {
            $('.momentum-solar').show();
        } else if ($('.planretailer').val() == 'Alinta Energy') {
        } else if ($('.planretailer').val() == 'AGL' || $('.planretailer').val() == 'Powerdirect' || $('.planretailer').val() == 'Powerdirect and AGL') {
            if ($('.aglpd-solar').length > 0) {
                $('.aglpd-solar').show();
            }
        }
        $('.solarpanelsyes').show();
        $('.PlanSolarPanels').val(1);
    } else {
        $('.PlanSolarPanels').val(0);
    }
}

operatingbusiness = function(arg) {
    if (arg == 1) {
        $('.operatingbusinessno').hide();
    } else {
        $('.operatingbusinessno').show();
    }
}

smartmeter = function(arg) {
    if (arg == 1) {
        $('.smartmeterno').hide();
    } else {
        $('.smartmeterno').show();
    }
}

howreceivewelcomepack = function(arg) {
    if (arg == 1) {
        if ($('#contact_email').val() == 'no@email.com.au') {
            $('.sumonoemail').hide();
        }
        if ($('.planstate').val() == 'VIC') {
            $('.paperbill').hide();
        }
    } else {
        if ($('#contact_email').val() == 'no@email.com.au') {
            $('.sumonoemail').show();
        }
        if ($('.planstate').val() == 'VIC') {
            $('.paperbill').show();
        }
    }
}

australiapostsumo = function(arg) {
    $('.sumoaustraliapostyes').hide();
    if (arg == 1) {
        if ($('.planstate').val() != 'NSW') {
            $('.sumoaustraliapostyes').show();
        }
    }
}

marketingoptoutsumo = function(arg) {
    if (arg == 1) {
        $('.marketingoptoutsumoyes').show();
        //$('.paperbill').hide();
    } else {
        $('.marketingoptoutsumoyes').hide();
    }
}

otherfamilyfriends = function(arg) {
    if (arg == 1) {
        $('.otherfamilyfriendsyes').show();
    } else {
        $('.otherfamilyfriendsyes').hide();
    }
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

worksplanned = function(arg) {
    if (arg == 1) {
        $('.movein_special_checked').removeClass('display_hide');
    } else {
        $('.movein_special_checked').addClass('display_hide');
    }
}

meterhazard = function(arg) {
    if (arg == 1) {
        if ($('.planretailer').val() == 'Momentum') {
            $('.moveinhazardsmomentum').show();
        } else if($('.planretailer').val() != 'Elysian Energy') {
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

ovo_energy_payments = function(arg) {
    if (arg) {
        $('.ovoenergypayments').val(1);
        $('.ovoenergypaymentsyes').show();
    } else {
        $('.ovoenergypayments').val(0);
        $('.ovoenergypaymentsyes').hide();
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
    if ($('.planelecproduct').length > 0) {
        if (!checkselectbox('.planelecproduct')) {
            flag = false;
        }
        if ($('.planelecproduct').val() == 'MAXI 12' || $('.planelecproduct').val() == 'MAXI 17' || $('.planelecproduct').val() == 'MAXI 22') {
            $('.noemail-btn').hide();
        } else {
            $('.noemail-btn').show();
        }
    }
    if ($('.plangasproduct').length > 0) {
        if (!checkselectbox('.plangasproduct')) {
            flag = false;
        }
        if ($('.plangasproduct').val() == 'MAXI 8' || $('.plangasproduct').val() == 'MAXI 9' || $('.plangasproduct').val() == 'MAXI 16' || $('.plangasproduct').val() == 'MAXI 22') {
            $('.noemail-btn').hide();
        } else {
            $('.noemail-btn').show();
        }
    }
    if ($('.metertype').is(":visible")) {
        if (!checkselectbox('.selectmetertype')) {
            flag = false;
        }
    }
    if (!checkselectbox('.selectpropertytype')) {
        flag = false;
    }
    if ($('.reconfirmrates').length > 0 && $('.reconfirmrates').is(":visible")) {
        if (!checkYesNoValidation('.plan_reconfirm_rates')) {
            flag = false;
        }
    }
    if ($('.plan_market_contract_rates').length > 0) {
        if (!checkYesNoValidation('.plan_market_contract_rates')) {
            flag = false;
        }
    }
    if ($('.electricitydisconnected').length > 0) {
        if (!checkYesNoValidation('.plan_electricity_disconnected')) {
            flag = false;
        }
    }
    if ($('.solarpanels').is(":visible")) {
        if (!checkYesNoValidation('.plan_solar_panels')) {
            flag = false;
        }
    }
    if ($('.agreetorates').is(":visible")) {
        if (!checkYesNoValidation('.plan_understand_agree_rates')) {
            flag = false;
        }
    }
    if ($('.plan_bluenrg_elec_business').length > 0) {
        if (!checkYesNoValidation('.plan_bluenrg_elec_business')) {
            flag = false;
        }
    }
    if ($('.plan_bluenrg_elec_business').length > 0) {
        if (!checkYesNoValidation('.plan_bluenrg_elec_business')) {
            flag = false;
        }
    }
    if ($('.plan_operating_business').length > 0) {
        if (!checkYesNoValidation('.plan_operating_business')) {
            flag = false;
        }
    }
    if ($('.plan_smart_meter').length > 0) {
        if (!checkYesNoValidation('.plan_smart_meter')) {
            flag = false;
        }
    }
    if (arg === undefined ) {
        arg = false;
    }
    if (arg) {
        return flag;
    }
    if (flag && $('#plan_lead_id').val() == '' && $('#lead_imported').val() == 0) {
        var r = confirm("Are you sure you haven't go a fresh lead in your system for this customer?");
        if (!r) {
            flag = false;
        }
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
    if ($('.contactauthorised').is(":visible")) {
        if (!checkYesNoValidation('.contact_authorised')) {
            flag = false;
        }
    }
    if (!checkselectbox('.selectcontacttitle')) {
        flag = false;
    }
    if (!$('.signup_contact').valid()) {
        flag = false;
    }
    if ($('.actewagl_email_bill').length > 0 && $('.actewagl_email_bill').is(':visible')) {
        if (!checkYesNoValidation('.contact_actewagl_email_bill')) {
            flag = false;
        }
    }
    if ($('.contact_welcome_pack_simply_energy').length > 0 && $('.contact_welcome_pack_simply_energy').is(':visible')) {
        if (!checkYesNoValidation('.contact_welcome_pack_simply_energy')) {
            flag = false;
        }
    }
    if ($('.contact_agl_email_bill').is(':visible')) {
        if (!checkYesNoValidation('.contact_agl_email_bill')) {
            flag = false;
        }
    }
    if ($('.pd_email_bill').is(':visible')) {
        if (!checkYesNoValidation('.contact_powerdirect_email_bill')) {
            flag = false;
        }
    }
    if ($('.pd_agl_email_bill').is(':visible')) {
        if (!checkYesNoValidation('.contact_pd_agl_email_bill')) {
            flag = false;
        }
    }
    if ($('.receivebillsbypost').is(':visible')) {
        if (!checkYesNoValidation('.contact_receive_bills_by_post')) {
            flag = false;
        }
    }
    if ($('.origin_email_bill').is(':visible')) {
        if (!checkYesNoValidation('.contact_origin_email_bill')) {
            flag = false;
        }
    }
    if ($('.EA_email_bill').is(':visible')) {
        if (!checkYesNoValidation('.contact_EA_email_bill')) {
            flag = false;
        }
    }
    if ($('.erm_email_bill').is(':visible')) {
        if (!checkYesNoValidation('.contact_erm_email_bill')) {
            flag = false;
        }
    }
    if ($('.alinta_secondary_account').is(':visible')) {
        if (!checkYesNoValidation('.contact_alinta_secondary_account')) {
            flag = false;
        }
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
    if ($('.secondary-contact').is(':visible')) {
        if ($('#contact_secondary_mobile').val() == '' && $('#contact_secondary_home_phone').val() == '') {
            $('#contact_secondary_mobile').addClass('error').removeClass('valid');
            $('#contact_secondary_home_phone').addClass('error').removeClass('valid');
            flag = false;
        } else {
            if ($('#contact_secondary_mobile').val()) {
                if (!(/^(04)\d{8}$/.test($('#contact_secondary_mobile').val()))) {
                    $('#contact_secondary_mobile').addClass('error').removeClass('valid');
                    flag = false;
                } else {
                    $('#contact_secondary_mobile').addClass('valid').removeClass('error');
                }
                if ($('#contact_secondary_home_phone').val() == '') {
                    $('#contact_secondary_home_phone').removeClass('error');
                }
            }
            if ($('#contact_secondary_home_phone').val()) {
                if (!(/^(02|03|07|08)\d{8}$/.test($('#contact_secondary_home_phone').val()))) {
                    $('#contact_secondary_home_phone').addClass('error').removeClass('valid');
                    flag = false;
                } else {
                    $('#contact_secondary_home_phone').addClass('valid').removeClass('error');
                }
                if ($('#contact_secondary_mobile').val() == '') {
                    $('#contact_secondary_mobile').removeClass('error');
                }
            }
        }
        var email = $('#contact_secondary_email').val();
        if (email && !reg.test(email)) {
            $('#contact_secondary_email').addClass('error').removeClass('valid');
            flag = false;
        } else {
            $('#contact_secondary_email').addClass('valid').removeClass('error');
        }
    }
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
    if ($('.planretailer').val() == 'ActewAGL') {
        if ($('.actenergyrewards25').length > 0)  {
            var email = $('#contact_email').val();
            if (!email || !reg.test(email) || email == 'no@email.com.au') {
                $('#contact_email').addClass('error').removeClass('valid');
                flag = false;
            } else {
                $('#contact_email').addClass('valid').removeClass('error');
            }
        }
    }
    if ($('#plan_product_name').val().indexOf('Origin Max Saver') !== -1) {
        var email = $('#contact_email').val();
        if (!email || !reg.test(email) || email == 'no@email.com.au') {
            $('#contact_email').addClass('error').removeClass('valid');
            flag = false;
        } else {
            $('#contact_email').addClass('valid').removeClass('error');
        }
    }
    if (($('#plan_product_name').val().indexOf('Lumo Plus') !== -1 || $('#plan_product_name').val().indexOf('Lumo Movers') !== -1) && $('.planstate').val() == 'SA') {
        var email = $('#contact_email').val();
        if (!email || !reg.test(email) || email == 'no@email.com.au') {
            $('#contact_email').addClass('error').removeClass('valid');
            flag = false;
        } else {
            $('#contact_email').addClass('valid').removeClass('error');
        }
    }
    if ($('#plan_product_name').val().indexOf('Bill Boss') !== -1) {
        var email = $('#contact_email').val();
        if (!email || !reg.test(email) || email == 'no@email.com.au') {
            $('#contact_email').addClass('error').removeClass('valid');
            flag = false;
        } else {
            $('#contact_email').addClass('valid').removeClass('error');
        }
    }
    if ($('.planretailer').val() == 'OVO Energy' || $('.planretailer').val() == 'Powershop') {
        var email = $('#contact_email').val();
        if (!email || !reg.test(email) || email == 'no@email.com.au') {
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

        var contact_title = $('#contact_title').val();
        var contact_first_name = $('#contact_first_name').val();
        var contact_last_name = $('#contact_last_name').val();
        $('#concession_title').val(contact_title);
        $('#concession_title').selectpicker('refresh');
        $('#concession_first_name').val(contact_first_name);
        $('#concession_last_name').val(contact_last_name);

        if ($('.planretailer').val() == 'Origin Energy' || $('.planretailer').val() == 'ERM' || ($('.planretailer').val() == 'Alinta Energy' && $('.customertype').val() == 'Business' && $('#business_type').val() != 'Sole Trader') || ($('.planretailer').val() == 'Next Business Energy' && $('#business_type').val() != 'Sole Trader') || ($('.planretailer').val() == 'Blue NRG' && $('#business_type').val() == 'Private')) { // Hide Identification
            if ($('.customertype').val() == 'Business') {
                dataOptions.count = 2;
                $('#accordion').accordion({active:2});
            } else {
                dataOptions.count = 4;
                $('#accordion').accordion({active:4});
            }
        } else {
            if ($('.customertype').val() == 'Business') {
                dataOptions.count = 2;
                $('#accordion').accordion({active:2});
            } else {
                dataOptions.count = 3;
                $('#accordion').accordion({active:3});
            }
        }
        return true;
    } else {
        $('#contact_form_checked').val(0);
        return false;
    }
}

checkBusinessDetailsFields =  function(arg) {
    var flag = true;
    if (!$('.signup_business_details').valid()) {
        flag = false;
    }
    if (!checkselectbox('.businesstype')) {
        flag = false;
    }
    if (arg === undefined ) {
        arg = false;
    }
    if (arg) {
        return flag;
    }
    if (flag) {
        $('#business_details_form_checked').val(1);
        if ($('.planretailer').val() == 'Origin Energy' || $('.planretailer').val() == 'ERM' || ($('.planretailer').val() == 'Alinta Energy' && $('.customertype').val() == 'Business' && $('#business_type').val() != 'Sole Trader') || ($('.planretailer').val() == 'Next Business Energy' && $('#business_type').val() != 'Sole Trader') || ($('.planretailer').val() == 'Blue NRG' && $('#business_type').val() == 'Private')) { // Hide Identification
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
        $('#business_details_form_checked').val(0);
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
        if ($('.document_state_field').is(':visible')) {
            if (!checkselectbox('.selectIdentificationDS')) {
                flag = false;
            }
        }
        if ($('.document_country_field').is(':visible')) {
            if (!checkselectbox('.selectIdentificationDC')) {
                flag = false;
            }
        }
        if ($('.document_medicare_colour_field').is(':visible')) {
            if (!checkselectbox('.selectIdentificationDMC')) {
                flag = false;
            }
        }

        var document_id = $('#document_id').val();
        if ($('#document_type').val() == 'MED') {
            if (!/^\d+$/.test(document_id) || document_id.length != 11) {
                $('#document_id').addClass('error').removeClass('valid');
                flag = false;
            } else {
                $('#document_id').addClass('valid').removeClass('error');
            }
        } else {
            if (!/^[a-z0-9]+$/i.test(document_id) || document_id.length < 5) {
                $('#document_id').addClass('error').removeClass('valid');
                flag = false;
            } else {
                $('#document_id').addClass('valid').removeClass('error');
            }
        }
    }

    if ($('.identification_credit_check_fields').is(':visible')) {
        if ($('.planretailer').val() == 'AGL' || $('.planretailer').val() == 'Powerdirect') {
        	if (!$('.identification_default_fields').is(':visible')) {
            	//flag = false;
            	if (!checkYesNoValidation('.credit_check')) {
                	flag = false;
                }
        	}
        } else if (!checkYesNoValidation('.credit_check')) {
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
        if ($('.ConcessionHouseholdRequire').val() == -1) {
            flag = false;
            $('.ConcessionHouseholdRequire').parent().find('label').attr({'style':"color:#f00 !important"});
        } else {
            $('.ConcessionHouseholdRequire').parent().find('label').attr({'style':"color:#777777 !important"});
        }
    }
    if ($('.validconcessioncard').is(':visible')) {
        if ($('.ValidConcessionCard').val() == -1) {
            flag = false;
            $('.ValidConcessionCard').parent().find('label').attr({'style':"color:#f00 !important"});
        } else {
            $('.ValidConcessionCard').parent().find('label').attr({'style':"color:#777777 !important"});
        }
    }
    if ($('.concession_qld_anyoneelse_lives').is(':visible')) {
        if (!checkYesNoValidation('.concession_qld_anyoneelse_lives')) {
            flag = false;
        }
    }
    if ($('.concession_qld_agree_terms').is(':visible')) {
        if (!checkYesNoValidation('.concession_qld_agree_terms')) {
            flag = false;
        }
    }
    if ($('.concession_vic_lumo_disclose').is(':visible')) {
        if (!checkYesNoValidation('.concession_vic_lumo_disclose')) {
            flag = false;
        }
    }
    if ($('.concession_act_residence').is(':visible')) {
        if (!checkYesNoValidation('.concession_act_residence')) {
            flag = false;
        }
    }
    if ($('.concession_vic_residence').is(':visible')) {
        if (!checkYesNoValidation('.concession_vic_residence')) {
            flag = false;
        }
    }
    if ($('.concession_nsw_residence').is(':visible')) {
        if (!checkYesNoValidation('.concession_nsw_residence')) {
            flag = false;
        }
    }
    if ($('.concession_nsw_lumo_disclose').is(':visible')) {
        if (!checkYesNoValidation('.concession_nsw_lumo_disclose')) {
            flag = false;
        }
    }
    if ($('.planretailer').val() == 'Sumo Power') {
        if ($('.SumoPower').is(':visible')) {
            if (!checkYesNoValidation('.sumo_power_understand_form')) {
                flag = false;
            }
        }
    }
    if ($('.lifesupportuserfullname').is(':visible')) {
        if (!checkselectbox('.selectConcessionLifeSupportUserTitle')) {
            flag = false;
        }
        if ($('#concession_life_support_user_first_name').val() == '') {
            flag = false;
            $('#concession_life_support_user_first_name').addClass('error').removeClass('valid');
        } else {
            $('#concession_life_support_user_first_name').addClass('valid').removeClass('error');
        }
        if ($('#concession_life_support_user_last_name').val() == '') {
            flag = false;
            $('#concession_life_support_user_last_name').addClass('error').removeClass('valid');
        } else {
            $('#concession_life_support_user_last_name').addClass('valid').removeClass('error');
        }
    }
    if ($('.machinerunby').is(':visible')) {
        if (!checkYesNoValidation('.concession_machine_run_by')) {
            flag = false;
        }
    }
    if ($('.actretailer').is(':visible')) {
        if (!checkYesNoValidation('.concession_act_retailer')) {
            flag = false;
        }
    }
    if ($('.concession_act_customerdetial').is(':visible')) {
        if (!checkYesNoValidation('.concession_act_customerdetial')) {
            flag = false;
        }
    }
    if ($('.qldconfirmspouse').is(':visible')) {
        if (!checkYesNoValidation('.concession_qld_not_spouse')) {
            flag = false;
        }
    }
    if ($('.concession-card-fields').is(':visible')) {
        if (!checkselectbox('.selectConcessionCardIssuer')) {
            flag = false;
        }
        if (!checkselectbox('.selectConcessionCardType')) {
            flag = false;
        }

        if (!checkselectbox('.selectConcessionTitle')) {
            flag = false;
        }

        var concession_card_number = $('#concession_card_number').val();
        if (/^[a-z0-9]+$/i.test(concession_card_number)) {
            $('#concession_card_number').addClass('valid').removeClass('error');
        } else {
            flag = false
            $('#concession_card_number').addClass('error').removeClass('valid');
        }

        if ($('#concession_first_name').val() == '') {
            flag = false;
            $('#concession_first_name').addClass('error').removeClass('valid');
        } else {
            $('#concession_first_name').addClass('valid').removeClass('error');
        }

        if ($('#concession_last_name').val() == '') {
            flag = false;
            $('#concession_last_name').addClass('error').removeClass('valid');
        } else {
            $('#concession_last_name').addClass('valid').removeClass('error');
        }
    }
    if ($('.momentumhouseholdrequireyes').is(':visible')) {
        flag = false;
    }
    if ($('.EAhouseholdrequireyes').is(':visible')) {
        flag = false;
    }
    if ($('.concession_qld_retailer').length > 0 && $('.concession_qld_retailer').is(':visible')) {
        if (!checkYesNoValidation('.concession_qld_retailer')) {
            flag = false;
        }
    }
    if ($('.concession_medical_heating_cooling').length > 0 && $('.concession_medical_heating_cooling').is(':visible')) {
        if (!checkYesNoValidation('.concession_medical_heating_cooling')) {
            flag = false;
        }
    }
    if ($('.concession_confirm_concession').length > 0 && $('.concession_confirm_concession').is(':visible')) {
        if (!checkYesNoValidation('.concession_confirm_concession')) {
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
    if ($('.mni_field').is(':visible')) {
        var nmi = $('.mni_field').val();
        //if (!(/^([A-Z]{2}|[0-9]{2})\d{8}$/.test(nmi))) {
        if (nmi.length != 11) {
            $('.mni_field').addClass('error').removeClass('valid');
            flag = false;
        } else {
            $('.mni_field').addClass('valid').removeClass('error');
        }
    }
    if ($('.mirn_field').is(':visible')) {
        var mirn = $('.mirn_field').val();
        //if (!(/^(50|52|53|54)\d{8,9}$/.test(mirn))) {
        if (!(/^\d{10,11}$/.test(mirn))) {
            $('.mirn_field').addClass('error').removeClass('valid');
            flag = false;
        } else {
            $('.mirn_field').addClass('valid').removeClass('error');
        }
    }
    if (!checkselectbox('.selectSupplyState')) {
        flag = false;
    }
    if ($('.selectSupplyMirnState').is(':visible')) {
        if (!checkselectbox('.selectSupplyMirnState')) {
            flag = false;
        }
    }
    if ($('.supplyunstructuredmirnaddress').is(':visible')) {
        if (!checkselectbox('.supplyunstructuredmirnaddress')) {
            flag = false;
        }
    }
    if ($('.supplyunstructuredmsatsaddress').is(':visible')) {
        if (!checkselectbox('.supplyunstructuredmsatsaddress')) {
            flag = false;
        }
    }
    if ($('.nmiacqret').is(':visible')) {
        $('.supplynmiacqret').each(function() {
            var id = $(this).attr('data-id');
            if ($(this).val() == '-1') {
                $(this).parent().find('label').attr({'style':"color:#F00 !important"});
                flag = false;
            } else {
                $(this).parent().find('label').attr({'style':"color:#777777 !important"});
            }
        });
    }
    if ($('.mirnacqret').is(':visible')) {
        $('.supplymirnacqret').each(function() {
            var id = $(this).attr('data-id');
            if ($(this).val() == '-1') {
                $(this).parent().find('label').attr({'style':"color:#F00 !important"});
                flag = false;
            } else {
                $(this).parent().find('label').attr({'style':"color:#777777 !important"});
            }
        });
    }
    $('.understand_transfer_retention').each(function() {
        if ($(this).is(':visible')) {
            var id = $(this).attr('data-id');
            if (!checkYesNoValidation('.transfer_retention_' + id)) {
                flag = false;
            }
        }
    });
    $('.supplytenantowner').each(function() {
        if ($(this).val() == '-1') {
            $(this).parent().find('label').attr({'style':"color:#F00 !important"});
            flag = false;
        } else {
            $(this).parent().find('label').attr({'style':"color:#777777 !important"});
        }
    });
    if ($('.supplyunit').val()) {
        if ($('.supplyunit').parents().eq(2).find('.supplyunittype').val() == '') {
            $('.supplyunit').parents().eq(2).find('.supplyunittype').parent().find('label').attr({'style':"color:#F00 !important"});
            flag = false;
        } else {
            $('.supplyunit').parents().eq(2).find('.supplyunittype').parent().find('label').attr({'style':"color:#777777 !important"});
        }
    }
    if ($('.supplyunitmirn').val()) {
        if ($('.supplyunitmirn').parents().eq(2).find('.supplyunittypemirn').val() == '') {
            $('.supplyunitmirn').parents().eq(2).find('.supplyunittypemirn').parent().find('label').attr({'style':"color:#F00 !important"});
            flag = false;
        } else {
            $('.supplyunitmirn').parents().eq(2).find('.supplyunittypemirn').parent().find('label').attr({'style':"color:#777777 !important"});
        }
    }
    if ($('.supplyunitmsats').val()) {
        if ($('.supplyunitmsats').parents().eq(2).find('.supplyunittypemsats').val() == '') {
            $('.supplyunitmsats').parents().eq(2).find('.supplyunittypemsats').parent().find('label').attr({'style':"color:#F00 !important"});
            flag = false;
        } else {
            $('.supplyunitmsats').parents().eq(2).find('.supplyunittypemsats').parent().find('label').attr({'style':"color:#777777 !important"});
        }
    }
    if ($('.supplysecondaryunit').val()) {
        if ($('.supplysecondaryunit').parents().eq(2).find('.supplysecondaryunittype').val() == '') {
            $('.supplysecondaryunit').parents().eq(2).find('.supplysecondaryunittype').parent().find('label').attr({'style':"color:#F00 !important"});
            flag = false;
        } else {
            $('.supplysecondaryunit').parents().eq(2).find('.supplysecondaryunittype').parent().find('label').attr({'style':"color:#777777 !important"});
        }
    }
    if ($('.understand_transfer_retention_retain').is(':visible')) {
        flag = false;
    }
    if (!checkYesNoValidation('.billing_address_different')) {
        flag = false;
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
    if ($('.consumptiondata').is(":visible")) {
        if ($('#elec_consumption_data').length > 0) {
            if ($('#elec_consumption_data').val() == '') {
                $('#elec_consumption_data').addClass('error').removeClass('valid');
                flag = false;
            }

        }
        if ($('#gas_consumption_data').length > 0) {
            if ($('#gas_consumption_data').val() == '') {
                $('#gas_consumption_data').addClass('error').removeClass('valid');
                flag = false;
            }
        }
    }
    if ($('.billing_default_fields').is(':visible')) {
        if ($('.e_billing').is(':visible')) {
            if (!checkYesNoValidation('.billing_e_billing')) {
                flag = false;
            }
        }
        if ($('.e_billing_momentum').is(':visible')) {
            if (!checkYesNoValidation('.billing_e_billing_momentum')) {
                flag = false;
            }
        }
        if ($('.electronic').is(':visible')) {
            if (!checkYesNoValidation('.billing_electronic')) {
                flag = false;
            }
        }
        if ($('.billing_direct_debit').length > 0) {
            if (!checkYesNoValidation('.billing_direct_debit')) {
                flag = false;
            }
        }
        if ($('.marketing_opt_out').is(':visible')) {
            if (!checkYesNoValidation('.billing_marketing_opt_out')) {
                flag = false;
            }
        }
        if ($('.ermbilling').is(':visible')) {
            if (!checkYesNoValidation('.billing_erm_understand_exit_fee')) {
                flag = false;
            }
            if (!checkYesNoValidation('.billing_erm_credit_check')) {
                flag = false;
            }
            if (!checkYesNoValidation('.billing_erm_understand_structure')) {
                flag = false;
            }
            if (!checkYesNoValidation('.billing_erm_consent_transfer')) {
                flag = false;
            }
            if (!checkYesNoValidation('.billing_erm_receive_communications')) {
                flag = false;
            }
        }
        if ($('.lumobilling').is(":visible")) {
            if (!checkYesNoValidation('.billing_ezpay')) {
                flag = false;
            }
        }
        if ($('.sumovalidemail').is(":visible")) {
            if (!checkYesNoValidation('.billing_how_receive_welcome_pack')) {
                flag = false;
            }
        }
        if ($('.sumomarketingoptout').is(":visible")) {
            if (!checkYesNoValidation('.marketing_opt_out_sumo')) {
                flag = false;
            }
        }
    }
    else if ($('.billing_powershop_fields').is(':visible')) {

    }
    else if ($('.billing_EA_fields').is(':visible')) {
        if (!checkYesNoValidation('.hear_fees_charges_now_EA')) {
            flag = false;
        }
    }
    if ($('.billingallowactewagluse').is(":visible")) {
        if (!checkYesNoValidation('.billing_allow_actewagl_use')) {
            flag = false;
        }
    }
    if ($('.billing_carbon_neutral_consent').length > 0) {
        if (!checkYesNoValidation('.billing_carbon_neutral_consent')) {
            flag = false;
        }
    }
    if ($('.understand_accessing').length > 0) {
        if (!checkYesNoValidation('.billing_understand_accessing')) {
            flag = false;
        }
    }
    if ($('.origin_lpg_property').length > 0) {
        if (!checkYesNoValidation('.billing_origin_lpg_property')) {
            flag = false;
        }
    }
    if (!checkYesNoValidation('.other_family_friends')) {
        flag = false;
    }

    if ($('.confirm_annual_gas').length > 0) {
        if (!checkYesNoValidation('.confirm_annual_gas')) {
            flag = false;
        }
    }
    if ($('.ebillingemail').is(':visible')) {
        var email = $('#contact_email').val();
        if (!email || email == 'no@email.com.au') {
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
    if (!$(".signup_moveindetail").valid())
    {
        flag = false
    }
    if ($('.movein_fee_advised').is(':visible')) {
        if (!checkYesNoValidation('.movein_fee_advised')) {
            flag = false;
        }
    }
    if ($('.moveinundersandmainswitchoff').is(':visible')) {
        if (!checkYesNoValidation('.movein_understand_main_switch_off')) {
            flag = false;
        }
    }
    if ($('.moveinunderstandclearaccessvicsa').is(':visible')) {
        if (!checkYesNoValidation('.movein_understand_clear_access_vic_sa')) {
            flag = false;
        }
    }
    if ($('.moveinunderstandclearaccessactnsw').is(':visible')) {
        if (!checkYesNoValidation('.movein_understand_clear_access_act_nsw')) {
            flag = false;
        }
    }
    if ($('.moveinunderstandclearaccessactnsw2').length > 0 && $('.moveinunderstandclearaccessactnsw2').is(':visible')) {
        if (!checkYesNoValidation('.movein_understand_clear_access_act_nsw2')) {
            flag = false;
        }
    }
    if ($('.moveinundersandmainswitchoffactnsw').length > 0 && $('.moveinundersandmainswitchoffactnsw').is(':visible')) {
        if (!checkYesNoValidation('.movein_understand_main_switch_off_act_nsw')) {
            flag = false;
        }
    }
    if ($('.moveinundersandmainswitchoffactnsw2').length > 0 && $('.moveinundersandmainswitchoffactnsw2').is(':visible')) {
        if ($('.moveinundersandmainswitchoffactnsw2').is(':visible')) {
            if (!checkYesNoValidation('.movein_understand_main_switch_off_act_nsw2')) {
                flag = false;
            }
        }
    }
    if ($('.moveinundersandmainswitchoffactnsw3').length > 0 && $('.moveinundersandmainswitchoffactnsw3').is(':visible')) {
        if ($('.moveinundersandmainswitchoffactnsw3').is(':visible')) {
            if (!checkYesNoValidation('.movein_understand_main_switch_off_act_nsw3')) {
                flag = false;
            }
        }
    }
    if ($('.moveinworksplanned').length > 0 && $('.moveinworksplanned').is(':visible')) {
        if (!checkYesNoValidation('.movein_works_planned')) {
            flag = false;
        }
    }
    if ($('.movein_special_checked').is(':visible')) {
        if ($('#movein_special_details').val() == '') {
            $('#movein_special_details').addClass('error').removeClass('valid');
            flag = false;
        } else {
            $('#movein_special_details').addClass('valid').removeClass('error');
        }
    }
    if ($('.moveinclearaccess').is(':visible')) {
        if (!checkYesNoValidation('.movein_clear_access')) {
            flag = false;
        }
    }
    if ($('.moveinanyhazards').is(':visible')) {
        if (!checkYesNoValidation('.movein_meter_hazard')) {
            flag = false;
        }
    }
    if ($('.moveinelectricitymeter').is(':visible')) {
        if (!checkselectbox('.movein_elec_meter')) {
            flag = false;
        }
    }
    if ($('.moveingasmeter').is(':visible')) {
        if (!checkselectbox('.movein_gas_meter')) {
            flag = false;
        }
    }
    if ($('.moveinnmistatus').is(':visible')) {
        if (!checkselectbox('.movein_nmi_status')) {
            flag = false;
        }
    }
    if ($('.moveinvisualinspection').is(':visible')) {
        if (!checkselectbox('.movein_visual_inspection')) {
            flag = false;
        }
    }
    if ($('.moveinalterations').is(':visible')) {
        if (!checkYesNoValidation('.movein_alterations')) {
            flag = false;
        }
    }
    if ($('.moveinconfirmremainonsite').is(':visible')) {
        if (!checkYesNoValidation('.movein_confirm_remain_onsite')) {
            flag = false;
        }
    }
    if ($('.moveinelecconnectionfeetype').is(':visible')) {
        if ($('#elecconnectionfeetype').val() == '-1') {
            $('#elecconnectionfeetype').parent().find('label').attr({'style':"color:#F00 !important"});
            flag = false;
        } else {
            $('#elecconnectionfeetype').parent().find('label').attr({'style':"color:#777777 !important"});
        }
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
    if ($('.purchase_reason').is(':visible')) {
        if (!checkselectbox('#final_purchase_reason')) {
            flag = false;
        }
    }
    if ($('.final_ovo_energy_payments').length > 0) {
        if (!checkYesNoValidation('.final_ovo_energy_payments')) {
            flag = false;
        }
    }
    if ($('.final_healthdeal').length > 0) {
        if (!checkYesNoValidation('.final_healthdeal')) {
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
        $('#final_form_checked').val(1);
        dataOptions.count = 9
        if ($(".checkFinalFields").parents('.form.collapse').prev().hasClass('final')) {
            $(".checkFinalFields").parents('.form.collapse').slideUp();
            $(".checkFinalFields").parents('.form.collapse').prev()
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
            if (!checkBusinessDetailsFields(true)) {
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

trustee_company_type_lookup = function() {
    if ($('#trustee_company_type').val() == "") {
        return;
    }
    $('#trustee_company_type').autocomplete({
        source: function( request, response ) {
            var data = [{label: "Approved Deposit Fund"}, {label: "APRA Regulated Fund (Fund Type Unknown)"}, {label: "Commonwealth Government Public Company"}, {label: "Commonwealth Government Co-operative"}, {label: "Commonwealth Government Limited Partnership"}, {label: "Commonwealth Government Other Unincorporated Entity"}, {label: "Commonwealth Government Other Incorporated Entity"}, {label: "Commonwealth Government Pooled Development Fund"}, {label: "Commonwealth Government Private Company"}, {label: "Commonwealth Government Strata Title"}, {label: "Commonwealth Government Public Trading Trust"}, {label: "Commonwealth Government Corporate Unit Trust"}, {label: "Commonwealth Government Statutory Authority"}, {label: "Commonwealth Government Company"}, {label: "Commonwealth Government Entity"}, {label: "Commonwealth Government Partnership"}, {label: "Commonwealth Government Super Fund"}, {label: "Commonwealth Government Trust"}, {label: "Cash Management Trust"}, {label: "Co-operative"}, {label: "Commonwealth Government APRA Regulated Public Sector Fund"}, {label: "Commonwealth Government APRA Regulated Public Sector Scheme"}, {label: "Commonwealth Government Non-Regulated Super Fund"}, {label: "Commonwealth Government Cash Management Trust"}, {label: "Commonwealth Government Discretionary Services Management Trust"}, {label: "Commonwealth Government Fixed Trust"}, {label: "Commonwealth Government Hybrid Trust"}, {label: "Commonwealth Government Discretionary Investment Trust"}, {label: "Commonwealth Government Listed Public Unit Trust"}, {label: "Commonwealth Government Unlisted Public Unit Trust"}, {label: "Commonwealth Government Discretionary Trading Trust"}, {label: "Commonwealth Government Fixed Unit Trust"}, {label: "Corporate Unit Trust"}, {label: "Deceased Estate"}, {label: "Diplomatic/Consulate Body or High Commissioner"}, {label: "Discretionary Investment Trust"}, {label: "Discretionary Services Management Trust"}, {label: "Discretionary Trading Trust"}, {label: "First Home Saver Accounts Trust"}, {label: "Family Partnership"}, {label: "Fixed Unit Trust"}, {label: "Fixed Trust"}, {label: "Hybrid Trust"}, {label: "Individual/Sole Trader"}, {label: "Local Government Public Company"}, {label: "Local Government Co-operative"}, {label: "Local Government Limited Partnership"}, {label: "Local Government Other Unincorporated Entity"}, {label: "Local Government Other Incorporated Entity"}, {label: "Local Government Pooled Development Fund"}, {label: "Local Government Private Company"}, {label: "Local Government Strata Title"}, {label: "Local Government Public Trading Trust"}, {label: "Local Government Corporate Unit Trust"}, {label: "Local Government Statutory Authority"}, {label: "Local Government Company"}, {label: "Local Government Entity"}, {label: "Local Government Partnership"}, {label: "Local Government Trust"}, {label: "Limited Partnership"}, {label: "Local Government APRA Regulated Public Sector Fund"}, {label: "Local Government APRA Regulated Public Sector Scheme"}, {label: "Local Government Non-Regulated Super Fund"}, {label: "Local Government Cash Management Trust"}, {label: "Local Government Discretionary Services Management Trust"}, {label: "Local Government Fixed Trust"}, {label: "Local Government Hybrid Trust"}, {label: "Local Government Discretionary Investment Trust"}, {label: "Local Government Listed Public Unit Trust"}, {label: "Local Government Unlisted Public Unit Trust"}, {label: "Local Government Discretionary Trading Trust"}, {label: "Local Government Fixed Unit Trust"}, {label: "APRA Regulated Non-Public Offer Fund"}, {label: "Non-Regulated Superannuation Fund"}, {label: "Other Incorporated Entity"}, {label: "Pooled Development Fund"}, {label: "APRA Regulated Public Offer Fund"}, {label: "Unlisted Public Unit Trust"}, {label: "Australian Private Company"}, {label: "Pooled Superannuation Trust"}, {label: "Other Partnership"}, {label: "Public Trading trust"}, {label: "Australian Public Company"}, {label: "Listed Public Unit Trust"}, {label: "Small APRA Regulated Fund"}, {label: "State Government Public Company"}, {label: "State Government Co-operative"}, {label: "State Government Limited Partnership"}, {label: "State Government Other Unincorporated Entity"}, {label: "State Government Other Incorporated Entity"}, {label: "State Government Pooled Development Fund"}, {label: "State Government Private Company"}, {label: "State Government Strata Title"}, {label: "State Government Public Trading Trust"}, {label: "State Government Corporate Unit Trust"}, {label: "State Government Statutory Authority"}, {label: "State Government Company"}, {label: "State Government Entity"}, {label: "State Government Partnership"}, {label: "State Government Trust"}, {label: "ATO Regulated Self-Managed Superannuation Fund"}, {label: "State Government APRA Regulated Public Sector Fund"}, {label: "State Government APRA Regulated Public Sector Scheme"}, {label: "State Government Non-Regulated Super Fund"}, {label: "State Government Cash Management Trust"}, {label: "State Government Discretionary Services Management Trust"}, {label: "State Government Fixed Trust"}, {label: "State Government Hybrid Trust"}, {label: "State Government Discretionary Investment Trust"}, {label: "State Government Listed Public Unit Trust"}, {label: "State Government Unlisted Public Unit Trust"}, {label: "Strata-title"}, {label: "State Government Discretionary Trading Trust"}, {label: "State Government Fixed Unit Trust"}, {label: "Super Fund"}, {label: "Territory Government Public Company"}, {label: "Territory Government Co-operative"}, {label: "Territory Government Limited Partnership"}, {label: "Territory Government Other Unincorporated Entity"}, {label: "Territory Government Other Incorporated Entity"}, {label: "Territory Government Pooled Development Fund"}, {label: "Territory Government Private Company"}, {label: "Territory Government Strata Title"}, {label: "Territory Government Public Trading Trust"}, {label: "Territory Government Corporate Unit Trust"}, {label: "Territory Government Statutory Authority"}, {label: "Territory Government Entity"}, {label: "Territory Government Partnership"}, {label: "Territory Government Trust"}, {label: "Other trust"}, {label: "Territory Government APRA Regulated Public Sector Fund"}, {label: "Territory Government APRA Regulated Public Sector Scheme"}, {label: "Territory Government Non-Regulated Super Fund"}, {label: "Territory Government Cash Management Trust"}, {label: "Territory Government Discretionary Services Management Trust"}, {label: "Territory Government Fixed Trust"}, {label: "Territory Government Hybrid Trust"}, {label: "Territory Government Discretionary Investment Trust"}, {label: "Territory Government Listed Public Unit Trust"}, {label: "Territory Government Unlisted Public Unit Trust"}, {label: "Territory Government Discretionary Trading Trust"}, {label: "Territory Government Fixed Unit Trust"}, {label: "Other Unincorporated Entity"}, {label: "Other Unincorporated Entity"}, {label: "Other Unincorporated Entity"}];

            response( data );
        },
        delay:5,
        minLength: 1,
        select: function( event, ui ) {
            $('#trustee_company_type').val(ui.item.label);
        },
        change: function (event, ui) {
            if (ui.item == null || ui.item == undefined) {
                $('#trustee_company_type').val('');
            }
        }
    });
}
