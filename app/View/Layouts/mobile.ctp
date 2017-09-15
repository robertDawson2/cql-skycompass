<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title><?php echo str_replace('{br}', ' ', $title_for_layout); ?> | The Council on Quality and Leadership</title>
    <?php if (isset($content) && !empty($content['Content']['meta_description'])): ?><meta name="description" content="<?php echo $content['Content']['meta_description']; ?>"><?php endif; ?>
    <?php if (isset($content) && !empty($content['Content']['meta_description'])): ?><meta name="keywords" content="<?php echo $content['Content']['meta_keywords']; ?>"><?php endif; ?>

    <link rel="stylesheet" href="/_/css/bootstrap.min.css">
    <link rel="stylesheet" href="/_/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="/_/plugins/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/main.css?v=122314-2">
	<link href="http://fonts.googleapis.com/css?family=Roboto:400,500,700|Roboto+Condensed:400,500,700|Oswald:400,700" rel="stylesheet" type="text/css">
	<link rel="stylesheet" media="screen" href="/plugins/superfish/superfish.css?v=112314">
	<link rel="shortcut icon" href="/favicon.ico">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="/_/js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    <script src="/_/js/vendor/html5shiv.js"></script>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="/_/js/vendor/jquery-1.11.0.min.js"><\/script>')</script>
    <script src="/js/jquery.cycle2.min.js"></script>
    <script src="/js/jquery.cycle2.center.min.js"></script>
    <script src="/js/jquery.easing.min.js"></script>
    <script src="/_/js/vendor/bootstrap.min.js"></script>

    <script src="/_/js/main.js"></script>

	<script src="/plugins/superfish/hoverIntent.js"></script>
	<script src="/plugins/superfish/superfish.js"></script>

	<script src="/plugins/fontsize/store.min.js"></script>
	<script src="/plugins/fontsize/rv-jquery-fontsize.js"></script>

	<script src="/js/rollover.js"></script>
	<script src="/js/preload.js"></script>
	
	<script type="text/javascript">
		$(function() {
			$('ul.sf-menu').superfish();
		});
	</script>

	<script>
		$(function() {
			<?php echo $this->fetch('jquery-scripts'); ?>
			$.rvFontsize({
			    targetSection: 'main',
			    store: true,
			    variations: 9,
			    controllers: {
			    	appendTo: '.fontsize-control-group',
        			showResetButton: true,
        			template: '<a href="#" class="rvfs-decrease" style="padding: 0;"><img style="margin-right: 4px;" src="/img/nav/font-decrease.png"></a><a href="#" class="rvfs-reset" style="padding: 0;"><img style="margin-right: 4px;" src="/img/nav/font-reset.png"></a><a href="#" class="rvfs-increase" style="padding: 0;"><img style="margin-right: 0;" src="/img/nav/font-increase.png"></a>'
        		}
			}); 
		});
	</script>
	<?php include_once("analyticstracking.php") ?>

	<style type="text/css">
		article { width: 92%; float: none; margin: 0 4%; }
		img, iframe { max-width: 96%; float: none !important; margin: 0 !important; }
		article img.left { float: none; margin: 0; }
		article img.right { float: none; margin: 0; }
                body .mobile-only {display: none !important; }
                body.mobile1 td.mobile-only { display: block !important; }
        body.mobile1 img.mobile-only { display: inline-block !important; }
	</style>
</head>

<body class="mobile<?php echo $isMobile ? '1' : '0'; ?> tablet<?php echo $isTablet ? '1' : '0'; ?>">
<header style="text-align: center; padding-top: 8px;">    
	<a href="/" class="logo" ><img src="/img/the-council-on-quality-and-leadership.png" alt="The Council on Quality and Leadership"></a>
	<div class="search" style="position: absolute; top: 6px; right: 4px;">
		<form method="post" action="/content/search"><input style="color: #333; margin: 0; padding: 4px;" id="search" name="data[query]" placeholder="Search..." /><input type="submit" value="Go" style="padding: 4px; margin-left: 4px; color: #333;"></form>
	</div>
</header>
<nav class="navbar navbar-default" role="navigation">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-1" style="float: left; margin-left: 15px;">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#" onclick="return false;"><?php echo str_replace('{br}', ' ', $title_for_layout); ?></a>
    </div>

    <div class="collapse navbar-collapse" id="navbar-collapse-1">
      <ul class="nav navbar-nav">
		<li><a href="/">Home</a></li>
		<?php echo $menu['/the-cql-difference']['menu']; ?>
		<?php echo $menu['/services']['menu']; ?>
		<?php echo $menu['/accreditation']['menu']; ?>
		<?php echo $menu['/training-and-certification']['menu']; ?>
		<?php echo $menu['/resource-library']['menu']; ?>
		<?php echo $menu['/news-and-events']['menu']; ?>
		<?php echo $menu['/about']['menu']; ?>
		<li><a href="/ligas-outreach">Ligas Outreach</a></li>
		<li><a href="http://ecommunity.c-q-l.org" target="_blank">e-Community</a></li>
		<li><a href="/contact">Contact Us</a></li>
		<li><a href="/sitemap">Site Map</a></li>
		<li style="font-weight: bold;"><a href="/services/pay-fees">Pay Fees</a></li>
      </ul>
    </div>
  </div>
</nav>

<?php if ($this->request->here == '/'): ?>
   	<?php foreach ($columns as $group): ?>
   	<div class="row" style="width: 92%; float: none; margin: 0 4%;">
   		<?php ksort($group); ?>
   		<?php foreach ($group as $column): ?>
		<div class="col-md-<?php echo $column['Content']['tag']; ?>">
	   		<main>
		   		<?php echo $column['Content']['content']; ?>
			</main>
	   	</div>
   		<?php endforeach; ?>
   	</div>
   	<?php endforeach; ?>
<?php else: ?>
	<?php echo $content_for_layout; ?>
<?php endif; ?>


</body>
</html>