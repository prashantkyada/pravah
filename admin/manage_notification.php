<?php
include("header.php");
$dateupdate = date("Y-m-d");
define("GOOGLE_API_KEY", 'BE0TElKEjjjyRpVI3sk36A9HufWk2AtDT4khHsmqPpFFVKfbTmaJy6-PMeQioYmp3BjM4GYnciedUHqz6KmJdZE');

$file_path = 'http://'.$_SERVER['SERVER_NAME'].dirname( $_SERVER['REQUEST_URI'] ) . '/';

//generic php function to send GCM push notification
function sendPushNotificationToGCM($registatoin_ids, $message) 
{
	  //Google cloud messaging GCM-API url
	  $url = 'https://fcm.googleapis.com/fcm/send';
	  $fields = array(
			  'registration_ids' => $registatoin_ids,
			  'data' => $message,
	  );
	  // Google Cloud Messaging GCM API Key
	  //define("GOOGLE_API_KEY", "AIzaSyBTSI3E8Qd4wE4M2xD1uNuMDG4HMJPC2_g");   
	  $headers = array(
			  'Authorization: key=' . GOOGLE_API_KEY,
			  'Content-Type: application/json'
	  );
	  $ch = curl_init();
	  curl_setopt($ch, CURLOPT_URL, $url);
	  curl_setopt($ch, CURLOPT_POST, true);
	  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	  curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
	  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
	  $result = curl_exec($ch);      
	  if ($result === FALSE) {
			  die('Curl failed: ' . curl_error($ch));
	  }
	  curl_close($ch);
	  return $result;
}
	

	if($_GET['action'] == 'delete')
	{
		mysqli_query($mysqli,"delete from `tbl_notification` Where `id`='".$_GET['id']."'");
		echo "<script language='javascript'>window.location='manage_notification.php?msg=deleted';</script>";
	}
	if(isset($_POST['submit']))
	{
		if($_POST['action'] == 'add')
		{	
			
			$title = mysqli_real_escape_string($mysqli,$_POST['title']);
			$noti = mysqli_real_escape_string($mysqli,$_POST['message']);
			
			if($_FILES["image"]["error"]==UPLOAD_ERROR_OK)
		   {
				$emb_tmp_name = $_FILES["image"]["tmp_name"];
				$emb_name = $_FILES["image"]["name"];	
				$emb = getuniqkey($emb_tmp_name).substr($emb_name,strrpos($emb_name,"."));
				move_uploaded_file($_FILES["image"]["tmp_name"], '../image/notification/'.$emb);
			    $source_img = "image/notification/".$emb;

			}
			
			if($_POST['noti_category'] == 1)
			{
				$user_id = implode(",",$_POST['select']);		
				foreach($_POST['select']  as $userid_row) 
				{
					$main_cat = mysqli_query($mysqli,"SELECT * FROM `tbl_user` where `id`='".$userid_row."'");
					while($find_row = mysqli_fetch_array($main_cat))
					{
						//$device_token[] = $find_row['device_token'];
						
						//one signal notification send start
						$ONESIGNAL_APP_ID = '4612585a-0e20-41d6-941f-daf4bdc8c416';
						$ONESIGNAL_REST_KEY = 'os_v2_app_iyjfqwqoeba5nfa73l2l3sgecyc6yvql3rbeh4v5hve7wib6t3qhurabr7fffq3cknhh7jvdky6vxhzzq6dvs4oamanj3laes2xcary';

						$content = array(
						  "en" => $noti                                                 
						  );


						/*$fields = array(
								'app_id' => $ONESIGNAL_APP_ID,
								'included_segments' => array('All'),
								'headings'=> array("en" => $title),
								'contents' => $content,
								'big_picture' => $file_path.$source_img
								);

							$fields = json_encode($fields);*/
						
						 $fields = [
								"app_id" => $ONESIGNAL_APP_ID,
								"included_segments" => ["Active Users"],
								"data" => ["event_id" => $event_id, "status" => "Cancelled"],
								"filters" => [
									[
										"field" => "tag",
										"key" => "mobile",
										"relation" => "=",
										"value" => $find_row['mobile_no'],
									],
								],
								"contents" => $content,
								'headings'=> array("en" => $title),
							 	'big_picture' => $file_path.$source_img
							];
						
							$fields = json_encode($fields);
							//print("\nJSON sent:\n");
							//print($fields);

							$ch = curl_init();
							curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
							curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
																	   'Authorization: Basic '.$ONESIGNAL_REST_KEY));
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
							curl_setopt($ch, CURLOPT_HEADER, FALSE);
							curl_setopt($ch, CURLOPT_POST, TRUE);
							curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
							curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

							$response = curl_exec($ch);
							curl_close($ch);
							//one signal notification end
					}
				}
				
			}
			else
			{
				$user_id = $_POST['noti_category'];
				//one signal notification send start
				$ONESIGNAL_APP_ID = '4612585a-0e20-41d6-941f-daf4bdc8c416';
				$ONESIGNAL_REST_KEY = 'os_v2_app_iyjfqwqoeba5nfa73l2l3sgecyc6yvql3rbeh4v5hve7wib6t3qhurabr7fffq3cknhh7jvdky6vxhzzq6dvs4oamanj3laes2xcary';

				$content = array(
				  "en" => $noti                                                 
				  );


				$fields = array(
						'app_id' => $ONESIGNAL_APP_ID,
						'included_segments' => array('All'),
						'headings'=> array("en" => $title),
						'contents' => $content,
					    'big_picture' => $file_path.$source_img
						);

					$fields = json_encode($fields);
					print("\nJSON sent:\n");
					print($fields);

					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
					curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
															   'Authorization: Basic '.$ONESIGNAL_REST_KEY));
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
					curl_setopt($ch, CURLOPT_HEADER, FALSE);
					curl_setopt($ch, CURLOPT_POST, TRUE);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

					$response = curl_exec($ch);
					curl_close($ch);
					//one signal notification end
			
			}
			mysqli_query($mysqli,"insert `tbl_notification` set 
			`user_id`='".$user_id."',
			`title`='".$title."',
			`image`='".$emb."',
			`message`='".$noti."',
			`user_view`='0',
			`create_date`='".$dateupdate."'
			");
			
			if(isset($device_token))
			{
				$send_device_token = array_unique($device_token);
			}
			
			if(isset($send_device_token))
			{
				foreach($send_device_token as $gcmRegID) 
				{
					//start gcm notification
					 $pushStatus = "";
					 if(isset($gcmRegID) && isset($noti)){   
					  $gcmRegIds = array($gcmRegID);
					  $message = array("message" => $noti,"title" => 'gcm_'.$title,"image" => $file_path.$source_img);
					  $pushStatus = sendPushNotificationToGCM($gcmRegIds, $message);
					}  
					//end gcm notification 
				}
			}
			
			echo "<script language='javascript'>window.location='manage_notification.php?msg=updated';</script>";
		}
	}			
