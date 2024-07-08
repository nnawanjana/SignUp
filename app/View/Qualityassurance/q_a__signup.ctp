
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
					<li><a class="active" href="QA_Main">Main</a></li>
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
	<div class="container-fluid QA-pdt125">
		<form action="/QA_SignUp" method="post" enctype="multipart/form-data">
			<div class="row">
				<div class="col-xs-12 test-data-section QA-test-data">
					<h2>TEST DATA</h2>
					<p class='QA-physical-data'><input type="file" name="SignUpCSVToUpload" id="SignUpCSVToUpload"></p>
					<input class='QA-physical-data' type="submit" value="Upload CSV" name="submit">
				</div>
			</div>
		</form>


	</div>





<script>
	var current_result;

	$( document ).ready(function() {
		var url_value = $.url('?');
		if (parseInt(url_value.test_type) == 3){
			var newHTML = '<p>This test requires no data to upload, however, it is to be conducted directly on the online signup form.  Please follow the instruction below.</p>' +
				'<dl class="dl-horizontal"><dt>Step 1</dt><dd>Go awscompare.electricitywizard.com.au/ and navigate through to the online signup page</dd></dl>' +
				'<dl class="dl-horizontal"><dt>Step 2</dt><dd>Test and log errors by clicking on button "Report an Issue"</dd></dl>';

			$('.QA-physical-data').hide();
			$('.QA-test-data').append(newHTML);

		}
		if (parseInt(url_value.test_type) == 1){
			var newHTML = '<p>This test requires no data to upload, however, it is to be conducted directly on both the compare and signup web stei.  Please follow the instruction below.</p>' +
				'<dl class="dl-horizontal"><dt>Step 1</dt><dd>Notify Okkey of retailers that you would like to exclude/include</dd></dl>' +
				'<dl class="dl-horizontal"><dt>Step 2</dt><dd>Notify Okkey of operation hours that you would like to set within</dd></dl>' +
				'<dl class="dl-horizontal"><dt>Step 4</dt><dd>Notify Okkey of if you would like to force Velocify response disabled</dd></dl>' +
				'<dl class="dl-horizontal"><dt>Step 5</dt><dd>Okkey will notify you of the completion of requested configuration (normally takes 1 - 2 minutes)</dd></dl>' +
				'<dl class="dl-horizontal"><dt>Step 6</dt><dd>Go awscompare.electricitywizard.com.au/ and navigate through to the compare page</dd></dl>' +
				'<dl class="dl-horizontal"><dt>Step 7</dt><dd>Test and report errors</dd></dl>';

			$('.QA-physical-data').hide();
			$('.QA-test-data').append(newHTML);

		}

	});

	$(function() {

	});

</script>
</div>
