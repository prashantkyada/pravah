<?php
include("../mpdf.php");

include("../../../include/conf.php");

$startdt = $_GET['start_dt'];
$enddt  = $_GET['end_dt'];
$user_id  = $_GET['user_id'];
$dateupdate = date("d-m-Y");


$payuorder = mysqli_query($mysqli, "SELECT SUM(total_price) as total_price FROM tbl_order where user_id='".$user_id."' AND create_date>= '".$startdt."' AND create_date <= '".$enddt."'");
$payu_rows = mysqli_fetch_array($payuorder);


 $html ='
 <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <!--Body content-->

        <div id="content" class="clearfix">

            <div class="contentwrapper"><!--Content wrapper-->
				<div>
				<h4>
					<div>
					Lalit R. Sojitra
					</div>
					<div>
					Mo.98983 52023
					</div>
				</h4> 	
				</div>

                <div class="heading" style="text-align: center;">

                    <h1>Mahalaxmi Design</h1>                    

                </div><!-- End .heading-->

                <!-- Build page from here: Usual with <div class="row-fluid"></div> -->


               <div class="row">

                        <div class="col-lg-12">

                            <div class="panel panel-default gradient">

                               <div class="panel-heading">

                                     <h4>
                                      
									   <div style="text-align;">
										   <span>Date :'.$dateupdate.'</br></span>
                                       </div>
									  </h4> 

				 			  </div>     

                                <div class="panel-body noPad clearfix">

                                    <table cellpadding="2" cellspacing="0" border="1" class="display table table-bordered" width="100%">

                                        <thead>
                                            <tr>
                                                <th>Number</th>
												<th>Date</th>
                                                <th>Mobile no.</th>
                                                <th>User Name</th>
												<th>Product Id</th>
                                                <th>Price</th>
                                            </tr>

                                        </thead>

                                        

                                         <tbody>';
											$i=0;
											$query = mysqli_query($mysqli,"select orde.*,ord.total_price,ord.payment_type,ord.create_date,us.name,us.mobile_no from `tbl_order` ord, `tbl_order_detail` orde,`tbl_user` us where orde.order_id=ord.id and us.id=ord.user_id and
											ord.create_date>= '".$startdt."' and ord.create_date <= '".$enddt."' and
											ord.user_id='".$user_id."'");
											while($row=mysqli_fetch_array($query))
											{
												$i++;
												
											 $html.='

												<tr class="odd gradeX">
												  <td>'.$i.'</td>
												  <td>'.date_format (new DateTime($row["create_date"]), "d-m-Y").'</td>
												   <td>
												     <a href="https://api.whatsapp.com/send/?phone='.$row['mobile_no'].'>&text=Welcome%20Mahalaxmi%20design%20Join:%20https://chat.whatsapp.com/Jos5D6Lg3OFEci6ErkAm6B%20Join:%20Telegram%20link%20https://t.me/joinchat/V0nUCiacQtZ8WaDi&app_absent=0" target="_blank">'.$row['mobile_no'].'</a></td>
												   <td>'.$row["name"].'</td>
												   <td>'.$row["product_id"].'</td>
												   <td>'.$row["price"].'</td>
												</tr>
											';

											}

											$html .='

                                          </tbody>
										 <tfoot>
                                            <tr>
                                                <th colspan="5" style="text-align: right;">Total Price</th>
                                                <th>'.$payu_rows["total_price"].'</th>
											</tr>
										 </tfoot>	
                                            
                                    </table>

                                </div>

							<div>
							<h4>
								<div>Note : Deposit the payment from 1st to 5th.</div> 
								<div>9898352023 Phone pay Google pay</div>
							</h4>
							</div>

                            </div><!-- End .panel -->

							

                        </div><!-- End .span12 -->



                    </div><!-- End .row -->

				<!-- Page end here -->
				

		

            </div><!-- End contentwrapper -->

        </div><!-- End #content -->

    </div><!-- End #wrapper -->



   

';

												

ob_clean(); // cleaning the buffer before Output()



$mpdf=new mPDF(); 

$mpdf->WriteHTML($html);

$mpdf->Output(); exit;



?>