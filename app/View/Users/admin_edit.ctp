<?php $this->set('title_for_layout', 'Edit User'); ?>
<?php $this->set('breadcrumbs', array('/admin/users' => 'Manage Users', '-' => 'Edit User')); ?>

<form method="post" action="/admin/users/edit" novalidate>

<ul class="nav nav-tabs">
	<li class="active"><a href="#user" data-toggle="tab">Details</a></li>
	<li><a href="#permissions" data-toggle="tab">Permissions</a></li>
</ul>

<div class="tab-content">
	<div class="tab-pane active" id="user">
		<div class="row form-group">	
			<?php echo $this->Form->input('User.first_name', array('div' => 'col-md-6', 'label' => 'First Name', 'autofocus', 'class' => 'input form-control')); ?>
			<?php echo $this->Form->input('User.last_name', array('div' => 'col-md-6', 'label' => 'Last Name', 'class' => 'input form-control')); ?>
		</div>
		<div class="row form-group">	
			<?php echo $this->Form->input('User.email', array('div' => 'col-md-12', 'label' => 'Email Address', 'class' => 'input form-control')); ?>
		</div>
		<div class="row form-group">	
			<?php echo $this->Form->input('User.new_password', array('type' => 'password', 'div' => 'col-md-6', 'label' => 'Password (leave blank for no change)', 'class' => 'input form-control')); ?>
			<?php echo $this->Form->input('User.password_confirmation', array('type' => 'password', 'div' => 'col-md-6', 'label' => 'Password (Repeat)', 'class' => 'input form-control')); ?>
		</div>
	</div>
	
	
	<div class="tab-pane" id="permissions">
		<h4>Site Permissions</h4>
		<div class="row form-group">
			<div class="col-md-12">
				<?php echo $this->Form->input('User.permissions.site.content', array('type' => 'checkbox', 'label' => 'Content <small>(user can edit all content, regardless of permissions below)</small>')); ?>
				<?php echo $this->Form->input('User.permissions.site.home_page_features', array('type' => 'checkbox', 'label' => 'Home Page Features')); ?>
				<?php echo $this->Form->input('User.permissions.site.events', array('type' => 'checkbox', 'label' => 'Events')); ?>
				<?php echo $this->Form->input('User.permissions.site.news', array('type' => 'checkbox', 'label' => 'News')); ?>
				<?php echo $this->Form->input('User.permissions.site.galleries', array('type' => 'checkbox', 'label' => 'Galleries')); ?>
				<?php echo $this->Form->input('User.permissions.site.users', array('type' => 'checkbox', 'label' => 'Users <small>(user can create and manage other users &ndash; including you!)</small>')); ?>
			</div>
		</div>
		
		<h4>Content Permissions</h4>
		<p style="margin-bottom: 0;">Selecting a page will automatically allow access to any children of that page.</p>
		<div class="row form-group">
			<div class="col-md-12">
				<?php
					foreach ($content as $id => $name) {
						$name = explode('|', $name);
						echo $this->Form->input('User.permissions.content.' . $id, array('type' => 'checkbox', 'div' => array('class' => 'input checkbox', 'style' => 'margin-left: ' . ($name[0] * 20) . 'px;'), 'label' => $name[1]));
					}
				?>
			</div>
		</div>
	</div>

</div>

<?php echo $this->Form->hidden('User.id'); ?>
<button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save Changes</button>