
<div class="box box-warning collapsed-box">
            <div class="box-header ui-sortable-handle" style="cursor: move;">
              <i class="fa fa-edit"></i>

              <h3 class="box-title">Data Options</h3>
              <!-- tools box -->
              <div class="pull-right box-tools">
               <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                
              </div>
              <!-- /. tools -->
            </div>
    <div class="box-body">
       
        <h4>Included Fields</h4>
        <div class='row'>
            <div class='col-md-12'>

        <div class='row'>
            <?php foreach($fields as $category => $list): ?>
            <div class='col-md-3'>
                <h5><?= $category; ?></h5>
                <a role='button' class='categoryCheckAll'>Check</a> &nbsp;/  <a role='button' class='categoryUncheckAll'>Uncheck</a>
                <select id ="Category<?= $category; ?>" multiple class="input form-control fields" style='height: 200px;'
                        data-category='<?= $category; ?>'>
                    <?php foreach($list as $field):
                        ?>
                    <option value='<?= $field['id']; ?>'><?= $field['pretty_name']; ?></option>
                    <?php
                    endforeach;
                     ?>
                </select>
                        
              
                </div> 
            <?php endforeach; ?>
                
        </div>
                
                
                 </div>
        </div>
        <hr>
<!--    <div class="row">
            <div class='col-md-10 col-md-offset-1'>
                <h4>Export Type</h4>
                
                <div class='row' style='margin-bottom: 10px; padding: 10px; border-bottom: 1px solid #fdfdfd; '>
                    <div class='col-md-2'>
                        <a role='button' class='btn btn-success btn-block export-type' data-val='screen'><i class='fa fa-eye'></i> On-Screen</a>
                    </div>
                     <div class='col-md-2'>
                        <a role='button' class='btn btn-default btn-block export-type' data-val='excel'><i class='fa fa-file-excel-o'></i> Excel</a>
                    </div>
                    <div class='col-md-2'>
                        <a role='button' class='btn btn-default btn-block export-type' data-val='pdf'><i class='fa fa-file-pdf-o'></i> PDF</a>
                    </div>
                    <div class='col-md-2'>
                        <a role='button' class='btn btn-default btn-block export-type' data-val='word'><i class='fa fa-file-word-o'></i> Word</a>
                    </div>
                    <div class='col-md-2'>
                        <a role='button' class='btn btn-default btn-block export-type' data-val='txt'><i class='fa fa-file-text'></i> TXT</a>
                    </div>
                    <div class='col-md-2'>
                        <a role='button' class='btn btn-default btn-block export-type' data-val='xml'><i class='fa fa-file-code-o'></i> XML</a>
                    </div>
                </div>
                
            </div>
        </div>
            
         <hr>-->
    <div class="row">
            <div class='col-md-8 col-md-offset-2'>
                <h4>Save As Template...</h4>
                <div style='display: none;' id='saveTemplateResult' class="alert alert-success" role="alert">
  <strong>Well done!</strong> You successfully read this important alert message.
</div>
                <div class='row' style='margin-bottom: 10px; padding: 10px; border-bottom: 1px solid #fdfdfd; '>
                    <div class='col-md-8'>
                        <input type='textbox' class='input form-control' id='templateName' placeholder='Template Name...' />
                    </div>
                     <div class='col-md-4'>
                        <a role='button' class='btn btn-info btn-block' id='btnSaveTemplate'><i class='fa fa-save'></i> Save</a>
                    </div>
                    
                </div>
                
            </div>
        </div>

    </div>
</div>

