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
    <link rel="stylesheet" href="/css/main.css?v=011415">
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700|Roboto+Condensed:400,500,700|Oswald:400,700" rel="stylesheet" type="text/css">
        <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Oswald" />
	<link rel="stylesheet" media="screen" href="/plugins/superfish/superfish.css?v=112314">
	<link rel="shortcut icon" href="/favicon.ico">

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
		header, nav, .container { min-width: 1170px; }
		.nav-main { min-width: 905px; }
		.nav-social { min-width: 200px; }
	</style>
	
	<meta property='og:type' content='website' />
	<meta property='og:image' content='<?php echo $fbFeatured; ?>' />
	
	
	<!--[if lte IE 8]>
	<style type="text/css">
		article, aside { margin-top: 120px; }
	</style>
	<![endif]-->
<script src='https://www.google.com/recaptcha/api.js'></script>
</head>

<body class="mobile<?php echo $isMobile ? '1' : '0'; ?> tablet<?php echo $isTablet ? '1' : '0'; ?>">
<!-- Google Tag Manager -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-WTVVT6"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-WTVVT6');</script>
<!-- End Google Tag Manager -->

   <div class="container">
	<header>
		<h1><a href="/"><span>CQL | The Council on Quality and Leadership</span></a></h1>
		<ul class="nav-top">
