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
    		echo $this->Html->css('reset');
    		echo $this->Html->css('bootstrap');
    		echo $this->Html->css('style.css?v=' . filemtime('css/style.css'));
    		echo $this->Html->css('responsive.css?v=' . filemtime('css/responsive.css'));
    		echo $this->Html->css('publicsignup');
    		echo $this->Html->css('snapshot');

    		echo $this->Html->css('datepicker');
    		echo $this->Html->css('bootstrap-select');
    		
    		echo $this->Html->script('jquery.min');
    		echo $this->Html->script('jquery-ui.min');
    		echo $this->Html->script('bootstrap.min');
    		echo $this->Html->script('bootstrap-datepicker');
    		echo $this->Html->script('bootstrap-select');
	

    		echo $this->Html->script('jquery.formatter');
    		echo $this->Html->script('url.min');
			echo $this->Html->script('blockUI');
			echo $this->Html->script('FileSaver.min');

    		

     		echo $this->Html->script('xmlparser');  

    		echo $this->Html->script('QA_Compare_Submit');   		
	
		   	
    		echo $this->fetch('meta');
			echo $this->fetch('css');
			echo $this->fetch('script');	
       ?>
		<style>
		.dt-velocify-request{width:250px !important;}
		.dd-velocify-request{margin-left:280px !important;}
		.QA-pdt125{padding: 0px 30px 50px 30px !important;}
		.QA-div-row{padding: 10px 00px 10px 00px  !important; margin-top:50px;}
		.QA-issue-item-row{margin-bottom:5px  !important; padding:5px; border-bottom:1px solid #AAAAAA;}
		.QA-Issue-Paragraph{display:inline-block !important;}
		.QA-new-response-row-divider{margin-top:20px !important;}
		.issue-response-header h5{font-size:18px; display:inline-block !important;}
		</style>
    </head>
	<body class="about-you ondisplay">



	<?php echo $this->Session->flash(); ?>
	<?php echo $this->fetch('content'); ?>

	<?php echo $this->element('modals');?>
	<?php echo $this->element('mobile_footer');?>

</body>
</html>