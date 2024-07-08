<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"  charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0">
        <title><?php echo WEBSITE_NAME; ?></title>
        <meta name="ROBOTS" content="NOINDEX,FOLLOW">

        <?php
            echo $this->Html->meta('icon');
	        echo $this->Html->css('//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700');
    		echo $this->Html->css('font-awesome');
    		echo $this->Html->css('bootstrap');
    		echo $this->Html->css('jquery-ui.min');

    		echo $this->Html->script('jquery.min');
    		echo $this->Html->script('bootstrap.min');
    		echo $this->Html->script('jquery-ui.min');
    		
    		echo $this->Html->script('jquery.cookie');

    		echo $this->fetch('meta');
			echo $this->fetch('css');
			echo $this->fetch('script');
		?>

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="/js/html5shiv.js"></script>
          <script src="/js/respond.min.js"></script>
        <![endif]-->

    </head>
    
	<body>

	<?php echo $this->Session->flash(); ?>
	<?php echo $this->fetch('content'); ?>

    </body>
</html>