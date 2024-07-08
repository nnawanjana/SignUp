
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
	<div class="container-fluid QA-pdt125 issues">

		<div class="row issue-response-header">
			<div class="col-xs-2">
				<h5>ISSUES - ID NUMBER:</h5>
				<p class='QA-Issue-Paragraph p-issue-id'></p>
			</div>
			<div class="col-xs-5">
				<h5>ISSUES - TITLE:</h5>
				<p class='QA-Issue-Paragraph p-issue-title'></p>
			</div>	
			<div class="col-xs-5">
				<h5>ISSUES - DESCRIPTION:</h5>
				<p class='QA-Issue-Paragraph p-issue-description'></p>
			</div>				
						
		</div>
		
		<div class="row">
			<div class="col-xs-12 list-response">
				<div class="row">
					<div class="col-xs-1"><span>RESPONSE ID</span></div>	
					<div class="col-xs-2"><span>RESPONSE RECORDED ON</span></div>		
					<div class="col-xs-5"><span>RESPONSE/ACTION</span></div>
					<div class="col-xs-2"><span>ACTION TAKEN BY</span></div>
					<div class="col-xs-2"><span>STATUS CHANGED TO</span></div>																	
				</div>			
			</div>																	
		</div>
				

		
		
		<div class="row QA-new-response-row QA-new-response-row-divider">
			<div class="col-xs-10"></div>
			<div class="col-xs-2"><button type="button" class="btn btn-primary btn-lg new-response">Create a new response</button></div>																		
		</div>					


		<div class="row qa-response-description response-divider">
			<div class="col-xs-9">
				<h3>Description</h3>
			</div>
			<div class="col-xs-3">
				<h3>Change status to..</h3>
			</div>					
			
		</div>			
		<div class="row qa-response-description">

			<div class="col-xs-9">
				<textarea class="form-control" rows="15" id="response_description"></textarea>
			</div>
			<div class="col-xs-3">
				<select id="issue_status" name="issue_status" class="form-control selectpicker show-menu-arrow">
					<option value="">Select</option>
					<option value="1">OPEN</option>
					<option value="2">FIX IN PROGRESS</option>
					<option value="3">RE-TEST</option>
					<option value="4">CLOSED</option>
				</select>
			</div>					
			
		</div>
		<div class="row qa-response-description QA-new-response-row-divider">
			<div class="col-xs-12 issue_report_board">
				<div class="form-group">
					<div class="col-sm-10"></div>				
					<div class="col-sm-1">
						<button type="button" class="btn btn-primary btn-lg new-response-cancel">Cancel</button>
					</div>
					<div class="col-sm-1">
						<button type="button" class="btn btn-primary btn-lg new-response-submit">Submit</button>
					</div>
				</div>				
			</div>		
		</div>
		
		<hr>
	
	</div>
<!--
			<div class="container-fluid QA-pdt125 validations">
				<div class="row">
					<div class="col-xs-2"></div>
					<div class="col-xs-8"></div>
					<div class="col-xs-2">
					</div>				
				</div>
			</div>'
     -->





