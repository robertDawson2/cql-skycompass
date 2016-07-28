<!doctype html>
<!--
	HTML5 Reset: https://github.com/murtaugh/HTML5-Reset
	Free to use
-->
<!--[if lt IE 7 ]> <html class="ie ie6 ie-lt10 ie-lt9 ie-lt8 ie-lt7 no-js" lang="en"> <![endif]-->
<!--[if IE 7 ]>    <html class="ie ie7 ie-lt10 ie-lt9 ie-lt8 no-js" lang="en"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie ie8 ie-lt10 ie-lt9 no-js" lang="en"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie ie9 ie-lt10 no-js" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--><html class="no-js" lang="en"><!--<![endif]-->
<!-- the "no-js" class is for Modernizr. --> 
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	
	<title><?php echo $title_for_layout; ?></title>
	
	<link rel="shortcut icon" href="favicon.ico" />
	<link rel="stylesheet" href="/_/css/reset.css" />
	<link rel="stylesheet" href="/_/css/bootstrap.cms.css">
	<link rel="stylesheet" href="/_/css/cms.css?v=1128144" />
	<link href="http://fonts.googleapis.com/css?family=Roboto:400,500,700|Oswald:400,700" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="/_/plugins/font-awesome/css/font-awesome.min.css">
	<link href="/_/css/bootstrap-modal-bs3patch.css" rel="stylesheet" />
	<link href="/_/css/bootstrap-modal.css" rel="stylesheet" />
	<link href="/_/plugins/data-tables/DT_bootstrap.css" rel="stylesheet" type="text/css" />
	<link href="/_/plugins/jquery-ui/jquery-ui.min.css" rel="stylesheet" type="text/css" />
        
	<?php echo $this->fetch('css'); ?>
	
	<?php if (0): ?><script src="/_/js/libs/prefixfree.min.js"></script><?php endif; ?>
	<script src="/_/js/libs/modernizr-2.7.1.dev.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>

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

