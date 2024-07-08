<div class="container-fluid pdt100">

    <?php echo $this->element('signup_header'); ?>

    <section class="contact-deatils">

        <div class="contact-form">
            <div id="accordion">
                <h3 class="form-hdg">
                    <div class="fh-inr">Lead Details</div>
                </h3>
                <div class="form clearfix">
                    <form action="" method="post" class="signup_plan" name="signup_plan">
                        <input type="hidden" name="Plan[product_name]" id="plan_product_name" value="<?php echo $plan['Plan']['product_name']; ?>"/>
                        <div class="form-data">
                            <div class="form-group row">
                                <div class="col-xs-6 field-col">
                                    <label>Telco Lead ID</label>
                                    <input type="text" name="Plan[lead_id]" id="plan_lead_id" value="<?php echo $lead_id; ?>"/>
                                </div>
                                <div class="col-xs-6 field-col">
                                    <label>Referrer Lead ID</label>
                                    <input type="text" name="Plan[referrer_lead_id]" id="referrer_lead_id" value="<?php echo $referrer_lead_id; ?>" readonly="readonly"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-12 field-col">
                                    <label>Referrer Name</label>
                                    <input type="text" name="Plan[referrer_name]" id="referrer_name" value=""/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6 field-col">
                                    <label>Transfer/Move In</label>
                                    <select class="lookingfor selectpicker show-menu-arrow" name="Plan[looking_for]">
                                        <option value="">Select</option>
                                        <option value="Transfer" <?php if ($user['step1']['looking_for'] == 'Compare Plans'): ?>selected="selected"<?php endif; ?>>Transfer</option>
                                        <option value="Move In" <?php if ($user['step1']['looking_for'] == 'Move Properties'): ?>selected="selected"<?php endif; ?>>Move In</option>
                                    </select>
                                </div>
                                <div class="col-xs-6 field-col">
                                    <label>Business or Residential</label>
                                    <input type="hidden" name="Plan[is_soho]" id="is_soho" value="<?php echo ($plan['Plan']['res_sme'] == 'SOHO') ? 1 : 0; ?>"/>
                                    <select class="customertype selectpicker show-menu-arrow" name="Plan[res_sme]">
                                        <option value="">Select</option>
                                        <option value="Residential" <?php if ($plan['Plan']['res_sme'] == 'RES'): ?>selected="selected"<?php endif; ?>>Residential</option>
                                        <option value="Business" <?php if ($plan['Plan']['res_sme'] == 'SME' || $plan['Plan']['res_sme'] == 'SOHO'): ?>selected="selected"<?php endif; ?>>Business</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row display_hide">
                                <div class="col-xs-12 field-col">
                                    <input type="hidden" name="Plan[product_summary]" value="<?php if ($plan['Plan']['retailer'] != 'Origin Energy'):?>Their <strong><?php echo $plan['Plan']['product_name']; ?></strong> plan <?php if (in_array($plan['Plan']['retailer'], array('AGL', 'Powerdirect')) && $plan['Plan']['res_sme'] == 'RES'):?>includes<?php else:?>gives you<?php endif;?> <?php endif;?><?php echo nl2br($plan['Plan']['signup_summary']); ?>">
                                </div>
                            </div>
                            <div class="form-group row display_hide">
                                <div class="col-xs-12 field-col">
                                    <label>Fuel</label>
                                    <select class="planfuel selectpicker show-menu-arrow" name="Plan[fuel]">
                                        <option value="">Select</option>
                                        <option value="Elec" <?php if ($plan['Plan']['package'] == 'Elec'): ?>selected="selected"<?php endif; ?>>Electricity</option>
                                        <option value="Gas" <?php if ($plan['Plan']['package'] == 'Gas'): ?>selected="selected"<?php endif; ?>>Gas</option>
                                        <option value="Dual" <?php if ($plan['Plan']['package'] == 'Dual'): ?>selected="selected"<?php endif; ?>>Dual</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row display_hide">
                                <?php if (in_array($plan['Plan']['package'], array('Dual', 'Elec'))): ?>
                                    <div class="col-xs-6 field-col">
                                        <label>So your electricity is currently with:</label>
                                        <select class="selectpicker show-menu-arrow" name="Plan[elec_supplier]">
                                            <option value="">Select</option>
                                            <option value="ActewAGL" <?php if ($user['step1']['elec_supplier'] == 'ActewAGL' || $user['step1']['elec_supplier2'] == 'ActewAGL'): ?>selected="selected"<?php endif; ?>>ActewAGL</option>
                                            <option value="AGL" <?php if ($user['step1']['elec_supplier'] == 'AGL' || $user['step1']['elec_supplier2'] == 'AGL'): ?>selected="selected"<?php endif; ?>>AGL</option>
                                            <option value="Alinta Energy" <?php if ($user['step1']['elec_supplier'] == 'Alinta Energy' || $user['step1']['elec_supplier2'] == 'Alinta Energy'): ?>selected="selected"<?php endif; ?>>Alinta Energy</option>
                                            <option value="Australian Power & Gas" <?php if ($user['step1']['elec_supplier'] == 'Australian Power & Gas' || $user['step1']['elec_supplier2'] == 'Australian Power & Gas'): ?>selected="selected"<?php endif; ?>>Australian Power & Gas</option>
                                            <option value="Click Energy" <?php if ($user['step1']['elec_supplier'] == 'Click Energy' || $user['step1']['elec_supplier2'] == 'Click Energy'): ?>selected="selected"<?php endif; ?>>Click Energy</option>
                                            <option value="Dodo Power & Gas" <?php if ($user['step1']['elec_supplier'] == 'Dodo Power & Gas' || $user['step1']['elec_supplier2'] == 'Dodo Power & Gas'): ?>selected="selected"<?php endif; ?>>Dodo Power & Gas</option>
                                            <option value="Diamond Energy" <?php if ($user['step1']['elec_supplier'] == 'Diamond Energy' || $user['step1']['elec_supplier2'] == 'Diamond Energy'): ?>selected="selected"<?php endif; ?>>Diamond Energy</option>
                                            <option value="Energy Australia (TRUenergy)" <?php if ($user['step1']['elec_supplier'] == 'Energy Australia (TRUenergy)' || $user['step1']['elec_supplier2'] == 'Energy Australia (TRUenergy)'): ?>selected="selected"<?php endif; ?>>Energy Australia (TRUenergy)</option>
                                            <option value="Ergon Energy" <?php if ($user['step1']['elec_supplier'] == 'Ergon Energy' || $user['step1']['elec_supplier2'] == 'Ergon Energy'): ?>selected="selected"<?php endif; ?>>Ergon Energy</option>
                                            <option value="Lumo Energy" <?php if ($user['step1']['elec_supplier'] == 'Lumo Energy' || $user['step1']['elec_supplier2'] == 'Lumo Energy'): ?>selected="selected"<?php endif; ?>>Lumo Energy</option>
                                            <option value="Momentum" <?php if ($user['step1']['elec_supplier'] == 'Momentum' || $user['step1']['elec_supplier2'] == 'Momentum'): ?>selected="selected"<?php endif; ?>>Momentum</option>
                                            <option value="Neighbourhood Energy" <?php if ($user['step1']['elec_supplier'] == 'Neighbourhood Energy' || $user['step1']['elec_supplier2'] == 'Neighbourhood Energy'): ?>selected="selected"<?php endif; ?>>Neighbourhood Energy</option>
                                            <option value="Origin Energy" <?php if ($user['step1']['elec_supplier'] == 'Origin Energy' || $user['step1']['elec_supplier2'] == 'Origin Energy'): ?>selected="selected"<?php endif; ?>>Origin Energy</option>
                                            <option value="Powerdirect" <?php if ($user['step1']['elec_supplier'] == 'Powerdirect' || $user['step1']['elec_supplier2'] == 'Powerdirect'): ?>selected="selected"<?php endif; ?>>Powerdirect</option>
                                            <option value="Powershop" <?php if ($user['step1']['elec_supplier'] == 'Powershop' || $user['step1']['elec_supplier2'] == 'Powershop'): ?>selected="selected"<?php endif; ?>>Powershop</option>
                                            <option value="QEnergy" <?php if ($user['step1']['elec_supplier'] == 'QEnergy' || $user['step1']['elec_supplier2'] == 'QEnergy'): ?>selected="selected"<?php endif; ?>>QEnergy</option>
                                            <option value="Red Energy" <?php if ($user['step1']['elec_supplier'] == 'Red Energy' || $user['step1']['elec_supplier2'] == 'Red Energy'): ?>selected="selected"<?php endif; ?>>Red Energy</option>
                                            <option value="Sanctuary Energy" <?php if ($user['step1']['elec_supplier'] == 'Sanctuary Energy' || $user['step1']['elec_supplier2'] == 'Sanctuary Energy'): ?>selected="selected"<?php endif; ?>>Sanctuary Energy</option>
                                            <option value="Simply Energy" <?php if ($user['step1']['elec_supplier'] == 'Simply Energy' || $user['step1']['elec_supplier2'] == 'Simply Energy'): ?>selected="selected"<?php endif; ?>>Simply Energy</option>
                                            <option value="Energy Australia" <?php if ($user['step1']['elec_supplier'] == 'Energy Australia' || $user['step1']['elec_supplier2'] == 'Energy Australia'): ?>selected="selected"<?php endif; ?>>Energy Australia</option>
                                            <option value="Energy Australia" <?php if ($user['step1']['elec_supplier'] == 'Sumo Power' || $user['step1']['elec_supplier2'] == 'Sumo Power'): ?>selected="selected"<?php endif; ?>>Sumo Power</option>
                                            <option value="ERM" <?php if ($user['step1']['elec_supplier'] == 'ERM' || $user['step1']['elec_supplier2'] == 'ERM'): ?>selected="selected"<?php endif; ?>>ERM</option>
                                            <option value="Powerdirect and AGL" <?php if ($user['step1']['elec_supplier'] == 'Powerdirect and AGL'): ?>selected="selected"<?php endif; ?>>Powerdirect and AGL</option>
                                            <option value="Next Business Energy" <?php if ($user['step1']['elec_supplier'] == 'Next Business Energy'): ?>selected="selected"<?php endif; ?>>Next Business Energy</option>
                                            <option value="Unsure/Other" <?php if ($user['step1']['elec_supplier'] == 'Unsure/Other' || $user['step1']['elec_supplier2'] == 'Unsure/Other'): ?>selected="selected"<?php endif; ?>>Unsure/Other</option>
                                        </select>
                                    </div>
                                <?php endif; ?>
                                <?php if (in_array($plan['Plan']['package'], array('Dual', 'Gas'))): ?>
                                    <div class="col-xs-6 field-col">
                                        <label>and your gas is currently with:</label>
                                        <select class="selectpicker show-menu-arrow" name="Plan[gas_supplier]">
                                            <option value="">Select</option>
                                            <option value="ActewAGL" <?php if ($user['step1']['gas_supplier'] == 'ActewAGL' || $user['step1']['gas_supplier2'] == 'ActewAGL'): ?>selected="selected"<?php endif; ?>>ActewAGL</option>
                                            <option value="AGL" <?php if ($user['step1']['gas_supplier'] == 'AGL' || $user['step1']['gas_supplier2'] == 'AGL'): ?>selected="selected"<?php endif; ?>>AGL</option>
                                            <option value="Alinta Energy" <?php if ($user['step1']['gas_supplier'] == 'Alinta Energy' || $user['step1']['gas_supplier2'] == 'Alinta Energy'): ?>selected="selected"<?php endif; ?>>Alinta Energy</option>
                                            <option value="Australian Power & Gas" <?php if ($user['step1']['gas_supplier'] == 'Australian Power & Gas' || $user['step1']['gas_supplier2'] == 'Australian Power & Gas'): ?>selected="selected"<?php endif; ?>>Australian Power & Gas</option>
                                            <option value="Dodo Power & Gas" <?php if ($user['step1']['gas_supplier'] == 'Dodo Power & Gas' || $user['step1']['gas_supplier2'] == 'Dodo Power & Gas'): ?>selected="selected"<?php endif; ?>>Dodo Power & Gas</option>
                                            <option value="Energy Australia (TRUenergy)" <?php if ($user['step1']['gas_supplier'] == 'Energy Australia (TRUenergy)' || $user['step1']['gas_supplier2'] == 'Energy Australia (TRUenergy)'): ?>selected="selected"<?php endif; ?>>Energy Australia (TRUenergy)</option>
                                            <option value="Lumo Energy" <?php if ($user['step1']['gas_supplier'] == 'Lumo Energy' || $user['step1']['gas_supplier2'] == 'Lumo Energy'): ?>selected="selected"<?php endif; ?>>Lumo Energy</option>
                                            <option value="Origin Energy" <?php if ($user['step1']['gas_supplier'] == 'Origin Energy' || $user['step1']['gas_supplier2'] == 'Origin Energy'): ?>selected="selected"<?php endif; ?>>Origin Energy</option>
                                            <option value="Red Energy" <?php if ($user['step1']['gas_supplier'] == 'Red Energy' || $user['step1']['gas_supplier2'] == 'Red Energy'): ?>selected="selected"<?php endif; ?>>Red Energy</option>
                                            <option value="Simply Energy" <?php if ($user['step1']['gas_supplier'] == 'Simply Energy' || $user['step1']['gas_supplier2'] == 'Simply Energy'): ?>selected="selected"<?php endif; ?>>Simply Energy</option>
                                            <option value="Energy Australia" <?php if ($user['step1']['gas_supplier'] == 'Energy Australia' || $user['step1']['gas_supplier2'] == 'Energy Australia'): ?>selected="selected"<?php endif; ?>>Energy Australia</option>
                                            <option value="Powerdirect and AGL" <?php if ($user['step1']['gas_supplier'] == 'Powerdirect and AGL'): ?>selected="selected"<?php endif; ?>>Powerdirect and AGL</option>
                                            <option value="Unsure/Other" <?php if ($user['step1']['gas_supplier'] == 'Unsure/Other' || $user['step1']['gas_supplier2'] == 'Unsure/Other'): ?>selected="selected"<?php endif; ?>>Unsure/Other</option>
                                        </select>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="form-group row display_hide">
                                <div class="col-xs-12 field-col">
                                    <label>We are organising a change over to</label>
                                    <select class="planretailer selectpicker show-menu-arrow" name="Plan[supplier]">
                                        <option value="">Select</option>
                                        <option value="ActewAGL" <?php if ($plan['Plan']['retailer'] == 'ActewAGL'): ?>selected="selected"<?php endif; ?>>ActewAGL</option>
                                        <option value="AGL"<?php if ($plan['Plan']['retailer'] == 'AGL'): ?>selected="selected"<?php endif; ?>>AGL</option>
                                        <option value="Alinta Energy" <?php if ($plan['Plan']['retailer'] == 'Alinta Energy'): ?>selected="selected"<?php endif; ?>>Alinta Energy</option>
                                        <option value="Lumo Energy" <?php if ($plan['Plan']['retailer'] == 'Lumo Energy'): ?>selected="selected" <?php endif; ?>>Lumo Energy</option>
                                        <option value="Momentum" <?php if ($plan['Plan']['retailer'] == 'Momentum'): ?>selected="selected"<?php endif; ?>>Momentum</option>
                                        <option value="Origin Energy" <?php if ($plan['Plan']['retailer'] == 'Origin Energy'): ?>selected="selected"<?php endif; ?>>Origin Energy</option>
                                        <option value="Powerdirect" <?php if ($plan['Plan']['retailer'] == 'Powerdirect'): ?>selected="selected"<?php endif; ?>>Powerdirect</option>
                                        <option value="Powershop" <?php if ($plan['Plan']['retailer'] == 'Powershop'): ?>selected="selected"<?php endif; ?>>Powershop</option>
                                        <option value="Red Energy" <?php if ($plan['Plan']['retailer'] == 'Red Energy'): ?>selected="selected"<?php endif; ?>>Red Energy</option>
                                        <option value="Energy Australia" <?php if ($plan['Plan']['retailer'] == 'Energy Australia'): ?>selected="selected"<?php endif; ?>>Energy Australia</option>
                                        <option value="Sumo Power" <?php if ($plan['Plan']['retailer'] == 'Sumo Power'): ?>selected="selected"<?php endif; ?>>Sumo Power</option>
                                        <option value="ERM" <?php if ($plan['Plan']['retailer'] == 'ERM'): ?>selected="selected"<?php endif; ?>>ERM</option>
                                        <option value="Powerdirect and AGL" <?php if ($plan['Plan']['retailer'] == 'Powerdirect and AGL'): ?>selected="selected"<?php endif; ?>>Powerdirect and AGL</option>
                                        <option value="Next Business Energy" <?php if ($plan['Plan']['retailer'] == 'Next Business Energy'): ?>selected="selected"<?php endif; ?>>Next Business Energy</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row display_hide">
                                <div class="col-xs-6 field-col">
                                    <label>State*</label>
                                    <select class="planstate selectpicker show-menu-arrow" name="Plan[state]">
                                        <option value="QLD" <?php if ($plan['Plan']['state'] == 'Queensland'): ?>selected="selected"<?php endif; ?>>QLD</option>
                                        <option value="VIC" <?php if ($plan['Plan']['state'] == 'Victoria'): ?>selected="selected"<?php endif; ?>>VIC</option>
                                        <option value="NSW" <?php if ($plan['Plan']['state'] == 'New South Wales'): ?>selected="selected"<?php endif; ?>>NSW</option>
                                        <option value="SA" <?php if ($plan['Plan']['state'] == 'South Australia'): ?>selected="selected"<?php endif; ?>>SA</option>
                                        <option value="ACT" <?php if ($plan['Plan']['state'] == 'Australian Capital Territory'): ?>selected="selected"<?php endif; ?>>ACT</option>
                                    </select>
                                </div>
                            </div>
                            <div class="submit-btnotr"><input onclick="checkPlanFields();" type="submit" class="submit-btn" value="Mark section as Completed"/></div>
                        </div>
                    </form>
                </div>

                <h3 class="form-hdg">
                    <div class="fh-inr">Contact Details</div>
                </h3>
                <div class="form clearfix">
                    <form action="" method="post" class="signup_contact" name="signup_contact">
                        <div class="form-data contactdetialinputs">
                            <div class="form-group yes-no-btn row">
                                <div class="col-xs-12 field-col required-yes">
                                    <input type="hidden" name="Contact[authorised]" value="-1">
                                    <label>Do you understand you about to initiate a new Broadband service?</label>
                                    <button class="butn contact_authorised checkbutton">No</button>
                                    <button class="butn contact_authorised checkbutton">Yes</button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-2 field-col">
                                    <label>Title*</label>
                                    <select class="selectcontacttitle selectpicker show-menu-arrow contactdetialinputselect" name="Contact[title]" id="contact_title">
                                        <option value="">Select</option>
                                        <option value="Mr">Mr</option>
                                        <option value="Mrs">Mrs</option>
                                        <option value="Ms">Ms</option>
                                        <option value="Miss">Miss</option>
                                        <option value="Lady">Lady</option>
                                        <option value="Rev">Rev</option>
                                        <option value="Dr">Dr</option>
                                    </select>
                                </div>
                                <div class="col-xs-5 field-col">
                                    <label>First Name*</label>
                                    <input class="contactdetialinputstext" type="text" name="Contact[first_name]" id="contact_first_name" value=""/>
                                </div>
                                <div class="col-xs-5 field-col">
                                    <label>Surname*</label>
                                    <input class="contactdetialinputstext" type="text" name="Contact[last_name]" id="contact_last_name" value=""/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-12"><label>Date of Birth*</label>
                                    <input type="text" value="" name="Contact[dateofbirth]" class="datepickerDateOfBirth calender-icon contactdetialinputstext" id="datepickerDateOfBirth"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6 field-col">
                                    <label>Mobile Number*</label>
                                    <input class="contactdetialinputstext" type="text" value="" name="Contact[mobile]" id="contact_mobile"/>
                                </div>
                                <div class="col-xs-6 field-col">
                                    <label>Home Number</label>
                                    <input class="contactdetialinputstext" type="text" value="" name="Contact[home_phone]" id="contact_home_phone"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-8"><label>Please spell your email out*</label><input class="contactdetialinputstext" type="text" name="Contact[email]" id="contact_email" value=""/></div>
                                <?php if ($plan['Plan']['retailer'] != 'Powershop' && $plan['Plan']['product_name'] != 'Smile Power Plus'): ?>
                                    <div class="col-xs-4 field-col pdr0">
                                        <label>&nbsp;</label>
                                        <button class="noemail-btn">No Email</button>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="submit-btnotr"><input type="submit" onclick="checkContactFields();" class="submit-btn checkContactFields" value="Mark section as Completed"/></div>
                        </div>
                    </form>
                </div>

                <h3 class="form-hdg telco_details">
                    <div class="fh-inr">Telco</div>
                </h3>
                <div class="form clearfix">
                    <form action="" method="post" class="signup_telco_details" name="signup_telco_details">
                        <div class="form-data">
                            <div class="form-group row">
                                <div class="col-xs-6 field-col">
                                    <label>Telco plan type</label>
                                    <select id="telco_plan_type" class="selectTelcoPlanType selectpicker show-menu-arrow" name="Telco[plan_type]">
                                        <option value="NBN">NBN</option>
                                        <option value="Wireless Broadband">Wireless Broadband</option>
                                        <option value="ADSL">ADSL</option>
                                    </select>
                                </div>
                                <div class="col-xs-6 field-col">
                                    <label>NBN speed</label>
                                    <select id="telco_nbn_speed" class="selectTelcoNBNSpeed selectpicker show-menu-arrow" name="Telco[nbn_speed]">
                                        <option value="NBN 12Mbps">NBN 12Mbps</option>
                                        <option value="NBN 50Mbps">NBN 50Mbps</option>
                                        <option value="NBN 100Mbps">NBN 100Mbps</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6 field-col">
                                    <label>Monthly price</label>
                                    <select id="telco_price" class="selectTelcoPrice selectpicker show-menu-arrow" name="Telco[price]">
                                        <option value="NBN $70">NBN $70</option>
                                        <option value="NBN $80">NBN $80</option>
                                        <option value="NBN $90">NBN $90</option>
                                        <option value="NBN $100">NBN $100</option>
                                        <option value="ADSL $75">ADSL $75</option>
                                        <option value="HWB $80">HWB $80</option>
                                    </select>
                                </div>
                                <div class="col-xs-6 field-col">
                                    <label>Bundle discount</label>
                                    <select id="telco_bundle_discount" class="selectTelcoNBNSpeed selectpicker show-menu-arrow" name="Telco[bundle_discount]">
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6 field-col">
                                    <label>Payment type</label>
                                    <select id="telco_payment_type" class="selectTelcoPaymentType selectpicker show-menu-arrow" name="Telco[payment_type]">
                                        <option value="Credit Card">Credit Card</option>
                                        <option value="Bank Account">Bank Account</option>
                                    </select>
                                </div>
                                <div class="col-xs-6 field-col">
                                    <label>Confirmed payment type</label>
                                    <select id="telco_pay_and_submit" class="selectTelcoPaySubmit selectpicker show-menu-arrow" name="Telco[pay_and_submit]">
                                        <option value="Pay Now & Submit">Pay Now & Submit</option>
                                        <option value="Pre-Auth & Submit">Pre-Auth & Submit</option>
                                        <option value="Email Payment Link">Email Payment Link</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6 field-col">
                                    <label>Telco Retailer Reference Number</label>
                                    <input id="telco_pre_auth_submit" type="text" value='' name="Telco[pre_auth_submit]"/>
                                </div>
                                <div class="col-xs-6 field-col">
                                    <label>Referred Agent Name</label>
                                    <input id="telco_na" type="text" value='' name="Telco[na]"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6 field-col">
                                    <label>Telco Retailer</label>
                                    <input id="telco_retailer" type="text" value='SUMO TELCO' name="Telco[retailer]"/>
                                </div>
                            </div>
                            <div class="submit-btnotr"><input onclick="checkTelcoDetailsFields()" type="submit" class="submit-btn" value="Mark section as Completed"/>
                            </div>
                        </div>
                    </form>
                </div>

                <h3 class="form-hdg identification">
                    <div class="fh-inr">Identification</div>
                </h3>
                <div class="form clearfix">
                    <form action="" method="post" class="signup_identification" name="signup_identification">
                        <div class="form-data">
                            <div class="identification_credit_check_fields display_hide">
                                <div class="form-group yes-no-btn row">
                                    <div class="col-xs-12 required-yes">
                                        <label>
                                            <span class="creditcheckdefaultmsg">I will need to obtain some Identification from you, as <?php echo $plan['Plan']['retailer']; ?> will need to complete a credit check before they will take you on board as a customer. Do you give your consent for them to perform a credit check?</span>
                                            <span class="creditcheckmomentummsg">Momentum Energy require a form of ID to complete a credit check, Do you consent to this?</span>
                                            <span class="creditchecklumomsg">Do you consent to Lumo performing a credit check with their external credit service?</span>
                                        </label>
                                        <input type="hidden" value="-1" name="Identification[credit_check]" class="IdentificationCreditCheck">
                                        <button onclick="creditcheck(0)" type="button" class="butn credit_check checkbutton">No</button>
                                        <button onclick="creditcheck(1)" type="button" class="butn credit_check checkbutton">Yes</button>
                                    </div>
                                </div>
                                <div class="form-group row display_hide creditcheckno">
                                    <div class="read-msg bgred">
                                        <h5>READ VERBATIM TO CUSTOMER </h5>
                                        <p>I apologise, we will not be able to proceed with this change to <?php echo $plan['Plan']['retailer']; ?>.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="identification_default_fields display_hide">
                                <div class="form-group row">
                                    <div class="col-xs-4 field-col">
                                        <label>Document Type*</label>
                                        <select id="document_type" class="selectIdentificationDocumentType selectpicker show-menu-arrow" name="Identification[document_type]">
                                            <option value=''>Select</option>
                                            <option value='DRV'>Driver's License</option>
                                            <?php if ($plan['Plan']['retailer'] != 'Next Business Energy'):?>
                                                <option value='MED'>Medicare Card</option>
                                            <?php endif;?>
                                            <option value='PP'>Passport</option>
                                        </select>
                                    </div>
                                    <div class="col-xs-8 field-col">
                                        <label>Document ID Number*</label>
                                        <input id="document_id" type="text" value='' name="Identification[document_id]"/>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-4 field-col expiry-date">
                                        <label>Document Expiry*</label>
                                        <input type="text" name="Identification[document_expiry]" id="identification_document_expiry" class="calender-icon"/>
                                    </div>
                                    <div class="col-xs-4 field-col">
                                        <label>Document State</label>
                                        <select id="document_state" class="selectIdentificationDS selectpicker show-menu-arrow" name="Identification[document_state]">
                                            <option value=''>Select</option>
                                            <option <?php if ($plan['Plan']['state'] == 'Queensland'): ?>selected="selected"<?php endif; ?> value="QLD">QLD</option>
                                            <option <?php if ($plan['Plan']['state'] == 'Victoria'): ?>selected="selected"<?php endif; ?> value="VIC">VIC</option>
                                            <option <?php if ($plan['Plan']['state'] == 'New South Wales'): ?>selected="selected"<?php endif; ?> value="NSW">NSW</option>
                                            <option <?php if ($plan['Plan']['state'] == 'South Australia'): ?>selected="selected"<?php endif; ?> value="SA">SA</option>
                                            <option value="WA">WA</option>
                                            <option value="NT">NT</option>
                                            <option <?php if ($plan['Plan']['state'] == 'Australian Capital Territory'): ?>selected="selected"<?php endif; ?> value="ACT">ACT</option>
                                            <option value="NZ">NZ</option>
                                        </select>
                                    </div>
                                    <div class="col-xs-4 field-col">
                                        <label>Document Country</label>
                                        <select id="document_country" class="selectIdentificationDC selectpicker show-menu-arrow" name="Identification[document_country]">
                                            <option>Select</option>
                                            <option value='Australia' selected="selected">Australia</option>
                                            <option value='New Zealand'>New Zealand</option>
                                            <option value='Other Country'>Other Country</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="submit-btnotr"><input type="submit" onclick="checkIdentificationFields()" class="submit-btn checkIdentificationFields" value="Mark section as Completed"/></div>
                        </div>
                    </form>
                </div>

                <h3 class="form-hdg concession">
                    <div class="fh-inr">Concession</div>
                </h3>
                <div class="form clearfix">
                    <form action="" method="post" class="signup_concession" name="signup_concession">
                        <div class="form-data">
                            <div class="form-group yes-no-btn row validconcessionhouseholdrequire">
                                <div class="col-xs-12">
                                    <label>Does anyone in the household require the use of Life Support OR Powered Medical Equipment where their health may be impacted if there was a power outage?</label>
                                    <input type="hidden" value="-1" name="Concession[household_require]" class="ConcessionHouseholdRequire">
                                    <button type="button" class="butn concession_household_require checkbutton">No</button>
                                    <button type="button" class="butn concession_household_require checkbutton">Yes</button>
                                </div>
                            </div>
                            <div class="submit-btnotr"><input type="submit" onclick="checkConcessionFields()" class="submit-btn checkConcessionFields" value="Mark section as Completed"/></div>
                        </div>
                    </form>
                </div>

                <h3 class="form-hdg">
                    <div class="fh-inr">Supply Address</div>
                </h3>
                <div class="form clearfix">
                    <form action="" method="post" class="signup_supply" name="signup_supply">
                        <div class="form-data">
                            <p>*The Supply address is what the customer knows their address to be</p>
                            <div class="repeater">
                                <div class="repeater-field">
                                    <div class="form-group row">
                                        <div class="col-xs-12 checkbox-sec">
                                            <input id="supply_no_street_number_0" type="checkbox" name="Supply[no_street_number][0]">
                                            <label class="checkbox-clone" for="supply_no_street_number_0">&nbsp;</label>
                                            <span>Address Has no Street Number</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-6 field-col1">
                                            <label>Unit</label>
                                            <input type="text" name="Supply[unit][0]" value="" class="supplyunit"/>
                                        </div>
                                        <div class="col-xs-6 field-col1">
                                            <label>Unit Type</label>
                                            <select class="supplyunittype" name="Supply[unit_type][0]">
                                                <option value=''>Select</option>
                                                <option value="Apartment">Apartment</option>
                                                <option value="Flat">Flat</option>
                                                <option value="Factory">Factory</option>
                                                <option value="Suite">Suite</option>
                                                <option value="Shop">Shop</option>
                                                <option value="Site">Site</option>
                                                <option value="Unit">Unit</option>
                                                <option value="Villa">Villa</option>
                                                <option value="Antenna">Antenna</option>
                                                <option value="Automated Teller Machine">Automated Teller Machine</option>
                                                <option value="Barbeque">Barbeque</option>
                                                <option value="Block">Block</option>
                                                <option value="Boatshed">Boatshed</option>
                                                <option value="Building">Building</option>
                                                <option value="Bungalow">Bungalow</option>
                                                <option value="Cage">Cage</option>
                                                <option value="Carpark">Carpark</option>
                                                <option value="Carspace">Carspace</option>
                                                <option value="Club">Club</option>
                                                <option value="Coolroom">Coolroom</option>
                                                <option value="Cottage">Cottage</option>
                                                <option value="Duplex">Duplex</option>
                                                <option value="Garage">Garage</option>
                                                <option value="Hall">Hall</option>
                                                <option value="House">House</option>
                                                <option value="Kiosk">Kiosk</option>
                                                <option value="Lease">Lease</option>
                                                <option value="Lobby">Lobby</option>
                                                <option value="Loft">Loft</option>
                                                <option value="Lot">Lot</option>
                                                <option value="Maisonette">Maisonette</option>
                                                <option value="Marine Berth">Marine Berth</option>
                                                <option value="Office">Office</option>
                                                <option value="Penthouse">Penthouse</option>
                                                <option value="Rear">Rear</option>
                                                <option value="Reserve">Reserve</option>
                                                <option value="Room">Room</option>
                                                <option value="Section">Section</option>
                                                <option value="Shed">Shed</option>
                                                <option value="Showroom">Showroom</option>
                                                <option value="Sign">Sign</option>
                                                <option value="Stall">Stall</option>
                                                <option value="Store">Store</option>
                                                <option value="Strata Unit">Strata Unit</option>
                                                <option value="Studio">Studio</option>
                                                <option value="Substation">Substation</option>
                                                <option value="Tenancy">Tenancy</option>
                                                <option value="Tower">Tower</option>
                                                <option value="Townhouse">Townhouse</option>
                                                <option value="Vault">Vault</option>
                                                <option value="Ward">Ward</option>
                                                <option value="Warehouse">Warehouse</option>
                                                <option value="Workshop">Workshop</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-4 field-col1">
                                            <label>Lot</label>
                                            <input type="text" name="Supply[lot][0]" value=""/>
                                        </div>
                                        <div class="col-xs-4 field-col1">
                                            <label>Floor</label>
                                            <input type="text" name="Supply[floor][0]" value=""/>
                                        </div>
                                        <div class="col-xs-4 field-col1">
                                            <label>Floor Type</label>
                                            <select class="supplyfloortype" name="Supply[floor_type][0]">
                                                <option value=''>Select</option>
                                                <option value="BASEMENT">BASEMENT</option>
                                                <option value="FLOOR">FLOOR</option>
                                                <option value="GROUND">GROUND</option>
                                                <option value="LEVEL">LEVEL</option>
                                                <option value="LOBBY">LOBBY</option>
                                                <option value="LOWER GROUND FLOOR">LOWER GROUND FLOOR</option>
                                                <option value="MEZZANINE">MEZZANINE</option>
                                                <option value="OBSERVATION DECK">OBSERVATION DECK</option>
                                                <option value="PARKING">PARKING</option>
                                                <option value="ROOFTOP">ROOFTOP</option>
                                                <option value="SUB-BASEMENT">SUB-BASEMENT</option>
                                                <option value="UPPER GROUND FLOOR">UPPER GROUND FLOOR</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <label>Building Name</label>
                                            <input type="text" name="Supply[building_name][0]" value=""/>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-4 field-col">
                                            <label>Street Number</label>
                                            <input type="text" name="Supply[street_number][0]" value=""/>
                                        </div>
                                        <div class="col-xs-4 field-col">
                                            <label>Street Number Suffix</label>
                                            <input type="text" name="Supply[street_number_suffix][0]" value=""/>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-4 field-col">
                                            <label>Street Name</label>
                                            <input type="text" name="Supply[street_name][0]" value=""/>
                                        </div>
                                        <div class="col-xs-4 field-col">
                                            <label>Street Name Suffix</label>
                                            <input type="text" name="Supply[street_name_suffix][0]" value=""/>
                                        </div>
                                        <div class="col-xs-4 field-col strtType poRltv">
                                            <label>Street Type</label>
                                            <input type="text" value="" name="Supply[street_type][0]" class="street_type">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-4 field-col">
                                            <label>Suburb*</label>
                                            <input type="text" name="Supply[suburb][0]" value="<?php echo $user['suburb']; ?>"/>
                                        </div>
                                        <div class="col-xs-4 field-col">
                                            <label>Postcode*</label>
                                            <input type="text" name="Supply[postcode][0]" value="<?php echo $user['postcode']; ?>"/>
                                        </div>
                                        <div class="col-xs-4 field-col">
                                            <label>State*</label>
                                            <select class="selectSupplyState" name="Supply[state][0]">
                                                <option value=''>Select</option>
                                                <option
                                                    <?php if ($plan['Plan']['state'] == 'Queensland'): ?>selected="selected"<?php endif; ?>
                                                    value="QLD">QLD
                                                </option>
                                                <option
                                                    <?php if ($plan['Plan']['state'] == 'Victoria'): ?>selected="selected"<?php endif; ?>
                                                    value="VIC">VIC
                                                </option>
                                                <option
                                                    <?php if ($plan['Plan']['state'] == 'New South Wales'): ?>selected="selected"<?php endif; ?>
                                                    value="NSW">NSW
                                                </option>
                                                <option
                                                    <?php if ($plan['Plan']['state'] == 'South Australia'): ?>selected="selected"<?php endif; ?>
                                                    value="SA">SA
                                                </option>
                                                <option
                                                    <?php if ($plan['Plan']['state'] == 'Australian Capital Territory'): ?>selected="selected"<?php endif; ?>
                                                    value="ACT">ACT
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <script type="text/template" class="repeater-field-tpl">
                                    <div class="form-group row">
                                        <div class="col-xs-12 field-col">
                                            <h2>Supply Address</h2>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-12 checkbox-sec">
                                            <input id="supply_no_street_number_{{index}}" type="checkbox" name="Supply[no_street_number][{{index}}]">
                                            <label class="checkbox-clone" for="supply_no_street_number_{{index}}">&nbsp;</label>
                                            <span>Address Has no Street Number</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-6 field-col1">
                                            <label>Unit</label>
                                            <input type="text" name="Supply[unit][{{index}}]" value="" class="supplyunit"/>
                                        </div>
                                        <div class="col-xs-6 field-col1">
                                            <label>Unit Type</label>
                                            <select class="supplyunittype" name="Supply[unit_type][{{index}}]">
                                                <option value=''>Select</option>
                                                <option value="Apartment">Apartment</option>
                                                <option value="Flat">Flat</option>
                                                <option value="Factory">Factory</option>
                                                <option value="Suite">Suite</option>
                                                <option value="Shop">Shop</option>
                                                <option value="Site">Site</option>
                                                <option value="Unit">Unit</option>
                                                <option value="Villa">Villa</option>
                                                <option value="Antenna">Antenna</option>
                                                <option value="Automated Teller Machine">Automated Teller Machine</option>
                                                <option value="Barbeque">Barbeque</option>
                                                <option value="Block">Block</option>
                                                <option value="Boatshed">Boatshed</option>
                                                <option value="Building">Building</option>
                                                <option value="Bungalow">Bungalow</option>
                                                <option value="Cage">Cage</option>
                                                <option value="Carpark">Carpark</option>
                                                <option value="Carspace">Carspace</option>
                                                <option value="Club">Club</option>
                                                <option value="Coolroom">Coolroom</option>
                                                <option value="Cottage">Cottage</option>
                                                <option value="Duplex">Duplex</option>
                                                <option value="Garage">Garage</option>
                                                <option value="Hall">Hall</option>
                                                <option value="House">House</option>
                                                <option value="Kiosk">Kiosk</option>
                                                <option value="Lease">Lease</option>
                                                <option value="Lobby">Lobby</option>
                                                <option value="Loft">Loft</option>
                                                <option value="Lot">Lot</option>
                                                <option value="Maisonette">Maisonette</option>
                                                <option value="Marine Berth">Marine Berth</option>
                                                <option value="Office">Office</option>
                                                <option value="Penthouse">Penthouse</option>
                                                <option value="Rear">Rear</option>
                                                <option value="Reserve">Reserve</option>
                                                <option value="Room">Room</option>
                                                <option value="Section">Section</option>
                                                <option value="Shed">Shed</option>
                                                <option value="Showroom">Showroom</option>
                                                <option value="Sign">Sign</option>
                                                <option value="Stall">Stall</option>
                                                <option value="Store">Store</option>
                                                <option value="Strata Unit">Strata Unit</option>
                                                <option value="Studio">Studio</option>
                                                <option value="Substation">Substation</option>
                                                <option value="Tenancy">Tenancy</option>
                                                <option value="Tower">Tower</option>
                                                <option value="Townhouse">Townhouse</option>
                                                <option value="Vault">Vault</option>
                                                <option value="Ward">Ward</option>
                                                <option value="Warehouse">Warehouse</option>
                                                <option value="Workshop">Workshop</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-4 field-col1">
                                            <label>Lot</label>
                                            <input type="text" name="Supply[lot][{{index}}]" value=""/>
                                        </div>
                                        <div class="col-xs-4 field-col1">
                                            <label>Floor</label>
                                            <input type="text" name="Supply[floor][{{index}}]" value=""/>
                                        </div>
                                        <div class="col-xs-4 field-col1">
                                            <label>Floor Type</label>
                                            <select class="supplyfloortype" name="Supply[floor_type][{{index}}]">
                                                <option value=''>Select</option>
                                                <option value="BASEMENT">BASEMENT</option>
                                                <option value="FLOOR">FLOOR</option>
                                                <option value="GROUND">GROUND</option>
                                                <option value="LEVEL">LEVEL</option>
                                                <option value="LOBBY">LOBBY</option>
                                                <option value="LOWER GROUND FLOOR">LOWER GROUND FLOOR</option>
                                                <option value="MEZZANINE">MEZZANINE</option>
                                                <option value="OBSERVATION DECK">OBSERVATION DECK</option>
                                                <option value="PARKING">PARKING</option>
                                                <option value="ROOFTOP">ROOFTOP</option>
                                                <option value="SUB-BASEMENT">SUB-BASEMENT</option>
                                                <option value="UPPER GROUND FLOOR">UPPER GROUND FLOOR</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <label>Building Name</label>
                                            <input type="text" name="Supply[building_name][{{index}}]" value=""/>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-4 field-col">
                                            <label>Street Number</label>
                                            <input type="text" name="Supply[street_number][{{index}}]" value=""/>
                                        </div>
                                        <div class="col-xs-4 field-col">
                                            <label>Street Number Suffix</label>
                                            <input type="text" name="Supply[street_number_suffix][{{index}}]" value=""/>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-4 field-col">
                                            <label>Street Name</label>
                                            <input type="text" name="Supply[street_name][{{index}}]" value=""/>
                                        </div>
                                        <div class="col-xs-4 field-col">
                                            <label>Street Name Suffix</label>
                                            <input type="text" name="Supply[street_name_suffix][{{index}}]" value=""/>
                                        </div>
                                        <div class="col-xs-4 field-col strtType poRltv">
                                            <label>Street Type</label>
                                            <input type="text" value="" name="Supply[street_type][{{index}}]" class="street_type">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-4 field-col">
                                            <label>Suburb*</label>
                                            <input type="text" name="Supply[suburb][{{index}}]" value=""/>
                                        </div>
                                        <div class="col-xs-4 field-col">
                                            <label>Postcode*</label>
                                            <input type="text" name="Supply[postcode][{{index}}]" value=""/>
                                        </div>
                                        <div class="col-xs-4 field-col">
                                            <label>State*</label>
                                            <select class="selectSupplyState" name="Supply[state][{{index}}]">
                                                <option value=''>Select</option>
                                                <option value="QLD">QLD</option>
                                                <option value="VIC">VIC</option>
                                                <option value="NSW">NSW</option>
                                                <option value="SA">SA</option>
                                                <option value="ACT">ACT</option>
                                            </select>
                                        </div>
                                    </div>
                                </script>
                            </div>
                            <div class="form-group yes-no-btn row">
                                <div class="col-xs-12">
                                    <label>Is your billing address different?</label>
                                    <input type="hidden" value="-1" name="Supply[billing_address_is_different]" class="BillingAddressDifferent">
                                    <button onclick="checkSupplySecondary(0)" type="button" class="butn billing_address_different checkbutton">No</button>
                                    <button onclick="checkSupplySecondary(1)" type="button" class="butn billing_address_different checkbutton">Yes</button>
                                </div>
                            </div>
                            <div class="secondary-supply display_hide supply_secondary_contact">
                                <h3 class="form-hdg">
                                    <div class="fh-inr">Billing Address</div>
                                </h3>
                                <div class="form-data">
                                    <div class="form-group row">
                                        <div class="col-xs-12"><label>PO Box</label><input type="text" value="" name="SupplySecondary[po_box]"/></div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-12 checkbox-sec">
                                            <input id="supply_secondary_no_street_number" type="checkbox" name="SupplySecondary[no_street_number]">
                                            <label class="checkbox-clone" for="supply_secondary_no_street_number">&nbsp;</label>
                                            <span>Address Has no Street Number</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-6  field-col1">
                                            <label>Unit</label>
                                            <input type="text" value="" name="SupplySecondary[unit]" class="supplysecondaryunit"/>
                                        </div>
                                        <div class="col-xs-6 field-col1">
                                            <label>Unit Type</label>
                                            <select class="supplysecondaryunittype" name="SupplySecondary[unit_type]">
                                                <option value=''>Select</option>
                                                <option value="Apartment">Apartment</option>
                                                <option value="Flat">Flat</option>
                                                <option value="Factory">Factory</option>
                                                <option value="Suite">Suite</option>
                                                <option value="Shop">Shop</option>
                                                <option value="Site">Site</option>
                                                <option value="Unit">Unit</option>
                                                <option value="Villa">Villa</option>
                                                <option value="Antenna">Antenna</option>
                                                <option value="Automated Teller Machine">Automated Teller Machine</option>
                                                <option value="Barbeque">Barbeque</option>
                                                <option value="Block">Block</option>
                                                <option value="Boatshed">Boatshed</option>
                                                <option value="Building">Building</option>
                                                <option value="Bungalow">Bungalow</option>
                                                <option value="Cage">Cage</option>
                                                <option value="Carpark">Carpark</option>
                                                <option value="Carspace">Carspace</option>
                                                <option value="Club">Club</option>
                                                <option value="Coolroom">Coolroom</option>
                                                <option value="Cottage">Cottage</option>
                                                <option value="Duplex">Duplex</option>
                                                <option value="Garage">Garage</option>
                                                <option value="Hall">Hall</option>
                                                <option value="House">House</option>
                                                <option value="Kiosk">Kiosk</option>
                                                <option value="Lease">Lease</option>
                                                <option value="Lobby">Lobby</option>
                                                <option value="Loft">Loft</option>
                                                <option value="Lot">Lot</option>
                                                <option value="Maisonette">Maisonette</option>
                                                <option value="Marine Berth">Marine Berth</option>
                                                <option value="Office">Office</option>
                                                <option value="Penthouse">Penthouse</option>
                                                <option value="Rear">Rear</option>
                                                <option value="Reserve">Reserve</option>
                                                <option value="Room">Room</option>
                                                <option value="Section">Section</option>
                                                <option value="Shed">Shed</option>
                                                <option value="Showroom">Showroom</option>
                                                <option value="Sign">Sign</option>
                                                <option value="Stall">Stall</option>
                                                <option value="Store">Store</option>
                                                <option value="Strata Unit">Strata Unit</option>
                                                <option value="Studio">Studio</option>
                                                <option value="Substation">Substation</option>
                                                <option value="Tenancy">Tenancy</option>
                                                <option value="Tower">Tower</option>
                                                <option value="Townhouse">Townhouse</option>
                                                <option value="Vault">Vault</option>
                                                <option value="Ward">Ward</option>
                                                <option value="Warehouse">Warehouse</option>
                                                <option value="Workshop">Workshop</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-4  field-col1">
                                            <label>Lot</label>
                                            <input type="text" value="" name="SupplySecondary[lot]"/>
                                        </div>
                                        <div class="col-xs-4  field-col1">
                                            <label>Floor</label>
                                            <input type="text" value="" name="SupplySecondary[floor]"/>
                                        </div>
                                        <div class="col-xs-4  field-col1">
                                            <label>Floor Type</label>
                                            <select class="supplysecondaryfloortype" name="SupplySecondary[floor_type]">
                                                <option value=''>Select</option>
                                                <option value="BASEMENT">BASEMENT</option>
                                                <option value="FLOOR">FLOOR</option>
                                                <option value="GROUND">GROUND</option>
                                                <option value="LEVEL">LEVEL</option>
                                                <option value="LOBBY">LOBBY</option>
                                                <option value="LOWER GROUND FLOOR">LOWER GROUND FLOOR</option>
                                                <option value="MEZZANINE">MEZZANINE</option>
                                                <option value="OBSERVATION DECK">OBSERVATION DECK</option>
                                                <option value="PARKING">PARKING</option>
                                                <option value="ROOFTOP">ROOFTOP</option>
                                                <option value="SUB-BASEMENT">SUB-BASEMENT</option>
                                                <option value="UPPER GROUND FLOOR">UPPER GROUND FLOOR</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <label>Building Name</label>
                                            <input type="text" value="" name="SupplySecondary[building_name]"/>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-6 field-col">
                                            <label>Street Number</label>
                                            <input type="text" value="" name="SupplySecondary[street_number]"/>
                                        </div>
                                        <div class="col-xs-6 field-col">
                                            <label>Street Number Suffix</label>
                                            <input type="text" value="" name="SupplySecondary[street_number_suffix]"/>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-4 field-col">
                                            <label>Street Name</label>
                                            <input type="text" value="" name="SupplySecondary[street_name]"/>
                                        </div>
                                        <div class="col-xs-4 field-col">
                                            <label>Street Name Suffix</label>
                                            <input type="text" value="" name="SupplySecondary[street_name_suffix]"/>
                                        </div>
                                        <div class="col-xs-4 field-col">
                                            <label>Street Type</label>
                                            <input type="text" value="" name="SupplySecondary[street_type]" class="street_type">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-4 field-col">
                                            <label>Suburb*</label>
                                            <input class="secondary_input" type="text" value="<?php echo $user['suburb']; ?>" name="SupplySecondary[suburb]"/>
                                        </div>
                                        <div class="col-xs-4 field-col">
                                            <label>Postcode*</label>
                                            <input class="secondary_input" type="text" value="<?php echo $user['postcode']; ?>" name="SupplySecondary[postcode]"/>
                                        </div>
                                        <div class="col-xs-4 field-col">
                                            <label>State*</label>
                                            <select class="selectSupplySecondaryState" name="SupplySecondary[state]">
                                                <option value="">Select</option>
                                                <option
                                                    <?php if ($plan['Plan']['state'] == 'Queensland'): ?>selected="selected"<?php endif; ?>
                                                    value="QLD">QLD
                                                </option>
                                                <option
                                                    <?php if ($plan['Plan']['state'] == 'Victoria'): ?>selected="selected"<?php endif; ?>
                                                    value="VIC">VIC
                                                </option>
                                                <option
                                                    <?php if ($plan['Plan']['state'] == 'New South Wales'): ?>selected="selected"<?php endif; ?>
                                                    value="NSW">NSW
                                                </option>
                                                <option
                                                    <?php if ($plan['Plan']['state'] == 'South Australia'): ?>selected="selected"<?php endif; ?>
                                                    value="SA">SA
                                                </option>
                                                <option
                                                    <?php if ($plan['Plan']['state'] == 'Australian Capital Territory'): ?>selected="selected"<?php endif; ?>
                                                    value="ACT">ACT
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="submit-btnotr"><input onclick="checkSupplyFields()" type="submit" class="submit-btn checkSupplyFields" value="Mark section as Completed"/></div>
                        </div>
                    </form>
                </div>

                <h3 class="form-hdg">
                    <div class="fh-inr">Billing Information</div>
                </h3>
                <div class="form clearfix">
                    <form action="" method="post" class="signup_billinginfo" name="signup_billinginfo">
                        <div class="form-data">
                            <div class="billing_default_fields">
                                <div class="form-group yes-no-btn row" style="padding-top:20px">
                                    <div class="col-xs-12 field-col">
                                        <label>Residential Duration</label>
                                        <select class="residentialduration selectpicker show-menu-arrow" name="Billing[residential_duration]">
                                            <option value="">Select</option>
                                            <option value="Less than 6 months">Less than 6 months</option>
                                            <option value="6-12 months">6-12 months</option>
                                            <option value="1-2 years">1-2 years</option>
                                            <option value="2-5 years">2-5 years</option>
                                            <option value="5+ years">5+ years</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="submit-btnotr"><input onclick="checkBillingInformation()" type="submit" class="submit-btn" value="Mark section as Completed"/>
                            </div>
                        </div>
                    </form>
                </div>

                <h3 class="form-hdg MoveInDetails ">
                    <div class="fh-inr">Move In Details</div>
                </h3>
                <div class="form clearfix">
                    <form action="" method="post" class="signup_moveindetail" name="signup_moveindetail">
                        <div class="form-data">
                            <div class="form-group row">
                                <div class="col-xs-6 field-col">
                                    <label>Connection Date</label>
                                    <input type="text" class="calender-icon" id="connection_date" name="MoveInDetail[connection_date]"  value=""/>
                                </div>
                            </div>
                            <div class="submit-btnotr">
                                <input type="submit" onclick="checkMoveInDetails()" class="submit-btn" value="Mark section as Completed"/>
                            </div>
                        </div>
                    </form>
                </div>

                <h3 class="form-hdg final">
                    <div class="fh-inr">Final Info</div>
                </h3>
                <div class="form clearfix">
                    <form action="" method="post" class="signup_final" name="signup_final">
                        <input type="hidden" name="Final[complete_date]" value="<?php echo date('Y-m-d'); ?>">
                        <input type="hidden" id="final_campaign_source" name="Final[campaign_source]" value="0">
                        <input type="hidden" id="final_content" name="Final[content]" value="0">
                        <input type="hidden" id="final_lead_age" name="Final[lead_age]" value="0">
                        <input type="hidden" id="final_medium" name="Final[medium]" value="(Not Set)">
                        <input type="hidden" id="final_howtheyfoundus" name="Final[howtheyfoundus]" value="(Not Set)">
                        <input type="hidden" id="final_keyword" name="Final[keyword]" value="0">
                        <input type="hidden" id="final_url" name="Final[url]" value="0">
                        <input type="hidden" id="final_lead_campaign" name="Final[lead_campaign]" value="0">
                        <input type="hidden" id="final_campaign_ad_group" name="Final[campaign_ad_group]" value="0">
                        <input type="hidden" id="final_id" name="Final[id]" value="">
                        <input type="hidden" id="final_first_campaign" name="Final[first_campaign]" value="">

                        <div class="form-data">
                            <div class="form-group row">
                                <div class="col-xs-12 field-col">
                                    <label>Special Campaign</label>
                                    <select class="selectpicker show-menu-arrow" name="Final[campaign_id]" id="final_campaign_id">
                                        <option value="">Please select</option>
                                        <option value="76">13Energy Campaign</option>
                                        <option value="77">MIC</option>
                                        <option value="1">EW Phone</option>
                                    </select>
                                    <input type="hidden" id="final_campaign_name" name="Final[campaign_name]" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6 field-col">
                                    <label>Sales Rep Name*</label>
                                    <input type="text" value="" name="Final[sales_rep_name]" id="sales_rep_name">
                                    <input type="hidden" name="Final[sales_rep_email]" id="sales_rep_email" value="">
                                    <input type="hidden" name="Final[agent_id]" id="agent_id" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-12 field-col">
                                    <label>Open EIC*</label>
                                    <?php if (!empty($pdfs)): ?>
                                        <select class="eicpdf selectpicker show-menu-arrow" name="Final[eic_pdf]">
                                            <option value="">Select</option>
                                            <?php foreach ($pdfs as $pdf): ?>
                                                <option value="<?php echo $pdf['Pdf']['filename']; ?>"><?php echo $pdf['Pdf']['filename']; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    <?php else: ?>
                                        PDF not found - please check network drive
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6 field-col">
                                    <label>Voice Verification Number</label>
                                    <input type="text" name="Final[voice_verification_number]" id="final_voice_verification_number" value=""/>
                                </div>
                            </div>
                            <div class="submit-btnotr">
                                <input onclick="checkFinalFields()" type="submit" class="submit-btn checkFinalFields" value="Mark section as Completed"/>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="mt50 clearfix">
                <form action="" method="post" id="signup_complete" name="signup_complete">
                    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
                    <input type="hidden" id="lead_imported" value="0">
                    <input type="hidden" id="plan_form_checked" value="0">
                    <input type="hidden" id="contact_form_checked" value="0">
                    <input type="hidden" id="telco_details_form_checked" value="0">
                    <input type="hidden" id="identification_form_checked" value="0">
                    <input type="hidden" id="concession_form_checked" value="0">
                    <input type="hidden" id="supply_form_checked" value="0">
                    <input type="hidden" id="billinginfo_form_checked" value="0">
                    <input type="hidden" id="moveindetail_form_checked" value="0">
                    <input type="hidden" id="final_form_checked" value="0">
                    <input type="submit" id="signup_complete_submit" class="submit-form" value="Submit Form"/>
                </form>
            </div>
        </div>
    </section>
</div>
<div class="modal fade process-modal" id="process_modal" data-backdrop="static" data-keyboard="false" tabindex="-1"
     role="dialog" aria-labelledby="process_modalLabel" aria-hidden="true">
    <div class="modal-dialog" style="margin-top: 10%">
        <div class="modal-content">
            <div class="modal-body thanks-popup text-center">
                <h1>Processing...</h1>
            </div>
        </div>
    </div>
</div>
<div class="modal fade complete-modal" id="complete_modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="complete_modalLabel" aria-hidden="true">
    <div class="modal-dialog" style="margin-top: 10%">
        <div class="modal-content">
            <div class="modal-body thanks-popup text-center">
                <h1>This sale has been posted to Velocify! Well done!</h1>

                <h2>Lead ID: <span id="complete_dialog_lead_id">123</span></h2>
                
                <p>
                    <input value='I am done' class="btn btn-warning no-thanks pull-right mt20" type="button">
                </p>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(window).bind('beforeunload', function () {
        return "You haven't finished the form yet. Do you want to leave without finishing?";
    });
</script>