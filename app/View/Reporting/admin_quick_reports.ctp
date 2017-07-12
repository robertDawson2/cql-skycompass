<?php $this->Html->script('https://cdn.datatables.net/buttons/1.3.1/js/dataTables.buttons.min.js', array('block' => 'scripts')); 
    $this->Html->script('//cdn.datatables.net/buttons/1.3.1/js/buttons.flash.min.js', array('block' => 'scripts')); 
    $this->Html->script('//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js', array('block' => 'scripts')); 
    $this->Html->script('//cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/pdfmake.min.js', array('block' => 'scripts')); 
    $this->Html->script('//cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/vfs_fonts.js', array('block' => 'scripts')); 
    $this->Html->script('//cdn.datatables.net/buttons/1.3.1/js/buttons.html5.min.js', array('block' => 'scripts')); 
    $this->Html->script('//cdn.datatables.net/buttons/1.3.1/js/buttons.print.min.js ', array('block' => 'scripts')); 

?>

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
                <h3 class="box-title"><em><strong>Quick</strong> Reports</em></h3>
                
                    
                
             
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="box box-info">
            <div class="box-header ui-sortable-handle" style="cursor: move;">
              <i class="fa fa-gears"></i>

              <h3 class="box-title">Generate From Template</h3>
              <!-- tools box -->
              <div class="pull-right box-tools">
               <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                
              </div>
              <!-- /. tools -->
            </div>
    <div class="box-body">
       <div class="row">
                    <div class="col-md-3" id='customer'>
                        <h4>Organizations</h4>
                        <?= $this->Form->input('customerLoadFromTemplate', array(
                            'class' => 'input form-control loadFromTemplate',
                            'options' => array('loading...'),
                            'label' => false
                        )); ?>
                        
                        <a role='button'  data-context='Customer'
                           class='btn btn-info btn-block btnLoadFromTemplate'><i class='fa fa-gears'></i> Generate</a>
                    </div>
           <div class="col-md-3" id='contact'>
               <h4>Contacts</h4>
                        <?= $this->Form->input('contactLoadFromTemplate', array(
                            'class' => 'input form-control loadFromTemplate',
                            'options' => array('loading...'),
                            'label' => false
                        )); ?>
                        
                        <a role='button'  data-context='Contact'
                           class='btn btn-info btn-block btnLoadFromTemplate'><i class='fa fa-gears'></i> Generate</a>
                    </div>
           
           <div class="col-md-3" id='accreditations'>
                        <h4>Accreditations</h4>
                        <?= $this->Form->input('accreditationsLoadFromTemplate', array(
                            'class' => 'input form-control loadFromTemplate',
                            'options' => array('loading...'),
                            'label' => false
                        )); ?>
                        
                        <a role='button'  data-context='CustomerAccreditation'
                           class='btn btn-info btn-block btnLoadFromTemplate'><i class='fa fa-gears'></i> Generate</a>
                    </div>
           <div class="col-md-3" id='certifications'>
               <h4>Certifications</h4>
                        <?= $this->Form->input('certificationsLoadFromTemplate', array(
                            'class' => 'input form-control loadFromTemplate',
                            'options' => array('loading...'),
                            'label' => false
                        )); ?>
                        
                        <a role='button' data-context='ContactCertificaton' 
                           class='btn btn-info btn-block btnLoadFromTemplate'><i class='fa fa-gears'></i> Generate</a>
                    </div>
           
       </div> 
        
                    </div>
                </div>
                    </div>
                </div>
            </div>
</div>
    </div>
</div>
<div class='row'>
    <div class='col-sm-12'>
        <div class="box box-success">
            <div class="box-header ui-sortable-handle" style="cursor: move;">
              <i class="fa fa-file-o"></i>

              <h3 class="box-title">Results</h3>
              <!-- tools box -->
              <div class="pull-right box-tools">
               <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                
              </div>
              <!-- /. tools -->
            </div>
    <div class="box-body">
         <div class='row'>
                    
                    <div class='col-md-12' style='padding: 10px; overflow-x: scroll;'>
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

    </div>
</div>


    </div>
</div>

    
    
<?php $this->append('scripts'); ?>
<script>
    var table = null;

    

$('.resultCheckAll').click(function() {
    $('.report-select').prop("checked",true);

});   

