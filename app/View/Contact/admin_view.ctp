<style>
    .fieldname {
       
        font-size: 16px;
        font-weight: bold;
        text-transform: capitalize;
        color: #6a6a6a;
    }
    .box {
        float: left;
        position: absolute;
    }
    .ui-menu {z-index: 99999;}
    .active > .box {
        z-index: 9999;
    
    }
    .remove-row {
        float: right;
        color: red;
        text-decoration: none;
        font-size: 12px;
        cursor: pointer;
    }
    .remove-row:hover {
        color: darkred;
    }
    .phone-row, .customer-row, .address-row, .cert-row, .group-row {
        width: 400px;
        background-color: #fafafa;
        border-bottom: 1px solid white;
        padding: 5px 15px;
       
    }
    .phone-row:nth-child(2n), .customer-row:nth-child(2n), .address-row:nth-child(2n), .cert-row:nth-child(2n), .group-row:nth-child(2n)
    {
        background-color: #efefef;
    }
    .add-remove-list {
        list-style-type: none;
        
    }
    
    </style>
<div class="row">
    <div class='col-md-3'>
        <ul class="nav nav-pills nav-stacked">
  <li class="active"><a data-toggle="pill" href="#general">General Information</a></li>
  <li><a data-toggle="pill" href="#certifications">Certifications/Docs</a></li>
  <li><a data-toggle="pill" href="#communications">Communications</a></li>
  
</ul>
       
         
    </div>
    
    <div class="col-md-9">
        <div id="general" class="tab-pane fade in active">
<div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-question-circle"></i> General Information
            </h3>
            <hr>
             <!-- Name/birthday -->
            <div class='row'>
                <div class='col-md-6'>
                    <div class='col-md-4 fieldname'>Name:</div><div class='col-md-8'><?= $contact['Contact']['first_name']; ?> <?= empty($contact['Contact']['middle_name']) ? "" : $contact['Contact']['middle_name']. " "; ?><?= $contact['Contact']['last_name']; ?></div>
                </div>
                <div class='col-md-6'>
                    <div class='col-md-4 fieldname'>Birthday:</div><div class='col-md-8'><?= date('m/d/Y', strtotime($contact['Contact']['birthday'])); ?></div>
                </div>
            </div>
             <!-- Title, dept -->
             <div class='row'>
                <div class='col-md-6'>
                    <div class='col-md-4 fieldname'>Title:</div><div class='col-md-8'><?= $contact['Contact']['title']; ?></div>
                </div>
             </div>
             <div class='row'>
                <div class='col-md-6'>
                    <div class='col-md-4 fieldname'>Dept:</div><div class='col-md-8'><?= $contact['Contact']['department']; ?></div>
                </div>
            </div>
             <div class='row'>
                <div class='col-md-6'>
                    <div class='col-md-6 fieldname'>Email Opt Out:</div><div class='col-md-6'><?= $contact['Contact']['marketing_opt_out'] ? "Yes" : "No"; ?></div>
                </div>
            </div>
             <div class='row'>
                <div class='col-md-12'>
                    <div class='col-md-4 fieldname'>Linked Customers:</div><div class='col-md-8'>
                        <?php if(!empty($contact['Customer'])){
                            $custText = "";
                            foreach($contact['Customer'] as $customer)
                            {
                                if($customer['ContactCustomer']['archived'] === NULL)
                                {
                                    if(!empty($custText))
                                        $custText .= ", ";
                                    
                                    $custText .= "<a href='/admin/customers/view/" . $customer['id'] .
                                            "'>" . $customer['name'] . "</a>";
                                }
                            }
                            echo $custText; 
                        } else
                        {
                            echo "<em>---none---</em>";
                        }
?>
                        
                    </div>
                </div>
                 
             </div>
             
             <?php if(!empty($contact['Customer'])){
                            $custText = "";
                            foreach($contact['Customer'] as $customer)
                            {
                                if($customer['ContactCustomer']['archived'] !== NULL)
                                {
                                    if(!empty($custText))
                                        $custText .= ", ";
                                    
                                    $custText .= "<a href='/admin/customers/view/" . $customer['id'] .
                                            "'>" . $customer['name'] . "(" . 
                                            date('m-d-Y', strtotime($customer['ContactCustomer']['archived'])) . ")</a>";
                                }
                            }
                           
                        } 
                        
