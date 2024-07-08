<?php echo $this->element('submissions_header');?>
<div class="pdt125">
	<section class="bread-cum-wrap show-big">
		<div class="container-fluid">
			<ul class="bread-cum ml20">
                        <li class="blue-text"> <a href="https://<?php echo WEBSITE_COMPARE_DOMAIN_NAME; ?>/about-you"> About You </a></li>
                        <li class="blue-text"> <a href="https://<?php echo WEBSITE_COMPARE_DOMAIN_NAME; ?>/product-options"> Product Features </a></li>
                        <li class="blue-text"> <a href="https://<?php echo WEBSITE_COMPARE_DOMAIN_NAME; ?>/compare-plans"> Product Compare </a></li>
			</ul>
		</div>
	</section>
	<div class="container compare">
		<div class="row">
			<!-- Left column begins -->
			<div class="col-sm-8 franco-660">
				<div class="form-group leftPane">
					<p class="leftPaneHeader">Please review and confirm information below</p>
					<div id="accordionWrapper">
						<!-- First form begins -->
						<!-- note: 22/10/2015removed lead class -->
						<h3 id="PlanExplanation" class="form-hdg AC-Primary">Plan Explanation </h3>
						<div>
							<form action="" method="post" class="row signup_plan clearfix" name="signup_plan">
								<div class="col-xs-12">
									<div class="form-group">
									<?php echo $plan_estimates['product_summary'];?>
                                    </div>
								</div>
								<div class="col-xs-12">
									<div class="form-group"><input type="submit" value="Continue" class="submit-btn btn-border-blue triggerValidation" onclick="" name="PlanExplanation"/></div>
								</div>
							</form>
						</div>

						<!-- Account Details Begins -->
						<h3 id="AccountDetails" class="form-hdg contact_details AC-Primary">Account Details</h3>
						<div>
							<!-- Account Details -->
							<form action="" method="post" class="row clearfix signup_contact" name="signup_contact">
								<div class="col-xs-4 col-lg-2">
									<div class="form-group"><?php echo $ew -> title['label_string'];?><?php echo $ew -> title['node_string'];?></div>
								</div>
								<div class="col-xs-8 col-lg-5">
									<div class="form-group"><?php echo $ew -> firstName['label_string'];?><?php echo $ew -> firstName['node_string'];?></div>
								</div>
								<div class="col-xs-12 col-lg-5">
									<div class="form-group"><?php echo $ew -> surName['label_string'];?><?php echo $ew -> surName['node_string'];?></div>
								</div>
								<!-- First row ends -->

								<!-- DOB row begins single column-->
								<div class="col-xs-12">
									<div class="form-group"><?php echo $ew -> dob['label_string'];?><?php echo $ew -> dob['node_string'];?></div>
								</div>
								<!-- DOB row ends -->
								<!-- Mobile row begins single column-->
								<div class="col-xs-12">
									<div class="form-group"><?php echo $ew -> mobileNumber['label_string'];?><?php echo $ew -> mobileNumber['node_string'];?></div>
								</div>
								<!-- Mobile row ends -->

								<!-- OtherNumberCheckBox row begins single column-->
								<!--
								<div class="col-xs-12">
									<div class="form-group"><label class="checkbox-inline control-label"><input type="checkbox" value="" class="" onclick="getInputs(this, 'section_OtherNumber');"/>Other number</label></div>
								</div>
								-->
								<!-- OtherNumberCheckBox row ends -->
                                <!-- OtherNumber row begins single column-->
								<div id="section_OtherNumber" class="col-xs-12">
									<div class="form-group"><?php echo $ew -> secondayContactNumber['label_string'];?><?php echo $ew -> secondayContactNumber['node_string'];?></div>
                                </div>
                                <!-- OtherNumber row ends -->

								<!-- Email row begins single column-->
								<div class="col-xs-12">
									<div class="form-group"><?php echo $ew -> email['label_string'];?><?php echo $ew -> email['node_string'];?></div>
								</div>
								<!-- Email row ends -->
								<!-- AddSecondaryContactCheckBox row begins single column-->

								<!-- removed by retailer evaluation
								<div class="col-xs-12">
									<div class="form-group"><label class="checkbox-inline control-label"><input id="secondaryContactCheckBx" type="checkbox" value="" class="" onclick="getInputs(this, 'section_SecondaryContact');"/>I want to add a secondary contact</label></div>
								</div>
								 -->

								<!-- AddSecondaryContactCheckBox row ends -->

								<div id="section_SecondaryContact" class="col-xs-12">
									<!-- We want to hide this section begins -->
									<div class="row">
										<!-- Secondary Contact First row begins -->
										<div class="col-xs-4 col-lg-2">
											<div class="form-group"><?php echo $ew -> SecondaryContact_title['label_string'];?><?php echo $ew -> SecondaryContact_title['node_string'];?></div>
										</div>

										<div class="col-xs-8 col-lg-5">
											<div class="form-group"><?php echo $ew -> SecondaryContact_firstName['label_string'];?><?php echo $ew -> SecondaryContact_firstName['node_string'];?></div>
										</div>
										<div class="col-xs-12 col-lg-5">
											<div class="form-group"><?php echo $ew -> SecondaryContact_surName['label_string'];?><?php echo $ew -> SecondaryContact_surName['node_string'];?></div>
										</div>
										<!-- Secondary Contact First row ends -->
										<!-- Secondary Contact DOB row begins single column-->
										<div class="col-xs-12">
											<div class="form-group"><?php echo $ew -> SecondaryContact_dob['label_string'];?><?php echo $ew -> SecondaryContact_dob['node_string'];?></div>
										</div>
										<!-- Secondary Contact DOB row ends -->
										<!-- Secondary Contact Mobile row begins single column-->
										<div class="col-xs-12">
											<div class="form-group"><?php echo $ew -> SecondaryContact_mobileNumber['label_string'];?><?php echo $ew -> SecondaryContact_mobileNumber['node_string'];?></div>
										</div>
										<!-- Secondary Contact Mobile row ends -->

										<!-- Secondary Contact OtherNumberCheckBox row begins single column-->
										<div class="col-xs-12">
											<div class="form-group"><label class="checkbox-inline control-label"><input type="checkbox" value="" class="" onclick="getInputs(this, 'section_SecondaryContact_OtherNumber');"/>Other Number</label></div>
										</div>
										<!-- Secondary Contact OtherNumberCheckBox row ends -->

										<!-- Secondary Contact OtherNumber row begins single column-->
										<div id="section_SecondaryContact_OtherNumber" class="col-xs-12">
											<div class="form-group"><?php echo $ew -> SecondaryContact_secondayContactNumber['label_string'];?><?php echo $ew -> SecondaryContact_secondayContactNumber['node_string'];?></div>
										</div>
										<!-- Secondary Contact OtherNumber row ends -->

										<!-- We want to hide this section ends -->
									</div>
								</div>

								<!-- AccountDetails submit begins -->
								<div class="col-xs-12">
									<div class="form-group"><input type="submit" value="Continue" class="submit-btn btn-border-blue triggerValidation" onclick="" name="AccountDetails"/></div>
								</div>
								<!-- AccountDetails submit ends -->

							</form>
						</div>

						<h3 id="PersonalDetails" class="form-hdg identification AC-Primary">Personal Details</h3>
						<div>
							<form action="" method="" class="row clearfix signup_personal" name="signup_personal">
								<!-- Personal Details -->
								<!-- Personal Details First row begins 2 columns-->
								<div class="col-xs-6">
									<div class="form-group"><?php echo $ew -> PersonalDetails_documentType['label_string'];?><?php echo $ew -> PersonalDetails_documentType['node_string'];?></div>
								</div>
								<div class="col-xs-6">
									<div class="form-group"><?php echo $ew -> PersonalDetails_documentState['label_string'];?><?php echo $ew -> PersonalDetails_documentState['node_string'];?></div>
								</div>
								<!-- Personal Details First  row ends -->

								<!-- Document Number row begins single column-->
								<div class="col-xs-12">
									<div class="form-group"><?php echo $ew -> PersonalDetails_documentIdNumber['label_string'];?><?php echo $ew -> PersonalDetails_documentIdNumber['node_string'];?></div>
								</div>
								<!-- Document Number row ends -->
								<!-- Expiry Date row begins single column-->
								<div class="col-xs-12">
									<div class="form-group"><?php echo $ew -> PersonalDetails_documentExpiry['label_string'];?><?php echo $ew -> PersonalDetails_documentExpiry['node_string'];?></div>
								</div>
								<!-- Expiry Date row ends -->

								<!-- Document Country row begins single column-->
								<div class="col-xs-12">
									<div class="form-group"><?php echo $ew -> PersonalDetails_documentCountry['label_string'];?><?php echo $ew -> PersonalDetails_documentCountry['node_string'];?></div>
								</div>
								<!-- Document Country row ends -->
								<!-- Concession Declaration row begins 2 columns for concessions-->
								<div class="col-xs-8">
									<div class="form-group"><?php echo $ew -> PersonalDetails_concession_confirm['label_string'];?></div>
								</div>
								<div class="col-xs-4">
									<div class="form-group"><?php echo $ew -> PersonalDetails_concession_confirm['node_string'];?></div>
								</div>
								<!-- Concession Declaration row ends -->

								<!-- Retailer specific pre-concession question begines -->
								<?php foreach ($ew -> preConcessionQuestionNodes as $preConcessionQuestion):?>

								<div class="col-lg-8">
									<div class="form-group"><?php echo $preConcessionQuestion['label_string'];?></div>
								</div>
								<div class="col-lg-4">
									<div class="form-group"><?php echo $preConcessionQuestion['node_string'];?></div>
								</div>

								<?php endforeach;?>


								<!-- Retailer specific pre-concession questions ends -->

								<!-- Concession First row begins 2 columns-->
								<section id="setction_ConcessionCard" class="col-xs-12">
									<div id="concession_block" class="row clearfix">
										<div class="col-xs-6">
											<div class="form-group"><?php echo $ew -> PersonalDetails_concessionCardIssuer['label_string'];?><?php echo $ew -> PersonalDetails_concessionCardIssuer['node_string'];?></div>
										</div>
										<div class="col-xs-6">
											<div class="form-group"><?php echo $ew -> PersonalDetails_concessionCardType['label_string'];?><?php echo $ew -> PersonalDetails_concessionCardType['node_string'];?></div>
										</div>
										<!-- Concession First row ends -->
										<!-- Concession Second row begins 2 columns-->
										<div class="col-xs-12">
											<div class="form-group"><?php echo $ew -> PersonalDetails_concessionCardNumber['label_string'];?><?php echo $ew -> PersonalDetails_concessionCardNumber['node_string'];?></div>
										</div>
										<!-- Concession Second row ends -->
										<!-- Concession Third row begins 2 columns-->
										<div class="col-xs-6">
											<div class="form-group"><?php echo $ew -> PersonalDetails_concessionCardStartDate['label_string'];?><?php echo $ew -> PersonalDetails_concessionCardStartDate['node_string'];?></div>
										</div>
										<div class="col-xs-6">
											<div class="form-group"><?php echo $ew -> PersonalDetails_concessionCardExpiry['label_string'];?><?php echo $ew -> PersonalDetails_concessionCardExpiry['node_string'];?></div>
										</div>
										<!-- Concession Third row ends -->

										<!-- Retailer specific Concession concession questions begin -->
										<?php foreach ($ew -> concessionNodes as $concessionNode):?>
										<div class="col-xs-8">
											<div class="form-group">
												<?php echo $concessionNode['label_string'];?>
											</div>
										</div>
										<div class="col-xs-4">
											<div class="form-group">
												<?php echo $concessionNode['node_string'];?>
											</div>
										</div>
										<?php endforeach;?>
										<!-- Retailer specific Concession concession questions end -->
									</div>

								</section>


								<!-- PersonalDetails submit begins -->
								<div class="col-xs-12">
									<div class="form-group"><input type="submit" value="Continue" class="submit-btn btn-border-blue triggerValidation" onclick="" name="PersonalDetails"></div>
								</div>
								<!-- PersonalDetails submit ends -->
							</form>
						</div>

						<h3 id="PropertyDetails" class="form-hdg supply_address AC-Primary">Property Details</h3>
						<div>
							<form action="" method="" class="row clearfix signup_supply" name="signup_supply" autocomplete="off">
								<!-- Property Details begins -->
								<!-- Property Address row begins single column-->
								<div class="col-lg-12 hide">
									<div class="form-group">
										<label for="your_address" class="control-label">Your address</label>
										<input type="text" value="" placeholder="Your address" class="form-control full_address" name="your_address" id="your_address" autocomplete="off" />
										<div class="danger-box hide full_address_error">
                                            <h4>Postcode Mismatch</h4>
                                            <p>The address you've selected doesn't match the postcode you entered at the start of the comparison. Please <a href="http://<?php echo WEBSITE_COMPARE_DOMAIN_NAME; ?>/about-you?refresh=1">start the comparison</a> again with the correct postcode in order to continue the signup.</p>
                                        </div>
									</div>
								</div>
                                <div class="clearfix"></div>
                                <div class="address_fields">
								<!-- Property Address row ends -->
								<!-- Property Unit/Street Number row begins 2 columns-->
								<div class="col-lg-6">
									<div class="form-group"><?php echo $ew -> Property_unit['label_string'];?><?php echo $ew -> Property_unit['node_string'];?></div>
								</div>
								<div class="col-lg-6">
									<div class="form-group"><?php echo $ew -> Property_streetNumber['label_string'];?><?php echo $ew -> Property_streetNumber['node_string'];?></div>
								</div>
								<!-- Property Unit/Street Number row ends -->
								<!-- Property StreetName row begins single column-->
								<div class="col-lg-12">
									<div class="form-group"><?php echo $ew -> Property_streetName['label_string'];?><?php echo $ew -> Property_streetName['node_string'];?></div>
								</div>
								<!-- Property StreetName row ends -->
								<!-- Property StreetType/Suburb row begins 2 columns-->
								<div class="col-lg-6">
									<div class="form-group"><?php echo $ew -> Property_streetType['label_string'];?><?php echo $ew -> Property_streetType['node_string'];?></div>
								</div>
								<div class="col-lg-6">
									<div class="form-group"><?php echo $ew -> Property_suburb['label_string'];?><?php echo $ew -> Property_suburb['node_string'];?></div>
								</div>
								<!-- Property StreetType/Suburb row ends -->
								<!-- Property Postcode/State row begins 2 columns-->
								<div class="col-lg-6">
									<div class="form-group"><?php echo $ew -> Property_postCode['label_string'];?><?php echo $ew -> Property_postCode['node_string'];?></div>
								</div>
								<div class="col-lg-6">
									<div class="form-group"><?php echo $ew -> Property_state['label_string'];?><?php echo $ew -> Property_state['node_string'];?></div>
								</div>
								<!-- Property Postcode/State row ends -->
                                </div>

								<!-- Property NMI and MIRN begins -->
								<div class="col-lg-6">
									<div class="form-group"><?php echo $ew -> Property_nmi['label_string'];?><?php echo $ew -> Property_nmi['node_string'];?></div>
								</div>
								<div class="col-lg-6">
									<div class="form-group"><?php echo $ew -> Property_mirn['label_string'];?><?php echo $ew -> Property_mirn['node_string'];?></div>
								</div>
								<!-- Property NMI and MIRN ends  -->
								<!-- Property Tenant/Owner begins -->
								<div class="col-lg-6">
									<div class="form-group"><?php echo $ew -> Property_rentown['label_string'];?><?php echo $ew -> Property_rentown['node_string'];?></div>
								</div>
								<div class="col-lg-6">
									<div class="form-group"></div>
								</div>
								<!-- Property Tenant/Owner ends  -->
								<!-- Property DifferentAdressDeclaration row begins 2 columns -->
								<div class="col-lg-8">
									<div class="form-group">
											<?php echo $ew -> Property_postalAddress_different['label_string'];?>
											<!-- My postal address is different to my supply address -->
									</div>
								</div>
								<div class="col-lg-4">
									<div class="form-group">
											<?php echo $ew -> Property_postalAddress_different['node_string'];?>
											<!--
											<label class="radio-inline control-label"><input type="radio" name="postal_radio" onclick="radioBtnTriggeredAccordion(this,'section_PropertySecondayContact')" value="Yes">Yes</label>
											<label class="radio-inline control-label"><input type="radio" name="postal_radio" onclick="radioBtnTriggeredAccordion(this,'section_PropertySecondayContact')" value="No" >No</label>
											-->
									</div>
								</div>
								<!-- Property DifferentAdressDeclaration  row ends -->

								<div id="section_PropertySecondayContact" class="col-xs-12">
								<!-- POBOX Declaration row begins single column-->
									<div class="row clearfix">
										<div class="col-xs-12">
											<div class="form-group"><label class="checkbox-inline control-label"><input type="checkbox" id="poBoxCheckBx" value="" class="" onclick="POBOXDisplayControls(this);"/>My Address is a PO BOX</label></div>
										</div>
										<!-- POBOX Declaration row ends -->
                                        <div class="clearfix"></div>
										<!-- POBOX row begins single column-->
										<div class="col-lg-6 col_pobox">
											<div class="form-group"><?php echo $ew -> Postal_pobox['label_string'];?><?php echo $ew -> Postal_pobox['node_string'];?></div>
										</div>
										<!-- POBOX row ends -->
										<!-- POBOX Unit/StreetNumber row begins 2 columns-->
										<div class="col-lg-6 col_units">
											<div class="form-group"><?php echo $ew -> Postal_unit['label_string'];?><?php echo $ew -> Postal_unit['node_string'];?></div>
										</div>
										<div class="col-lg-6 col_streetNum">
											<div class="form-group"><?php echo $ew -> Postal_streetNumber['label_string'];?><?php echo $ew -> Postal_streetNumber['node_string'];?></div>
										</div>
										<!-- POBOX Unit/StreetNumber  row ends -->
										<!-- POBOX StreetName row begins single column-->
										<div class="col-xs-12 col_streetName">
											<div class="form-group"><?php echo $ew -> Postal_streetName['label_string'];?><?php echo $ew -> Postal_streetName['node_string'];?></div>
										</div>
										<!-- StreetName row ends -->
										<!-- POBOX StreetType/Suburb row begins 2 columns-->
										<div class="col-lg-6 col_streetType">
											<div class="form-group"><?php echo $ew -> Postal_streetType['label_string'];?><?php echo $ew -> Postal_streetType['node_string'];?></div>
										</div>
										<div class="col-lg-6">
											<div class="form-group"><?php echo $ew -> Postal_suburb['label_string'];?><?php echo $ew -> Postal_suburb['node_string'];?></div>
										</div>
										<!-- POBOX StreetType/Suburb row ends -->
										<!-- POBOX Postcode/State row begins 2 columns-->
										<div class="col-lg-6">
											<div class="form-group"><?php echo $ew -> Postal_postcode['label_string'];?><?php echo $ew -> Postal_postcode['node_string'];?></div>
										</div>
										<div class="col-lg-6">
											<div class="form-group"><?php echo $ew -> Postal_state['label_string'];?><?php echo $ew -> Postal_state['node_string'];?></div>
										</div>

									</div>
								</div>
								<!-- POBOX StreetType/Suburb row ends -->
								<!-- PropertyDetails submit begins -->
								<div class="col-xs-12">
									<div class="form-group"><input type="submit" value="Continue" class="submit-btn btn-border-blue triggerValidation" onclick="" name="PropertyDetails"></div>
								</div>
								<!-- PropertyDetails submit ends -->
                                <div class="clearfix"></div>
							</form>
						</div>

						<h3 id="PlanOptions" class="form-hdg deal_options AC-Primary">Plan Options</h3>
						<div>
							<form action="" method="" class="row clearfix signup_options plan-options-form" name="signup_options">

								<!-- Retailer specific plan options begin -->
								<?php foreach ($ew -> plan_options as $plan_option):?>

								<div class="col-lg-8">
									<div class="form-group"><?php echo $plan_option['label_string'];?></div>
								</div>
								<div class="col-lg-4">
									<div class="form-group"><?php echo $plan_option['node_string'];?></div>
								</div>

								<?php endforeach;?>
								<!-- Retailer specific plan options ends -->

								<div class="col-xs-12">
									<div class="form-group"><input type="submit" value="Continue" class="submit-btn btn-border-blue triggerValidation" onclick="" name="PlanOptions"/></div>
								</div>
                                <div class="clearfix"></div>
							</form>
						</div>

						<h3 id="TACDetails" class="form-hdg TAC AC-Primary">Terms And Conditions</h3>
						<div>
							<form action="" method="" class="row clearfix signup_tac" name="signup_tac">
								<!-- TAC row begins single column-->
								<div class="col-xs-12">
									<div class="form-group">
										<div  style="overflow-y: scroll; height: 205px; -webkit-overflow-scrolling: touch">
										<?php echo $ew -> tac_txt;?>
										</div>
									</div>
								</div>
								<!-- TAC row ends -->
								<!-- TAC submit begins -->
								<div class="col-xs-12">
									<div class="form-group">
										<?php echo $ew -> TermsAndConditions_Confirm['node_string'];?>
									</div>
								</div>
								<!-- TAC submit ends -->
							</form>
							<div class="leftPane-bottom">
								<input type="submit" value="Confirm and Submit" class="btn pd30 orange-button triggerValidation" id="SubmitBtn" name="TACDetails">
							</div>
						</div>
						<!-- First form ends -->
					</div>
				</div>
            </div>
			<!-- Left column ends -->
			<!-- Right column begins -->
			<div class="col-sm-4">
				<div class="form-group">
					<div class="singup-info-box">
                    	<div class="row">
							<div class="col-lg-12">
								<div class="form-group"><p class="summary">Summary</p></div>
							</div>
							<div class="col-lg-12">
								<div class="form-group supplier_logo"><?php echo $this->Html->image("/images/supplier_logo_{$plan_estimates['retailer']}.jpg", array('alt' => $plan_estimates['retailer']));?></div>
							</div>
							<div class="col-lg-12">
								<div class="form-group"><p class="retailer_name"><?php echo $plan_estimates['retailer'];?></p></div>
							</div>
							<div id="elec_cost_estimate">
								<div class="form-group">
									<span>ESTIMATED ELECTRICITY COSTS</span>
									<p class="elecimg"><span class="dollarSign">$ <?php echo $plan_estimates['elec'];?></span></p>
								</div>
							</div>
							<div id="gas_cost_estimate">
								<div class="form-group">
									<span>ESTIMATED GAS COSTS</span>
									<p class="gasimg"><span class="dollarSign">$ <?php echo $plan_estimates['gas']; ?></span></p>
								</div>
							</div>
                    	</div>
					</div>

					<?php /*?><div class="col-md-12 singup-info-box info-bottom">
							<div class="col-sm-6 col-xs-12">
								<div class="form-group">
									<div class="col-md-12">
										<div data-stars="4,5" data-style-width="250px" data-style-height="420px" data-businessunit-id="" data-template-id="" data-locale="en-AU" class="trustpilot-widget" style="position: relative;"></div>
									</div>
								</div>
							</div>
					</div><?php */?>
				</div>
			</div>
			<!-- Right column ends -->
		</div>
	</div>

	<div class="<?php echo $qa_html_navigator;?>">
		<!-- Button trigger modal -->
		<button class="btn btn-primary btn-lg report-issue" data-toggle="modal" data-target="#myModalHorizontal">
			Report an issue
		</button>

		<!-- Modal -->
		<div class="modal fade" id="myModalHorizontal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<!-- Modal Header -->
					<div class="modal-header">
						<button type="button" class="close"
						   data-dismiss="modal">
							   <span aria-hidden="true">&times;</span>
							   <span class="sr-only">Close</span>
						</button>
						<h4 class="modal-title" id="myModalLabel">
							Report an issue
						</h4>
					</div>

					<!-- Modal Body -->
					<div class="modal-body">
						<form class="form-horizontal confirm" role="form">
						  <div class="form-group row">
						  	<div class="col-xs-12"><p class="issue_confirm_txt"></p></div>
						  </div>
						</form>
						<form class="form-horizontal submission_modal" role="form">
						  <div class="form-group">
							<label class="col-sm-2 control-label" for="issue_summary" >Issue Summary</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" id="issue_summary" placeholder="Enter the title of issue"/>
							</div>
						  </div>
						  <div class="form-group">
							<label class="col-sm-2 control-label" for="inputIssueTitle" >Issue Description</label>
							<div class="col-sm-10">
								<textarea class="form-control" rows="15" id="issue_description"></textarea>
							</div>
						  </div>
						</form>
					</div>

					<!-- Modal Footer -->
					<div class="modal-footer submission_modal">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-primary field-validation-submit">Submit</button>
					</div>
					<div class="modal-footer confirm">
						<button type="button" class="btn btn-default btn_confirm_acknowledged" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- Concession JavaScript data begins  -->
	<?php echo $ew -> concessionHiddeninputs;?>
	<script>var selectElementList = <?php echo $ew -> getSelectElementList();?></script>
	<script>var radioElementList = <?php echo $ew -> getRadioElementList();?></script>
	<script>var concessionElementList = <?php echo $ew -> getConcessionElementList();?></script>
	<script>var planOptionsElementList = <?php echo $ew -> getPlanOptionsElementLilst();?></script>
	<script>var planOptionSelectElementList = <?php echo $ew -> getPlanOptionSelectElementList();?></script>
	<!-- Concession JavaScript data ends  -->
	<script>var elementList = <?php echo $ew -> getElementsList();?></script>
	<script>var ui_instructions = <?php echo $ui_instruction_String;?> </script>
	<script>var l_id = <?php echo $l_id;?> </script>
	<script>var consession_removed = <?php echo $ew -> ConcessionCardTypesToBeRemoved;?> </script>
	<script>var preConcessionQuestionOrder = <?php echo $ew -> preConcessionQuestionOrder;?> </script>

</div>
