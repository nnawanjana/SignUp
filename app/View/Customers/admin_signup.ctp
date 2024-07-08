<?php
$msg = 'Today, we are organising a ';
if ($plan['Plan']['res_sme'] == 'RES') {
    $msg .= '<strong>Residential</strong> ';
} elseif ($plan['Plan']['res_sme'] == 'SOHO') {
    $msg .= '<strong>SOHO</strong> ';
} else {
    $msg .= '<strong>Business</strong> ';
}
if ($user['step1']['looking_for'] == 'Compare Plans') {
    $msg .= '<strong>Transfer</strong> ';
    $msg .= 'from ';
    if ($plan['Plan']['package'] == 'Elec') {
        if ($user['step1']['elec_supplier']) {
            $msg .= '<strong>' . $user['step1']['elec_supplier'] . '</strong> ';
        } elseif ($user['step1']['elec_supplier2']) {
            $msg .= '<strong>' . $user['step1']['elec_supplier2'] . '</strong> ';
        }
    } elseif ($plan['Plan']['package'] == 'Gas') {
        if ($user['step1']['gas_supplier']) {
            $msg .= '<strong>' . $user['step1']['gas_supplier'] . '</strong> ';
        } elseif ($user['step1']['gas_supplier2']) {
            $msg .= '<strong>' . $user['step1']['gas_supplier2'] . '</strong> ';
        }
    } elseif ($plan['Plan']['package'] == 'Dual') {
        if ($user['step1']['elec_supplier']) {
            $msg .= '<strong>' . $user['step1']['elec_supplier'] . '</strong> for electricity ';
        } elseif ($user['step1']['elec_supplier2']) {
            $msg .= '<strong>' . $user['step1']['elec_supplier2'] . '</strong> for electricity ';
        }
        if ($user['step1']['gas_supplier']) {
            $msg .= 'and <strong>' . $user['step1']['gas_supplier'] . '</strong> for gas ';
        } elseif ($user['step1']['gas_supplier2']) {
            $msg .= 'and <strong>' . $user['step1']['gas_supplier2'] . '</strong> for gas ';
        }
    }
} else {
    $msg .= '<strong>Move In</strong> ';
}
$msg .= 'to <strong>' . $plan['Plan']['retailer'] . '</strong>';
if ($plan['Plan']['package'] == 'Dual') {
    $msg .= ' for both energy accounts';
}
$msg .= '.';

