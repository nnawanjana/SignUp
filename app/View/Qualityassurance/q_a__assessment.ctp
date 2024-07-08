
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
		<!--
		<div class="row" id="issue_report_board">
			<div class="col-xs-12 issue_report_board">
				<div class="form-group">
					<label class="col-sm-2 control-label">ISSUE SUMMARY</label>
					<div class="col-sm-10">
						<input class="form-control" id="issue_summary" type="text" value="Enter Summary...">
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 issue_report_board">
				<div class="form-group">
					<label class="col-sm-2 control-label">ISSUE DESCRIPTION</label>
					<div class="col-sm-10">
						<textarea class="form-control" rows="15" id="issue_description"></textarea>
					</div>
				</div>				
			</div>		
		</div>
		<div class="row">
			<div class="col-xs-4">			
			</div>
			<div class="col-xs-4">	
				<button type="button" class="btn btn-primary btn-lg register_issue">Submit</button>		
			</div>	
			<div class="col-xs-4">
				<button type="button" class="btn btn-primary btn-lg cancel_issue">Cancel</button>			
			</div>									
		</div>		
		-->
	</div>
	<div class="">
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
	</div>





<script>var local_qa_config = <?php echo $local_qa_config;?></script>
<script>var g = <?php echo $test_cases;?></script>
<script>
	var current_result;
	
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
			

			var target = document.getElementById('results');
			
			var html = xmlDoc.getElementById('purified');
			

			$('#results').append(xmlToString(html));

			
			var test_data = JSON.parse(msg.CASE);
			

			for (var i in test_data.aboutyou){
				$('.test-data-section').append('<dl class="dl-horizontal"><dt>' + i + '</dt><dd>' + test_data.aboutyou[i] + '</dd></dl>');
			}
			for (var i in test_data.productoption){
				$('.test-data-section').append('<dl class="dl-horizontal"><dt>' + i + '</dt><dd>' + test_data.productoption[i] + '</dd></dl>');
			}
			for (var i in test_data.personalinformation){
				$('.test-data-section').append('<dl class="dl-horizontal"><dt>' + i + '</dt><dd>' + test_data.personalinformation[i] + '</dd></dl>');
			}						
			
		});

	});

	$(function() {
	/*
		$('.pagenator').click(function(){
			current_result = JSON.parse($(this).attr('name'));
			var result = $.post( "/qualityassurance/QA_Retrieve_Testcase", current_result, function(msg) {

				var parser = new DOMParser();
				var xmlDoc = parser.parseFromString(msg.RESULT,"text/xml");
				var html = xmlDoc.getElementById('purified');
				
				var target = document.getElementById('results');
				$(tatget).empty();
				
				target.appendChild(html);
			});		
		});
	*/
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
				var html = xmlDoc.getElementById('purified');
			
				$('#results').empty();
				$('.test-data-section').empty();
			
				var target = document.getElementById('results');
			
				var html = xmlDoc.getElementById('purified');
			

				$('#results').append(xmlToString(html));

			
				var test_data = JSON.parse(msg.CASE);
			

				for (var i in test_data.aboutyou){
					$('.test-data-section').append('<dl class="dl-horizontal"><dt>' + i + '</dt><dd>' + test_data.aboutyou[i] + '</dd></dl>');
				}
				for (var i in test_data.productoption){
					$('.test-data-section').append('<dl class="dl-horizontal"><dt>' + i + '</dt><dd>' + test_data.productoption[i] + '</dd></dl>');
				}
				for (var i in test_data.personalinformation){
					$('.test-data-section').append('<dl class="dl-horizontal"><dt>' + i + '</dt><dd>' + test_data.personalinformation[i] + '</dd></dl>');
				}						
			
			});			
			
			
		});
	});

</script>
</div>
