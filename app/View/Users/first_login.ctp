<h3>Welcome to your User Creation Portal!</h3>
<style>
    .submit {
        display: none;
    }
    </style>
<div class="users form">
   <?php echo $this->Form->create('User', array('action' => 'firstLogin')); ?>
   <fieldset>
      <legend><?php echo __('Set Your New Password'); ?></legend>
      <?php
      //debug($users);
      echo $this->Form->input('password', array('label' => 'Password'));
      echo $this->Form->input('password_retype', array('label' => 'Confirm password', 'type' => 'password'));
      ?>
      <p class='text-red' id='validation-error'>*** Passwords do not match ***</p>
      <?php
      echo $this->Form->hidden('id', array('value' => $id));
      echo $this->Form->hidden('hash', array('value' => $hash));
      ?>
   </fieldset>
   <?php echo $this->Form->end(__('Submit')); ?>
</div>

<?php $this->append('scripts'); ?>
    <script>
$("#UserFirstLoginForm").submit(function(e) {
 if($("#UserPasswordRetype").val() !== $("#UserPassword").val())
 {
 e.preventDefault();
 }
 
});
$("#UserPasswordRetype").on('keyup', function(){

     if($(this).val() == $("#UserPassword").val()){
               $("#validation-error").fadeOut('fast');
               $(".submit").fadeIn('fast');
               //more processing here
     }
     else
     {
         $("#validation-error").show();
         $(".submit").hide();
     }
     
});
</script>
<?php $this->end(); ?>