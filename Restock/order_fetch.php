<?php

//order_fetch.php

include('connections/connection.php');
function fill_brand_list($db, $category_id)
{
	$query = "SELECT * FROM brand WHERE brand_status = 'active' AND category_id = '".$category_id."' ORDER BY brand_name ASC";
	$statement = $db->query($query);
	$output = '<option value="">Select Brand</option>';
	foreach($statement as $row)
	{
		$output .= '<option value="'.$row["brand_id"].'">'.$row["brand_name"].'</option>';
	}
	return $output;
}
if ($_POST['btn_action']=='fetch_single') {
	# code...
	$product_id = $_POST['product_id'];
	$query =  "SELECT * FROM product WHERE product_id = $product_id";
	$statement = $db->query($query);
	foreach ($statement as $row) {
		# code...
		$output['category_id'] = $row['category_id'];
		$output['brand_id'] = $row['brand_id'];
		$output["brand_select_box"] = fill_brand_list($db, $row["category_id"]);
		$output['product_name'] = $row['product_name'];
		$output['product_description'] = $row['product_description'];
		$output['product_quantity'] = $row['product_quantity'];
		$output['product_unit'] = $row['product_unit'];

		$output['product_base_price'] = $row['product_base_price'];
		$output['product_tax'] = $row['product_tax'];
	}
	echo $output;

}
if ($_POST['btn_action']== 'Edit') {
	# code...
				$category_id = $_POST['category_id'];
				$brand_id	= $_POST['brand_id'];
				$product_name = $_POST['product_name'];
				$product_description = $_POST['product_description'];
				$product_quantity =	$_POST['product_quantity'];
				$product_unit =	$_POS['product_unit'];
				$product_base_price	= $_POST['product_base_price'];
				$product_tax = $_POT['product_tax'];
				$product_id	= $_PST['product_id'];
				$query1="INSERT INTO `Restock`(`restock_id`, `product_id`, `category_id`, `brand_id`, `product_name`, `product_quantity`, `Avl_Bal`, `product_enter_by`, `date`) VALUES ('','$product_id','$category_id','$brand_id','$product_name','$','$product_quantity','','')";
				$query_Statement=$db->query($query1);
				$query = "UPDATE product set category_id = $category_id, brand_id = $brand_id, product_name = $product_name, product_description = $product_description, product_quantity = $product_quantity, product_unit = $product_unit, product_base_price = $product_base_price, product_tax = $product_tax WHERE product_id = $product_id";
				$statement = $db->query($query);
				if ($statement->num_rows){
					echo "Done";
				}
				else {
					# code...
					echo "not done";
				}
}

?>