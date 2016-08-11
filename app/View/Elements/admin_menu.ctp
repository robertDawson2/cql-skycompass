
<ul class="sidebar-menu">
        <li class="header"></li>
                <li class="header"><?= $currentUser['web_user_type']; ?> Dashboard</li>
           
                <?php if(!empty($currentUser['pmArray']) && $currentUser['pmArray']['site']['content'] === 1): ?>

                <li class="<?= $section == 'web' ? 'active' : ''; ?> treeview">
          <a href="#">
            <i class="fa fa-globe"></i> <span>Webpage Management</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li><a href="/admin/content"><i class="fa fa-edit"></i> View/Edit Content</a></li>
            <?php if(!empty($currentUser['pmArray']) && $currentUser['pmArray']['site']['home_page_features'] === 1): ?>
            <li><a href="/admin/features"><i class="fa fa-star"></i> View/Edit Features</a></li>
            <?php endif; ?>
            <?php if(!empty($currentUser['pmArray']) && $currentUser['pmArray']['site']['events'] === 1): ?>
            <li><a href="/admin/events"><i class="fa fa-calendar"></i> View/Edit Events</a></li>
            <?php endif; ?>
            <?php if(!empty($currentUser['pmArray']) && $currentUser['pmArray']['site']['news'] === 1): ?>
            <li><a href="/admin/news"><i class="fa fa-microphone"></i> View/Edit News</a></li>
            <?php endif; ?>
            <?php if(!empty($currentUser['pmArray']) && $currentUser['pmArray']['site']['galleries'] === 1): ?>
            <li><a href="#" id="galleryManager"><i class="fa fa-image"></i> View/Edit Galleries</a></li>
            <?php endif; ?>
          </ul>
        </li>
       <?php endif; ?>
        <?php if(!empty($currentUser['pmArray']) && $currentUser['pmArray']['customers']['admin_index']): ?>
        <li class="<?= $section == 'customers' ? 'active' : ''; ?> treeview">
          <a href="#">
            <i class="fa fa-building"></i> <span>Customers</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
              <?php if(!empty($currentUser['pmArray']) && $currentUser['pmArray']['customers']['admin_add']): ?>
            <li><a disabled href="#"><i class="fa fa-plus"></i> Add New Customer</a></li>
            <?php endif; ?>
            <li><a href="/admin/customers"><i class="fa fa-edit"></i> View Customers</a></li>
           </ul>
        </li>
        <?php endif; ?>
        <?php if(0): ?>
        <li class="<?= $section == 'contacts' ? 'active' : ''; ?> treeview">
          <a href="#">
            <i class="fa fa-users"></i>
            <span>Contacts</span>
           <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li><a href="pages/layout/top-nav.html"><i class="fa fa-plus"></i> Add New Contacts</a></li>
            <li><a href="pages/layout/boxed.html"><i class="fa fa-edit"></i> View Contacts</a></li>
         </ul>
        </li>
      <?php endif; ?>
        <?php if(!empty($currentUser['pmArray']) && $currentUser['pmArray']['jobs']['admin_index']): ?>
        <li class="<?= $section == 'jobs' ? 'active' : ''; ?> treeview">
          <a href="#">
            <i class="fa fa-calendar"></i>
            <span>Jobs</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li><a href="/admin/jobs"><i class="fa fa-circle-o"></i> View Open Jobs</a></li>
            <li><a href="/admin/jobs/index/past"><i class="fa fa-circle"></i> View Past Jobs</a></li>
          </ul>
        </li>
        <?php endif; ?>
        <?php if(!empty($currentUser['pmArray']) && $currentUser['pmArray']['users']['admin_index']): ?>
        <li class="<?= $section == 'employees' ? 'active' : ''; ?> treeview">
          <a href="#">
            <i class="fa fa-cubes"></i> <span>Employees</span>
            <?php if($newEmployees > 0): ?>
            
            <span class="label label-primary pull-right"><?= $newEmployees; ?></span>
            <?php else: ?>
            <i class="fa fa-angle-left pull-right"></i>
            <?php endif; ?>
          </a>
          <ul class="treeview-menu">
              <?php if(!empty($currentUser['pmArray']) && $currentUser['pmArray']['users']['admin_add']): ?>
            <li><a href="#"><i class="fa fa-user-plus"></i> Add New Employee</a></li>
            <?php endif; ?>
            <li><a href="/admin/users"><i class="fa fa-list"></i> View All Employees</a></li>
           
          </ul>
        </li>
        
        
        <?php endif; ?>
        
        <li class="<?= $section == 'time' ? 'active' : ''; ?> treeview">
          <a href="#">
            <i class="fa fa-cubes"></i> <span>Time/Expenses</span>
           
            <i class="fa fa-angle-left pull-right"></i>
           
          </a>
          <ul class="treeview-menu">
   
            <li><a href="#entryModal" data-toggle="modal" ><i class="fa fa-user-plus"></i> Add Time Entry</a></li>

            <li><a href="/admin/timeEntry/viewMyTime"><i class="fa fa-list"></i> View My Time Entries</a></li>
            <?php if(isset($currentUser['Vendor']) && !empty($currentUser['Vendor'])) { ?>
            <li><a href="/admin/expenses/add" data-toggle="modal" ><i class="fa fa-user-plus"></i> Add Expense</a></li>

            <li><a href="/admin/expenses/viewMyExpenses"><i class="fa fa-list"></i> View My Expenses</a></li>
            <?php } ?>
            <?php if(!empty($currentUser['pmArray']) && $currentUser['pmArray']['time_entries']['admin_approve']): ?>
            <li><a href="/admin/timeEntry/approve"><i class="fa fa-clock-o"></i> Approve Employee Time</a></li>
            <?php endif; ?>
            <?php if(!empty($currentUser['pmArray']) && $currentUser['pmArray']['bills']['admin_approve']): ?>
            <li><a href="/admin/expenses/approve"><i class="fa fa-credit-card"></i> Approve Employee Expenses</a></li>
            <?php endif; ?>
            <?php if(!empty($currentUser['pmArray']) && $currentUser['pmArray']['time_entries']['admin_index']): ?>
            <li><a href="#"><i class="fa fa-hourglass-half"></i> Time Log Summary</a></li>
            <?php endif; ?>
            <?php if(!empty($currentUser['pmArray']) && $currentUser['pmArray']['bills']['admin_index']): ?>
            <li><a href="#"><i class="fa fa-money"></i> Expense Summary</a></li>
            <?php endif; ?>
          </ul>
        </li>
        
        <?php if(!empty($currentUser['pmArray']) && $currentUser['pmArray']['config']['admin_index']): ?>
        <li class="<?= $section == 'misc' ? 'active' : ''; ?> treeview">
          <a href="#">
            <i class="fa fa-gears"></i> <span>Miscellaneous</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li><a href="pages/examples/invoice.html"><i class="fa fa-circle-o"></i> Site Advanced Settings</a></li>
            <li><a href="pages/examples/profile.html"><i class="fa fa-circle-o"></i> Edit My Profile</a></li>
          </ul>
        </li>
        <?php endif; ?>
          </ul>