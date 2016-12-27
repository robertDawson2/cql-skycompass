<div class="box box-info collapsed-box">
            <div class="box-header ui-sortable-handle" style="cursor: move;">
              <i class="fa fa-envelope"></i>

              <h3 class="box-title">Quick Email</h3>
              <!-- tools box -->
              <div class="pull-right box-tools">
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                <button type="button" class="btn btn-box-tool" data-widget="remove" data-toggle="tooltip" title="" data-original-title="Remove">
                  <i class="fa fa-times"></i></button>
              </div>
              <!-- /. tools -->
            </div>
    <form id="quickEmail" action="/admin/users/quickEmail" method="post">
            <div class="box-body">
              
                  <div class="form-group">
                      <span style="font-weight: bold; padding-right: 25px;">From: </span>
                      <input name="from" value='company' checked type="radio" /> <span style="padding-right: 25px;">CQL &nbsp;</span>
                      <input name="from" value='me' type="radio" /> Me &nbsp;
                </div>
                <div class="form-group">
                  <input class="form-control" name="to" <?php if(isset($customer) && !empty($customer['Customer']['email'])) echo "value='" . $customer['Customer']['email'] . "'"; ?> placeholder="Email to:" type="email">
                </div>
                <div class="form-group">
                  <input class="form-control" name="subject" placeholder="Subject" type="text">
                </div>
                <div>
                  <textarea class="textarea" name="message" style="width: 100%; height: 125px; font-size: 14px;
                            line-height: 18px; border: 1px solid rgb(221, 221, 221);
                            padding: 10px; " placeholder="Message"></textarea>
                    
                </div>
              
            </div>
            <div class="box-footer clearfix">
              <button type="button" class="pull-right btn btn-default" id="sendEmail">Send
                <i class="fa fa-arrow-circle-right"></i></button>
            </div>
    </form>
          </div>

<?php $this->append('scripts'); ?>
<script>
$('#sendEmail').click(function() {
    $("#quickEmail").submit();
});</script>

<?php $this->end(); ?>