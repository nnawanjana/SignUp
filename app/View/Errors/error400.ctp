<div class="container-fluid">
	<section class="col-md-12">
		<h2><?php echo $message; ?></h2>
		<p class="error">
			<strong><?php echo __d('cake', 'Error'); ?>: </strong>
			<?php printf(
				__d('cake', 'The requested address %s was not found on this server.'),
				"<strong>'{$url}'</strong>"
			); ?>
		</p>
	</section>
</div>