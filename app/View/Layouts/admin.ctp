<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>CQL SkyCompass | <?= $title_for_layout; ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="/adminPanel/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- jvectormap -->
  <link rel="stylesheet" href="/adminPanel/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
   <!-- DataTables -->
  <link rel="stylesheet" href="/adminPanel/plugins/datatables/dataTables.bootstrap.css">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="/adminPanel/plugins/datepicker/datepicker3.css">

  <!-- Theme style -->
  <link rel="stylesheet" href="/adminPanel/dist/css/AdminLTE.min.css">
  
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="/adminPanel/dist/css/skins/skin-blue.min.css">
  <link href='/plugins/fullcalendar/lib/fullcalendar.min.css' rel='stylesheet' />
<link href='/plugins/fullcalendar/lib/fullcalendar.print.css' rel='stylesheet' media='print' />
<link rel="stylesheet" href="/adminPanel/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
<link href='/plugins/fullcalendar/scheduler.min.css' rel='stylesheet' />
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <link href="/adminPanel/plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css" />
  <link href="/adminPanel/plugins/jquery-ui/jquery-ui.structure.min.css" rel="stylesheet" type="text/css" />
  
  <link type="text/css" href="/adminPanel/plugins/chatbox/jquery.ui.chatbox.css" rel="stylesheet" />
   

    
  <script type="text/javascript">
		tinymceSettings = {
			selector: ".tinymce",
			plugins: [
				"advlist autolink lists link image charmap print preview anchor textcolor",
				"searchreplace visualblocks code fullscreen",
				"insertdatetime media table contextmenu paste colorpicker responsivefilemanager save"
			],
			toolbar: "<?php if ($this->request->params['controller'] == 'content' && $this->request->params['action'] == 'admin_edit'): ?>save | <?php endif; ?>undo redo | formatselect | fontselect | fontsizeselect | forecolor backcolor | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent blockquote | link image media",
			relative_urls : false,
			width: 786,
			doctype: '<!DOCTYPE html>',
			image_advtab: true,
			table_advtab: true,
			table_cell_advtab: true,
			table_row_advtab: true,
			//file_browser_callback: RoxyFileBrowser,
                external_filemanager_path:"/adminPanel/plugins/filemanager/",
   filemanager_title:"Your Site " ,
   external_plugins: { "filemanager" : "/adminPanel/plugins/filemanager/plugin.min.js"},
			<?php if ($this->request->params['controller'] == 'content' && $this->request->params['action'] == 'admin_edit'): ?>
    		save_enablewhendirty: false,
			save_onsavecallback: function() {
				$.blockUI({ message: 'Please wait...'}); 

				var jqxhr = $.post("/admin/content/ajaxSave", { 'id': <?php echo $content['Content']['id']; ?>, 'content': tinymce.get('ContentContent').getContent() }, function(data) {
					if (data == 'ok') {
						$(".blockMsg").html('<span style="color: #090; font-weight: bold;">Changes saved.</span>')
						setTimeout($.unblockUI, 500);
					} else {
						console.log(data);
						$(".blockMsg").html('<span style="color: #900; font-weight: bold;">Changes could not be saved.</span>')
						setTimeout($.unblockUI, 1000);
					}
				})
				.fail(function() {
					$(".blockMsg").html('<span style="color: #900; font-weight: bold;">Changes could not be saved.</span>')
					setTimeout($.unblockUI, 1000);
				});
			},
			<?php endif; ?>
			content_css : "/_/css/bootstrap.min.css,/_/css/bootstrap-theme.min.css,/css/editor.css?v=14,http://fonts.googleapis.com/css?family=Roboto:400,500,700|Oswald:400,700",
			extended_valid_elements : "img[!src|border:0|alt|title|width|height|style]a[name|href|target|title|onclick]",
		  	valid_children : "+body[style]",
		    style_formats: [
		    {
		      title: 'Float Left',
		      selector: 'img', 
		      classes: 'left'
		    },
		    {
		       title: 'Float Right',
		       selector: 'img', 
		       classes: 'right'
		    }
		    ],
		    table_cell_class_list: [
		        {title: 'None', value: ''},
		        {title: 'Mobile Full', value: 'mobile-full'},
		        {title: 'Mobile Hide', value: 'mobile-hide'}
		    ],
		    image_class_list: [
		        {title: 'None', value: ''},
		        {title: 'Mobile Hide', value: 'mobile-hide'},
		        {title: 'Mobile Hide / Desktop Float Left', value: 'mobile-hide-left'},
		        {title: 'Mobile Hide / Desktop Float Right', value: 'mobile-hide-right'}
		    ],
		    block_formats: "Paragraph=p;Pre=pre;Heading 1=h1;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6",
			browser_spellcheck: true,
			font_formats: "Arial=arial,helvetica,sans-serif;"+
		        "Courier New=courier new,courier;"+
		        "Oswald=Oswald;"+
		        "Roboto=Roboto",
			fontsize_formats : "10px 12px 13px 14px 16px 18px 20px 22px 24px 26px 28px 30px 32px"
		};

		
	</script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
    <style>
        .todo-list > li {
            list-style-type: none;
        }
        .form-control.checkbox {
            -webkit-appearance: checkbox;
        }
  .custom-combobox {
    position: relative;
    display: inline-block;
    width: 100%;
  }
  .custom-combobox-toggle {
    position: absolute;
    top: 0;
    bottom: 0;
    margin-left: -1px;
    padding: 2px 5px;
    border: 1px solid #cfcfcf;
    background: #efefef;
    cursor: pointer;
    color: #888;
    font-weight: bold;
    
    right: 0;
    
    
  }
  .custom-combobox-input {
    margin: 0;
    padding: 5px 10px;
  
  }
  .ui-widget {
      z-index: 9;
  }
  .main-item {font-weight: bold;}
                  .child-item {font-style: italic; font-size: 95%; padding-left: 25px;}
                  
                  /* *** Add this for visible Scrolling ;) */

        .ui-autocomplete {
		max-height: 350px;
		overflow-y: auto;
		/* prevent horizontal scrollbar */
		overflow-x: hidden;
		/* add padding to account for vertical scrollbar */
		padding-right: 20px;
	}
	/* IE 6 doesn't support max-height
	 * we use height instead, but this forces the menu to always be this tall
	 */
	* html .ui-autocomplete {
		height: 100px;
	}
  </style>