$tariffs = array();
if ($user['step1']['tariff1']) {
    $tariff1 = explode('|', $user['step1']['tariff1']);
    if ($tariff1[3] == 'Solar') {
        if (strpos($tariff1[4], '/') !== false) {
            $tariff1[0] .= ' (' . $user['step1']['solar_rebate_scheme'] . ')';
        } else {
            $tariff1[0] .= ' (' . $tariff1[4] . ')';
        }
    }
    $tariffs[] = $tariff1[0];
}
if ($user['step1']['tariff2']) {
    $tariff2 = explode('|', $user['step1']['tariff2']);
    if ($tariff2[3] == 'Solar') {
        if (strpos($tariff2[4], '/') !== false) {
            $tariff2[0] .= ' (' . $user['step1']['solar_rebate_scheme'] . ')';
        } else {
            $tariff2[0] .= ' (' . $tariff2[4] . ')';
        }
    }
    $tariffs[] = $tariff2[0];
}
if ($user['step1']['tariff3']) {
    $tariff3 = explode('|', $user['step1']['tariff3']);
    if ($tariff3[3] == 'Solar') {
        if (strpos($tariff3[4], '/') !== false) {
            $tariff3[0] .= ' (' . $user['step1']['solar_rebate_scheme'] . ')';
        } else {
            $tariff3[0] .= ' (' . $tariff3[4] . ')';
        }
    }
    $tariffs[] = $tariff3[0];
}
if ($user['step1']['tariff4']) {
    $tariff4 = explode('|', $user['step1']['tariff4']);
    if ($tariff4[3] == 'Solar') {
        if (strpos($tariff4[4], '/') !== false) {
            $tariff4[0] .= ' (' . $user['step1']['solar_rebate_scheme'] . ')';
        } else {
            $tariff4[0] .= ' (' . $tariff4[4] . ')';
        }
    }
    $tariffs[] = $tariff4[0];
}
$sub_domain = array_shift((explode('.', $_SERVER['HTTP_HOST'])));
if ($sub_domain == 'signup') {
    $tools_version = 'v4';
} else {
    $tools_version = 'v5';
}
if ($plan['Plan']['package'] == 'Elec') {
    $package = 'Electricity';
} elseif ($plan['Plan']['package'] == 'Gas') {
    $package = 'Gas';
} elseif ($plan['Plan']['package'] == 'Dual') {
    $package = 'Electricity & Gas';
}
?>
<div class="container-fluid">

    <?php echo $this->element('signup_header'); ?>

    <section class="contact-deatils">
        <div class="contact-form"><div class="container"><div class="row"><div class="col-sm-12">
			<div id="accordion">
            
                <h3 class="form-hdg">Plan Type<i class="fa fa-angle-up" aria-hidden="true"></i><i class="fa fa-angle-down" aria-hidden="true"></i></h3>
            	<div class="form clearfix collapse in" id="form-collapse1">
                    <form action="" method="post" class="signup_plan" name="signup_plan">
                        <input type="hidden" name="Plan[product_name]" id="plan_product_name" value="<?php echo $plan['Plan']['product_name']; ?>"/>
                        <input type="hidden" name="Plan[ovo_direct_debit]" id="plan_ovo_direct_debit" value="<?php echo isset($plan['Plan']['ovo_direct_debit']) && $plan['Plan']['ovo_direct_debit'] ? $plan['Plan']['ovo_direct_debit'] : ''; ?>"/>

                        <div class="form-data">
                            <p><a href="http://check.compareconnectsave.com.au/<?php echo $tools_version;?>/compare/3" class="btn-back">< Back to results page</a></p>
                            <div class="form-group">
                            	<label class="v2_label">Lead ID</label>
                                <div class="v2_field"><input type="text" name="Plan[lead_id]" id="plan_lead_id" class="form-control" value="<?php echo $lead_id;?>"/></div>
                            </div>
                            <?php if ($elec_product): ?>
                            <div class="form-group">
                            	<label class="v2_label">Electricity Product</label>
								<div class="v2_field">
                                <select class="planelecproduct form-control" name="Plan[ElectricityProduct]">
                                	<option value="">Select</option>
                                    <?php foreach ($elec_product as $product): ?>
                                    <option value="<?php echo $product['Product']['field_value']; ?>"><?php echo $product['Product']['field_value']; ?></option>
                                    <?php endforeach; ?>
                                </select>
								</div>
							</div>	
                            <?php endif; ?>
                            <?php if ($gas_product): ?>
                            <div class="form-group">	
                                 <label class="v2_label">Gas Product</label>
                                 <div class="v2_field">
									 <select class="plangasproduct form-control" name="Plan[GasProduct]">
                                            <option value="">Select</option>
                                            <?php foreach ($gas_product as $product): ?>
                                                <option value="<?php echo $product['Product']['field_value']; ?>"><?php echo $product['Product']['field_value']; ?></option>
                                            <?php endforeach; ?>
                                 </select>
								</div>	 
							</div>
                            <?php endif; ?>
                            <div class="form-group">
                                    <div class="read-msg">
                                        <h5 style="color:#F00">Please read verbatim to customer:</h5>
                                        <p>This is (your name) from (Company Brand Name), on behalf of <?php echo $plan['Plan']['retailer'];?>. Can you please confirm that you are happy for me to record this conversation with a clear yes or no?</p>

                                        <p><?php echo $msg; ?></p>

                                        <?php if ($user['step1']['looking_for'] == 'Move Properties' && in_array($plan['Plan']['res_sme'], array('RES', 'SOHO'))): ?>
                                        <div class="bgylw"><div class="inner">
                                            <p>So this means that the bills will be in your name moving forward.</p>
                                        </div></div>
                                        <?php endif;?>
                                        <?php if ($user['step1']['looking_for'] == 'Move Properties' && in_array($plan['Plan']['res_sme'], array('SME'))): ?>
                                        <div class="bgylw"><div class="inner">
                                            <p>So this means that the bills will be in the business trading name moving forward.</p>
										</div></div>
                                        <?php endif;?>

                                        <?php if (in_array($plan['Plan']['retailer'], array('AGL', 'Powerdirect', 'Powerdirect and AGL', 'OVO Energy', 'Blue NRG'))):?>
                                            <p><?php echo nl2br($plan['Plan']['signup_summary']); ?></p>
                                            <input type="hidden" name="Plan[product_summary]" value="<?php echo nl2br($plan['Plan']['signup_summary']); ?>">
                                        <?php else:?>
                                        <p><?php if (!in_array($plan['Plan']['retailer'], array('Origin Energy', 'Sumo Power', 'Alinta Energy'))):?>Their <strong><?php echo $plan['Plan']['product_name']; ?></strong> plan <?php if (in_array($plan['Plan']['retailer'], array('AGL', 'Powerdirect')) && $plan['Plan']['res_sme'] == 'RES'):?>includes<?php else:?>gives you<?php endif;?> <?php endif;?><?php echo nl2br($plan['Plan']['signup_summary']); ?></p>
                                        <input type="hidden" name="Plan[product_summary]" value="<?php if ($plan['Plan']['retailer'] != 'Origin Energy'):?>Their <strong><?php echo $plan['Plan']['product_name']; ?></strong> plan gives you <?php endif;?><?php echo nl2br($plan['Plan']['signup_summary']); ?>">
                                        <?php endif;?>
                                    </div>
                         
                            </div>
                            <?php if ($plan['Plan']['retailer'] == 'Alinta Energy' && $plan['Plan']['state'] == 'Victoria'): ?>
                                <div class="form-group">
                                    <div class="read-msg bgylw"><div class="inner">
                                        <p>Alinta Energy may pay commissions to the marketing representative as a result of you entering into this energy contract.</p>
                                    </div></div>
                                </div>
                            <?php endif;?>
                            <div class="form-group display_hide">
                                    <label class="v2_label">Fuel</label>
                                    <div class="v2_field"><select class="planfuel form-control" name="Plan[fuel]">
                                        <option value="">Select</option>
                                        <option value="Elec" <?php if ($plan['Plan']['package'] == 'Elec'): ?>selected="selected"<?php endif; ?>>Electricity</option>
                                        <option value="Gas" <?php if ($plan['Plan']['package'] == 'Gas'): ?>selected="selected"<?php endif; ?>>Gas</option>
                                        <option value="Dual" <?php if ($plan['Plan']['package'] == 'Dual'): ?>selected="selected"<?php endif; ?>>Dual</option>
										</select>
									</div>
                            </div>
                            <div class="form-group display_hide">
                                <?php if (in_array($plan['Plan']['package'], array('Dual', 'Elec'))): ?>
                                    
                                        <label class="v2_label">So your electricity is currently with:</label>
								<div class="v2_field">
                                        <select class="selectpicker show-menu-arrow form-control" name="Plan[elec_supplier]">
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
                                            <option value="OVO Energy" <?php if ($user['step1']['elec_supplier'] == 'OVO Energy'): ?>selected="selected"<?php endif; ?>>OVO Energy</option>
                                            <option value="Simply Energy" <?php if ($user['step1']['elec_supplier'] == 'Simply Energy'): ?>selected="selected"<?php endif; ?>>Simply Energy</option>
                                            <option value="Blue NRG" <?php if ($user['step1']['elec_supplier'] == 'Blue NRG'): ?>selected="selected"<?php endif; ?>>Blue NRG</option>
                                            <option value="Unsure/Other" <?php if ($user['step1']['elec_supplier'] == 'Unsure/Other' || $user['step1']['elec_supplier2'] == 'Unsure/Other'): ?>selected="selected"<?php endif; ?>>Unsure/Other</option>
                                        </select>
								</div>
                                <?php endif; ?>
                                <?php if (in_array($plan['Plan']['package'], array('Dual', 'Gas'))): ?>
                                    
                                        <label class="v2_label">and your gas is currently with:</label>
                                        <div class="v2_field">
								<select class="selectpicker show-menu-arrow form-control" name="Plan[gas_supplier]">
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
                                            <option value="Simply Energy" <?php if ($user['step1']['gas_supplier'] == 'Simply Energy'): ?>selected="selected"<?php endif; ?>>Simply Energy</option>
                                            <option value="Blue NRG" <?php if ($user['step1']['gas_supplier'] == 'Blue NRG'): ?>selected="selected"<?php endif; ?>>Blue NRG</option>
                                            <option value="Unsure/Other" <?php if ($user['step1']['gas_supplier'] == 'Unsure/Other' || $user['step1']['gas_supplier2'] == 'Unsure/Other'): ?>selected="selected"<?php endif; ?>>Unsure/Other</option>
                                        </select>
								</div>
                                <?php endif; ?>
                            </div>
                            <div class="form-group display_hide">
                                    <label class="v2_label">We are organising a change over to</label>
								<div class="v2_field">
                                    <select class="planretailer selectpicker show-menu-arrow" name="Plan[supplier]">
                                        <option value="">Select</option>
                                        <option value="ActewAGL" <?php if ($plan['Plan']['retailer'] == 'ActewAGL'): ?>selected="selected"<?php endif; ?>>ActewAGL</option>
                                        <option value="AGL"<?php if ($plan['Plan']['retailer'] == 'AGL'): ?>selected="selected"<?php endif; ?>>AGL</option>
                                        <option value="Alinta Energy" <?php if ($plan['Plan']['retailer'] == 'Alinta Energy'): ?>selected="selected"<?php endif; ?>>Alinta Energy</option>
                                        <option value="Elysian Energy" <?php if ($plan['Plan']['retailer'] == 'Elysian Energy'): ?>selected="selected"<?php endif; ?>>Elysian Energy</option>
                                        <option value="Energy Australia" <?php if ($plan['Plan']['retailer'] == 'Energy Australia'): ?>selected="selected"<?php endif; ?>>Energy Australia</option>
                                        <option value="ERM" <?php if ($plan['Plan']['retailer'] == 'ERM'): ?>selected="selected"<?php endif; ?>>ERM</option>
                                        <option value="Lumo Energy" <?php if ($plan['Plan']['retailer'] == 'Lumo Energy'): ?>selected="selected" <?php endif; ?>>Lumo Energy</option>
                                        <option value="Momentum" <?php if ($plan['Plan']['retailer'] == 'Momentum'): ?>selected="selected"<?php endif; ?>>Momentum</option>
                                        <option value="Next Business Energy" <?php if ($plan['Plan']['retailer'] == 'Next Business Energy'): ?>selected="selected"<?php endif; ?>>Next Business Energy</option>
                                        <option value="Origin Energy" <?php if ($plan['Plan']['retailer'] == 'Origin Energy'): ?>selected="selected"<?php endif; ?>>Origin Energy</option>
                                        <option value="Powerdirect" <?php if ($plan['Plan']['retailer'] == 'Powerdirect'): ?>selected="selected"<?php endif; ?>>Powerdirect</option>
                                        <option value="Powershop" <?php if ($plan['Plan']['retailer'] == 'Powershop'): ?>selected="selected"<?php endif; ?>>Powershop</option>
                                        <option value="Red Energy" <?php if ($plan['Plan']['retailer'] == 'Red Energy'): ?>selected="selected"<?php endif; ?>>Red Energy</option>
                                        <option value="Sumo Power" <?php if ($plan['Plan']['retailer'] == 'Sumo Power'): ?>selected="selected"<?php endif; ?>>Sumo Power</option>
                                        <option value="Powerdirect and AGL" <?php if ($plan['Plan']['retailer'] == 'Powerdirect and AGL'): ?>selected="selected"<?php endif; ?>>Powerdirect and AGL</option>
                                        <option value="Testing Retailer" <?php if ($plan['Plan']['retailer'] == 'Testing Retailer'): ?>selected="selected"<?php endif; ?>>Testing Retailer</option>
                                        <option value="OVO Energy" <?php if ($plan['Plan']['retailer'] == 'OVO Energy'): ?>selected="selected"<?php endif; ?>>OVO Energy</option>
                                        <option value="Simply Energy" <?php if ($plan['Plan']['retailer'] == 'Simply Energy'): ?>selected="selected"<?php endif; ?>>Simply Energy</option>
                                        <option value="Blue NRG" <?php if ($plan['Plan']['retailer'] == 'Blue NRG'): ?>selected="selected"<?php endif; ?>>Blue NRG</option>
                                    </select>
								</div>
                            </div>
                            <div class="form-group row display_hide">
                                <div class="col-xs-6">
                                    <label class="v2_label">Residential/Business</label>
                                    <input type="hidden" name="Plan[is_soho]" id="is_soho" value="<?php echo ($plan['Plan']['res_sme'] == 'SOHO') ? 1 : 0; ?>"/>
                                    <input type="hidden" name="Plan[res_sme]" value="<?php echo ($plan['Plan']['res_sme'] == 'RES') ? 'Residential' : (($plan['Plan']['res_sme'] == 'SOHO') ? 'SOHO' : 'Business'); ?>"/>
                                    <div class="v2_field">
									<select class="customertype selectpicker show-menu-arrow" name="Plan[res_sme_tmp]">
                                        <option value="">Select</option>
                                        <option value="Residential" <?php if ($plan['Plan']['res_sme'] == 'RES'): ?>selected="selected"<?php endif; ?>>Residential</option>
                                        <option value="Business" <?php if ($plan['Plan']['res_sme'] == 'SME' || $plan['Plan']['res_sme'] == 'SOHO'): ?>selected="selected"<?php endif; ?>>Business</option>
                                    </select>
									</div>
                                </div>
                                <div class="col-xs-6">
                                    <label class="v2_label">Transfer/Move In</label>
                                    <div class="v2_field"><select class="lookingfor selectpicker show-menu-arrow" name="Plan[looking_for]">
                                        <option value="">Select</option>
                                        <option value="Transfer" <?php if ($user['step1']['looking_for'] == 'Compare Plans'): ?>selected="selected"<?php endif; ?>>Transfer</option>
                                        <option value="Move In" <?php if ($user['step1']['looking_for'] == 'Move Properties'): ?>selected="selected"<?php endif; ?>>Move In</option>
										</select></div>
                                </div>
                            </div>
                            <div class="form-group row display_hide">
                                <div class="col-xs-6">
                                    <label class="v2_label">State*</label>
                                    <div class="v2_field"><select class="planstate selectpicker show-menu-arrow" name="Plan[state]">
                                        <option value="QLD" <?php if ($plan['Plan']['state'] == 'Queensland'): ?>selected="selected"<?php endif; ?>>QLD</option>
                                        <option value="VIC" <?php if ($plan['Plan']['state'] == 'Victoria'): ?>selected="selected"<?php endif; ?>>VIC</option>
                                        <option value="NSW" <?php if ($plan['Plan']['state'] == 'New South Wales'): ?>selected="selected"<?php endif; ?>>NSW</option>
                                        <option value="SA" <?php if ($plan['Plan']['state'] == 'South Australia'): ?>selected="selected"<?php endif; ?>>SA</option>
                                        <option value="WA" <?php if ($plan['Plan']['state'] == 'Western Australia'): ?>selected="selected"<?php endif; ?>>WA</option>
                                        <option value="NT" <?php if ($plan['Plan']['state'] == 'Northern Territory'): ?>selected="selected"<?php endif; ?>>NT</option>
                                        <option value="ACT" <?php if ($plan['Plan']['state'] == 'Australian Capital Territory'): ?>selected="selected"<?php endif; ?>>ACT</option>
                                        <option value="NZ" <?php if ($plan['Plan']['state'] == 'New Zealand'): ?>selected="selected"<?php endif; ?>>NZ</option>
                                        <option value="TAS" <?php if ($plan['Plan']['state'] == 'Tasmania'): ?>selected="selected"<?php endif; ?>>TAS</option>
										</select></div>
                                    <input type="hidden" name="Plan[suburb]" value="<?php echo $user['suburb'];?>" id="plan_suburb"/>
                                    <input type="hidden" name="Plan[postcode]" value="<?php echo $user['postcode'];?>" id="plan_postcode"/>
                                </div>
                            </div>
                            <div class="form-group row consumptiondata">
                                <?php if ($plan['Plan']['package'] == 'Dual' || $plan['Plan']['package'] == 'Elec'): ?>
                                    <div class="col-xs-6">
                                        <label class="v2_label">Electricity Consumption Data</label>
                                        <div class="v2_field"><input type="text" name="Plan[elec_consumption_data]" id="elec_consumption_data" class="form-control" value="<?php echo (isset($user['consumption_data'])) ? round(($user['consumption_data']['elec_consumption'] / $user['consumption_data']['elec_billing_days']) * 365, 2) : ''; ?>" <?php if (isset($user['consumption_data'])): ?>readonly="readonly"<?php endif; ?> /></div>
                                    </div>
                                <?php endif; ?>
                                <?php if ($plan['Plan']['package'] == 'Dual' || $plan['Plan']['package'] == 'Gas'): ?>
                                    <div class="col-xs-6">
                                        <label class="v2_label">Gas Consumption Data</label>
                                        <div class="v2_field"><input type="text" name="Plan[gas_consumption_data]" id="gas_consumption_data" class="form-control" value="<?php echo (isset($user['consumption_data'])) ? round(($user['consumption_data']['gas_consumption'] / $user['consumption_data']['gas_billing_days']) * 365, 2) : ''; ?>" <?php if (isset($user['consumption_data'])): ?>readonly="readonly"<?php endif; ?> /></div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <?php if ($plan['Plan']['retailer'] == 'Simply Energy' && $plan['Plan']['state'] == 'New South Wales' && $plan['Plan']['product_name'] == 'Simply NRMA'): ?>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                     <label class="v2_label">Before we start I need to obtain your NRMA Member Number. Please bear in mind you must remain a member of NRMA during your energy plan to continue to be eligible for this offer.</label>
                                    <div class="v2_field"><input type="text" name="Plan[nrma]" id="nrma" class="form-control"></div>
                                </div>
                            </div>
                            <?php endif;?>
                            <div class="form-group tariffs">
                                <label class="v2_label">Tariffs</label>
                                <div class="v2_field"><input type="text" name="Plan[tariffs]" value="<?php echo implode(' & ', $tariffs); ?>" readonly="readonly" class="form-control"/></div>
                            </div>
                            <div class="form-group metertype">
                            	<label class="v2_label">Meter Type</label>
                            	<div class="v2_field"><select class="selectmetertype selectpicker show-menu-arrow" name="Plan[meter_type]">
                                        <option value="">Select</option>
                                        <option value="MRIM">MRIM</option>
                                        <option value="COMMS">COMMS</option>
                                        <option value="BASIC">BASIC</option>
                                    </select></div>
                            </div>
                            <div class="form-group">
                            	<label class="v2_label">Please Select Property Type</label>
                            	<div class="v2_field">
									<select class="selectpropertytype selectpicker show-menu-arrow" name="Plan[property_type]">
                                        <option value="">Select</option>
                                        <option value="Single Site">Single Site</option>
                                        <option value="Multi Site">Multi Site</option>
                                    </select>
                                </div>
                            </div>
                            <?php if ($plan['Plan']['retailer'] == 'Blue NRG'): ?>
                                <div class="form-group">
                                    <div class="read-msg">
                                        <p>Based on our discussions, we are recommending Blue NRG Pty Ltd located in the Melbourne CBD. For Blue NRG to establish your electricity account(s), a voice recording is used to confirm your agreement. You are bound by the terms and conditions of this agreement if you accept it verbally at the conclusion of this agreement.</p>
                                    </div>
                                </div>
                                <div class="form-group row yes-no-btn">
                                    <div class="col-xs-12 required-yes">
                                        <input type="hidden" name="Plan[bluenrg_elec_business]" value="-1">
                                         <label class="v2_label v2">The electricity to be used at your supply address must be for business purposes only. Blue NRG does not provide electricity for residential purposes. Can you please confirm for the purposes of this agreement, that nobody uses the property as their place of residence?</label>
                                        <button class="butn plan_bluenrg_elec_business checkbutton">No</button>
                                        <button class="butn plan_bluenrg_elec_business checkbutton">Yes</button>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="read-msg">
                                        <p>Based on your (meter data or previous invoice) we are estimating your annual electricity consumption to be <?php echo round(($user['consumption_data']['elec_consumption'] / $user['consumption_data']['elec_billing_days']) * 365 / 1000);?>MWh/year
 which makes you eligible for this electricity business plan.</p>
                                    </div>
                                </div>
                                <div class="form-group row yes-no-btn">
                                    <div class="col-xs-12">
                                        <input type="hidden" name="Plan[reconfirm_rates]" value="-1">
                                         <label class="v2_label v2">This agreement has no minimum supply period and no exit fees. Would you like to hear the rates and charges now or would you prefer to review them in your welcome pack?</label>
                                        <button class="butn plan_reconfirm_rates checkbutton" onclick="planreconfirmrates(0)">No</button>
                                        <button class="butn plan_reconfirm_rates checkbutton" onclick="planreconfirmrates(1)">Yes</button>
                                    </div>
                                </div>
                            <?php endif;?>

                            <?php if (in_array($plan['Plan']['retailer'], array('Lumo Energy', 'Origin Energy', 'Simply Energy'))): ?>
                                <?php if ($plan['Plan']['retailer'] == 'Lumo Energy'): ?>
                                    <div class="form-group">
                                        <div class="read-msg bgylw"><div class="inner">
                                            <p>Before I read out the offer conditions, I am required to read to you the rates & charges of this plan, all of which will be inclusive of GST.</p>
                                        </div></div>
                                    </div>
                                <?php endif;?>
                                <?php if ($plan['Plan']['retailer'] == 'Origin Energy'):?>
                                    <div class="form-group">
                                        <div class="read-msg bgylw"><div class="inner">
                                            <h5>READ VERBATIM TO CUSTOMER </h5>
                                            <p>Before I read out the offer conditions, I am required to read to you the rates & charges of this plan, all of which will be inclusive of GST.<br>Please bear in mind you will receive confirmation of these energy rates, tariff type and all the details of your plan in your welcome pack.</p>
                                        </div></div>
                                    </div>
                                <?php endif;?>
                                <?php if ($plan['Plan']['retailer'] == 'Lumo Energy' && $plan['Plan']['state'] != 'Victoria'): ?>
                                    <div class="form-group">
                                        <div class="read-msg">
                                            <h5>READ VERBATIM TO CUSTOMER </h5>
                                            <p>Lumo's Basic Plan Information Document, which is an information sheet that contains all the key details about this offer is available online at lumoenergy.com.au</p>
                                        </div>
                                    </div>
                                <?php endif;?>
                                <?php if ($plan['Plan']['retailer'] == 'Lumo Energy' && $plan['Plan']['state'] == 'Victoria'): ?>
                                    <div class="form-group">
                                        <div class="read-msg">
                                            <h5>READ VERBATIM TO CUSTOMER </h5>
                                            <p>Lumo's Victorian Energy Fact Sheet document, which is an information sheet that contains all the key details about this offer is available online at lumoenergy.com.au</p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if ($plan['Plan']['retailer'] == 'Origin Energy' && $plan['Plan']['state'] != 'Victoria'): ?>
                                    <div class="form-group">
                                        <div class="read-msg">
                                            <h5>Please read verbatim to customer:</h5>
                                            <p>Basic Plan Information Documents are information sheets that contain all the key details about a plan and are available at: <span style="text-decoration: underline; color: #07C; font-weight: bold;">public.compareconnectsave.com.au/BPID-search</span> or on request.<br><br>
                                                <i>(Link to be read as:)</i> Compare dot Deal Expert dot com dot AU forward slash BPID hyphen search.</p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if ($plan['Plan']['retailer'] == 'Origin Energy' && $plan['Plan']['state'] == 'Victoria'): ?>
                                    <div class="form-group">
                                        <div class="read-msg">
                                            <h5>Please read verbatim to customer:</h5>
                                            <p>Victorian Energy Fact Sheets are information sheets that contain all the key details about a plan and are available at public.compareconnectsave.com.au/VEFS or on request.<br><br>
                                                <i>(Link to be read as:)</i> Public dot Deal Expert com dot AU forward slash VEFS)</p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if ($plan['Plan']['retailer'] == 'Simply Energy' && $plan['Plan']['state'] == 'Victoria'): ?>
                                    <div class="form-group row yes-no-btn">
                                    <div class="col-xs-12">
                                        <input type="hidden" name="Plan[market_contract_rates]" value="-1">
                                             <label class="v2_label v2">Your network’s tariff price change are dependent on the regulator and change's once per year. Simply Energy can only increase tariff prices for this energy plan 1 month after your network changes their tariff pricing. We will let you know in advance of any rate change. Your rates are Simply Energy’s market contract rates. Your rates will be in your Welcome Pack and you can view Simply Energy’s Victorian Energy Fact Sheet at simplyenergy.com.au. We can send you a copy via email or post. Would you like me to read the rates, fees and charges to you now or would you like to review them on your own later?</label>
                                            <button class="butn plan_market_contract_rates checkbutton" onclick="planreconfirmrates(0)">No</button>
                                            <button class="butn plan_market_contract_rates checkbutton" onclick="planreconfirmrates(1)">Yes</button>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if ($plan['Plan']['retailer'] == 'Simply Energy' && $plan['Plan']['state'] != 'Victoria'): ?>
                                    <div class="form-group row yes-no-btn">
                                    	<div class="col-xs-12">
                                        	<input type="hidden" name="Plan[market_contract_rates]" value="-1">
                                             <label class="v2_label v2">Your rates are Simply Energy’s market contract rates.<br>Your rates will be in your Welcome Pack and you can view Simply Energy’s Basic Plan Information Documentnat simplyenergy.com.au. We can send you a copy via email or post.<br>Would you like me to read the rates, fees and charges to you now or would you like to review them on your own later?</label>
                                            <button class="butn plan_market_contract_rates checkbutton" onclick="planreconfirmrates(0)">No</button>
                                            <button class="butn plan_market_contract_rates checkbutton" onclick="planreconfirmrates(1)">Yes</button>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if (in_array($plan['Plan']['state'], array('Victoria')) && in_array($plan['Plan']['package'], array('Gas', 'Dual'))): ?>
                                    <div class="form-group">
                                        <div class="read-msg">
                                            <h5>Please read verbatim to customer:</h5>
                                            <p>Gas rates are subject to confirmation of distribution zone at your supply address.</p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if ($plan['Plan']['package'] == 'Elec'): ?>
                                    <div class="form-group">
                                        <div class="read-msg bgylw"><div class="inner">
                                            <h5>READ VERBATIM TO CUSTOMER </h5>
                                            <p>Electricity usage rates are measured in Cents per KWH.</p>
                                        </div></div>
                                    </div>
                                <?php endif;?>
                                <?php if ($plan['Plan']['package'] == 'Gas'): ?>
                                    <div class="form-group">
                                        <div class="read-msg bgylw"><div class="inner">
                                            <h5>READ VERBATIM TO CUSTOMER </h5>
                                            <p>Gas usage rates are measured in Cents per mj.</p>
                                        </div></div>
                                    </div>
                                <?php endif;?>
                                <?php if ($plan['Plan']['package'] == 'Dual'): ?>
                                    <div class="form-group">
                                        <div class="read-msg bgylw"><div class="inner">
                                            <h5>READ VERBATIM TO CUSTOMER </h5>
                                            <p>Electricity usage rates are measured in Cents per KWH & Gas usage rates are measured in Cents per mj</p>
                                        </div></div>
                                    </div>
                                <?php endif;?>
                                <div class="form-group row <?php if ($plan['Plan']['retailer'] == 'Simply Energy'): ?>display_hide reconfirmratesyes<?php endif;?>">
                                    <div class="col-xs-12 table-rate"></div>
                                    <?php if ($plan['Plan']['retailer'] == 'Simply Energy'): ?>
                                    <div class="form-group">
                                        <div class="read-msg">
                                            <h5>Please read verbatim to customer:</h5>
                                            <p>The rates and charges I have discussed with you is based on the information in front of me. The rates and charges Simply Energy will charge you depend on the network tariff at your site, and this will be indicated in your welcome pack.</p>
                                        </div>
                                    </div>
                                    <?php endif;?>
                                </div>
                            <?php endif;?>

                            <?php if ($plan['Plan']['retailer'] == 'ERM'): ?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                         <label class="v2_label">The offer you have accepted today is ERM Business Energy's <?php echo $plan['Plan']['product_name']; ?> offer and your new rates will be:</label>
                                    </div>
                                </div>
                                <?php if ($plan['Plan']['package'] == 'Elec'): ?>
                                    <div class="form-group display_hide rates_measured">
                                        <div class="read-msg bgylw"><div class="inner">
                                            <h5>READ VERBATIM TO CUSTOMER </h5>
                                            <p>Electricity usage rates are measured in Cents per KWH. These rates are including GST</p>
                                        </div></div>
                                    </div>
                                <?php endif;?>
                                <?php if ($plan['Plan']['package'] == 'Gas'): ?>
                                    <div class="form-group display_hide rates_measured">
                                        <div class="read-msg bgylw"><div class="inner">
                                            <h5>READ VERBATIM TO CUSTOMER </h5>
                                            <p>Gas usage rates are measured in Cents per mj. These rates are including GST</p>
                                        </div></div>
                                    </div>
                                <?php endif;?>
                                <?php if ($plan['Plan']['package'] == 'Dual'): ?>
                                    <div class="form-group display_hide rates_measured">
                                        <div class="read-msg bgylw"><div class="inner">
                                            <h5>READ VERBATIM TO CUSTOMER </h5>
                                            <p>Electricity usage rates are measured in Cents per KWH & Gas usage rates are measured in Cents per mj. These rates are including GST</p>
                                        </div></div>
                                    </div>
                                <?php endif;?>
                                <div class="form-group row">
                                    <div class="col-xs-12 table-rate"></div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                         <label class="v2_label">These will be sent out in your contract pack which you will receive via post from ERM Business Energy in the next 48 hours. Energy Price Fact Sheets are available on ERM Business Energy’s website or upon request.</label>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($plan['Plan']['retailer'] == 'Powershop'):?>
                                <?php if ($user['step1']['looking_for'] == 'Move Properties'): ?>
                                    <div class="form-group row yes-no-btn electricitydisconnected">
                                        <div class="col-xs-12 required-no">
                                            <input type="hidden" name="Plan[electricity_disconnected]" value="-1">
                                             <label class="v2_label v2">Has the electricity at the property you're moving to been disconnected for more than 12 months?</label>
                                            <button class="butn plan_electricity_disconnected checkbutton" onclick="planelectricitydisconnected(0)">No</button>
                                            <button class="butn plan_electricity_disconnected checkbutton" onclick="planelectricitydisconnected(1)">Yes</button>
                                        </div>
                                    </div>
                                    <div class="form-group display_hide electricitydisconnectedyes">
                                        <div class="read-msg bgred"><div class="inner">
                                            <h5>READ VERBATIM TO CUSTOMER </h5>
                                            <p>Unfortunately, we cannot proceed with the sign up. Certification by an electrician, may be required before connection can take place. Please call Powershop directly to organise your connection</p>
                                        </div></div>
                                    </div>
                                <?php endif; ?>
                                <div class="form-group row yes-no-btn reconfirmrates">
                                    <div class="col-xs-12">
                                        <input type="hidden" name="Plan[reconfirm_rates]" value="-1">
                                         <label class="v2_label v2">Full details of your rates and charges will be sent to you via email and are also available at powershop.com.au Would you like to hear the rates now?</label>
                                        <button class="butn plan_reconfirm_rates checkbutton" onclick="planreconfirmrates(0)">No</button>
                                        <button class="butn plan_reconfirm_rates checkbutton" onclick="planreconfirmrates(1)">Yes</button>
                                    </div>
                                </div>
                                <?php if ($plan['Plan']['package'] == 'Elec'): ?>
                                    <div class="form-group display_hide rates_measured">
                                        <div class="read-msg bgylw"><div class="inner">
                                            <h5>READ VERBATIM TO CUSTOMER </h5>
                                            <p>Electricity usage rates are measured in Cents per KWH. These rates are including GST</p>
                                        </div></div>
                                    </div>
                                <?php endif;?>
                                <?php if ($plan['Plan']['package'] == 'Gas'): ?>
                                    <div class="form-group display_hide rates_measured">
                                        <div class="read-msg bgylw"><div class="inner">
                                            <h5>READ VERBATIM TO CUSTOMER </h5>
                                            <p>Gas usage rates are measured in Cents per mj. These rates are including GST</p>
                                        </div></div>
                                    </div>
                                <?php endif;?>
                                <?php if ($plan['Plan']['package'] == 'Dual'): ?>
                                    <div class="form-group display_hide rates_measured">
                                        <div class="read-msg bgylw"><div class="inner">
                                            <h5>READ VERBATIM TO CUSTOMER </h5>
                                            <p>Electricity usage rates are measured in Cents per KWH & Gas usage rates are measured in Cents per mj. These rates are including GST</p>
                                        </div></div>
                                    </div>
                                <?php endif;?>
                                <?php if (in_array($plan['Plan']['package'], array('Gas', 'Dual'))): ?>
                                    <div class="form-group display_hide rates_measured">
                                        <div class="read-msg">
                                            <h5>READ VERBATIM TO CUSTOMER </h5>
                                            <p>Gas rates are subject to confirmation of distribution zone at your supply address.</p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <div class="form-group row display_hide reconfirmratesyes">
                                    <div class="col-xs-12 table-rate"></div>
                                </div>
                            <?php endif;?>

                            <?php if (in_array($plan['Plan']['retailer'], array('AGL', 'Powerdirect', 'Powerdirect and AGL', 'Momentum', 'Sumo Power', 'Next Business Energy', 'Alinta Energy', 'Energy Australia', 'ActewAGL', 'OVO Energy', 'Blue NRG'))):?>
                            <?php if ($plan['Plan']['retailer'] != 'Blue NRG'):?>
                                <div class="form-group row yes-no-btn reconfirmrates">
                                    <div class="col-xs-12">
                                        <input type="hidden" name="Plan[reconfirm_rates]" value="-1">
                                        <?php if (in_array($plan['Plan']['retailer'], array('AGL', 'Powerdirect', 'Powerdirect and AGL'))):?>
                                         <label class="v2_label v2">You will receive confirmation of your energy rates and all the details of your plan in your <?php echo $plan['Plan']['retailer'];?> Plan Confirmation. Would you like to hear those rates now?</label>
                                        <?php else:?>
                                         <label class="v2_label v2">You will receive confirmation of your energy rates and all the details of your plan in your welcome pack. Would you like to hear those rates now?</label>
                                        <?php endif;?>
                                        <button class="butn plan_reconfirm_rates checkbutton" onclick="planreconfirmrates(0)">No</button>
                                        <button class="butn plan_reconfirm_rates checkbutton" onclick="planreconfirmrates(1)">Yes</button>
                                    </div>
                                </div>
                                <?php endif;?>
                                <div class="form-group display_hide basic_plan_info_agl">
                                    <div class="read-msg">
                                        <h5>READ VERBATIM TO CUSTOMER </h5>
                                        <p>Basic Plan Information Documents are available by visiting www.agl.com.au/bpid or upon request.</p>
                                    </div>
                                </div>
                                <div class="form-group display_hide basic_plan_info_agl2">
                                    <div class="read-msg">
                                        <h5>READ VERBATIM TO CUSTOMER </h5>
                                        <p>Victorian Energy Fact Sheets are available by visiting agl.com.au or upon request.</p>
                                    </div>
                                </div>
                                <div class="form-group display_hide basic_plan_info_powerdirect">
                                    <div class="read-msg">
                                        <h5>READ VERBATIM TO CUSTOMER </h5>
                                        <p>Basic Plan Information Documents are available by visiting www.powerdirect.com.au or upon request.</p>
                                    </div>
                                </div>
                                <div class="form-group display_hide basic_plan_info_powerdirect2">
                                    <div class="read-msg">
                                        <h5>READ VERBATIM TO CUSTOMER </h5>
                                        <p>Victorian Energy Fact Sheets are available by visiting powerdirect.com.au or upon request.</p>
                                    </div>
                                </div>
                                <?php if ($plan['Plan']['package'] == 'Elec'): ?>
                                    <div class="form-group display_hide rates_measured">
                                        <div class="read-msg bgylw"><div class="inner">
                                            <h5>READ VERBATIM TO CUSTOMER </h5>
                                            <p>Electricity usage rates are measured in Cents per KWH. These rates are including GST</p>
                                        </div></div>
                                    </div>
                                <?php endif;?>
                                <?php if ($plan['Plan']['package'] == 'Gas'): ?>
                                    <div class="form-group display_hide rates_measured">
                                        <div class="read-msg bgylw"><div class="inner">
                                            <h5>READ VERBATIM TO CUSTOMER </h5>
                                            <p>Gas usage rates are measured in Cents per mj. These rates are including GST</p>
                                        </div></div>
                                    </div>
                                <?php endif;?>
                                <?php if ($plan['Plan']['package'] == 'Dual'): ?>
                                    <div class="form-group display_hide rates_measured">
                                        <div class="read-msg bgylw"><div class="inner">
                                            <h5>READ VERBATIM TO CUSTOMER </h5>
                                            <p>Electricity usage rates are measured in Cents per KWH & Gas usage rates are measured in Cents per mj. These rates are including GST</p>
                                        </div></div>
                                    </div>
                                <?php endif;?>
                                <div class="form-group row display_hide reconfirmratesyes">
                                    <div class="col-xs-12 table-rate"></div>
                                    <?php if (in_array($plan['Plan']['retailer'], array('Momentum', 'AGL', 'Alinta Energy', 'Energy Australia', 'Powershop', 'Sumo Power')) && in_array($plan['Plan']['package'], array('Gas', 'Dual'))): ?>
                                        <div class="form-group">
                                            <div class="read-msg">
                                                <h5>Please read verbatim to customer:</h5>
                                                <p>Gas rates are subject to confirmation of distribution zone at your supply address.</p>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif;?>

                            <div class="form-group yes-no-btn row display_hide agreetorates">
                                <div class="col-xs-12 required-yes">
                                    <input type="hidden" name="Plan[understand_agree_rates]" value="-1">
                                     <label class="v2_label v2">Do you understand and agree to these rates?</label>
                                    <button class="butn plan_understand_agree_rates checkbutton">No</button>
                                    <button class="butn plan_understand_agree_rates checkbutton">Yes</button>
                                </div>
                            </div>
                            <?php if ($plan['Plan']['retailer'] == 'Origin Energy'):?>
                                <div class="form-group">
                                    <div class="read-msg">
                                        <h5>Please read verbatim to customer:</h5>
                                        <p>You can find further information on pricing, distribution zone, and metering configuration information on your Origin bill or meter, or alternatively through Origin’s website: www.originenergy.com.au/pricing</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($plan['Plan']['retailer'] == 'ActewAGL' && in_array($plan['Plan']['package'], array('Dual', 'Elec'))):?>
                            <div class="form-group">
                                <div class="read-msg">
                                    <h5>Please read verbatim to customer:</h5>
                                    <p>Subject to confirmation of the meter type at the supply address, the daily supply charge may be less than originally quoted to you today. Your welcome pack from ActewAGL Retail will reflect the correct charge.</p>
                                </div>
                            </div>
                            <?php endif; ?>
                            <div class="form-group yes-no-btn row display_hide solarpanels">
                                <div class="col-xs-12">
                                    <input type="hidden" name="Plan[solar_panels]" value="-1" class="PlanSolarPanels">
                                     <label class="v2_label v2">Do you have solar panels installed at your property?</label>
                                    <button class="butn plan_solar_panels checkbutton" onclick="plansolarpanels(0)">No</button>
                                    <button class="butn plan_solar_panels checkbutton" onclick="plansolarpanels(1)">Yes</button>
                                </div>
                            </div>
                            <?php if ($plan['Plan']['retailer'] == 'AGL' && $plan['Plan']['product_name'] == 'AGL Solar Savers' && in_array($plan['Plan']['state'], array('New South Wales', 'Queensland', 'South Australia'))): ?>
                                <div class="form-group">
                                    <div class="read-msg bgylw"><div class="inner">
                                        <h5>Please read verbatim to customer:</h5>
                                        <p>Please be advised that the rates are subject to change in July 2021, tariff structures may also change.</p>
                                    </div></div>
                                </div>
                            <?php endif;?>
                            <div class="form-group row display_hide solarpanelsyes">
                            <?php if ((in_array($plan['Plan']['retailer'], array('AGL', 'Powerdirect'))) && $plan['Plan']['product_name'] != 'AGL Solar Savers' && in_array($plan['Plan']['state'], array('New South Wales', 'Queensland', 'South Australia'))): ?>
                                <div class="form-group">
                                    <div class="read-msg bgylw"><div class="inner">
                                        <h5>Please read verbatim to customer:</h5>
                                        <p>This solar feed-in tariff is variable and can change with notice to you at any time.</p>
                                    </div></div>
                                </div>
                            <?php endif;?>
                            <?php if ((in_array($plan['Plan']['retailer'], array('AGL', 'Powerdirect'))) && $plan['Plan']['product_name'] != 'AGL Solar Savers' && in_array($plan['Plan']['state'], array('Victoria'))): ?>
                                <div class="form-group">
                                    <div class="read-msg bgylw"><div class="inner">
                                        <h5>Please read verbatim to customer:</h5>
                                        <p>This solar feed-in tariff is variable and can change with notice to you at any time. If we vary your solar feed-in tariff, we will give you at least five business days prior notice of the variation.</p>
                                    </div></div>
                                </div>
                            <?php endif;?>
                                <?php if ((($user['step1']['elec_supplier'] = 'AGL' || $user['step1']['elec_supplier2'] == 'AGL') && $plan['Plan']['retailer'] == 'AGL') || (($user['step1']['elec_supplier'] = 'Powerdirect' || $user['step1']['elec_supplier2'] == 'Powerdirect') && $plan['Plan']['retailer'] == 'Powerdirect') || (($user['step1']['elec_supplier'] = 'Powerdirect and AGL' || $user['step1']['elec_supplier2'] == 'Powerdirect and AGL') && $plan['Plan']['retailer'] == 'Powerdirect and AGL')):?>
                                <div class="aglpd-solar">
                                    <div class="read-msg">
                                        <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                        <h5>READ if electricity is retention</h5>
                                        <p>READ IF RETENTION: (STATE CUSTOMER NAME) As you are a current <?php echo $plan['Plan']['retailer'];?> customer, your Feed-in Tariff may be different, by signing up to this product the feed-in tariff will be:</p>
                                    </div>
                                </div>
                                <?php endif;?>
                                <?php if ($plan['Plan']['retailer'] == 'Simply Energy' && $plan['Plan']['package'] != 'Gas'): ?>
                                    <div class="form-group">
                                        <div class="read-msg">
                                            <h5>Please read verbatim to customer:</h5>
                                            <p>If you're eligible for a Government Feed-in tariff, we'll credit you the relevant amount, otherwise you'll receive Simply Energy's standard feed-in tariff</p>
                                        </div>
                                    </div>
                                    <?php if ($plan['Plan']['state'] == 'Victoria'): ?>
                                    <div class="form-group">
                                        <div class="read-msg">
                                            <h5>Please read verbatim to customer:</h5>
                                            <p>In Victoria the Solar Feed in rate change is dependent on the regulator. The rate typically changes once per year and we have no plans to change your Solar Feed in rate in the coming months. However, we will let you know in advance of any rate change.</p>
                                        </div>
                                    </div>
                                    <?php endif;?>
                                <?php endif; ?>
                                <div class="col-xs-12 table-rate-solar"></div>
                                <div class="origin-solar">
                                    <div class="read-msg">
                                        <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                        <h5>READ VERBATIM TO CUSTOMER </h5>
                                        <p>You can review the solar feed in tariffs discussed today in your welcome pack. If you have any questions you can also contact Origin Solar Resolutions team on 1300 791 468.</p>
                                    </div>
                                </div>
                                <div class="momentum-solar">
                                    <div class="read-msg">
                                        <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                        <h5>READ VERBATIM TO CUSTOMER </h5>
                                        <p>If this application’s approved, you’ve agreed to enter into a solar feed-in tariff agreement with Momentum, with terms that are additional to the terms of your electricity Market Contract.</p>
                                    </div>
                                </div>
                            </div>
                            <?php if ($plan['Plan']['retailer'] == 'AGL' && in_array($plan['Plan']['res_sme'], array('SOHO'))): ?>
                                <div class="form-group yes-no-btn row">
                                    <div class="col-xs-12 required-yes">
                                        <input type="hidden" name="Plan[operating_business]" value="-1">
                                         <label class="v2_label v2">FOR AGL HOME OFFICE EVERYDAY ONLY : Can you please confirm that you are operating a business from your premises?</label>
                                        <button class="butn plan_operating_business checkbutton" onclick="operatingbusiness(0)">No</button>
                                        <button class="butn plan_operating_business checkbutton" onclick="operatingbusiness(1)">Yes</button>
                                    </div>
                                </div>
                                <div class="form-group display_hide operatingbusinessno">
                                    <div class="read-msg bgred"><div class="inner">
                                        <h5>READ VERBATIM TO CUSTOMER </h5>
                                        <p>We can not proceed with the sign up</p>
                                    </div></div>
                                </div>
                            <?php endif; ?>
                            <?php if ($plan['Plan']['retailer'] == 'Lumo Energy' && in_array($plan['Plan']['state'], array('New South Wales', 'Queensland', 'South Australia')) && in_array($plan['Plan']['package'], array('Elec', 'Dual'))): ?>
                                <div class="form-group yes-no-btn">
                                    <div class="read-msg bgylw"><div class="inner">
                                        <input type="hidden" name="Plan[smart_meter]" value="-1">
                                         <label class="v2_label v2">While you are with Lumo, they may replace your meter with a smart meter. This could occur at your request, or where Lumo are advised it needs to be replaced under the National Electricity Rules. Lumo may also decide to install a smart meter as part of a new meter deployment. Do you consent to this?</label>
                                        <button class="butn plan_smart_meter checkbutton" onclick="smartmeter(0)">No</button>
                                        <button class="butn plan_smart_meter checkbutton" onclick="smartmeter(1)">Yes</button>
                                    </div></div>
                                </div>
                                <div class="form-group display_hide smartmeterno">
                                    <div class="read-msg bgylw"><div class="inner">
                                        <h5>READ VERBATIM TO CUSTOMER </h5>
                                        <p>Lumo may still need to replace your meter while you are their customer if required under the National Electricity Rules, for example, if your meter is faulty or coming to the end of its life. If so, Lumo will install a smart meter without active telecommunications. This means the smart meter will not communicate with Lumo’s system and will need to be manually read. There are costs you will incur if you request telecommunications turned off, these include a de-activation fee and ongoing meter reading fees. These fees can be found on Lumo's website at: https://lumoenergy.com.au/help-centre</p>
                                    </div></div>
                                </div>
                                <div class="read-msg yes-no-btn">
                                    <button class="butn meterexchange-btn">PRESS: If customer asks "Will the meter exchange cost anything?"</button>
                                </div>
                                <div class="form-group display_hide meterexchangetext">
                                    <div class="read-msg bgylw"><div class="inner">
                                        <h5>READ VERBATIM TO CUSTOMER </h5>
                                        <p>Unless there is something about your meter or your property that means the smart meter installation requires more work than usual, customers who consent to having smart meters installed will generally not pay any extra than customers who do not. If your meter is faulty or is coming to the end of its life and needs to be replaced, Lumo will cover the cost of the meter and a simple installation. For other situations we will advise you of this and agree the costs with you before installation.</p>
                                    </div></div>
                                </div>
                            <?php endif;?>
                            <div class="submit-btnotr text-right"><input onclick="checkPlanFields();" type="submit" class="submit-btn" value="Mark section as Completed"/></div>
                        </div>
                    </form>
                </div>
			
                <h3 class="form-hdg">Contact Details<i class="fa fa-angle-up" aria-hidden="true"></i><i class="fa fa-angle-down" aria-hidden="true"></i></h3>
            	<div class="form clearfix collapse" id="form-collapse2">
                    <form action="" method="post" class="signup_contact" name="signup_contact">
                        <div class="form-data contactdetialinputs">
                            <div class="form-group"><p>Please confirm the Customers, title get them to spell their first and last name - make sure you obtain 2 phone numbers for them</p></div>
                            <?php if ($user['step1']['looking_for'] == 'Compare Plans'):?>
                            <div class="form-group yes-no-btn row contactauthorised">
                                <div class="col-xs-12 required-yes">
                                    <input type="hidden" name="Contact[authorised]" value="-1">
                                    <?php if ($plan['Plan']['res_sme'] == 'SME'):?>
                                         <label class="v2_label v2">Are you authorised to enter into this agreement on behalf of the business?</label>
                                    <?php else:?>
                                         <label class="v2_label v2">Are you authorised to make this change to the energy account/s at this property?</label>
                                    <?php endif;?>
                                    <button class="butn contact_authorised checkbutton">No</button>
                                    <button class="butn contact_authorised checkbutton">Yes</button>
                                </div>
                            </div>
                            <?php else:?>
                                <?php if ($plan['Plan']['res_sme'] == 'SME'):?>
                                <div class="form-group yes-no-btn row">
                                    <div class="col-xs-12 required-yes">
                                        <input type="hidden" name="Contact[authorised]" value="-1">
                                             <label class="v2_label v2">Are you authorised to enter into this agreement on behalf of the business?</label>
                                        <button class="butn contact_authorised checkbutton">No</button>
                                        <button class="butn contact_authorised checkbutton">Yes</button>
                                    </div>
                                </div>
                                <?php endif;?>
                            <?php endif;?>
                            <div class="form-group">
                                    <label class="v2_label">Title*</label>
									<div class="v2_field">
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
							</div>
							<div class="form-group">
                                <label class="v2_label">First Name*</label>
                                <div class="v2_field"><input class="contactdetialinputstext form-control" type="text" name="Contact[first_name]" id="contact_first_name" value=""/>
                                </div>
							</div>	
                                <?php if ($plan['Plan']['retailer'] == 'Alinta Energy'):?>
                                <div class="form-group">
                                    <label class="v2_label">Middle Name</label>
                                    <div class="v2_field"><input class="contactdetialinputstext form-control" type="text" name="Contact[middle_name]" id="contact_middle_name" value=""/></div>
                                </div>
                                <?php endif;?>
                                <div class="form-group">
                                    <label class="v2_label">Surname*</label>
                                    <div class="v2_field"><input class="contactdetialinputstext form-control" type="text" name="Contact[last_name]" id="contact_last_name" value=""/></div>
                                </div>
                            <div class="form-group company-position">
                                <label class="v2_label">Company Position</label>
                                <div class="v2_field">
									<input type="text" value="Authorised Contact" name="Contact[company_position]" class="contactdetialinputstext form-control"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="v2_label">Date of Birth*</label>
                                <div class="v2_field">
									<input type="text" value="" name="Contact[dateofbirth]" class="datepickerDateOfBirth calender-icon contactdetialinputstext form-control" id="datepickerDateOfBirth"/>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <label class="v2_label">Mobile Number*</label>
                                    <div class="v2_field"><input class="contactdetialinputstext form-control" type="text" value="" name="Contact[mobile]" id="contact_mobile"/></div>
                                </div>
                                <div class="col-xs-6">
                                    <label class="v2_label">Home Number</label>
                                    <div class="v2_field"><input class="contactdetialinputstext form-control" type="text" value="" name="Contact[home_phone]" id="contact_home_phone"/></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-8">
									<label class="v2_label">Please spell your email out*</label>
									<div class="v2_field"><input class="contactdetialinputstext form-control" type="text" name="Contact[email]" id="contact_email" value=""/>
									</div>
								</div>	
                                <?php if ($plan['Plan']['retailer'] != 'Powershop' && $plan['Plan']['product_name'] != 'Smile Power Plus'): ?>
                                    <div class="col-xs-4 pdr0">
                                         
                                        <button class="noemail-btn">No Email</button>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="form-group row display_hide alintabilling">
                                <div class="col-xs-12">
                                    <label class="v2_label">What is your preferred contact method?</label>
                                    <div class="v2_field"><select class="selectBillingContactMethod selectpicker show-menu-arrow" name="Billing[contact_method]">
                                        <option value="">Select</option>
                                        <option value="Home">Home</option>
                                        <option value="Work">Work</option>
                                        <option value="Mobile">Mobile</option>
                                        <option value="Email">Email</option>
										</select></div>
                                </div>
                            </div>
                            <?php if ($plan['Plan']['retailer'] == 'Simply Energy'):?>
                                <div class="form-group yes-no-btn row display_hide simplyenergywelcomepack">
                                    <div class="col-xs-12">
                                        <input type="hidden" name="Contact[welcome_pack_simply_energy]" value="-1" class="contact_welcome_pack_simply_energy">
                                         <label class="v2_label v2">We will be sending you a welcome pack, which covers everything we have spoken about today in greater detail, this includes things like your offer details, energy rates. Would you like to receive your bills and other correspondence via email?<br>(Advise the customer that they can change this any time by contacting customer service)</label>
                                        <button type="button" onclick="simplyenergy_email_bill(0)" class="butn contact_welcome_pack_simply_energy checkbutton">No</button>
                                        <button type="button" onclick="simplyenergy_email_bill(1)" class="butn contact_welcome_pack_simply_energy checkbutton">Yes</button>
                                    </div>
                                </div>
                                <div class="form-group display_hide simplyenergynoemail">
                                    <div class="read-msg">
                                        <h5>READ VERBATIM TO CUSTOMER </h5>
                                        <p>Okay, I do need to let you know that by receiving your bills via post you will be charged a fee of $1.65 including GST. So are you happy for me to proceed with the paper bill setup?</p>
                                    </div>
                                </div>
                            <?php endif;?>
                            <?php if ($plan['Plan']['product_name'] == 'ACT Energy Rewards 25 (E-Comms Mandatory)'): ?>
                                <div class="form-group actenergyrewards25">
                                    <div class="read-msg">
                                        <h5>READ VERBATIM TO CUSTOMER </h5>
                                        <p>(E-mail Mandatory Plan) You will receive your bills and all communications from ActewAGL via Email.</p>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="form-group yes-no-btn row display_hide actewagl_email_bill">
                                    <div class="col-xs-12">
                                        <input type="hidden" name="Contact[actewagl_email_bill]" class="ContactActewAGLEmailBill" value="-1">
                                         <label class="v2_label">Would you like to receive your bills and all communications from ActewAGL via Email?</label>
                                        <button type="button" class="butn contact_actewagl_email_bill checkbutton">No</button>
                                        <button type="button" class="butn contact_actewagl_email_bill checkbutton">Yes</button>
                                    </div>
                                </div>
                                <div class="form-group display_hide actewaglnoemail">
                                    <div class="read-msg">
                                        <h5>READ VERBATIM TO CUSTOMER </h5>
                                        <p>As you do not have an email your bills and all communications from ActewAGL will come via the post.</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="form-group display_hide aglnoemailall">
                                <div class="read-msg">
                                    <h5>READ VERBATIM TO CUSTOMER </h5>
                                    <p>As you don't have an email <?php echo $plan['Plan']['retailer'];?> will send your bills, Plan Confirmation and other important communication about your account by post.</p>
                                </div>
                            </div>
                            <div class="form-group display_hide aglnoemail">
                                <div class="read-msg">
                                    <h5>READ VERBATIM TO CUSTOMER</h5>
                                    <p>Customers receiving a paper bill may incur a $1.75 fee. You can avoid this fee if you hold a concession card or by supplying <?php echo $plan['Plan']['retailer'];?> with an email address in the future.</p>
                                </div>
                            </div>
                            <?php if ($plan['Plan']['state'] != 'New South Wales'):?>
                                <div class="form-group display_hide EAnoemail">
                                    <div class="read-msg bgylw"><div class="inner">
                                        <h5>READ VERBATIM TO CUSTOMER</h5>
                                        <p>As you do not have an email address, your invoices and any changes regarding your account will be sent via the post. </p>
                                    </div></div>
                                </div>
                            <?php endif;?>
                            <div class="form-group yes-no-btn row display_hide bluenrg_email_bill">
                                <div class="col-xs-12">
                                    <input type="hidden" name="Contact[bluenrg_email_bill]" class="ContactBlueNrgEmailBill" value="-1">
                                     <label class="v2_label v2">Blue NRG will send your welcome pack, invoices & other important communication about your account via email, is that ok?</label>
                                    <button type="button" onclick="bluenrg_email_bill(0)" class="butn contact_bluenrg_email_bill checkbutton">No</button>
                                    <button type="button" onclick="bluenrg_email_bill(1)" class="butn contact_bluenrg_email_bill checkbutton">Yes</button>
                                </div>
                            </div>
                            <div class="form-group display_hide bluenrg_postal_invoices">
                                <div class="read-msg">
                                    <h5>READ VERBATIM TO CUSTOMER </h5>
                                    <p>Postal invoices in Victoria will incur a admin fee of $5 inclusive of GST.</p>
                                </div>
                            </div>
                            <div class="form-group display_hide bluenrgnoemail">
                                <div class="read-msg bgred"><div class="inner">
                                    <h5>READ VERBATIM TO CUSTOMER </h5>
                                    <p>As you don’t have an email, you will receive your welcome pack, invoices & other important communication about your account via post.</p>
                                </div></div>
                            </div>
                            <div class="form-group display_hide nextbusinessnoemail">
                                <div class="read-msg bgred"><div class="inner">
                                    <h5>READ VERBATIM TO CUSTOMER </h5>
                                    <p>An email is required for Next Business Energy without an email we can not complete the agreement.</p>
                                </div></div>
                            </div>
                            <div class="form-group display_hide momentumnoemail">
                                <div class="read-msg">
                                    <h5>READ VERBATIM TO CUSTOMER </h5>
                                    <p>As you don't have an email Momentum will send your bills and all communication via the post. This includes notices like disconnection warnings and changes to your pricing.</p>
                                </div>
                            </div>
                            <div class="form-group yes-no-btn row display_hide agl_email_bill">
                                <div class="col-xs-12">
                                    <input type="hidden" name="Contact[agl_email_bill]" class="ContactAglEmailBill" value="-1">
                                     <label class="v2_label v2">Do you want to receive bills, your Plan Confirmation and other important communication about your account by email?</label>
                                    <button type="button" onclick="agl_email_bill(0)" class="butn contact_agl_email_bill checkbutton">No</button>
                                    <button type="button" onclick="agl_email_bill(1)" class="butn contact_agl_email_bill checkbutton">Yes</button>
                                </div>
                            </div>
                            <div class="form-group yes-no-btn row display_hide pd_email_bill">
                                <div class="col-xs-12">
                                    <input type="hidden" name="Contact[powerdirect_email_bill]" class="ContactPowerdirectEmailBill" value="-1">
                                     <label class="v2_label v2">Do you want to receive bills, your Plan Confirmation and other important communication about your account by email?</label>
                                    <button type="button" onclick="pd_email_bill(0)" class="butn contact_powerdirect_email_bill checkbutton">No</button>
                                    <button type="button" onclick="pd_email_bill(1)" class="butn contact_powerdirect_email_bill checkbutton">Yes</button>
                                </div>
                            </div>
                            <div class="form-group yes-no-btn row display_hide pd_agl_email_bill">
                                <div class="col-xs-12">
                                    <input type="hidden" name="Contact[pd_agl_email_bill]" class="ContactPowerdirectAGLEmailBill" value="-1">
                                     <label class="v2_label v2">Do you want to receive bills, your Plan Confirmation and other important communication about your account by email?</label>
                                    <button type="button" onclick="pd_agl_email_bill(0)" class="butn contact_pd_agl_email_bill checkbutton">No</button>
                                    <button type="button" onclick="pd_agl_email_bill(1)" class="butn contact_pd_agl_email_bill checkbutton">Yes</button>
                                </div>
                            </div>
                            <div class="form-group yes-no-btn row display_hide receivebillsbypost">
                                <div class="col-xs-12">
                                    <input type="hidden" name="Contact[receive_bills_by_post]" value="-1">
                                     <label class="v2_label v2">Customers receiving a paper bill will incur a $1.75 fee unless they hold a concession card. Do you still want to receive the bills by post?</label>
                                    <button type="button" onclick="receive_bills_by_post(0)" class="butn contact_receive_bills_by_post checkbutton">No</button>
                                    <button type="button" onclick="receive_bills_by_post(1)" class="butn contact_receive_bills_by_post checkbutton">Yes</button>
                                </div>
                            </div>
                            <?php if (strpos($plan['Plan']['product_name'], 'Origin Max Saver') !== false):?>
                            <div class="form-group">
                                <div class="read-msg">
                                    <h5>READ VERBATIM TO CUSTOMER </h5>
                                    <p>You will receive correspondence and bills by email unless Origin advise otherwise. If Origin are unable to reach you by email, they will send correspondence to your nominated postal address.<br>
                                        Once you receive your agreement pack, please ensure you contact Origin to set up direct debit (and monthly billing if you have a smart meter); or alternatively, you can set up direct debit via the Origin app or Origin’s My Account online</p>
                                </div>
                            </div>
                            <?php endif;?>
                            <div class="form-group yes-no-btn row display_hide origin_email_bill">
                                <div class="col-xs-12">
                                    <input type="hidden" name="Contact[origin_email_bill]" class="ContactOriginEmailBill" value="-1">
                                     <label class="v2_label v2">You will receive correspondence and bills by email unless Origin advise otherwise. Is this ok?</label>
                                    <button type="button" onclick="origin_email_bill(0)" class="butn contact_origin_email_bill checkbutton">No</button>
                                    <button type="button" onclick="origin_email_bill(1)" class="butn contact_origin_email_bill checkbutton">Yes</button>
                                </div>
                            </div>
                            <div class="form-group display_hide originemailbillyes">
                                <div class="read-msg">
                                    <h5>READ VERBATIM TO CUSTOMER </h5>
                                    <p>If Origin are unable to reach you by email, they will send correspondence to your nominated postal address.</p>
                                </div>
                            </div>
                            <div class="form-group display_hide originemailbillno">
                                <div class="read-msg">
                                    <h5>READ VERBATIM TO CUSTOMER </h5>
                                    <p>All correspondence will arrive by post and a $1.75 incl GST paper bill fee will apply.</p>
                                </div>
                            </div>
                            <div class="form-group display_hide originemailbillno2">
                                <div class="read-msg">
                                    <h5>READ VERBATIM TO CUSTOMER </h5>
                                    <p>Great, all correspondence will arrive by post.</p>
                                </div>
                            </div>
                            <div class="form-group display_hide originpayataustraliapost">
                                <div class="read-msg">
                                    <h5>READ VERBATIM TO CUSTOMER </h5>
                                    <p>If you choose to receive a paper bill, a $1.75 incl GST paper bill fee will apply.</p>
                                </div>
                            </div>
                            <div class="form-group yes-no-btn row display_hide EA_email_bill">
                                <div class="col-xs-12">
                                    <input type="hidden" name="Contact[EA_email_bill]" class="ContactEAEmailBill" value="-1">
                                     <label class="v2_label v2">Would you like to receive your welcome pack and bills via email?</label>
                                    <button type="button" onclick="EA_email_bill(0)" class="butn contact_EA_email_bill checkbutton">No</button>
                                    <button type="button" onclick="EA_email_bill(1)" class="butn contact_EA_email_bill checkbutton">Yes</button>
                                </div>
                            </div>
                            <div class="form-group yes-no-btn row display_hide erm_email_bill">
                                <div class="col-xs-12">
                                    <input type="hidden" name="Contact[erm_email_bill]" class="ContactERMEmailBill" value="-1">
                                     <label class="v2_label v2">Would you like to receive bills and other correspondence regarding your contract via email to this address?</label>
                                    <button type="button" onclick="erm_email_bill(0)" class="butn contact_erm_email_bill checkbutton">No</button>
                                    <button type="button" onclick="erm_email_bill(1)" class="butn contact_erm_email_bill checkbutton">Yes</button>
                                </div>
                            </div>
                            <div class="read-msg display_hide lumo_email_bill_sa">
                                <p>
                                    <span style="font-weight: bold; color: red">An email address is mandatory for Lumo Movers & Lumo Plus in SA Read: </span><br>Lumo will issue your bills quarterly and you will receive your bills and certain communications via email.
                                </p>
                            </div>
                            <div class="form-group display_hide lumo_email_bill">
                                <div class="read-msg">
                                    <div class="form-group yes-no-btn row">
                                        <div class="col-xs-12 ">
                                            <input type="hidden" name="Contact[lumo_email_invoices]" class="ContactLumoEmailInvoices" value="-1">
                                             <label class="v2_label v2">
                                                Lumo Energy will send your energy bills and notices via email. Certain notices may be delivered via post. If they are unable to deliver an email to your nominated address, they will send it via post and may update your delivery preference<br><br>Are you happy to receive electronic communications?
                                            </label>
                                            <button type="button" onclick="lumo_email_invoices(0)" class="butn contact_lumo_email_invoices checkbutton">No</button>
                                            <button type="button" onclick="lumo_email_invoices(1)" class="butn contact_lumo_email_invoices checkbutton">Yes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group display_hide lumoemailbillno">
                                <div class="read-msg">
                                    <h5>READ VERBATIM TO CUSTOMER </h5>
                                    <p>Lumo Energy will send your energy bills and notices via post. You can update your preference to email at any time by contacting Lumo or on their website via MyAccount.</p>
                                </div>
                            </div>
                            <div class="form-group yes-no-btn row display_hide alinta_secondary_account">
                                <div class="col-xs-12">
                                    <input type="hidden" name="Contact[alinta_secondary_account]" class="ContactAlintaSecondaryAccount" value="-1">
                                     <label class="v2_label v2">Would you like to add a secondary account holder?
                                        (A secondary contact can access the account for enquiries only & is not financially responsible)</label>
                                    <button type="button" onclick="alinta_secondary_account(0)" class="butn contact_alinta_secondary_account checkbutton">No</button>
                                    <button type="button" onclick="alinta_secondary_account(1)" class="butn contact_alinta_secondary_account checkbutton">Yes</button>
                                </div>
                            </div>
                            <div class="form-group row add-a-secondary-contact">
                                <div class="col-xs-12 checkbox-sec">
                                    <input id="secodryCheckbox" onclick="checkContactSecondary(this,'.secondary-contact')" type="checkbox" name="Contact[Secondary]" value="1"/>
                                    <label class="checkbox-clone" for="secodryCheckbox">&nbsp;</label><span onclick="checkContactSecondarySpan('#secodryCheckbox','.secondary-contact')">Add a Secondary Contact</span>
                                </div>
                            </div>
                            <div class="form-group display_hide secondarycontactno">
                                <div class="read-msg bgylw"><div class="inner">
                                    <h5>READ VERBATIM TO CUSTOMER </h5>
                                    <p>Unfortunately we can not add a secondary contact at this point of the sign up. However, once you receive your welcome pack you can call <?php echo $plan['Plan']['retailer']; ?> and they will gladly add a secondary contact to your account.</p>
                                </div></div>
                            </div>
                            <div class="secondary-contact">
                                <h3 class="form-hdg">
                                    <div class="fh-inr">Secondary Contact</div>
                                </h3>
                                <div class="form-data">
                                    <div class="form-group row">
                                        <div class="col-xs-2">
                                            <label class="v2_label">Title*</label>
                                            <div class="v2_field"><select class="selectsecondarytitle selectpicker show-menu-arrow" name="Secondary[title]" id="contact_secondary_title">
                                                <option value="">Select</option>
                                                <option value="Mr">Mr</option>
                                                <option value="Mrs">Mrs</option>
                                                <option value="Ms">Ms</option>
                                                <option value="Miss">Miss</option>
                                                <option value="Lady">Lady</option>
                                                <option value="Rev">Rev</option>
                                                <option value="Dr">Dr</option>
												</select></div>
                                        </div>
                                        <div class="col-xs-5">
                                            <label class="v2_label">First Name*</label>
                                            <div class="v2_field"><input class="secondary_input form-control" type="text" name="Secondary[first_name]" value="" id="contact_secondary_first_name"/></div>
                                        </div>
                                        <div class="col-xs-5">
                                            <label class="v2_label">Surname*</label>
                                            <div class="v2_field"><input class="secondary_input form-control" type="text" class="" name="Secondary[last_name]" value="" id="contact_secondary_last_name"/></div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-12"><label class="v2_label">Date of Birth*</label>
                                            <div class="v2_field"><input class="secondary_input calender-icon datepickerDateOfBirthSec form-control" id="datepickerDateOfBirthSec" type="text" value="" name="Secondary[dateofbirth]"/></div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label class="v2_label">Mobile Number*</label>
                                            <div class="v2_field"><input class="secondary_input form-control" type="text" value="" name="Secondary[mobile]" id="contact_secondary_mobile"/></div>
                                        </div>
                                        <div class="col-xs-6">
                                            <label class="v2_label">Home Number</label>
                                            <div class="v2_field"><input class="secondary_input form-control" type="text" value="" name="Secondary[home_phone]" id="contact_secondary_home_phone"/></div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                            <label class="v2_label">Email</label>
                                            <div class="v2_field"><input class="secondary_input form-control" type="text" value="" name="Secondary[email]" id="contact_secondary_email"/></div>
                                        </div>
                                    </div>
                                    <div class="form-group row display_hide secondary_contact_method">
                                        <div class="col-xs-12">
                                            <label class="v2_label">Do you have a preferred contact method for the secondary contact?</label>
                                            <div class="v2_field"><select class="selectBillingSecondaryContactMethod selectpicker show-menu-arrow" name="Billing[secondary_contact_method]">
                                                <option value="">Select</option>
                                                <option value="Home">Home</option>
                                                <option value="Work">Work</option>
                                                <option value="Mobile">Mobile</option>
                                                <option value="Email">Email</option>
												</select></div>
                                        </div>
                                    </div>
                                    <?php if ($plan['Plan']['retailer'] == 'Origin Energy'):?>
                                        <div class="form-group">
                                            <div class="read-msg bgylw"><div class="inner">
                                                <h5>READ VERBATIM TO CUSTOMER </h5>
                                                <p>A joint account holder has the same access to the account as you. Please note that because you're the primary account holder, you're still solely financially responsible for the Origin account. Since you have given us [STATE THE JOINT ACC HOLDER NAME] details today, please let them know that their information has been provided to Origin and tell them about Origin’s privacy and credit reporting statement, which can be found at www.originenergy.com.au/privacy.</p>
                                            </div></div>
                                        </div>
                                    <?php endif;?>
                                </div>
                            </div>
                            <div class="submit-btnotr text-right"><input type="submit" onclick="checkContactFields();" class="submit-btn checkContactFields" value="Mark section as Completed"/></div>
                        </div>
                    </form>
                </div>
			
                <h3 class="form-hdg business_details">Business Details<i class="fa fa-angle-up" aria-hidden="true"></i><i class="fa fa-angle-down" aria-hidden="true"></i></h3>
            	<div class="form clearfix collapse" id="form-collapse3">
                    <form action="" method="post" class="signup_business_details" name="signup_business_details">
                        <div class="form-data">
                            <div class="form-group row">
                                <div class="col-xs-6">
                                    <div class="form-group inner-form-sec row">
                                        <div class="col-xs-8">
                                             <label class="v2_label">ABN*</label>
                                            <div class="v2_field"><input id="abn" name="Business[abn]" class="form-control" type="text" value=""/></div>
                                        </div>
                                        <div class="col-xs-4 pdr0">
                                             <label class="v2_label">&nbsp;</label>
                                            <button class="lookup-btn">Lookup</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-6">
                                     <label class="v2_label">Trading Name*</label>
                                    <div class="v2_field"><input id="trading_name" name="Business[trading_name]" class="form-control" type="text" value=""/></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                     <label class="v2_label">Legal Name*</label>
                                    <div class="v2_field"><input id="legal_name" name="Business[legal_name]" class="form-control" type="text" value=""/></div>
                                </div>
                                <div class="col-xs-6">
                                     <label class="v2_label">Business Type*</label>
                                    <div class="v2_field"><select id="business_type" name="Business[type]" class="businesstype selectpicker show-menu-arrow">
                                        <option value="">Select</option>
                                        <?php if ($plan['Plan']['retailer'] == 'Energy Australia'):?>
                                            <option value="AGRICULTURE">AGRICULTURE</option>
                                            <option value="COMMUNICATION">COMMUNICATION</option>
                                            <option value="COMMUNITY_SVC">COMMUNITY_SVC</option>
                                            <option value="CONSTRUCTION">CONSTRUCTION</option>
                                            <option value="EDUCATION">EDUCATION</option>
                                            <option value="FINANCE">FINANCE</option>
                                            <option value="GOVERNMENT">GOVERNMENT</option>
                                            <option value="HOSPITALITY">HOSPITALITY</option>
                                            <option value="MANUFACTURE">MANUFACTURE</option>
                                            <option value="MINING">MINING</option>
                                            <option value="PERSONAL">PERSONAL</option>
                                            <option value="PROPERTY">PROPERTY</option>
                                            <option value="RECREATIONAL">RECREATIONAL</option>
                                            <option value="RETAIL_TRD">RETAIL_TRD</option>
                                            <option value="TRANSPORT">TRANSPORT</option>
                                            <option value="UNKNOWN">UNKNOWN</option>
                                            <option value="UTILITIES">UTILITIES</option>
                                            <option value="WHOLESALE_TRD">WHOLESALE_TRD</option>
                                        <?php else:?>
                                            <option value="Sole Trader">Sole Trader</option>
                                            <option value="Partnership">Partnership</option>
                                            <option value="Incorporation">Incorporation</option>
                                            <option value="Limited Company">Limited Company</option>
                                            <option value="Private">Private</option>
                                            <option value="Trust">Trust</option>
                                            <option value="NA">NA</option>
                                        <?php endif;?>
										</select></div>
                                </div>
                            </div>
                            <?php if ($plan['Plan']['retailer'] == 'Alinta Energy'):?>
                            <div class="form-group row display_hide trustee">
                                <div class="col-xs-12">
                                     <label class="v2_label">Trustee Type</label>
                                    <div class="v2_field"><select id="trustee_type" name="Business[trustee_type]" class="trusteetype selectpicker show-menu-arrow">
                                        <option value="Individual">Individual</option>
                                        <option value="Company">Company</option>
										</select></div>
                                </div>
                            </div>
                            <div class="form-group row display_hide trustee">
                                <div class="col-xs-12">
                                     <label class="v2_label">Trustee Company Name</label>
                                    <div class="v2_field"><input id="trustee_company_name" name="Business[trustee_company_name]" class="form-control" type="text" value=""/></div>
                                </div>
                            </div>
                            <div class="form-group row display_hide trustee">
                                <div class="col-xs-12">
                                     <label class="v2_label">Trustee ACN</label>
                                    <div class="v2_field"><input id="trustee_acn" name="Business[trustee_acn]" class="form-control" type="text" value="" maxlength="9"/></div>
                                </div>
                            </div>
                            <div class="form-group row display_hide trustee">
                                <div class="col-xs-12">
                                     <label class="v2_label">Trustee Company Type</label>
                                    <div class="v2_field"><input type="text" value="" name="Business[trustee_company_type]" id="trustee_company_type" class="form-control"></div>
                                </div>
                            </div>
                            <?php endif;?>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                     <label class="v2_label">Company Industry</label>
                                    <div class="v2_field"><select id="company_industry" name="Business[company_industry]" class="companyindustry selectpicker show-menu-arrow">
                                        <option value="Unknown">Unknown</option>
                                        <option value="Accommodation">Accommodation</option>
                                        <option value="Air and Space Transport">Air and Space Transport</option>
                                        <option value="Apple and Pear Growing">Apple and Pear Growing</option>
                                        <option value="Aquaculture">Aquaculture</option>
                                        <option value="Arts">Arts</option>
                                        <option value="Bakery Product Manufacturing">Bakery Product Manufacturing</option>
                                        <option value="Basic Chemical Manufacturing">Basic Chemical Manufacturing</option>
                                        <option value="Basic Non-Ferrous Metal Manufacturing">Basic Non-Ferrous Metal Manufacturing</option>
                                        <option value="Beef Cattle Farming">Beef Cattle Farming</option>
                                        <option value="Beverage and Malt Manufacturing">Beverage and Malt Manufacturing</option>
                                        <option value="Builders Supplies Wholesaling">Builders Supplies Wholesaling</option>
                                        <option value="Building Completion Services">Building Completion Services</option>
                                        <option value="Building Construction">Building Construction</option>
                                        <option value="Building Structure Services">Building Structure Services</option>
                                        <option value="Cafes and Restaurants">Cafes and Restaurants</option>
                                        <option value="Cement and Concrete Product Manufacturing">Cement and Concrete Product Manufacturing</option>
                                        <option value="Ceramic Product Manufacturing">Ceramic Product Manufacturing</option>
                                        <option value="Child Care Services">Child Care Services</option>
                                        <option value="Clothing and Soft Good Retailing">Clothing and Soft Good Retailing</option>
                                        <option value="Clothing Manufacturing">Clothing Manufacturing</option>
                                        <option value="Clubs (Hospitality)">Clubs (Hospitality)</option>
                                        <option value="Coal Mining">Coal Mining</option>
                                        <option value="Community Care Services">Community Care Services</option>
                                        <option value="Construction Material Mining">Construction Material Mining</option>
                                        <option value="Crop and Plant Growing n.e.c.">Crop and Plant Growing n.e.c.</option>
                                        <option value="Cut Flower and Flower Seed Growing">Cut Flower and Flower Seed Growing</option>
                                        <option value="Dairy Cattle Farming">Dairy Cattle Farming</option>
                                        <option value="Dairy Product Manufacturing">Dairy Product Manufacturing</option>
                                        <option value="Deer Farming">Deer Farming</option>
                                        <option value="Defence">Defence</option>
                                        <option value="Department Stores">Department Stores</option>
                                        <option value="Deposit Taking Financiers">Deposit Taking Financiers</option>
                                        <option value="Electrical Equipment and Appliance Manufacturing">Electrical Equipment and Appliance Manufacturing</option>
                                        <option value="Electricity Supply">Electricity Supply</option>
                                        <option value="Electronic Equipment Manufacturing">Electronic Equipment Manufacturing</option>
                                        <option value="Exploration">Exploration</option>
                                        <option value="Fabricated Metal Product Manufacturing">Fabricated Metal Product Manufacturing</option>
                                        <option value="Farm Produce Wholesaling">Farm Produce Wholesaling</option>
                                        <option value="Film and Video Services">Film and Video Services</option>
                                        <option value="Financial Asset Investors">Financial Asset Investors</option>
                                        <option value="Finfish Trawling">Finfish Trawling</option>
                                        <option value="Flour Mill and Cereal Food Manufacturing">Flour Mill and Cereal Food Manufacturing</option>
                                        <option value="Food, Drink and Tobacco Wholesaling">Food, Drink and Tobacco Wholesaling</option>
                                        <option value="Footwear Manufacturing">Footwear Manufacturing</option>
                                        <option value="Foreign Government Representation">Foreign Government Representation</option>
                                        <option value="Forestry">Forestry</option>
                                        <option value="Fruit and Vegetable Processing">Fruit and Vegetable Processing</option>
                                        <option value="Fruit Growing n.e.c.">Fruit Growing n.e.c.</option>
                                        <option value="Furniture Manufacturing">Furniture Manufacturing</option>
                                        <option value="Furniture, Houseware and Appliance Retail">Furniture, Houseware and Appliance Retail</option>
                                        <option value="Gambling Services">Gambling Services</option>
                                        <option value="Gas Supply">Gas Supply</option>
                                        <option value="Glass and Glass Product Manufacturing">Glass and Glass Product Manufacturing</option>
                                        <option value="Government Administration">Government Administration</option>
                                        <option value="Grape Growing">Grape Growing</option>
                                        <option value="Hospitals and Nursing Homes">Hospitals and Nursing Homes</option>
                                        <option value="Household Equipment Repair Services">Household Equipment Repair Services</option>
                                        <option value="Household Good Wholesaling">Household Good Wholesaling</option>
                                        <option value="Industrial Machinery and Equipment Manufacturing">Industrial Machinery and Equipment Manufacturing</option>
                                        <option value="Installation Trade Services">Installation Trade Services</option>
                                        <option value="Interest Groups">Interest Groups</option>
                                        <option value="Iron and Steel Manufacturing">Iron and Steel Manufacturing</option>
                                        <option value="Justice">Justice</option>
                                        <option value="Kiwi Fruit Growing">Kiwi Fruit Growing</option>
                                        <option value="Knitting Mills">Knitting Mills</option>
                                        <option value="Leather and Leather Product Manufacturing">Leather and Leather Product Manufacturing</option>
                                        <option value="Legal and Accounting Services">Legal and Accounting Services</option>
                                        <option value="Libraries">Libraries</option>
                                        <option value="Life Insurance and Superannuation Funds">Life Insurance and Superannuation Funds</option>
                                        <option value="Livestock Farming n.e.c.">Livestock Farming n.e.c.</option>
                                        <option value="Log Sawmilling and Timber Dressing">Log Sawmilling and Timber Dressing</option>
                                        <option value="Logging">Logging</option>
                                        <option value="Machinery and Equipment Hiring and Leasing">Machinery and Equipment Hiring and Leasing</option>
                                        <option value="Machinery and Equipment Wholesaling">Machinery and Equipment Wholesaling</option>
                                        <option value="Marine Fishing n.e.c.">Marine Fishing n.e.c.</option>
                                        <option value="Marketing and Business Management Service">Marketing and Business Management Service</option>
                                        <option value="Meat and Meat Product Manufacturing">Meat and Meat Product Manufacturing</option>
                                        <option value="Medical and Dental Services">Medical and Dental Services</option>
                                        <option value="Metal Ore Mining">Metal Ore Mining</option>
                                        <option value="Mineral, Metal and Chemical Wholesaling">Mineral, Metal and Chemical Wholesaling</option>
                                        <option value="Motor Vehicle and Part Manufacturing">Motor Vehicle and Part Manufacturing</option>
                                        <option value="Motor Vehicle Retailing">Motor Vehicle Retailing</option>
                                        <option value="Motor Vehicle Services">Motor Vehicle Services</option>
                                        <option value="Motor Vehicle Wholesaling">Motor Vehicle Wholesaling</option>
                                        <option value="Museums">Museums</option>
                                        <option value="Non-Building Construction">Non-Building Construction</option>
                                        <option value="Non-Ferrous Basic Metal Product Manufacturing">Non-Ferrous Basic Metal Product Manufacturing</option>
                                        <option value="Non-Financial Asset Investors">Non-Financial Asset Investors</option>
                                        <option value="Non-Metallic Mineral Product Manufacturing">Non-Metallic Mineral Product Manufacturing</option>
                                        <option value="Oil and Fat Manufacturing">Oil and Fat Manufacturing</option>
                                        <option value="Oil and Gas Extraction">Oil and Gas Extraction</option>
                                        <option value="Other Business Services">Other Business Services</option>
                                        <option value="Other Chemical Product Manufacturing">Other Chemical Product Manufacturing</option>
                                        <option value="Other Construction Services">Other Construction Services</option>
                                        <option value="Other Education">Other Education</option>
                                        <option value="Other Financiers">Other Financiers</option>
                                        <option value="Other Food Manufacturing">Other Food Manufacturing</option>
                                        <option value="Other Health Services">Other Health Services</option>
                                        <option value="Other Insurance">Other Insurance</option>
                                        <option value="Other Manufacturing">Other Manufacturing</option>
                                        <option value="Other Mining">Other Mining</option>
                                        <option value="Other Mining Services">Other Mining Services</option>
                                        <option value="Other Personal and Household Good Retailing">Other Personal and Household Good Retailing</option>
                                        <option value="Other Personal Services">Other Personal Services</option>
                                        <option value="Other Recreation Services">Other Recreation Services</option>
                                        <option value="Other Services to Transport">Other Services to Transport</option>
                                        <option value="Other Transport">Other Transport</option>
                                        <option value="Other Transport Equipment Manufacturing">Other Transport Equipment Manufacturing</option>
                                        <option value="Other Wholesaling">Other Wholesaling</option>
                                        <option value="Other Wood Product Manufacturing">Other Wood Product Manufacturing</option>
                                        <option value="Paper and Paper Product Manufacturing">Paper and Paper Product Manufacturing</option>
                                        <option value="Parks and Gardens">Parks and Gardens</option>
                                        <option value="Personal and Household Goods Hiring">Personal and Household Goods Hiring</option>
                                        <option value="Petroleum and Coal Product Manufacturing">Petroleum and Coal Product Manufacturing</option>
                                        <option value="Petroleum Refining">Petroleum Refining</option>
                                        <option value="Photo and Scientific Equipment Manufacturing">Photo and Scientific Equipment Manufacturing</option>
                                        <option value="Plant Nurseries">Plant Nurseries</option>
                                        <option value="Plastic Product Manufacturing">Plastic Product Manufacturing</option>
                                        <option value="Post School Education">Post School Education</option>
                                        <option value="Postal and Courier Services">Postal and Courier Services</option>
                                        <option value="Prefabricated Building Manufacturing">Prefabricated Building Manufacturing</option>
                                        <option value="Preschool Education">Preschool Education</option>
                                        <option value="Printing and Services to Printing">Printing and Services to Printing</option>
                                        <option value="Private Households Employing Staff">Private Households Employing Staff</option>
                                        <option value="Property Operators and Developers">Property Operators and Developers</option>
                                        <option value="Public Order and Safety Services">Public Order and Safety Services</option>
                                        <option value="Publishing">Publishing</option>
                                        <option value="Pubs, Taverns and Bars">Pubs, Taverns and Bars</option>
                                        <option value="Radio and Television Services">Radio and Television Services</option>
                                        <option value="Rail Transport">Rail Transport</option>
                                        <option value="Real Estate Agents">Real Estate Agents</option>
                                        <option value="Recorded Media Manufacturing and Publish">Recorded Media Manufacturing and Publish</option>
                                        <option value="Recreational Good Retailing">Recreational Good Retailing</option>
                                        <option value="Religious Organisations">Religious Organisations</option>
                                        <option value="Residential">Residential</option>
                                        <option value="Road Freight Transport">Road Freight Transport</option>
                                        <option value="Road Passenger Transport">Road Passenger Transport</option>
                                        <option value="Rubber Product Manufacturing">Rubber Product Manufacturing</option>
                                        <option value="School Education">School Education</option>
                                        <option value="Scientific Research">Scientific Research</option>
                                        <option value="Services to Air Transport">Services to Air Transport</option>
                                        <option value="Services to Finance and Investment">Services to Finance and Investment</option>
                                        <option value="Services to Forestry">Services to Forestry</option>
                                        <option value="Services to Insurance">Services to Insurance</option>
                                        <option value="Services to Road Transport">Services to Road Transport</option>
                                        <option value="Services to the Arts">Services to the Arts</option>
                                        <option value="Services to Water Transport">Services to Water Transport</option>
                                        <option value="Sheep Farming">Sheep Farming</option>
                                        <option value="Sheep-Beef Cattle Farming">Sheep-Beef Cattle Farming</option>
                                        <option value="Sheet Metal Product Manufacturing">Sheet Metal Product Manufacturing</option>
                                        <option value="Site Preparation Services">Site Preparation Services</option>
                                        <option value="Specialised Food Retailing">Specialised Food Retailing</option>
                                        <option value="Sport">Sport</option>
                                        <option value="Stone Fruit Growing">Stone Fruit Growing</option>
                                        <option value="Storage">Storage</option>
                                        <option value="Structural Metal Product Manufacturing">Structural Metal Product Manufacturing</option>
                                        <option value="Supermarket and Grocery Stores">Supermarket and Grocery Stores</option>
                                        <option value="Technical Services">Technical Services</option>
                                        <option value="Telecommunication Services">Telecommunication Services</option>
                                        <option value="Textile and Woven Fabric Manufacturing">Textile and Woven Fabric Manufacturing</option>
                                        <option value="Textile Product Manufacturing">Textile Product Manufacturing</option>
                                        <option value="Textile, Clothing and Footwear Wholesaling">Textile, Clothing and Footwear Wholesaling</option>
                                        <option value="Tobacco Product Manufacturing">Tobacco Product Manufacturing</option>
                                        <option value="Vegetable Growing">Vegetable Growing</option>
                                        <option value="Veterinary Services">Veterinary Services</option>
                                        <option value="Water Supply, Sewerage and Drainage Services">Water Supply, Sewerage and Drainage Services</option>
                                        <option value="Water Transport">Water Transport</option>
										</select></div>
                                </div>
                            </div>
                            <div class="submit-btnotr text-right"><input onclick="checkBusinessDetailsFields()" type="submit" class="submit-btn" value="Mark section as Completed"/>
                            </div>
                        </div>
                    </form>
                </div>
			
                <h3 class="form-hdg identification">Identification<i class="fa fa-angle-up" aria-hidden="true"></i><i class="fa fa-angle-down" aria-hidden="true"></i></h3>
            	<div class="form clearfix collapse" id="form-collapse4">
                    <form action="" method="post" class="signup_identification" name="signup_identification">
                        <div class="form-data">
                            <div class="identification_credit_check_fields display_hide">
                                <div class="form-group yes-no-btn row">
                                    <div class="col-xs-12" id="identification_credit_check_content">
                                         <label class="v2_label v2">
                                            <span class="creditcheckdefaultmsg">I will need to obtain some Identification from you, as <?php echo $plan['Plan']['retailer']; ?> will need to complete a credit check before they will take you on board as a customer. Do you give your consent for them to perform a credit check?</span>
                                            <span class="creditcheckmomentummsg">Do you understand and agree that Momentum may conduct a credit check on you, and that your application may not be accepted if you do not meet Momentum’s credit requirements?</span>
                                            <span class="creditchecklumomsg">Do you consent to Lumo performing a credit check with their external credit service?</span>
                                            <span class="creditcheckalintamsg">Please confirm that you are authorised to provide the personal details that you have presented today and that you consent to your information being checked with the document issuer or official record holder via third party systems for the purpose of confirming your identity?</span>
                                        	<span class="creditcheckaglmsg"><?php echo $plan['Plan']['retailer']; ?> will conduct a credit check and consider your history with them. <?php echo $plan['Plan']['retailer']; ?> will use your details safely in accordance with their privacy and credit reporting policy which is available at
                                        	<?php if ($plan['Plan']['retailer'] == 'AGL'):?>
                                            	www.agl.com.au/privacy-policy.
                                            <?php endif;?>

                                            <?php if ($plan['Plan']['retailer'] == 'Powerdirect'):?>
                                            	www.powerdirect.com.au/privacy-policy.
                                            <?php endif;?>
                                             Do you consent to a credit check?</span>
                                        </label>
                                        <input type="hidden" value="-1" name="Identification[credit_check]" class="IdentificationCreditCheck">
                                        <button onclick="creditcheck(0)" type="button" class="butn credit_check checkbutton">No</button>
                                        <button onclick="creditcheck(1)" type="button" class="butn credit_check checkbutton">Yes</button>
                                    </div>
                                </div>
                                <div class="form-group display_hide creditcheckno">
                                    <div class="read-msg bgred"><div class="inner">
                                        <h5>READ VERBATIM TO CUSTOMER </h5>
                                        <p>I apologise, we will not be able to proceed with this change to <?php echo $plan['Plan']['retailer']; ?>.</p>
                                    </div></div>
                                </div>
                            </div>
                            <div class="identification_default_fields display_hide">
                                <div class="form-group row">
                                    <div class="col-xs-4">
                                         <label class="v2_label">Document Type*</label>
                                        <div class="v2_field"><select id="document_type" class="selectIdentificationDocumentType selectpicker show-menu-arrow" name="Identification[document_type]">
                                            <option value=''>Select</option>
                                            <option value='DRV'>Driver's License</option>
                                            <?php if (!in_array($plan['Plan']['retailer'], array('Next Business Energy', 'Blue NRG'))):?>
                                            <option value='MED'>Medicare Card</option>
                                            <?php endif;?>
                                            <option value='PP'>Passport</option>
											</select></div>
                                    </div>
                                    <div class="col-xs-4">
                                         <label class="v2_label">Document ID Number*</label>
                                        <div class="v2_field"><input id="document_id" type="text" value='' name="Identification[document_id]" class="form-control"/></div>
                                    </div>
                                    <div class="col-xs-4 document_driver_license_card_number_field display_hide">
                                        <label class="v2_label">Driver License Card Number</label>
                                        <div class="v2_field"><input id="document_driver_license_card_number" type="text" value='' name="Identification[driver_license_card_number]" class="form-control"/></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-xs-4 expiry-date">
                                         <label class="v2_label">Document Expiry*</label>
                                        <div class="v2_field"><input type="text" name="Identification[document_expiry]" id="identification_document_expiry" class="calender-icon form-control"/></div>
                                    </div>
                                    <div class="col-xs-4 display_hide document_state_field">
                                         <label class="v2_label">Document State</label>
                                        <div class="v2_field"><select id="document_state" class="selectIdentificationDS selectpicker show-menu-arrow" name="Identification[document_state]">
                                            <option value=''>Select</option>
                                            <option value="QLD">QLD</option>
                                            <option value="VIC">VIC</option>
                                            <option value="NSW">NSW</option>
                                            <option value="SA">SA</option>
                                            <option value="WA">WA</option>
                                            <option value="NT">NT</option>
                                            <option value="ACT">ACT</option>
                                            <option value="NZ">NZ</option>
                                            <option value="TAS">TAS</option>
											</select></div>
                                    </div>
                                    <div class="col-xs-4 display_hide document_country_field">
                                         <label class="v2_label">Document Country</label>
                                        <div class="v2_field"><select id="document_country" class="selectIdentificationDC selectpicker show-menu-arrow" name="Identification[document_country]">
                                            <option value="">Select</option>
                                            <option value="Taiwan">Taiwan</option>
                                            <option value="Afghanistan">Afghanistan</option>
                                            <option value="Albania">Albania</option>
                                            <option value="Algeria">Algeria</option>
                                            <option value="American Samoa">American Samoa</option>
                                            <option value="Andorra">Andorra</option>
                                            <option value="Angola">Angola</option>
                                            <option value="Anguilla">Anguilla</option>
                                            <option value="Antarctica">Antarctica</option>
                                            <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                                            <option value="Argentina">Argentina</option>
                                            <option value="Armenia">Armenia</option>
                                            <option value="Aruba">Aruba</option>
                                            <option value="Australia">Australia</option>
                                            <option value="Austria">Austria</option>
                                            <option value="Azerbaijan">Azerbaijan</option>
                                            <option value="Bahamas">Bahamas</option>
                                            <option value="Bahrain">Bahrain</option>
                                            <option value="Bangladesh">Bangladesh</option>
                                            <option value="Barbados">Barbados</option>
                                            <option value="Belarus">Belarus</option>
                                            <option value="Belgium">Belgium</option>
                                            <option value="Belize">Belize</option>
                                            <option value="Benin">Benin</option>
                                            <option value="Bermuda">Bermuda</option>
                                            <option value="Bhutan">Bhutan</option>
                                            <option value="Bolivia (Plurinational State of)">Bolivia (Plurinational State of)</option>
                                            <option value="Bonaire, Sint Eustatius and Saba">Bonaire, Sint Eustatius and Saba</option>
                                            <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                            <option value="Botswana">Botswana</option>
                                            <option value="Bouvet Island">Bouvet Island</option>
                                            <option value="Brazil">Brazil</option>
                                            <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                                            <option value="British Virgin Islands">British Virgin Islands</option>
                                            <option value="Brunei Darussalam">Brunei Darussalam</option>
                                            <option value="Bulgaria">Bulgaria</option>
                                            <option value="Burkina Faso">Burkina Faso</option>
                                            <option value="Burundi">Burundi</option>
                                            <option value="Cabo Verde">Cabo Verde</option>
                                            <option value="Cambodia">Cambodia</option>
                                            <option value="Cameroon">Cameroon</option>
                                            <option value="Canada">Canada</option>
                                            <option value="Cayman Islands">Cayman Islands</option>
                                            <option value="Central African Republic">Central African Republic</option>
                                            <option value="Chad">Chad</option>
                                            <option value="Chile">Chile</option>
                                            <option value="China">China</option>
                                            <option value="China, Hong Kong Special Administrative Region">China, Hong Kong Special Administrative Region</option>
                                            <option value="China, Macao Special Administrative Region">China, Macao Special Administrative Region</option>
                                            <option value="Christmas Island">Christmas Island</option>
                                            <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                                            <option value="Colombia">Colombia</option>
                                            <option value="Comoros">Comoros</option>
                                            <option value="Congo">Congo</option>
                                            <option value="Cook Islands">Cook Islands</option>
                                            <option value="Costa Rica">Costa Rica</option>
                                            <option value="Croatia">Croatia</option>
                                            <option value="Cuba">Cuba</option>
                                            <option value="Curaçao">Curaçao</option>
                                            <option value="Cyprus">Cyprus</option>
                                            <option value="Czechia">Czechia</option>
                                            <option value="Côte d'Ivoire">Côte d'Ivoire</option>
                                            <option value="Democratic People's Republic of Korea">Democratic People's Republic of Korea</option>
                                            <option value="Democratic Republic of the Congo">Democratic Republic of the Congo</option>
                                            <option value="Denmark">Denmark</option>
                                            <option value="Djibouti">Djibouti</option>
                                            <option value="Dominica">Dominica</option>
                                            <option value="Dominican Republic">Dominican Republic</option>
                                            <option value="Ecuador">Ecuador</option>
                                            <option value="Egypt">Egypt</option>
                                            <option value="El Salvador">El Salvador</option>
                                            <option value="Equatorial Guinea">Equatorial Guinea</option>
                                            <option value="Eritrea">Eritrea</option>
                                            <option value="Estonia">Estonia</option>
                                            <option value="Eswatini">Eswatini</option>
                                            <option value="Ethiopia">Ethiopia</option>
                                            <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                                            <option value="Faroe Islands">Faroe Islands</option>
                                            <option value="Fiji">Fiji</option>
                                            <option value="Finland">Finland</option>
                                            <option value="France">France</option>
                                            <option value="French Guiana">French Guiana</option>
                                            <option value="French Polynesia">French Polynesia</option>
                                            <option value="French Southern Territories">French Southern Territories</option>
                                            <option value="Gabon">Gabon</option>
                                            <option value="Gambia">Gambia</option>
                                            <option value="Georgia">Georgia</option>
                                            <option value="Germany">Germany</option>
                                            <option value="Ghana">Ghana</option>
                                            <option value="Gibraltar">Gibraltar</option>
                                            <option value="Greece">Greece</option>
                                            <option value="Greenland">Greenland</option>
                                            <option value="Grenada">Grenada</option>
                                            <option value="Guadeloupe">Guadeloupe</option>
                                            <option value="Guam">Guam</option>
                                            <option value="Guatemala">Guatemala</option>
                                            <option value="Guernsey">Guernsey</option>
                                            <option value="Guinea">Guinea</option>
                                            <option value="Guinea-Bissau">Guinea-Bissau</option>
                                            <option value="Guyana">Guyana</option>
                                            <option value="Haiti">Haiti</option>
                                            <option value="Heard Island and McDonald Islands">Heard Island and McDonald Islands</option>
                                            <option value="Holy See">Holy See</option>
                                            <option value="Honduras">Honduras</option>
                                            <option value="Hungary">Hungary</option>
                                            <option value="Iceland">Iceland</option>
                                            <option value="India">India</option>
                                            <option value="Indonesia">Indonesia</option>
                                            <option value="Iran (Islamic Republic of)">Iran (Islamic Republic of)</option>
                                            <option value="Iraq">Iraq</option>
                                            <option value="Ireland">Ireland</option>
                                            <option value="Isle of Man">Isle of Man</option>
                                            <option value="Israel">Israel</option>
                                            <option value="Italy">Italy</option>
                                            <option value="Jamaica">Jamaica</option>
                                            <option value="Japan">Japan</option>
                                            <option value="Jersey">Jersey</option>
                                            <option value="Jordan">Jordan</option>
                                            <option value="Kazakhstan">Kazakhstan</option>
                                            <option value="Kenya">Kenya</option>
                                            <option value="Kiribati">Kiribati</option>
                                            <option value="Kuwait">Kuwait</option>
                                            <option value="Kyrgyzstan">Kyrgyzstan</option>
                                            <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
                                            <option value="Latvia">Latvia</option>
                                            <option value="Lebanon">Lebanon</option>
                                            <option value="Lesotho">Lesotho</option>
                                            <option value="Liberia">Liberia</option>
                                            <option value="Libya">Libya</option>
                                            <option value="Liechtenstein">Liechtenstein</option>
                                            <option value="Lithuania">Lithuania</option>
                                            <option value="Luxembourg">Luxembourg</option>
                                            <option value="Madagascar">Madagascar</option>
                                            <option value="Malawi">Malawi</option>
                                            <option value="Malaysia">Malaysia</option>
                                            <option value="Maldives">Maldives</option>
                                            <option value="Mali">Mali</option>
                                            <option value="Malta">Malta</option>
                                            <option value="Marshall Islands">Marshall Islands</option>
                                            <option value="Martinique">Martinique</option>
                                            <option value="Mauritania">Mauritania</option>
                                            <option value="Mauritius">Mauritius</option>
                                            <option value="Mayotte">Mayotte</option>
                                            <option value="Mexico">Mexico</option>
                                            <option value="Micronesia (Federated States of)">Micronesia (Federated States of)</option>
                                            <option value="Monaco">Monaco</option>
                                            <option value="Mongolia">Mongolia</option>
                                            <option value="Montenegro">Montenegro</option>
                                            <option value="Montserrat">Montserrat</option>
                                            <option value="Morocco">Morocco</option>
                                            <option value="Mozambique">Mozambique</option>
                                            <option value="Myanmar">Myanmar</option>
                                            <option value="Namibia">Namibia</option>
                                            <option value="Nauru">Nauru</option>
                                            <option value="Nepal">Nepal</option>
                                            <option value="Netherlands">Netherlands</option>
                                            <option value="New Caledonia">New Caledonia</option>
                                            <option value="New Zealand">New Zealand</option>
                                            <option value="Nicaragua">Nicaragua</option>
                                            <option value="Niger">Niger</option>
                                            <option value="Nigeria">Nigeria</option>
                                            <option value="Niue">Niue</option>
                                            <option value="Norfolk Island">Norfolk Island</option>
                                            <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                                            <option value="Norway">Norway</option>
                                            <option value="Oman">Oman</option>
                                            <option value="Pakistan">Pakistan</option>
                                            <option value="Palau">Palau</option>
                                            <option value="Panama">Panama</option>
                                            <option value="Papua New Guinea">Papua New Guinea</option>
                                            <option value="Paraguay">Paraguay</option>
                                            <option value="Peru">Peru</option>
                                            <option value="Philippines">Philippines</option>
                                            <option value="Pitcairn">Pitcairn</option>
                                            <option value="Poland">Poland</option>
                                            <option value="Portugal">Portugal</option>
                                            <option value="Puerto Rico">Puerto Rico</option>
                                            <option value="Qatar">Qatar</option>
                                            <option value="Republic of Korea">Republic of Korea</option>
                                            <option value="Republic of Moldova">Republic of Moldova</option>
                                            <option value="Romania">Romania</option>
                                            <option value="Russian Federation">Russian Federation</option>
                                            <option value="Rwanda">Rwanda</option>
                                            <option value="Réunion">Réunion</option>
                                            <option value="Saint Barthélemy">Saint Barthélemy</option>
                                            <option value="Saint Helena">Saint Helena</option>
                                            <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                            <option value="Saint Lucia">Saint Lucia</option>
                                            <option value="Saint Martin (French Part)">Saint Martin (French Part)</option>
                                            <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                                            <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
                                            <option value="Samoa">Samoa</option>
                                            <option value="San Marino">San Marino</option>
                                            <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                                            <option value="Saudi Arabia">Saudi Arabia</option>
                                            <option value="Senegal">Senegal</option>
                                            <option value="Serbia">Serbia</option>
                                            <option value="Seychelles">Seychelles</option>
                                            <option value="Sierra Leone">Sierra Leone</option>
                                            <option value="Singapore">Singapore</option>
                                            <option value="Sint Maarten (Dutch part)">Sint Maarten (Dutch part)</option>
                                            <option value="Slovakia">Slovakia</option>
                                            <option value="Slovenia">Slovenia</option>
                                            <option value="Solomon Islands">Solomon Islands</option>
                                            <option value="Somalia">Somalia</option>
                                            <option value="South Africa">South Africa</option>
                                            <option value="South Georgia and the South Sandwich Islands">South Georgia and the South Sandwich Islands</option>
                                            <option value="South Sudan">South Sudan</option>
                                            <option value="Spain">Spain</option>
                                            <option value="Sri Lanka">Sri Lanka</option>
                                            <option value="State of Palestine">State of Palestine</option>
                                            <option value="Sudan">Sudan</option>
                                            <option value="Suriname">Suriname</option>
                                            <option value="Svalbard and Jan Mayen Islands">Svalbard and Jan Mayen Islands</option>
                                            <option value="Sweden">Sweden</option>
                                            <option value="Switzerland">Switzerland</option>
                                            <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                                            <option value="Tajikistan">Tajikistan</option>
                                            <option value="Thailand">Thailand</option>
                                            <option value="The former Yugoslav Republic of Macedonia">The former Yugoslav Republic of Macedonia</option>
                                            <option value="Timor-Leste">Timor-Leste</option>
                                            <option value="Togo">Togo</option>
                                            <option value="Tokelau">Tokelau</option>
                                            <option value="Tonga">Tonga</option>
                                            <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                                            <option value="Tunisia">Tunisia</option>
                                            <option value="Turkey">Turkey</option>
                                            <option value="Turkmenistan">Turkmenistan</option>
                                            <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                                            <option value="Tuvalu">Tuvalu</option>
                                            <option value="Uganda">Uganda</option>
                                            <option value="Ukraine">Ukraine</option>
                                            <option value="United Arab Emirates">United Arab Emirates</option>
                                            <option value="United Kingdom of Great Britain and Northern Ireland">United Kingdom of Great Britain and Northern Ireland</option>
                                            <option value="United Republic of Tanzania">United Republic of Tanzania</option>
                                            <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                                            <option value="United States Virgin Islands">United States Virgin Islands</option>
                                            <option value="United States of America">United States of America</option>
                                            <option value="Uruguay">Uruguay</option>
                                            <option value="Uzbekistan">Uzbekistan</option>
                                            <option value="Vanuatu">Vanuatu</option>
                                            <option value="Venezuela (Bolivarian Republic of)">Venezuela (Bolivarian Republic of)</option>
                                            <option value="Viet Nam">Viet Nam</option>
                                            <option value="Wallis and Futuna Islands">Wallis and Futuna Islands</option>
                                            <option value="Western Sahara">Western Sahara</option>
                                            <option value="Yemen">Yemen</option>
                                            <option value="Zambia">Zambia</option>
                                            <option value="Zimbabwe">Zimbabwe</option>
                                            <option value="Åland Islands">Åland Islands</option>
											</select></div>
                                    </div>
								</div>
								<div class="form-group row display_hide document_medicare_colour_field">
                                    <div class="col-xs-4 display_hide document_medicare_colour_field">
                                         <label class="v2_label">Medicare Colour</label>
                                        <div class="v2_field"><select id="document_medicare_colour" class="selectIdentificationDMC selectpicker show-menu-arrow" name="Identification[document_medicare_colour]">
                                            <option>Select</option>
                                            <option value="Green">Green</option>
                                            <option value="Blue">Blue</option>
                                            <option value="Yellow">Yellow</option>
											</select></div>
                                    </div>
                                </div>
                            </div>
                            <div class="submit-btnotr text-right"><input type="submit" onclick="checkIdentificationFields()" class="submit-btn checkIdentificationFields" value="Mark section as Completed"/></div>
                        </div>
                    </form>
                </div>
			
                <h3 class="form-hdg concession">Concession<i class="fa fa-angle-up" aria-hidden="true"></i><i class="fa fa-angle-down" aria-hidden="true"></i></h3>
            	<div class="form clearfix collapse" id="form-collapse5">
                    <form action="" method="post" class="signup_concession" name="signup_concession">
                        <div class="form-data">
                            <div class="form-group yes-no-btn row validconcessionhouseholdrequire">
                                <div class="col-xs-12">
                                    <?php if ($plan['Plan']['retailer'] == 'Origin Energy'):?>
                                         <label class="v2_label v2">Does anyone residing at the premises, or intending to reside at your premises, require life support equipment for the fuel(s) that you are taking up today with Origin?</label>
                                    <?php else:?>
                                         <label class="v2_label v2">Does anyone intending to reside at the property require Life Support <strong>or medical equipment</strong> that needs Electricity or Gas?</label>
                                    <?php endif;?>
                                    <input type="hidden" value="-1" name="Concession[household_require]" class="ConcessionHouseholdRequire">
                                    <button onclick="HouseholdRequire(0)" type="button" class="butn checkbutton">No</button>
                                    <button onclick="HouseholdRequire(1)" type="button" class="butn checkbutton">Yes</button>
                                </div>
                            </div>
                            <div class="form-group yes-no-btn display_hide originhouseholdrequireyes">
                                <div class="read-msg bgred"><div class="inner">
                                    <h5>READ VERBATIM TO CUSTOMER</h5>
                                    <p>If you have taken up one fuel today with Origin and require life support for the other fuel, you must inform your other retailer that a person residing, or intending to reside at your premises, requires life support equipment.</p>
                                </div></div>
                            </div>
                            <div class="form-group yes-no-btn display_hide momentumhouseholdrequireyes">
                                <div class="read-msg bgred"><div class="inner">
                                    <h5>READ VERBATIM TO CUSTOMER</h5>
                                    <p>Unfortunately we are unable to continue with the signup process, you will need to contact Momentum Energy directly on 1300 662 778 to take up this offer.</p>
                                </div></div>
                            </div>
                            <div class="form-group yes-no-btn display_hide simplyenergyhouseholdrequireyes">
                                <div class="read-msg bgred"><div class="inner">
                                    <h5>READ VERBATIM TO CUSTOMER</h5>
                                    <p>Unfortunately we are unable to continue with the signup process, you will need to contact Simply Energy directly on 13 88 08 to take up this offer.</p>
                                </div></div>
                            </div>
                            <div class="form-group display_hide alintahouseholdrequireyes">
                                <div class="read-msg">
                                    <h5>READ VERBATIM TO CUSTOMER</h5>
                                    <p>Due to the requirement of Life Support or medical equipment at your property, we will need to refer you to our dedicated Alinta Energy Life Support team who will be able to process this sign up for you. The contact number is 1300 721 092, and the team will be available Monday to Friday from 8am to 9pm and Saturdays from 9am to 6pm (AEST).</p>
                                    <p><strong>Working Instructions</strong></p>
                                    <p><strong>If the agent can’t warm transfer the call to the Life Support team, then they would process with this process below.</strong></p>
                                    <ul>
                                        <li>ALL fields in the template below must be completed.</li>
                                        <li>Once completed please email it to lifeSupport@alintaenergy.com.au copy in salessupport@alintaenergy.com.au and your Alinta Energy Channel Manager.</li>
                                        <li>Please note your Sales Channel will still be paid for the sale if the sale is successfully transferred to our dedicated Alinta Energy Life Support team and the sale has successfully been processed.</li>
                                        <li>Please ensure you email salessupport@alintaenergy.com.au and copy in your Alinta Energy Sales Channel Manager with all the customer details and in the subject line enter “Life Support Customer” so we can assign the sale to your Sales Channel.</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="form-group display_hide EAhouseholdrequireyes">
                                <div class="read-msg bgred"><div class="inner">
                                    <h5>READ VERBATIM TO CUSTOMER</h5>
                                    <p>As you require life support we will not proceed with the sale. Please call Energy Australia directly on 133 466.</p>
                                </div></div>
                            </div>
                            <div class="display_hide householdrequireyes">
                                <div class="form-group row display_hide lifesupportuserfullname">
                                    <div class="col-xs-12">
                                         <label class="v2_label">I need to ask you a few questions regarding the life support requirements at the property. What is the full name of the life support user?</label>
                                    </div>
                                </div>
                                <div class="form-group row display_hide lifesupportuserfullname">
                                    <div class="col-xs-6">
                                         <label class="v2_label">Life Support Title</label>
                                        <div class="v2_field"><select class="selectConcessionLifeSupportUserTitle selectpicker show-menu-arrow" name="Concession[life_support_user_title]" id="concession_life_support_user_title">
                                            <option value="">Select</option>
                                            <option value="Mr">Mr</option>
                                            <option value="Mrs">Mrs</option>
                                            <option value="Ms">Ms</option>
                                            <option value="Miss">Miss</option>
                                            <option value="Lady">Lady</option>
                                            <option value="Rev">Rev</option>
                                            <option value="Dr">Dr</option>
                                        </select></div>
                                    </div>
                                    <div class="col-xs-6">
                                         <label class="v2_label">Life Support First Name</label>
                                        <div class="v2_field"><input type="text" value="" name="Concession[life_support_user_first_name]" id="concession_life_support_user_first_name" class="form-control"/></div>
                                    </div>
                                </div>
                                <div class="form-group row display_hide lifesupportuserfullname">
                                    <div class="col-xs-6">
                                         <label class="v2_label">Life Support Middle Name</label>
                                        <div class="v2_field"><input type="text" value="" name="Concession[life_support_user_middle_name]" id="concession_life_support_user_middle_name" class="form-control"/></div>
                                    </div>
                                    <div class="col-xs-6">
                                         <label class="v2_label">Life Support Last Name</label>
                                        <div class="v2_field"><input type="text" value="" name="Concession[life_support_user_last_name]" id="concession_life_support_user_last_name" class="form-control"/></div>
                                    </div>
                                </div>
                                <div class="form-group display_hide sendlifesupportapplicationform">
                                    <div class="read-msg">
                                        <h5>PLEASE INFORM CUSTOMER</h5>
                                        <p>Please be aware that Lumo Energy will be sending you a Life Support Application form to be filled out by you and your doctor. This form must be returned to Lumo Energy as soon as possible, you can refer to your registration pack for further details.</p>
                                        <p>In the case of an emergency, there is a Faults & Emergency line, which you can call at any time. This number will be available in your Life Support registration pack and on the front page of your energy bill.</p>
                                        <p>Any Life Support Concessions will be applied once the completed form is returned to Lumo Energy. Even if you are not eligible for a concession, you are still required to complete a Life Support form to register your property with the distributor as Life Support.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row display_hide machinetype">
                                <div class="col-xs-12">
                                     <label class="v2_label">What is the Life Support Machine Type?</label>
                                    <div class="v2_field"><select id="concession_machine_type" class="selectConcessionMachineType selectpicker show-menu-arrow" name="Concession[machine_type]">
                                        <option value="">Select</option>
                                        <option value='PAP / CPAP short for Positive Airway Pressure (Including 24 hour usage)'>PAP / CPAP short for Positive Airway Pressure (Including 24 hour usage)</option>
                                        <option value="Ventilator For Life support (including Polio) (formerly known as 'respirator' or 'iron lung')">Ventilator For Life support (including Polio) (formerly known as 'respirator' or 'iron lung')</option>
                                        <option value='Oxygen concentrators (Including 24 hour usage)'>Oxygen concentrators (Including 24 hour usage)</option>
                                        <option value='Medical heating and cooling'>Medical heating and cooling</option>
                                        <option value='Nebuliser (Including Ventolin)'>Nebuliser (Including Ventolin)</option>
                                        <option value='Home haemodialysis'>Home haemodialysis</option>
                                        <option value='Peritoneal dialysis'>Peritoneal dialysis</option>
                                        <option value='Kidney dialysis machine'>Kidney dialysis machine</option>
                                        <option value='Left ventricular assist device'>Left ventricular assist device</option>
                                        <option value='Phototherapy equipment (Crigler Najjar)'>Phototherapy equipment (Crigler Najjar)</option>
                                        <option value='Total Parental Nutrition (TPN)'>Total Parental Nutrition (TPN)</option>
                                        <option value='Enteral feeding pump'>Enteral feeding pump</option>
                                        <option value='Long stay life support'>Long stay life support</option>
                                        <option value='Other Apparatus Not listed'>Other Apparatus Not listed</option>
										</select></div>
                                </div>
                            </div>
                            <div class="form-group row display_hide machinetype2">
                                <div class="col-xs-12">
                                     <label class="v2_label">What is the Life Support Machine Type?</label>
                                    <div class="v2_field"><select id="concession_machine_type2" class="selectConcessionMachineType selectpicker show-menu-arrow" name="Concession[machine_type2]">
                                        <option value="">Select</option>
                                        <option value='A. An oxygen concentrator'>A. An oxygen concentrator</option>
                                        <option value="B. An intermittent peritoneal dialysis machine">B. An intermittent peritoneal dialysis machine</option>
                                        <option value='C. A kidney dialysis machine'>C. A kidney dialysis machine</option>
                                        <option value='D. A chronic positive airways pressure respirator'>D. A chronic positive airways pressure respirator</option>
                                        <option value='E. Cigler najjar syndrome phototherapy equipment'>E. Cigler najjar syndrome phototherapy equipment</option>
                                        <option value='F. A ventilator for life support'>F. A ventilator for life support</option>
                                        <option value='G. Other'>G. Other</option>
										</select></div>
                                </div>
                            </div>
                            <div class="form-group row machinetypeother">
                                <div class="col-xs-12">
                                     <label class="v2_label">Other - Please type machine type as customer knows it</label>
                                    <div class="v2_field"><input type="text" name="Concession[machine_type_other]" value="" class="form-control"/></div>
                                </div>
                            </div>
                            <div class="form-group yes-no-btn row display_hide machinerunby">
                                <div class="col-xs-12">
                                     <label class="v2_label v2">Does the machine run by electricity or gas?</label>
                                    <input type="hidden" value="-1" name="Concession[machine_run_by]" class="ConcessionMachineRunBy">
                                    <button onclick="machine_run_by(1)" type="button" class="butn checkbutton concession_machine_run_by">Electricity</button>
                                    <button onclick="machine_run_by(2)" type="button" class="butn checkbutton concession_machine_run_by">Gas</button>
                                    <button onclick="machine_run_by(3)" type="button" class="butn checkbutton concession_machine_run_by">Dual</button>
                                </div>
                            </div>
                            <div class="form-group display_hide aglpowerdirectlifesupport">
                                <div class="read-msg">
                                    <h5>READ VERBATIM TO CUSTOMER</h5>
                                    <p>If your life support equipment requires both gas and electricity to operate, please inform your <span class="aglpowerdirectlifesupporttext">[FUEL_TYPE]</span> retailer that you or someone at your property relies on life support equipment.</p>
                                </div>
                            </div>
                            <div class="form-group yes-no-btn row validconcessioncard">
                                <div class="col-xs-12">
                                     <label class="v2_label v2">Do you hold a valid concession card?</label>
                                    <input type="hidden" value="-1" name="Concession[valid_concession_card]" class="ValidConcessionCard">
                                    <button onclick="concessionCard(0)" type="button" class="butn buttonno">No</button>
                                    <button onclick="concessionCard(1)" type="button" class="butn buttonyes">Yes</button>
                                </div>
                            </div>
                            <div class="concession-all mt0 display_hide">
                                <div class="form-group ACT display_hide">
                                    <?php if (in_array($plan['Plan']['retailer'], array('AGL', 'Powerdirect', 'Powerdirect and AGL'))):?>
                                    <div class="read-msg">
                                        <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                        <h5>READ VERBATIM TO CUSTOMER </h5>
                                        <p><?php echo $plan['Plan']['retailer'];?>, will collect and use your name, postcode and concession card information to electronically confirm that you are eligible for concession. This authority is only effective for the period that you are a customer of <?php echo $plan['Plan']['retailer'];?>. You can also withdraw your consent at any time by contacting them. If you do not provide your consent you may not be eligible for the concession.</p>
                                    </div>
                                    <?php endif;?>
                                    <?php if (in_array($plan['Plan']['retailer'], array('AGL', 'Powerdirect', 'Powerdirect and AGL', 'Alinta Energy'))):?>
                                    <div class="read-msg">
                                        <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                        <h5>READ VERBATIM TO CUSTOMER </h5>
                                        <div class="form-group yes-no-btn row display_hide actretailer">
                                            <div class="col-xs-12 ">
                                                <?php if (in_array($plan['Plan']['retailer'], array('AGL', 'Powerdirect', 'Powerdirect and AGL'))):?>
                                                 <label class="v2_label v2">Do you understand and authorise <?php echo $plan['Plan']['retailer'];?> to provide and access this information?</label>
                                                <?php endif;?>
                                                <input type="hidden" name="Concession[act_retailer]" class="ConcessionActRetailer" value="-1">
                                                <button type="button" onclick="act_retailer(0,this)" class="butn concession_act_retailer act_retailerbuttonno">No</button>
                                                <button type="button" onclick="act_retailer(1,this)" class="butn concession_act_retailer act_retailerbuttonyes">Yes</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="read-msg display_hide actretaileryesagl">
                                        <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                        <h5>READ VERBATIM TO CUSTOMER </h5>
                                        <p>Just to let you know, if your circumstances change you’ll need to let <?php echo $plan['Plan']['retailer'];?> know.</p>
                                    </div>
                                    <?php endif;?>
                                    <?php if ($plan['Plan']['retailer'] == 'Alinta Energy' && $plan['Plan']['state'] == 'Australian Capital Territory'):?>
                                    <div class="form-group yes-no-btn row">
                                        <div class="col-xs-12">
                                            <input type="hidden" name="Concession[medical_heating_cooling]" value="-1">
                                             <label class="v2_label v2">Do you have medical heating or cooling?</label>
                                            <button class="butn concession_medical_heating_cooling checkbutton">No</button>
                                            <button class="butn concession_medical_heating_cooling checkbutton">Yes</button>
                                        </div>
                                    </div>
                                    <?php endif;?>
                                    <?php if ($plan['Plan']['retailer'] == 'Simply Energy' && $plan['Plan']['state'] == 'Australian Capital Territory' && $plan['Plan']['res_sme'] == 'RES'):?>
                                    <div class="form-group yes-no-btn row">
                                        <div class="col-xs-12">
                                            <input type="hidden" name="Concession[confirm_concession]" value="-1">
                                             <label class="v2_label v2">Is this your principal place of residence and the only residence for which the rebate is claimed?<br><br>
                                            <i>(If "Yes" continue with script, If "No" do not continue with adding concession)</i><br><br>
                                            -Just to let you know, Simply Energy will confirm your name, postcode, payment,  concession details and eligibility with the Department of Human Services, Department of Veterans’ Affairs, and other government authorities, as required.
                                            <br>Upon confirming your eligibility, any energy concessions that you are entitled to receive will be applied to your account/s.<br>
                                            It is important that you let Simply Energy and your card issuer know of any changes in your circumstances which may affect your eligibility for a concession.<br>
                                            This consent is valid while you are a customer of Simply Energy. At any point you can withdraw your consent, but please be aware that you may no longer be entitled to receive an energy concession.<br>
                                            If you don’t want your information shared in this way, or withdraw consent, you must get proof of your details directly from the department<br>
                                            Do you consent to Simply Energy performing this check?</label>
                                            <button onclick="show_concession_fields(0)" class="butn concession_confirm_concession checkbutton">No</button>
                                            <button onclick="show_concession_fields(1)" class="butn concession_confirm_concession checkbutton">Yes</button>
                                        </div>
                                    </div>
                                    <?php endif;?>
                                    <div class="read-msg bgylw display_hide act_retailerno"><div class="inner">
                                        <h5>PLEASE INFORM CUSTOMER</h5>
                                        <p>Concession can't be applied at this time; you can contact the retailer at a later date to have your concession applied.</p>
                                    </div></div>
                                    <div class="read-msg bgylw display_hide actretailernoagl"><div class="inner">
                                        <h5>PLEASE INFORM CUSTOMER</h5>
                                        <p>Concession cannot be applied!</p>
                                    </div></div>
                                </div>
                                <div class="form-group VIC display_hide">
                                    <?php if (in_array($plan['Plan']['retailer'], array('AGL', 'Powerdirect', 'Powerdirect and AGL'))):?>
                                    <div class="read-msg">
                                        <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                        <h5>READ VERBATIM TO CUSTOMER </h5>
                                        <p><?php echo $plan['Plan']['retailer'];?>, will collect and use your name, postcode and concession card information to electronically confirm that you are eligible for concession. This authority is only effective for the period that you are a customer of <?php echo $plan['Plan']['retailer'];?>. You can also withdraw your consent at any time by contacting them. If you do not provide your consent you may not be eligible for the concession.</p>
                                    </div>

                                    <?php endif;?>
                                    <?php if (in_array($plan['Plan']['retailer'], array('AGL', 'Powerdirect', 'Powerdirect and AGL', 'Energy Australia'))):?>
                                    <div class="read-msg">
                                        <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                        <h5>READ VERBATIM TO CUSTOMER </h5>
                                        <p class="vicaglmsg display_hide">Do you understand and authorise <?php echo $plan['Plan']['retailer'];?> to provide and access this information?</p>
                                        <p class="viceamsg display_hide">If you have an eligible form of concession, please contact EnergyAustralia directly on 136 102 once you have received your Welcome pack. As a third party partner we cannot apply it at this point in the sale.</p>
                                        <p class="vicdefaultmsg display_hide">Are you happy for <?php echo $plan['Plan']['retailer']; ?> to confirm your concession details and eligibility with Centrelink, Veteran Affairs or the Department of Communities?</p>
                                        <div class="form-group yes-no-btn row display_hide vicretailer">
                                            <div class="col-xs-12 ">
                                                <input type="hidden" name="Concession[vic_retailer]" class="ConcessionVicRetailer" value="-1">
                                                <button type="button" onclick="vic_retailer(0,this)" class="butn vic_retailerbuttonno">No</button>
                                                <button type="button" onclick="vic_retailer(1,this)" class="butn vic_retailerbuttonyes">Yes</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="read-msg display_hide vicretaileryesagl">
                                        <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                        <h5>READ VERBATIM TO CUSTOMER </h5>
                                        <p>Just to let you know, if your circumstances change you’ll need to let <?php echo $plan['Plan']['retailer'];?> know.</p>
                                    </div>
                                    <?php endif;?>
                                    <?php if ($plan['Plan']['retailer'] == 'Alinta Energy' && $plan['Plan']['state'] == 'Victoria'):?>
                                    <div class="form-group yes-no-btn row">
                                        <div class="col-xs-12">
                                            <input type="hidden" name="Concession[medical_heating_cooling]" value="-1">
                                             <label class="v2_label v2">Do you have medical heating or cooling?</label>
                                            <button class="butn concession_medical_heating_cooling checkbutton">No</button>
                                            <button class="butn concession_medical_heating_cooling checkbutton">Yes</button>
                                        </div>
                                    </div>
                                    <?php endif;?>
                                    <?php if ($plan['Plan']['retailer'] == 'Simply Energy' && $plan['Plan']['state'] == 'Victoria' && $plan['Plan']['res_sme'] == 'RES'):?>
                                    <div class="form-group yes-no-btn row">
                                        <div class="col-xs-12">
                                            <input type="hidden" name="Concession[confirm_concession]" value="-1">
                                             <label class="v2_label v2">Is this your principal place of residence and the only residence for which the rebate is claimed?<br><br>
                                            <i>(If "Yes" continue with script, If "No" do not continue with adding concession)</i><br><br>
                                            -Just to let you know, Simply Energy will confirm your name, postcode, payment,  concession details and eligibility with the Department of Human Services, Department of Veterans’ Affairs, and other government authorities, as required.
                                            <br>Upon confirming your eligibility, any energy concessions that you are entitled to receive will be applied to your account/s.<br>
                                            It is important that you let Simply Energy and your card issuer know of any changes in your circumstances which may affect your eligibility for a concession.<br>
                                            This consent is valid while you are a customer of Simply Energy. At any point you can withdraw your consent, but please be aware that you may no longer be entitled to receive an energy concession.<br>
                                            If you don’t want your information shared in this way, or withdraw consent, you must get proof of your details directly from the department<br>
                                            Do you consent to Simply Energy performing this check?</label>
                                            <button onclick="show_concession_fields(0)" class="butn concession_confirm_concession checkbutton">No</button>
                                            <button onclick="show_concession_fields(1)" class="butn concession_confirm_concession checkbutton">Yes</button>
                                        </div>
                                    </div>
                                    <?php endif;?>
                                    <div class="viclumomsg display_hide">
                                        <p>To confirm your eligibility and provide you with a concession, Lumo Energy will need  to disclose the information you have provided with the Department of Human Services. Do you agree to Lumo Energy contacting DHS to confirm your name, address and concession card information?</p>
                                        <div class="form-group yes-no-btn row">
                                            <div class="col-xs-12 required-yes">
                                                <input type="hidden" name="Concession[vic_disclose]" class="ConcessionVicDisclose" value="-1">
                                                <button type="button" onclick="vicdisclose(0)" class="butn concession_vic_lumo_disclose checkbutton">No</button>
                                                <button type="button" onclick="vicdisclose(1)" class="butn concession_vic_lumo_disclose checkbutton">Yes</button>
                                            </div>
                                        </div>
                                        <div class="read-msg bgylw display_hide vic_discloseyes"><div class="inner">
                                            <h5>PLEASE INFORM CUSTOMER</h5>
                                            <p>Thank you, your consent will be valid while you are a Lumo Customer or until you withdraw it by contacting them. </p>
                                        </div></div>
                                        <div class="read-msg bgylw display_hide vic_discloseno"><div class="inner">
                                            <h5>PLEASE INFORM CUSTOMER</h5>
                                            <p>Lumo Energy will be unable to provide you with a concession unless you obtain proof of your eligibility from DHS and provide this to them.</p>
                                        </div></div>
                                    </div>
                                    <div class="read-msg bgylw display_hide vic_retailerno"><div class="inner">
                                        <h5>PLEASE INFORM CUSTOMER</h5>
                                        <p>Concession can't be applied at this time; you can contact the retailer at a later date to have your concession applied.</p>
                                    </div></div>
                                    <div class="read-msg bgylw display_hide vicretailernoagl"><div class="inner">
                                        <h5>PLEASE INFORM CUSTOMER</h5>
                                        <p>Concession cannot be applied!</p>
                                    </div></div>
                                    <div class="read-msg bgylw display_hide vicpowershop"><div class="inner">
                                        <h5>PLEASE INFORM CUSTOMER</h5>
                                        <p>Thank you, Powershop will contact you in regards to your concession card details.</p>
                                    </div></div>
                                </div>
                                <div class="form-group NSW display_hide">
                                    <?php if (in_array($plan['Plan']['retailer'], array('AGL', 'Powerdirect', 'Powerdirect and AGL'))):?>
                                    <div class="read-msg">
                                        <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                        <h5>READ VERBATIM TO CUSTOMER </h5>
                                        <p><?php echo $plan['Plan']['retailer'];?>, will collect and use your name, postcode and concession card information to electronically confirm that you are eligible for concession. This authority is only effective for the period that you are a customer of <?php echo $plan['Plan']['retailer'];?>. You can also withdraw your consent at any time by contacting them. If you do not provide your consent you may not be eligible for the concession.</p>
                                    </div>
                                    <?php endif;?>
                                    <?php if (in_array($plan['Plan']['retailer'], array('AGL', 'Powerdirect', 'Powerdirect and AGL', 'Energy Australia'))):?>
                                    <div class="read-msg">
                                        <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                        <h5>READ VERBATIM TO CUSTOMER </h5>
                                        <p class="nswaglmsg display_hide">Do you understand and authorise <?php echo $plan['Plan']['retailer'];?> to provide and access this information?</p>
                                        <p class="nsweamsg display_hide">If you have an eligible form of concession, please contact EnergyAustralia directly on 136 102 once you have received your Welcome pack. As a third party partner we cannot apply it at this point in the sale. </p>
                                        <p class="nswdefaultmsg">Are you happy for <?php echo $plan['Plan']['retailer']; ?> to confirm your concession details and eligibility with Centrelink, Veteran Affairs or the Department of Communities?</p>
                                        <div class="form-group yes-no-btn row display_hide nswretailer">
                                            <div class="col-xs-12 ">
                                                <input type="hidden" name="Concession[nsw_retailer]" class="ConcessionNswRetailer" value="-1">
                                                <button type="button" onclick="nsw_retailer(0,this)" class="butn nsw_retailerbuttonno">No</button>
                                                <button type="button" onclick="nsw_retailer(1,this)" class="butn nsw_retailerbuttonyes">Yes</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="read-msg display_hide nswretaileryesagl">
                                        <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                        <h5>READ VERBATIM TO CUSTOMER </h5>
                                        <p>Just to let you know, if your circumstances change you’ll need to let <?php echo $plan['Plan']['retailer'];?> know.</p>
                                    </div>
                                    <?php endif;?>
                                    <?php if ($plan['Plan']['retailer'] == 'Alinta Energy' && $plan['Plan']['state'] == 'New South Wales'):?>
                                    <div class="form-group yes-no-btn row">
                                        <div class="col-xs-12">
                                            <input type="hidden" name="Concession[medical_heating_cooling]" value="-1">
                                             <label class="v2_label v2">Do you have medical heating or cooling?</label>
                                            <button class="butn concession_medical_heating_cooling checkbutton">No</button>
                                            <button class="butn concession_medical_heating_cooling checkbutton">Yes</button>
                                        </div>
                                    </div>
                                    <?php endif;?>
                                    <?php if ($plan['Plan']['retailer'] == 'Simply Energy' && $plan['Plan']['state'] == 'New South Wales' && $plan['Plan']['res_sme'] == 'RES'):?>
                                    <div class="form-group yes-no-btn row">
                                        <div class="col-xs-12">
                                            <input type="hidden" name="Concession[confirm_concession]" value="-1">
                                             <label class="v2_label v2">Is this your principal place of residence and the only residence for which the rebate is claimed?<br><br>
                                            <i>(If "Yes" continue with script, If "No" do not continue with adding concession)</i><br><br>
                                            -Just to let you know, Simply Energy will confirm your name, postcode, payment,  concession details and eligibility with the Department of Human Services, Department of Veterans’ Affairs, and other government authorities, as required.
                                            <br>Upon confirming your eligibility, any energy concessions that you are entitled to receive will be applied to your account/s.<br>
                                            It is important that you let Simply Energy and your card issuer know of any changes in your circumstances which may affect your eligibility for a concession.<br>
                                            This consent is valid while you are a customer of Simply Energy. At any point you can withdraw your consent, but please be aware that you may no longer be entitled to receive an energy concession.<br>
                                            If you don’t want your information shared in this way, or withdraw consent, you must get proof of your details directly from the department<br>
                                            Do you consent to Simply Energy performing this check?</label>
                                            <button onclick="show_concession_fields(0)" class="butn concession_confirm_concession checkbutton">No</button>
                                            <button onclick="show_concession_fields(1)" class="butn concession_confirm_concession checkbutton">Yes</button>
                                        </div>
                                    </div>
                                    <?php endif;?>
                                    <div class="display_hide nswlumomsg">
                                        <p>To confirm your eligibility and provide you with a concession, Lumo Energy will need to disclose the information you have provided with the Department of Human Services. Do you agree to Lumo Energy contacting DHS to confirm your name, address and concession card information?</p>
                                        <div class="form-group yes-no-btn row">
                                            <div class="col-xs-12 required-yes">
                                                <input type="hidden" name="Concession[nsw_disclose]" class="ConcessionNswDisclose" value="-1">
                                                <button type="button" onclick="nswdisclose(0)" class="butn concession_nsw_lumo_disclose checkbutton">No</button>
                                                <button type="button" onclick="nswdisclose(1)" class="butn concession_nsw_lumo_disclose checkbutton">Yes</button>
                                            </div>
                                        </div>
                                        <div class="read-msg bgylw display_hide nsw_discloseyes"><div class="inner">
                                            <h5>PLEASE INFORM CUSTOMER</h5>
                                            <p>Thank you, your consent will be valid while you are a Lumo Customer or until you withdraw it by contacting them. </p>
                                        </div></div>
                                        <div class="read-msg bgylw display_hide nsw_discloseno"><div class="inner">
                                            <h5>PLEASE INFORM CUSTOMER</h5>
                                            <p>Lumo Energy will be unable to provide you with a concession unless you obtain proof of your eligibility from DHS and provide this to them.</p>
                                        </div></div>
                                    </div>
                                    <div class="read-msg bgylw display_hide nsw_retailerno"><div class="inner">
                                        <h5>PLEASE INFORM CUSTOMER</h5>
                                        <p>Concession can't be applied at this time; you can contact the retailer at a later date to have your concession applied.</p>
                                    </div></div>
                                    <div class="read-msg bgylw display_hide nswretailernoagl"><div class="inner">
                                        <h5>PLEASE INFORM CUSTOMER</h5>
                                        <p>Concession cannot be applied!</p>
                                    </div></div>
                                    <div class="read-msg bgylw display_hide nswpowershop"><div class="inner">
                                        <h5>PLEASE INFORM CUSTOMER</h5>
                                        <p>Thank you, Powershop will contact you in regards to your concession card details.</p>
                                    </div></div>
                                </div>
                                <div class="form-group QLD display_hide">
                                    <div class="read-msg">
                                        <div class="qlddefaultmsg">
                                            <div class="read-msg bgylw display_hide qldretailerno"><div class="inner">
                                                <h5>PLEASE INFORM CUSTOMER</h5>
                                                <p>Concession can't be applied at this time; you can contact the retailer at a later date to have your concession applied.</p>
                                            </div></div>
                                        </div>
                                        <div class="read-msg display_hide qldaglmsg">
                                            <div class="read-msg">
                                                <h5>PLEASE INFORM CUSTOMER</h5>
                                                <p><strong>To assess electricity or gas rebate eligibility for Queensland I need to confirm a few things:</strong></p>
                                                <p>• Is this your primary place of residence? (If Yes, proceed to next question. If No, do not add concession)<br>
                                                    • Does anyone live in the house that receives an income and pays rent that is not a dependent on you? (If Yes, proceed to next question, If No add concession)<br>
                                                    • Do they hold a concession? (If Yes, add concession & read the following consent script. If No, do not add concession)</p>
                                                <div class="form-group yes-no-btn row">
                                                    <div class="col-xs-12">
                                                        <input type="hidden" name="Concession[qld_add_concession]" class="ConcessionQldAddconcession" value="-1">
                                                        <button type="button" onclick="qldaddconcession(0)" class="butn qldaddconcessionno">Don't add concession</button>
                                                        <button type="button" onclick="qldaddconcession(1)" class="butn qldaddconcessionyes">Add concession</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="read-msg display_hide collectanduse">
                                                <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                                <h5>READ VERBATIM TO CUSTOMER </h5>
                                                <p><?php echo $plan['Plan']['retailer'];?>, will collect and use your name, postcode and concession card information to electronically confirm that you are eligible for concession. This authority is only effective for the period that you are a customer of <?php echo $plan['Plan']['retailer'];?>. You can also withdraw your consent at any time by contacting them. If you do not provide your consent you may not be eligible for the concession.</p>
                                            </div>
                                            <div class="read-msg display_hide understandandauthorise">
                                                <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                                <h5>READ VERBATIM TO CUSTOMER </h5>
                                                <div class="form-group yes-no-btn row">
                                                    <div class="col-xs-12 ">
                                                         <label class="v2_label v2">Do you understand and authorise <?php echo $plan['Plan']['retailer'];?> to provide and access this information?</label>
                                                        <input type="hidden" name="Concession[qld_retailer]" class="ConcessionQldRetailer" value="-1">
                                                        <button type="button" onclick="show_concession_fields(0)" class="butn concession_qld_retailer qldretailerbuttonno checkbutton">No</button>
                                                        <button type="button" onclick="show_concession_fields(1)" class="butn concession_qld_retailer qldretailerbuttonyes checkbutton">Yes</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="read-msg display_hide qldretaileryes">
                                                <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                                <h5>READ VERBATIM TO CUSTOMER </h5>
                                                <p>Just to let you know, if your circumstances change you’ll need to let <?php echo $plan['Plan']['retailer'];?> know.</p>
                                            </div>
                                            <div class="read-msg bgylw display_hide qldretailernoagl"><div class="inner">
                                                <h5>PLEASE INFORM CUSTOMER</h5>
                                                <p>Concession cannot be applied!</p>
                                            </div></div>
                                        </div>
                                        <?php if ($plan['Plan']['retailer'] == 'Alinta Energy' && $plan['Plan']['state'] == 'Queensland'):?>
                                        <div class="form-group yes-no-btn row">
                                            <div class="col-xs-12">
                                                <input type="hidden" name="Concession[medical_heating_cooling]" value="-1">
                                                 <label class="v2_label v2">Do you have medical heating or cooling?</label>
                                                <button class="butn concession_medical_heating_cooling checkbutton">No</button>
                                                <button class="butn concession_medical_heating_cooling checkbutton">Yes</button>
                                            </div>
                                        </div>
                                        <?php endif;?>
                                        <?php if ($plan['Plan']['retailer'] == 'Simply Energy' && $plan['Plan']['state'] == 'Queensland' && $plan['Plan']['res_sme'] == 'RES'):?>
                                        <div class="form-group yes-no-btn row">
                                            <div class="col-xs-12">
                                                <input type="hidden" name="Concession[confirm_concession]" value="-1">
                                                 <label class="v2_label v2">Is this your principal place of residence and the only residence for which the rebate is claimed?<br><br>
                                                <i>(If "Yes" continue with script, If "No" do not continue with adding concession)</i><br><br>
                                                -Just to let you know, Simply Energy will confirm your name, postcode, payment,  concession details and eligibility with the Department of Human Services, Department of Veterans’ Affairs, and other government authorities, as required.
                                                <br>Upon confirming your eligibility, any energy concessions that you are entitled to receive will be applied to your account/s.<br>
                                                It is important that you let Simply Energy and your card issuer know of any changes in your circumstances which may affect your eligibility for a concession.<br>
                                                This consent is valid while you are a customer of Simply Energy. At any point you can withdraw your consent, but please be aware that you may no longer be entitled to receive an energy concession.<br>
                                                If you don’t want your information shared in this way, or withdraw consent, you must get proof of your details directly from the department<br>
                                                Do you consent to Simply Energy performing this check?</label>
                                                <button onclick="show_concession_fields(0)"  class="butn concession_confirm_concession checkbutton">No</button>
                                                <button onclick="show_concession_fields(1)"  class="butn concession_confirm_concession checkbutton">Yes</button>
                                            </div>
                                        </div>
                                        <?php endif;?>
                                        <div class="read-msg display_hide qldeamsg">
                                            <p>If you have an eligible form of concession, please contact EnergyAustralia directly on 136 102 once you have received your Welcome pack. As a third party partner we cannot apply it at this point in the sale.</p>
                                        </div>
                                    </div>
                                    <div class="read-msg bgylw display_hide qldpowershop"><div class="inner">
                                        <h5>PLEASE INFORM CUSTOMER</h5>
                                        <p>Thank you, Powershop will contact you in regards to your concession card details.</p>
                                    </div></div>
                                </div>
                                <div class="form-group SA display_hide">
                                    <div class="read-msg display_hide contactdepartmentmsg">
                                        <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                        <h5>READ VERBATIM TO CUSTOMER </h5>
                                        <p>As you are in South Australia, you'll need to contact the Department of Human Services on 1800 307 758 to have the concession applied.</p>
                                    </div>
                                    <div class="read-msg display_hide contacthotlinemsg">
                                        <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                        <h5>READ VERBATIM TO CUSTOMER </h5>
                                        <p>To apply for a concession, contact the SA concessions hotline on 1800 307 758</p>
                                    </div>
                                    <div class="read-msg display_hide viadepartmentmsg">
                                        <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                        <h5>READ VERBATIM TO CUSTOMER </h5>
                                        <p>We cannot update your concession information directly, but you can do it via the Department for Communities and Social Inclusion on 1800 307 758 to have the concession applied.</p>
                                    </div>
                                    <div class="read-msg bgylw display_hide sapowershop"><div class="inner">
                                        <h5>PLEASE INFORM CUSTOMER</h5>
                                        <p>Thank you, Powershop will contact you in regards to your concession card details.</p>
                                    </div></div>
                                </div>
                                <div class="form-group row Momentum display_hide"></div>
                                <div class="form-group SumoPower display_hide">
                                    <div class="form-group">
                                        <div class="form-group yes-no-btn row">
                                            <div class="col-xs-12 required-yes">
                                                 <label class="v2_label v2">Sumo Power will send you out a form regarding your concession which you will need to fill in and send back to them. Do you understand this?</label>
                                                <input type="hidden" name="Concession[sumo_power_understand_form]" value="-1">
                                                <button type="button" onclick="sumopowerunderstandform(0)" class="butn sumo_power_understand_form checkbutton">No</button>
                                                <button type="button" onclick="sumopowerunderstandform(1)" class="butn sumo_power_understand_form checkbutton">Yes</button>
                                            </div>
                                        </div>
                                        <div class="read-msg bgylw display_hide sumopowerunderstandformno"><div class="inner">
                                            <h5>PLEASE INFORM CUSTOMER </h5>
                                            <p>Sorry, we cannot apply concession with Sumo Power</p>
                                        </div></div>
                                    </div>
                                </div>
                                <div class="concession-card-fields mt0 display_hide">
                                    <div class="form-data">
                                        <div class="form-group yes-no-btn">
                                            <a class="butn" href="https://drive.google.com/file/d/1MzABfO9-vyJN0Wa0v_Tmqs_W6ACp1LX_/view" target="_blank">Click Here for Concession Card Eligibility in each state</a>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-xs-6">
                                                 <label class="v2_label">Concession Title</label>
                                                <div class="v2_field"><select class="selectConcessionTitle selectpicker show-menu-arrow" name="Concession[title]" id="concession_title">
                                                    <option value="">Select</option>
                                                    <option value="Mr">Mr</option>
                                                    <option value="Mrs">Mrs</option>
                                                    <option value="Ms">Ms</option>
                                                    <option value="Miss">Miss</option>
                                                    <option value="Lady">Lady</option>
                                                    <option value="Rev">Rev</option>
                                                    <option value="Dr">Dr</option>
                                                </select></div>
                                            </div>
                                            <div class="col-xs-6">
                                                 <label class="v2_label">Concession First Name</label>
                                                <div class="v2_field"><input type="text" value="" name="Concession[first_name]" id="concession_first_name" class="form-control"/></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-xs-6">
                                                 <label class="v2_label">Concession Middle Name</label>
                                                <div class="v2_field"><input type="text" value="" name="Concession[middle_name]" id="concession_middle_name" /></div>
                                            </div>
                                            <div class="col-xs-6">
                                                 <label class="v2_label">Concession Last Name</label>
                                                <div class="v2_field"><input type="text" value="" name="Concession[last_name]" id="concession_last_name" class="form-control"/></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-xs-6">
                                                 <label class="v2_label">Concession Card Issuer</label>
                                                <div class="v2_field"><select class="selectConcessionCardIssuer selectpicker show-menu-arrow" name="Concession[card_issuer]">
                                                    <option value="">Select</option>
                                                    <?php if (in_array($plan['Plan']['retailer'], array('AGL', 'Powerdirect', 'Powerdirect and AGL'))):?>
                                                    <option value="DVA">Dept of Communities</option>
                                                    <option value="CTLK">Centrelink</option>
                                                    <option value="DVA">Dept of Veterans' Affairs</option>
                                                    <?php else:?>
                                                    <option value="CTLK">Centrelink</option>
                                                    <option value="DVA">Department of Veteran Affairs</option>
                                                    <?php endif;?>
													</select></div>
                                            </div>
                                            <div class="col-xs-6">
                                                 <label class="v2_label">Concession Card Type</label>
                                                <div class="v2_field"><select class="selectConcessionCardType selectpicker show-menu-arrow" name="Concession[card_type]">
                                                    <option value="">Select</option>
                                                    <?php if (in_array($plan['Plan']['retailer'], array('AGL', 'Powerdirect', 'Powerdirect and AGL'))):?>
                                                    <option value="COM_SENIOR">Commonwealth Seniors Health Card</option>
                                                    <option value="PENSIONER">Pensioner</option>
                                                    <option value="HEALTHCARE">Healthcare</option>
                                                    <option value="GOLD_WW">War Widow (WW)</option>
                                                    <option value="GOLD_TPI">Totally & Permanently Incapacitated (TPI)</option>
                                                    <option value="GOLD_EDA">Extreme Disablement Adjustment (EDA)</option>
                                                    <option value="QSC">QLD State Seniors Card</option>
                                                    <?php else:?>
                                                    <option value="GOLD_TPI">GOLD_TPI</option>
                                                    <option value="GOLD_WW">GOLD_WW</option>
                                                    <option value="HEALTHCARE">HEALTHCARE</option>
                                                    <option value="PENSIONER">PENSIONER</option>
                                                    <option value="QSC_GOV">QSC_GOV</option>
                                                    <option value="GOLD_EDA">GOLD_EDA</option>
                                                    <option value="CSHC">CSHC</option>
                                                    <option value="NA">NA</option>
                                                    <?php endif;?>
													</select></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                                 <label class="v2_label">Concession Card Number</label>
                                                <div class="v2_field"><input type="text" value="" name="Concession[card_number]" id="concession_card_number" class="form-control"/></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-xs-6">
                                                 <label class="v2_label">Concession Card Start Date</label>
                                                <div class="v2_field"><input type="text" name="Concession[card_start]" id="concession_card_start" class="calender-icon form-control"/></div>
                                            </div>
                                            <div class="col-xs-6">
                                                 <label class="v2_label">Concession Card Expiry</label>
                                                <div class="v2_field"><input type="text" name="Concession[card_expiry]" id="concession_card_expiry" class="calender-icon form-control"/></div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="read-msg bgred"><div class="inner">
                                                <p>Make sure you have read & spelt out the full name which is shown on the concession card! - This name can be different to the name on the energy account & is very important - you will get a fix up if you do not action this correctly!</p>
                                            </div></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="submit-btnotr text-right"><input type="submit" onclick="checkConcessionFields()" class="submit-btn checkConcessionFields" value="Mark section as Completed"/></div>
                        </div>
                    </form>
                </div>
			
                <h3 class="form-hdg">Supply Address<i class="fa fa-angle-up" aria-hidden="true"></i><i class="fa fa-angle-down" aria-hidden="true"></i></h3>
            	<div class="form clearfix collapse" id="form-collapse6">
                    <form action="" method="post" class="signup_supply" name="signup_supply">
                        <div class="form-data">
                            <p>*The Supply address is what the customer knows their address to be</p>
                            <?php if ($plan['Plan']['package'] == 'Elec'): ?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <div class="read-msg">
                                            <h5>PLEASE INFORM CUSTOMER </h5>
                                            <p>I'm now going to read to you, your NMI (National Meter Identifier) this will be shown on your bill and welcome pack.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row display_hide clear-access">
                                    <div class="col-xs-12">
                                        <div class="read-msg bgylw"><div class="inner">
                                            <h5>PLEASE INFORM CUSTOMER </h5>
                                            <p>Please note that clear access to the electricity meter and property is required and fees may be levied where multiple visits are required.</p>
                                        </div></div>
                                    </div>
                                </div>
                            <?php elseif ($plan['Plan']['package'] == 'Gas'):?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <div class="read-msg">
                                            <h5>PLEASE INFORM CUSTOMER </h5>
                                            <p>I'm now going to read to you, your MIRN (Meter Installation Registration Number) this will be shown on your bill and welcome pack.</p>
                                        </div>
                                    </div>
                                </div>
                            <?php elseif ($plan['Plan']['package'] == 'Dual'):?>
                                <div class="form-group row">
                                    <div class="col-xs-12">
                                        <div class="read-msg">
                                            <h5>PLEASE INFORM CUSTOMER </h5>
                                            <p>I'm now going to read to you, your NMI (National Meter Identifier) & MIRN (Meter Installation Registration Number) these will be shown on your bill and welcome pack.</p>
                                        </div>
                                    </div>
                                </div>
                            <?php endif;?>
                            <?php if ($plan['Plan']['retailer'] == 'Alinta Energy'):?>
                            <div class="form-group row display_hide multisite">
                                <div class="col-xs-12">
                                    <div class="read-msg bgylw"><div class="inner">
                                        <p>This offer provides for the supply of energy to the following sites:</p>
                                    </div></div>
                                </div>
                            </div>
                            <?php endif;?>
                            <div class="repeater">
                                <div class="repeater-field">
                                    <div class="form-group row nmisupply">
                                        <div class="col-xs-12">
                                             <label class="v2_label"><strong>NMI*</strong> - <i>Careful! This is a full fix up - Is this the right NMI? - If so read all 11 digits</i></label>
                                            <div class="v2_field"><input type="text" name="Supply[nmi][0]" value="<?php echo $user['step1']['nmi']; ?>" maxlength="11" class="mni_field form-control"/></div>
                                        </div>
                                    </div>
                                    <div class="form-group row mirnsupply">
                                        <div class="col-xs-12">
                                             <label class="v2_label"><strong>MIRN*</strong> - <i>Careful! This is a full fix up - Is this the right MIRN? - If so read all 11 digits</i></label>
                                            <div class="v2_field"><input type="text" name="Supply[mirn][0]" class="mirn_field form-control" maxlength="11"/></div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-12 checkbox-sec">
                                            <input id="supply_no_street_number_0" type="checkbox" name="Supply[no_street_number][0]" value="1">
                                            <label class="checkbox-clone" for="supply_no_street_number_0">&nbsp;</label>
                                            <span>Address Has no Street Number</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                             <label class="v2_label">Unit</label>
                                            <div class="v2_field"><input type="text" name="Supply[unit][0]" value="" class="supplyunit form-control"/></div>
                                        </div>
                                        <div class="col-xs-6">
                                             <label class="v2_label">Unit Type</label>
                                            <div class="v2_field"><select class="supplyunittype form-control" name="Supply[unit_type][0]">
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
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-4">
                                             <label class="v2_label">Lot</label>
                                            <div class="v2_field"><input type="text" name="Supply[lot][0]" value="" class="form-control"/></div>
                                        </div>
                                        <div class="col-xs-4">
                                             <label class="v2_label">Floor</label>
                                            <div class="v2_field"><input type="text" name="Supply[floor][0]" value="" class="form-control"/></div>
                                        </div>
                                        <div class="col-xs-4">
                                             <label class="v2_label">Floor Type</label>
                                            <div class="v2_field"><select class="supplyfloortype form-control" name="Supply[floor_type][0]">
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
                                            </select></div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                             <label class="v2_label">Building Name</label>
                                            <div class="v2_field"><input type="text" name="Supply[building_name][0]" value="" class="form-control"/></div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-4">
                                             <label class="v2_label">Street Number</label>
                                            <div class="v2_field"><input type="text" name="Supply[street_number][0]" value="" class="form-control"/></div>
                                        </div>
                                        <div class="col-xs-4">
                                             <label class="v2_label">Street Number Suffix</label>
                                            <div class="v2_field"><input type="text" name="Supply[street_number_suffix][0]" value="" class="form-control"/></div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-4">
                                             <label class="v2_label">Street Name</label>
                                            <div class="v2_field"><input type="text" name="Supply[street_name][0]" value="" class="form-control"/></div>
                                        </div>
                                        <div class="col-xs-4">
                                             <label class="v2_label">Street Name Suffix</label>
                                            <div class="v2_field"><input type="text" name="Supply[street_name_suffix][0]" value="" class="form-control"/></div>
                                        </div>
                                        <div class="col-xs-4 strtType poRltv">
                                             <label class="v2_label">Street Type</label>
                                            <div class="v2_field"><input type="text" value="" name="Supply[street_type][0]" class="street_type form-control"></div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-4">
                                             <label class="v2_label">Suburb*</label>
                                            <div class="v2_field"><input type="text" name="Supply[suburb][0]" value="<?php echo $user['suburb']; ?>" class="form-control"/></div>
                                        </div>
                                        <div class="col-xs-4">
                                             <label class="v2_label">Postcode*</label>
                                            <div class="v2_field"><input type="text" name="Supply[postcode][0]" value="<?php echo $user['postcode']; ?>" class="form-control"/></div>
                                        </div>
                                        <div class="col-xs-4">
                                             <label class="v2_label">State*</label>
                                            <div class="v2_field"><select class="selectSupplyState form-control" name="Supply[state][0]">
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
                                                    <?php if ($plan['Plan']['state'] == 'Western Australia'): ?>selected="selected"<?php endif; ?>
                                                    value="WA">WA
                                                </option>
                                                <option
                                                    <?php if ($plan['Plan']['state'] == 'Northern Territory'): ?>selected="selected"<?php endif; ?>
                                                    value="NT">NT
                                                </option>
                                                <option
                                                    <?php if ($plan['Plan']['state'] == 'Australian Capital Territory'): ?>selected="selected"<?php endif; ?>
                                                    value="ACT">ACT
                                                </option>
                                                <option
                                                    <?php if ($plan['Plan']['state'] == 'New Zealand'): ?>selected="selected"<?php endif; ?>
                                                    value="NZ">NZ
                                                </option>
                                                <option
                                                    <?php if ($plan['Plan']['state'] == 'Tasmania'): ?>selected="selected"<?php endif; ?>
                                                    value="TAS">TAS
                                                </option>
                                            </select></div>
                                        </div>
                                    </div>
                                    <div class="form-group yes-no-btn row nmiacqret">
                                        <div class="col-xs-12 ">
                                             <label class="v2_label v2">NMI Acquisition/Retention</label>
                                            <input type="hidden" name="Supply[nmi_acq_ret][0]" class="supplynmiacqret" value="-1">
                                            <button type="button" onclick="nmiacqret(0, this)" class="butn supplynmiacqretbtn supplynmiacq checkbutton">Acquisition</button>
                                            <button type="button" onclick="nmiacqret(1, this)" class="butn supplynmiacqretbtn supplynmiret checkbutton">Retention</button>
                                        </div>
                                    </div>
                                    <div class="form-group yes-no-btn row mirnacqret">
                                        <div class="col-xs-12 ">
                                             <label class="v2_label v2">MIRN Acquisition/Retention</label>
                                            <input type="hidden" name="Supply[mirn_acq_ret][0]" class="supplymirnacqret" value="-1">
                                            <button type="button" onclick="mirnacqret(0, this)" class="butn supplymirnacqretbtn supplymirnacq checkbutton">Acquisition</button>
                                            <button type="button" onclick="mirnacqret(1, this)" class="butn supplymirnacqretbtn supplymirnret checkbutton">Retention</button>
                                        </div>
                                    </div>
                                    <div class="form-group yes-no-btn row display_hide understand_transfer_retention" data-id="0">
                                        <div class="col-xs-12 required-yes">
                                            <input type="hidden" name="Supply[transfer_retention][0]" value="-1">
                                             <label class="v2_label v2">Please confirm you understand these changes to your account will be updated when <?php echo $plan['Plan']['retailer']; ?> approve and update your details in their system.​</label>
                                            <button class="butn transfer_retention_0 checkbutton">No</button>
                                            <button class="butn transfer_retention_0 checkbutton">Yes</button>
                                        </div>
                                    </div>
                                    <div class="form-group yes-no-btn row display_hide understand_transfer_retention_sold">
                                        <div class="col-xs-12">
                                             <label class="v2_label v2">The offer I have sold you today is based on the address and information you have provided me. You will remain with your current retailer for <?php echo $plan['Plan']['package'];?> but I need to let you know this may not be the cheapest option available to you from our panel.​</label>
                                        </div>
                                    </div>
                                    <div class="form-group display_hide understand_transfer_retention_retain">
                                        <div class="read-msg bgred"><div class="inner">
                                            <h5>PLEASE INFORM CUSTOMER </h5>
                                            <p>Sorry, we are unable to proceed with the signup as we are unauthorised to retain customers for this retailer </p>
                                        </div></div>
                                    </div>
                                    <div class="form-group row mirnisdifferent">
                                        <div class="col-xs-12 checkbox-sec">
                                            <input type="checkbox" name="Supply[mirn_is_different][0]" id="mirn_is_different_0" onclick="checkSupplyMIRNAddress(this);" value="1"/>
                                            <label class="checkbox-clone" for="mirn_is_different_0">&nbsp;</label>
                                            <span onclick="checkSupplyMIRNAddressSpan(this)" style="color: #F00;"><strong>CHECK IF MIRN ADDRESS IS DIFFERENT</strong>
                                                <i>IF MIRN address is different from supply address, read the MIRN address & advise customer this is how it is displayed in the national grid then enter MIRN Address as per MIRN look up.</i></span>
                                        </div>
                                    </div>
                                    <div class="mirnaddress">
                                        <div class="form-group row">
                                            <h3>MIRN Address</h3>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-xs-12 checkbox-sec">
                                                <input id="supply_no_street_number_mirn_0" type="checkbox" name="Supply[no_street_number_mirn][0]" value="1">
                                                <label class="checkbox-clone" for="supply_no_street_number_mirn_0">&nbsp;</label>
                                                <span>Address Has no Street Number</span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                                 <label class="v2_label">Unstructured MIRN Address</label>
                                                <div class="v2_field"><select class="supplyunstructuredmirnaddress form-control" name="Supply[unstructured_mirn_address][0]" class="form-control">
                                                    <option value=''>Select</option>
                                                    <option value="Yes">Yes</option>
                                                    <option value="Has Both">Has Both</option>
                                                    <option value="No">No</option>
                                                    <option value="N/A">N/A</option>
													</select></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-xs-6">
                                                 <label class="v2_label">Unit</label>
                                                <div class="v2_field"><input type="text" name="Supply[unit_mirn][0]" value="" class="supplyunitmirn"  class="form-control"></div>
                                            </div>
                                            <div class="col-xs-6">
                                                 <label class="v2_label">Unit Type</label>
                                                <div class="v2_field">
													<select class="supplyunittypemirn form-control" name="Supply[unit_type_mirn][0]" class="form-control">
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
                                            </div></div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-xs-4">
                                                 <label class="v2_label">Lot</label>
                                                <div class="v2_field"><input type="text" name="Supply[lot_mirn][0]" value="" class="form-control"></div>
                                            </div>
                                            <div class="col-xs-4">
                                                 <label class="v2_label">Floor</label>
                                                <div class="v2_field"><input type="text" name="Supply[floor_mirn][0]" value="" class="form-control"></div>
                                            </div>
                                            <div class="col-xs-4">
                                                 <label class="v2_label">Floor Type</label>
                                                <div class="v2_field"><select class="supplyfloortype form-control" name="Supply[floor_type_mirn][0]">
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
                                                </select></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                                 <label class="v2_label">Building Name</label>
                                                <div class="v2_field"><input type="text" name="Supply[building_name_mirn][0]" value="" class="form-control"/></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-xs-4">
                                                 <label class="v2_label">Street Number</label>
                                                <div class="v2_field"><input type="text" name="Supply[street_number_mirn][0]" value="" class="form-control"/></div>
                                            </div>
                                            <div class="col-xs-4">
                                                 <label class="v2_label">Street Number Suffix</label>
                                                <div class="v2_field"><input type="text" name="Supply[street_number_suffix_mirn][0]" value="" class="form-control"/></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-xs-4">
                                                 <label class="v2_label">Street Name</label>
                                                <div class="v2_field"><input type="text" name="Supply[street_name_mirn][0]" value="" class="form-control"/></div>
                                            </div>
                                            <div class="col-xs-4">
                                                 <label class="v2_label">Street Name Suffix</label>
                                                <div class="v2_field"><input type="text" name="Supply[street_name_suffix_mirn][0]" value="" class="form-control"/></div>
                                            </div>
                                            <div class="col-xs-4 strtType poRltv">
                                                 <label class="v2_label">Street Type</label>
                                                <div class="v2_field"><input type="text" value="" name="Supply[street_type_mirn][0]" class="street_type form-control"></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-xs-4">
                                                 <label class="v2_label">Suburb*</label>
                                                <div class="v2_field"><input type="text" name="Supply[suburb_mirn][0]" value="<?php echo $user['suburb']; ?>" class="form-control"/></div>
                                            </div>
                                            <div class="col-xs-4">
                                                 <label class="v2_label">Postcode*</label>
                                                <div class="v2_field"><input type="text" name="Supply[postcode_mirn][0]" value="<?php echo $user['postcode']; ?>" class="form-control"/></div>
                                            </div>
                                            <div class="col-xs-4">
                                                 <label class="v2_label">State*</label>
                                                <div class="v2_field"><select class="selectSupplyMirnState form-control" name="Supply[state_mirn][0]">
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
                                                        <?php if ($plan['Plan']['state'] == 'Western Australia'): ?>selected="selected"<?php endif; ?>
                                                        value="WA">WA
                                                    </option>
                                                    <option
                                                        <?php if ($plan['Plan']['state'] == 'Northern Territory'): ?>selected="selected"<?php endif; ?>
                                                        value="NT">NT
                                                    </option>
                                                    <option
                                                        <?php if ($plan['Plan']['state'] == 'Australian Capital Territory'): ?>selected="selected"<?php endif; ?>
                                                        value="ACT">ACT
                                                    </option>
                                                    <option
                                                        <?php if ($plan['Plan']['state'] == 'New Zealand'): ?>selected="selected"<?php endif; ?>
                                                        value="NZ">NZ
                                                    </option>
                                                    <option
                                                        <?php if ($plan['Plan']['state'] == 'Tasmania'): ?>selected="selected"<?php endif; ?>
                                                        value="TAS">TAS
                                                    </option>
													</select></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row msatsisdifferent">
                                        <div class="col-xs-12 checkbox-sec">
                                            <input type="checkbox" name="Supply[msats_is_different][0]" id="msats_is_different0" onclick="checkSupplyMSATSAddress(this);" value="1"/>
                                            <label class="checkbox-clone" for="msats_is_different0">&nbsp;</label>
                                            <span onclick="checkSupplyMSATSAddressSpan(this)" style="color: #F00;"><strong>CHECK IF MSATS ADDRESS IS DIFFERENT</strong><i>Advisory - IF MSATS address is different from supply address, read the MSATS address & advise customer this is how it is displayed in the national grid then enter MSATS address correctly.</i></span>
                                        </div>
                                    </div>
                                    <div class="msatsaddress">
                                        <div class="form-group row">
                                            <h3>MSATS Address</h3>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-xs-12 checkbox-sec">
                                                <input id="supply_no_street_number_msats_0" type="checkbox" name="Supply[no_street_number_msats][0]" value="1">
                                                <label class="checkbox-clone" for="supply_no_street_number_msats_0">&nbsp;</label>
                                                <span>Address Has no Street Number</span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                                 <label class="v2_label">Unstructured MSATS Address</label>
                                                <div class="v2_field"><select class="supplyunstructuredmsatsaddress form-control" name="Supply[unstructured_msats_address][0]">
                                                    <option value=''>Select</option>
                                                    <option value="Yes">Yes</option>
                                                    <option value="Has Both">Has Both</option>
                                                    <option value="No">No</option>
                                                    <option value="N/A">N/A</option>
													</select></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-xs-6">
                                                 <label class="v2_label">Unit</label>
                                                <div class="v2_field"><input type="text" name="Supply[unit_msats][0]" value="" class="supplyunitmsats"/></div>
                                            </div>
                                            <div class="col-xs-6">
                                                 <label class="v2_label">Unit Type</label>
                                                <div class="v2_field"><select class="supplyunittypemsats form-control" name="Supply[unit_type_msats][0]">
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
													</select></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-xs-4">
                                                 <label class="v2_label">Lot</label>
                                                <div class="v2_field"><input type="text" name="Supply[lot_msats][0]" value="" class="form-control"/></div>
                                            </div>
                                            <div class="col-xs-4">
                                                 <label class="v2_label">Floor</label>
                                                <div class="v2_field"><input type="text" name="Supply[floor_msats][0]" value="" class="form-control"/></div>
                                            </div>
                                            <div class="col-xs-4">
                                                 <label class="v2_label">Floor Type</label>
                                                <div class="v2_field"><select class="supplyfloortype form-control" name="Supply[floor_type_msats][0]">
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
													</select></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                                 <label class="v2_label">Building Name</label>
                                                <div class="v2_field"><input type="text" name="Supply[building_name_msats][0]" value=""/></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-xs-4">
                                                 <label class="v2_label">Street Number</label>
                                                <div class="v2_field"><input type="text" name="Supply[street_number_msats][0]" value=""/></div>
                                            </div>
                                            <div class="col-xs-4">
                                                 <label class="v2_label">Street Number Suffix</label>
                                                <div class="v2_field"><input type="text" name="Supply[street_number_suffix_msats][0]" value=""/></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-xs-4">
                                                 <label class="v2_label">Street Name</label>
                                                <div class="v2_field"><input type="text" name="Supply[street_name_msats][0]" value=""/></div>
                                            </div>
                                            <div class="col-xs-4">
                                                 <label class="v2_label">Street Name Suffix</label>
                                                <div class="v2_field"><input type="text" name="Supply[street_name_suffix_msats][0]" value=""/></div>
                                            </div>
                                            <div class="col-xs-4 strtType poRltv">
                                                 <label class="v2_label">Street Type</label>
                                                <div class="v2_field"><input type="text" value="" name="Supply[street_type_msats][0]" class="street_type"></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-xs-4">
                                                 <label class="v2_label">Suburb*</label>
                                                <div class="v2_field"><input type="text" name="Supply[suburb_msats][0]" value="<?php echo $user['suburb']; ?>" class="form-control"/></div>
                                            </div>
                                            <div class="col-xs-4">
                                                 <label class="v2_label">Postcode*</label>
                                                <div class="v2_field"><input type="text" name="Supply[postcode_msats][0]" value="<?php echo $user['postcode']; ?>" class="form-control"/></div>
                                            </div>
                                            <div class="col-xs-4">
                                                 <label class="v2_label">State*</label>
                                                <div class="v2_field"><select class="selectSupplyState form-control" name="Supply[state_msats][0]">
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
                                                        <?php if ($plan['Plan']['state'] == 'Western Australia'): ?>selected="selected"<?php endif; ?>
                                                        value="WA">WA
                                                    </option>
                                                    <option
                                                        <?php if ($plan['Plan']['state'] == 'Northern Territory'): ?>selected="selected"<?php endif; ?>
                                                        value="NT">NT
                                                    </option>
                                                    <option
                                                        <?php if ($plan['Plan']['state'] == 'Australian Capital Territory'): ?>selected="selected"<?php endif; ?>
                                                        value="ACT">ACT
                                                    </option>
                                                    <option
                                                        <?php if ($plan['Plan']['state'] == 'New Zealand'): ?>selected="selected"<?php endif; ?>
                                                        value="NZ">NZ
                                                    </option>
                                                    <option
                                                        <?php if ($plan['Plan']['state'] == 'Tasmania'): ?>selected="selected"<?php endif; ?>
                                                        value="TAS">TAS
                                                    </option>
													</select></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group bggrey row aglsupply">
                                        <div class="col-xs-12">
                                             <label class="v2_label">MSATS/MIRN Address</label>
                                            <div class="v2_field"><input name="Supply[msats_mirn_address][0]" type="text" value="" class="form-control"/></div>
                                        </div>
                                    </div>
                                    <div class="form-group yes-no-btn row">
                                        <div class="col-xs-12 ">
                                             <label class="v2_label v2">Tenant/Owner</label>
                                            <input type="hidden" name="Supply[tenant_owner][0]" class="supplytenantowner" value="-1">
                                            <button type="button" onclick="tenantowner(0, this)" class="butn supplytenantownerbtn  supplytenant checkbutton">Tenant</button>
                                            <button type="button" onclick="tenantowner(1, this)" class="butn supplytenantownerbtn supplyowner checkbutton">Owner</button>
                                        </div>
                                    </div>
                                </div>
                                <script type="text/template" class="repeater-field-tpl">
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <h2>Supply Address</h2>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                            <p>Only use this function if the new property matches the same plan, <strong>fuel</strong> & <strong>state</strong> as the original property. If it doesn't, please finish & submit this lead, choose the new plan, then use this lead ID to populate the data.</p>
                                        </div>
                                    </div>
                                    <div class="form-group row nmisupply">
                                        <div class="col-xs-12">
                                             <label class="v2_label"><strong>NMI*</strong> - <i>Careful! This is a full fix up - Is this the right NMI? - If so read all 11 digits</i></label>
                                            <div class="v2_field"><input type="text" name="Supply[nmi][{{index}}]" value="" maxlength="11" class="form-control"/></div>
                                        </div>
                                    </div>
                                    <div class="form-group row mirnsupply">
                                        <div class="col-xs-12">
                                             <label class="v2_label"><strong>MIRN*</strong> - <i>Careful! This is a full fix up - Is this the right MIRN? - If so read all 11 digits</i></label>
                                            <div class="v2_field"><input type="text" name="Supply[mirn][{{index}}]" value="" maxlength="11" class="form-control"/></div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-12 checkbox-sec">
                                            <input id="supply_no_street_number_{{index}}" type="checkbox" name="Supply[no_street_number][{{index}}]" value="1">
                                            <label class="checkbox-clone" for="supply_no_street_number_{{index}}">&nbsp;</label>
                                            <span>Address Has no Street Number</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                             <label class="v2_label">Unit</label>
                                            <div class="v2_field"><input type="text" name="Supply[unit][{{index}}]" value="" class="supplyunit form-control"/></div>
                                        </div>
                                        <div class="col-xs-6">
                                             <label class="v2_label">Unit Type</label>
                                            <div class="v2_field"><select class="supplyunittype form-control" name="Supply[unit_type][{{index}}]">
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
                                            </select></div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-4">
                                             <label class="v2_label">Lot</label>
                                            <div class="v2_field"><input type="text" name="Supply[lot][{{index}}]" value="" class="form-control"/></div>
                                        </div>
                                        <div class="col-xs-4">
                                             <label class="v2_label">Floor</label>
                                            <div class="v2_field"><input type="text" name="Supply[floor][{{index}}]" value="" class="form-control"/></div>
                                        </div>
                                        <div class="col-xs-4">
                                             <label class="v2_label">Floor Type</label>
                                            <div class="v2_field"><select class="supplyfloortype form-control" name="Supply[floor_type][{{index}}]">
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
                                            </select></div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                             <label class="v2_label">Building Name</label>
                                            <div class="v2_field"><input type="text" name="Supply[building_name][{{index}}]" value="" class="form-control"/></div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-4">
                                             <label class="v2_label">Street Number</label>
                                            <div class="v2_field"><input type="text" name="Supply[street_number][{{index}}]" value="" class="form-control"/></div>
                                        </div>
                                        <div class="col-xs-4">
                                             <label class="v2_label">Street Number Suffix</label>
                                            <div class="v2_field"><input type="text" name="Supply[street_number_suffix][{{index}}]" value="" class="form-control"/></div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-4">
                                             <label class="v2_label">Street Name</label>
                                            <div class="v2_field"><input type="text" name="Supply[street_name][{{index}}]" value="" class="form-control"/></div>
                                        </div>
                                        <div class="col-xs-4">
                                             <label class="v2_label">Street Name Suffix</label>
                                            <div class="v2_field"><input type="text" name="Supply[street_name_suffix][{{index}}]" value="" class="form-control"/></div>
                                        </div>
                                        <div class="col-xs-4 strtType poRltv">
                                             <label class="v2_label">Street Type</label>
                                            <div class="v2_field"><input type="text" value="" name="Supply[street_type][{{index}}]" class="street_type form-control"></div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-4">
                                             <label class="v2_label">Suburb*</label>
                                            <div class="v2_field"><input type="text" name="Supply[suburb][{{index}}]" value="" class="form-control"/></div>
                                        </div>
                                        <div class="col-xs-4">
                                             <label class="v2_label">Postcode*</label>
                                            <div class="v2_field"><input type="text" name="Supply[postcode][{{index}}]" value="" class="form-control"/></div>
                                        </div>
                                        <div class="col-xs-4">
                                             <label class="v2_label">State*</label>
                                            <div class="v2_field"><select class="selectSupplyState form-control" name="Supply[state][{{index}}]">
                                                <option value=''>Select</option>
                                                <option value="QLD">QLD</option>
                                                <option value="VIC">VIC</option>
                                                <option value="NSW">NSW</option>
                                                <option value="SA">SA</option>
                                                <option value="WA">WA</option>
                                                <option value="NT">NT</option>
                                                <option value="ACT">ACT</option>
                                                <option value="NZ">NZ</option>
                                                <option value="TAS">TAS</option>
                                            </select></div>
                                        </div>
                                    </div>
                                    <div class="form-group yes-no-btn row nmiacqret">
                                        <div class="col-xs-12 ">
                                             <label class="v2_label v2">NMI Acquisition/Retention</label>
                                            <input type="hidden" name="Supply[nmi_acq_ret][{{index}}]" class="supplynmiacqret" value="-1">
                                            <button type="button" onclick="nmiacqret(0, this)" class="butn supplynmiacqretbtn supplynmiacq checkbutton">Acquisition</button>
                                            <button type="button" onclick="nmiacqret(1, this)" class="butn supplynmiacqretbtn supplynmiret checkbutton">Retention</button>
                                        </div>
                                    </div>
                                    <div class="form-group yes-no-btn row mirnacqret">
                                        <div class="col-xs-12 ">
                                             <label class="v2_label v2">MIRN Acquisition/Retention</label>
                                            <input type="hidden" name="Supply[mirn_acq_ret][{{index}}]" class="supplymirnacqret" value="-1">
                                            <button type="button" onclick="mirnacqret(0, this)" class="butn supplymirnacqretbtn supplymirnacq checkbutton">Acquisition</button>
                                            <button type="button" onclick="mirnacqret(1, this)" class="butn supplymirnacqretbtn supplymirnret checkbutton">Retention</button>
                                        </div>
                                    </div>
                                    <div class="form-group yes-no-btn row display_hide understand_transfer_retention" data-id="{{index}}">
                                        <div class="col-xs-12 required-yes">
                                            <input type="hidden" name="Supply[transfer_retention][{{index}}]" value="-1">
                                             <label class="v2_label v2">Please confirm you understand these changes to your account will be updated when <?php echo $plan['Plan']['retailer']; ?> approve and update your details in their system.​</label>
                                            <button class="butn transfer_retention_{{index}} checkbutton">No</button>
                                            <button class="butn transfer_retention_{{index}} checkbutton">Yes</button>
                                        </div>
                                    </div>
                                    <div class="form-group yes-no-btn row display_hide understand_transfer_retention_sold">
                                        <div class="col-xs-12">
                                             <label class="v2_label">The offer I have sold you today is based on the address and information you have provided me. You will remain with your current retailer for <?php echo $plan['Plan']['package'];?> but I need to let you know this may not be the cheapest option available to you from our panel.​</label>
                                        </div>
                                    </div>
                                    <div class="form-group display_hide understand_transfer_retention_retain">
                                        <div class="read-msg bgred"><div class="inner">
                                            <h5>PLEASE INFORM CUSTOMER </h5>
                                            <p>Sorry, we are unable to proceed with the signup as we are unauthorised to retain customers for this retailer </p>
                                        </div></div>
                                    </div>
                                    <div class="form-group row mirnisdifferent">
                                        <div class="col-xs-12 checkbox-sec">
                                            <input type="checkbox" name="Supply[mirn_is_different][{{index}}]" id="mirn_is_different_{{index}}" onclick="checkSupplyMIRNAddress(this);" value="1"/>
                                            <label class="checkbox-clone" for="mirn_is_different_{{index}}">&nbsp;</label>
                                            <span onclick="checkSupplyMIRNAddressSpan(this)" style="color: #F00;"><strong>CHECK IF MIRN ADDRESS IS DIFFERENT</strong>
                                                <i>IF MIRN address is different from supply address, read the MIRN address & advise customer this is how it is displayed in the national grid then enter MIRN Address as per MIRN look up.</i></span>
                                        </div>
                                    </div>
                                    <div class="mirnaddress">
                                        <div class="form-group row">
                                            <h3>MIRN Address</h3>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-xs-12 checkbox-sec">
                                                <input id="supply_no_street_number_mirn_{{index}}" type="checkbox" name="Supply[no_street_number_mirn][{{index}}]" value="1">
                                                <label class="checkbox-clone" for="supply_no_street_number_mirn_{{index}}">&nbsp;</label>
                                                <span>Address Has no Street Number</span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                                 <label class="v2_label">Unstructured MIRN Address</label>
                                                <div class="v2_field"><select class="supplyunstructuredmirnaddress form-control" name="Supply[unstructured_mirn_address][{{index}}]">
                                                    <option value=''>Select</option>
                                                    <option value="Yes">Yes</option>
                                                    <option value="Has Both">Has Both</option>
                                                    <option value="No">No</option>
                                                    <option value="N/A">N/A</option>
                                                </select></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-xs-6">
                                                 <label class="v2_label">Unit</label>
                                                <div class="v2_field"><input type="text" name="Supply[unit_mirn][{{index}}]" value="" class="supplyunitmirn form-control"/></div>
                                            </div>
                                            <div class="col-xs-6">
                                                 <label class="v2_label">Unit Type</label>
                                                <select class="supplyunittypemirn form-control" name="Supply[unit_type_mirn][{{index}}]">
                                                    <option value=''>Select</option>
                                                    <option value="Apartment">Apartment</option>
                                                    <o<div class="v2_field">ption value="Flat">Flat</option>
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
                                                </select></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-xs-4">
                                                 <label class="v2_label">Lot</label>
                                                <div class="v2_field"><input type="text" name="Supply[lot_mirn][{{index}}]" value="" class="form-control"/></div>
                                            </div>
                                            <div class="col-xs-4">
                                                 <label class="v2_label">Floor</label>
                                                <div class="v2_field"><input type="text" name="Supply[floor_mirn][{{index}}]" value="" class="form-control"/></div>
                                            </div>
                                            <div class="col-xs-4">
                                                 <label class="v2_label">Floor Type</label>
                                                <div class="v2_field"><select class="supplyfloortype form-control" name="Supply[floor_type_mirn][{{index}}]">
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
                                                </select></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                                 <label class="v2_label">Building Name</label>
                                                <div class="v2_field"><input type="text" name="Supply[building_name_mirn][{{index}}]" value="" class="form-control"/></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-xs-4">
                                                 <label class="v2_label">Street Number</label>
                                                <div class="v2_field"><input type="text" name="Supply[street_number_mirn][{{index}}]" value="" class="form-control"/></div>
                                            </div>
                                            <div class="col-xs-4">
                                                 <label class="v2_label">Street Number Suffix</label>
                                                <div class="v2_field"><input type="text" name="Supply[street_number_suffix_mirn][{{index}}]" value="" class="form-control"/></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-xs-4">
                                                 <label class="v2_label">Street Name</label>
                                                <div class="v2_field"><input type="text" name="Supply[street_name_mirn][{{index}}]" value="" class="form-control"/></div>
                                            </div>
                                            <div class="col-xs-4">
                                                 <label class="v2_label">Street Name Suffix</label>
                                                <div class="v2_field"><input type="text" name="Supply[street_name_suffix_mirn][{{index}}]" value="" class="form-control"/></div>
                                            </div>
                                            <div class="col-xs-4 strtType poRltv">
                                                 <label class="v2_label">Street Type</label>
                                                <div class="v2_field"><input type="text" value="" name="Supply[street_type_mirn][{{index}}]" class="street_type form-control"></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-xs-4">
                                                 <label class="v2_label">Suburb*</label>
                                                <div class="v2_field"><input type="text" name="Supply[suburb_mirn][{{index}}]" value="" class="form-control"/></div>
                                            </div>
                                            <div class="col-xs-4">
                                                 <label class="v2_label">Postcode*</label>
                                                <div class="v2_field"><input type="text" name="Supply[postcode_mirn][{{index}}]" value="" class="form-control"/></div>
                                            </div>
                                            <div class="col-xs-4">
                                                 <label class="v2_label">State*</label>
                                                <div class="v2_field"><select class="selectSupplyMirnState form-control" name="Supply[state_mirn][{{index}}]">
                                                    <option value=''>Select</option>
                                                    <option value="QLD">QLD</option>
                                                    <option value="VIC">VIC</option>
                                                    <option value="NSW">NSW</option>
                                                    <option value="SA">SA</option>
                                                    <option value="WA">WA</option>
                                                    <option value="NT">NT</option>
                                                    <option value="ACT">ACT</option>
                                                    <option value="NZ">NZ</option>
                                                    <option value="TAS">TAS</option>
                                                </select></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group row msatsisdifferent">
                                        <div class="col-xs-12 checkbox-sec">
                                            <input type="checkbox" name="Supply[msats_is_different][{{index}}]" id="msats_is_different{{index}}" onclick="checkSupplyMSATSAddress(this);" value="1"/>
                                            <label class="checkbox-clone" for="msats_is_different{{index}}">&nbsp;</label>
                                            <span onclick="checkSupplyMSATSAddressSpan(this)" style="color: #F00;"><strong>CHECK IF MSATS ADDRESS IS DIFFERENT</strong><i>Advisory - IF MSATS address is different from supply address, read the MSATS address & advise customer this is how it is displayed in the national grid then enter MSATS address correctly.</i></span>
                                        </div>
                                    </div>
                                    <div class="msatsaddress">
                                        <div class="form-group row">
                                            <h3>MSATS Address</h3>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-xs-12 checkbox-sec">
                                                <input id="supply_no_street_number_masts_{{index}}" type="checkbox" name="Supply[no_street_number_masts][{{index}}]" value="1">
                                                <label class="checkbox-clone" for="supply_no_street_number_masts_{{index}}">&nbsp;</label>
                                                <span>Address Has no Street Number</span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                                 <label class="v2_label">Unstructured MSATS Address</label>
                                                <div class="v2_field"><select class="supplyunstructuredmsatsaddress form-control" name="Supply[unstructured_msats_address][{{index}}]">
                                                    <option value=''>Select</option>
                                                    <option value="Yes">Yes</option>
                                                    <option value="Has Both">Has Both</option>
                                                    <option value="No">No</option>
                                                    <option value="N/A">N/A</option>
                                                </select></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-xs-6">
                                                 <label class="v2_label">Unit</label>
                                                <div class="v2_field"><input type="text" name="Supply[unit_msats][{{index}}]" value="" class="supplyunitmsats form-control"/></div>
                                            </div>
                                            <div class="col-xs-6">
                                                 <label class="v2_label">Unit Type</label>
                                                <div class="v2_field"><select class="supplyunittypemsats form-control" name="Supply[unit_type_msats][{{index}}]">
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
                                                </select></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-xs-4">
                                                 <label class="v2_label">Lot</label>
                                                <div class="v2_field"><input type="text" name="Supply[lot_msats][{{index}}]" value="" class="form-control"/></div>
                                            </div>
                                            <div class="col-xs-4">
                                                 <label class="v2_label">Floor</label>
                                                <div class="v2_field"><input type="text" name="Supply[floor_msats][{{index}}]" value="" class="form-control"/></div>
                                            </div>
                                            <div class="col-xs-4">
                                                 <label class="v2_label">Floor Type</label>
                                                <div class="v2_field"><select class="supplyfloortype form-control" name="Supply[floor_type_msats][{{index}}]">
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
                                                </select></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-xs-12">
                                                 <label class="v2_label">Building Name</label>
                                                <div class="v2_field"><input type="text" name="Supply[building_name_msats][{{index}}]" value="" class="form-control"/></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-xs-4">
                                                 <label class="v2_label">Street Number</label>
                                                <div class="v2_field"><input type="text" name="Supply[street_number_msats][{{index}}]" value="" class="form-control"/></div>
                                            </div>
                                            <div class="col-xs-4">
                                                 <label class="v2_label">Street Number Suffix</label>
                                                <div class="v2_field"><input type="text" name="Supply[street_number_suffix_msats][{{index}}]" value="" class="form-control"/></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-xs-4">
                                                 <label class="v2_label">Street Name</label>
                                                <div class="v2_field"><input type="text" name="Supply[street_name_msats][{{index}}]" value="" class="form-control"/></div>
                                            </div>
                                            <div class="col-xs-4">
                                                 <label class="v2_label">Street Name Suffix</label>
                                                <div class="v2_field"><input type="text" name="Supply[street_name_suffix_msats][{{index}}]" value="" class="form-control"/></div>
                                            </div>
                                            <div class="col-xs-4 strtType poRltv">
                                                 <label class="v2_label">Street Type</label>
                                                <div class="v2_field"><input type="text" value="" name="Supply[street_type_msats][{{index}}]" class="street_type form-control"></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-xs-4">
                                                 <label class="v2_label">Suburb*</label>
                                                <div class="v2_field"><input type="text" name="Supply[suburb_msats][{{index}}]" value="" class="form-control"/></div>
                                            </div>
                                            <div class="col-xs-4">
                                                 <label class="v2_label">Postcode*</label>
                                                <div class="v2_field"><input type="text" name="Supply[postcode_msats][{{index}}]" value="" class="form-control"/></div>
                                            </div>
                                            <div class="col-xs-4">
                                                 <label class="v2_label">State*</label>
                                                <div class="v2_field"><select class="selectSupplyState form-control" name="Supply[state_msats][{{index}}]">
                                                    <option value=''>Select</option>
                                                    <option value="QLD">QLD</option>
                                                    <option value="VIC">VIC</option>
                                                    <option value="NSW">NSW</option>
                                                    <option value="SA">SA</option>
                                                    <option value="WA">WA</option>
                                                    <option value="NT">NT</option>
                                                    <option value="ACT">ACT</option>
                                                    <option value="NZ">NZ</option>
                                                    <option value="TAS">TAS</option>
                                                </select></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group bggrey row aglsupply">
                                        <div class="col-xs-12">
                                             <label class="v2_label v2">MSATS/MIRN Address</label>
                                            <div class="v2_field"><input name="Supply[msats_mirn_address][{{index}}]" type="text" value="" class="form-control"/></div>
                                        </div>
                                    </div>
                                    <div class="form-group yes-no-btn row">
                                        <div class="col-xs-12 ">
                                             <label class="v2_label v2">Tenant/Owner</label>
                                            <input type="hidden" name="Supply[tenant_owner][{{index}}]" class="supplytenantowner" value="-1">
                                            <button type="button" onclick="tenantowner(0, this)" class="butn tenantowner checkbutton">Tenant</button>
                                            <button type="button" onclick="tenantowner(1, this)" class="butn tenantowner checkbutton">Owner</button>
                                        </div>
                                    </div>
                                </script>
                                <?php if ($plan['Plan']['retailer'] == 'Alinta Energy'):?>
                                    <div class="form-group row display_hide multisite">
                                        <div class="col-xs-12">
                                            <div class="read-msg bgylw"><div class="inner">
                                                <p>The terms of supply to each premise are governed by individual contracts. Your rights and obligations, and ours, apply to each one of those premises independently without affecting any other premises.</p>
                                            </div></div>
                                        </div>
                                    </div>
                                <?php endif;?>
                                <div class="form-group yes-no-btn">
                                    <button type="button" class="butn repeater-add">Add Supply Address</button>
                                </div>
                            </div>
                            <div class="form-group yes-no-btn row">
                                <div class="col-xs-12">
                                     <label class="v2_label v2">Is your billing address different?</label>
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
                                        <div class="col-xs-12"> <label class="v2_label">PO Box</label><div class="v2_field"><input type="text" value="" name="SupplySecondary[po_box]" class="form-control"/></div></div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-12 checkbox-sec">
                                            <input id="supply_secondary_no_street_number" type="checkbox" name="SupplySecondary[no_street_number]" value="1">
                                            <label class="checkbox-clone" for="supply_secondary_no_street_number">&nbsp;</label>
                                            <span>Address Has no Street Number</span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                             <label class="v2_label">Unit</label>
                                            <div class="v2_field"><input type="text" value="" name="SupplySecondary[unit]" class="supplysecondaryunit form-control"/></div>
                                        </div>
                                        <div class="col-xs-6">
                                             <label class="v2_label">Unit Type</label>
                                            <div class="v2_field"><select class="supplysecondaryunittype form-control" name="SupplySecondary[unit_type]">
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
												</select></div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-4">
                                             <label class="v2_label">Lot</label>
                                            <div class="v2_field"><input type="text" value="" name="SupplySecondary[lot]" class="form-control"/></div>
                                        </div>
                                        <div class="col-xs-4">
                                             <label class="v2_label">Floor</label>
                                            <div class="v2_field"><input type="text" value="" name="SupplySecondary[floor]" class="form-control"/></div>
                                        </div>
                                        <div class="col-xs-4">
                                             <label class="v2_label">Floor Type</label>
                                            <div class="v2_field"><select class="supplyfloortype form-control" name="SupplySecondary[floor_type]"> 
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
												</select></div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-12">
                                             <label class="v2_label">Building Name</label>
                                            <div class="v2_field"><input type="text" value="" name="SupplySecondary[building_name]" class="form-control"/></div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-6">
                                             <label class="v2_label">Street Number</label>
                                            <div class="v2_field"><input type="text" value="" name="SupplySecondary[street_number]" class="form-control"/></div>
                                        </div>
                                        <div class="col-xs-6">
                                             <label class="v2_label">Street Number Suffix</label>
                                            <div class="v2_field"><input type="text" value="" name="SupplySecondary[street_number_suffix]" class="form-control"/></div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-4">
                                             <label class="v2_label">Street Name</label>
                                            <div class="v2_field"><input type="text" value="" name="SupplySecondary[street_name]" class="form-control"/></div>
                                        </div>
                                        <div class="col-xs-4">
                                             <label class="v2_label">Street Name Suffix</label>
                                            <div class="v2_field"><input type="text" value="" name="SupplySecondary[street_name_suffix]" class="form-control"/></div>
                                        </div>
                                        <div class="col-xs-4">
                                             <label class="v2_label">Street Type</label>
                                            <div class="v2_field"><input type="text" value="" name="SupplySecondary[street_type]" class="street_type form-control"></div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-xs-4">
                                             <label class="v2_label">Suburb*</label>
                                            <div class="v2_field"><input class="secondary_input form-control" type="text" value="<?php echo $user['suburb']; ?>" name="SupplySecondary[suburb]"/></div>
                                        </div>
                                        <div class="col-xs-4">
                                             <label class="v2_label">Postcode*</label>
                                            <div class="v2_field"><input class="secondary_input form-control" type="text" value="<?php echo $user['postcode']; ?>" name="SupplySecondary[postcode]"/></div>
                                        </div>
                                        <div class="col-xs-4">
                                             <label class="v2_label">State*</label>
                                            <div class="v2_field"><select class="selectSupplySecondaryState selectpicker show-menu-arrow" name="SupplySecondary[state]">
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
                                                    <?php if ($plan['Plan']['state'] == 'Western Australia'): ?>selected="selected"<?php endif; ?>
                                                    value="WA">WA
                                                </option>
                                                <option
                                                    <?php if ($plan['Plan']['state'] == 'Northern Territory'): ?>selected="selected"<?php endif; ?>
                                                    value="NT">NT
                                                </option>
                                                <option
                                                    <?php if ($plan['Plan']['state'] == 'Australian Capital Territory'): ?>selected="selected"<?php endif; ?>
                                                    value="ACT">ACT
                                                </option>
                                                <option
                                                    <?php if ($plan['Plan']['state'] == 'New Zealand'): ?>selected="selected"<?php endif; ?>
                                                    value="NZ">NZ
                                                </option>
                                                <option
                                                    <?php if ($plan['Plan']['state'] == 'Tasmania'): ?>selected="selected"<?php endif; ?>
                                                    value="TAS">TAS
                                                </option>
												</select></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="read-msg bgred"><div class="inner">
                                    <p><strong>WAIT!! Nobody likes a fix up - Ensure you have covered all address sections in full & correctly</strong></p>
                                </div></div>
                            </div>
                            <div class="submit-btnotr text-right"><input onclick="checkSupplyFields()" type="submit" class="submit-btn checkSupplyFields" value="Mark section as Completed"/></div>
                        </div>
                    </form>
                </div>
			
                <h3 class="form-hdg">Billing Information<i class="fa fa-angle-up" aria-hidden="true"></i><i class="fa fa-angle-down" aria-hidden="true"></i></h3>
            	<div class="form clearfix collapse" id="form-collapse7">
                    <form action="" method="post" class="signup_billinginfo" name="signup_billinginfo">
                        <div class="form-data">
                            <?php if ($plan['Plan']['res_sme'] == 'RES'):?>
                                <?php if ($plan['Plan']['retailer'] == 'AGL' && $plan['Plan']['package'] == 'Dual'):?>
                                <div class="form-group yes-no-btn row">
                                    <div class="col-xs-12">
                                        <input type="hidden" name="Billing[carbon_neutral_consent]" value="-1">
                                         <label class="v2_label v2">(Customer Name) You now have the option to choose to go Carbon Neutral on AGL'S Residential electricity plans for $1 per week and AGL'S Residential Gas plans for 50 cents per week. Would you like to opt into that now?</label>
                                        <button class="butn billing_carbon_neutral_consent checkbutton">No</button>
                                        <button class="butn billing_carbon_neutral_consent checkbutton">Yes</button>
                                    </div>
                                </div>
                                <?php endif;?>
                                <?php if (in_array($plan['Plan']['retailer'], array('AGL', 'Powerdirect')) && $plan['Plan']['package'] == 'Elec'):?>
                                <div class="form-group yes-no-btn row">
                                    <div class="col-xs-12">
                                        <input type="hidden" name="Billing[carbon_neutral_consent]" value="-1">
                                         <label class="v2_label v2">(Customer Name) You now have the option to choose to go Carbon Neutral on <?php echo $plan['Plan']['retailer'];?> Residential electricity plans for $1 per week. Would you like to opt into that now?</label>
                                        <button class="butn billing_carbon_neutral_consent checkbutton">No</button>
                                        <button class="butn billing_carbon_neutral_consent checkbutton">Yes</button>
                                    </div>
                                </div>
                                <?php endif;?>
                                <?php if (in_array($plan['Plan']['retailer'], array('AGL', 'Powerdirect')) && $plan['Plan']['package'] == 'Gas'):?>
                                <div class="form-group yes-no-btn row">
                                    <div class="col-xs-12">
                                        <input type="hidden" name="Billing[carbon_neutral_consent]" value="-1">
                                         <label class="v2_label v2">(Customer Name), you now have the option to choose to go Carbon Neutral on AGL'S Residential Gas plans for 50 cents per week. Would you like to opt into that now?</label>
                                        <button class="butn billing_carbon_neutral_consent checkbutton">No</button>
                                        <button class="butn billing_carbon_neutral_consent checkbutton">Yes</button>
                                    </div>
                                </div>
                                <?php endif;?>
                            <?php else:?>
                                <?php if ($plan['Plan']['retailer'] == 'AGL'):?>
                                <?php if ($plan['Plan']['package'] == 'Dual'):?>
                                <div class="form-group yes-no-btn row">
                                    <div class="col-xs-12">
                                        <input type="hidden" name="Billing[carbon_neutral_consent]" value="-1">
                                         <label class="v2_label v2">(Customer Name), you now have the option to choose to go Carbon Neutral on Retailer’s Name Small Business electricity plans for $4 per week and AGL's Small Business Gas plans for $7 per week. Would you like to opt into that now?</label>
                                        <button class="butn billing_carbon_neutral_consent checkbutton">No</button>
                                        <button class="butn billing_carbon_neutral_consent checkbutton">Yes</button>
                                    </div>
                                </div>
                                <?php elseif ($plan['Plan']['package'] == 'Elec'):?>
                                <div class="form-group yes-no-btn row">
                                    <div class="col-xs-12">
                                        <input type="hidden" name="Billing[carbon_neutral_consent]" value="-1">
                                         <label class="v2_label v2">(Customer Name), you now have the option to choose to go Carbon Neutral on AGL's Small Business electricity plans for $4 per week. Would you like to opt into that now?</label>
                                        <button class="butn billing_carbon_neutral_consent checkbutton">No</button>
                                        <button class="butn billing_carbon_neutral_consent checkbutton">Yes</button>
                                    </div>
                                </div>
                                <?php elseif ($plan['Plan']['package'] == 'Gas'):?>
                                <div class="form-group yes-no-btn row">
                                    <div class="col-xs-12">
                                        <input type="hidden" name="Billing[carbon_neutral_consent]" value="-1">
                                         <label class="v2_label v2">(Customer Name), you now have the option to choose to go Carbon Neutral on AGL'S Small Business Gas plans for $7 per week. Would you like to opt into that now?</label>
                                        <button class="butn billing_carbon_neutral_consent checkbutton">No</button>
                                        <button class="butn billing_carbon_neutral_consent checkbutton">Yes</button>
                                    </div>
                                </div>
                                <?php endif;?>
                                <?php endif;?>
                            <?php endif;?>
                            <?php if (in_array($plan['Plan']['retailer'], array('AGL', 'Powerdirect'))):?>
                                <div class="form-group yes-no-btn row understand_accessing">
                                    <div class="col-xs-12 required-yes">
                                        <input type="hidden" name="Billing[understand_accessing]" value="-1">
                                         <label class="v2_label v2">Do you understand and agree to <?php echo $plan['Plan']['retailer'];?> accessing your usage, meter and related information from the market operator and your distributor, to arrange the transfer of your energy to <?php echo $plan['Plan']['retailer'];?>?</label>
                                        <button class="butn billing_understand_accessing checkbutton">No</button>
                                        <button class="butn billing_understand_accessing checkbutton">Yes</button>
                                    </div>
                                </div>
                            <?php endif;?>
                            <?php if ($plan['Plan']['retailer'] == 'Origin Energy'):?>
                            <div class="form-group">
                                <div class="read-msg">
                                    <h5>Please read verbatim to customer:</h5>
                                    <p>Your meter details will be verified with the distributor, and your charges may change if any details are incorrect. Origin will confirm these once they’ve identified your meter type and processed your application and will notify you by letter about any such change.</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="read-msg">
                                    <p>If you or someone you know is in a family domestic violence situation Origin have support available, you can call Origin directly on 13 24 61 for further information and a confidential conversation.</p>
                                </div>
                            </div>
                            <?php endif;?>
                            <?php if ($plan['Plan']['retailer'] == 'Origin Energy' && $plan['Plan']['package'] == 'Elec'):?>
                            <div class="form-group yes-no-btn row origin_lpg_property">
                                <div class="col-xs-12">
                                    <input type="hidden" name="Supply[origin_lpg_property]" value="-1" class="origin_lpg_property">
                                     <label class="v2_label v2">Is there LPG at the property?</label>
                                    <button class="butn billing_origin_lpg_property checkbutton" onclick="origin_lpg_property(0)">No</button>
                                    <button class="butn billing_origin_lpg_property checkbutton" onclick="origin_lpg_property(1)">Yes</button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="read-msg">
                                    <p>I'll just check if Origin services your area with LPG</p>
                                </div>
                            </div>
                            <div class="form-group row display_hide lpg_available">
                                 <label class="v2_label">Origin can provide an LPG service in the area, I can organise for an Origin LPG specialist to give you a call to discuss this further. What time best suits you?</label>
                                <div class="col-xs-4">
                                    <input type="text" name="Supply[lpg_date]" id="lpg_date" value="" placeholder=""/>
                                </div>
                            </div>
                            <div class="form-group row display_hide lpg_not_available">
                                <div class="read-msg">
                                    <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                    <h5>READ VERBATIM TO CUSTOMER </h5>
                                    <p>Unfortunately, Origin doesn't deliver to your area</p>
                                </div>
                            </div>
                            <?php endif;?>
                            <div class="form-group yes-no-btn row display_hide billingallowactewagluse">
                                <div class="col-xs-12">
                                    <input type="hidden" name="Billing[allow_actewagl_use]" value="-1">
                                     <label class="v2_label v2">(Marketing) Do you allow ActewAGL to use and disclose your personal information to provide you with special offers from time-to-time?</label>
                                    <button class="butn billing_allow_actewagl_use checkbutton">No</button>
                                    <button class="butn billing_allow_actewagl_use checkbutton">Yes</button>
                                </div>
                            </div>
                            <div class="form-group display_hide billing_powershop_fields">
                                <div class="read-msg">
                                    <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                    <h5>READ VERBATIM TO CUSTOMER </h5>
                                    <p>Your agreement starts at the end of the cooling off period.</p>
                                </div>
                            </div>
                            <div class="billing_EA_fields display_hide" style="margin-top: 5px;">
                                <div class="form-group display_hide EA_VIC_DUAL">
                                    <div class="read-msg bgylw"><div class="inner">
                                        <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                        <h5>READ VERBATIM TO CUSTOMER </h5>
                                        <p>You agree to be billed monthly if your local distributor sends us monthly meter reads. If we don't receive monthly reads, we will send you a bill at least once every 3 months for electricity and/or at least once every 2 months for gas</p>
                                    </div></div>
                                </div>
                                <div class="form-group display_hide EA_VIC_ELEC">
                                    <div class="read-msg bgylw"><div class="inner">
                                        <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                        <h5>READ VERBATIM TO CUSTOMER </h5>
                                        <p>You agree to be billed monthly if your local distributor sends us monthly meter reads. If we don't receive monthly reads, we will send you a bill at least once every 3 months for electricity</p>
                                    </div></div>
                                </div>
                                <div class="form-group display_hide EA_VIC_GAS">
                                    <div class="read-msg bgylw"><div class="inner">
                                        <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                        <h5>READ VERBATIM TO CUSTOMER </h5>
                                        <p>You agree to be billed monthly if your local distributor sends us monthly meter reads. If we don't receive monthly reads, we will send you a bill at least once every 2 months for gas</p>
                                    </div></div>
                                </div>
                                <div class="form-group yes-no-btn row display_hide eabillinghearfees">
                                    <div class="col-xs-12">
                                        <input type="hidden" name="Billing[hear_fees_charges_now]" value="-1" class="BillingHearFeesChargesNow">
                                         <label class="v2_label v2">Payment fees and charges will be detailed in your Welcome pack. Would you like to hear these now?</label>
                                        <button class="butn hear_fees_charges_now_EA checkbutton" onclick="hearfeeschargesnow(0)">No</button>
                                        <button class="butn hear_fees_charges_now_EA checkbutton" onclick="hearfeeschargesnow(1)">Yes</button>
                                    </div>
                                </div>
                                <div class="read-msg yes-no-btn display_hide hearfeeschargesnowyes">
                                    <a class="butn" href="https://docs.google.com/spreadsheets/d/1kaBmU3I_pCT_GBqRe81Tpfowzi8UZLF9h2Txz3o_338/edit#gid=0" target="_blank">Show Fees PDF</a>
                                </div>
                                <div class="read-msg display_hide eabillingmsg">
                                    <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                    <h5>READ VERBATIM TO CUSTOMER </h5>
                                    <p>The best way to avoid these fees is by setting up direct debit from a credit card or bank account, payment via cheque or BPAY from a bank account. EnergyAustralia can help you set this up once you receive your confirmation pack.</p>
                                </div>
                            </div>
                            <div class="billing_default_fields display_hide">
                                <div class="form-group yes-no-btn row e_billing" style="padding-top:20px">
                                    <div class="col-xs-12" id="e_billing_field">
                                        <input type="hidden" name="Billing[e_billing]" value="-1">
                                         <label class="v2_label v2">Do you want to register for e-billing?</label>
                                        <button class="butn billing_e_billing checkbutton" onclick="ebilling(0)">No</button>
                                        <button class="butn billing_e_billing checkbutton" onclick="ebilling(1)">Yes</button>
                                    </div>
                                </div>
                                <div class="form-group row display_hide howlonglivedmomentum">
                                    <div class="col-xs-12">
                                         <label class="v2_label">How long have you lived at this property?</label>
                                        <div class="v2_field"><select class="selecthowlonglived selectpicker show-menu-arrow" name="Billing[how_long_lived]">
                                            <option value="">Select</option>
                                            <option value="Less than 6 months">Less than 6 months</option>
                                            <option value="6-12 months">6-12 months</option>
                                            <option value="1-2 years">1-2 years</option>
                                            <option value="2-5 years">2-5 years</option>
                                            <option value="5+ years">5+ years</option>
                                        </select></div>
                                    </div>
                                </div>
                                <div class="read-msg bgylw display_hide ebillingemail"><div class="inner">
                                    <label style="color:#F00">Please fill in a valid email address in the Contact Details section and try again.</label>
                                </div></div>
                                <div class="read-msg display_hide nextbusinessebillingno">
                                    <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                    <h5>READ VERBATIM TO CUSTOMER </h5>
                                    <p>All bills and communication will be sent via email</p>
                                </div>
                                <div class="read-msg display_hide understand_acquisition_nextbusiness">
                                    <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                    <h5>READ VERBATIM TO CUSTOMER </h5>
                                    <p>You agree to transfer from your current retailer for your electricity account to Next Business Energy for a supply period of 24 months. (There is no early termination fee for small customers)</p>
                                </div>
                                <div class="form-group yes-no-btn row display_hide e_billing_momentum">
                                    <div class="col-xs-12">
                                        <input type="hidden" name="Billing[e_billing_momentum]" value="-1">
                                         <label class="v2_label v2">Would you like to receive your bills and other notices, like disconnection warnings and price change notifications, by email?</label>
                                        <button class="butn billing_e_billing_momentum checkbutton">No</button>
                                        <button class="butn billing_e_billing_momentum checkbutton">Yes</button>
                                    </div>
                                </div>
                                <div class="form-group yes-no-btn row display_hide electronic">
                                    <div class="col-xs-12">
                                        <input type="hidden" name="Billing[electronic]" value="-1">
                                         <label class="v2_label v2">Would you like to receive marketing information from <?php echo $plan['Plan']['retailer'];?> about special offers or new products?</label>
                                        <button class="butn billing_electronic checkbutton">No</button>
                                        <button class="butn billing_electronic checkbutton">Yes</button>
                                    </div>
                                </div>
                                <div class="form-group display_hide checkspam">
                                    <div class="read-msg bgylw"><div class="inner">
                                        <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                        <h5>READ VERBATIM TO CUSTOMER </h5>
                                        <p>(State customer name) if you have not received your Plan Confirmation in 5 business days please make sure you have checked your email SPAM or junk folders as these communications may be filtered by your email provider.</p>
                                    </div></div>
                                </div>
                                <div class="display_hide sumobilling">
                                    <div class="form-group yes-no-btn row display_hide sumovalidemail">
                                        <div class="col-xs-12">
                                            <input type="hidden" name="Billing[how_receive_welcome_pack]" value="-1">
                                             <label class="v2_label v2">Your welcome pack will be sent to you via email. Would you like to receive your bills and other notices by email too?</label>
                                            <button class="butn billing_how_receive_welcome_pack checkbutton" onclick="howreceivewelcomepack(0)">No</button>
                                            <button class="butn billing_how_receive_welcome_pack checkbutton" onclick="howreceivewelcomepack(1)">Yes</button>
                                        </div>
                                    </div>
                                    <div class="read-msg display_hide sumonoemail">
                                        <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                        <h5>READ VERBATIM TO CUSTOMER </h5>
                                        <p>As you don't have an email your Welcome pack and bills will be default to Postal. We will send this information to the postal address you have provided.</p>
                                    </div>
                                    <div class="read-msg display_hide paperbill">
                                        <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                        <h5>READ VERBATIM TO CUSTOMER </h5>
                                        <p>There is a $3.10 fee for paper bills per fuel. <br>That is $37.20 per year for electricity and if applicable $18.60 for gas.</p>
                                    </div>
                                    <div class="form-group yes-no-btn row display_hide sumoaustraliapost">
                                        <div class="col-xs-12">
                                            <input type="hidden" name="Billing[australia_post_sumo]" value="-1">
                                             <label class="v2_label v2">Do you pay your bills at Australia Post?</label>
                                            <button class="butn australia_post_sumo checkbutton" onclick="australiapostsumo(0)">No</button>
                                            <button class="butn australia_post_sumo checkbutton" onclick="australiapostsumo(1)">Yes</button>
                                        </div>
                                    </div>
                                    <div class="read-msg display_hide sumoaustraliapostyes">
                                        <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                        <h5>READ VERBATIM TO CUSTOMER </h5>
                                        <p>There is a $2 fee per bill for paying over the counter at Australia Post.<br>
                                            That would be<br>
                                            $24 per year for your electricity account if you pay every bill at Australia Post.<br>
                                            and If applicable, $12 per year for your gas account if you pay every bill at Australia Post. </p>
                                    </div>
                                    <div class="form-group yes-no-btn row display_hide sumomarketingoptout">
                                        <div class="col-xs-12">
                                            <input type="hidden" name="Billing[marketing_opt_out_sumo]" value="-1">
                                             <label class="v2_label v2">Would you like Sumo Power to keep you updated with other great products and offers, from Sumo Power and other suppliers?</label>
                                            <button class="butn marketing_opt_out_sumo checkbutton" onclick="marketingoptoutsumo(0)">No</button>
                                            <button class="butn marketing_opt_out_sumo checkbutton" onclick="marketingoptoutsumo(1)">Yes</button>
                                        </div>
                                    </div>
                                    <div class="read-msg display_hide marketingoptoutsumoyes">
                                        <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                        <h5>READ VERBATIM TO CUSTOMER </h5>
                                        <p>If you change your mind, you can opt out at any time.</p>
                                    </div>
                                </div>
                                <?php if (in_array($plan['Plan']['retailer'], array('AGL', 'Momentum', 'Alinta Energy'))):?>
                                    <div class="form-group yes-no-btn row">
                                        <div class="col-xs-12" id="direct_debit_field">
                                            <input type="hidden" name="Billing[direct_debit]" value="-1">
                                             <label class="v2_label v2">Do you want direct debit on your account?</label>
                                            <button class="butn billing_direct_debit checkbutton">No</button>
                                            <button class="butn billing_direct_debit checkbutton">Yes</button>
                                        </div>
                                    </div>
                                <?php endif;?>
                                <div class="read-msg bgylw display_hide welcomepackpost" style="margin-bottom: 20px;"><div class="inner">
                                    <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                    <h5>READ VERBATIM TO CUSTOMER </h5>
                                    <p>As you do not have an email your welcome pack will be posted to you.</p>
                                </div></div>
                                <div class="form-group yes-no-btn row display_hide marketing_opt_out">
                                    <div class="col-xs-12">
                                        <input type="hidden" name="Billing[marketing_opt_out]" value="-1">
                                         <label class="v2_label v2">Do you want to opt out of marketing material from <?php echo $plan['Plan']['retailer']; ?>?</label>
                                        <button class="butn billing_marketing_opt_out checkbutton">No</button>
                                        <button class="butn billing_marketing_opt_out checkbutton">Yes</button>
                                    </div>
                                </div>
                                <div class="read-msg display_hide lumovicmsg">
                                    <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                    <h5>READ VERBATIM TO CUSTOMER </h5>
                                    <p>Electricity bills will be issued monthly if and when you have an active Smart Meter, otherwise they will be issued quarterly.</p>
                                </div>
                                <div class="read-msg display_hide lumovicmsg2">
                                    <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                    <h5>READ VERBATIM TO CUSTOMER </h5>
                                    <p>Your gas bills will be issued bi-monthly.</p>
                                </div>
                                <div class="read-msg display_hide lumosamsg">
                                    <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                    <h5>READ VERBATIM TO CUSTOMER </h5>
                                    <p>You will receive your bills on a quarterly basis for electricity and/or gas.</p>
                                </div>
                                <div class="read-msg bgylw display_hide lumosmemsg lumocoolingoffperiod"><div class="inner">
                                    <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                    <h5>READ VERBATIM TO CUSTOMER </h5>
                                     <label class="v2_label">Your electricity (and/or gas) account will switch to Lumo Energy as of the next scheduled meter read. Once your Cooling Off Period has completed, LUMO Energy will request for your account. You will receive one last bill from your current provider before switching.</label>
                                </div></div>
                                <div class="form-group yes-no-btn row display_hide ermbilling">
                                    <div class="col-xs-12 required-yes">
                                        <input type="hidden" name="Billing[erm_understand_exit_fee]" value="-1">
                                         <label class="v2_label v2">If you are charged an exit fee from your current retailer ERM Business Energy will reimburse you for this through a credit on your invoice.  However you must provide evidence of being charged an exit fee such as a copy of the invoice. Do you understand this?</label>
                                        <button class="butn billing_erm_understand_exit_fee checkbutton">No</button>
                                        <button class="butn billing_erm_understand_exit_fee checkbutton">Yes</button>
                                    </div>
                                </div>
                                <div class="form-group yes-no-btn row display_hide ermbilling">
                                    <div class="col-xs-12 required-yes">
                                        <input type="hidden" name="Billing[erm_credit_check]" value="-1">
                                         <label class="v2_label v2">This offer is subject to a successful credit check. Do you consent to ERM Business Energy completing a credit check as part of the customer sign-up process?</label>
                                        <button class="butn billing_erm_credit_check checkbutton">No</button>
                                        <button class="butn billing_erm_credit_check checkbutton">Yes</button>
                                    </div>
                                </div>
                                <div class="form-group yes-no-btn row display_hide ermbilling">
                                    <div class="col-xs-12 required-yes">
                                        <input type="hidden" name="Billing[erm_understand_structure]" value="-1">
                                         <label class="v2_label v2">Do you understand that the structure of your tariff may be varied as a result of changes made by your distributor?</label>
                                        <button class="butn billing_erm_understand_structure checkbutton">No</button>
                                        <button class="butn billing_erm_understand_structure checkbutton">Yes</button>
                                    </div>
                                </div>
                                <div class="form-group yes-no-btn row display_hide ermbilling">
                                    <div class="col-xs-12 required-yes">
                                        <input type="hidden" name="Billing[erm_consent_transfer]" value="-1">
                                         <label class="v2_label v2">Do you consent to the transfer of your Electricity account to ERM Business Energy?</label>
                                        <button class="butn billing_erm_consent_transfer checkbutton">No</button>
                                        <button class="butn billing_erm_consent_transfer checkbutton">Yes</button>
                                    </div>
                                </div>
                                <div class="form-group yes-no-btn row display_hide ermbilling">
                                    <div class="col-xs-12">
                                        <input type="hidden" name="Billing[erm_receive_communications]" value="-1">
                                         <label class="v2_label v2">Are you happy to receive marketing communications from ERM Business Energy?</label>
                                        <button class="butn billing_erm_receive_communications checkbutton">No</button>
                                        <button class="butn billing_erm_receive_communications checkbutton">Yes</button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group yes-no-btn row">
                                <div class="col-xs-12">
                                    <input type="hidden" name="Billing[other_family_friends]" value="-1">
                                     <label class="v2_label v2">Do you have any other family or friends I could call to offer a comparison?</label>
                                    <button class="butn other_family_friends checkbutton" onclick="otherfamilyfriends(0)">No</button>
                                    <button class="butn other_family_friends checkbutton" onclick="otherfamilyfriends(1)">Yes</button>
                                </div>
                            </div>
                            <div class="read-msg yes-no-btn display_hide otherfamilyfriendsyes">
                                <a class="butn" href="/admin/customers/referral_program" target="_blank">Open Referral Page</a>
                            </div>
                            <?php if ($plan['Plan']['product_name'] == 'BusinessSaver HC'): ?>
                                <?php if ($plan['Plan']['package'] == 'Dual' || $plan['Plan']['package'] == 'Gas'): ?>
                                    <div class="form-group yes-no-btn row">
                                        <div class="col-xs-12 required-yes">
                                            <input type="hidden" name="Billing[confirm_annual_gas]" value="-1">
                                             <label class="v2_label v2">Can you please confirm that your annual cost for gas is higher or equal to $5500 per annum or use higher or equal to 20GJ per annum?</label>
                                            <button class="butn confirm_annual_gas checkbutton" onclick="confirmannualgas(0)">No</button>
                                            <button class="butn confirm_annual_gas checkbutton" onclick="confirmannualgas(1)">Yes</button>
                                        </div>
                                        <div class="read-msg yes-no-btn display_hide confirmannualgasno">
                                            <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                            <h5>READ VERBATIM TO CUSTOMER </h5>
                                            <p>Unfortunately you are not eligible for this offer.</p>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endif;?>
                            <div class="submit-btnotr text-right"><input onclick="checkBillingInformation()" type="submit" class="submit-btn" value="Mark section as Completed"/>
                            </div>
                        </div>
                    </form>
                </div>
			
                <h3 class="form-hdg MoveInDetails">Move In Details<i class="fa fa-angle-up" aria-hidden="true"></i><i class="fa fa-angle-down" aria-hidden="true"></i></h3>
            	<div class="form clearfix collapse" id="form-collapse8">
                    <form action="" method="post" class="signup_moveindetail" name="signup_moveindetail">
                        <div class="form-data">
                            <div class="form-group row">
                                <div class="col-xs-12 table-move-in-info"></div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                     <label class="v2_label">Move In Date</label>
                                    <div class="v2_field"><input type="text" class="calender-icon form-control" id="move_in_date" name="MoveInDetail[date]"  value="<?php echo (isset($user['step1']['move_in_date']) && $user['step1']['move_in_date']) ? $user['step1']['move_in_date'] : '' ?>"/></div>
                                </div>
                                <div class="col-xs-6">
                                    <div class="col-xs-6 pdr0">
                                         <label class="v2_label">&nbsp;</label>
                                        <button class="movein-fees-btn">Move-In Fees</button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row display_hide sumomovein">
                                <div class="col-xs-6">
                                     <label class="v2_label">Previous Street Address</label>
                                    <input type="text" name="MoveInDetail[previous_street]"/>
                                </div>
                                <div class="col-xs-6">
                                     <label class="v2_label">Previous Suburb</label>
                                    <input type="text" name="MoveInDetail[previous_suburb]"/>
                                </div>
                            </div>
                            <div class="form-group row display_hide sumomovein">
                                <div class="col-xs-6">
                                     <label class="v2_label">Previous State</label>
                                    <div class="v2_field"><select class="previousstate selectpicker show-menu-arrow form-control" name="MoveInDetail[previous_state]">
                                        <option value="QLD" <?php if ($plan['Plan']['state'] == 'Queensland'): ?>selected="selected"<?php endif; ?>>QLD</option>
                                        <option value="VIC" <?php if ($plan['Plan']['state'] == 'Victoria'): ?>selected="selected"<?php endif; ?>>VIC</option>
                                        <option value="NSW" <?php if ($plan['Plan']['state'] == 'New South Wales'): ?>selected="selected"<?php endif; ?>>NSW</option>
                                        <option value="SA" <?php if ($plan['Plan']['state'] == 'South Australia'): ?>selected="selected"<?php endif; ?>>SA</option>
                                        <option value="WA" <?php if ($plan['Plan']['state'] == 'Western Australia'): ?>selected="selected"<?php endif; ?>>WA</option>
                                        <option value="NT" <?php if ($plan['Plan']['state'] == 'Northern Territory'): ?>selected="selected"<?php endif; ?>>NT</option>
                                        <option value="ACT" <?php if ($plan['Plan']['state'] == 'Australian Capital Territory'): ?>selected="selected"<?php endif; ?>>ACT</option>
                                        <option value="NZ" <?php if ($plan['Plan']['state'] == 'New Zealand'): ?>selected="selected"<?php endif; ?>>NZ</option>
                                        <option value="TAS" <?php if ($plan['Plan']['state'] == 'Tasmania'): ?>selected="selected"<?php endif; ?>>TAS</option>
										</select></div>
                                </div>
                                <div class="col-xs-6">
                                     <label class="v2_label">Previous Postcode</label>
                                    <input type="text" name="MoveInDetail[previous_postcode]"/>
                                </div>
                            </div>
                            <div class="form-group row yes-no-btn">
                                <div class="col-xs-12">
                                    <input type="hidden" name="MoveInDetail[fee_advised]">
                                     <label class="v2_label v2">
                                        <span class="fee_advised_dual">Can you please confirm you understand a connection fee of $_______ including GST will be applied to your first electricity bill and a connection fee of $_____ including GST will be applied to your first gas bill?</span>
                                        <span class="fee_advised_dual_vic">If your electricity meter is a remote read the connection fee will be $_____ including GST or if it is a manual read a fee of $______ including GST will be applied to your first electricity bill. A gas connection fee of $_____ including GST will be applied to your first gas bill. Do you understand this?</span>
                                        <span class="fee_advised_elec">Can you please confirm you understand a connection fee of $_______ including GST will be applied to your first electricity bill?</span>
                                        <span class="fee_advised_elec_vic">Can you please confirm you understand If your meter is a remote read the connection fee will be $_____ including GST or if it is a manual read a fee of $______ including GST will be applied to your first electricity bill?</span>
                                        <span class="fee_advised_gas">Can you please confirm you understand a connection fee of $_______ including GST will be applied to your first gas bill?</span>
                                    </label>
                                    <button class="butn movein_fee_advised checkbutton">No</button>
                                    <button class="butn movein_fee_advised checkbutton ">Yes</button>
                                </div>
                            </div>
                            <div class="form-group display_hide alintamoveinvic">
                                <div class="read-msg">
                                    <p>A connection fee may apply, which will appear on your first Alinta Energy bill. These fees are set by the distributor and are subject to changes as determined by your distributor. Information on all fees and charges are located on their website at www.alintaenergy.com.au.</p>
                                </div>
                            </div>
                            <div class="form-group display_hide alintamovein">
                                <div class="read-msg">
                                    <p>A connection fee may apply, which will appear on your first Alinta Energy bill. These fees are set by the distributor or metering co-ordinator and are subject to changes as determined by your distributor or metering co-ordinator. Information on all fees and charges are located on their website at www.alintaenergy.com.au.</p>
                                </div>
                            </div>
                            <div class="form-group row display_hide moveinnmistatus">
                                <div class="col-xs-12">
                                    <label class="font17 v2_label">NMI Status</label>
                                    <div class="v2_field"><select class="movein_nmi_status selectpicker show-menu-arrow" name="MoveInDetail[nmi_status]">
                                        <option value="">Select</option>
                                        <option value="Active (A)">Active (A)</option>
                                        <option value="De-energised (D)">De-energised (D)</option>
										</select></div>
                                </div>
                            </div>
                            <div class="read-msg display_hide moveinvisualinspection">
                                <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                <h5>READ VERBATIM TO CUSTOMER </h5>
                                <p>Energex is required a 5 hour window/timeframe to do a visual safety inspection to your new property. The examination requires full access to all buildings on the premises, including all electrical points inside the premises and any shed or a garage. Energex require someone over the age of 18 to be present for this timeframe.</p>
                                <div class="form-group yes-no-btn row">
                                    <div class="col-xs-6">
                                        <input type="hidden" name="MoveInDetail[visual_inspectioncon_confirm]" class="MoveInDetailVisualInspectionconConfirm" value="-1">
                                         <label class="v2_label v2">Are you able to confirm this?</label>
                                        <button class="butn checkbutton" onclick="visualinspectionconfirm(0)">No</button>
                                        <button class="butn checkbutton" onclick="visualinspectionconfirm(1)">Yes</button>
                                    </div>
                                </div>
                                <div class="read-msg  display_hide visualinspectionconfirmyes">
                                    <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                    <h5>READ VERBATIM TO CUSTOMER </h5>
                                    <p>The time frames for a visual inspection are 8am to 1pm and 1pm to 6pm. Which would you prefer?</p>
                                </div>
                                <div class="read-msg  display_hide visualinspectionconfirmno">
                                    <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                    <h5>READ VERBATIM TO CUSTOMER</h5>
                                    <p>The only alternative is if the premise is completely vacant, with no personal items inside, you can leave the key in the meter box. If there are ANY items inside the property this may prevent or disrupt your connection.</p>
                                </div>
                            </div>
                            <div class="form-group row display_hide visualinspectionconfirmdetails">
                                <div class="col-xs-12">
                                     <label class="v2_label">Visual Inspection Details*</label>
                                    <p class="movein_visual_inspection_selection"></p>
                                </div>
                            </div>
                            <div class="form-group row yes-no-btn ">
                                <div class="col-xs-6 moveinelectricitymeter">
                                    <label class="font17 v2_label">Electricity Meter Location*</label>
                                    <div class="v2_field"><select class="movein_elec_meter selectpicker show-menu-arrow" name="MoveInDetail[electricity_meter]">
                                        <option value="">Select</option>
                                        <option value="I">Inside</option>
                                        <option value="O">Outside</option>
										</select></div>
                                </div>
                                <div class="col-xs-6 moveingasmeter">
                                    <label class="font17 v2_label">Gas Meter Location*</label>
                                    <div class="v2_field"><select class="movein_gas_meter selectpicker show-menu-arrow" name="MoveInDetail[gas_meter]">
                                        <option value="">Select</option>
                                        <option value="I">Inside</option>
                                        <option value="O">Outside</option>
										</select></div>
                                </div>
                            </div>
                            <div class="form-group row yes-no-btn moveinelecconnectionfeetype">
                                <div class="col-xs-12">
                                    <input type="hidden" name="MoveInDetail[elec_connection_fee_type]" id="elecconnectionfeetype" value="-1">
                                     <label class="v2_label v2">Electricity Connection Fee Type</label>
                                    <button class="butn movein_elec_connection_fee_type checkbutton" onclick="elec_connection_fee_type(0, this)">Normal</button>
                                    <button class="butn movein_elec_connection_fee_type checkbutton" onclick="elec_connection_fee_type(1, this)">SDFI</button>
                                </div>
                            </div>
                            <div class="form-group row yes-no-btn display_hide moveinclearaccess">
                                <div class="col-xs-12 required-yes">
                                    <input type="hidden" name="MoveInDetail[clear_access]">
                                     <label class="v2_label v2">Your electricity is currently active, for your reading to occur there will need to be clear access to the meter(s) and main switch between 7AM and 10PM with no access restrictions such as locked meter, gates or unrestrained animals. You may also be connected remotely IF you have an active digital meter. Do you Understand?</label>
                                    <button class="butn movein_clear_access checkbutton">No</button>
                                    <button class="butn movein_clear_access checkbutton">Yes</button>
                                </div>
                            </div>
                            <div class="form-group display_hide moveindeenergisedcontact">
                                <div class="read-msg">
                                    <span href="#" class="commmentmsg">&nbsp <span class="rq">This question must be read verbatim</span></span>
                                    <h5>READ VERBATIM TO CUSTOMER </h5>
                                    <p>If for any reason your power becomes de-energised between today and the move in date, please contact me back as we will need to make amendments to the connection request.</p>
                                </div>
                            </div>
                            <?php if (isset($elec_rate['ElectricityRate']['distributor']) && in_array($elec_rate['ElectricityRate']['distributor'], array('Ausgrid'))) : ?>
                            <?php if (!in_array($plan['Plan']['retailer'], array('AGL', 'Powerdirect', 'Powerdirect and AGL'))):?>
                            <div class="form-group display_hide moveindeenergisedcontact2">
                                <div class="read-msg">
                                    <span href="#" class="commmentmsg">&nbsp <span class="rq">This question must be read verbatim</span></span>
                                    <h5>READ VERBATIM TO CUSTOMER </h5>
                                    <p>If for any reason your power becomes de-energised between today and the move in date, please turn your main switch to the off position between 7am & 10pm & ensure access is clear.</p>
                                </div>
                            </div>
                            <?php endif;?>
                            <?php else:?>
                                <div class="form-group display_hide moveindeenergisedcontact2">
                                    <div class="read-msg">
                                        <span href="#" class="commmentmsg">&nbsp <span class="rq">This question must be read verbatim</span></span>
                                        <h5>READ VERBATIM TO CUSTOMER </h5>
                                        <p>If for any reason your power becomes de-energised between today and the move in date, please turn your main switch to the off position between 7am & 10pm & ensure access is clear.</p>
                                    </div>
                                </div>
                            <?php endif;?>
                            <?php if (isset($elec_rate['ElectricityRate']['distributor']) && !in_array($elec_rate['ElectricityRate']['distributor'], array('Ausgrid'))) : ?>
                            <div class="form-group display_hide moveindeenergisedcontact3">
                                <div class="read-msg">
                                    <span href="#" class="commmentmsg">&nbsp <span class="rq">This question must be read verbatim</span></span>
                                    <h5>READ VERBATIM TO CUSTOMER </h5>
                                    <p>If for any reason your power becomes de-energised between today and the move in date, contact me back as we will need to make amendments to the connection request.</p>
                                </div>
                            </div>
                            <?php endif;?>
                            <?php if (isset($elec_rate['ElectricityRate']['distributor']) && in_array($elec_rate['ElectricityRate']['distributor'], array('Ausgrid'))) : ?>
                            <div class="form-group display_hide moveindeenergisedremainonsite">
                                <div class="read-msg">
                                    <span href="#" class="commmentmsg">&nbsp <span class="rq">This question must be read verbatim</span></span>
                                    <h5>READ VERBATIM TO CUSTOMER </h5>
                                    <p>If for any reason your power becomes de-energised between today and the move in date, Someone over the age of 18 will need to remain on site on the day of connection.</p>
                                </div>
                            </div>
                            <?php endif;?>
                            <div class="form-group display_hide moveinpowershopvic">
                                <div class="read-msg">
                                    <span href="#" class="commmentmsg">&nbsp <span class="rq">This question must be read verbatim</span></span>
                                    <h5>READ VERBATIM TO CUSTOMER </h5>
                                    <p>Charges may apply if multiple visits are required.</p>
                                </div>
                            </div>
                            <div class="form-group display_hide moveinsdfisa">
                                <div class="read-msg">
                                    <span href="#" class="commmentmsg">&nbsp <span class="rq">This question must be read verbatim</span></span>
                                    <h5>READ VERBATIM TO CUSTOMER </h5>
                                    <p>A same day connection may take up to 12am midnight.</p>
                                </div>
                            </div>
                            <div class="form-group display_hide moveincancellationfee">
                                <div class="read-msg">
                                    <span href="#" class="commmentmsg">&nbsp <span class="rq">This question must be read verbatim</span></span>
                                    <h5>READ VERBATIM TO CUSTOMER </h5>
                                    <p>You may be charged a service order cancellation fee if you cancel or change your move request. This fee is a pass through from your distributor.</p>
                                </div>
                            </div>
                            <div class="form-group row yes-no-btn display_hide moveinundersandmainswitchoff">
                                <div class="col-xs-12 required-yes">
                                    <input type="hidden" name="MoveInDetail[understand_main_switch_off]" value="-1">
                                     <label class="v2_label v2">As your electricity is currently de-energised you will need to have your main switch in the "Off" Position between 7am & 10pm on the day of connection, if it is not, this may affect or prevent your connection by the date required, you may also be connected remotely IF you have an active digital meter. Do you understand?</label>
                                    <button class="butn movein_understand_main_switch_off checkbutton">No</button>
                                    <button class="butn movein_understand_main_switch_off checkbutton">Yes</button>
                                </div>
                            </div>
                            <?php if (isset($elec_rate['ElectricityRate']['distributor']) && in_array($elec_rate['ElectricityRate']['distributor'], array('Ausgrid'))) : ?>
                                <div class="form-group row yes-no-btn display_hide moveinundersandmainswitchoffactnsw">
                                    <div class="col-xs-12 required-yes">
                                        <input type="hidden" name="MoveInDetail[understand_main_switch_off_act_nsw]" value="-1">
                                         <label class="v2_label v2">Your electricity is currently de-energised you will need to have your main switch in the "Off" Position between 7am & 10pm on the day of connection, if it is not, this may affect or prevent your connection by the date required, You may also be connected remotely IF you have an active digital meter. Do you understand?</label>
                                        <button class="butn movein_understand_main_switch_off_act_nsw checkbutton">No</button>
                                        <button class="butn movein_understand_main_switch_off_act_nsw checkbutton">Yes</button>
                                    </div>
                                </div>
                            <?php else:?>
                                <div class="form-group row yes-no-btn display_hide moveinundersandmainswitchoffactnsw2">
                                    <div class="col-xs-12 required-yes">
                                        <input type="hidden" name="MoveInDetail[understand_main_switch_off_act_nsw2]" value="-1">
                                         <label class="v2_label v2">Your electricity is currently de-energised you will need to have your main switch in the "Off" Position between 7am & 10pm on the day of connection, if it is not, this may affect or prevent your connection by the date required, You may also be connected remotely IF you have an active digital meter. Do you understand?</label>
                                        <button class="butn movein_understand_main_switch_off_act_nsw2 checkbutton">No</button>
                                        <button class="butn movein_understand_main_switch_off_act_nsw2 checkbutton">Yes</button>
                                    </div>
                                </div>
                                <div class="form-group row yes-no-btn display_hide moveinundersandmainswitchoffactnsw3">
                                    <div class="col-xs-12 required-yes">
                                        <input type="hidden" name="MoveInDetail[understand_main_switch_off_act_nsw3]" value="-1">
                                         <label class="v2_label v2">Power is currently de-energised you will need to have your main switch in the "Off" Position between 7am & 10pm on the day of connection, if it is not, this may affect or prevent your connection by the date required, You may also be connected remotely IF you have an active digital meter. Do you understand?</label>
                                        <button class="butn movein_understand_main_switch_off_act_nsw3 checkbutton">No</button>
                                        <button class="butn movein_understand_main_switch_off_act_nsw3 checkbutton">Yes</button>
                                    </div>
                                </div>
                            <?php endif;?>
                            <?php if (isset($elec_rate['ElectricityRate']['distributor']) && !in_array($elec_rate['ElectricityRate']['distributor'], array('Ausgrid'))) : ?>
                            <div class="read-msg bgylw display_hide moveinundersandmainswitchofforiginnsw"><div class="inner">
                                <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                <h5>READ VERBATIM TO CUSTOMER </h5>
                                <p>As your electricity is De-energised your distributor advises you MUST turn off all appliances in the property by 7am to 10pm on the date of connection to ensure there is no load on your meter. If this requirement is not actioned this may affect or prevent your connection of electricity by the date required.</p>
                            </div></div>
                            <?php endif;?>
                            <div class="read-msg display_hide moveinwontcharged">
                                <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                <h5>READ VERBATIM TO CUSTOMER </h5>
                                <p>You won't be charged a connection fee for a Visual Safety Inspection.</p>
                            </div>
                            <div class="form-group row yes-no-btn display_hide moveinunderstandclearaccessvicsa">
                                <div class="col-xs-12 required-yes">
                                    <input type="hidden" name="MoveInDetail[understand_clear_access_vic_sa]" value="-1">
                                     <label class="v2_label v2">During the advised time frame, there will need to be clear access to the meter(s) & main switch with no access restrictions such as locked meter, gates or unrestrained animals. Do you understand?</label>
                                    <button class="butn movein_understand_clear_access_vic_sa checkbutton">No</button>
                                    <button class="butn movein_understand_clear_access_vic_sa checkbutton">Yes</button>
                                </div>
                            </div>
                            <div class="form-group row yes-no-btn display_hide moveinunderstandclearaccessactnsw">
                                <div class="col-xs-12 required-yes">
                                    <input type="hidden" name="MoveInDetail[understand_clear_access_act_nsw]" value="-1">
                                     <label class="v2_label v2">During the advised time frame, there will need to be clear access to the meter(s) with no access restrictions such as locked meter, gates or unrestrained animals. Do you Understand?</label>
                                    <button class="butn movein_understand_clear_access_act_nsw checkbutton">No</button>
                                    <button class="butn movein_understand_clear_access_act_nsw checkbutton">Yes</button>
                                </div>
                            </div>
                            <?php if (isset($elec_rate['ElectricityRate']['distributor']) && !in_array($elec_rate['ElectricityRate']['distributor'], array('Ausgrid'))) : ?>
                                <div class="form-group row yes-no-btn display_hide moveinunderstandclearaccessactnsw2">
                                    <div class="col-xs-12 required-yes">
                                        <input type="hidden" name="MoveInDetail[understand_clear_access_act_nsw2]" value="-1">
                                         <label class="v2_label">You'll need to have clear access to the meter(s) between this time frame with no access restrictions such as locked meter, gates or unrestrained animals. Do you Understand?</label>
                                        <button class="butn movein_understand_clear_access_act_nsw2 checkbutton">No</button>
                                        <button class="butn movein_understand_clear_access_act_nsw2 checkbutton">Yes</button>
                                    </div>
                                </div>
                            <?php endif;?>
                            <div class="read-msg bgylw display_hide moveinmainswitchoff"><div class="inner">
                                <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                <h5>READ VERBATIM TO CUSTOMER </h5>
                                <?php if ($plan['Plan']['retailer'] == 'Origin Energy'):?>
                                    <p>If access arrangements are not kept, Energex will charge a Wasted Truck fee of up to $110.39 and will not reconnect the power.</p>
                                <?php else:?>
                                    <p>If access arrangements are not kept, Energex will charge a Wasted Truck fee of up to ($110.39 for BAU connections OR $154.60 for SDFI connections) they will not reconnect the power.</p>
                                <?php endif;?>
                            </div></div>
                            <?php if (in_array($plan['Plan']['retailer'], array('Momentum')) && $plan['Plan']['state'] == 'Victoria' && $plan['Plan']['package'] != 'Gas') : ?>
                                <div class="form-group row yes-no-btn moveinworksplanned">
                                    <div class="col-xs-12">
                                        <input type="hidden" name="MoveInDetail[works_planned]">
                                         <label class="v2_label v2">To your knowledge, from the date the power at the property was (or will be) disconnected to the proposed date of re-connection are there any works at these premises which might involve someone coming into contact with exposed wiring?</label>
                                        <button class="butn movein_works_planned checkbutton">No</button>
                                        <button class="butn movein_works_planned checkbutton">Yes</button>
                                    </div>
                                </div>
                            <?php endif;?>
                            <?php if (!in_array($plan['Plan']['retailer'], array('Momentum', 'Energy Australia', 'ERM')) && $plan['Plan']['state'] == 'Victoria' && $plan['Plan']['package'] != 'Gas') : ?>
                                <div class="form-group row yes-no-btn moveinworksplanned">
                                    <div class="col-xs-12">
                                        <input type="hidden" name="MoveInDetail[works_planned]">
                                         <label class="v2_label v2">Are there any electrical works planned prior to connection? (rewiring, switchboard works or replacement)</label>
                                        <button class="butn movein_works_planned checkbutton" onclick="worksplanned(0)">No</button>
                                        <button class="butn movein_works_planned checkbutton" onclick="worksplanned(1)">Yes</button>
                                    </div>
                                </div>
                            <?php endif;?>
                            <div class="form-group display_hide moveinclearaccessgas">
                                <div class="read-msg bgylw"><div class="inner">
                                    <h5>READ VERBATIM TO CUSTOMER </h5>
                                    <p>For your connection to occur there will need to be clear access to your gas meter with no access restrictions such as locked meter, gates or unrestrained animals.</p>
                                </div></div>
                            </div>
                            <div class="form-group display_hide moveinclearaccessdual">
                                <div class="read-msg bgylw"><div class="inner">
                                    <h5>READ VERBATIM TO CUSTOMER </h5>
                                    <p>The same clear access requirements are needed for your gas connection to be completed</p>
                                </div></div>
                            </div>
                            <div class="form-group display_hide moveinpoweronmomentum">
                                <div class="read-msg bgylw"><div class="inner">
                                    <h5>READ VERBATIM TO CUSTOMER </h5>
                                    <p>Your network has up to four business days from the requested service order date to attend the site and obtain a read. Momentum will commence billing from the date this read is obtained.</p>
                                </div></div>
                            </div>
                            <div class="form-group row yes-no-btn display_hide moveinanyhazards">
                                <div class="col-xs-12">
                                    <input type="hidden" name="MoveInDetail[meter_hazard]">
                                    <label class="font17 v2_label v2">Are there any hazards near the meter that would prevent connection?</label>
                                    <button class="butn movein_meter_hazard checkbutton" onclick="meterhazard(0)">No</button>
                                    <button class="butn movein_meter_hazard checkbutton" onclick="meterhazard(1)">Yes</button>
                                    <button class="butn movein_meter_hazard checkbutton" onclick="meterhazard(2)">Unsure</button>
                                </div>
                            </div>
                            <div class="form-group row display_hide moveinhazardsmomentum">
                                <div class="col-xs-6">
                                    <label class="font17 v2_label">Hazards</label>
                                    <div class="v2_field"><select class="selectpicker show-menu-arrow form-control" name="MoveInDetail[hazards_momentum]">
                                        <option value="0">Select</option>
                                        <option value="CAUTION">CAUTION</option>
                                        <option value="DOG">DOG</option>
                                        <option value="ELECFENCE">ELECFENCE</option>
                                        <option value="NONE">NONE</option>
                                        <option value="NOTKNOWN">NOTKNOWN</option>
                                    </select></div>
                                </div>
                            </div>
                            <div class="form-group row display_hide moveinhazards">
                                <div class="col-xs-12">
                                     <label class="v2_label">Hazards</label>
                                    <div class="v2_field"><input type="text" name="MoveInDetail[hazards]" class="form-control"/></div>
                                </div>
                            </div>
                            <div class="form-group display_hide workscompletedoriginyes">
                                <div class="read-msg bgred"><div class="inner">
                                    <h5>READ VERBATIM TO CUSTOMER </h5>
                                    <p>Arrange a manual connection and advise of manual connection move-in fee.</p>
                                </div></div>
                            </div>
                            <div class="form-group display_hide workscompletedoriginno">
                                <div class="read-msg bgred"><div class="inner">
                                    <h5>READ VERBATIM TO CUSTOMER </h5>
                                    <p>Arrange a remote read and advise the customer of the maximum move-in fee.</p>
                                </div></div>
                            </div>
                            <div class="form-group row yes-no-btn display_hide moveinalterations">
                                <div class="col-xs-12">
                                    <input type="hidden" name="MoveInDetail[alterations]">
                                     <label class="v2_label v2">Are there any alterations or renovations currently in progress at the property?</label>
                                    <button class="butn movein_alterations checkbutton">No</button>
                                    <button class="butn movein_alterations checkbutton">Yes</button>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-12 checkbox-sec">
                                    <input id="movein_special" type="checkbox" name="MoveInDetail[special]" value="1">
                                    <label class="checkbox-clone" for="movein_special">&nbsp;</label>
                                    <span id="movein_special_span">Any Special Instructions for access?</span>
                                </div>
                            </div>
                            <div class="form-group row display_hide movein_special_checked">
                                <div class="col-xs-12">
                                    <div class="v2_field"><input id="movein_special_details" type="text" name="MoveInDetail[special_details]" maxlength="160" class="form-control"/></div>
                                </div>
                            </div>
                            <?php if (isset($elec_rate['ElectricityRate']['distributor']) && in_array($elec_rate['ElectricityRate']['distributor'], array('Ausgrid'))) : ?>
                            <div class="form-group row yes-no-btn display_hide moveinconfirmremainonsite">
                                <div class="col-xs-12 required-yes">
                                    <input type="hidden" name="MoveInDetail[confirm_remain_onsite]" value="-1">
                                     <label class="v2_label v2">As your power is De-energised, someone over the age of 18 will need to remain onsite on the day of connection. This is to ensure there are no electrical faults inside the property prior to connection. There will also need to be clear access to the meter. Can you confirm this?</label>
                                    <button class="butn movein_confirm_remain_onsite checkbutton">No</button>
                                    <button class="butn movein_confirm_remain_onsite checkbutton">Yes</button>
                                </div>
                            </div>
                            <?php endif;?>
                            <div class="form-group">
                                <div class="read-msg">
                                    <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                    <h5>READ VERBATIM TO CUSTOMER </h5>
                                    <p>As a 3rd party, we are not responsible for any personal reimbursements where your energy has not been connected due to main switch issues or access restrictions. If this happens You may be charged an additional fee for another connection</p>
                                </div>
                            </div>
                            <div class="form-group display_hide gas_momentum_invoice">
                                <div class="read-msg">
                                    <h5>READ VERBATIM TO CUSTOMER </h5>
                                    <p>If the gas is currently on at your property, your network may attend the site up to 2 business days before or on the requested move in date to obtain a read. If there is no gas supply currently at the property, you will be invoiced for the network fees associated with your connection. Momentum will commence billing from the date the read is obtained.</p>
                                </div>
                            </div>
                            <div class="form-group display_hide gas_take_days">
                                <div class="read-msg">
                                    <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                    <h5>READ VERBATIM TO CUSTOMER </h5>
                                    <p>Where there is no gas supply, the gas connection can take 5 to 15 business days to be completed by your distributor. If Gas is currently active the meter will generally be read in 5 business days.</p>
                                </div>
                            </div>
                            <div class="form-group yes-no-btn display_hide gas_help">
                                <button class="butn movein_gas_help checkbutton">Gas HELP!</button>
                            </div>
                            <div class="form-group display_hide gas_help_text">
                                <div class="read-msg">
                                    <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                    <h5>READ VERBATIM TO CUSTOMER </h5>
                                    <p>In the open position (gas is flowing) the handle (lever) will be parallel with the pipe, when it is closed (gas not flowing) the handle (lever) will be perpendicular to the pipe. This should make it easy for you to tell if the gas is on, or off. When turning gas on you will need to wait a while for the flow of gas to reach the property.</p>
                                </div>
                            </div>
                            <div class="submit-btnotr text-right">
                                <input type="submit" onclick="checkMoveInDetails()" class="submit-btn" value="Mark section as Completed"/>
                            </div>
                        </div>
                    </form>
                </div>
			
                <h3 class="form-hdg final">Final Info<i class="fa fa-angle-up" aria-hidden="true"></i><i class="fa fa-angle-down" aria-hidden="true"></i></h3>
            	<div class="form clearfix collapse" id="form-collapse9">
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
                        <input type="hidden" id="final_telco_lead" name="Final[telco_lead]" value="0">

                        <input type="hidden" id="final_campaign_id" name="Final[campaign_id]" value="">
                        <input type="hidden" id="final_campaign_name" name="Final[campaign_name]" value="">

                        <input type="hidden" id="final_vid" name="Final[vid]" value="">
                        <input type="hidden" id="final_call_medium" name="Final[call_medium]" value="">

                        <div class="form-data">
                            <div class="form-group row purchase_reason">
                                <div class="col-xs-6">
                                    <label class="font17 v2_label">Purchase Reason</label>
                                    <div class="v2_field"><select class="selectpicker show-menu-arrow" name="Final[purchase_reason]" id="final_purchase_reason">
                                        <option value="">Select</option>
                                        <option value="Price">Price</option>
                                        <option value="Brand - Retailer">Brand - Retailer</option>
                                        <option value="Reputation">Reputation</option>
                                        <option value="Benefits">Benefits</option>
                                    </select></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                     <label class="v2_label v2">Sales Rep Name*</label>
                                    <div class="v2_field"><input type="text" value="" name="Final[sales_rep_name]" id="sales_rep_name" class="form-control"></div>
                                    <input type="hidden" name="Final[sales_rep_email]" id="sales_rep_email" value="">
                                    <input type="hidden" name="Final[agent_id]" id="agent_id" value="">
                                </div>
                            </div>
                            <div class="form-group row finalquoteid">
                                <div class="col-xs-4">
                                     <label class="v2_label">Momentum Quote ID</label>
                                    <div class="v2_field"><input name="Final[quote_id]" id="final_quote_id" type="text" value="" class="form-control"/></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-12">
                                    <?php if (!empty($pdfs)): ?>
                                     <label class="v2_label">Open EIC*</label>
                                        <div class="v2_field"><select class="eicpdf selectpicker show-menu-arrow" name="Final[eic_pdf]">
                                            <option value="">Select</option>
                                            <?php foreach ($pdfs as $pdf): ?>
                                                <option value="<?php echo $pdf['Pdf']['filename']; ?>"><?php echo $pdf['Pdf']['filename']; ?></option>
                                            <?php endforeach; ?>
											</select></div>
                                    <?php else: ?>
                                     <label class="v2_label v2">Open EIC*</label>
                                        PDF not found - please check network drive
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-xs-6">
                                     <label class="v2_label">EIC Recording*</label>
                                    <div class="v2_field"><input name="Final[eic]" type="text" value="" class="form-control"/></div>
                                </div>
                            </div>
                            <div class="form-group row powershoptoken">
                                <div class="col-xs-12">
                                     <label class="v2_label">Powershop Token*</label>
                                    <div class="v2_field"><input type="text" name="Final[powershop_token]" id="powershop_token" value="" maxlength="40" class="form-control"/></div>
                                </div>
                            </div>

                            <!-- Update X3 logics for Ovo Energy Direct Debit - ID 632, 633, 634 on 18-06-2021-->
                            <?php if ($plan['Plan']['retailer'] == 'OVO Energy' &&  $plan['Plan']['product_name'] == 'The One Plan'):?>
                            <div class="form-group row yes-no-btn">
                                <div class="col-xs-12 required-yes">
                                    <p style="color: red"><strong>OVO DIRECT DEBIT CONSENT AGENT ACTION:</strong> <i>Please read the below script AFTER you have obtained consent for the EIC. This script should be used in conjunction with the direct Debit Form.</i></p>
                                    <p>Today, <?php echo date('d/m/Y');?> you have agreed to pay any amount deemed payable by OVO Energy. Your initial $<?php echo $plan['Plan']['ovo_direct_debit'];?> will be debited within 5 business days of your successful transfer to OVO Energy and monthly from then on, or the business day before if it falls on a weekend or public holiday.</p>
                                    <input type="hidden" name="Final[ovo_energy_payments]" value="-1" class="ovoenergypayments">
                                     <label class="v2_label v2">Are you happy to proceed with payment(s) to be debited from your account?</label>
                                    <button class="butn final_ovo_energy_payments checkbutton" onclick="ovo_energy_payments(0)">No</button>
                                    <button class="butn final_ovo_energy_payments checkbutton" onclick="ovo_energy_payments(1)">Yes</button>
                                </div>
                            </div>
                            <div class="read-msg display_hide ovoenergypaymentsyes">
                                <span href="#" class="commmentmsg">&nbsp; <span class="rq">This question must be read verbatim</span></span>
                                <h5>READ VERBATIM TO CUSTOMER </h5>
                                <p>We will now obtain your banking details. To ensure that we comply with the applicable privacy standards we are unable to record our call from this point due to the fact that we will be collecting personal information. We advise that from this point forward we can and will only be obtaining your bank details.  Please confirm that you do not have any more questions regarding our call today before we proceed? <i>(If customer has no more questions request management or admin to pause your call before obtaining DD details)</i></p>
                            </div>
                            <?php endif;?>
                            <?php if ($plan['Plan']['res_sme'] == 'SME'):?>
                            <div class="form-group">
                                <div class="read-msg">
                                    <h5>READ VERBATIM TO CUSTOMER </h5>
                                    <p><strong>READ AFTER YOU OBTAIN CONSENT FROM PRE-RECORDED (ADMIN DO NOT MARK THIS SECTION</strong><br>
                                        As I mentioned earlier in my call, we work with businesses to improve their cash flow. To help small & medium businesses like yourself, we’ve established a panel of qualified lenders from banks & non-banks. If you’re interested, we can organise a conversation with one of our experts, either now or at a later date. Which time works best for you?</i></p>

                                        <p><strong>NO</strong> - OK, no worries – <i>(Wrap up your call)</i><br>
                                            <strong>YES, NOW</strong> Great,  let me transfer you now. <strong>Agent Action</strong>: Warm transfer to 1300 307 136 (if there’s no answer – sorry, all of our team are busy right now, I'll pass you details on and they’ll call you back shortly)<br>
                                            <strong>YES, but not now, book a time</strong>: let's book in an appointment for you with the finance team.  Agent Action: Complete ZOHO form with the details of appointment in the comments and Add the lead ID: <a href="https://zfrmz.com/8Bc6O9VGLakcz3ImCkJE">https://zfrmz.com/8Bc6O9VGLakcz3ImCkJE</a><br>
                                        <p><strong>Agent Action</strong> - Complete usual wrap up for SME</p>
                                        <p><i>* if any questions around who the lenders or brokers are, scripting follows:</i><br>
                                            We have selectively chosen lenders who are responsible and reputable to coordinate with for financing small to medium enterprises. They will include banks and non-banks. You choose on the who you want to deal with, all we do is manage the process to ensure they deliver on timing and communication with you but most important make sure they understand your needs</p>
                                </div>
                            </div>
                            <?php endif;?>
                            <?php if ($plan['Plan']['res_sme'] != 'SME'):?>
                            <div class="read-msg">
                                <p><strong><i>Now that I've helped you save on your energy, we also work with Health Deal, they specialise in health insurance comparisons - Did you want me to get them to give you a call and see if you can save on health insurance as well?</i></strong>
                                <p class="yes-no-btn">
                                        <input type="hidden" name="Final[healthdeal]" value="-1">
                                        <button class="butn final_healthdeal checkbutton">No</button>
                                        <button class="butn final_healthdeal checkbutton">Yes</button>
                                </p>
                            </div>
                            <?php endif;?>
                            <div class="submit-btnotr text-right">
                                <input onclick="checkFinalFields()" type="submit" class="submit-btn checkFinalFields" value="Mark section as Completed"/>
                            </div>
                        </div>
                    </form>
                </div>
          
			</div>
			<div class="mt50 mb50 clearfix">
                <form action="" method="post" id="signup_complete" name="signup_complete" class="text-right">
                    <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
                    <input type="hidden" id="lead_imported" value="0">
                    <input type="hidden" id="plan_form_checked" value="0">
                    <input type="hidden" id="contact_form_checked" value="0">
                    <input type="hidden" id="business_details_form_checked" value="0">
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
    <div class="modal-dialog modal-lg" style="margin-top: 10%">
        <div class="modal-content">
            <div class="modal-body thanks-popup text-center">
                <h1>Your sale has been posted! Well done!</h1>

                <h2>Lead ID: <span id="complete_dialog_lead_id">123</span> <span id="complete_dialog_cb_lead_id"></span></h2>
                <p><i>(Ensure customer has your name & number & advise to contact you or the retailer if they have any questions.)</i></p>
                <p class="display_hide receive_an_email">You will receive an email shortly from us. This is not your welcome pack. Your welcome pack will come from the retailer directly. </p>
                <p class="display_hide receive_no_email">Your welcome pack will come direct from the retailer</p>
                <p class="display_hide popup_movein_text">If you experience any issues or concerns after our conversation today please don't hesitate to contact myself or the retailer directly. (Make sure Customer has the retailer contact number noted down)</p>
                <p class="display_hide popup_transfer_acquisition_text">Your current retailer may attempt contact with you with a counter offer, if they do please give me a call so that I can check if it would be beneficial or not.<br>After your 10 Business day cooling off period you will receive one final bill from your current retailer & then you will switch to X retailer.</p>
                <p>
                    <input value='Add a "Gas Product" for this property' class="btn btn-warning add-a-gas-product" type="button">
                    <input value='Sign up another property' class="btn btn-warning signup-another-property" type="button">
                </p>
                <p>
                    <input value='Create Telco lead' class="btn btn-warning create-telco-lead display_hide" type="button">
                    <input value='Create Telco form' class="btn btn-warning create-telco-form display_hide" type="button">
                </p>
                <p>
                    <input value='No thanks I am done' class="btn btn-warning no-thanks" type="button">
                </p>
            </div>
        </div>
    </div>
</div>
<div class="modal fade complete-modal-ovo" id="complete_modal_ovo" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="complete_modalLabel" aria-hidden="true">
    <div class="modal-dialog" style="margin-top: 10%">
        <div class="modal-content">
            <div class="modal-body thanks-popup text-center">
                <h1><span id="complete_dialog_cb_lead_id_ovo"></span>Customer Payment Details entered?</h1>
                <input type="hidden" id="ovo_lead_id" name="ovo_lead_id" value="">
                <input type="hidden" id="ovo_lead_datetime" name="ovo_lead_datetime" value="">
                <input type="hidden" id="ovo_cb_lead_id" name="ovo_cb_lead_id" value="">
                <p>
                    <input value='Yes' class="btn btn-warning ovo-payment-details-yes" type="button">
                    <input value='No' class="btn btn-warning ovo-payment-details-no" type="button">
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
