<?php
include("../include/conf.php");

if($_POST['get'] == 'delete')
{
	$getid = $_POST['id'];
	$chk_val = $_POST['chk_val'];
	$getid = explode("|",$getid);
	$getidcount = count($getid);

	echo "------------";
	if (isset($_POST['id'])) {
		for ($i = 0; $i < $getidcount; $i++) {
			$update_query = "delete from `tbl_wallpaper` Where `id`='".$getid[$i]."'";
			mysqli_query($mysqli, $update_query);
		}
	}
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["remove_id"])) {
    $id = intval($_POST["remove_id"]); // Sanitize input

    if ($id > 0) {
        $query = "DELETE FROM `tbl_product_detail` WHERE `id` = $id";
        if (mysqli_query($mysqli, $query)) {
            echo "success"; // Response back to JavaScript
        } else {
            echo "error";
        }
    } else {
        echo "invalid";
    }
} else {
    echo "invalid request";
}

if($_POST['get'] == 'data')
{

	$sel = $_POST['sel'];

	$category_id = $_POST['category_id'];
	?>

	  <select name="sub_category_id" id="sub_category_id" class="nostyle form-control" style="cursor:pointer;">
		<?php 

		$idproduct= "select * from `tbl_sub_category` where `category_id`='".$category_id."' and `status`='1'";

		$str = mysqli_query($mysqli,$idproduct);				

		$select="";

		$issel = '';

		if(mysqli_num_rows($str)>0)

		{	

			while($viewrow=mysqli_fetch_array($str))

			{

				if($sel == $viewrow['id']) 

				{

					$select = "selected";

					$issel = 'yes';

				}else

					$select ="";

					$optsne .= "<option value='".$viewrow['id']."'".$select." >".$viewrow['category_name']."</option>";

			}

		}

		if($issel == '')
			$optsne = '<option value="" selected>--Select--</option>'.$optsne;
		else
			$optsne = '<option value="" >--Select--</option>'.$optsne;
		 echo $optsne;

		 ?>     

	  </select>

	  <?php
}

	?>

							 