<div class="wrapper">

  <header class="main-header">

    <!-- Logo -->
    <a href="/admin" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>CQL</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg">CQL <b>Sky</b>Compass</span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Messages: style can be found in dropdown.less-->
          <?= $this->element('admin/messages'); ?>
          <!-- Notifications: style can be found in dropdown.less -->
          <?= $this->element('admin/notifications'); ?>
          <!-- Tasks: style can be found in dropdown.less -->
          <?= $this->element('admin/tasks'); ?>
          <!-- User Account: style can be found in dropdown.less -->
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="/adminPanel/uploads/default.jpg" class="user-image" alt="User Image">
              <span class="hidden-xs"><?= $currentUser['first_name'] . " " . $currentUser['last_name']; ?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="/adminPanel/uploads/default.jpg" class="img-circle" alt="User Image">

                <p>
                  <?= $currentUser['first_name'] . " " . $currentUser['last_name']; ?>
                  <small>Member since <?= date('M Y', strtotime($currentUser['created'])); ?></small>
                </p>
              </li>
              <!-- Menu Body -->
<!--              <li class="user-body">
                <div class="row">
                  <div class="col-xs-4 text-center">
                    <a href="#">Followers</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Sales</a>
                  </div>
                  <div class="col-xs-4 text-center">
                    <a href="#">Friends</a>
                  </div>
                </div>
                 /.row 
              </li>-->
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="/admin/users/profile" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="/admin/users/logout" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="#" data-toggle="control-sidebar"><i class="fa fa-check-square-o"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="/adminPanel/uploads/default.jpg" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?= $currentUser['first_name'] . " " . $currentUser['last_name']; ?></p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <?php echo $this->element('admin_menu'); ?>
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?= $this->fetch('title'); ?>
        <small>Version 2.0</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
       <?php echo $this->Session->flash(); ?>
        <div class="main" style="padding: 10px;">
      <?php echo $this->fetch('content'); ?>
        </div>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.3.3
    </div>
    <strong>Copyright &copy; 2016 <a href="http://net2sky.com">Net2Sky, LLC</a>.</strong> All rights
    reserved.
  </footer>

 <!-- This is where the sidebar would go if i need it - it's an element now -->
  <?= $this->element('modals/entry'); ?>
