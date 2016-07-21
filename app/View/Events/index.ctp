<?php $this->set('title_for_layout', 'Events'); ?>
<article style="width: 100%; float: none;">
<style type="text/css">
.item p { margin: 10px 0; }
</style>

<h1>Events</h1>
<?php foreach ($events as $e): ?>
	<div class="item">
		<?php if (!empty($e['Event']['url'])): ?>
			<h2 style="font-size: 120%;"><a target="_blank" href="<?php echo $e['Event']['url']; ?>"><?php echo $e['Event']['title']; ?></a></h2>
		<?php else: ?>
			<h2 style="font-size: 120%;"><?php echo $e['Event']['title']; ?></h2>
		<?php endif; ?>
		<div style="margin-left: 14px; margin-bottom: 34px;">
			<?php if (!empty($e['Event']['description'])): ?><?php echo $e['Event']['description']; ?><?php endif; ?>
			<p><a class="cal" href="/content/event/<?php echo $e['Event']['id']; ?>"><i class="fa fa-calendar"></i> Save to Calendar (iCal)</a></p>
		</div>
	</div>
<?php endforeach; ?>