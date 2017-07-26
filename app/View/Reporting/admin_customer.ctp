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
                <h3 class="box-title"><em><strong>Organization:</strong> <small>Reporting Options</small></em></h3>
                
                    
                
             
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
           
        <?= $this->element('reporting/customer/criteria'); ?>
    </div>
    
</div>
                <div class='row'>
    <div class='col-sm-12'>
           
        <?= $this->element('reporting/accreditation/accreditations'); ?>
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


<?php $this->append('scripts'); ?>
<script>
    $(".previewEmail").click(function() {
        var id = $("#EmailTemplateId").val();
       
         $.fancybox.open({
        type: 'iframe',
        autoDimensions: true,
        autoScale: true,
        href: '/admin/emailTemplates/preview/' + id
    });
    });
   $(".generateEmail").click(function() {
       $('.emptyError').fadeOut();
       $('.sendError').fadeOut();
       $sendDataArray = [];
       $(".report-select").each(function() {
           if($(this).is(':checked'))
            $sendDataArray.push($(this).data('id'));
       });
       $sendData = {
           idList: $sendDataArray
       };
       console.log($sendData);
       $.ajax({
           type: 'post',
           url: '/admin/reporting/ajaxSendEmails/' + $("#EmailTemplateId").val() + '/customer',
           data: $sendData
           
       }).done(function(data) {
           
           if(data === 'e1')
           {
               $('.emptyError').fadeIn();
           }
           else if(data === 'e2')
           {
               $('.emptyError').fadeIn();
           }
           else
           {
               $("#num-emails-sent").html("<strong>" + data + "</strong>");
               $("#confirm-send").modal('show');
           }
       });
   });

    var table = null;

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

$('.resultCheckAll').click(function() {
    $('.report-select').prop("checked",true);

});   

$('.resultUncheckAll').click(function() {
    $('.report-select').prop("checked",false);
});

    $('.deepcategoryCheckAll').click(function() {
    $(this).parent().parent().find('select > option').prop("selected",true);

});   

