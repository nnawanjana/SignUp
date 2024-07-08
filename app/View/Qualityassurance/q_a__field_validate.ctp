
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
				<button type="button" class="btn btn-primary btn-lg confirmPass">Confirm this test case with "PASS"</button>
				
			</div>
			<div class="col-xs-4">
				<button type="button" class="btn btn-primary btn-lg report_issue">Report issue</button>
				
			</div>			
		</div>
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
				<button type="button" class="btn btn-primary btn-lg register_issue">Register Issue</button>		
			</div>	
			<div class="col-xs-4">
				<button type="button" class="btn btn-primary btn-lg cancel_issue">Cancel</button>			
			</div>									
		</div>		
	</div>





<script>var local_qa_config = <?php echo $local_qa_config;?></script>
<script>var g = <?php echo $test_cases;?></script>
<script>
	var current_result;
	
	$( document ).ready(function() {
	
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
			var html = xmlDoc.getElementById('purified');
			
			var target = document.getElementById('results');
			target.appendChild(html);
			
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

		$('.confirmPass').click(function(){
			var qa_param = {};
			qa_param.CASE_ID = current_result.CASE_ID;
			qa_param.RESULT_ID = current_result.RESULT_ID;
			qa_param.USER_ID = local_qa_config.USER_ID;
			
			var result = $.post( "/qualityassurance/QA_Confirm_PASS", JSON.stringify(qa_param), function(msg) {

				console.log(msg);
			});		
		});		

		$('.report_issue').click(function(){
			$('#issue_summary').val('');
			$('#issue_description').val('');
			$('.issue_report_board').show();
			$(window).scrollTop($('div#issue_report_board').offset().top);
			//$('body').animate({scrollTop:$('div#issue_report_board').offset().top},500);
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
	});

</script>
</div>
