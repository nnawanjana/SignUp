
<div class="pdt125">


	<nav id="myNavbar" class="navbar navbar-default navbar-inverse navbar-fixed-top" role="navigation">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbarCollapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="#">Deal Expert QA</a>
			</div>
			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="navbarCollapse">
				<ul class="nav navbar-nav">
					<li><a class="active" href="#">Main</a></li>
					<li><a href="#">Logout</a></li>
				</ul>
			</div>
		</div>
	</nav>
	<div class="jumbotron">
		<div class="container-fluid">
			<h1>Deal Expert QA Tool</h1>
			<p class='QA-Project-title'></p>
		</div>
	</div>
	<div class="container-fluid QA-pdt125">
		<div class="row QA-div-row">
			<div class="col-xs-12">

				<div class="text-error">Select Projects</div>
				<select id="qa-projects" name="qa-projects" class="form-control selectpicker show-menu-arrow">
					<option value="">Select</option>
					<option value="1">Online Signup Public</option>
				</select>
			</div>
		</div>

		<div class="row QA-div-row QA-testmenu">
			<div class="col-xs-4">
				<button type="button" class="btn btn-primary btn-lg configure">Update Velocify Lead Status</button>
			</div>
			<div class="col-xs-4">
				<button type="button" class="btn btn-primary btn-lg snapshot-view">View Signup Snapshot</button>
			</div>
			<div class="col-xs-4">
				<button type="button" class="btn btn-primary btn-lg snapshot-csv">Download Snapshot CSV</button>
			</div>
		</div>
		<div class="row QA-div-row QA-testtype">
			<div class="col-xs-8">

				<div class="text-error">Select a test type to perform</div>
				<select id="test_options" name="test_options" class="form-control selectpicker show-menu-arrow">
					<option value="">Select</option>
					<option value="Availability">Online Signup Availability</option>
					<option value="FieldValidation">Online Signup Field Validation</option>
					<option value="VelocifyRequestString">Velocify Request String</option>
					<option value="VelocifyPost">Post to Velocify</option>
					<option value="SnapShot">Compare/Signup Snapshot image</option>
				</select>

			</div>
			<div class="col-xs-4">
				<button type="button" class="btn btn-primary btn-lg proceed">Proceed to test</button>
			</div>
		</div>

		<div class="row QA-div-row QA-testconfig">
			<div class="col-xs-12">
				<!--
				<div class="row">
					<div class="col-xs-6">
						<span>Force Lead id to be 0 (Zero)</span>
					</div>
					<div class="col-xs-6">
						<select id="lead_id" name="lead_id" class="form-control selectpicker show-menu-arrow">
							<option value="">Select</option>
							<option value="1">ON</option>
							<option value="0">OFF</option>
						</select>
					</div>
				</div>
				<div class="row QA-div-row">
					<div class="col-xs-6">
						<span>Define hours for operation</span>
					</div>
					<div class="col-xs-6">
						<select id="operation_hours" name="operation_hours" class="form-control selectpicker show-menu-arrow">
							<option value="">Select</option>
							<option value="1">ON</option>
							<option value="0">OFF</option>
						</select>
					</div>
				</div>

				<div class="row QA-div-row operation-hours">
					<div class="col-xs-6">
						<div class="">Select hour from which the operation to begin</div>
						<select id="operation_begins" name="operation_begins" class="form-control selectpicker show-menu-arrow">
							<option value="">Select</option>
							<option value="8">8:00 am</option>
							<option value="9">9:00 am</option>
							<option value="10">10:00 am</option>
							<option value="11">11:00 am</option>
							<option value="12">Noon</option>
							<option value="13">1:00 pm</option>
							<option value="14">2:00 pm</option>
							<option value="15">3:00 pm</option>
							<option value="16">4:00 pm</option>
							<option value="17">5:00 pm</option>
							<option value="18">6:00 pm</option>
							<option value="19">7:00 pm</option>
							<option value="20">8:00 pm</option>
							<option value="21">9:00 pm</option>
							<option value="22">10:00 pm</option>
							<option value="23">11:00 pm</option>
							<option value="24">0:00 am</option>
						</select>
					</div>
					<div class="col-xs-6">
						<div class="">Select hour from which the operation to end</div>
						<select id="operation_ends" name="operation_ends" class="form-control selectpicker show-menu-arrow">
							<option value="">Select</option>
							<option value="8">8:00 am</option>
							<option value="9">9:00 am</option>
							<option value="10">10:00 am</option>
							<option value="11">11:00 am</option>
							<option value="12">Noon</option>
							<option value="13">1:00 pm</option>
							<option value="14">2:00 pm</option>
							<option value="15">3:00 pm</option>
							<option value="16:">4:00 pm</option>
							<option value="17">5:00 pm</option>
							<option value="18">6:00 pm</option>
							<option value="19">7:00 pm</option>
							<option value="20">8:00 pm</option>
							<option value="21">9:00 pm</option>
							<option value="22">10:00 pm</option>
							<option value="23">11:00 pm</option>
							<option value="24">0:00 am</option>
						</select>
					</div>
				</div>
				-->
				<script>var g = <?php echo $qa_retailers_list;?></script>
				<!--
				<section id="retailers"></section>


				<div class="row QA-div-row">
					<div class="col-xs-4"></div>
					<div class="col-xs-4"><button type="button" class="btn btn-primary btn-lg set_config">Set</button></div>
					<div class="col-xs-4"></div>
				</div>
				-->


				<div class="row QA-div-row velocify-row">
					<div class="col-xs-12"><h5>Velocify SOAP</h5></div>
				</div>

				<div class="row QA-div-row velocify-row">
					<div class="col-xs-6">Operation</div>
					<div class="col-xs-6">
						<select id="soap_operation" name="soap_operation" class="form-control selectpicker show-menu-arrow">
							<option value="">Select</option>
							<option value="ModifyLeadStatus">ModifyLeadStatus</option>
						</select>
					</div>
				</div>
				<!--
				<div class="row QA-div-row velocify-row">
					<div class="col-xs-6">Operation</div>
					<div class="col-xs-6">
						<select id="soap_operation" name="soap_operation" class="form-control selectpicker show-menu-arrow">
							<option value="">Select</option>
							<option value="GetLead">GetLead</option>
							<option value="GetFullLead">GetFullLead</option>
							<option value="ModifyLeadStatus">ModifyLeadStatus</option>
							<option value="GetStatuses">GetStatuses</option>
							<option value="GetStatuseID">GetStatuseID</option>
							<option value="ModifyLeadField">ModifyLeadField</option>
						</select>
					</div>
				</div>
				-->
				<div class="row QA-div-row velocify-row">
					<div class="col-xs-6">Lead ID</div>
					<div class="col-xs-6"><input class="form-control" id="leadid" name="leadid" type="text" placeholder="Enter lead id" value=""></div>
				</div>
				<div class="row QA-div-row velocify-row">
					<div class="col-xs-6">Status ID</div>
					<div class="col-xs-6"><input class="form-control" id="statusid" name="statusid" type="text" placeholder="Enter status id" value=""></div>
				</div>
				<div class="row QA-div-row" style="display:none">
					<div class="col-xs-6">Field ID</div>
					<div class="col-xs-6"><input class="form-control" id="fieldid" name="fieldid" type="text" placeholder="Enter field id" value=""></div>
				</div>
				<div class="row QA-div-row" style="display:none">
					<div class="col-xs-6">Field Value</div>
					<div class="col-xs-6"><input class="form-control" id="fieldvalue" name="fieldvalue" type="text" placeholder="Enter field value" value=""></div>
				</div>

				<div class="row QA-div-row velocify-row">
					<div class="col-xs-10"></div>
					<div class="col-xs-2"><button type="button" class="btn btn-primary btn-lg doSoap">Do Soap</button></div>
				</div>

				<div class="row QA-div-row" style="display:none">
					<div class="col-xs-2"></div>
					<div class="col-xs-2">
						<label for="comment">Output:</label>
						<textarea class="form-control" rows="5" id="output"></textarea>
					</div>
					<div class="col-xs-2"></div>
				</div>
				<!--
				<div class="row QA-div-row velocify-row">
					<div class="col-xs-6">UAT MODE</div>
					<div class="col-xs-6">
						<select id="uat_mode" name="uat_mode" class="form-control selectpicker show-menu-arrow">
							<option value="">Select</option>
							<option value="0">OFF</option>
							<option value="1">ON</option>
						</select>
					</div>
				</div>

				<div class="row QA-div-row velocify-row">
					<div class="col-xs-4"></div>
					<div class="col-xs-4"><button type="button" class="btn btn-primary btn-lg set_mode">Set MODE</button></div>
					<div class="col-xs-4"></div>
				</div>
				-->

			</div>

		</div>


		<hr/>

	</div>


