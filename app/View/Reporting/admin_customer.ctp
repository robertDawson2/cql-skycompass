
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
                <h3 class="box-title"><em><strong>Customer:</strong> <small>Reporting Options</small></em></h3>
                
                    
                
             
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
                        <a role='button' class='btn btn-info btn-block'><i class='fa fa-envelope-o'></i> Load</a>
                    </div>
                    
                </div>  
                    </div>
                </div>
                    </div>
                </div>
               <div class='row'>
    <div class='col-sm-12'>
           
        <?= $this->element('reporting/customer/criteria'); ?>
    </div>
    
</div>
<div class='row'>
    <div class='col-sm-12'>
       <?= $this->element('reporting/customer/options'); ?>
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
        var current = "";
        // expired first
        if($("#checkGroups").is(":checked"))
        {
            var groups = [];
            $("#GroupGroups > option:checked").each(function() {
                groups.push($(this).val());
            });
           current = "(Group.id IN (" + groups.toString() + ")) OR ";
            conditions += current;
        }
        
        // expiring by date
        if($("#checkTypes").is(":checked"))
        {
          var groups = [];
          var current = "(";
          $first = true;
            $("#TypeTypes > option:checked").each(function() {
                if(!$first)
                    current += " OR ";
                $first = false;
                current += "(Customer.organization_type LIKE '%" + $(this).val() + "%')";
            });
            current += ") OR ";
           
            conditions += current;
        }
         if($("#checkSources").is(":checked"))
        {
            var groups = [];
            $("#SourceSources > option:checked").each(function() {
                groups.push($(this).val());
            });
           current = "(Customer.source IN (" + groups.toString() + ")) OR ";
            conditions += current;
        }
        if($("#checkMarketingEmails").is(":checked"))
        {
            conditions += "(Customer.email_opt_out = '" + $("#marketingEmailsDropdown option:selected").val() +
                  "') OR ";
        //    conditions['CustomerAccreditation.visit_2_18_mo <'] = convertDate($("#visit2").parent().children('.datepicker').val()).toISOString().slice(0, 19).replace('T', ' ');
        //    conditions['CustomerAccreditation.visit_2_18_mo is null'] = 'CustomerAccreditation.visit_2_18_mo is null';
        }
        
        if($("#checkContractExpiration").is(":checked"))
        {
            conditions += "(Customer.contract_expiration < '" +
                    convertDate($("#checkContractExpiration").parent().find('.datepicker').val()).toISOString().slice(0, 19).replace('T', ' ') +
                    "')";
        //    conditions['CustomerAccreditation.visit_2_18_mo <'] = convertDate($("#visit2").parent().children('.datepicker').val()).toISOString().slice(0, 19).replace('T', ' ');
        //    conditions['CustomerAccreditation.visit_2_18_mo is null'] = 'CustomerAccreditation.visit_2_18_mo is null';
        }

       // console.log(conditions);
        return conditions;
    }
    $("#createReport").click(function(){
        var fields = getFields();
        var conditions = getConditions();
        
        var submission = {'fields' : fields,
            'conditions' : conditions
        };
       $.ajax({
                type : 'json',
                method : 'post',
               url : '/admin/reporting/runCustomerReport/Customer',
               data : submission}).done(function(data) {
           
           $("#resultsTable").html(data);
       
   });
   
    });

    $(".export-type").click(function() {
        $(".export-type").removeClass('btn-success').addClass('btn-default');
        $(this).addClass('btn-success');
        $(this).removeClass('btn-default');
        
    });
    $('.categoryCheckAll').click(function() {
    $(this).parent().find('select > option').prop("selected",true);

});   

$('.categoryUncheckAll').click(function() {
    $(this).parent().find('select > option').prop("selected",false);
});

    $('.deepcategoryCheckAll').click(function() {
    $(this).parent().parent().find('select > option').prop("selected",true);

});   

$('.deepcategoryUncheckAll').click(function() {
    $(this).parent().parent().find('select > option').prop("selected",false);
});
</script>
<?php $this->end(); ?>
<?php $this->append('jquery-scripts'); ?>
$('select.fields option').attr("selected","selected");
<?php $this->end(); ?>