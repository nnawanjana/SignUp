
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
					<li><a href="QA_Main">Main</a></li>
					<li><a href="#">Logout</a></li>
				</ul>
			</div>
		</div>
	</nav>
	<div class="jumbotron">
		<div class="container-fluid">
			<h1>Deal Expert UAT Tool</h1>
			<p class='QA-Project-title'></p>
		</div>
	</div>
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-4 test-data-section">
				<h2>TEST DATA</h2>
				<dl class="dl-horizontal">
				  <dt>test ...</dt>
				  <dd>data ...</dd>
				</dl>
			</div>
			<div class="col-xs-8">
				<h2>RESULT</h2>
				<h4 id="leadid_container"></h4>
				<div id="results" class="results"></div>
			</div>
		</div>
		<hr>
		<div class="row">
			<div class="col-xs-4">
				<ul class="pagination"></ul>
			</div>
			<div class="col-xs-4">
				<button type="button" class="btn btn-primary btn-lg report-issue" data-toggle="modal" data-target="#myModalHorizontal">Report issue</button>
			</div>
			<div class="col-xs-4">
				<button type="button" class="btn btn-primary btn-lg confirmPass">Confirm this test case with "PASS"</button>
			</div>
		</div>
	</div>






<script>var local_qa_config = <?php echo $local_qa_config;?></script>
<script>var g = <?php echo $test_cases;?></script>
<script>
	var current_result;

	var ui_filed_map = {};
	var labels = "title,firstName,surName,dob,mobileNumber,secondayContactNumber,email,document_type,documentIdNumber,documentExpiry,documentState,documentCountry,concessionCardIssuer,concessionCardType,PersonalDetails_concession_confirm,nameOnConcessionCard,concessionCardNumber,concessionCardStartDate,concessionCardExpiry,Property_unit,Property_lot,Property_floor,Property_buildingName,Property_streetNumber,Property_streetName,Property_streetType,Property_suburb,Property_postCode,Property_state,NMI,MIRN,Property_rentown,Property_postalAddress_different,Postal_pobox,Postal_unit,Postal_streetNumber,Postal_streetName,Postal_streetType,Postal_suburb,Postal_postcode,Postal_state,TermsAndConditions_Confirm,PersonalDetails_concession_details,Do you want to register for e-billing?,Would you like your welcome pack emailed to you?,Do you want to opt out of marketing material from [retailer]?,Do you have a FlyBuys account? (optional),Link your FlyBuys account to receive 1 point for every $1 spent on your energy bill.,FlyBuys Membership Number,Sumo Power will send your bills monthly. Would you like your bills and welcome pack to be delivered via mail or email?,Are you authorised to make this change to the energy account/s at this property?,Secret Question,Secret Answer,Other than some important letters, Origin will arrange to send all correspondence and bills to you via email, meaning you won't receive a paper bill.  is this ok?";
	var unique_names = 'f_43,f_44,f_45,f_46,f_47,f_48,f_49,f_56,f_57,f_58,f_59,f_60,f_61,f_62,f_97,f_63,f_64,f_65,f_66,f_67,f_68,f_69,f_70,f_71,f_72,f_73,f_74,f_75,f_76,f_94,f_95,f_96,f_98,f_77,f_78,f_82,f_83,f_84,f_85,f_86,f_87,f_99,f_100,p_01,p_02,p_03,p_04,p_05,p_06,p_07,p_08,p_09,p_10,p_11';

	var label_array = labels.split(',');
	var unique_names_array = unique_names.split(',');


	for (var t = 0; t< label_array.length; t++){
		ui_filed_map[unique_names_array[t]] = label_array[t];
	}

	$( document ).ready(function() {
		$('.modal-dialog').css('width', '80%');
		//initialisation
		$('.issue_report_board').hide();


		for (var i = 0; i< g.results.length; i++){
			$(".pagination").append("<li><a class='pagenator' name='" + JSON.stringify(g.results[i]) + "'>" + (i+1) + "</a></li>");
		}

		//Must update this
		current_result = g.results[0];

		var result = $.post( "/qualityassurance/QA_Retrieve_Testcase", g.results[0], function(msg) {

			var parser = new DOMParser();
			var xmlDoc = parser.parseFromString(msg.RESULT,"text/xml");
			var dataNodes = xmlDoc.getElementsByTagName('data')[0];
			var signup_image_id = dataNodes.getAttribute('signup_image_id');

			for (var j = 0; j< dataNodes.children.length; j++){
				$('.results').append('<dl class="dl-horizontal"><dt class="dt-velocify-request">' + dataNodes.children[j].nodeName + '</dt><dd class="dd-velocify-request">' + dataNodes.children[j].textContent + '</dd></dl>');

			}


			var test_data = JSON.parse(msg.CASE);




			for (var i in test_data.submission){
				$('.test-data-section').append('<dl class="dl-horizontal"><dt>' + ui_filed_map[i] + '</dt><dd>' + test_data.submission[i] + '</dd></dl>');
			}

			var query_id = {};
			query_id.signup_image_id = signup_image_id;
			var signup_id_query = $.post( "/qualityassurance/QA_GetLeadIdBySignUpImage", query_id, function(signup_id_msg) {
				$('#leadid_container').text("Velocify Lead ID = " + signup_id_msg.LEAD_ID);

			});

		});



	});

	$(function() {


		$('.confirmPass').click(function(){
			var qa_param = {};
			qa_param.CASE_ID = current_result.CASE_ID;
			qa_param.RESULT_ID = current_result.RESULT_ID;
			qa_param.USER_ID = local_qa_config.USER_ID;

			var result = $.post( "/qualityassurance/QA_Confirm_PASS", JSON.stringify(qa_param), function(msg) {

				alert("Case ID:" + msg.CASE_ID + " has been successfully marked as PASS");
			});
		});

		$('.report-issue').click(function(){
			$('#issue_summary').val('');
			$('#issue_description').val('');
			$('.issue_report_board').show();
			$('.submission_modal').show();
			$('.confirm').hide();
		});

		$('.register_issue').click(function(){

			var qa_param = {};
			qa_param.CASE_ID = current_result.CASE_ID;
			qa_param.RESULT_ID = current_result.RESULT_ID;
			qa_param.ISSUE_TITLE = $('#issue_summary').val();
			qa_param.ISSUE_DESCRIPTION = $('#issue_description').val();

			var result = $.post( "/qualityassurance/QA_Register_Issue", JSON.stringify(qa_param), function(msg) {

				console.log(msg);
			});

		});

		$('.field-validation-submit').click(function(){

			var qa_param = {};
			qa_param.CASE_ID = current_result.CASE_ID;
			qa_param.RESULT_ID = current_result.RESULT_ID;
			qa_param.ISSUE_TITLE = $('#issue_summary').val();
			qa_param.ISSUE_DESCRIPTION = $('#issue_description').val();

			$('.submission_modal').show();
			$('.confirm').hide();

			var result = $.post( "/qualityassurance/QA_Register_Issue", JSON.stringify(qa_param), function(msg) {

				$('.submission_modal').hide();
				$('.confirm').show();
				$('.issue_confirm_txt').empty();
				$('.issue_confirm_txt').text("The issue is successfully registered with the id number:" + msg.QA_ISSUE_ID);
				console.log(msg);
			});


		});

		$('.pagenator').click(function(){
			var obj = JSON.parse($(this).attr('name'));
			current_result = obj;

			console.log(obj);

			var result = $.post( "/qualityassurance/QA_Retrieve_Testcase", obj, function(msg) {

				var parser = new DOMParser();
				var xmlDoc = parser.parseFromString(msg.RESULT,"text/xml");
				var dataNodes = xmlDoc.getElementsByTagName('data')[0];

				var signup_image_id = dataNodes.getAttribute('signup_image_id');

				$('.results').empty();
				$('.test-data-section').empty();

				for (var j = 0; j< dataNodes.children.length; j++){
					$('.results').append('<dl class="dl-horizontal"><dt class="dt-velocify-request">' + dataNodes.children[j].nodeName + '</dt><dd class="dd-velocify-request">' + dataNodes.children[j].textContent + '</dd></dl>');

				}


				var test_data = JSON.parse(msg.CASE);




				for (var i in test_data.submission){
					$('.test-data-section').append('<dl class="dl-horizontal"><dt>' + ui_filed_map[i] + '</dt><dd>' + test_data.submission[i] + '</dd></dl>');
				}

				var query_id = {};
				query_id.signup_image_id = signup_image_id;

				var signup_id_query = $.post( "/qualityassurance/QA_GetLeadIdBySignUpImage", query_id, function(signup_id_msg) {
					$('#leadid_container').text("Velocify Lead ID = " + signup_id_msg.LEAD_ID);

				});





			});


		});

	});

</script>
</div>
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
						<h2 class="modal-title" id="myModalLabel">
							TEST CASE/RESULT
						</h2>
					</div>

					<!-- Modal Body -->
					<div class="modal-body">

						<form class="form-horizontal confirm" role="form">
						  <div class="form-group row">
						  	<div class="col-xs-12"><p class="issue_confirm_txt"></p></div>
						  </div>
						</form>
						<form class="form-horizontal submission_modal" role="form">
						  <div class="form-group row">
						  	<div class="col-xs-2"><label class="" for="issue_summary" >Issue Summary</label></div>

							<div class="col-xs-10">
								<input type="text" class="form-control" id="issue_summary" placeholder="Enter the title of issue"/>
							</div>
						  </div>
						  <div class="form-group row">
						  <div class="col-xs-2"><label class="" for="issue_summary" >Issue Description</label></div>
							<div class="col-xs-10">
								<textarea class="form-control" rows="15" id="issue_description"></textarea>
							</div>
						  </div>
						</form>
					</div>

					<!-- Modal Footer -->
					<div class="modal-footer submission_modal">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary field-validation-submit">Submit</button>
					</div>
					<div class="modal-footer confirm">
						<button type="button" class="btn btn-default btn_confirm_acknowledged" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
