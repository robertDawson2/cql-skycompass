
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
    function getFields()
    {
        var fields = [];
        $(".fields > option").each(function() {
            $category = $(this).parent().data('category');
            $field = $(this).text();
            if($(this).prop('selected'))
            {
                fields.push($category + "." + $field);
            }
        });
        return fields;
    }
    function convertDate(startDate) {
        var parts = startDate.split('/');
        return new Date(parts[2],parts[0]-1, parts[1]);
        
    }
    function getConditions()
    {
//        var conditions = {
//            'CustomerAccreditation.expiration_date <' : null,
//            'CustomerAccreditation.visit_2_18_mo <' : null,
//            'CustomerAccreditation.visit_2_start_date is null' : null
//        };

        var conditions = "";
        // expired first
        if($("#expired").is(":checked"))
        {
            conditions += "(CustomerAccreditation.expiration_date < '<?= date('Y-m-d H:i:s'); ?>') OR ";
           // conditions['CustomerAccreditation.expiration_date <'] = "<?= date('Y-m-d H:i:s'); ?>";
        }
        
        // expiring by date
        if($("#expiring").is(":checked"))
        {
          conditions += "(CustomerAccreditation.expiration_date < '" + 
                  convertDate($("#expiring").parent().children('.datepicker').val()).toISOString().slice(0, 19).replace('T', ' ') + 
                  "') OR ";
          //  conditions['CustomerAccreditation.expiration_date <'] = convertDate($("#expiring").parent().children('.datepicker').val()).toISOString().slice(0, 19).replace('T', ' ');
        }
         if($("#visit2").is(":checked"))
        {
            conditions += "(CustomerAccreditation.visit_2_18_mo < '" +
                    convertDate($("#visit2").parent().children('.datepicker').val()).toISOString().slice(0, 19).replace('T', ' ') + 
                    "' AND CustomerAccreditation.visit_2_start_date is null) OR ";
        //    conditions['CustomerAccreditation.visit_2_18_mo <'] = convertDate($("#visit2").parent().children('.datepicker').val()).toISOString().slice(0, 19).replace('T', ' ');
        //    conditions['CustomerAccreditation.visit_2_18_mo is null'] = 'CustomerAccreditation.visit_2_18_mo is null';
        }
        if($("#visit3").is(":checked"))
        {
            conditions += "(CustomerAccreditation.visit_3_36_mo < '" +
                    convertDate($("#visit3").parent().children('.datepicker').val()).toISOString().slice(0, 19).replace('T', ' ') + 
                    "' AND CustomerAccreditation.visit_3_start_date is null) OR ";
        //    conditions['CustomerAccreditation.visit_2_18_mo <'] = convertDate($("#visit2").parent().children('.datepicker').val()).toISOString().slice(0, 19).replace('T', ' ');
        //    conditions['CustomerAccreditation.visit_2_18_mo is null'] = 'CustomerAccreditation.visit_2_18_mo is null';
        }
        
        if($("#9mo").is(":checked"))
        {
            conditions += "(CustomerAccreditation.9_mo_due_date < '" +
                    convertDate($("#9mo").parent().children('.datepicker').val()).toISOString().slice(0, 19).replace('T', ' ') + 
                    "' AND CustomerAccreditation.9_mo_actual_date is null) OR ";
        //    conditions['CustomerAccreditation.visit_2_18_mo <'] = convertDate($("#visit2").parent().children('.datepicker').val()).toISOString().slice(0, 19).replace('T', ' ');
        //    conditions['CustomerAccreditation.visit_2_18_mo is null'] = 'CustomerAccreditation.visit_2_18_mo is null';
        }
        
        if($("#18mo").is(":checked"))
        {
            conditions += "(CustomerAccreditation.18_mo_due_date < '" +
                    convertDate($("#18mo").parent().children('.datepicker').val()).toISOString().slice(0, 19).replace('T', ' ') + 
                    "' AND CustomerAccreditation.18_mo_actual_date is null) OR ";
        //    conditions['CustomerAccreditation.visit_2_18_mo <'] = convertDate($("#visit2").parent().children('.datepicker').val()).toISOString().slice(0, 19).replace('T', ' ');
        //    conditions['CustomerAccreditation.visit_2_18_mo is null'] = 'CustomerAccreditation.visit_2_18_mo is null';
        }
        
        if($("#notes").is(":checked"))
        {
            conditions += "(CustomerAccreditation.notes like '%" +
                   $("#9mo").parent().children('.form-control').val() + 
                    "%'";
        //    conditions['CustomerAccreditation.visit_2_18_mo <'] = convertDate($("#visit2").parent().children('.datepicker').val()).toISOString().slice(0, 19).replace('T', ' ');
        //    conditions['CustomerAccreditation.visit_2_18_mo is null'] = 'CustomerAccreditation.visit_2_18_mo is null';
        }
        var accredcond = "(";
        $first = true;
        $("#accredTypes > option").each(function() {
            
       if($(this).is(":checked"))
       {
            if(!$first)
              accredcond += ", ";
          $first = false;
          accredcond += "'" + $(this).val() + "'";
         
        }
    });
    accredcond += ")";
    var newcond = 'CustomerAccreditation.accreditation_id IN ' + accredcond + " AND (" + conditions + ")";
    conditions = newcond;
    
        console.log(conditions);
        return conditions;
    }
    $("#createReport").click(function(){
        var fields = getFields();
        var conditions = getConditions();
        
        var submission = {'fields' : fields,
            'conditions' : conditions
        }
       $.ajax({
                type : 'json',
                method : 'post',
               url : '/admin/reporting/runAccreditationReport/CustomerAccreditation',
               data : submission}).done(function(data) {
           
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