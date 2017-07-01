
<div class="box box-danger collapsed-box">
            <div class="box-header ui-sortable-handle" style="cursor: move;">
              <i class="fa fa-trophy"></i>

              <h3 class="box-title">Accreditations</h3>
              <!-- tools box -->
              <div class="pull-right box-tools">
                  <?php $count = 0;
if(!empty($accreditations))
    $count = count($accreditations);
?>
                  <span data-toggle="tooltip" title="" class="badge bg-red" data-original-title="<?= $count; ?> Accreditation Entries"><?= $count; ?></span>
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                
              </div>
              <!-- /. tools -->
            </div>
    <div class="box-body">
        <strong>Key: </strong><div class='key' style='display: inline; margin-left: 40px;'>
                  <i class='fa fa-square red'></i> <span class='red'>Expired</span> &nbsp;
                  <i class='fa fa-square yellow'></i> <span class='yellow'>Expiring Soon</span> &nbsp;
                  <i class='fa fa-square green'></i> <span class='green'>Current</span> &nbsp;
              </div>
<?php if (!empty($accreditations)): ?>
        <table class="table table-striped table-responsive table-condensed">
            <thead>
            <th>Type</th>
            <th>Details</th>
            <th>Dates</th>
            <th></th>
            <th>Notes</th>
            </thead>
            <tbody>
                <?php foreach($accreditations as $accreditation): 
                $expiring = "";
             if(!empty($accreditation['expiration_date']) && $accreditation['expiration_date'] !== NULL)
             {
             $thirty = strtotime("+30 days");
             $current = time();
             $renewal = strtotime($accreditation['expiration_date']);
             if($renewal <= $current)
             {
                 $expiring = 'red';
             }
             else if($renewal <= $thirty) {
                 $expiring = 'yellow';
             }
             else
             {
                 $expiring = 'green';
             }
             }
             ?>
                <tr class="<?= $expiring; ?>">
                    <td><?= $types[$accreditation['accreditation_id']]; ?></td>
                    <td>
                        <?php if(!empty($accreditation['services_provided'])): ?>
                        <strong>Services: </strong><?= $accreditation['services_provided']; ?><br />
                        <?php endif; ?>
                        <strong>Terms: </strong><?= $accreditation['terms']; ?><br />
                        <strong>Exp: </strong><?= $accreditation['expiration_date'] === null ? "" : date('m/d/Y', strtotime($accreditation['expiration_date']));?><br />
                        <strong>Ext: </strong><?= $accreditation['extension_date'] === null ? "" : date('m/d/Y', strtotime($accreditation['extension_date']));?><br />
                        <?php if(!empty($accreditation['people_served'])): ?>
                        <strong>Served: </strong><?= $accreditation['people_served']; ?><br />
                        <?php endif; ?>
                        <?php if(!empty($accreditation['people_interviewed'])): ?>
                        <strong>Interviewed: </strong><?= $accreditation['people_interviewed']; ?><br />
                        <?php endif; ?>
                    </td>
                    <td>
                        <strong>Visit 1</strong><br />
                        <em>
                            Start: <?= $accreditation['visit_1_start_date'] === null ? "" : date('m/d/Y', strtotime($accreditation['visit_1_start_date']));?><br />
                            End: <?= $accreditation['visit_1_end_date'] === null ? "" : date('m/d/Y', strtotime($accreditation['visit_1_end_date']));?>
                        </em>
                        <br /><br />
                        <strong>Visit 2</strong><br />
                        <em>
                            Due: <?= $accreditation['visit_2_18_mo'] === null ? "" : date('m/d/Y', strtotime($accreditation['visit_2_18_mo']));?><br />
                            Start: <?= $accreditation['visit_2_start_date'] === null ? "" : date('m/d/Y', strtotime($accreditation['visit_2_start_date']));?><br />
                            End: <?= $accreditation['visit_2_end_date'] === null ? "" : date('m/d/Y', strtotime($accreditation['visit_2_end_date']));?>
                        </em>
                        <?php if($accreditation['visit_3_required']): ?>
                        <br /><br />
                        <strong>Visit 3 Required</strong><br />
                        <em>
                            Due: <?= $accreditation['visit_3_36_mo'] === null ? "" : date('m/d/Y', strtotime($accreditation['visit_3_36_mo']));?><br />
                            Start: <?= $accreditation['visit_3_start_date'] === null ? "" : date('m/d/Y', strtotime($accreditation['visit_3_start_date']));?><br />
                            End: <?= $accreditation['visit_3_end_date'] === null ? "" : date('m/d/Y', strtotime($accreditation['visit_3_end_date']));?>
                        </em>
                        <?php endif; ?>
                    </td>
                    <td>
                        <strong>PCE</strong><br />
                        <em>
                            Start: <?= $accreditation['pce_start_date'] === null ? "" : date('m/d/Y', strtotime($accreditation['pce_start_date']));?><br />
                            End: <?= $accreditation['pce_end_date'] === null ? "" : date('m/d/Y', strtotime($accreditation['pce_end_date']));?>
                        </em>
                        <?php if($accreditation['9_mo_followup_required']): ?>
                        <br /><br />
                        
                        <strong>9 Mo Follow-Up Required</strong><br />
                        <em>
                            Due: <?= $accreditation['9_mo_due_date'] === null ? "" : date('m/d/Y', strtotime($accreditation['9_mo_due_date']));?><br />
                            Actual: <?= $accreditation['9_mo_actual_date'] === null ? "" : date('m/d/Y', strtotime($accreditation['9_mo_actual_date']));?><br />
                            
                        </em>
                        <?php endif; 
                        if($accreditation['18_mo_onsite_required']): ?>
                        <br /><br />
                        <strong>18 Mo On-Site Required</strong><br />
                            Due: <?= $accreditation['18_mo_due_date'] === null ? "" : date('m/d/Y', strtotime($accreditation['18_mo_due_date']));?><br />
                            Actual: <?= $accreditation['18_mo_actual_date'] === null ? "" : date('m/d/Y', strtotime($accreditation['18_mo_actual_date']));?>
                        </em>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?= $accreditation['notes']; ?>
                    </td>
                </tr>
                
                <?php endforeach; ?>
            </tbody>
        </table>
<?php else: ?>
<p>There are no accreditations linked to this customer.</p>
<?php endif; ?>
    </div>
</div>