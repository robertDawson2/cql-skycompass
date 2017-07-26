<?php $this->set('title_for_layout', $content['Content']['title']); ?>
<?php $this->set('meta_description', $content['Content']['meta_description']); ?>
<?php $depth = substr_count($content['Content']['tag'], '/') - 1; ?>
<style type="text/css">
	a.cal { font-size: 80%; }
	a.cal:hover { text-decoration: none; }
	.news-intro p { margin-top: 0; }
        body .mobile-only {display: none !important; }

	body.mobile1 article { width: 92% !important; }
	body.mobile1 td.mobile-full { width: 96% !important; padding: 0 !important; }
	body.mobile1 td.mobile-hide { width: 0% !important; display: none; }
        body.mobile1 td.mobile-only { display: block !important; }
        body.mobile1 img.mobile-only { display: inline-block !important; }
	body.mobile1 img.mobile-hide, body.mobile1 img.mobile-hide-left, body.mobile1 img.mobile-hide-right { display: none; }
	body.mobile1 iframe {max-width: 10000px;}
</style>

<script type="text/javascript">
	$(function() {
		$("article table").each(function(index) {
			if (!isNaN(parseInt($(this).attr('cellspacing'))))
				$("td", $(this)).css('padding', '10px');
		});
		$(".slidedown").on('click', function() {
			$("#slidedown" + $(this).attr('rel')).slideToggle();
			return false;
		});
	});
</script>

<?php if (isset($form) && !isset($submitted)): ?>
	<?php ob_start(); ?>
	<form method="post" action="<?php echo $content['Content']['tag']; ?>#form">
		<a name="form"></a>
		<?php if (!empty($form['Form']['hardcoded_form'])): ?>
			<?php echo $this->element('forms/' . $form['Form']['hardcoded_form']); ?>
		<?php else: ?>
		<?php endif; ?>
		<?php echo $this->Form->submit(); ?>
	</form>
	<?php $content['Content']['content'] .= ob_get_clean(); ?>
<?php endif; ?>

	

<?php if ($content['Content']['tag'] == '/news-and-events'): ?>
<article style="width: 100%; float: none;">
    <div class="row">
    	<div class="col-md-6">
    		<div class="row">
    			<div class="col-md-12 news-intro">
    				<?php echo $content['Content']['content']; ?>
    			</div>
    		</div>
    		<div class="row">
				<div class="col-md-6 news">
					<h1>News <span style="font-size: 18px;"><a href="http://feeds.feedburner.com/CQL" target="_blank" style="color: #f60; text-decoration: none; font-weight: bold;"><i class="fa fa-rss"></i></a></span></h1>
					<?php foreach ($news as $n): ?>
						<div class="item">
							<?php if (!empty($n['News']['url'])): ?>
								<h2 style="font-size: 120%;"><a target="_blank" href="<?php echo $n['News']['url']; ?>"><?php echo $n['News']['headline']; ?></a></h2>
							<?php else: ?>
								<h2 style="font-size: 120%;"><?php echo $n['News']['headline']; ?></h2>
							<?php endif; ?>
							<?php if (!empty($n['News']['content'])): ?><?php echo $n['News']['content']; ?><?php endif; ?>
						</div>
					<?php endforeach; ?>
					<p style="margin-top: 18px;"><a href="/news" style="text-transform: uppercase;">View all</a></p>
				</div>
				<div class="col-md-6 news">
					<h1>Events<?php if (0): ?> <span style="font-size: 18px;"><a href="/news/index.rss" target="_blank" style="color: #f60; text-decoration: none; font-weight: bold;"><i class="fa fa-calendar"></i></a></span><?php endif; ?></h1>
					<?php foreach ($events as $e): ?>
						<div class="item">
							<?php if (!empty($e['Event']['url'])): ?>
								<h2 style="font-size: 120%;"><a target="_blank" href="<?php echo $e['Event']['url']; ?>"><?php echo $e['Event']['title']; ?></a></h2>
							<?php else: ?>
								<h2 style="font-size: 120%;"><?php echo $e['Event']['title']; ?></h2>
							<?php endif; ?>
							<?php if (!empty($e['Event']['description'])): ?><?php echo $e['Event']['description']; ?><?php endif; ?>
							<p><a class="cal" href="/content/event/<?php echo $e['Event']['id']; ?>"><i class="fa fa-calendar"></i> Save to Calendar (iCal)</a></p>
						</div>
					<?php endforeach; ?>
					<p style="margin-top: 18px;"><a href="/events" style="text-transform: uppercase;">View all</a></p>
				</div>
			</div>
    	</div>
		<div class="col-md-5 col-md-offset-1">
            <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-54724f916b76a938" async="async"></script>
            <div style="text-align: right; margin-bottom: 20px;" class="addthis_sharing_toolbox"></div>
			
			<a class="twitter-timeline" style="background-color: #ffffff;" href="https://twitter.com/TheCQL/lists/latest-tweets" data-dnt="true" data-widget-id="530189368632152065">Tweets from https://twitter.com/TheCQL/lists/latest-tweets</a>

			<iframe style="margin-top: 40px; border: none; overflow: hidden; width: 100%; height: 800px; background: white; float: left;" src="http://www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2FTheCQL&amp;width=600&amp;colorscheme=light&amp;show_faces=true&amp;border_color&amp;stream=true&amp;header=true&amp;height=600" width="300" height="150" frameborder="0" scrolling="yes">
			</iframe>

			<script>// <![CDATA[
			!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
			// ]]></script>
		</div>
		</div>