<?= $this->element('modals/expense'); ?>
 <?= $this->element('modals/expense_report'); ?>
<div id="dialog" style="display: none">

</div>
  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark" style='z-index: 99999; margin-top: 50px; padding-top: 0; height: 100%;'>
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li><a href="#control-sidebar-ordered-tab" data-toggle="tab"><i class="fa fa-calendar-check-o"></i></a></li>
      <li><a href="#control-sidebar-jobs-tab" data-toggle="tab"><i class="fa fa-building"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <!-- Home tab content -->
      <?= $this->element('todo/todo'); ?>

      
      
      <!-- Settings tab content -->
      <?= $this->element('todo/jobs', array('jobtaskliksts' => $jobtasklists)); ?>
    </div>
  </aside>
  <?php $this->append('scripts'); ?>
<script>
$(".textarea").wysihtml5();

$(".job-todo-list").todolist({
    onCheck: function (ele) {
      $.ajax('/admin/jobTaskListItems/ajaxChangeItemStatus/' + $(this).data('id') + '/1');
      return ele;
    },
    onUncheck: function (ele) {
      $.ajax('/admin/jobTaskListItems/ajaxChangeItemStatus/' + $(this).data('id') + '/0');
      return ele;
    }
  });
</script>
<?php $this->end(); ?>

  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>

  </div>
<!-- ./wrapper -->

<!-- jQuery 2.2.0 -->
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="/adminPanel/plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="/adminPanel/bootstrap/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="/adminPanel/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/adminPanel/plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- FastClick -->
<script src="/adminPanel/plugins/fastclick/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="/adminPanel/dist/js/app.min.js"></script>
<!-- Sparkline -->
<script src="/adminPanel/plugins/sparkline/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="/adminPanel/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="/adminPanel/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- SlimScroll 1.3.0 -->
<script src="/adminPanel/plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- ChartJS 1.0.1 -->
<script src="/adminPanel/plugins/chartjs/Chart.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!--<script src="/adminPanel/dist/js/pages/dashboard2.js"></script>-->
<!-- AdminLTE for demo purposes -->
<script src="/_/js/modals.js"></script>
<script src='/plugins/fullcalendar/lib/moment.min.js'></script>

<script src='/plugins/fullcalendar/lib/fullcalendar.min.js'></script>
<script src='/plugins/fullcalendar/scheduler.min.js'></script>

<!-- bootstrap datepicker -->
<script src="/adminPanel/plugins/datepicker/bootstrap-datepicker.js"></script>
<!-- Add fancyBox -->
<link rel="stylesheet" href="/adminPanel/plugins/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<script type="text/javascript" src="/adminPanel/plugins/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>

 <script type="text/javascript" src="/adminPanel/plugins/chatbox/jquery.ui.chatbox.js"></script>