?>          
             <?php if(!empty($custText)): ?>
             <div class='row'>
                <div class='col-md-12'>
                    <div class='col-md-4 fieldname'>Archived Customers:</div><div class='col-md-8'>
                        
                       <?= $custText; ?> 
                    </div>
                </div>
                 
             </div>
             <?php endif; ?>
             
             <div class='row'>
                <div class='col-md-12'>
                    <div class='col-md-4 fieldname'>Linked Groups:</div><div class='col-md-8'>
                        <?php if(!empty($contact['ContactGroup'])){
                            $custText = "";
                            foreach($contact['ContactGroup'] as $customer)
                            {
                               
                                    if(!empty($custText))
                                        $custText .= ", ";
                                    
                                    $custText .= "<a href='/admin/groups/view/" . $customer['group_id'] .
                                            "'>" . $customer['Group']['name'] . "</a>";
                                
                            }
                            echo $custText; 
                        } else
                        {
                            echo "<em>---none---</em>";
                        }
?>
                    </div>
                </div>
            </div>
             <hr>
             <!-- Addresses, phone numbers, emails -->
             <div class='row'>
                 <div class='col-md-6'>
                     <h4>Address(es)</h4>
                     <p></p>
                     <?php if(!empty($contact['ContactAddress'])): ?>
                     <?php foreach($contact['ContactAddress'] as $address): ?>
                     <p>
                         <strong><?= ucwords($address['type']); ?></strong><br />
                         <?= $address['address_1']; ?><br />
                         <?= !empty($address['address_2']) ? $address['address_2'] . "<br />" : ""; ?>
                         <?= $address['city'] . ", " . $address['state'] . " " . $address['zip']; ?>
                         
                     </p>
                     <?php endforeach; ?>
                     <?php else: ?> 
                     <em>---No linked addresses---</em>
                     <?php endif; ?>
                 </div>
                 <div class='col-md-6'>
                     <h4>Phone Number(s)</h4>
                     <p></p>
                     <?php if(!empty($contact['ContactPhone'])): ?>
                     <?php foreach($contact['ContactPhone'] as $phone): ?>
                     <p>
                         <strong><?= ucwords($phone['type']); ?></strong><br />
                         <?= $phone['phone']; ?> 
                         <?= !empty($phone['ext']) ? "ext. " . $address['address_2']: ""; ?>
                         
                         
                     </p>
                     <?php endforeach; ?>
                     <?php else: ?> 
                     <em>---No phone numbers listed---</em>
                     <?php endif; ?>
                     <h4>Email Address(es)</h4>
                     <p></p>
                     <p>
                         <strong>Primary Email</strong><br />
                         <a href='mailto:<?= $contact['Contact']['email'];?>'><i class='fa fa-envelope'></i> <?= $contact['Contact']['email']; ?></a>
                     </p>
                     <?php if(!empty($contact['Contact']['alternate_email'])): ?>
                     <p>
                         <strong>Primary Email</strong><br />
                         <a href='mailto:<?= $contact['Contact']['email'];?>'><i class='fa fa-envelope'></i> <?= $contact['Contact']['email']; ?></a>
                     </p>
                     <?php endif; ?>
                 </div>
             </div>
          
        </div>
        
        <div class="box-body">
      
            
        </div>
        
        
 </div>
            
            
        </div>
        

        
        <div class="tab-pane fade" id="certifications">   
            <div class='row'>
                <div class='col-md-6'>
         <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-trophy"></i> Certifications
            </h3>

          
        </div>
        <!-- /.box-header -->
        <div class="box-body">
         
        </div>
        
        
 </div>
                </div>
                <div class="col-md-6">
            <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-newspaper-o"></i> Documents
            </h3>

          
        </div>
        <!-- /.box-header -->
        <div class="box-body">
         
        </div>
        
        
 </div>
                </div></div>
        </div>
        
               <div class="tab-pane fade" id="communications">   
         <div class="box box-info">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-envelope"></i> Communications
            </h3>

          
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            
        </div>
        
      
 </div>
        </div>
        
    </div>
</div>
  <?php $this->start('jquery-scripts'); ?>
	
	$("#bgImageBrowse").on('click', function() {
        
		$('#roxyCustomPanel2').dialog({modal:true, width:875,height:600});
	});
	
<?php $this->end(); ?>  
<?php $this->append('scripts'); ?>
<script>
function closeCustomRoxy2(){
	$('#roxyCustomPanel2').dialog('close');
}

