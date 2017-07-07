<?php $this->Html->script('/_/plugins/tinymce/tinymce.min.js', array('block' => 'scripts')); 

$contextOptions = array('CustomerAccreditation'=> 'Accreditation','ContactCertification' => 'Certification', 'Contact'=>'Contacts', 'Customer' => 'Customers', 'User' => 'Employees');
?>
<style>
    li > .completion-div {
        display: none;
        float: right;
        margin-right: 10px;
        color: green;
    }
    li.done > .completion-div {
        display: inline;
    }
     #FullAddForm .quick-todo
    {
        border-radius: 8px;
        background: #deefff;
        color: #242424;
        font-size: 11px;
    }
    </style>
    
    <style>
              .template-list {
                  background: #fff;
                  color: #424242;
                  //border: 2px solid #484848;
                  border-radius: 5px;
                  padding: 0;
                  margin: 0;
              }
              .template-list > li {
                  background: #efefef;
                  color: #424242;
                  border-bottom: 1px solid #858585;
                  padding: 8px 16px;
                  list-style-type: none;
                  margin-bottom: 0;
                 
              }
              .template-list > li:hover {
                  background: #dedede;
                  cursor: pointer;
              }
              .template-list > li:nth-child(2n) {
                  background: #fafafa;
              }
              .template-list > li:nth-child(2n):hover {
                  background: #dedede;
                  cursor: pointer;
              }
              
              .template-list > li  {
                  color: #424242;
              }
              #possibleFields {
                  font-style: italic;
                  font-size: 10px;
                  text-align: center;
                  width: 100%;
                  padding: 10px 20px;
                  margin: 10px 0;
                  border: 1px solid #dadada;
                  border-radius: 4px;
                  background: #eaeaea;
                  line-height: 20px;
                  margin-bottom: 0;
                  border-bottom: none;
              }
              .insertMe {
                  padding: 1px 3px;
                  border: 1px solid #fdfdfd;
                  background: #fefefa;
                  border-radius: 5px;
                  margin: 2px;
                  cursor: pointer;
                  
              }
              #showHideFields {
                  margin-bottom: 3px;
                  
              }
              #showHideButton {
                  font-size: 10px;
                  font-style: italic;
              }
              
              .insertMe:hover {
                  background: #dedefa;
              }
              .delete-object {
                  float: right;
                  font-size: 80%;
              }
              #div-choices {
                  max-height: 550px;
                  
                  background: none;
                  color: #424242;
                  border: 1px solid #8a8a8a;
                  border-radius: 5px;
              }
          </style>       
    
   
<div class="row">
    <div class='col-md-3'>
        <h4>
            Current Templates
        </h4>
        <div id='div-choices'>
            
        </div>
    </div>
    
    <div class="col-md-9 tab-content">
        
<div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-list-ul"></i> <span id='box-title-text'>Add New Template</span>
            </h3>

        </div>
        
        <div class="box-body">
            <div class="alert alert-danger" id='saveError' style='display: none;'>
  <strong>Error!</strong> An error occurred while saving. Be sure to include all necessary information.
</div>
            <div class="alert alert-warning" id='renameError' style='display: none;'>
  <strong>Warning!</strong> An error occurred while saving. You must use a unique name within the context chosen.
</div>
            <form id='TemplateAddForm' onsubmit="return false;" action='/admin/emailTemplates/ajaxSave' method='post'>
                <div class='row'>
                     
                          <div class='col-sm-8'>
                      <?= $this->Form->input('EmailTemplate.name', array(
                          'id' => 'EmailTemplateName',
                          'label' => 'Template Name', 'placeholder' => 'Name your template...'
                          ,
                          'class' => 'input form-control quick-todo')); ?>
                  </div>
                      
                  <div class='col-sm-4'>
                      <label for='EmailTemplateContext'>
                          Context <span style='color: blue; cursor: pointer; font-size: 80%;' title='Changing context after adding tags will cause tags to show incorrectly in some cases.'>(?)</span>
                      </label>
                      <?= $this->Form->input('EmailTemplate.context', array(
                          'id' => 'EmailTemplateContext',
                          'label' => false,
                          
                          'options' => $contextOptions,
                          'class' => 'input form-control quick-todo')); ?>
                  </div>
                      
                  </div> 
              <div class='row' style='margin-top: 5px;'>
                  <div class='col-sm-12'>
                      <div id ="showHideFields">
                      <div id='possibleFields' style='display: none;'></div>
                      <a role ="button'" id="showHideButton" class='btn btn-default btn-block'>
                          <i class='fa fa-angle-double-down'></i>
                               Show Available Fields
                      </a>
                     
                      </div>
                      
              <textarea id='EmailTemplateContentEditor' rows='15' class='form-control quick-todo tinymce'></textarea>
                  </div>
              </div>
                  
                
                <?= $this->Form->hidden('EmailTemplate.id'); ?>
                <?= $this->Form->hidden('EmailTemplate.content', array(
                    'id'=> 'hiddenContent'
                )); ?>
                
                <div class='row'>
                    <div class='col-sm-6 col-sm-offset-3'>
                        <label></label>
                        <a id='btnFullAdd' data-context='add' role='button' class='btn btn-block btn-info'><i class='fa  fa-check-square-o'></i> <span class='btn-text'>Add</span></a>
                      </div>
                    <div class='col-sm-2 col-sm-offset-1'>
                        <label></label>
                        <a style='margin-top: 20px;' id='btnClear' onclick='resetForm();' role='button' class='btn btn-sm btn-default'><i class='fa  fa-remove'></i> <span class='btn-text'>Clear</span></a>
                      </div>
                </div>
                

       
          </form>
               
          
          
          
            
        </div>
        
        
 </div>
            
            
        
        

        
 
        
    </div>
