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
       console.log("<?= $endUrl; ?>");
       
       $.ajax({
           type: 'post',
           url: '/admin/reporting/ajaxSendEmails/' + $("#EmailTemplateId").val() + '/<?= $endUrl; ?>',
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
            $field = $(this).val();
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
    <?php echo $this->fetch('conditionsArray'); ?>
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
                if(obj.type === 'double-string')
                {
                    $conditions[key].data = $("#" + key).parent().parent().find('.data.start').val();
                      $conditions[key].data2 = $("#" + key).parent().parent().find('.data.end').val();
// console.log($("#" + key).parent().parent().find('.data.start').val());
// console.log($("#" + key).parent().parent().find('.data.end').val());
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
            extra: "",
            specialType: "",
            overall: ""
        };
        var current = "";
        for(var key in conditionArray)
        {
            
            current = "";
            if(!conditionArray.hasOwnProperty(key)) continue;
            
            var obj = conditionArray[key];
           // console.log(obj);
            if(obj.data !== null)
            {
                
                if(obj.type === 'ignore')
                    continue;

               // console.log(obj);
                if(obj.conditionType === 'betweenDate')
                {
                
                current += "(" + obj.conditionField + " BETWEEN '"; 
                 var date = new Date(obj['data']);
                 current += date.toISOString().slice(0, 19).replace('T', ' ');
                 date = new Date(obj['data2']);
                 current += "' AND '" + date.toISOString().slice(0, 19).replace('T', ' ');
                current += "')";
                }
             
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
                   
                current += "(" + obj.conditionField + " LIKE '%" + obj.data.replace(/,/gi, '%') + "%')";
                 
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
        console.log(conditionsObject);
        
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
                if(key !== 'specialType')
                    conditions += ") " + "AND" + " (";
               
                
            }
            else
            {
            first = false;
            if(key !== 'specialType')
                conditions += "(";
            }
            
            if(key !== 'specialType')
                conditions += addOn;
        

            }
  
        }
        
        var oldConditions = conditions;
        if(conditions === "")
            conditions = conditionsObject['specialType'];
        else
        {
            
            var compare = oldConditions.substring(oldConditions.length-3).trim();
        
        if(compare === 'AND' || compare === 'OR')
        {
            oldConditions = oldConditions.substring(0,oldConditions.length-3).trim();
            }
            
            if(conditionsObject['specialType'] !== "" && typeof conditionsObject['specialType'] !== undefined)
                conditions = "(" + oldConditions + ")) AND " + conditionsObject['specialType'];
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
            locationLimit: JSON.stringify(getIsLocationLimited()),
            fields: JSON.stringify(getFields()),
            context: '<?= $templateContext; ?>'
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
                $.ajax('/admin/reporting/ajaxGetTemplates/<?= $templateContext; ?>').done(function(data) {
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
    
    function getIsLocationLimited() 
    {
        if($("#limitByState").is(':checked'))
            return {"byState": $("#states").val()};
        if($("#limitByZip").is(':checked'))
        {
            return {"byZip" : $("#zip").val(),
                    "range" : $("#zipRange").val()};
        }
        return false;
    }
    
    $("#createReport").on('click', function(){
    var condArray = getConditionsArray();
        var fields = getFields();
        var conditions = getConditions(condArray);
        var today = new Date();
        var locationLimit = getIsLocationLimited();
        
        if($("#templateName").val() !== "" && fileName === "")
            fileName = $("#templateName").val() + (today.getMonth()+1) + "-" + today.getDate() + "-" + today.getFullYear();
        else
            fileName = defaultFileName;
        var submission = {'fields' : fields,
            'conditions' : conditions,
            'locationLimit' : locationLimit
        };
        var id=null;
       $.ajax({
                type : 'json',
                method : 'post',
               url : '/admin/reporting/<?= $reportType; ?>/<?= $templateContext; ?>',
               data : submission})
                   .done(function(data) {
                       //console.log(data);
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
                populateLocation(jsonData.locationLimit);
    });
        
    });
    
    function populateLocation(locationLimit)
    {
        locationLimit = (JSON.parse(locationLimit));
        if(locationLimit === false || locationLimit === null)
            return false;

        if(typeof locationLimit.byState !== "undefined")
        {
            $("#limitByState").attr('checked', 'checked');
            $("#states").val(locationLimit.byState);
            return true;
        }
        
        if(typeof locationLimit.byZip !== "undefined")
        {
            $("#limitByZip").attr("checked", "checked");
            $('#zip').val(locationLimit.byZip);
            $('#zipRange').val(locationLimit.range);
            return true;
        }
        
        return false;
    }
    
    function populateCriteria(conditions)
    {
     //console.log(conditions);   
     for (var key in conditions) {
  if (conditions.hasOwnProperty(key)) {
      
      if(key === "andOr")
      {
           $("#overallAndOr").val(conditions[key]['overall']);
           $("#searchAndOr").val(conditions[key]['criteria']);
            $("#extraAndOr").val(conditions[key]['criteria']);
            $("#specialTypeAndOr").val(conditions[key]['specialType']);
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
              if(inArray($(this).val(),$obj[key]))
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

$.ajax('/admin/reporting/ajaxGetTemplates/<?= $templateContext; ?>').done(function(data) {
                    $("#loadFromTemplate").html(data);
    });
    
   // table = $("#resultsTable").DataTable();

<?php $this->end(); ?>