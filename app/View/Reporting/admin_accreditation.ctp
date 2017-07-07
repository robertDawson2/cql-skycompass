
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
                <h3 class="box-title"><em><strong>Accreditation:</strong> <small>Reporting Options</small></em></h3>
                
                    
                
              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
           
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
               
               <div class='row'>
    <div class='col-sm-12'>
           
        <?= $this->element('reporting/accreditation/criteria'); ?>
    </div>
    
</div>
<div class='row'>
    <div class='col-sm-12'>
       <?= $this->element('reporting/accreditation/options'); ?>
    </div>
</div>
                <div class='row'>
                    <div class='col-sm-4 col-sm-offset-8'>
                        <a role='button' id='createReport' class='btn btn-info btn-block'><i class='fa fa-arrow-circle-right'></i> Go</a>
                    </div>
                </div>
                <div class='row'>
                    
                    <div class='col-md-12'>
                        <h4>Results</h4>
                        <table id='resultsTable' class='table table-hover table-responsive table-striped table-condensed'>
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Results Here</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type='checkbox' /></td>
                                    <td>Example Record</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class='row'>
                    
                     <div class='col-md-8 col-md-offset-2'>
                         <h4>With Selected:</h4>
                        <a style='display: inline; margin-right: 15px;' role='button' class='btn btn-warning btn-block generateEmail' data-val='email'><i class='fa fa-send-o'></i> Generate Email</a>
                        <?= $this->Form->input('EmailTemplate.id', array(
                            'class' => 'input inline',
                            'div' => 'select input inline',
                            'label' => 'Template Name', 'style' => 'margin-left: 10px;',
                            'options'=>$templateOptions)); ?>
                    </div>
                </div>

        
            </div>
            <!-- /.box-body -->
          </div>
    </div>
    </div>
</div>



<?php $this->append('scripts'); ?>
<script>
    $("#createReport").click(function(){
       $.ajax('/admin/reporting/runReport/CustomerAccreditation').done(function(data) {
           $("#resultsTable").html(data);
       });
    });
    $("#checkAll").click(function() {
        $("#accredTypes > option").prop("selected", true);
    });
    $("#uncheckAll").click(function() {
        $("#accredTypes > option").prop("selected", false);
    });
    $(".export-type").click(function() {
        $(".export-type").removeClass('btn-success').addClass('btn-default');
        $(this).addClass('btn-success');
        $(this).removeClass('btn-default');
        
    });
    
    $('.categoryCheckAll').click(function() {
    $(this).parent().find('select.fields > option').prop("selected",true);

});   

$('.categoryUncheckAll').click(function() {
    $(this).parent().find('select.fields > option').prop("selected",false);
});
</script>
<?php $this->end(); ?>
<?php $this->append('jquery-scripts'); ?>
$('select.fields option').attr("selected","selected");
<?php $this->end(); ?>