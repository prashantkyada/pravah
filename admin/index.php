<?php
include("../include/conf.php");
if($_SESSION['islogged'] == "1")
{
	echo "<script language='javascript'>window.location='dashboard.php';</script>";
}		
if(isset($_POST['submit']))
{

	$check_qry=mysqli_query($mysqli,"select * from `admin_master` where username='".$_POST['username']."' and password='".$_POST['password']."'");
	while($check_row=mysqli_fetch_array($check_qry))
	{
		$_SESSION['islogged']="1";
		$_SESSION['admin_id']=$check_row['id'];
		$_SESSION['username']=$check_row['username'];
		$_SESSION['paging']="10";
		echo "<script language='javascript'>window.location='dashboard.php';</script>";
	}
	if($_SESSION['islogged'] !="1")
	{
	?>
		<script language="JavaScript" type="text/javascript">	
			alert("Password you entered is wrong, Please try again.");
			window.location='index.php';
		</script>
	<?php
		die;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <title>Welcome to Admin pannel</title>
    <meta name="author" content="Dabster Solution" />
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="application-name" content="Dabster admin template" />

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <!-- Force IE9 to render in normla mode -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- Le styles -->
    <link href="css/bootstrap/bootstrap.css" rel="stylesheet" />
    <link href="css/bootstrap/bootstrap-responsive.css" rel="stylesheet" />
    <link href="css/supr-theme/jquery.ui.supr.css" rel="stylesheet" type="text/css"/>
    <link href="css/icons.css" rel="stylesheet" type="text/css" />
    <link href="plugins/forms/uniform/uniform.default.css" type="text/css" rel="stylesheet" />

    <!-- Main stylesheets -->
    <link href="css/main.css" rel="stylesheet" type="text/css" /> 

    <!--[if IE 8]><link href="css/ie8.css" rel="stylesheet" type="text/css" /><![endif]-->
    
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script type="text/javascript" src="js/libs/excanvas.min.js"></script>
      <script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
      <script type="text/javascript" src="js/libs/respond.min.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="../images/60.png" />
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="images/apple-touch-icon-144-precomposed.png" />
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="images/apple-touch-icon-114-precomposed.png" />
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="images/apple-touch-icon-72-precomposed.png" />
    <link rel="apple-touch-icon-precomposed" href="images/apple-touch-icon-57-precomposed.png" />

    <script type="text/javascript" src="js/libs/modernizr.js"></script>

    </head>

    <body class="loginPage">

    <div class="container">

        <div id="header">

            <div class="row">

                <div class="navbar">
                    <div class="container">
                        <a class="navbar-brand" href="#">&nbsp;<span class="slogan">Welcome to Admin Pannel</span></a>
                    </div>
                </div><!-- /navbar -->

            </div><!-- End .row -->

        </div><!-- End #header -->

    </div><!-- End .container -->    

    <div class="container">

        <div class="loginContainer">
            <form class="form-horizontal" method="post" action="index.php" id="loginForm" >
                <div class="form-group">
                    <label class="col-lg-12 control-label" for="username">Username:</label>
                    <div class="col-lg-12">
                        <input id="username" type="text" name="username" class="form-control" value="" placeholder="Enter your username ...">
                        <span class="icon16 icomoon-icon-user right gray marginR10"></span>
                    </div>
                </div><!-- End .form-group  -->
                <div class="form-group">
                    <label class="col-lg-12 control-label" for="password">Password:</label>
                    <div class="col-lg-12">
                        <input id="password" type="password" name="password" value="" class="form-control">
                        <span class="icon16 icomoon-icon-lock right gray marginR10"></span>
                        <span class="forgot help-block"><a href="#">Forgot your password?</a></span>
                    </div>
                </div><!-- End .form-group  -->
                <div class="form-group">
                    <div class="col-lg-12 clearfix form-actions">
                        <div class="checkbox left">
                            <label><input type="checkbox" id="keepLoged" value="Value" class="styled" name="logged" /> Keep me logged in</label>
                        </div>
                        <button type="submit" class="btn btn-info right" name="submit" id="loginBtn"><span class="icon16 icomoon-icon-enter white"></span> Login</button>                        
                    </div>
                </div><!-- End .form-group  -->
            </form>
        </div>

    </div><!-- End .container -->

    <!-- Le javascript
    ================================================== -->
    <script  type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/bootstrap/bootstrap.js"></script>  
    <script type="text/javascript" src="plugins/forms/validate/jquery.validate.min.js"></script>
    <script type="text/javascript" src="plugins/forms/uniform/jquery.uniform.min.js"></script>

     <script type="text/javascript">
        // document ready function
        $(document).ready(function() {
            //------------- Options for Supr - admin tempalte -------------//
            var supr_Options = {
                rtl:false//activate rtl version with true
            }
            //rtl version
            if(supr_Options.rtl) {
                localStorage.setItem('rtl', 1);
                $('#bootstrap').attr('href', 'css/bootstrap/bootstrap.rtl.min.css');
                $('#bootstrap-responsive').attr('href', 'css/bootstrap/bootstrap-responsive.rtl.min.css');
                $('body').addClass('rtl');
                $('#sidebar').attr('id', 'sidebar-right');
                $('#sidebarbg').attr('id', 'sidebarbg-right');
                $('.collapseBtn').addClass('rightbar').removeClass('leftbar');
                $('#content').attr('id', 'content-one')
            } else {localStorage.setItem('rtl', 0);}
            
            $("input, textarea, select").not('.nostyle').uniform();
            $("#loginForm").validate({
                rules: {
                    username: {
                        required: true,
                       // minlength: 4
                    },
                    password: {
                        required: true,
                       // minlength: 6
                    }  
                },
                messages: {
                    username: {
                        required: "Fill me please",
                       // minlength: "My name is bigger"
                    },
                    password: {
                        required: "Please provide a password",
                       // minlength: "My password is more that 6 chars"
                    }
					
                } 
				
            });
        });
    </script> 
    </body>
</html>
