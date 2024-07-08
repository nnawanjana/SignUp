



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
				<a class="navbar-brand" href="#">Deal Expert</a>
			</div>
			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="navbarCollapse">
				<ul class="nav navbar-nav">
					<li><a href="QA_Main">Main</a></li>
					<li><a href="#">Logout</a></li>
					<li><input class="form-control input-sm" id="leadid" type='text' placeholder="enter lead id"/></li>
					<li><button type="button" class="btn btn-primary btn-me doSnapshot">Lookup</button></li>
				</ul>
			</div>
		</div>
	</nav>


	<div class="snapshot">ssdsdsds</div>

<script>
	

	$( document ).ready(function() {



	});

	$(function() {
	
		$('.doSnapshot').click(function(){
			var obj = {};
			obj.leadid = $('#leadid').val();
			$('.snapshot').empty();
			var result = $.post( "/qualityassurance/snapshot", obj, function(msg) {
				$('.snapshot').append(msg.html);
				if ($('.tac').find('.snapshot-span').length > 0){
			
					if ($('.tac span.snapshot-span').text() == 'on'){
						$('.tac span.snapshot-span').text("Yes");
					}
				}				
				
			});
		});				

	});

</script>
