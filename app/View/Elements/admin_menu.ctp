
<ul class="sidebar-menu">
        <li class="header"></li>
                <li class="header"><?= $currentUser['web_user_type']; ?> Dashboard</li>

                <?php if(!empty($currentUser['pmArray']) && $currentUser['pmArray']['site']['content'] === "1"): ?>
                <li class="active treeview">
          <a href="#">
            <i class="fa fa-globe"></i> <span>Webpage Management</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li><a href="/admin/content"><i class="fa fa-edit"></i> View/Edit Content</a></li>
            <li><a href="/admin/features"><i class="fa fa-star"></i> View/Edit Features</a></li>
            <li><a href="/admin/events"><i class="fa fa-calendar"></i> View/Edit Events</a></li>
            <li><a href="/admin/news"><i class="fa fa-microphone"></i> View/Edit News</a></li>
            <li><a href="#" id="galleryManager"><i class="fa fa-image"></i> View/Edit Galleries</a></li>
          </ul>
        </li>
       <?php endif; ?>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-building"></i> <span>Customers</span> <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li><a href="index.html"><i class="fa fa-plus"></i> Add New Customer</a></li>
            <li><a href="index2.html"><i class="fa fa-edit"></i> View Customers</a></li>
           </ul>
        </li>
        <li class="treeview">
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
      
        <li class="treeview">
          <a href="#">
            <i class="fa fa-calendar"></i>
            <span>Jobs</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li><a href="pages/UI/general.html"><i class="fa fa-circle-o"></i> View Open Jobs</a></li>
            <li><a href="pages/UI/icons.html"><i class="fa fa-circle"></i> View Past Jobs</a></li>
          </ul>
        </li>
        <?php if(!empty($currentUser['pmArray']) && $currentUser['pmArray']['site']['users'] === "1"): ?>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-cubes"></i> <span>Employees</span>
            <?php if($newEmployees > 0): ?>
            
            <span class="label label-primary pull-right"><?= $newEmployees; ?></span>
            <?php else: ?>
            <i class="fa fa-angle-left pull-right"></i>
            <?php endif; ?>
          </a>
          <ul class="treeview-menu">
            <li><a href="#"><i class="fa fa-user-plus"></i> Add New Employee</a></li>
            <li><a href="/admin/users"><i class="fa fa-list"></i> View All Employees</a></li>
            <li><a href="#"><i class="fa fa-clock-o"></i> Approve Employee Time</a></li>
            <li><a href="#"><i class="fa fa-credit-card"></i> Approve Employee Expenses</a></li>
            <li><a href="#"><i class="fa fa-hourglass-half"></i> Time Log Summary</a></li>
            <li><a href="#"><i class="fa fa-money"></i> Expense Summary</a></li>  
          </ul>
        </li>
        
        
        <?php endif; ?>
        
        <li class="treeview">
          <a href="#">
            <i class="fa fa-gears"></i> <span>Miscellaneous</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li><a href="pages/examples/invoice.html"><i class="fa fa-circle-o"></i> Site Advanced Settings</a></li>
            <li><a href="pages/examples/profile.html"><i class="fa fa-circle-o"></i> Edit My Profile</a></li>
          </ul>
        </li>
        
          </ul>