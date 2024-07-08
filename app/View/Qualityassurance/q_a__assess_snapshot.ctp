
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
			<h1>Deal Expert QA Tool</h1>
			<p class='QA-Project-title'></p>
		</div>
	</div>
	<div class="container-fluid" style="padding-left:50px;">
		<div class="row">
			<div class="col-xs-12 test-data-section">
				<h2>Search BY Lead ID</h2>
			</div>		
		</div>
		<div class="row">
			<div class="col-xs-4 test-data-section">
				<p>Lead id</p>
				<input class="form-control" id="leadid" name="leadid" type="text" placeholder="Enter lead id(s), if multiple, separate them with comma" value="">
			</div>
			<div class="col-xs-4 test-data-section">
				<p>File name</p>
				<input class="form-control" id="csvName" name="csvName" type="text" placeholder="Assign a file name here" value="">			
			</div>
			<div class="col-xs-4 test-data-section"></div>			
		</div>
		<div class="row" style="margin-top:20px;">
			<div class="col-xs-8 test-data-section">
				<button type="button" class="btn btn-primary btn-block search-lead-id">Search</button>
			</div>
			<div class="col-xs-4 test-data-section">
			</div>			
		</div>
	</div>
	





<script>
	var current_result;


	$( document ).ready(function() {




	});

	$(function() {


		$('.confirmPass').click(function(){
			var qa_param = {};
			qa_param.CASE_ID = current_result.CASE_ID;
			qa_param.RESULT_ID = current_result.RESULT_ID;
			qa_param.USER_ID = local_qa_config.USER_ID;

			var result = $.post( "/qualityassurance/QA_Confirm_PASS", JSON.stringify(qa_param), function(msg) {

				console.log(msg);
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
		$('.search-leads-by_date-range').click(function(){
			var qa_param = {};
			qa_param.leadid = $('#leadid').val();
			
			var result = $.post( "/qualityassurance/QA_SearchLeadsByDateRange", qa_param, function(msg) {
				console.log(msg);	
			});		
		});
		$('.search-lead-id').click(function(){
			if ($('#csvName').val() == ""){
				alert ("Enter file name");

				return;
			} else {
				var s_index = $('#csvName').val().indexOf(".csv");
				var l_index = $('#csvName').val().indexOf(".CSV");
				if ((s_index < 0)&&(l_index < 0)){
					alert ("'.csv' file extension is required");
					return;
				}
			}


						
			if ($('#leadid').val() == ""){
				alert ("Enter lead id");
				return;
			}
			
			var qa_param = {};
			qa_param.leadid = $('#leadid').val();
			
			var result = $.post( "/qualityassurance/QA_PDF", qa_param, function(msg) {
				var blob = new Blob([msg.RESULT], {type: "text/csv;charset=utf-8"});
				saveAs(blob, $('#csvName').val());				
			});
		
			///window.location.replace("/QA_PDF?leadid=" + $('#leadid').val());
		})

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

			QA_Retrieve_Testcase();



		});


	});

</script>
</div>