</div>
<script>var g = <?php echo $qa_retailers_list;?></script>
<script>

	var retailer_html_temp = '<div class="row QA-div-row">' +
					'<div class="col-xs-4"><span>@RetailerName</span></div>' +
					'<div class="col-xs-4"><button data="INCLUDE" class="retailer-toggle btn blue-button" name="@RetailerAbbr">INCLUDED</button></div>' +
					'<div class="col-xs-4"></div>' +
				'</div>';

	var auth = 0;
	$( document ).ready(function() {
		$('.QA-testmenu').hide();
		$('.QA-testtype').hide();
		$('.QA-testconfig').hide();
		$('.operation-hours').hide();
		$('.velocify-row').hide();

		for (var i = 0; i<g.length; i++){
		/*
			var retailer_row = retailer_html_temp.replace(/@RetailerName/g, g[i].ps_Retailers.retailer_name);
			retailer_row = retailer_row.replace(/@RetailerAbbr/g, g[i].ps_Retailers.retailer_abbreviation);
			$('#retailers').append(retailer_row);
		*/
		}

		var soap_obj = {};
		var result = $.post( "/qualityassurance/QA_GetCurrentUser", soap_obj, function(msg) {
			if ((msg.outcome == 'OKKEY SUMIYOSHI')||(msg.outcome == 'JOEL KLEASE')){
				auth = 1;
			}
		});


	});

	$(function() {

		$('.retailer-toggle').click(function(){
			var data = $(this).attr('data');
			if (data == 'EXCLUDE'){
				$(this).attr('data','INCLUDE');
				$(this).text('INCLUDE');
			} else {
				$(this).attr('data','EXCLUDE');
				$(this).text('EXCLUDE');
			}
		});
		$( "#qa-projects").change(function() {
			if (parseInt($(this).val()) != ""){

				var data = {
					PROJECT_ID:parseInt($(this).val())
				};
				var jqxhr = $.post( "/qualityassurance/QA_Set_ProjectID", JSON.stringify(data), function(msg) {
					console.log(msg);
				});

				$('.QA-testmenu').show();
			} else {
				$('.QA-testmenu').hide();
				$('.QA-testtype').hide();
				$('.QA-testconfig').hide();
			}

		});

		$( "#operation_hours" ).change(function() {
			if (parseInt($(this).val()) == 1){
				$('.operation-hours').show();
			} else {
				$('.operation-hours').hide();
			}

		});




		$('.issue').click(function(){
			window.location.replace("/QA_Issues");
		});


		$('.test').click(function(){
			$('.QA-testtype').show();
			$('.QA-testconfig').hide();
		});
		
		$('.snapshot-csv').click(function(){
			window.location.replace("/QA_Assess_Snapshot");
		});		
		
		$('.snapshot-view').click(function(){
			window.location.replace("/QA_View_Snapshot");
		});		  

		$('.configure').click(function(){
		$("#lead_id option[value='1']").prop('selected', true);
		$.get("/qualityassurance/QA_GetQAConfig", '', function(response) {
			var obj = JSON.parse(response.RESULT);

				$("#lead_id").find('option').each( function() {
				console.log(obj.CONFIG.VELOCIFY_LEAD + "::" + $(this).val());

					var $this = $(this);
					if ($this.val() == obj.CONFIG.VELOCIFY_LEAD) {
						$this.attr('selected','selected');
						return false;
					}
				});

			$("#lead_id option[value='" + obj.CONFIG.VELOCIFY_LEAD + "']").prop('selected', true);
			$("#operation_hours option[value='" + obj.CONFIG.OPERATION_HOURS + "']").prop('selected', true);
			$("#operation_begins option[value='" + obj.CONFIG.OPERATION_BEGINS + "']").prop('selected', true);
			$("#operation_ends option[value='" + obj.CONFIG.OPERATION_ENDS + "']").prop('selected', true);

			for (var i in obj.RETAILER_QUALIFIER){
				var retailer_name = i.replace(' ','');
				$('button[name=' + retailer_name + ']').attr('data',(obj.RETAILER_QUALIFIER[i] == 1)? "INCLUDED":"EXCLUDED");
				$('button[name=' + retailer_name + ']').text((obj.RETAILER_QUALIFIER[i] == 1)? "INCLUDED":"EXCLUDED");
			}


		});

			$('.QA-testtype').hide();
			$('.QA-testconfig').show();
			if (auth == 1){
				$('.velocify-row').show();
			}



		});

		$('.proceed').click(function(){
			if ($('#test_options').val() =='Availability'){
				window.location.replace("/QA_SignUp?test_type=1");
			};
			if ($('#test_options').val() =='FieldValidation'){
				window.location.replace("/QA_SignUp?test_type=3");
			};

			if ($('#test_options').val() =='VelocifyRequestString'){
				window.location.replace("/QA_SignUp?test_type=8");
			};
			if ($('#test_options').val() =='SnapShot'){
				window.location.replace("/QA_Assess_Snapshot");
			};
			if ($('#test_options').val() =='VelocifyPost'){
				window.location.replace("/QA_SignUp?test_type=9");
			};
		})

		$('.set_config').click(function(){
			var config_obj = {};

			config_obj.velocify_lead = $('#lead_id').val();
			config_obj.operation_hours = $('#operation_hours').val();
			config_obj.operation_begins = $('#operation_begins').val();
			config_obj.operation_ends = $('#operation_ends').val();

			var retailers = new Array();

			for (var i = 0; i<g.length; i++){
				var decision = $('button[name=' + g[i].ps_Retailers.retailer_abbreviation + ']').attr('data');
				var retailer = new Array(g[i].ps_Retailers.retailer_name, decision);
				retailers.push(retailer);
			}
			config_obj.retailers = retailers;
			var result = $.post( "/qualityassurance/QA_SetQACriteria", JSON.stringify(config_obj), function(msg) {

				alert ("configuration successfully set");
				console.log(msg);
			});

		});


		$('.set_mode').click(function(){
			var config_obj = {};

			config_obj.mode_value = $('#uat_mode').val();

			var result = $.post( "/qualityassurance/QA_SetQA_Mode", JSON.stringify(config_obj), function(msg) {

				console.log(msg);
			});

		});

		$('.clear_config').click(function(){
			var config_obj = {};

			var result = $.post( "/qualityassurance/QA_ClearQACriteria", JSON.stringify(config_obj), function(msg) {
				console.log(msg);
			});

		});

		$('.doSoap').click(function(){
			var soap_obj = {};
			soap_obj.operation = $('#soap_operation').val();
			soap_obj.leadid = $('#leadid').val();
			soap_obj.signup_status = $('#statusid').val();
			soap_obj.fieldId = $('#fieldid').val();
			soap_obj.fieldvalue = $('#fieldvalue').val();

			var result = $.post( "/qualityassurance/QA_DoSOAPtoVelocify", soap_obj, function(msg) {
				$('#output').val(msg.outcome);
					alert ("operation complete.  Please review Velocify Field id " + soap_obj.leadid);
			});
		});

		$('.doGetStatusListSoap').click(function(){

			var soap_obj = {};
			var result = $.post( "/qualityassurance/QA_GetVelocifyStatusList", soap_obj, function(msg) {
				var parser = new DOMParser();
				var xmlDoc = parser.parseFromString(msg.outcome,"text/xml");
				console.log(xmlDoc);
			});
		});



	});

</script>