</div>
<script>var issue_id = <?php echo $issue_id;?></script>
<script>
	
	$( document ).ready(function() {
	
		$('.qa-response-description').hide();
		
		$.get( "/qualityassurance/QA_getIssueByID?issue_id=" + issue_id, function( data ) {
			var results = JSON.parse(data.RESULT);
			
			console.log(results);
			$('.p-issue-id').text(results.issues.qa_issues.ISSUE_ID);
			$('.p-issue-title').text(results.issues.qa_issues.ISSUE_TITLE);
			$('.p-issue-description').text(results.issues.qa_issues.ISSUE_DESCRIPTION);

		});
		
		$.get( "/qualityassurance/QA_getIssueResponses?issue_id=" + issue_id, function( data ) {
			var results = JSON.parse(data.RESULT);

			var row_tamplateHTML = '<div class="row QA-issue-item-row">' +
									'<div class="col-xs-1"><a href="#" class="issue-item">@ID</a></div>' +
									'<div class="col-xs-2"><a href="#" class="issue-item">@RESPONSE_RECORDED_ON</a></div>' +
									'<div class="col-xs-5"><a href="#" class="issue-item">@RESPONSE_ACTION</a></div>' +
									'<div class="col-xs-2"><a href="#" class="issue-item">@ACTION_TAKEN_BY</a></div>' +
									'<div class="col-xs-2"><a href="#" class="issue-item">@STATUS_CHANGED_TO</a></div>' +														
									'</div>';
												
			var status_options = [
				{value:1, label:"OPEN"},
				{value:2, label:"FIX IN PROGRESS"},
				{value:3, label:"RE-TEST"},
				{value:4, label:"CLOSED"},
			];
			
			var mapStatus = function(_val){
				for (var q = 0; q<status_options.length; q++){
					if (status_options[q].value == parseInt(_val)){
						return status_options[q].label;
					}
				}
			}
			

					
					
			console.log(results);
			for (var i = 0; i<results.responses.length; i++){
				

				results.responses[i].qa_issue_response_logs.RESPONSE_RECORDED_BY = results.users['RESPONSE_LOG_ID_' + results.responses[i].qa_issue_response_logs.RESPONSE_LOG_ID];
				
				
				results.responses[i].qa_issue_response_logs.ACTION_STATUS = mapStatus(results.responses[i].qa_issue_response_logs.ACTION_STATUS);
				
				
				var temp_timestamp_cont = results.responses[i].qa_issue_response_logs.RESPONSE_DATE.split(' ');
				var temp_date_cont = temp_timestamp_cont[0].split('-');
				results.responses[i].qa_issue_response_logs.RESPONSE_DATE = temp_date_cont[2] + '-' + temp_date_cont[1] + '-' + temp_date_cont[0] + ' ' + temp_timestamp_cont[1];
				
				var response_row = '';
				response_row = row_tamplateHTML.replace('@ID', results.responses[i].qa_issue_response_logs.RESPONSE_LOG_ID);
				response_row = response_row.replace('@RESPONSE_RECORDED_ON', results.responses[i].qa_issue_response_logs.RESPONSE_DATE);
				response_row = response_row.replace('@RESPONSE_ACTION', results.responses[i].qa_issue_response_logs.RESPONSE_DESCRIPTION);
				response_row = response_row.replace('@ACTION_TAKEN_BY', results.responses[i].qa_issue_response_logs.RESPONSE_RECORDED_BY);
				response_row = response_row.replace('@STATUS_CHANGED_TO', results.responses[i].qa_issue_response_logs.ACTION_STATUS);
				$('.list-response').append(response_row);
			
			}			
			
			
		});	
		

	});

	$(function() {
	
		var row_tamplateHTML = '<div class="row QA-issue-item-row">' +
									'<div class="col-xs-1"><a href="#" class="issue-item">@ID</a></div>' +
									'<div class="col-xs-2"><a href="#" class="issue-item">@RESPONSE_RECORDED_ON</a></div>' +
									'<div class="col-xs-5"><a href="#" class="issue-item">@RESPONSE_ACTION</a></div>' +
									'<div class="col-xs-2"><a href="#" class="issue-item">@ACTION_TAKEN_BY</a></div>' +
									'<div class="col-xs-2"><a href="#" class="issue-item">@STATUS_CHANGED_TO</a></div>' +														
									'</div>';
		
		$('.new-response').click(function(){
			$('#response_description').val('');
			$('.qa-response-description').show();
		});
		
		
		$('.new-response-submit').click(function(){
			
			if ($('#issue_status').val() == ''){
				console.log('select is null');
				return false;
			}
			if ($('#response_description').val() == ''){
				console.log('no text entered');
				return false;
			}			
			
			
			var QA_data = {};
			QA_data.RESPONSE_DESCRIPTION = $('#response_description').val();
			QA_data.ISSUE_ID = issue_id;
			QA_data.STATUS = $('#issue_status').val();
			
			
			
			var jqxhr = $.post( "/qualityassurance/QA_Register_Reponse", JSON.stringify(QA_data), function(msg) {

					var obj = JSON.parse(msg.RESULT);


					var temp_timestamp_cont = obj.timestamp.split(' ');
					var temp_date_cont = temp_timestamp_cont[0].split('-');
					var time_stamp = temp_date_cont[2] + '-' + temp_date_cont[1] + '-' + temp_date_cont[0] + ' ' + temp_timestamp_cont[1];
					
					
					//obj.response_id
					//obj.recorded_by
					//
					
					var response_row = '';
					response_row = row_tamplateHTML.replace('@ID', obj.response_id);
					response_row = response_row.replace('@RESPONSE_RECORDED_ON', time_stamp);
					response_row = response_row.replace('@RESPONSE_ACTION', QA_data.RESPONSE_DESCRIPTION);
					response_row = response_row.replace('@ACTION_TAKEN_BY', obj.recorded_by);
					response_row = response_row.replace('@STATUS_CHANGED_TO', $("#issue_status option[value='" + QA_data.STATUS + "']").text());
					$('.list-response').append(response_row);	
					
					
					$('#issue_status option[value=""]').attr("selected",true);	
					$('#response_description').val('');
					$('.qa-response-description').hide();								
	
				})		
		
		});


	});

</script>