?>
       
        <!--Body content-->
        <div id="content" class="clearfix">
            <div class="contentwrapper"><!--Content wrapper-->
                <div class="heading">
                    <h3>Manage Notification</h3>                    
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
                        <li class="active">Manage Notification</li>
                    </ul>
                </div><!-- End .heading-->
                <!-- Build page from here: Usual with <div class="row-fluid"></div> -->
    <?php
	if($_GET['action'] == "edit")
	{
		$btn="Edit";
		$act="edit";
		$edit_query=mysqli_query($mysqli,"select * from `tbl_notification` where id='".$_GET['id']."'");				
		$upd_row=mysqli_fetch_array($edit_query);
	}
	else
	{
		$act="add";
		$btn="Add";
	}
     if($_GET['action'] == "add" || $_GET['action'] == "edit")
	{
	?>	
     <!-- Build page from here: -->
                <div class="row">
                    <div class="col-lg-12">
                        <form class="form-horizontal seperator" id="form-validate"  method="post" role="form" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="<?php echo $act?>" />
						<input type="hidden" name="id" value="<?php echo $_GET['id']?>" />
                            <div class="form-group">
                                <label class="col-lg-2 control-label" for="fullname">Notification Category:</label>
                                <div class="col-lg-10">
                                   <select name="noti_category" id="noti_category_id" class="nostyle form-control" 
                                   onchange="callonyes_8()" style="cursor:pointer;">
                                	<option value="0">All User</option>
                                	<option value="1">Selected User</option>
                               </select>
                                </div>
                           </div><!-- End .form-group -->          
                          <div class="form-group" style="display:none;" id="multi_select">
                                <label class="col-lg-2 control-label" for="select">Multiple Users: </label>
                                <div class="col-lg-10">
                                    <select name="select[]" id="select2" class="nostyle form-control" multiple="multiple">
                                     <?php 
                                    $idproduct= "SELECT * FROM `tbl_user`";
                                    $str = mysqli_query($mysqli,$idproduct);				
                                    $select="";
                                    $issel = '';
                                    if(mysqli_num_rows($str)>0)
                                    {	
                                        while($viewrow=mysqli_fetch_array($str))
                                        {
                                            $select ="";
                                            $optsne .= "<option value='".$viewrow['id']."'".$select.">".$viewrow['mobile_no']."</option>";
                                        }
                                    }
                                     echo $optsne;
                                     ?>     
                                    </select>
                                </div>
                            </div><!-- End .form-group -->
                            <div class="form-group">
                                 <label class="col-lg-2 control-label" for="required">Title:</label>
                                <div class="col-lg-10">
                                  <input type="text" class="form-control" id="required" name="title" 
                                  value="<?php echo $upd_row['title'];?> <?php echo $read;?>"> 
                                </div>
                            </div><!-- End .form-group  -->
                             
                           <div class="form-group">
							  <label class="col-lg-2 control-label" for="username">Image:</label>
							  <div class="col-lg-10">
								<input type="file" name="image" id="image" value="">
							  </div>
							</div>
						   <!-- End .form-group -->
                           
                            <div class="form-group">
                                <label class="col-lg-2 control-label" for="username">Message:</label>
                                <div class="col-lg-10">
                                <textarea name="message" id="message_id" class="form-control"></textarea>
                               
                                </div>
                            </div><!-- End .form-group  -->
                           <div class="form-group">
                                <div class="col-lg-offset-2">
                                    <button type="submit" name="submit" class="btn btn-info marginR10 marginL10">Save changes</button>
                                    <button type="reset" class="btn btn-danger" onclick="javascript:window.location='manage_notification.php';">Cancel</button>
                                </div>
                            </div><!-- End .form-group  -->
    					   </form>
                      
                    </div><!-- End .span12 -->

                </div><!-- End .row -->
			<?php
            }
			else
			{
            ?>
               <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default gradient">
                                <div class="panel-heading">
                                  <h4 style="text-align: center;">
										<button class="btn btn-info" onclick="javascript:window.location='manage_notification.php?action=add';">
											Send Notification
										</button>    
                                    </h4>
                                </div>
                                <div class="panel-body noPad clearfix">
                                    <table cellpadding="0" cellspacing="0" border="0" class="dynamicTable display table table-bordered" width="100%">
                                        <thead>
                                            <tr>
                                               <th>Id</th>
                                                <th>Title</th>
                                                <th>Image</th>
                                                <th>Message</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                         <tbody>
                                        <?php
										$query = mysqli_query($mysqli,"select * from `tbl_notification` where user_id='0' 
										ORDER BY `id` DESC");
										while($row=mysqli_fetch_array($query))
										{
										 ?>
                                            <tr class="odd gradeX">
                                               <td><?php echo $row['id']; ?></td>
                                               <td><?php echo $row['title']; ?></td>
                                               <td>
                                                <?php
												if($row['image'] !='')
												{
												?>
                                                <a href="../image/notification/<?php echo $row['image']; ?>" rel="prettyPhoto">
                                                <img src="../image/notification/<?php echo $row['image']; ?>"
												class='img-thumbnail' alt='No Image' width='60' height='60'/>
                                                </a>
                                                <?php
												}
												else
												{
												?>
												 <img src="http://placehold.it/60x60" alt="" class="image marginR10"/>
												<?php
												}
												?>
												</td>
                                                 <td><?php echo $row['message'];?></td>
                                                 <td><?php echo $row['create_date']; ?></td>
                                                
                                                <td class="controls center">
                                               <a class="tip" href="manage_notification.php?action=delete&amp;id=<?php echo $row['id'];?>" onClick="return confirmdelete();"
                                               title="Delete task" data-hasqtip="true" aria-describedby="qtip-7">
                                               <span class="icon12 icomoon-icon-remove"></span>
                                               </a>
                                               </td>
                                            </tr>
                                           <?php
										   }
										   ?> 
                                          </tbody>
                                            <tfoot>
                                            <tr>
                                              	<th>Id</th>
                                               	<th>Title</th>
                                               	<th>Image</th>
                                                <th>Message</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                            </tr>   
                                        </tfoot> 
                                    </table>
                                </div>
                            </div><!-- End .panel -->
                        </div><!-- End .span12 -->
                    </div><!-- End .row -->
				<!-- Page end here -->
          <?php
		  }
		  mysqli_close($mysqli);
		  ?>  
            </div><!-- End contentwrapper -->
        </div><!-- End #content -->
    </div><!-- End #wrapper -->
	<script language="javascript">
	function callonsubmit()
	{
		if(document.getElementById("message_id").value=="" || document.getElementById("message_id").value == null)
		{
			alert("Please enter message.");
			document.getElementById("message_id").focus();
			return false;
		}
		return true					
	}
	function callonyes_8()
	{
		var noti_category_id = $("#noti_category_id").val();
		if(noti_category_id == '1')	
		{
			$("#multi_select").css("display","");	
		}
		else
		{
			$("#multi_select").css("display","none");	
		}
	}
	function confirmdelete()
	{
		var agree=confirm("Would you like to continue?");
		if(agree)	
			return true ;
		else
			return false ;
	}
	
 </script>
    <!-- Le javascript-->
    <link href="css/pop.css" rel='stylesheet'  type='text/css'/>
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
	<script type="text/javascript" src="plugins/forms/wizard/jquery.form.wizard.js"></script>
    <script type="text/javascript" src="plugins/forms/wizard/jquery.bbq.js"></script>
    <script type="text/javascript" src="plugins/forms/wizard/jquery.form.js"></script>
    <script type="text/javascript" src="plugins/forms/typeahead/typeahead.min.js"></script>
    
    <!-- Gallery plugins -->
    <script type="text/javascript" src="plugins/gallery/lazy-load/jquery.lazyload.min.js"></script>
    <script type="text/javascript" src="plugins/gallery/jpages/jPages.min.js"></script>
    <script type="text/javascript" src="plugins/gallery/pretty-photo/jquery.prettyPhoto.js"></script>

    <!-- Table plugins -->
    <script type="text/javascript" src="plugins/tables/dataTables/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="plugins/tables/dataTables/TableTools.min.js"></script>
    <script type="text/javascript" src="plugins/tables/dataTables/ZeroClipboard.js"></script>
    <script type="text/javascript" src="plugins/tables/responsive-tables/responsive-tables.js"></script>
    <!-- Make tables responsive -->
    
    <!-- Init plugins -->
    <script type="text/javascript" src="js/main.js"></script><!-- Core js functions -->
    <script type="text/javascript" src="js/datatable.js"></script><!-- Init plugins only for page -->
    <script type="text/javascript" src="js/forms.js"></script><!-- Init plugins only for page -->
    <script type="text/javascript" src="js/widgets.js"></script><!-- Init plugins only for page -->
    
    </body>
</html>