<!--			<li style="margin-left: 60px; padding-top: 2px;"><a href="/ligas-outreach">Ligas Outreach</a></li>-->
<!--<li style="margin-left: 60px; padding-top: 2px;"><a href="/ColoradoTraining">Colorado Training</a></li>-->
			<li style="padding-top: 2px;"><a href="http://ecommunity.c-q-l.org" target="_blank">e-Community</a></li>
			<li style="padding-top: 2px;"><a href="/contact">Contact Us</a></li>
			<li style="padding-top: 2px;"><a href="/sitemap">Site Map</a></li>
			<li style="margin-left: 5px; padding-top: 3px;"><a href="/payments" style="font-size: 14px; color: #2d2d2d; background-color: #eaeaea; padding: 3px 8px 3px; font-weight: bold;">Pay Fees</a></li>
			<li style="margin-left: 4px; padding-top: 1px;"><form method="post" action="/content/search"><input style="margin: 0 8px;" id="search" name="data[query]" placeholder="Search..." /></form></li>
			<li>
				<a target="_blank" style="padding: 0;" href="http://www.facebook.com/pages/The-Council-on-Quality-and-Leadership/70205137928"><img style="margin-right: 4px;" src="/img/nav/facebook.png" /></a>
				<a target="_blank" style="padding: 0;" href="http://twitter.com/TheCQL"><img style="margin-right: 4px;" src="/img/nav/twitter.png" /></a>
				<a target="_blank" style="padding: 0;" href="http://www.linkedin.com/company/cql-the-council-on-quality-and-leadership"><img style="margin-right: 4px;" src="/img/nav/linked-in.png" /></a>
				<a target="_blank" style="padding: 0;" href="http://www.youtube.com/TheCQL"><img src="/img/nav/youtube.png" /></a>
			</li>
			<li style="margin-left: 8px;" class="fontsize-control-group">
			</li>
		</ul>
	</header>

	<nav>
		<ul class="nav-main sf-menu">
			<li><a href="/the-cql-difference"><?php echo str_replace('{br}', '<br />', $menu['/the-cql-difference']['title']); ?></a><?php echo $menu['/the-cql-difference']['menu']; ?></li>
			<li><a href="/services"><?php echo str_replace('{br}', '<br />', $menu['/services']['title']); ?></a><?php echo $menu['/services']['menu']; ?></li>
			<li><a href="/accreditation"><?php echo str_replace('{br}', '<br />', $menu['/accreditation']['title']); ?></a><?php echo $menu['/accreditation']['menu']; ?></li>
			<li><a href="/training-and-certification"><?php echo str_replace('{br}', '<br />', $menu['/training-and-certification']['title']); ?></a><?php echo $menu['/training-and-certification']['menu']; ?></li>
			<li><a href="/resource-library"><?php echo str_replace('{br}', '<br />', $menu['/resource-library']['title']); ?></a><?php echo $menu['/resource-library']['menu']; ?></li>
			<li><a href="/news-and-events"><?php echo str_replace('{br}', '<br />', $menu['/news-and-events']['title']); ?></a><?php echo $menu['/news-and-events']['menu']; ?></li>
			<li><a href="/about"><?php echo str_replace('{br}', '<br />', $menu['/about']['title']); ?></a><?php echo $menu['/about']['menu']; ?></li>
		</ul>
	</nav>

   </div>

   <?php if ($this->request->here == '/'): ?>
   <div style="margin: 137px auto 0; border-bottom: 4px solid #f97845;" class="cycle-slideshow"
   	data-cycle-fx="scrollHorz"
   	data-cycle-timeout="8000"
   	data-cycle-slides="> div.feature"
    data-cycle-prev=".slider-button-left"
    data-cycle-next=".slider-button-right"
   >
   	<a href="#" class="slider-button-left"></a>
	<a href="#" class="slider-button-right"></a>
   	<?php foreach ($features as $feature): ?>
   		<?php if (!empty($feature['Feature']['url'])): ?>
   			<div style="width: 100%;" class="feature">
   				<a style="display: block; width: 100%;" href="<?php echo $feature['Feature']['url']; ?>">
   					<?php if (!empty($feature['Feature']['headline']) || !empty($feature['Feature']['content'])): ?>
   						<div style="position: absolute; width: 1170px; height: 100%; left: 0; right: 0; margin: auto;">
   							<div class="<?php echo $feature['Feature']['show_background_box'] == 1 ? 'slideshow-panel' : ''; ?>" style="width: <?php echo $feature['Feature']['width']; ?>px; height: <?php echo $feature['Feature']['height']; ?>px; top: <?php echo $feature['Feature']['y']; ?>px; left: <?php echo $feature['Feature']['x']; ?>px;">
	   							<?php if (!empty($feature['Feature']['headline'])): ?><h1><?php echo $feature['Feature']['headline']; ?></h1><?php endif; ?>
	   							<?php if (!empty($feature['Feature']['content'])): ?><?php echo $feature['Feature']['content']; ?><?php endif; ?>
	   						</div>
   						</div>
   					<?php endif; ?>
   					<img src="<?php echo $feature['Feature']['background_image']; ?>" style="width: 100%;">
   				</a>
   			</div>
   		<?php else: ?>
	   		<div style="width: 100%;">
	   			<?php if (!empty($feature['Feature']['headline']) || !empty($feature['Feature']['content'])): ?>
					<div style="position: absolute; width: 1170px; height: 100%; left: 0; right: 0; margin: auto;">
						<div class="<?php echo $feature['Feature']['show_background_box'] == 1 ? 'slideshow-panel' : ''; ?>" style="width: <?php echo $feature['Feature']['width']; ?>px; height: <?php echo $feature['Feature']['height']; ?>px; top: <?php echo $feature['Feature']['y']; ?>px; left: <?php echo $feature['Feature']['x']; ?>px;">
							<?php if (!empty($feature['Feature']['headline'])): ?><h1><?php echo $feature['Feature']['headline']; ?></h1><?php endif; ?>
							<?php if (!empty($feature['Feature']['content'])): ?><?php echo $feature['Feature']['content']; ?><?php endif; ?>
						</div>
					</div>
				<?php endif; ?>
	   			<img src="<?php echo $feature['Feature']['background_image']; ?>" style="width: 100%;">
	   		</div>
   		<?php endif; ?>
   	<?php endforeach; ?>
   	<div class="cycle-pager"></div>
   	</div>
	<?php endif; ?>	

   <div class="container" style="margin-top: 44px;">
   	<?php if ($this->request->here == '/'): ?>
   	<?php foreach ($columns as $group): ?>
   	<div class="row">
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
   <main style="margin-top: 124px;">
           <?php echo $this->Session->flash(); ?>
   	<?php echo $content_for_layout; ?>
   </main>
   <?php endif; ?>
   	</main>
   </div>

		
	<footer style="padding-top: 100px;">
		<p style="text-align: center; font-size: 90%;">Copyright &copy; 2015<?php if (date('Y') != '2015'): ?>-<?php echo date('Y'); ?><?php endif; ?> CQL</p>
		<p style="text-align: center; font-size: 90%;">The Council on Quality and Leadership, 100 West Road, Suite 300, Towson, Maryland 21204 | Phone: 410.583.0060 | Email: <a href="mailto:info@thecouncil.org">info@thecouncil.org</a>
		<p style="text-align: center;"><img style="margin: 0 10px;" src="/img/footer/alliance-for-full-participation.png"> <img style="margin: 0 10px;" src="/img/footer/national-leadership-institute.png"> <img style="margin: 0 10px;" src="/img/footer/md-non-profits.png"></p>
	</footer>

</body>
</html>
