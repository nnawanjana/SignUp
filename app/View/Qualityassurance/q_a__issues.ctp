
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
					<li class="active"><a href="#">Home</a></li>
					<li><a href="#">About</a></li>
					<li><a href="#">Contact</a></li>
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

		<div class="row">
			<div class="col-xs-12">
				<h2>ISSUES</h2>
			</div>			
		</div>
		<div class="row">
			<div class="col-xs-1"><span>ID</span></div>
			<div class="col-xs-2"><span>ISSUE TITLE</span></div>
			<div class="col-xs-4"><span>ISSUE DESCRIPTION</span></div>
			<div class="col-xs-1"><span>ISSUE RECORDED ON</span></div>
			<div class="col-xs-1"><span>ISSUE CURRENT STATUS</span></div>
			<div class="col-xs-2"><span>ISSUE OWNED BY</span></div>
			<div class="col-xs-1"><span>VIEW TEST CASE/RESULTS</span></div>																	
		</div>			
			

		
		<hr>
	
	</div>


     



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
					<form class="form-horizontal" role="form">
					  <div class="form-group">
						<div class="col-sm-12 test-case">
							
						</div>
					  </div>
					  <div class="form-group">
						<div class="col-sm-12 test-result">
							
						</div>
					  </div>                  
					</form>
				</div>
		
				<!-- Modal Footer -->
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	function proceed_to_response(_id){
		window.location.replace("/QA_Issue_Response?issue_id=" + _id);
	}
	
	function getAssociatedInformation(_id){
		var obj = {
			RESULT_ID:_id
		}
		var result = $.post( "/qualityassurance/QA_getTestInformation", JSON.stringify(obj), function(msg) {
		var t_result = JSON.parse(msg.RESULT.QA_TEST_RESULT);
		var t_case = JSON.parse(msg.RESULT.QA_TEST_CASE);
		var f = JSON.parse(t_case.content.qa_testcases.QA_TEST_CASE_XML);
			$('.test-result').empty();
			$('.test-case').empty();
			$('.test-result').append('<p>TEST RESULT</p><xmp>"' + t_result.content + '"</xmp>');
			$('.test-case').append('<p>TEST DATA</p><xmp>"' + JSON.stringify(f, null, "\t") + '"</xmp>');
			
			

		});		
	
	}
	$( document ).ready(function() {
		$('.modal-dialog').css('width', '80%');
		$.get( "/qualityassurance/QA_getIssueList", function( data ) {
		
			var row_tamplateHTML = '<div class="row QA-issue-item-row">' +
									'<div class="col-xs-1"><a href="#" class="issue-item" onclick="proceed_to_response(@ID)">@ID</a></div>' +
									'<div class="col-xs-2"><a href="#" class="issue-item" onclick="proceed_to_response(@ID)">@ISSUE_TITLE</a></div>' +
									'<div class="col-xs-4"><a href="#" class="issue-item" onclick="proceed_to_response(@ID)">@ISSUE_DESCRIPTION</a></div>' +
									'<div class="col-xs-1"><a href="#" class="issue-item" onclick="proceed_to_response(@ID)">@ISSUE_RECORDED_ON</a></div>' +
									'<div class="col-xs-1"><a href="#" class="issue-item" onclick="proceed_to_response(@ID)">@ISSUE_CURRENT_STATUS</a></div>'+	
									'<div class="col-xs-2"><a href="#" class="issue-item" onclick="proceed_to_response(@ID)">@ISSUE_OWNED_BY</a></div>'+
									'<div class="col-xs-1"><button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#myModalHorizontal" onclick="getAssociatedInformation(@RESULT_ID)">VIEW</button></div>'+																
									'</div>';
			
			var mapStatus = function (id_num){
				for (var t = 0; t<results.list_status.length; t++){
					if (parseInt(id_num) == parseInt(results.list_status[t].qa_issue_status.STATUS_ID)){
						return results.list_status[t].qa_issue_status.STATUS;
					}
				}
			}
			

						
			var results = JSON.parse(data.RESULT);
			
			for (var i = 0; i<results.issues.length; i++){
				
				results.issues[i].qa_issues.ISSUE_STATUS = mapStatus(results.issues[i].qa_issues.ISSUE_STATUS);
				results.issues[i].qa_issues.ISSUE_OWNER = results.users['ISSUE_ID_' + parseInt(results.issues[i].qa_issues.ISSUE_ID)];
				
				var temp_timestamp_cont = results.issues[i].qa_issues.ISSUED_DATE.split(' ');
				var temp_date_cont = temp_timestamp_cont[0].split('-');
				results.issues[i].qa_issues.ISSUED_DATE = temp_date_cont[2] + '-' + temp_date_cont[1] + '-' + temp_date_cont[0] + ' ' + temp_timestamp_cont[1];
				
				var issue_row = '';
				issue_row = row_tamplateHTML.replace(/@ID/g, results.issues[i].qa_issues.ISSUE_ID);
				issue_row = issue_row.replace('@ISSUE_TITLE', results.issues[i].qa_issues.ISSUE_TITLE);
				issue_row = issue_row.replace('@ISSUE_DESCRIPTION', results.issues[i].qa_issues.ISSUE_DESCRIPTION);
				issue_row = issue_row.replace('@ISSUE_RECORDED_ON', results.issues[i].qa_issues.ISSUED_DATE);
				issue_row = issue_row.replace('@ISSUE_CURRENT_STATUS', results.issues[i].qa_issues.ISSUE_STATUS);
				issue_row = issue_row.replace('@ISSUE_OWNED_BY', results.issues[i].qa_issues.ISSUE_OWNER);
				issue_row = issue_row.replace('@RESULT_ID', results.issues[i].qa_issues.ISSUE_RESULT_ID);
				
				$('.issues').append(issue_row);
			
			}
		});	

	});

	$(function() {


	});

</script>