$("#certSubmit").click(function(e) {
    e.preventDefault();
    
    $html = "<tr data-type='";
    $html += $("#CertificationType option:selected").val();
    $html += "' data-level='" + $("#CertificationLevel option:selected").text();
    $html += "' data-name='" + $("#CertificationName").val();
    $html += "' data-renewal_date='" + $("#CertificationRenewalDate").val();
    $html += "' data-reliability_score='" + $("#CertificationReliabilityScore").val();
    $html += "' data-notes='" + $("#CertificationNotes").val();
    $html += "' data-start_date='" + $("#CertificationStartDate").val() + "' data-recertification_date='";
    $html += $("#CertificationRecertificationDate").val() + "' class='cert-row'>";
    $html += "<td>" + $("#CertificationType option:selected").text() + "</td>";
    $html += "<td>" + $("#CertificationName").val() + "</td>";
    $html += "<td>" + $("#CertificationLevel option:selected").text() + "</td>"; 
    $html += "<td>" + $("#CertificationStartDate").val() + "</td>";
    $html += "<td>" + $("#CertificationRecertificationDate").val() + "</td>"; 
    $html += "<td>" + $("#CertificationRenewalDate").val() + "</td>";
    $html += "<td>" + $("#CertificationReliabilityScore").val() + "</td>";
    $html += "<td>" + $("#CertificationNotes").val() + "</td>"; 
    $html += "<td><i onclick='removeCertRow(this);' class='fa fa-remove remove-row'></i></td></tr>";
    $("#certList").append($html);
    if($(".cert-row").size() > 0)
    {
        $("#certList").parent().children(".empty-message").fadeOut();
        $("#certList").fadeIn();
    }
});

$("#phoneSubmit").click(function(e) {
    e.preventDefault();
    $extension = "";
    if($("#extension").val().trim() !== "")
        $extension = " ext. " + $("#extension").val();
    $html = "<li data-type='";
    $html += $("#PhoneType option:selected").val();
    $html += "' data-ext='" + $("#extension").val() + "' data-phone='";
    $html += $("#phone_number").val() + "' class='phone-row'>";
    $html += $("#phone_number").val() + $extension + " (" + $("#PhoneType option:selected").text() + ")<i onclick='removeRow(this);' class='fa fa-remove remove-row'></i>";
    $("#phoneNumberList").append($html);
    if($(".phone-row").size() > 0)
    {
        $("#phoneNumberList").parent().children(".empty-message").fadeOut();
    }
});
$("#addressSubmit").click(function(e) {
    e.preventDefault();
    $addr2 = "";
    if($("#addr_2").val().trim() !== "")
        $addr2 = "<br />" + $("#addr_2").val();
    $html = "<li data-type='";
    $html += $("#AddressType").val();
    $html += "' data-addr1='" + $("#addr_1").val() + "' data-addr2='" + $("#addr_2").val() + "' data-city='";
    $html += $("#city").val() + "' data-state='" + $("#state").val() + "' data-zip='" + $("#zip").val() +"' class='address-row'>";
    $html += "<strong>" + $("#AddressType option:selected").text() + "</strong><br />" + $("#addr_1").val() + $addr2 + "<br />" + $("#city").val() +", " + $("#state").val() +  " " + $("#zip").val() + "<i onclick='removeRow(this);' class='fa fa-remove remove-row'></i>";
    $("#addressList").append($html);
    if($(".address-row").size() > 0)
    {
        $("#addressList").parent().children(".empty-message").fadeOut();
    }
});
$("#addCustomerSubmit").click(function(e) {
    e.preventDefault();
    
    $html = "<li data-id='";
    $html += $("#customerList").val();
    $html += "'";
    $html += " class='customer-row'>";
    $html +=  $("#customerList option:selected").text() + "<i onclick='removeRow(this);' class='fa fa-remove remove-row'></i>";
    $("#customersAddList").append($html);
    if($(".customer-row").size() > 0)
    {
        $("#customersAddList").parent().children(".empty-message").fadeOut();
    }
});

$("#addGroupSubmit").click(function(e) {
    e.preventDefault();
    
    $html = "<li data-id='";
    $html += $("#groupList").val();
    $html += "'";
    $html += " class='group-row'>";
    $html +=  $("#groupList option:selected").text() + "<i onclick='removeRow(this);' class='fa fa-remove remove-row'></i>";
    $("#groupAddList").append($html);
    if($(".group-row").size() > 0)
    {
        $("#groupAddList").parent().children(".empty-message").fadeOut();
    }
});