<script src="/adminPanel/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<?php echo $this->fetch('scripts'); ?>
<script type="text/javascript">
    
    $('.select2 span').addClass('needsclick');
    
    $('.dataTable').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "order": [[0, "desc"]]
    });
    $('.notifications-dataTable').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": false,
      "pageLength": 50
    });
    $('.approval-dataTable').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "order": [[2,"desc"]]
    });
    $(".datepicker").datepicker();
    
    $("#expenseReportGenerate").click(function(e) {
        e.preventDefault();
        $start = new Date($("#startDateReport").val());
        $end = new Date($("#endDateReport").val());
                window.location = "/admin/expenses/expenseExport/" + 
                        $start.getFullYear() + "-" + ($start.getMonth()+1) + "-" + $start.getDate() + "/" + 
                        $end.getFullYear() + "-" + ($end.getMonth()+1) + "-" + $end.getDate()
        
    });
 
	$(function() {
            
            $.ajax('/admin/todo/ajaxGetList').done(function(data) {
     $(".uncategorized-todo-list").html(data);
});
            
            //Make the dashboard widgets sortable Using jquery UI
  $(".connectedSortable").sortable({
    placeholder: "sort-highlight",
    connectWith: ".connectedSortable",
    handle: ".box-header, .nav-tabs",
    forcePlaceholderSize: true,
    zIndex: 999999
  });
  $(".connectedSortable .box-header, .connectedSortable .nav-tabs-custom").css("cursor", "move");
            
            $(".fancybox").fancybox({
                    'autoScale': true});
            
<?php echo $this->fetch('jquery-scripts'); ?>
		
               
     
         
         
	});
        
       

	
        $("#galleryManager").on('click', function(e) {
                        e.preventDefault();
                
			$('#galleryManagerCustomPanel').dialog({modal: true, width:1050,height:600});
			return false;
		});
                
                 $.widget( "custom.combobox", {
      _create: function() {
        this.wrapper = $( "<span>" )
          .addClass( "custom-combobox" )
          .insertAfter( this.element );
 
        this.element.hide();
        this._createAutocomplete();
        this._createShowAllButton();
      },
 
      _createAutocomplete: function() {
        var selected = this.element.children( ":selected" ),
          value = selected.val() ? selected.text() : "";
          
        this.input = $( "<input>" )
          .appendTo( this.wrapper )
          .val( value.trim() )
          .attr( "title", "" )
          .addClass( "custom-combobox-input form-control" )
          .tooltip({
            classes: {
              "ui-tooltip": "ui-state-highlight"
            }
          })
          .autocomplete({
            delay: 0,
            minLength: 0,
            source: $.proxy( this, "_source" )
          });
                  
            this.input
                    .autocomplete( "instance" )._renderItem = function( ul, item ) {
                       
              $class = item.option.attributes.class.value;
      
            return $( "<li value='"+ item.value.trim() + "' class='" + $class +"' >" + item.label + "</li>")
        .appendTo( ul );
    };
          
 
        this._on( this.input, {
          autocompleteselect: function( event, ui ) {
            ui.item.option.selected = true;
            this._trigger( "select", event, {
              item: ui.item.option
            });
          },
 
          autocompletechange: "_removeIfInvalid"
        });
      },
  
      _createShowAllButton: function() {
        var input = this.input,
          wasOpen = false;
 
        $( "<a>" )
          .attr( "tabIndex", -1 )
          .attr( "title", "Show All Items" )
          .tooltip()
          .html('<i class="fa fa-2x fa-angle-down"></i>')
          .appendTo( this.wrapper )
          .button({
            icons: {
              primary: "ui-icon-triangle-1-s"
            },
            text: false
          })
          .removeClass( "ui-corner-all" )
          .addClass( "custom-combobox-toggle ui-corner-right" )
          .on( "mousedown", function() {
            wasOpen = input.autocomplete( "widget" ).is( ":visible" );
          })
          .on( "click", function() {
            input.trigger( "focus" );
 
            // Close if already visible
            if ( wasOpen ) {
              return;
            }
 
            // Pass empty string as value to search for, displaying all results
            input.autocomplete( "search", "" );
          });
      },
 
      _source: function( request, response ) {
        var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
        response( this.element.children( "option" ).map(function() {
          var text = $( this ).text();
          if ( this.value && ( !request.term || matcher.test(text) ) )
            return {
              label: text,
              value: text.trim(),
              option: this
            };
        }) );
      },
 
      _removeIfInvalid: function( event, ui ) {
 
        // Selected an item, nothing to do
        if ( ui.item ) {
          return;
        }
 
        // Search for a match (case-insensitive)
        var value = this.input.val(),
          valueLowerCase = value.toLowerCase(),
          valid = false;
        this.element.children( "option" ).each(function() {
          if ( $( this ).text().toLowerCase() === valueLowerCase ) {
            this.selected = valid = true;
            return false;
          }
        });
 
        // Found a match, nothing to do
        if ( valid ) {
          return;
        }
 
        // Remove invalid value
        this.input
          .val( "" )
          .attr( "title", value + " didn't match any item" )
          .tooltip( "open" );
        this.element.val( "" );
        this._delay(function() {
          this.input.tooltip( "close" ).attr( "title", "" );
        }, 2500 );
        this.input.autocomplete( "instance" ).term = "";
      },
 
      _destroy: function() {
        this.wrapper.remove();
        this.element.show();
      }
    });
 
    $( ".select2" ).combobox();

