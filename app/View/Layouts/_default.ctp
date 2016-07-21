<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="/_/css/bootstrap.min.css">
        <style>
            body {
                padding-top: 50px;
                padding-bottom: 20px;
            }
        </style>
        <link rel="stylesheet" href="/_/css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="/_/css/main.css">

        <script src="/_/js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="browsehappy">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
        <![endif]-->
	<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/" style="color: #fff;">The Arena Club</a>
        </div>
        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li<?php if (isset($content) && $content['Content']['tag'] == '/health') echo ' class="active"'; ?>><a href="/health">Health</a></li>
            <li<?php if (isset($content) && $content['Content']['tag'] == '/athletics') echo ' class="active"'; ?>><a href="/athletics">Athletics</a></li>
			<li<?php if (isset($content) && $content['Content']['tag'] == '/kids') echo ' class="active"'; ?>><a href="/kids">Kids</a></li>
			<li<?php if (isset($content) && $content['Content']['tag'] == '/membership') echo ' class="active"'; ?>><a href="/membership">Membership</a></li>
			<li<?php if (isset($content) && $content['Content']['tag'] == '/about') echo ' class="active"'; ?>><a href="/about">About Us</a></li>
			<li<?php if (isset($content) && $content['Content']['tag'] == '/more') echo ' class="active"'; ?>><a href="/more">More</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>

    <div class="container">

      <?php echo $content_for_layout; ?>

    </div><!-- /.container -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="/_/js/vendor/jquery-1.11.0.min.js"><\/script>')</script>

        <script src="/_/js/vendor/bootstrap.min.js"></script>

        <script src="/_/js/main.js"></script>

        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script>
            (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
            function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
            e=o.createElement(i);r=o.getElementsByTagName(i)[0];
            e.src='//www.google-analytics.com/analytics.js';
            r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
            ga('create','UA-XXXXX-X');ga('send','pageview');
        </script>
    </body>
</html>