$("#addDocSubmit").click(function(e) {
    e.preventDefault();
    
    $html = "<li data-file='";
    $html += $("#newDoc").val();
    $html += "'";
    $html += " class='doc-row'>";
    $html +=  $("#newDoc").val() + "<i onclick='removeRow(this);' class='fa fa-remove remove-row'></i>";
    $("#docAddList").append($html);
    if($(".doc-row").size() > 0)
    {
        $("#docAddList").parent().children(".empty-message").fadeOut();
    }
});
function removeCertRow(me) {
    $(me).parent().parent().fadeOut().delay(500).remove();
 if($(".cert-row").size() === 0)
    {
        $("#certList").parent().children(".empty-message").fadeIn();
        $("#certList").fadeOut();
    }   
}
function removeRow(me) {

    $(me).parent().fadeOut().delay(500).remove();
    if($(".phone-row").size() === 0)
    {
        $("#phoneNumberList").parent().children(".empty-message").fadeIn();
    }
    if($(".customer-row").size() === 0)
    {
        $("#customersAddList").parent().children(".empty-message").fadeIn();
    }
    if($(".address-row").size() === 0)
    {
        $("#addressList").parent().children(".empty-message").fadeIn();
    }
     if($(".group-row").size() === 0)
    {
        $("#groupList").parent().children(".empty-message").fadeIn();
    }
     if($(".doc-row").size() === 0)
    {
        $("#docList").parent().children(".empty-message").fadeIn();
    }
};

$("#submitAll").click(function(e) {
    e.preventDefault();
    if($(".phone-row").size() > 0)
    {
        $phone = [];
        $(".phone-row").each(function() {
            $newPhone = {
                'type' : $(this).data('type'),
                'phone' : $(this).data('phone'),
                'ext' : $(this).data('ext')                
        };
        $phone.push($newPhone);
    });
   
    $("#PhoneList").val(JSON.stringify($phone));
    }
    if($(".customer-row").size() > 0)
    {
        $customers = [];
        $(".customer-row").each(function() {
            $newCust = {
                'id' : $(this).data('id'),
                
        };
        $customers.push($newCust);
    });
   
    $("#CustomerList").val(JSON.stringify($customers));
    }
    if($(".address-row").size() > 0)
    {
        $phone = [];
        $(".address-row").each(function() {
            $newPhone = {
                'type' : $(this).data('type'),
                'addr1' : $(this).data('addr1'),
                'addr2' : $(this).data('addr2'),
                'city' : $(this).data('city'),
                'state' : $(this).data('state'),
                'zip' : $(this).data('zip')
        };
        $phone.push($newPhone);
    });
   
    $("#AddressList").val(JSON.stringify($phone));
    }
    if($(".cert-row").size() > 0)
    {
        $phone = [];
        $(".cert-row").each(function() {
            $newPhone = {
                'name' : $(this).data('name'),
                'certification_type_id' : $(this).data('type'),
                'level' : $(this).data('level'),   
                'start_date' : $(this).data('start_date'),
                'recertification_date' : $(this).data('recertification_date'),
                'renewal_date' : $(this).data('renewal_date'),   
                'reliability_score' : $(this).data('reliability_score'),
                'notes' : $(this).data('notes')
                               
        };
        $phone.push($newPhone);
    });
   
    $("#CertList").val(JSON.stringify($phone));
    }
    if($(".doc-row").size() > 0)
    {
        $phone = [];
        $(".doc-row").each(function() {
            $newPhone = {
                'file' : $(this).data('file')              
        };
        $phone.push($newPhone);
    });
   
    $("#DocList").val(JSON.stringify($phone));
    }
    
    if($(".group-row").size() > 0)
    {
        $phone = [];
        $(".group-row").each(function() {
            $newPhone = {
                'group_type_id' : $(this).data('id')              
        };
        $phone.push($newPhone);
    });
   
    $("#GroupList").val(JSON.stringify($phone));
    }
    
    $("#addAdminAddForm").submit();
    
});
</script>
<?php $this->end(); ?>


<div id="roxyCustomPanel2" style="display: none; z-index: 100000;">
  <iframe src="/_/plugins/fileman/index.html?integration=custom&type=files&txtFieldId=newDoc" style="width:100%;height:100%" frameborder="0">
  </iframe>
</div>