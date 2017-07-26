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
  <!-- Theme style -->
  <link rel="stylesheet" href="/adminPanel/dist/css/AdminLTE.min.css">
  <link href="/_/plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css" />
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="/adminPanel/dist/css/skins/_all-skins.min.css">
  <link href='/plugins/fullcalendar/lib/fullcalendar.min.css' rel='stylesheet' />
<link href='/plugins/fullcalendar/lib/fullcalendar.print.css' rel='stylesheet' media='print' />
<link href='/plugins/fullcalendar/scheduler.min.css' rel='stylesheet' />
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  
  <script type="text/javascript">
		tinymceSettings = {
			selector: ".tinymce",
			plugins: [
				"advlist autolink lists link image charmap print preview anchor textcolor",
				"searchreplace visualblocks code fullscreen",
				"insertdatetime media table contextmenu paste colorpicker save"
			],
			toolbar: "<?php if ($this->request->params['controller'] == 'content' && $this->request->params['action'] == 'admin_edit'): ?>save | <?php endif; ?>undo redo | formatselect | fontselect | fontsizeselect | forecolor backcolor | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent blockquote | link image media",
			relative_urls : false,
			width: 786,
			doctype: '<!DOCTYPE html>',
			image_advtab: true,
			table_advtab: true,
			table_cell_advtab: true,
			table_row_advtab: true,
			file_browser_callback: RoxyFileBrowser,
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
		        {title: 'Mobile Hide', value: 'mobile-hide'},
                        {title: 'Mobile Only', value: 'mobile-only'}
		    ],
		    image_class_list: [
		        {title: 'None', value: ''},
		        {title: 'Mobile Hide', value: 'mobile-hide'},
                        {title: 'Mobile Only', value: 'mobile-only'},
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

		function RoxyFileBrowser(field_name, url, type, win) {
		  var roxyFileman = '/_/plugins/fileman/index.html';
		  if (roxyFileman.indexOf("?") < 0) {     
		    roxyFileman += "?type=" + type;   
		  }
		  else {
		    roxyFileman += "&type=" + type;
		  }
		  roxyFileman += '&input=' + field_name + '&value=' + document.getElementById(field_name).value;
		  tinyMCE.activeEditor.windowManager.open({
		     file: roxyFileman,
		     title: 'File Mananger',
		     width: 850, 
		     height: 650,
		     resizable: "yes",
		     plugins: "media",
		     inline: "yes",
		     close_previous: "no"  
		  }, {     window: win,     input: field_name    });
		  return false; 
		}
	</script>
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <?= $title_for_layout; ?>
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



</div>
<!-- ./wrapper -->

<!-- jQuery 2.2.0 -->
<script src="/adminPanel/plugins/jQuery/jQuery-2.2.0.min.js"></script>
<script src="/_/plugins/jquery-ui/jquery-ui.min.js"></script>
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
<script src="/adminPanel/dist/js/pages/dashboard2.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="/adminPanel/dist/js/demo.js"></script>
<script src='/plugins/fullcalendar/lib/moment.min.js'></script>

<script src='/plugins/fullcalendar/lib/fullcalendar.min.js'></script>
<script src='/plugins/fullcalendar/scheduler.min.js'></script>

<?php echo $this->fetch('scripts'); ?>
<script type="text/javascript">
    
    $('.dataTable').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
 
	$(function() {
            
<?php echo $this->fetch('jquery-scripts'); ?>
		
               
          $('#calendar').fullCalendar({
			now: '2016-05-07',
			editable: true,
			aspectRatio: 1.8,
			scrollTime: '00:00',
			header: {
				left: 'promptResource today prev,next',
				center: 'title',
				right: 'timelineDay,timelineThreeDays,agendaWeek,month'
			},
			customButtons: {
				promptResource: {
					text: '+ room',
					click: function() {
						var title = prompt('Room name');
						if (title) {
							$('#calendar').fullCalendar(
								'addResource',
								{ title: title },
								true // scroll to the new resource?
							);
						}
					}
				}
			},
			defaultView: 'timelineDay',
			views: {
				timelineThreeDays: {
					type: 'timeline',
					duration: { days: 3 }
				}
			},
			resourceLabelText: 'Rooms',
			resourceRender: function(resource, cellEls) {
				cellEls.on('click', function() {
					if (confirm('Are you sure you want to delete ' + resource.title + '?')) {
						$('#calendar').fullCalendar('removeResource', resource);
					}
				});
			},
			resources: [
				{ id: 'a', title: 'Auditorium A' },
				{ id: 'b', title: 'Auditorium B', eventColor: 'green' },
				{ id: 'c', title: 'Auditorium C', eventColor: 'orange' },
				{ id: 'd', title: 'Auditorium D', children: [
					{ id: 'd1', title: 'Room D1' },
					{ id: 'd2', title: 'Room D2' }
				] },
				{ id: 'e', title: 'Auditorium E' },
				{ id: 'f', title: 'Auditorium F', eventColor: 'red' },
				{ id: 'g', title: 'Auditorium G' },
				{ id: 'h', title: 'Auditorium H' },
				{ id: 'i', title: 'Auditorium I' },
				{ id: 'j', title: 'Auditorium J' },
				{ id: 'k', title: 'Auditorium K' },
				{ id: 'l', title: 'Auditorium L' },
				{ id: 'm', title: 'Auditorium M' },
				{ id: 'n', title: 'Auditorium N' },
				{ id: 'o', title: 'Auditorium O' },
				{ id: 'p', title: 'Auditorium P' },
				{ id: 'q', title: 'Auditorium Q' },
				{ id: 'r', title: 'Auditorium R' },
				{ id: 's', title: 'Auditorium S' },
				{ id: 't', title: 'Auditorium T' },
				{ id: 'u', title: 'Auditorium U' },
				{ id: 'v', title: 'Auditorium V' },
				{ id: 'w', title: 'Auditorium W' },
				{ id: 'x', title: 'Auditorium X' },
				{ id: 'y', title: 'Auditorium Y' },
				{ id: 'z', title: 'Auditorium Z' }
			],
			events: [
				{ id: '1', resourceId: 'b', start: '2016-05-07T02:00:00', end: '2016-05-07T07:00:00', title: 'event 1' },
				{ id: '2', resourceId: 'c', start: '2016-05-07T05:00:00', end: '2016-05-07T22:00:00', title: 'event 2' },
				{ id: '3', resourceId: 'd', start: '2016-05-06', end: '2016-05-08', title: 'event 3' },
				{ id: '4', resourceId: 'e', start: '2016-05-07T03:00:00', end: '2016-05-07T08:00:00', title: 'event 4' },
				{ id: '5', resourceId: 'f', start: '2016-05-07T00:30:00', end: '2016-05-07T02:30:00', title: 'event 5' }
			]
		});   
         
         
	});
        
       

	$(document).ready(function() {
		$(".fancybox").fancybox();
	});
        
        $("#galleryManager").on('click', function(e) {
                        e.preventDefault();
                
			$('#galleryManagerCustomPanel').dialog({modal:true, width:800,height:600});
			return false;
		});

</script>
<style>
    .ui-dialog {
        z-index: 99999 !important;
    }
    </style>
<div id="galleryManagerCustomPanel" style="display: none;" title="Galleries">
	<iframe src="/_/plugins/fileman/index.html?integration=custom" style="width:100%;height:100%; z-index: 99999;" frameborder="0"></iframe>
</div>

<?php echo $this->Js->writeBuffer();?>
</body>
</html>