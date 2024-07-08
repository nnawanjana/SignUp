<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"  charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0">
        <title>Deal Expert</title>
        <meta name="ROBOTS" content="NOINDEX,FOLLOW">
        
        <script type="text/javascript">
            var compare_website_domain_name = '<?php echo WEBSITE_COMPARE_DOMAIN_NAME;?>';
            var signup_website_domain_name = '<?php echo WEBSITE_DOMAIN_NAME;?>';
        </script>

        <?php
            echo $this->Html->meta('icon');
	       	echo $this->Html->css('//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700');
    		echo $this->Html->css('font-awesome');
    		echo $this->Html->css('reset');
    		echo $this->Html->css('bootstrap');
    		echo $this->Html->css('style.css?v=' . filemtime('css/style.css'));
    		echo $this->Html->css('responsive.css?v=' . filemtime('css/responsive.css'));
    		echo $this->Html->css('publicsignup');

    		echo $this->Html->css('datepicker');
    		echo $this->Html->css('bootstrap-select');

    		echo $this->Html->script('jquery.min');
    		echo $this->Html->script('jquery-ui.min');
    		echo $this->Html->script('modernizr.min');
    		echo $this->Html->script('moment.min');
    		echo $this->Html->script('bootstrap.min');
    		echo $this->Html->script('bootstrap-datepicker');
    		echo $this->Html->script('bootstrap-datepicker-mobile');
    		echo $this->Html->script('bootstrap-select');
    		echo $this->Html->script('jquery.formatter');

			echo $this->Html->script('jquery_003');
    		echo $this->Html->script('additional-methods.min');

    		echo $this->Html->script('jquery.confirm.min');

			/***************
			** Okkey Sumiyoshi
			** 26/01/2016
			** To prevent users from clicking submit button
			** Temporarily being placed
			** This will need to be replaced with suggestions and design that Joel would provide
			** To be assigned to Sean after Joel determining appropiate design
			***/
    		echo $this->Html->script('blockUI');

     		echo $this->Html->script('public_signup.js?v=' . filemtime('js/public_signup.js'));
     		echo $this->Html->script('jq.accordionControler');
     		echo $this->Html->script('xmlparser');
    		echo $this->Html->script('concessions');
    		echo $this->Html->script('ew_submit');


    		echo $this->fetch('meta');
			echo $this->fetch('css');
			echo $this->fetch('script');
       ?>

		<style>
			.modal-body .form-horizontal .col-sm-2,
			.modal-body .form-horizontal .col-sm-10 {
				width: 100%
			}

			.modal-body .form-horizontal .control-label {
				text-align: left;
			}
			.modal-body .form-horizontal .col-sm-offset-2 {
				margin-left: 15px;
			}
			.qa_modal{display:none;}
			.to-be-removed{display:none;}

		</style>
    </head>
	<body class="about-you">

	<?php echo $this->Session->flash(); ?>
	<?php echo $this->fetch('content'); ?>

	<?php echo $this->element('modals');?>
	<?php echo $this->element('mobile_footer');?>

</body>
</html>