</article>

<?php elseif ($content['Content']['tag'] == '/sitemap'): ?>
<article style="width: 100%; float: none;">
<h1>Sitemap</h1>
<style type="text/css">
article li { line-height: 140%; }
article h3 { margin-top: 22px; }
article h3 a { color: #333; text-decoration: underline; }
</style>
<?php foreach ($sitemap as $url => $section): ?>
<h3><a href="<?php echo $url; ?>"><?php echo str_replace('{br}', ' ', $section['title']); ?></a></h3>
<?php echo $section['menu']; ?>
<?php endforeach; ?>
</article>

<?php else: ?>

		<article<?php if ($content['Content']['hide_sidebar']): ?> style="width: 100%; float: none;"<?php endif; ?>>
			<h1><?php echo $content['Content']['name']; ?></h1>
			
			<?php if (!empty($content['Content']['photo_gallery']) && $content['Content']['gallery_position'] == 'top'): ?>
				<div class="cycle-slideshow"
				    data-cycle-pause-on-hover="true"
				    data-cycle-timeout="<?php echo $content['Content']['gallery_timing']; ?>"
    				data-cycle-prev=".slider-button-left-sm"
    				data-cycle-next=".slider-button-right-sm"
    				data-cycle-center-horz="true"
    				style="background-color: #eee; -webkit-box-shadow: 0px 0px 29px -1px rgba(0,0,0,0.5); -moz-box-shadow: 0px 0px 29px -1px rgba(0,0,0,0.5); box-shadow: 0px 0px 29px -1px rgba(0,0,0,0.5);
				    <?php if ($content['Content']['gallery_alignment'] != ''): ?>float: <?php echo $content['Content']['gallery_alignment']; ?><?php endif; ?>
				    ">
   					<a href="#" class="slider-button-left-sm"></a>
					<a href="#" class="slider-button-right-sm"></a>
				    <?php
						$d = dir(WWW_ROOT . "files/_galleries/" . $content['Content']['photo_gallery']);
                                                $img = array();
						while (false !== ($entry = $d->read())) {
							$img[] = $entry;
						}
						$d->close();
                                                
                                                sort($img);
                                                
                                                foreach($img as $entry)
                                                {
                                                    if ($entry != '.' && $entry != '..') {
						   		echo '<img src="/files/_galleries/' . $content['Content']['photo_gallery'] . '/' . $entry . '" style="';
								if ($content['Content']['gallery_width'] > 0) echo 'width: ' . $content['Content']['gallery_width'] . 'px;';
								if ($content['Content']['gallery_height'] > 0) echo 'height: ' . $content['Content']['gallery_height'] . 'px;';
								echo '">';
							}
                                                }
				    ?>
				</div>
			<?php endif; ?>

			<?php echo $content['Content']['content']; ?>

			<?php if (!empty($content['Content']['photo_gallery']) && $content['Content']['gallery_position'] == 'bottom'): ?>
				<div class="cycle-slideshow"
				    data-cycle-pause-on-hover="true"
				    data-cycle-timeout="<?php echo $content['Content']['gallery_timing']; ?>"
    				data-cycle-prev=".slider-button-left-sm"
    				data-cycle-next=".slider-button-right-sm"
    				data-cycle-center-horz="true"
    				style="background-color: #eee; -webkit-box-shadow: 0px 0px 29px -1px rgba(0,0,0,0.5); -moz-box-shadow: 0px 0px 29px -1px rgba(0,0,0,0.5); box-shadow: 0px 0px 29px -1px rgba(0,0,0,0.5);
				    <?php if ($content['Content']['gallery_alignment'] != ''): ?>float: <?php echo $content['Content']['gallery_alignment']; ?><?php endif; ?>
				    ">
   					<a href="#" class="slider-button-left-sm"></a>
					<a href="#" class="slider-button-right-sm"></a>
				    <?php
						$d = dir(WWW_ROOT . "files/_galleries/" . $content['Content']['photo_gallery']);
                                                $img = array();
						while (false !== ($entry = $d->read())) {
							$img[] = $entry;
						}
						$d->close();
                                                
                                                sort($img);
                                                
                                                foreach($img as $entry) {
                                                    if ($entry != '.' && $entry != '..') {
						   		echo '<img src="/files/_galleries/' . $content['Content']['photo_gallery'] . '/' . $entry . '" style="';
								if ($content['Content']['gallery_width'] > 0) echo 'width: ' . $content['Content']['gallery_width'] . 'px;';
								if ($content['Content']['gallery_height'] > 0) echo 'height: ' . $content['Content']['gallery_height'] . 'px;';
								echo '">';
							}
                                                }
				    ?>
				</div>
			<?php endif; ?>

			<?php if ($content['Content']['show_sharing_options']): ?>
				<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-54724f916b76a938" async="async"></script>
				<div style="margin-top: 40px;" class="addthis_sharing_toolbox"></div>
			<?php endif; ?>
		</article>

		<?php if (!$content['Content']['hide_sidebar']): ?>
		<aside class="sidebar" style="<?php if ($isMobile) echo 'width: 92%; margin: 4%;'; ?>">
			<?php if (!$isMobile && !empty($content['Content']['sidebar_image'])): ?>
			<div style="margin-top: 10px; margin-bottom: 34px;"><img width="240" height="240" src="<?php echo $content['Content']['sidebar_image']; ?>"></div>
			<?php endif; ?>

			<?php if (substr($content['Content']['tag'], 0, 30) == '/resource-library/publications'): ?>
				<form method="post" action="/content/search" style="margin-bottom: 30px;"><input style="color: #333; margin: 0; padding: 4px;" id="search" name="data[query]" placeholder="Search Publications..." /><input type="hidden" name="data[section]" value="/resource-library/publications"><input type="submit" value="Go" style="padding: 4px; margin-left: 4px; color: #333;"></form>
			<?php elseif (substr($content['Content']['tag'], 0, 34) == '/resource-library/resource-library'): ?>
				<form method="post" action="/content/search" style="margin-bottom: 30px;"><input style="color: #333; margin: 0; padding: 4px;" id="search" name="data[query]" placeholder="Search Resource Library..." /><input type="hidden" name="data[section]" value="/resource-library/resource-library"><input type="submit" value="Go" style="padding: 4px; margin-left: 4px; color: #333;"></form>
			<?php elseif (substr($content['Content']['tag'], 0, 54) == '/the-cql-difference/personal-outcome-measures/pom-blog'): ?>
				<form method="post" action="/content/search" style="margin-bottom: 30px;"><input style="color: #333; margin: 0; padding: 4px;" id="search" name="data[query]" placeholder="Search Outcomes Blog..." /><input type="hidden" name="data[section]" value="/the-cql-difference/personal-outcome-measures/pom-blog"><input type="submit" value="Go" style="padding: 4px; margin-left: 4px; color: #333;"></form>
			<?php elseif (substr($content['Content']['tag'], 0, 35) == '/resource-library/glossary-of-terms'): ?>
				<form method="post" action="/content/search" style="margin-bottom: 30px;"><input style="color: #333; margin: 0; padding: 4px;" id="search" name="data[query]" placeholder="Search Glossary..." /><input type="hidden" name="data[section]" value="/resource-library/glossary-of-terms"><input type="submit" value="Go" style="padding: 4px; margin-left: 4px; color: #333;"></form>
			<?php endif; ?>

			<?php if (substr($content['Content']['tag'], 0, 17) == '/news-and-events/'): ?>
			<h1 style="text-transform: uppercase; color: #005594; margin-top: 0; font: 42px 'Roboto Condensed', 'Roboto', 'Arial', sans-serif;">News <span style="font-size: 18px;"><a href="http://feeds.feedburner.com/CQL" target="_blank" style="color: #f60; text-decoration: none; font-weight: bold;"><i class="fa fa-rss"></i></a></span></h1>
			<ul>
				<?php foreach ($news as $n): ?>
					<li><a style="color: #428bca; font: 18px 'Roboto Condensed', 'Roboto', 'Arial', sans-serif;" href="<?php echo $n['News']['url']; ?>" target="_blank"><?php echo $n['News']['headline']; ?></a></li>
				<?php endforeach; ?>
				<p style="margin-top: 18px;"><a href="/news" style="text-transform: uppercase;">View all</a></p>
			</ul>
			<?php else: ?>

			<ul>
			<?php foreach ($sidebar as $sidebarContent): ?>
			<?php if (!empty($sidebarContent['Content']['sidebar_title'])) $sidebarContent['Content']['title'] = $sidebarContent['Content']['sidebar_title']; ?>
			<li<?php if ($_SERVER['REQUEST_URI'] == $sidebarContent['Content']['tag']): ?> class="active"<?php endif; ?>><a href="<?php echo $sidebarContent['Content']['slidedown'] == 1 ? '#' : $sidebarContent['Content']['tag']; ?>"<?php if ($sidebarContent['Content']['slidedown'] == 1) echo ' rel="' . $sidebarContent['Content']['id'] . '" class="slidedown"'; ?>><?php echo $sidebarContent['Content']['title']; ?></a>
				<?php if ((in_array($sidebarContent['Content']['id'], $lineage) || $sidebarContent['Content']['id'] == $content['Content']['id'] || $sidebarContent['Content']['id'] == $content['Content']['parent_id']) && !empty($sidebarContent['children'])): ?>
					<ul id="slidedown<?php echo $sidebarContent['Content']['id']; ?>">
					<?php foreach ($sidebarContent['children'] as $childContent): ?>
					<li<?php if ($_SERVER['REQUEST_URI'] == $childContent['Content']['tag']): ?> class="active"<?php endif; ?>><a href="<?php echo $childContent['Content']['slidedown'] == 1 ? '#' : $childContent['Content']['tag']; ?>"<?php if ($childContent['Content']['slidedown'] == 1) echo ' rel="' . $childContent['Content']['id'] . '" class="slidedown"'; ?>><?php echo $childContent['Content']['title']; ?></a>
					<?php if ((in_array($childContent['Content']['id'], $lineage) || $childContent['Content']['id'] == $content['Content']['id']) && !empty($childContent['children'])): ?>
						<ul id="slidedown<?php echo $childContent['Content']['id']; ?>">
						<?php foreach ($childContent['children'] as $grandChildContent): ?>
							<li<?php if ($_SERVER['REQUEST_URI'] == $grandChildContent['Content']['tag']): ?> class="active"<?php endif; ?>><a href="<?php echo $grandChildContent['Content']['slidedown'] == 1 ? '#' : $grandChildContent['Content']['tag']; ?>"<?php if ($grandChildContent['Content']['slidedown'] == 1) echo ' rel="' . $grandChildContent['Content']['id'] . '" class="slidedown"'; ?>><?php echo $grandChildContent['Content']['title']; ?></a>
							<?php if (in_array($grandChildContent['Content']['id'], $lineage) || $grandChildContent['Content']['id'] == $content['Content']['id']): ?>
								<ul id="slidedown<?php echo $grandChildContent['Content']['id']; ?>">
									<?php foreach ($grandChildContent['children'] as $greatGrandChildContent): ?>
										<li<?php if ($_SERVER['REQUEST_URI'] == $greatGrandChildContent['Content']['tag']): ?> class="active"<?php endif; ?>><a href="<?php echo $greatGrandChildContent['Content']['tag']; ?>"><?php echo $greatGrandChildContent['Content']['title']; ?></a></li>
									<?php endforeach; ?>
								</ul>
							<?php elseif ($grandChildContent['Content']['slidedown'] == 1): ?>
								<ul style="display: none;" id="slidedown<?php echo $grandChildContent['Content']['id']; ?>">
									<?php foreach ($grandChildContent['children'] as $greatGrandChildContent): ?>
										<li<?php if ($_SERVER['REQUEST_URI'] == $greatGrandChildContent['Content']['tag']): ?> class="active"<?php endif; ?>><a href="<?php echo $greatGrandChildContent['Content']['tag']; ?>"><?php echo $greatGrandChildContent['Content']['title']; ?></a></li>
									<?php endforeach; ?>
								</ul>
							<?php endif; ?>
							</li>
						<?php endforeach; ?>
						</ul>
					<?php elseif ($childContent['Content']['slidedown'] == 1): ?>
						<ul style="display: none;" id="slidedown<?php echo $childContent['Content']['id']; ?>">
						<?php foreach ($childContent['children'] as $grandChildContent): ?>
							<li><a href="<?php echo $grandChildContent['Content']['slidedown'] == 1 ? '#' : $grandChildContent['Content']['tag']; ?>"<?php if ($grandChildContent['Content']['slidedown'] == 1) echo ' rel="' . $grandChildContent['Content']['id'] . '" class="slidedown"'; ?>><?php echo $grandChildContent['Content']['title']; ?></a>


							<?php if ($grandChildContent['Content']['slidedown'] == 1): ?>
								<ul style="display: none;" id="slidedown<?php echo $grandChildContent['Content']['id']; ?>">
								<?php foreach ($grandChildContent['children'] as $greatGrandChildContent): ?>
									<li><a href="<?php echo $greatGrandChildContent['Content']['slidedown'] == 1 ? '#' : $greatGrandChildContent['Content']['tag']; ?>"<?php if ($greatGrandChildContent['Content']['slidedown'] == 1) echo ' rel="' . $greatGrandChildContent['Content']['id'] . '" class="slidedown"'; ?>><?php echo $greatGrandChildContent['Content']['title']; ?></a></li>
								<?php endforeach; ?>
								</ul>
							<?php endif; ?>

							</li>
						<?php endforeach; ?>
						</ul>

					<?php endif; ?>
					</li>
					<?php endforeach; ?>
					</ul>
				<?php elseif ($sidebarContent['Content']['slidedown'] == 1): ?>
					<ul style="display: none;" id="slidedown<?php echo $sidebarContent['Content']['id']; ?>">
					<?php foreach ($sidebarContent['children'] as $grandChildContent): ?>
						
							<li><a href="<?php echo $grandChildContent['Content']['slidedown'] == 1 ? '#' : $grandChildContent['Content']['tag']; ?>"<?php if ($grandChildContent['Content']['slidedown'] == 1) echo ' rel="' . $grandChildContent['Content']['id'] . '" class="slidedown"'; ?>><?php echo $grandChildContent['Content']['title']; ?></a>


							<?php if ($grandChildContent['Content']['slidedown'] == 1): ?>
								<ul style="display: none;" id="slidedown<?php echo $grandChildContent['Content']['id']; ?>">
								<?php foreach ($grandChildContent['children'] as $greatGrandChildContent): ?>
									<li><a href="<?php echo $greatGrandChildContent['Content']['slidedown'] == 1 ? '#' : $greatGrandChildContent['Content']['tag']; ?>"<?php if ($greatGrandChildContent['Content']['slidedown'] == 1) echo ' rel="' . $greatGrandChildContent['Content']['id'] . '" class="slidedown"'; ?>><?php echo $greatGrandChildContent['Content']['title']; ?></a>

										<?php if ($greatGrandChildContent['Content']['slidedown'] == 1): ?>
										<ul style="display: none;" id="slidedown<?php echo $greatGrandChildContent['Content']['id']; ?>">
										<?php foreach ($greatGrandChildContent['children'] as $greatGreatGrandChildContent): ?>
											<li><a href="<?php echo $greatGreatGrandChildContent['Content']['slidedown'] == 1 ? '#' : $greatGreatGrandChildContent['Content']['tag']; ?>"<?php if ($greatGreatGrandChildContent['Content']['slidedown'] == 1) echo ' rel="' . $greatGreatGrandChildContent['Content']['id'] . '" class="slidedown"'; ?>><?php echo $greatGreatGrandChildContent['Content']['title']; ?></a></li>
										<?php endforeach; ?>
										</ul>
									<?php endif; ?>
									</li>
								<?php endforeach; ?>
								</ul>
							<?php endif; ?>

							</li>
					<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			</li>
			<?php endforeach; ?>
			</ul>
			<?php endif; ?>

			<?php if (!empty($content['Content']['sidebar_content'])): ?>
			<div style="margin-top: 32px;"><?php echo $content['Content']['sidebar_content']; ?></div>
			<?php endif; ?>
		</aside>
		<?php endif; ?>

<?php endif; ?>
