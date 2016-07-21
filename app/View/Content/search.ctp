<?php $this->set('title_for_layout', 'Search Results'); ?>
<?php if (isset($content)): ?>
<?php $_SERVER['REQUEST_URI'] = $searchSection; ?>
<article>
<?php else: ?>
<article style="width: 100%; float: none;">
<?php endif; ?>

<h1>Search Results</h1>

<?php if (isset($searchSection)): ?>
	<?php $labels = array('/resource-library/publications' => 'Publications', '/resource-library/resource-library' => 'Resource Library', '/the-cql-difference/personal-outcome-measures/pom-blog' => 'Personal Outcomes Blog', '/resource-library/glossary-of-terms' => 'Glossary of Terms'); ?>
	<p>Searching within the <b><?php echo $labels[$searchSection]; ?></b><?php if ($searchSection == '/resource-library/publications'): ?> section<?php endif; ?></p>
<?php endif; ?>

<?php if (count($results) > 0): ?>
<p>Found <b><?php echo count($results); ?></b> result<?php if (count($results) > 0) echo 's'; ?> matching your query: <b><?php echo $query; ?></b></p>
<?php foreach ($results as $result): ?>
<h4 style="margin-top: 26px; margin-bottom: 0;"><a href="<?php echo $result['content']['tag']; ?>"><?php echo str_replace('{br}', ' ', $result['content']['title']); ?></a></h4>
<p style="margin-top: 8px;"><?php echo $this->Text->truncate(trim(str_replace('&nbsp;', ' ', strip_tags($result['content']['content']))), 300, array('ellipsis' => '...', 'exact' => false)); ?></p>
<?php endforeach; ?>
<?php else: ?>
<p>No results found matching your query: <b><?php echo $query; ?></b></p>
<?php endif; ?>
</article>

<?php if (isset($content) && !$isMobile && !$content['Content']['hide_sidebar']): ?>
	<aside class="sidebar">
		<?php if (!empty($content['Content']['sidebar_image'])): ?>
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