
<ul class="sidebar-menu">
        <li class="header"></li>
                <li class="header"><?= $currentUser['web_user_type']; ?> Dashboard</li>
           
                <?php if(!empty($currentUser['pmArray']) && $currentUser['pmArray']['site']['content'] == 1): ?>

                <li class="<?= $section == 'web' ? 'active' : ''; ?> treeview">
          <a href="#">
            <i class="fa fa-globe"></i> <span>Webpage Management</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
                   
          <ul class="treeview-menu">
            <li><a href="/admin/content"><i class="fa fa-edit"></i> View/Edit Content</a></li>
            <?php if(!empty($currentUser['pmArray']) && $currentUser['pmArray']['site']['home_page_features'] == 1): ?>
            <li><a href="/admin/features"><i class="fa fa-star"></i> View/Edit Features</a></li>
            <?php endif; ?>
            <?php if(!empty($currentUser['pmArray']) && $currentUser['pmArray']['site']['events'] == 1): ?>
            <li><a href="/admin/events"><i class="fa fa-calendar"></i> View/Edit Events</a></li>
            <?php endif; ?>
            <?php if(!empty($currentUser['pmArray']) && $currentUser['pmArray']['site']['news'] == 1): ?>
            <li><a href="/admin/news"><i class="fa fa-microphone"></i> View/Edit News</a></li>
            <?php endif; ?>
            <?php if(!empty($currentUser['pmArray']) && $currentUser['pmArray']['site']['galleries'] == 1): ?>
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
            <li><a disabled href="/admin/customers/add"><i class="fa fa-plus"></i> Add New Customer</a></li>
            <?php endif; ?>
            <li><a href="/admin/customers"><i class="fa fa-eye"></i> View Customers</a></li>
           </ul>
        </li>
        <?php endif; ?>
        <?php if(1): ?>
        <li class="<?= $section == 'contacts' ? 'active' : ''; ?> treeview">
          <a href="#">
            <i class="fa fa-users"></i>
            <span>Contacts</span>
           <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li><a href="/admin/contacts/add"><i class="fa fa-plus"></i> Add New Contact</a></li>
            <li><a href="/admin/contacts"><i class="fa fa-edit"></i> View Contacts</a></li>
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
            <li><a href="/admin/jobs/add"><i class="fa fa-plus-circle"></i> New Job</a></li>
            
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
            <?php if($currentUser['vendor_id'] != null && isset($currentUser['Vendor']) && !empty($currentUser['Vendor'])) { ?>
            <li><a href="#expenseModal" data-toggle="modal" ><i class="fa fa-user-plus"></i> Add Expense</a></li>

            <li><a href="/admin/expenses/viewMyExpenses"><i class="fa fa-list"></i> View My Expenses</a></li>
            <?php } ?>
            <?php if(!empty($currentUser['pmArray']) && $currentUser['pmArray']['timeEntries']['admin_approve']): ?>
            <li><a href="/admin/timeEntry/approve"><i class="fa fa-clock-o"></i> Approve Employee Time</a></li>
            <?php endif; ?>
            <?php if(!empty($currentUser['pmArray']) && $currentUser['pmArray']['bills']['admin_approve']): ?>
            <li><a href="/admin/expenses/approve"><i class="fa fa-credit-card"></i> Approve Employee Expenses</a></li>
            <?php endif; ?>
            <?php if(!empty($currentUser['pmArray']) && $currentUser['pmArray']['timeEntries']['admin_index']): ?>
            <li><a href="/admin/timeEntry/viewApproved"><i class="fa fa-hourglass-half"></i> Time Log Summary</a></li>
            <?php endif; ?>
            <?php if(!empty($currentUser['pmArray']) && $currentUser['pmArray']['bills']['admin_index']): ?>
            <li><a href="/admin/expenses/approved"><i class="fa fa-money"></i> Expense Summary</a></li>
            <?php endif; ?>
            <?php if(!empty($currentUser['pmArray']) && $currentUser['pmArray']['bills']['admin_index']): ?>
            <li><a href="#expenseReportModal" data-toggle='modal'><i class="fa fa-money"></i> CC Expense Report</a></li>
            <?php endif; ?>
          </ul>
        </li>
        <li class="<?= $section == 'scheduling' ? 'active' : ''; ?> treeview">
        <a href="#">
            <i class="fa fa-calendar"></i> <span>Scheduling</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
              <?php if($currentUser['is_scheduler'] || (!empty($currentUser['pmArray']) && $currentUser['pmArray']['jobs']['admin_scheduler'] === 1)): ?>
            <li><a href='/admin/jobs/scheduler'><i class='fa fa-calendar'></i> Scheduler</a></li>
            <?php endif; ?>
             <?php if($currentUser['is_scheduler'] || (!empty($currentUser['pmArray']) && $currentUser['pmArray']['schedule']['admin_alertAllUsers'] === 1)): ?>
            <li><a href="/admin/schedule/alertAllUsers" onclick="return confirm('Are you sure you want to send out scheduling emails?');">
                    <i class="fa fa-envelope"></i> Send Out Schedule Email</a></li>
            <?php endif; ?>
            <li><a href="/admin/schedule/requestOff"><i class="fa fa-circle-o"></i> Request Time Off</a></li>
            <li><a href="/admin/schedule/mySchedule"><i class="fa fa-circle-o"></i> View Schedule</a></li>
            <li><a href="/admin/schedule/approveMySchedule"><i class="fa fa-circle-o"></i> Approve/Deny Schedule</a></li>
            <?php if(!empty($currentUser['pmArray']) && $currentUser['pmArray']['schedule']['admin_approveTimeOff']): ?>
            <li><a href="/admin/schedule/approveTimeOff"><i class="fa fa-circle-o"></i> Approve/Deny Time Off</a></li>
            <?php endif; ?>
            <?php if(!empty($currentUser['pmArray']) && $currentUser['pmArray']['taskListTemplates']['admin_index']): ?>
            <li><a href="/admin/taskListTemplates"><i class="fa fa-circle-o"></i> View Task List Templates</a></li>
            <?php endif; ?>
            <?php if(!empty($currentUser['pmArray']) && $currentUser['pmArray']['taskListTemplates']['admin_create']): ?>
            <li><a href="/admin/taskListTemplates/create"><i class="fa fa-circle-o"></i> Add Task List Template</a></li>
            <?php endif; ?>
            <?php if(!empty($currentUser['pmArray']) && $currentUser['pmArray']['schedule']['admin_viewServiceAreas']): ?>
            <li><a href="/admin/schedule/viewServiceAreas"><i class="fa fa-circle-o"></i> Manage Service Areas</a></li>
            <li><a href="/admin/schedule/viewAbilities"><i class="fa fa-circle-o"></i> Manage Abilities</a></li>
            <?php endif; ?>
          </ul>
        </li>
        
        <li class="<?= $section == 'communications' ? 'active' : ''; ?> treeview">
          <a href="#">
            <i class="fa fa-send"></i> <span>Communications</span>
            
            <i class="fa fa-angle-left pull-right"></i>
            
          </a>
          <ul class="treeview-menu">
              
            <li><a href="/communications/campaigns"><i class="fa fa-check-square-o"></i> View Campaign Statistics</a></li>
         
            <li><a href="/admin/emailTemplates"><i class="fa fa-list"></i> Manage Email Templates</a></li>
           
          </ul>
        </li>
        
        

        <?php if(!empty($currentUser['pmArray']) && $currentUser['pmArray']['config']['admin_index']): ?>
        <li class="<?= $section == 'misc' ? 'active' : ''; ?><?= $section == 'crm' ? 'active' : ''; ?>  treeview">
          <a href="#">
            <i class="fa fa-gears"></i> <span>Miscellaneous</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li><a href="pages/examples/invoice.html"><i class="fa fa-circle-o"></i> Site Advanced Settings</a></li>
            <li><a href="pages/examples/profile.html"><i class="fa fa-circle-o"></i> Edit My Profile</a></li>
            <li class='<?= $section == 'crm' ? 'active' : ''; ?> '>
              <a href="#"><i class="fa fa-plus-circle"></i> CRM Elements <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="/admin/groups"><i class="fa fa-circle-o"></i> Manage Groups</a></li>
                <li><a href="/admin/certifications"><i class="fa fa-circle-o"></i> Manage Certs</a></li>
                <li><a href="/admin/accreditations"><i class="fa fa-circle-o"></i> Manage Accreds</a></li>
                <li><a href="/admin/contactTypes"><i class="fa fa-circle-o"></i> Manage Contact Types</a></li>
                <li><a href="/admin/customerTypes"><i class="fa fa-circle-o"></i> Manage Customer Types</a></li>
              </ul>
            </li>
          </ul>
        </li>
        <?php endif; ?>
        
        <?php if(!empty($currentUser['pmArray']) && $currentUser['pmArray']['schedule']['admin_scheduleReport']): ?>
        <li class="<?= $section == 'reporting' ? 'active' : ''; ?><?= $section == 'crmreporting' ? 'active' : ''; ?> treeview">
          <a href="#">
            <i class="fa fa-bar-chart-o"></i> <span>Reporting</span>
            <i class="fa fa-angle-left pull-right"></i>
          </a>
          <ul class="treeview-menu">
            <li><a href="/admin/schedule/scheduleReport"><i class="fa fa-circle-o"></i> Approval/Denial Report</a></li>
            <li class='<?= $section == 'crmreporting' ? 'active' : ''; ?> '>
              <a href="#"><i class="fa fa-plus-circle"></i> CRM Reports <i class="fa fa-angle-left pull-right"></i></a>
              <ul class="treeview-menu">
                <li><a href="/admin/reporting/accreditation"><i class="fa fa-circle-o"></i> Accreditation Reports</a></li>
                <li><a href="/admin/reporting/certification"><i class="fa fa-circle-o"></i> Certification Reports</a></li>
                <li><a href="/admin/reporting/contact"><i class="fa fa-circle-o"></i> Contact Reports</a></li>
                <li><a href="/admin/reporting/customer"><i class="fa fa-circle-o"></i> Customer Reports</a></li>
                <li><a href="/admin/reporting/user"><i class="fa fa-circle-o"></i> User Reports</a></li>
              </ul>
            </li>
          </ul>
        </li>
        <?php endif; ?>
        
        <?php if(isset($superUser)): ?> 
        <li class="<?= $section == 'superApprove' ? 'active' : ''; ?> treeview">
          <a href="/admin/timeEntry/superApprove">
            <i class="fa fa-clock-o"></i> <span>Send Time To QB</span>
     
            <span class="label label-primary pull-right"><?= $superUser['entries']; ?></span>

          </a>
          
        </li>
        <li class="<?= $section == 'superApprove' ? 'active' : ''; ?> treeview">
          <a href="/admin/expenses/superApprove">
            <i class="fa fa-money"></i> <span>Send Bills To QB</span>
     
            <span class="label label-info pull-right"><?= $superUser['expenses']; ?></span>

          </a>
          
        </li>
        <?php endif; ?>
          </ul>
