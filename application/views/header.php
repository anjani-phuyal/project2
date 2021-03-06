
<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo $title;?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>"> <!-- bootstrap -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/bootstrap.theme.min.css'); ?>"> <!-- bootstrap -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/font-awesome.min.css'); ?>">  <!-- Font-awesome -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/slider.css'); ?>">  <!-- Slider -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/demos.css'); ?>"> <!-- jquery ui -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/jquery-ui.css'); ?>"> <!-- jquery ui -->
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/css/style.css'); ?>"> <!-- Custom css Nepal Inn -->
	 <!-- less -->
	 <link rel="stylesheet/less" type="text/css" href="slider.less" />
	
</head>
<body>
	<div id="header"> <!-- starts:header -->
		<div class="header-top"> <!-- starts:header-top -->
			<div class="container"> <!-- starts:header-top container -->
				<!-- contents -->
			</div><!-- ends:header-top container -->
		</div>  <!-- ends:header-top -->

		<div class="header-main"> <!-- starts:header-main -->
			<div class="container"><!-- starts:header-main container -->
					<div class="row"><!-- starts:header-main row -->
						<nav class="navbar navbar-default" role="navigation">
						  <!-- Brand and toggle get grouped for better mobile display -->
						  <div class="navbar-header">
						    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						      <span class="icon-bar"></span>
						      <span class="icon-bar"></span>
						      <span class="icon-bar"></span>
						    </button>
						    <a class="navbar-brand" href="<?php echo base_url();?>home"><img src="<?php echo base_url('assets/images/logo.png'); ?>"></a>
						  </div>

						  <!-- Collect the nav links, forms, and other content for toggling -->
						  <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
						    <div class="inn-nav">
						    	<ul class="nav navbar-nav navbar-right">
							      <li><a href="<?php echo base_url(); ?>home">Home</a></li>
							      <li><a href="<?php echo base_url(); ?>about">About</a></li>
							      <li><a href="<?php echo base_url(); ?>contact">Contact</a></li>
							      <li><a href="<?php echo base_url(); ?>faq">FAQ</a></li>
							    </ul>
						    </div>
						  </div><!-- /.navbar-collapse -->
						</nav>



					</div><!-- ends:header-main row -->
				<div class></div>
			</div> <!-- ends:header-main container -->
		</div> <!-- ends:header-main -->
	</div>  <!-- ends:header -->
	
<input type="hidden" id="base_url" value="<?php echo base_url(); ?>" />
