<?php $this->set('title_for_layout', 'View All News'); ?>
<article style="width: 100%; float: none;">

<h1>View All News <span style="font-size: 18px;"><a href="http://feeds.feedburner.com/CQL" target="_blank" style="color: #f60; text-decoration: none; font-weight: bold;"><i class="fa fa-rss"></i></a></span></h1>
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