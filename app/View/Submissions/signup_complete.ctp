<?php echo $this->element('submissions_header');?>
<div class="pdt125">
	<section class="bread-cum-wrap show-big">
		<div class="container-fluid">
			<ul class="bread-cum ml20">
				<li class="blue-text"> <a href="https://<?php echo WEBSITE_COMPARE_DOMAIN_NAME; ?>/about-you"> About You </a></li>
            	<li class="blue-text"> <a href="https://<?php echo WEBSITE_COMPARE_DOMAIN_NAME; ?>/product-options"> Product Features </a></li>
            	<li class="blue-text"> <a href="https://<?php echo WEBSITE_COMPARE_DOMAIN_NAME; ?>/compare-plans"> Product Compare </a></li>
           		<li>Online Signup</li>
			</ul>
		</div>
	</section>
	<div class="container compare">
		<div class="row">
			<!-- Left column begins -->
			<div class="col-sm-8 franco-660">
				<div class="form-group leftPane">
					<p class="leftPaneHeader">Thank you! Your transfer is underway</p>

                    <div class="thank_element">
                    	<h4>What happens next?</h4>
                        <p>From here, Deal Expert will begin to process your information. We may need to contact you if needed, but otherwise we'll send your details to your chosen retailer.</p>
						<p>We’ll keep you updated of the process via email, so be sure to keep an eye on your inbox.</p>
                        <p>If you have any questions, please call our call centre on <a href="tel:1300087011">1300 087 011</a>.</p>
                    </div>
                    <div class="thank_element">
                    	<h4>Your welcome pack</h4>
                        <p>Once your chosen retailer receives your transfer details they’ll send out your welcome pack which will contain:</p>
                        <ul>
                            <li>Your chosen plan’s rates</li>
                            <li>The terms and conditions of your plan</li>
                            <li>Your customer charter</li>
                            <li>A 10-business day cooling off period, which starts from the day you receive the welcome pack</li>
                            <li>Any other information relevant to your new plan.</li>
                        </ul>
                    </div>
                    <div class="thank_element">
                    	<h4>Latest testimonials</h4>
                        <div data-stars="4,5" data-style-width="100%" data-style-height="420px" data-businessunit-id="" data-template-id="" data-locale="en-AU" class="trustpilot-widget" style="position: relative;"></div>
                    </div>
                    <div class="thank_element">
                    	<h4>Follow us!</h4>
                        <p align="center"><?php echo $this->Html->image("/img/btn-facebook.png");?></p>
                    </div>
                </div>
            </div>
            <!-- Left column ends -->
            <!-- Right column begins -->
			<div class="col-sm-4">
				<div class="form-group">
					<div class="singup-info-box">
                    	<div class="row">
							<div class="col-sm-12">
								<div class="form-group"><p class="summary">Summary</p></div>
							</div>
							<div class="col-sm-12">
								<div class="form-group supplier_logo"><?php echo $this->Html->image("/images/supplier_logo_{$plan_estimates['retailer']}.jpg", array('alt' => $plan_estimates['retailer']));?>
</div>
							</div>
							<div class="col-sm-12">
								<div class="form-group"><p class="retailer_name"><?php echo $plan_estimates['retailer'];?></p></div>
							</div>
							<div id="elec_cost_estimate" class="col-sm-6">
								<div class="form-group">
									<span>ESTIMATED ELECTRICITY COSTS</span>
									<p class="elecimg"><span class="dollarSign">$ <?php echo $plan_estimates['elec'];?></span></p>
								</div>
							</div>
							<div id="gas_cost_estimate" class="col-sm-6">
								<div class="form-group">
									<span>ESTIMATED GAS COSTS</span>
									<p class="gasimg"><span class="dollarSign">$ <?php echo $plan_estimates['gas']; ?></span></p>
								</div>
							</div>
                    	</div>
					</div>
				</div>
			</div>
			<!-- Right column ends -->
    	</div>
	</div>
</div>
<script>var ui_instructions = <?php echo $ui_instruction_String;?> </script>
<script>var l_id = "";</script>