$('.deepcategoryUncheckAll').click(function() {
    $(this).parent().parent().find('select > option').prop("selected",false);
});

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
    function getConditionsArray() {
        
        $conditions = {
            'andOr' : {
                type: 'ignore',
                accreditation: $("#accreditationAndOr").val(),
                accreditationType: 'OR',
                criteria: $("#searchAndOr").val(),
                overall: $("#overallAndOr").val()
            },
            'checkGroups' : {
                type: 'list',
                conditionField: 'CustomerGroup.group_id',
                conditionType: 'array',
                conditionSection: 'criteria',
                extra: null,
                data: null
            },
            'checkTypes': {
                type: 'list',
                conditionField: 'Customer.organization_type',
                conditionType: 'likeArray',
                conditionSection: 'criteria',
                extra: null,
                data: null   
            },
            'checkSources': {
                type: 'list',
                conditionField: 'Customer.source',
                conditionType: 'array',
                conditionSection: 'criteria',
                extra: null,
                data: null
            },
            'checkMarketingEmails' : {
                type: 'checkbox',
                conditionField: 'Customer.email_opt_out',
                conditionType: 'equals',
                conditionSection: 'criteria',
                extra: null,
                data: null
            },
            'checkContractExpiration' : {
                type: 'string',
                conditionField: 'Customer.contract_expiration',
                conditionType: 'lessThanDate',
                conditionSection: 'criteria',
                extra: null,
                data: null
            },
            'checkAccreditationExpired' : {
                type: 'string',
                conditionField: 'CustomerAccreditation.expiration_date',
                conditionType: 'lessThanDate',
                conditionSection: 'accreditation',
                extra: null,
                data: null
            },
            'checkAccreditationExpiring' : {
                type: 'string',
                conditionField: 'CustomerAccreditation.expiration_date',
                conditionType: 'lessThanDate',
                conditionSection: 'accreditation',
                extra: null,
                data: null
            },
            'checkAccreditationVisit2' : {
                type: 'string',
                conditionField: 'CustomerAccreditation.visit_2_18_mo',
                conditionType: 'lessThanDate',
                conditionSection: 'accreditation',
                extra: 'CustomerAccreditation.visit_2_start_date is null',
                data: null
            },
            'checkAccreditationVisit3' : {
                type: 'string',
                conditionField: 'CustomerAccreditation.visit_3_36_mo',
                conditionType: 'lessThanDate',
                conditionSection: 'accreditation',
                extra: 'CustomerAccreditation.visit_3_required = "1" AND CustomerAccreditation.visit_3_start_date is null',
                data: null
            },
            'checkAccreditation9Mo' : {
                type: 'string',
                conditionField: 'CustomerAccreditation.9_mo_due_date',
                conditionType: 'lessThanDate',
                conditionSection: 'accreditation',
                extra: 'CustomerAccreditation.9_mo_followup_required = "1" AND CustomerAccreditation.9_mo_actual_date is null',
                data: null
            },
            'checkAccreditation18Mo' : {
                type: 'string',
                conditionField: 'CustomerAccreditation.18_mo_due_date',
                conditionType: 'lessThanDate',
                conditionSection: 'accreditation',
                extra: 'CustomerAccreditation.18_mo_onsite_required = "1" AND CustomerAccreditation.18_mo_actual_date is null',
                data: null
            },
            'checkAccreditationNotes' : {
                type: 'string',
                conditionField: 'CustomerAccreditation.notes',
                conditionType: 'like',
                conditionSection: 'accreditation',
                extra: null,
                data: null
            },
            'checkAccreditationTypes' : {
                type: 'list',
                conditionField: 'CustomerAccreditation.accreditation_id',
                conditionType: 'array',
                conditionSection: 'accreditationType',
                extra: null,
                data: null
            }
            
        };
        
        for(var key in $conditions)
        {
            if(!$conditions.hasOwnProperty(key)) continue;
            
            var obj = $conditions[key];
            if($("#" + key).is(':checked'))
            {
                // make sure the child data has selections
                if(obj.type === 'string' && $("#" + key).parent().parent().find('.data').val() === "")
                    continue;
                if(obj.type === 'list' && $("#" + key).parent().parent().find('.data').children("option:checked").length === 0)
                    continue;
                
                if(obj.type === 'string')
                {
                    $conditions[key].data = $("#" + key).parent().parent().find('.data').val();
                }
                if(obj.type === 'list')
                {
                    $dataArray = [];
            
            $("#" + key).parent().parent().find('.data').children("option:checked").each(function() {
                $dataArray.push($(this).val());
            });
           $conditions[key].data = $dataArray;
                }
                if(obj.type === 'checkbox')
        {
            $dataArray = [];
             $dataArray.push($("#" + key).parent().parent().find('.data').val()); 
             $conditions[key].data = $dataArray;
        }
                
                
            }
            
        }
        
       
        
        return $conditions;
    }
    function getConditions(conditionArray)
    {
        var conditions = "";
        var conditionsObject = {
            criteria: "",
            accreditation: "",
            accreditationType: ""
        };
        var current = "";
        for(var key in conditionArray)
        {
            
            current = "";
            if(!conditionArray.hasOwnProperty(key)) continue;
            
            var obj = conditionArray[key];
            
            if(obj.data !== null)
            {
                
                if(obj.type === 'ignore')
                    continue;
                

                if(obj.conditionType === 'likeArray')
                {
                    current += "(";
                    $first = true;
                    for (var i=0; i<obj.data.length; i++) {
                if(!$first)
                    current += " OR ";
                $first = false;
                current += "(" + obj.conditionField + " LIKE '%" + obj.data[i] + "%')";
            }
            
            current += ")";
                }
                
                if(obj.conditionType === 'equals')
                {
                current += "(" + obj.conditionField + " = '" + obj.data + "')";
                }
                
                if(obj.conditionType === 'like')
                {
                   
                current += "(" + obj.conditionField + " LIKE '%" + obj.data + "%')";
                 
                }
                
                if(obj.conditionType === 'lessThanDate')
                {
                current += "(" + obj.conditionField + " < '"; 
                 var date = new Date(obj['data']);
                 current += date.toISOString().slice(0, 19).replace('T', ' ');
                                
                current += "')";
                
                }
                
                if(obj.conditionType === 'array')
                {
                    
                    var groups = [];
            for (var i = 0; i < obj['data'].length; i++)
                groups.push("'" + obj['data'][i] + "'");
            
           current = "(" + obj.conditionField + " IN (" + groups.toString() + "))";
                }
                
        if(obj.extra !== null)
                    {
                        conditionsObject[obj['conditionSection']] += "(" + obj.extra + " AND (" + current + ")) " + conditionArray.andOr[obj['conditionSection']] + " ";
                    }
                    else
                    {
                        conditionsObject[obj['conditionSection']] += current + " " + conditionArray.andOr[obj['conditionSection']] + " ";
                }
                
              
            }
            
        }
        
        var first = true;
        for(var key in conditionsObject)
        {
            if(!conditionsObject.hasOwnProperty(key)) continue;
        var compare = conditionsObject[key].substring(conditionsObject[key].length-4).trim();
        var addOn = conditionsObject[key];

        if(compare === 'AND' || compare === 'OR' || compare === ') OR')
        {
            addOn = conditionsObject[key].substring(0,conditionsObject[key].length-4).trim();
            conditionsObject[key] = addOn;

            }
        
        if(addOn !== "")
        {
            if(!first)
            {
                if(key !== 'accreditationType')
                    conditions += ") " + conditionArray.andOr.overall + " (";
               
                
            }
            else
            {
            first = false;
            if(key !== 'accreditationType')
                conditions += "(";
            }
            
            if(key !== 'accreditationType')
                conditions += addOn;
        

            }
  
        }
        
        var oldConditions = conditions;
        if(conditions === "")
            conditions = conditionsObject['accreditationType'];
        else
        {
            
            var compare = oldConditions.substring(oldConditions.length-3).trim();
        
        if(compare === 'AND' || compare === 'OR')
        {
            oldConditions = oldConditions.substring(0,oldConditions.length-3).trim();
            }
            
            if(conditionsObject['accreditationType'] !== "")
                conditions = "(" + oldConditions + ")) AND " + conditionsObject['accreditationType'];
            else
                conditions = oldConditions + ")";
            
        }
        
        
        
        return conditions;
    }
    
    
    var defaultFileName = "<?= $defaultExportTitle; ?>";
    var fileName = "";
    
    $("#btnSaveTemplate").on('click', function() {
        var condArray = getConditionsArray();
        var savedata = {
            name : $("#templateName").val(),
            conditions: JSON.stringify(condArray),
            conditions_string: getConditions(condArray),
            fields: JSON.stringify(getFields()),
            context: 'Customer'
        };
        
        $.post({
            url: "/admin/reporting/ajaxSaveTemplate",
            data: savedata
        }).done(function(data)
        {
            if(data === "success")
            {
                $("#saveTemplateResult").html("<strong>Success!</strong> You have successfully saved this template.")
                        .removeClass('alert-danger')
                        .addClass('alert-success')
                        .fadeIn('fast');
                $.ajax('/admin/reporting/ajaxGetTemplates/Customer').done(function(data) {
                    $("#loadFromTemplate").html(data);
    });
                
            }
            else
            {
                 $("#saveTemplateResult").html("<strong>Error!</strong> " + data)
                        .addClass('alert-danger')
                        .removeClass('alert-success')
                        .fadeIn('fast');
                }
        });
        
    });
    $("#createReport").on('click', function(){
    var condArray = getConditionsArray();
        var fields = getFields();
        var conditions = getConditions(condArray);
        var today = new Date();
        
        if($("#templateName").val() !== "" && fileName === "")
            fileName = $("#templateName").val() + (today.getMonth()+1) + "-" + today.getDate() + "-" + today.getFullYear();
        else
            fileName = defaultFileName;
        var submission = {'fields' : fields,
            'conditions' : conditions
        };
        var id=null;
       $.ajax({
                type : 'json',
                method : 'post',
               url : '/admin/reporting/runCustomerReport/Customer',
               data : submission})
                   .done(function(data) {
                       console.log(data);
           id = data;
           $.ajax('/admin/reporting/ajaxLoadRecent/' + data).done(function(data)
               {
                   var json_data = JSON.parse(data);
        //           console.log(json_data.columns);
         //  console.log(json_data.data);
           var textrow = "<tr>";
           for(var i = 0; i< json_data.columns.length; i++)
               textrow += "<th></th>";
           textrow += "</tr>";
           $("#resultsTable > thead").html(textrow);
           
           var buttonCommon = {
        exportOptions: {
            columns: ['.show-on-export' ],
            format: {
                body: function ( data, row, column, node ) {
                    // Strip $ from salary column to make it numeric
                    return data.replace( /<br\s*\/?>/ig, "\n" );
                }
            }
        }
    };
    
         //  table.destroy();
           var newTable = $("#resultsTable").DataTable({
               destroy: true,
               ajax: '/admin/reporting/ajaxLoadRecent/' + id + '/data',
               columns: json_data.columns,
               order: [[1, "desc"]],
               dom: 'Bfrtip',
        buttons: [
            $.extend( true, {}, buttonCommon, {
                extend: 'csvHtml5',
                title: fileName
            }),
            $.extend( true, {}, buttonCommon, {
                extend: 'excelHtml5',
                title: fileName
            }),
            $.extend( true, {}, buttonCommon, {
                extend: 'pdfHtml5',
                title: fileName
            }),
            'copy',
            $.extend( true, {}, buttonCommon, {
                extend: 'print'
            })
        ]
           });
           
           //newTable.ajax.reload();
           
           
               });
               
            
       
   });
   
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
     //console.log(conditions);   
     for (var key in conditions) {
  if (conditions.hasOwnProperty(key)) {
      
      if(key === "andOr")
      {
          $("#overallAndOr").val(conditions[key]['overall']);
           $("#searchAndOr").val(conditions[key]['criteria']);
            $("#accreditationAndOr").val(conditions[key]['accreditation']);
           
         }
         else
         {
             if(conditions[key].data !== null)
             {
                 obj = conditions[key];
                 $("#" + key).prop('checked', true);
                 
                 if(obj.type === 'list')
                 {
                     
                     $("#" + key).parent().parent().find('.data').val(obj.data);
                        }
                        
                        if(obj.type === 'string') {
                            $("#" + key).parent().parent().find('.data').val(obj.data);   
                        }
                        
                        if(obj.type === 'checkbox') {
                            $("#" + key).parent().parent().find('.data').val(obj.data[0]);   
                        }
                    }
             
             
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
//$('select.fields option').attr("selected","selected");

$.ajax('/admin/reporting/ajaxGetTemplates/Customer').done(function(data) {
                    $("#loadFromTemplate").html(data);
    });
    
   // table = $("#resultsTable").DataTable();

<?php $this->end(); ?>