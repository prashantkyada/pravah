<?php
include("header.php");
$check_date = date("Y/m/d");

$language = mysqli_query($mysqli, "SELECT * FROM tbl_user");
$language_rows = mysqli_num_rows($language);

$result = mysqli_query($mysqli, "SELECT * FROM tbl_category");
$num_rows = mysqli_num_rows($result);

/*$sub_cat_result = mysqli_query($mysqli, "SELECT * FROM tbl_sub_category");
$subcat_num_rows = mysqli_num_rows($sub_cat_result);*/

$product_result = mysqli_query($mysqli, "SELECT * FROM tbl_product");
$product_num_rows = mysqli_num_rows($product_result);

$order_result = mysqli_query($mysqli, "SELECT * FROM tbl_order");
$order_num_rows = mysqli_num_rows($order_result);

$dispach_order_result = mysqli_query($mysqli, "SELECT * FROM tbl_dispatch_order WHERE status = 2");
$dispach_order_num_rows = mysqli_num_rows($dispach_order_result);

$pending_order_result = mysqli_query($mysqli, "SELECT COUNT(*) AS pending_order_count
FROM (
	SELECT 
		o.order_id
	FROM 
		tbl_order o
	JOIN 
		tbl_user u ON o.user_id = u.id  
	JOIN 
		tbl_order_detail od ON o.order_id = od.order_id  
	LEFT JOIN 
		(
			SELECT 
				order_id, 
				product_id, 
				product_dtid, 
				SUM(quantity) AS total_dispatched_quantity,
				SUM(box_carton) AS total_dispatched_box_carton
			FROM 
				tbl_dispatch_detail
			GROUP BY 
				order_id, product_id, product_dtid
		) dd 
		ON od.order_id = dd.order_id 
		AND od.product_id = dd.product_id 
		AND od.product_dtid = dd.product_dtid

	GROUP BY 
		o.order_id
	HAVING 
		(SUM(od.quantity) - COALESCE(SUM(dd.total_dispatched_quantity), 0)) > 0
		OR (SUM(od.box_carton) - COALESCE(SUM(dd.total_dispatched_box_carton), 0)) > 0
) AS pending_orders");
$row = mysqli_fetch_assoc($pending_order_result);

$cancel_order_result = mysqli_query($mysqli, "SELECT * FROM tbl_dispatch_order WHERE status = 4");
$cancel_order_num_rows = mysqli_num_rows($cancel_order_result);
?>
	<!--Body content-->
        <div id="content" class="clearfix">
            <div class="contentwrapper"><!--Content wrapper-->
                <div class="heading">
                    <h3>Dashboard</h3>                    
                    <div class="resBtnSearch">
                        <a href="#"><span class="icon16 icomoon-icon-search-3"></span></a>
                    </div>
                    <ul class="breadcrumb">
                        <li>You are here:</li>
                        <li>
                            <a href="#" class="tip" title="back to dashboard">
                                <span class="icon16 icomoon-icon-screen-2"></span>
                            </a> 
                            <span class="divider">
                                <span class="icon16 icomoon-icon-arrow-right-3"></span>
                            </span>
                        </li>
                        <li class="active">Dashboard</li>
                    </ul>
             </div><!-- End .heading-->       
            <!-- Build page from here: -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="centerContent">
                            <ul class="bigBtnIcon">
                               <li>
                                    <a href="manage_user.php" title="Language" class="tipB">
                                        <span class="icon icomoon-icon-lanyrd"></span>
                                        <span class="txt">Users</span>
                                        <span class="notification"><?php echo $language_rows;?></span>
                                    </a>
                                </li>
                                <li>
                                    <a href="manage_category.php" title="Main Category" class="tipB">
                                        <span class="icon icomoon-icon-list-2"></span>
                                        <span class="txt">Main Category</span>
                                        <span class="notification"><?php echo $num_rows;?></span>
                                    </a>
                                </li>
                                <!--<li>
                                    <a href="manage_sub_category.php" title="Sub Category" class="tipB">
                                        <span class="icon icomoon-icon-list-2"></span>
                                        <span class="txt">Sub Category</span>
                                        <span class="notification"><?php echo $subcat_num_rows;?></span>
                                    </a>
                                </li>-->
                                <li>
                                    <a href="change_manage_product.php" title="Images" class="tipB">
                                        <span class="icon icomoon-icon-images "></span>
                                        <span class="txt">Product</span>
                                        <span class="notification blue"><?php echo $product_num_rows;?></span>
                                    </a>
                                </li>
                                 <li>
                                    <a href="manage_order.php" title="Images" class="tipB">
                                        <span class="icon icomoon-icon-images "></span>
                                        <span class="txt">Order</span>
                                        <span class="notification blue"><?php echo $order_num_rows;?></span>
                                    </a>
                                </li>
                                 <li>
                                    <a href="dispatch_order.php" title="Images" class="tipB">
                                        <span class="icon icomoon-icon-images "></span>
                                        <span class="txt">Dispatch Order</span>
                                        <span class="notification blue"><?php echo $dispach_order_num_rows;?></span>
                                    </a>
                                </li>
                                 <li>
                                    <a href="manage_pending_order.php" title="Images" class="tipB">
                                        <span class="icon icomoon-icon-images "></span>
                                        <span class="txt">Pending Order</span>
                                        <span class="notification blue"><?php echo $row['pending_order_count'];?></span>
                                    </a>
                                </li>
                                 <li>
                                    <a href="cancel_order.php" title="Images" class="tipB">
                                        <span class="icon icomoon-icon-images "></span>
                                        <span class="txt">Cancel Order</span>
                                        <span class="notification blue"><?php echo $cancel_order_num_rows;?></span>
                                    </a>
                                </li>
                               
                            </ul>
                        </div>
                    </div><!-- End .span8 -->
                 </div><!-- End .span8 -->  
     <!-- Build page from here: Usual with <div class="row-fluid"></div> -->
                
 	<!-- Le javascript -->
    <!-- Important plugins put in all pages -->
    <script  type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript" src="js/bootstrap/bootstrap.js"></script>  
    <script type="text/javascript" src="js/jquery.mousewheel.js"></script>
    <script type="text/javascript" src="js/libs/jRespond.min.js"></script>

    <!-- Charts plugins -->
    <script type="text/javascript" src="plugins/charts/sparkline/jquery.sparkline.min.js"></script><!-- Sparkline plugin -->
   
    <!-- Misc plugins -->
    <script type="text/javascript" src="plugins/misc/nicescroll/jquery.nicescroll.min.js"></script>
    <script type="text/javascript" src="plugins/misc/qtip/jquery.qtip.min.js"></script><!-- Custom tooltip plugin -->
    <script type="text/javascript" src="plugins/misc/totop/jquery.ui.totop.min.js"></script> 

    <!-- Search plugin -->
    <script type="text/javascript" src="plugins/misc/search/tipuesearch_set.js"></script>
    <script type="text/javascript" src="plugins/misc/search/tipuesearch_data.js"></script><!-- JSON for searched results -->
    <script type="text/javascript" src="plugins/misc/search/tipuesearch.js"></script>
    
     <!-- Gallery plugins -->
    <script type="text/javascript" src="plugins/gallery/lazy-load/jquery.lazyload.min.js"></script>
    <script type="text/javascript" src="plugins/gallery/jpages/jPages.min.js"></script>
    <script type="text/javascript" src="plugins/gallery/pretty-photo/jquery.prettyPhoto.js"></script>
    
    <!-- Form plugins -->
    <script type="text/javascript" src="plugins/forms/elastic/jquery.elastic.js"></script>
    <script type="text/javascript" src="plugins/forms/inputlimiter/jquery.inputlimiter.1.3.min.js"></script>
    <script type="text/javascript" src="plugins/forms/maskedinput/jquery.maskedinput-1.3.min.js"></script>
    <script type="text/javascript" src="plugins/forms/togglebutton/jquery.toggle.buttons.js"></script>
    <script type="text/javascript" src="plugins/forms/uniform/jquery.uniform.min.js"></script>
    <script type="text/javascript" src="plugins/forms/globalize/globalize.js"></script>
    <script type="text/javascript" src="plugins/forms/color-picker/colorpicker.js"></script>
    <script type="text/javascript" src="plugins/forms/timeentry/jquery.timeentry.min.js"></script>
    <script type="text/javascript" src="plugins/forms/select/select2.min.js"></script>
    <script type="text/javascript" src="plugins/forms/dualselect/jquery.dualListBox-1.3.min.js"></script>
    <script type="text/javascript" src="plugins/forms/tiny_mce/tinymce.min.js"></script>
    <script type="text/javascript" src="js/supr-theme/jquery-ui-timepicker-addon.js"></script>
    <script type="text/javascript" src="js/supr-theme/jquery-ui-sliderAccess.js"></script>
    
    <!-- Table plugins -->
    <script type="text/javascript" src="plugins/tables/dataTables/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="plugins/tables/dataTables/TableTools.min.js"></script>
    <script type="text/javascript" src="plugins/tables/dataTables/ZeroClipboard.js"></script>
    <script type="text/javascript" src="plugins/tables/responsive-tables/responsive-tables.js"></script><!-- Make tables responsive -->

    <!-- Init plugins -->
    <script type="text/javascript" src="js/main.js"></script><!-- Core js functions -->
    <script type="text/javascript" src="js/datatable.js"></script><!-- Init plugins only for page -->
    <script type="text/javascript" src="js/forms.js"></script><!-- Init plugins only for page -->
    
    </body>
</html>