</div>
  <?php $this->append('jquery-scripts'); ?>
	$.ajax('/admin/emailTemplates/ajaxLoad').done(function(data) {
     $("#div-choices").html(data);
});
	$.ajax('/admin/emailTemplates/ajaxAvailableFields/' + $("#EmailTemplateContext").val())
                 .done(function(data) {
             $("#possibleFields").html(data);
         });
         
<?php $this->end(); ?>  
<?php $this->append('scripts'); ?>
<script>
   
    tinymce.init(tinymceEmailSettings);
     
     function insertText(clicked){
         tinymce.activeEditor.execCommand('mceInsertContent', false, ' <span class="field">' + $(clicked).text() + '</span> ');
     }
     $("#EmailTemplateContext").on('change', function() {
         $.ajax('/admin/emailTemplates/ajaxAvailableFields/' + $("#EmailTemplateContext").val())
                 .done(function(data) {
             $("#possibleFields").html(data);
         });
     });
     
     $("#showHideButton").click(function() {
         if($(this).hasClass('visible'))
         {
         $(this).removeClass('visible');  
          $(this).html("<i class='fa fa-angle-double-down'></i> Show Available Fields");
       $("#possibleFields").slideUp('fast');
        }
        else
        {
            $(this).addClass('visible');
       $(this).html("<i class='fa fa-angle-double-up'></i> Hide Available Fields");
       $("#possibleFields").slideDown('fast');
   }
    });
   
   
    $("#btnFullAdd").click(function(e) {
        
        $("#hiddenContent").val(tinymce.get("EmailTemplateContentEditor").getContent());
        $.post('/admin/emailTemplates/ajaxSave',
    $("#TemplateAddForm").serialize()).done(function(data) {
        if(data === 'saveError') {
            $("#renameError").hide();
            $("#saveError").fadeIn('fast');
            
        }
        else if(data === 'renameError')
        {
            $("#saveError").hide();
            $("#renameError").fadeIn('fast');
        }
        else {
            $("#div-choices").html(data);
            resetForm();
            
        }
     
});
// Manipulate submit button for add and clear form


});

    
    function resetForm() {
        $("#TemplateAddForm input").val("");
$("#TemplateAddForm textarea").val("");
tinymce.get("EmailTemplateContentEditor").setContent("");
$("#EmailTemplateContext").val("Contact");
$("#EmailTemplateId").val("");
$("#btnFullAdd").addClass('btn-info').removeClass('btn-warning');
        $("#btnFullAdd").data('context', 'add');
$("#btnFullAdd .btn-text").text('Add');
$("#btnFullAdd > i").removeClass('fa-edit').addClass('fa-check-square-o');
$("#box-title-text").text("Add New Template");
$("#saveError").hide();
$("#renameError").hide();
    }

function loadToEdit(id) {
    
    //populate form with ajax return
    $.ajax({dataType: "json", 
        url: "/admin/emailTemplates/ajaxLoad/"+id,
complete: function(data){
$response = JSON.parse(data.responseText);

tinymce.get("EmailTemplateContentEditor").setContent($response.EmailTemplate.content);
$("#EmailTemplateContext").val($response.EmailTemplate.context);
$("#EmailTemplateName").val($response.EmailTemplate.name);


$("#EmailTemplateId").val(id);

// Manipulate submit button for edit
$("#btnFullAdd").removeClass('btn-info').addClass('btn-warning');
$("#btnFullAdd").data('context', 'edit');
$("#btnFullAdd .btn-text").text('Edit');
$("#btnFullAdd > i").removeClass('fa-check-square-o').addClass('fa-edit');
$("#box-title-text").text("Edit Template '" + $response.EmailTemplate.name + "'");
}
});
}
</script>
<?php $this->end(); ?>

 <div style='clear: both;'></div>
 
 <?php echo $this->element('modals/delete', array('title' => 'Delete Template', 'text' => 'delete the email template <strong>{name}</strong>', 'action' => '/admin/emailTemplates/delete/{id}')); ?>