</script>

<script type="text/javascript">
     
          var box = [];
          $(".message-link").click(function(event, ui) {
              event.preventDefault();
              if(box[$(this).data('chatid')]) {
                  box.chatbox("option", "boxManager").toggleBox();
              }
              else {
                  $user = $(this).data('user');
                  $url = $(this).attr('href');
                  $boxid = $(this).data('chatid');
                  box[$(this).data('chatid')] = $("#chat_div").chatbox({id:"<?= $currentUser['first_name']; ?>", 
                                                user:{key : "value"},
                                                title : $user,
                                                messageSent : function(id, user, msg) {
                                                    console.log(id + " said: " + msg);
                                                    var encoded = encodeURIComponent(msg);
                                                    $.ajax(
                                                    {
                                                        url: '/admin/messages/sendMessage/' + $boxid + '/' + encoded
                                                    }).done(function(data) {
                                                      //  alert(data);
                                                        $message = msg;
                                                        $("#chat_div").chatbox("option", "boxManager").addMsg(id, $message);
                                                        });
                                                    //$("#chat_div").chatbox("option", "boxManager").addMsg(id, msg);
                                                }});
                     
                                            $.ajax(
                                                    {
                                                        url: $url,
                                                        dataType: 'json'
                                                    }).done(function(data) {
                                                      //  alert(data);
                                                      
                                                        $.each(data, function(name, message) {
                                                            
                                                            $("#chat_div").chatbox("option", "boxManager").addMsg(message.user, message.message);
                                                        });
                                                        
                                                        });
                                                        
                        
              }
          });
  
        var fileName = "<?= $config['site.faq_file']; ?>";
        var width = $(window).width();
var height = $(window).height();

// Provide some space between the window edges.
width = width - 50;
height = height - 50; // iframe height will need to be even less to account for space taken up by dialog title bar, buttons, etc.

            $("#faq-link").click(function (r) {
                r.preventDefault();
                $("#dialog").dialog({
                    modal: true,
                    title: fileName,
                    width: width,
                    height: height,
                    buttons: {
                        Close: function () {
                            $(this).dialog('close');
                        }
                    },
                    open: function () {
                        var object = "<object data=\"{FileName}\" type=\"application/pdf\" width=\"100%\" height=\"100%\">";
                        object += "If you are unable to view file, you can download from <a href = \"{FileName}\">here</a>";
                        object += " or download <a target = \"_blank\" href = \"http://get.adobe.com/reader/\">Adobe PDF Reader</a> to view the file.";
                        object += "</object>";
                        object = object.replace(/{FileName}/g, fileName);
                        $("#dialog").html(object);
                    }
                });
            });
       
    $('.ls-modal').fancybox({
			  'width'	: '90%',
			  'height'	: '90%',
			  'type'	: 'iframe',
			  'autoScale'   : false
      });
    </script>

<style>
    .ui-dialog {
        z-index: 99999 !important;
    }
    </style>

    <div id="galleryManagerCustomPanel" style="display: none;" title="Galleries">
	<iframe src="/adminPanel/plugins/filemanager/dialog.php?type=0&relative_url=1&popup=1" style="width:100%;height:100%; z-index: 99999;" frameborder="0"></iframe>
</div>


</body>
</html>