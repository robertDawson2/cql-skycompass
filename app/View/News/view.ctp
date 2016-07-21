<?php $this->set('title_for_layout', $newsItem['News']['headline']); ?>
<article>

<h1><?php echo $newsItem['News']['headline']; ?></h1>
<?php echo $newsItem['News']['content']; ?>
<?php if (!empty($newsItem['News']['url'])): ?>
<p><a target="_blank" href="<?php echo $newsItem['News']['url']; ?>">Read more...</a></p>
<?php endif; ?>

</article>

<aside class="sidebar" style="<?php if ($isMobile) echo 'width: 92%; margin: 4%;'; ?>">
	<h1 style="text-transform: uppercase; color: #005594; margin-top: 0; font: 42px 'Roboto Condensed', 'Roboto', 'Arial', sans-serif;">News <span style="font-size: 18px;"><a href="http://feeds.feedburner.com/CQL" target="_blank" style="color: #f60; text-decoration: none; font-weight: bold;"><i class="fa fa-rss"></i></a></span></h1>
	<ul>
		<?php foreach ($news as $n): ?>
			<li><a style="color: #428bca; font: 18px 'Roboto Condensed', 'Roboto', 'Arial', sans-serif;" href="<?php echo $n['News']['url']; ?>" target="_blank"><?php echo $n['News']['headline']; ?></a></li>
		<?php endforeach; ?>
		<p style="margin-top: 18px;"><a href="/news" style="text-transform: uppercase;">View all</a></p>
	</ul>
</aside>