$('.resultUncheckAll').click(function() {
    $('.report-select').prop("checked",false);
});

 

    
    var fileName = "";
    
   
    
    function getTemplateDetails(id, callback)
{
  $.ajax('/admin/reporting/ajaxLoadTemplate/' + id)
    .done(function(data){
       // Do a bunch
       // of computation
       // blah blah blah
       callback(data);
    }).fail(function(){
       callback(false);
    });
}
    
    $(".btnLoadFromTemplate").on('click', function(){
        var templateId = $(this).parent().find('.loadFromTemplate').val();
        
        getTemplateDetails(templateId, function(result) {
            if(result !== false)
            {
                console.log(result);
                var jsonDetails = JSON.parse(result);
                console.log(jsonDetails);
                
                var today = new Date();
                fileName = jsonDetails.context + "-" + jsonDetails.name + (today.getMonth()+1) + "-" + today.getDate() + "-" + today.getFullYear();
                var url = "/admin/reporting/";
                if(jsonDetails.context === "Customer")
                {
                    url += "runCustomerReport/Customer";
                }
                
                var submission = {'fields' : jsonDetails['fields'],
            'conditions' : jsonDetails['conditions_string']
        };
        var id=null;
       $.ajax({
                type : 'json',
                method : 'post',
               url : url,
               data : submission})
                   .done(function(data) {
                       console.log(data);
           id = data;
           $.ajax('/admin/reporting/ajaxLoadRecent/' + id).done(function(data)
               {
                   var json_data = JSON.parse(data);
        //           console.log(json_data.columns);
         //  console.log(json_data.data);
           var textrow = "<tr>";
           for(var i = 0; i< json_data.columns.length; i++)
               textrow += "<th></th>";
           textrow += "</tr>";
           $("#resultsTable > thead").html(textrow);
         //  table.destroy();
           var newTable = $("#resultsTable").DataTable({
               destroy: true,
               ajax: '/admin/reporting/ajaxLoadRecent/' + id + '/data',
               columns: json_data.columns,
               order: [[1, "desc"]],
               dom: 'Bfrtip',
        buttons: [
            {
                extend: 'csvHtml5',
                title: fileName,
                exportOptions: {
                    columns: ['.show-on-export' ]
                }
            },
                    {
                extend: 'excelHtml5',
                title: fileName,
                exportOptions: {
                   columns: ['.show-on-export' ]
                }
            },
                    {
                extend: 'pdfHtml5',
                title: fileName,
                exportOptions: {
                    columns: ['.show-on-export' ]
                }
            },
            'copy',
            'print'
        ]
           });
           
           //newTable.ajax.reload();
              });
           
               });
            }
        });
        
        return false;
        
        
               
            
       

   
    });
    
    $("#btnLoadFromTemplate").click(function() {
    $.ajax('/admin/reporting/ajaxLoadTemplate/' + $("#loadFromTemplate option:selected").val()).done(
            function(data){
               var jsonData = (JSON.parse(data));
               $(".fields > option").prop('selected', false);
               $("input").val("");
               $("input[type='checkbox']").prop('checked', false);
               $(".box-danger select > option").prop('selected', false);
                  populateFields(jsonData.fields);
                populateCriteria(jsonData.conditions);
    });
        
    });
    
    function populateCriteria(conditions)
    {
     console.log(conditions);   
     for (var key in conditions) {
  if (conditions.hasOwnProperty(key)) {
      if(key === "GroupGroups"  && conditions[key].data !== null)
      {
          $("#checkGroups").prop('checked', true);
           $("#" + key).val(conditions[key].data);     
         }
         
         if(key === "TypeTypes" && conditions[key].data !== null)
      {
          $("#checkTypes").prop('checked', true);
          
           $("#" + key).val(conditions[key].data);     
         }
      if(key === "SourceSources" && conditions[key].data !== null)
      {
          $("#checkSources").prop('checked', true);
          
           $("#" + key).val(conditions[key].data);     
         }
         if(key === "marketingEmailsDropdown" && conditions[key].data !== null)
      {
          $("#checkMarketingEmails").prop('checked', true);
          
           $("#" + key).val(conditions[key].data[0]);     
         }
         if(key === "checkContractExpiration" && conditions[key].data !== null)
      {
          $("#" + key).prop('checked', true);
          
           $("#contractExpiration").val(conditions[key].data[0]);     
         }
      
   // console.log(key + " -> " + $obj[key]);
  }
}
    }
    function inArray(needle, haystack) {
    var length = haystack.length;
    for(var i = 0; i < length; i++) {
        if(haystack[i] == needle) return true;
    }
    return false;
}
    function populateFields(fields)
    {
        $obj = {};
        fields.forEach(function(element)
        {
            $broken = element.split(".");
            if(!$obj.hasOwnProperty($broken[0]))
                $obj[$broken[0]] = [];
            $obj[$broken[0]].push($broken[1]);   
    });
   
    for (var key in $obj) {
  if ($obj.hasOwnProperty(key)) {
       $("select#Category" + key + " > option").each(function() {
              if(inArray($(this).text(),$obj[key]))
                  $(this).prop('selected', true);
    });
      
   // console.log(key + " -> " + $obj[key]);
  }
}
    }
</script>
<?php $this->end(); ?>
<?php $this->append('jquery-scripts'); ?>


$.ajax('/admin/reporting/ajaxGetTemplates/Customer').done(function(data) {
                    $("#customer .loadFromTemplate").html(data);
    });
    $.ajax('/admin/reporting/ajaxGetTemplates/Contact').done(function(data) {
                    $("#contact .loadFromTemplate").html(data);
    });
    $.ajax('/admin/reporting/ajaxGetTemplates/CustomerAccreditation').done(function(data) {
                    $("#accreditation .loadFromTemplate").html(data);
    });
    $.ajax('/admin/reporting/ajaxGetTemplates/ContactCertification').done(function(data) {
                    $("#certification .loadFromTemplate").html(data);
    });


<?php $this->end(); ?>