<body>
<div class="wrapper">

    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#"><?php echo $config['site.name']; ?></a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            
            <li><a href="/admin/users/logout">Log Out</a></li>
          </ul>
          <?php if (0): ?>
		  <form class="navbar-form navbar-right">
            <input type="text" class="form-control" placeholder="Search...">
          </form>
		  <?php endif; ?>
        </div>
      </div>
    </div>
    <style>
        li.dropdown > ul {
            display: none;
        }
        .subnav {
            margin-left: 15px;
            background-color: rgba(0,0,0,0.1);
        }
    </style>
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
        <?php $permissions = unserialize(AuthComponent::user('permissions')); ?>
            <ul class="nav  nav-sidebar">
                <li class="dropdown">
                    <a href="#"><i class="fa fa-globe"></i> Website</a>
                    <ul class="nav subnav nav-sidebar">
            <li<?php if (!isset($section) || $section == 'dashboard') echo ' class="active"'; ?>><a href="/admin"><i class="fa fa-home"></i> &nbsp;Dashboard</a></li>
            <?php if ($permissions['site']['content'] == 1 || array_sum($permissions['content']) > 0): ?><li<?php if (isset($section) && $section == 'content') echo ' class="active"'; ?>><a href="/admin/content"><i class="fa fa-edit"></i> &nbsp;Content</a></li><?php endif; ?>
			<?php if ($permissions['site']['home_page_features'] == 1): ?><li<?php if (isset($section) && $section == 'features') echo ' class="active"'; ?>><a href="/admin/features"><i class="fa fa-star"></i> &nbsp;Home Page Features</a></li><?php endif; ?>
			<?php if ($permissions['site']['events'] == 1): ?><li<?php if (isset($section) && $section == 'events') echo ' class="active"'; ?>><a href="/admin/events"><i class="fa fa-calendar"></i> &nbsp;Events</a></li><?php endif; ?>
			<?php if ($permissions['site']['news'] == 1): ?><li<?php if (isset($section) && $section == 'news') echo ' class="active"'; ?>><a href="/admin/news"><i class="fa fa-microphone"></i> &nbsp;News</a></li><?php endif; ?>
			<?php if ($permissions['site']['galleries'] == 1): ?><li<?php if (isset($section) && $section == 'fileManager') echo ' class="active"'; ?>><a href="#" id="galleryManager"><i class="fa fa-image"></i> &nbsp;Galleries</a></li><?php endif; ?>
            <?php if (0): ?><li<?php if (isset($section) && $section == 'navigation') echo ' class="active"'; ?>><a href="/admin/content/navigation"><i class="fa fa-sitemap"></i> &nbsp;Navigation</a></li><?php endif; ?>
			<?php if (0): ?><li<?php if (isset($section) && $section == 'forms') echo ' class="active"'; ?>><a href="/admin/forms"><i class="fa fa-list-alt"></i> &nbsp;Forms</a></li><?php endif; ?>
			<?php if ($permissions['site']['users'] == 1): ?><li<?php if (isset($section) && $section == 'users') echo ' class="active"'; ?>><a href="/admin/users"><i class="fa fa-user"></i> &nbsp;Users</a></li><?php endif; ?>
			<?php if (0): ?><li<?php if (isset($section) && $section == 'settings') echo ' class="active"'; ?>><a href="/admin/content/settings"><i class="fa fa-cog"></i> &nbsp;Site Settings</a></li><?php endif; ?>
			
          </ul>
                </li>
               
                <li class="dropdown">
                    <a href="#"><i class="fa fa-dollar"></i> QuickBooks Integration</a>
                    <ul class="nav subnav nav-sidebar">
                        <li <?php if (isset($section) && $section == 'approve') echo ' class="active"'; ?>>
                            <a href="#"><i class="fa fa-money"></i> Approve Expenses</a>
                        </li>
                        <li <?php if (isset($section) && $section == 'time-log') echo ' class="active"'; ?>>
                            <a href="#"><i class="fa fa-clock-o"></i> View Time Log</a>
                        </li>
                    </ul>
                </li>
            </ul>
          
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          				
		<?php if (isset($notification)): ?>
		<div class="alert alert-<?php echo $notification['status']; ?>" style="font-size: 14px;">
			<button class="close" data-dismiss="alert"></button>
			<i class="fa fa-<?php echo $notification['status'] == 'success' ? 'check' : 'exclamation'; ?>"></i> &nbsp;<?php echo $notification['notification']; ?>
		</div>
		<?php endif; ?>
		
		  <h1 class="page-header"><?php echo $title_for_layout; ?></h1>
		  <?php if (isset($breadcrumbs)): ?>
		  <ol class="breadcrumb">
		    <?php 
				foreach ($breadcrumbs as $link => $crumb) {
					if ($link == '-')
						echo '<li class="active">' . $crumb . '</li>';
					else
						echo '<li><a href="' . $link . '">' . $crumb . '</a></li>';
				}
			?>
		  </ol>
		  <?php endif; ?>
		  
		  <?php echo $this->fetch('content'); ?>
        </div>
      </div>
    </div>
	
</div>

<script src="/_/js/libs/jquery-1.11.0.min.js"></script>
<script src="/_/plugins/jquery-ui/jquery-ui.min.js"></script>
<script src="/_/js/bootstrap.min.js"></script>
<script src="/_/js/bootstrap-modalmanager.js"></script>
<script src="/_/js/bootstrap-modal.js"></script>
<script src="/_/js/functions.js"></script>
<script src="/_/js/modals.js"></script>
<script src="/_/plugins/data-tables/jquery.dataTables.min.js" type="text/javascript"></script>	
<script src="/_/plugins/data-tables/DT_bootstrap.js" type="text/javascript"></script>
<script src="/_/js/jquery.blockUI.js" type="text/javascript"></script>s
    <link rel="stylesheet" href="/js/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />
<script type="text/javascript" src="/js/jquery.fancybox.pack.js?v=2.1.5"></script>

<?php echo $this->fetch('scripts'); ?>
<script type="text/javascript">
	$(function() {
<?php echo $this->fetch('jquery-scripts'); ?>
		$("#galleryManager").on('click', function() {
			$('#galleryManagerCustomPanel').dialog({modal:true, width:1000,height:700});
			return false;
		});
               
                $("li.dropdown a").on('click', function(e) {
                    
                    e.preventDefault();
                
                    $(this).parent().children('ul').slideToggle();
    });
        
         
         
	});

	$(document).ready(function() {
		$(".fancybox").fancybox();
	});

</script>
<div id="galleryManagerCustomPanel" style="display: none;" title="Galleries">
	<iframe src="/_/plugins/fileman/index.html?integration=custom" style="width:100%;height:100%" frameborder="0"></iframe>
</div>

<?php echo $this->Js->writeBuffer();?>
</body>
</html>