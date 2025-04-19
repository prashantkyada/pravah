<?php
include("../include/conf.php");
include("../include/function.php");
include("paginator.class.php");
error_reporting(E_ALL ^ E_NOTICE);
if($_SESSION['islogged'] != "1")
{
	echo "<script language='javascript'>window.location='index.php';</script>";
}

$order_view = mysqli_query($mysqli,"SELECT * FROM tbl_order where view_status='0'");
$order_rows = mysqli_num_rows($order_view);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <title>Welcome To Pravah Admin Pannel</title>
    <!-- Le styles -->
    <!-- Use new way for google web fonts 
    http://www.smashingmagazine.com/2012/07/11/avoiding-faux-weights-styles-google-web-fonts -->
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css' /> <!-- Headings -->
    <link href='https://fonts.googleapis.com/css?family=Droid+Sans:400,700' rel='stylesheet' type='text/css' /> <!-- Text -->
    
    <!-- Core stylesheets do not remove -->
    <link id="bootstrap" href="css/bootstrap/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="css/bootstrap/bootstrap-theme.css" rel="stylesheet" type="text/css" />
    <link href="css/supr-theme/jquery.ui.supr.css" rel="stylesheet" type="text/css"/>
    <link href="css/icons.css" rel="stylesheet" type="text/css" />
    <!-- Plugin stylesheets -->
    <link href="plugins/misc/qtip/jquery.qtip.css" rel="stylesheet" type="text/css" />
    <link href="plugins/forms/uniform/uniform.default.css" type="text/css" rel="stylesheet" />  
    <link href="plugins/forms/inputlimiter/jquery.inputlimiter.css" type="text/css" rel="stylesheet" />     
    <link href="plugins/forms/togglebutton/toggle-buttons.css" type="text/css" rel="stylesheet" /> 
    <link href="plugins/tables/dataTables/jquery.dataTables.css" type="text/css" rel="stylesheet" />
    <link href="plugins/misc/fullcalendar/fullcalendar.css" rel="stylesheet" type="text/css" />
    <link href="plugins/tables/dataTables/TableTools.css" type="text/css" rel="stylesheet" />
    <link href="plugins/files/elfinder/elfinder.css" type="text/css" rel="stylesheet" />
    <link href="plugins/files/plupload/jquery.ui.plupload/css/jquery.ui.plupload.css" type="text/css" rel="stylesheet" />
    <link href="plugins/gallery/jpages/jPages.css" rel="stylesheet" type="text/css" />
    <link href="plugins/gallery/pretty-photo/prettyPhoto.css" type="text/css" rel="stylesheet" />
    <link href="plugins/forms/uniform/uniform.default.css" type="text/css" rel="stylesheet" />
    <link href="plugins/forms/color-picker/color-picker.css" type="text/css" rel="stylesheet" />
    <link href="plugins/forms/select/select2.css" type="text/css" rel="stylesheet" />
    <link href="plugins/forms/validate/validate.css" type="text/css" rel="stylesheet" />

    
    <!-- Main stylesheets -->
    <link href="css/main.css" rel="stylesheet" type="text/css" /> 

    <!-- Custom stylesheets ( Put your own changes here ) -->
    <link href="css/custom.css" rel="stylesheet" type="text/css" />
    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="images/logo.png" />
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/apple-touch-icon-144-precomposed.png" />
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/apple-touch-icon-114-precomposed.png" />
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/apple-touch-icon-72-precomposed.png" />
    <link rel="apple-touch-icon-precomposed" href="images/logo.png" />
    <!-- Windows8 touch icon ( http://www.buildmypinnedsite.com/ )-->
    <meta name="application-name" content="Supr"/> 
    <meta name="msapplication-TileColor" content="#3399cc"/> 

    <!-- Load modernizr first -->
    <script type="text/javascript" src="js/libs/modernizr.js"></script>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    </head>
    <body>
    <!-- loading animation -->
    <div id="qLoverlay"></div>
    <div id="qLbar"></div>

    <div id="header">
        <nav class="navbar navbar-default" role="navigation">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">&nbsp;<span class="slogan">admin</span></a>
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon16 icomoon-icon-arrow-4"></span>
                </button>
          </div> 
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                 <ul class="nav navbar-right usernav">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle avatar" data-toggle="dropdown">
                            <span class="txt">Welcome to&nbsp;<?php echo $_SESSION['username']; ?></span>
                            <b class="caret"></b></a>
          				<ul class="dropdown-menu">
                            <li class="menu">
                                <ul>
                                    <li><a href="setting.php"><span class="icon16 icomoon-icon-user-plus"></span>Edit profile</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                  <li><a href="logout.php"><span class="icon16 icomoon-icon-exit"></span><span class="txt"> Logout</span></a></li>
                </ul>
            </div><!-- /.nav-collapse -->
        </nav><!-- /navbar --> 
    </div><!-- End #header -->
    <div id="wrapper">
        <!--Responsive navigation button-->  
        <div class="resBtn">
             <a href="#"><span class="icon16 minia-icon-list-3"></span></a>
        </div>
        <!--Sidebar background-->
        <div id="sidebarbg"></div>
        <!--Sidebar content-->
        <div id="sidebar">
            <div class="shortcuts">
            </div><!-- End search -->            
            <div class="sidenav">
                <div class="sidebar-widget" style="margin: -1px 0 0 0;">
                    <h5 class="title" style="margin-bottom:0">Navigation</h5>
                </div><!-- End .sidenav-widget -->
                <div class="mainnav">
                    <ul>
						<li><a href="dashboard.php"><span class="icon16 icomoon-icon-file"></span>Dashboard</a></li> 
                        <li><a href="manage_user.php"><span class="icon16 icomoon-icon-file"></span>Manage User</a></li>
                        <li><a href="manage_category.php"><span class="icon16 icomoon-icon-file"></span>Manage Category</a></li>
                        <li><a href="change_manage_product.php"><span class="icon16 icomoon-icon-file"></span>Manage Product</a></li> 
                       <!--   <li><a href="change_manage_product.php"><span class="icon16 icomoon-icon-file"></span>New Manage Product</a></li> -->
                        <li><a href="manage_order.php"><span class="icon16 icomoon-icon-file"></span>Manage Order<span class="notification"><?php echo $order_rows;?></span></a></li>
                        <li><a href="dispatch_order.php"><span class="icon16 icomoon-icon-file"></span>Dispatch Order</a></li>
                        <li><a href="manage_pending_order.php"><span class="icon16 icomoon-icon-file"></span>Pending Order</a></li>
                         <li><a href="cancel_order.php"><span class="icon16 icomoon-icon-file"></span>Cancel Order</a></li>
                        <li><a href="manage_banner.php"><span class="icon16 icomoon-icon-file"></span>Manage Banner</a></li>
                        <li><a href="manage_notification.php"><span class="icon16 icomoon-icon-file"></span>Manage Notification</a></li>
                        <li><a href="setting.php"><span class="icon16 icomoon-icon-file"></span>Setting</a></li>
                    </ul>
                </div>
            </div><!-- End sidenav -->
        </div><!-- End #sidebar -->
