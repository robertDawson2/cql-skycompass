

<div class="row">
    <div class="col-sm-12">
        <style>.edit-box {
                display: inline;
            margin-left: 30px;
            }
        .edit-row {
        float: right;
        color: blue;
        text-decoration: none;
        font-size: 14px;
        cursor: pointer;
       margin-left: 8px;
    }
    
    .edit-row:hover {
        color: darkblue;
    }
    .quickcontact {
        text-align: center;
        padding: 10px 20px;
        font-size: 14px;
    }
    .red {
        color: darkred;
        
    }
    .cert {
        margin-top: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #afafaf;
    }
    .yellow {
        color: goldenrod;
        
    }
    .green {
        color: darkgreen;
    }
    .quickcustomer {
        text-align: center;
        padding: 10px 20px;
        font-size: 14px;
    }
        </style>
<!-- Customer -->
<div class="box">
            <div class="box-header">
                
                <i class="fa fa-newspaper-o"></i>
                <h3 class="box-title"><em><strong>Contact Training:</strong> <small>Reporting Options</small></em></h3>
                
                    
                
             
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="box box-info collapsed-box">
            <div class="box-header ui-sortable-handle" style="cursor: move;">
              <i class="fa fa-envelope-o"></i>

              <h3 class="box-title">Load From Template</h3>
              <!-- tools box -->
              <div class="pull-right box-tools">
               <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                
              </div>
              <!-- /. tools -->
            </div>
    <div class="box-body">
       <div class="row">
                    <div class="col-md-4">

                        <?= $this->Form->input('loadFromTemplate', array(
                            'class' => 'input form-control',
                            'options' => array('saved template 1')
                        )); ?>
                    </div>
                    <div class="col-md-3">
                        <a role='button' id='btnLoadFromTemplate' class='btn btn-info btn-block'><i class='fa fa-envelope-o'></i> Load</a>
                    </div>
                    
                </div>  
                    </div>
                </div>
                    </div>
                </div>
               
                <div class='row'>
    <div class='col-sm-12'>
           
        <?= $this->element('reporting/training/trainings'); ?>
    </div>
    
</div>
                                <div class='row'>
    <div class='col-sm-12'>
       <?= $this->element('reporting/location'); ?>
    </div>
</div>
<div class='row'>
    <div class='col-sm-12'>
       <?= $this->element('reporting/options'); ?>
    </div>
</div>

                <div class='row'>
                    <div class='col-sm-4 col-sm-offset-8'>
                        <a role='button' id='createReport' class='btn btn-info btn-block'><i class='fa fa-arrow-circle-right'></i> Go</a>
                    </div>
                </div>
                <div class='row'>
                    
                    <div class='col-md-12' style='padding: 10px; overflow-x: scroll;'>
                        <h4>Results</h4>
                        <table id='resultsTable' class='table table-hover table-responsive table-striped table-condensed'>
                            <thead>
                                <tr>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                        <div style = "float: left; "><a role='button' class='resultCheckAll btn btn-default'><i class='fa fa-check-square'></i> Check All</a> &nbsp;  <a role='button' class='resultUncheckAll btn btn-default'><i class='fa fa-square-o'></i> Uncheck All</a></div>
                    </div>
                </div>
                
                <div class='row'>
                    
                     <div class='col-md-8 col-md-offset-2'>
                         <div class="alert alert-danger emptyError" style='display: none;'>
                             <b>Error!</b> No items selected in the table.
                         </div>
                         
                         <div class="alert alert-danger sendError" style='display: none;'>
                             <b>Error!</b> One or more emails did not send.
                         </div>
                         <h4>With Selected:</h4>
                        
                        <?= $this->Form->input('EmailTemplate.id', array(
                            'class' => 'input inline',
                            'div' => 'select input inline',
                            'label' => 'Template Name', 'style' => 'margin-left: 10px;',
                            'options'=>$templateOptions)); ?>
                        <a style='display: inline; margin-left: 15px;' role='button' class='btn btn-success btn-block previewEmail' data-val='email'><i class='fa fa-eye'></i> Preview Template</a>
                        <a style='display: inline; margin-left: 15px;' role='button' class='btn btn-info btn-block generateEmail' data-val='email'><i class='fa fa-send-o'></i> Generate Email</a>
                    </div>
                </div>

        
            </div>
            <!-- /.box-body -->
          </div>
    </div>
    </div>

<?= $this->element('modals/success_send'); ?>

<?php $this->append('conditionsArray'); ?>
$conditions = {
           'andOr' : {
                type: 'ignore',
                criteria: 'AND'
               

            },
            'trainingBetween' : {
                type: 'double-string',
                conditionField: 'Job.start_date',
                conditionType: 'betweenDate',
                conditionSection: 'criteria',
                extra: null,
                data: null
            },
            

            'trainingNotes' : {
                type: 'string',
                conditionField: 'Job.notes',
                conditionType: 'like',
                conditionSection: 'criteria',
                extra: null,
                data: null
            },
            'trainingUpsell' : {
                type: 'list',
                conditionField: 'Job.training_upsell',
                conditionType: 'array',
                conditionSection: 'criteria',
                extra: null,
                data: null
            },
            
            'checkTrainingTypes' : {
                type: 'list',
                conditionField: 'Job.service_area_id',
                conditionType: 'array',
                conditionSection: 'criteria',
                extra: null,
                data: null
            }
        };
        <?php $this->end(); ?>
        
        <?= $this->element('reporting/pageScripts', array(
            'reportType' => 'runContactTrainingReport', 
            'endUrl' => 'contact-training', 
            'templateContext' => 'ContactTraining')); ?>
